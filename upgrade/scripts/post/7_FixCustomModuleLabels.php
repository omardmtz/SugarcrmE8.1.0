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

/**
 * Fix missing labels for instance custom modules
 */
class SugarUpgradeFixCustomModuleLabels extends UpgradeScript
{
    public $order = 7600;
    public $type = self::UPGRADE_CUSTOM;

    protected $missingLabels =
        array(
            'LNK_IMPORT_{module_name}',
        );

    public function run()
    {
        // Only run this when coming from a version lower than 7.2.0
        if (version_compare($this->from_version, '7.2', '>=')) {
            return;
        }

        // Find all the classes we want to convert.
        $customModules = $this->getCustomModules();

        foreach ($customModules as $moduleName) {

            $path = $this->getModuleLangPath($moduleName);

            if (file_exists($path)) {

                $mod_strings = array();
                require $path;

                $labels = $this->compileLabels($moduleName, $this->missingLabels);
                $missingLabels = array_diff($labels, array_keys($mod_strings));

                if (!empty($missingLabels)) {
                    $this->upgrader->log(
                        'FixCustomModuleLabels: Missing import labels for '
                        . $moduleName . ' module - ' . var_export($missingLabels, true)
                    );

                    $header = file_get_contents('modules/ModuleBuilder/MB/header.php');
                    $translations = $this->translateLabels($missingLabels, $mod_strings, $moduleName);

                    $this->upgrader->backupFile($path);
                    write_array_to_file('mod_strings', $translations, $path, 'w', $header);

                    $this->upgrader->log('FixCustomModuleLabels: Module ' . $moduleName . '. Saving Complete');
                }
            }
        }

        return true;
    }

    /**
     * Get path to module language file
     *
     * @param $module
     * @return string
     */
    protected function getModuleLangPath($module)
    {
        return 'modules/' . $module . '/language/en_us.lang.php';
    }

    /**
     * Get SugarCRM instance custom modules
     *
     * @return array
     */
    protected function getCustomModules()
    {
        // Find all the classes we want to convert.
        $customModules = array();
        $customFiles = glob('modules/*/*_sugar.php', GLOB_NOSORT);

        foreach ($customFiles as $customFile) {
            $moduleName = str_replace('_sugar', '', pathinfo($customFile, PATHINFO_FILENAME));
            $customModules[] = $moduleName;
        }

        return $customModules;
    }

    /**
     * Return labels for search
     *
     * @param $moduleName
     * @param string|array $labels
     * @return mixed
     */
    public function compileLabels($moduleName, $labels)
    {
        return str_replace('{module_name}', strtoupper($moduleName), $labels);
    }

    /**
     * Compile translations array.
     * Put label translation logic there
     *
     * @param array $mod_strings
     * @param string $moduleName
     * @return array
     */
    protected function compileTranslations($mod_strings, $moduleName)
    {
        return array(
                'LNK_IMPORT_' . strtoupper($moduleName) => translate('LBL_IMPORT') . " " . $mod_strings['LBL_MODULE_NAME'],
               );
    }

    /**
     * Translate missing labels for module
     *
     * @param $labels
     * @param $mod_strings
     * @param $moduleName
     * @return mixed
     */
    public function translateLabels($labels, $mod_strings, $moduleName)
    {
        $knownTranslations = $this->compileTranslations($mod_strings, $moduleName);

        foreach ($labels as $label) {
            if (!isset($knownTranslations[$label])) {
                $this->upgrader->log('FixCustomModuleLabels: unable to translate label ' . $label);
            } else {
                $mod_strings[$label] = $knownTranslations[$label];
            }
        }

        return $mod_strings;
    }
}
