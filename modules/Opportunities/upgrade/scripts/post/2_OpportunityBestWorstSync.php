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

class SugarUpgradeOpportunityBestWorstSync extends UpgradeScript
{
    public $order = 2110;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from anything before 6.7?
        if (!version_compare($this->from_version, '6.7.0', '<')) {
            return;
        }

        // are we going to 7.0 or higher
        if (!version_compare($this->to_version, '7.0', '>=')) {
            return;
        }

        $this->log('Syncing Opportunity Amount to Best Case if it\'s empty or null');
        $sql = "UPDATE opportunities
                SET    best_case = amount
                WHERE  (COALESCE({$this->db->convert('best_case', 'length')}, 0) = 0)";
        $this->db->query($sql);

        $this->log('Syncing Opportunity Amount to Worst Case if it\'s empty or null');
        $sql = "UPDATE opportunities
                SET    worst_case = amount
                WHERE  (COALESCE({$this->db->convert('worst_case', 'length')}, 0) = 0)";
        $this->db->query($sql);

        $this->log('Done Syncing Opportunity Best and Worst Case with Amount Field');
    }
}
