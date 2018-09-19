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
 * Scan all ModuleBuilder modules and look for modules with missing
 * "moduleList" and "moduleListSingular" entry.
 */
class SugarUpgradeFixSingularList extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * Workflow: Check module builder modules for "moduleList" and "moduleListSingular"
     * labels and translations. Create or append fixed content to package
     * language file to make it compatible with Studio or ModuleBuilder for future changes
     */
    public function run()
    {
        if (empty($this->upgrader->state['MBModules'])) {
            // No MB modules - nothing to do
            return;
        }

        $app_list_strings = return_app_list_strings_language("en_us");

        $changes = array();
        $packages = $this->getPackages();

        foreach ($this->upgrader->state['MBModules'] as $MBModule) {

            // All custom modules will have package key in module name
            $keys = explode('_', $MBModule);
            $packageKey = $keys[0];

            if (!isset($packages[$packageKey])) {
                $this->upgrader->log(
                    'FixSingularList: can\'t find package for module: key - ' . $packageKey .
                    '. Script will use current key as package name'
                );

                $packages[$packageKey] = $packageKey;
            }

            $changes[$packageKey] = isset($changes[$packageKey])
                ? $changes[$packageKey]
                : array();

            // Try to add custom module to moduleList
            if (!isset($app_list_strings['moduleList'][$MBModule])) {
                $langFile = $this->getLanguageFilePath($MBModule);
                if (file_exists($langFile)) {
                    $mod_strings = array();
                    require $langFile;

                    $moduleName = isset($mod_strings['LBL_MODULE_NAME']) ? $mod_strings['LBL_MODULE_NAME'] : false;

                    if ($moduleName) {
                        $app_list_strings['moduleList'][$MBModule] = $moduleName;
                        $changes[$packageKey]['moduleList'][$MBModule] = $moduleName;
                    } else {
                        $this->upgrader->log('FixSingularList: warning - module ' . $MBModule . ' do not have module name translation');
                    }
                }
            }

            if (!isset($app_list_strings['moduleListSingular'][$MBModule])
                && !empty($app_list_strings['moduleList'][$MBModule])) {
                $changes[$packageKey]['moduleListSingular'][$MBModule] = $app_list_strings['moduleList'][$MBModule];
            }
        }

        $rebuildLang = false;

        foreach ($changes as $packageKey => $content) {

            // if no changes - continue
            if (empty($content)) {
                continue;
            }

            $packageName = $packages[$packageKey];
            $fileName = $this->getPackageLangFile($packageName);

            $values = $this->mergeCustomTranslations($fileName, $content);

            $header = file_get_contents('modules/ModuleBuilder/MB/header.php');
            $file = $header;

            foreach ($values as $key => $array) {
                $file .= override_value_to_string_recursive2('app_list_strings', $key, $array);
            }

            $this->upgrader->putFile($fileName, $file);
            $rebuildLang = true;
        }

        if ($rebuildLang) {
            $mi = new ModuleInstaller();
            $mi->silent = true;
            $mi->rebuild_languages(array('en_us' => 'en_us'));
        }
    }

    /**
     * Get path to module language file
     *
     * @param $module
     * @return string
     */
    protected function getLanguageFilePath($module)
    {
        return 'modules/' . $module . '/language/en_us.lang.php';
    }

    /**
     * Return package language file
     *
     * @param $package
     * @return string
     */
    protected function getPackageLangFile($package)
    {
        return 'custom/Extension/application/Ext/Language/en_us.' . $package . '.php';
    }

    /**
     * Parse module builder folder for manifest files and return packages
     * Example of return value array('PACKAGE_KEY' => 'PACKAGE_NAME')
     *
     * @return array
     */
    protected function getPackages()
    {
        $pattern = 'custom/modulebuilder/packages/*/manifest.php';

        $packages = array();

        foreach (glob($pattern) as $manifestFile) {
            $manifest = array();
            include $manifestFile;

            if (!empty($manifest['key']) && !empty($manifest['name'])) {
                $packages[$manifest['key']] = $manifest['name'];
            }
        }

        return $packages;
    }

    /**
     * Check package language file and return merged values
     *
     * @param $filename
     * @param $content
     * @return array
     */
    protected function mergeCustomTranslations($filename, $content)
    {
        if (file_exists($filename)) {
            $app_list_strings = array();
            include $filename;

            $content = array_merge_recursive($app_list_strings, $content);
        }

        return $content;
    }
}
