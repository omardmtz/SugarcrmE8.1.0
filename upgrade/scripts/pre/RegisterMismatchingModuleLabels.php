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
 * Store mismatching module labels before the instance has been upgraded
 */
class SugarUpgradeRegisterMismatchingModuleLabels extends UpgradeScript
{
    public $type = self::UPGRADE_CUSTOM;
    public $order = 400;

    public function run()
    {
        global $sugar_config, $current_language, $beanList;

        if (version_compare($this->from_version, '7.0.0', '>=')) {
            return;
        }

        $original_current_language = $current_language;
        $languages = $sugar_config['languages'];

        $labels = array();
        foreach ($languages as $language => $_) {
            if (!file_exists("include/language/{$language}.lang.php")) {
                continue;
            }

            $app_list_strings = array();
            include "include/language/{$language}.lang.php";

            foreach ($beanList as $module => $_) {
                $this->registerLabel($language, $module, $app_list_strings, $labels);
            }
        }

        $current_language = $original_current_language;

        $this->upgrader->state['mismatching_labels'] = $labels;
    }

    protected function registerLabel($language, $module, $default_app_list_strings, array &$labels)
    {
        global $current_language;
        $current_language = $language;

        if (!file_exists("modules/{$module}/language/{$language}.lang.php")) {
            return;
        }

        // check if the module label is defined in module list
        $app_list_strings = return_app_list_strings_language($language);
        if (empty($app_list_strings['moduleList'][$module])) {
            return;
        }

        // this is what the module label looks like before upgrade
        $list_module_name = $app_list_strings['moduleList'][$module];

        // this is what the module label would look like after upgrade if we didn't have this script
        $lbl_module_name = translate('LBL_MODULE_NAME', $module);
        if (strcmp($lbl_module_name, 'Module Name') == 0) {
            return;
        }

        // check if there's a mismatch in translations
        if (strcmp($lbl_module_name, $list_module_name) == 0) {
            return;
        }

        $mod_strings = array();
        include FileLoader::validateFilePath("modules/{$module}/language/{$language}.lang.php");

        // check if either of the label in module list and LBL_MODULE_NAME is customized
        if ((empty($default_app_list_strings['moduleList'][$module])
                || strcmp($default_app_list_strings['moduleList'][$module], $list_module_name) == 0)
            && (empty($mod_strings['LBL_MODULE_NAME'])
                || strcmp($mod_strings['LBL_MODULE_NAME'], $lbl_module_name) == 0)
        ) {
            return;
        }

        $labels[$language][$module] = $list_module_name;
    }
}
