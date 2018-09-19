<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

/**
 * Class SugarUpgradeFixClassConstructor
 *
 * Fix custom module classes to be rewritten to use the proper __construct()
 * form instead of class names.
 */
class SugarUpgradeFixClassConstructor extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // Only run this when coming from a version lower than 7.2.0
        if (version_compare($this->from_version, '7.2', '>=')) {
            return;
        }

        // Find all the classes we want to convert.
        $customModules = array();
        $customFiles = glob(
            'modules' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . '*_sugar.php',
            GLOB_NOSORT
        );
        foreach ($customFiles as $customFile) {
            $moduleName = str_replace('_sugar', '', pathinfo($customFile, PATHINFO_FILENAME));
            $customModules[] = $moduleName;
        }
        $customModules = array_flip($customModules);

        // Go through all the modules using the ModuleBuilder
        $mB = new ModuleBuilder();
        $mB->getPackages();
        foreach ($mB->packages as $package) {
            $this->log("FixClassConstructor: Found a custom package {$package->name}");
            foreach ($package->modules as $mbModule) {
                if (!isset($customModules[$mbModule->key_name])) {
                    continue;
                }
                $this->replaceCustomModuleClasses($mbModule);
                unset($customModules[$mbModule->key_name]);
            }
        }
        $customModules = array_flip($customModules);

        // Treat modules that have not been found by the ModuleBuilder
        foreach ($customModules as $moduleName) {
            $this->log("FixClassConstructor: Found a custom module {$moduleName} not recognized by ModuleBuilder");
            $this->replaceCustomModuleClassesByReflection($moduleName);
        }
    }

    /**
     * Rebuild the custom module classes so they have the the proper
     * __construct() instead of class names.
     *
     * This method uses the ModuleBuilder class to populate the class template.
     *
     * @param MBModule $mbModule ModuleBuilder Module to be replaced.
     *
     * @see MBModule::createClasses() for duplication of this code.
     * @todo refactor MBModule::createClasses() to be able to reuse code
     * (tracked by SC-2279).
     */
    private function replaceCustomModuleClasses($mbModule)
    {
        $moduleName = $mbModule->key_name;

        $parentClass = 'Basic';
        foreach ($mbModule->config['templates'] as $template => $a) {
            if ($template == 'basic') {
                continue;
            }
            $parentClass = ucFirst($template);
        }

        $mbModule->mbvardefs->updateVardefs();
        $fields = $mbModule->mbvardefs->vardefs['fields'];

        $params = array(
            'isImportable' => !empty($mbModule->config['importable']),
            'teamSecurityEnabled' => !empty($mbModule->config['team_security']),
            'acl' => !empty($mbModule->config['acl']),
        );

        $content = $this->populateClassTemplate($moduleName, $parentClass, $fields, $params);
        $this->writeCustomClass($moduleName, $content);
    }

    /**
     * Rebuild the custom module classes so they have the the proper
     * __construct() instead of class names.
     *
     * This method uses ReflectionClass to populate the class template.
     *
     * @param string $moduleName The module key name.
     */
    private function replaceCustomModuleClassesByReflection($moduleName)
    {
        $className = $moduleName . '_sugar';
        $file = $this->getModuleClassFile($moduleName);

        require_once FileLoader::validateFilePath($file);

        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            return $this->log("FixClassConstructor: Could not use ReflectionClass with {$className}");
        }

        $reflectionClass = new ReflectionClass($className);
        $parentClass = get_parent_class($className);

        $fields = array();
        // All the public properties in `Class.tpl` that are not vardefs.
        $notVardefs = array(
            'new_schema',
            'module_dir',
            'object_name',
            'table_name',
            'importable',
            'disable_row_level_security'
        );
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->class !== $className) {
                continue;
            }
            $name = $property->name;
            if (in_array($name, $notVardefs)) {
                continue;
            }
            $fields[$name] = $name;
        }

        $isImportable = false;
        $importable = $reflectionClass->getProperty('importable');
        if (!empty($importable)) {
            $isImportable = $importable->getValue(new $className);
        }

        $teamSecurityEnabled = true;
        $disable_row_level_security = $reflectionClass->getProperty('disable_row_level_security');
        if (!empty($disable_row_level_security)) {
            $teamSecurityEnabled = $disable_row_level_security->class !== $className;
        }

        $acl = false;
        $beanImplements = $reflectionClass->getMethod('bean_implements');
        if (!empty($beanImplements)) {
            $acl = $beanImplements->class === $className;
        }

        $params = array(
            'isImportable' => $isImportable,
            'teamSecurityEnabled' => $teamSecurityEnabled,
            'acl' => $acl,
        );

        $content = $this->populateClassTemplate($moduleName, $parentClass, $fields, $params);
        $this->writeCustomClass($moduleName, $content);
    }

    /**
     * Populate the class template.
     *
     * @param string $moduleName The module name.
     * @param array $parentClass The name of the extension class.
     * @param array $fields The list of fields of this module.
     * @param array $params The list of boolean parameters.
     * Expects `isImportable`, `teamSecurityEnabled` and `acl` to be defined.
     *
     * @return string The file content, ready to be written.
     */
    private function populateClassTemplate($moduleName, $parentClass, $fields, $params)
    {
        $class = array();
        $class['name'] = $moduleName;
        $class['table_name'] = strtolower($moduleName);

        $class['requires'] = array();
        if ($parentClass !== 'Basic') {
            $template = strtolower($parentClass);
            $class['requires'][] = 'include' . DIRECTORY_SEPARATOR .
                'SugarObjects' . DIRECTORY_SEPARATOR .
                'templates' . DIRECTORY_SEPARATOR .
                $template . DIRECTORY_SEPARATOR .
                $parentClass . '.php';
        }

        $class['extends'] = $parentClass;

        $class['fields'] = $fields;

        $class['team_security'] = $params['teamSecurityEnabled'];
        $class['acl'] = $params['acl'];
        $class['importable'] = $params['isImportable'];

        $smarty = new Sugar_Smarty();
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';
        $smarty->assign('class', $class);
        $content = $smarty->fetch(
            'modules' . DIRECTORY_SEPARATOR .
            'ModuleBuilder' . DIRECTORY_SEPARATOR .
            'tpls' . DIRECTORY_SEPARATOR .
            'MBModule' . DIRECTORY_SEPARATOR .
            'Class.tpl'
        );

        return $content;
    }

    /**
     * Override the existing file.
     *
     * @param string $moduleName The module name.
     * @param string $content The content of the file.
     */
    private function writeCustomClass($moduleName, $content)
    {
        //write sugar generated class
        $this->log("FixClassConstructor: Replace {$moduleName}_sugar.php for module: {$moduleName}");
        sugar_file_put_contents_atomic(
            $this->getModuleClassFile($moduleName),
            $content
        );
    }

    /**
     * Return file with a class for module.
     * @param string $moduleName
     * @return string
     */
    protected function getModuleClassFile($moduleName)
    {
        global $beanList;

        $fBeanList = array_flip($beanList);

        $className = $moduleName . '_sugar';

        $dirName = !empty($fBeanList[$moduleName]) ? $fBeanList[$moduleName] : $moduleName;

        return 'modules' . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $className . '.php';
    }
}
