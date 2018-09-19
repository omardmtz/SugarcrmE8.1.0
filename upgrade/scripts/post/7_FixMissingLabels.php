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
* Class SugarUpgradeFixMissingLabels
*
* Add missing labels for custom modules
*/
class SugarUpgradeFixMissingLabels extends UpgradeScript
{
    public $order = 7400;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2';

    public function run()
    {
        if (!version_compare($this->from_version, '7.0', '<')) {
            // only need to run this upgrading from pre 7.0 versions
            return;
        }

        // get singular module names
        $my_list_strings = return_app_list_strings_language($GLOBALS['current_language']);
        $moduleSingular = $my_list_strings['moduleListSingular'];

        // get custom modules
        $customModules = array();
        $customFiles = glob(
            'modules' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . '*_sugar.php',
            GLOB_NOSORT
        );

        // file header
        $header = file_get_contents('modules/ModuleBuilder/MB/header.php');

        // iterate custom modules
        foreach ($customFiles as $customFile) {
            $moduleName = str_replace('_sugar', '', pathinfo($customFile, PATHINFO_FILENAME));
            $modulePath = pathinfo($customFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . 'language';
            $langFiles = glob(
                $modulePath . DIRECTORY_SEPARATOR . '*.lang.php',
                GLOB_NOSORT
            );
            // iterate language file
            foreach ($langFiles as $langFile) {
                // add LBL_MODULE_NAME_SINGULAR if not already there
                unset($mod_strings);
                include($langFile);
                if (isset($mod_strings) && !isset($mod_strings['LBL_MODULE_NAME_SINGULAR']) && isset($moduleSingular[$moduleName])) {
                    $mod_strings['LBL_MODULE_NAME_SINGULAR'] = $moduleSingular[$moduleName];
                    write_array_to_file('mod_strings', $mod_strings, $langFile, 'w', $header);
                }
            }
        }
    }
}

