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
 * Lets make sure that the ProjectTask module is also in the $moduleList
 */
class SugarUpgradeProjectTaskInModuleList extends UpgradeScript
{
    public $order = 6999;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $path = 'custom/Extension/application/Ext';
        $file_name = 'project_unhide.php';
        if (version_compare($this->to_version, '7.2.2.0', '=') && file_exists($path . '/Include/' . $file_name)) {
            $file_contents = '
<?php
// WARNING: The contents of this file are auto-generated.

$moduleList[] = \'Project\';
$moduleList[] = \'ProjectTask\';

if (isset($modInvisList) && is_array($modInvisList)) {
    foreach($modInvisList as $key => $mod) {
        if($mod == \'Project\' || $mod == \'ProjectTask\') {
            unset($modInvisList[$key]);
        }
    }
}
';
            // enable the project module in the upgrade instance
            global $moduleList, $modInvisList;
            $moduleList[] = 'ProjectTask';
            foreach ($modInvisList as $key => $mod) {
                if ($mod == 'Project' || $mod == 'ProjectTask') {
                    unset($modInvisList[$key]);
                }
            }

            sugar_file_put_contents($path . '/Include/' . $file_name, $file_contents);
        }
    }
}
