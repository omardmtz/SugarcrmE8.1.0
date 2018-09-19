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
class SugarUpgradeFixBWCSubpanelEmail extends UpgradeScript
{
    public $order = 7405;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2';

    public function run()
    {
        if (!version_compare($this->from_version, '7.5.1.0', '<')) {
            // only need to run this upgrading from pre 7.5.1 versions
            return;
        }

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
            $modulePath = pathinfo($customFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . 'metadata' . DIRECTORY_SEPARATOR . 'subpanels';
            $layoutFiles = glob(
                $modulePath . DIRECTORY_SEPARATOR . '*.php',
                GLOB_NOSORT
            );
            // iterate layout file
            foreach ($layoutFiles as $layoutFile) {
                include($layoutFile);
                if (isset($subpanel_layout) && isset($subpanel_layout['list_fields'])
                    && isset($subpanel_layout['list_fields']['email1'])) {
                    $subpanel_layout['list_fields']['email'] = $subpanel_layout['list_fields']['email1'];
                    $subpanel_layout['list_fields']['email']['name'] = 'email';
                    unset($subpanel_layout['list_fields']['email1']);
                    write_array_to_file('subpanel_layout', $subpanel_layout, $layoutFile, 'w', $header);
                }
            }
        }
    }
}

