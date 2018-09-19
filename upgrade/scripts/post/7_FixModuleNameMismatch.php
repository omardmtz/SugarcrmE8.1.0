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
 * Module names in $app_list_strings and $mod_strings can be different in pre 7.7. 
 */
class SugarUpgradeFixModuleNameMismatch extends UpgradeScript
{
    // BR-3995 Fix: This script needs to run after 7_MergeDropDowns
    // to retain custom module names
    public $order = 7950;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.7', '>=')) {
            // only need to run this upgrading for versions lower than 7.7
            return;
        }

        $languageDefault = $GLOBALS['sugar_config']['default_language'];
        $languages = get_languages();
        $header = file_get_contents('modules/ModuleBuilder/MB/header.php');

        foreach ($languages as $langKey => $langName) {
            $GLOBALS['sugar_config']['default_language'] = $langKey;
            //get list strings for this language
            $appStrings = return_app_list_strings_language($langKey, false);
            $GLOBALS['sugar_config']['default_language'] = $languageDefault;

            if (empty($appStrings['moduleList'])) {
                // broken language file
                $this->log("Bad language file for $langKey, skipping");
                continue;
            }

            foreach ($appStrings['moduleList'] as $moduleId => $moduleName) {
                $langFiles = array('custom/modules/'.$moduleId.'/language/'.$langKey.'.lang.php',
                    'custom/Extension/modules/'.$moduleId.'/Ext/Language/'.$langKey.'.lang.php',
                    'custom/modules/'.$moduleId.'/Ext/Language/'.$langKey.'.lang.ext.php');

                foreach ($langFiles as $langFile) {
                    if (file_exists($langFile)) {
                        $mod_strings = array();
                        require $langFile;
    
                        if (isset($mod_strings['LBL_MODULE_NAME']) && $moduleName != $mod_strings['LBL_MODULE_NAME']) {
                            $mod_strings['LBL_MODULE_NAME'] = $moduleName;
                            write_array_to_file_as_key_value_pair('mod_strings', $mod_strings, $langFile, 'w', $header);
                            $this->log("Fixed module name mismatch for module: $moduleId in file: $langFile");
                        }
                    }
                }

                $subTitleKey = 'LBL_'.strtoupper($moduleId).'_SUBPANEL_TITLE';
                $lblModuleKey = 'LBL_'.strtoupper($moduleId);

                foreach (glob('custom/modules/*/language/'.$langKey.'.lang.php') as $langFile) {
                    $mod_strings = array();
                    require $langFile;
                    $fixed = false;

                    if (isset($mod_strings[$lblModuleKey]) && $moduleName != $mod_strings[$lblModuleKey]) {
                        $mod_strings[$lblModuleKey] = $moduleName;
                        $fixed = true;
                    }

                    if (isset($mod_strings[$subTitleKey]) && $moduleName != $mod_strings[$subTitleKey]) {
                        $mod_strings[$subTitleKey] = $moduleName;
                        $fixed = true;
                    }

                    if ($fixed) {
                        write_array_to_file_as_key_value_pair('mod_strings', $mod_strings, $langFile, 'w', $header);
                        $this->log("Fixed subpanel title for module: $moduleId in file: $langFile");
                    }
                }
            }
        }
    }
}
