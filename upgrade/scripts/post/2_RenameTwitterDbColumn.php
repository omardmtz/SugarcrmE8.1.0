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
 * Change db column name from 'twitter_id' to 'twitter' when upgrading from 6.7.5+ to 7
 */
class SugarUpgradeRenameTwitterDbColumn extends UpgradeScript
{
    public $order = 2000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // must be upgrading from 6.7.5+ to 7. mysql is the only db available to 6.7
        if (!version_compare($this->from_version, '6.7.4', '>') || !version_compare($this->from_version, '7.0.0', '<') || $this->db->getScriptName() != 'mysql') {
            return;
        }

        global $moduleList;

        foreach ($moduleList as $module) {
            $focus = BeanFactory::newBean($module);
                
            if (!empty($focus) && (($focus instanceOf Company) || ($focus instanceOf Person)) && !empty($focus->table_name)
                    && $focus->table_name != 'users' && $focus->table_name != 'styleguide') { // there are exceptions, eg, Employees, Styleguide
                if ($this->db->query("alter table `{$focus->table_name}` change `twitter_id` `twitter` varchar(100) NULL")) {
                    $this->log("Changed column name 'twitter_id' to 'twitter' for table: {$focus->table_name}");
                }
                else {
                    $this->log("Failed to change column name 'twitter_id' to 'twitter' for table: {$focus->table_name}");
                }
            }
        }
    }
}
