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

class SugarUpgradeRevenueLineItemAccountSync extends UpgradeScript
{
    public $order = 2180;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from anything before 7.0?
        if (!version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        // are we going to 7.6 or newer?
        // if we are and we are not using RLI's this can be skipped
        $settings = Opportunity::getSettings();
        if (version_compare($this->to_version, '7.6', '>=') && $settings['opps_view_by'] !== 'RevenueLineItems') {
            $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
            return;
        }

        $this->log('Syncing Accounts to RLI Table');

        $sql = "UPDATE revenue_line_items rli
               SET account_id = (SELECT ac.account_id
                                 FROM accounts_opportunities ac
                                 WHERE ac.opportunity_id = rli.opportunity_id and ac.deleted = 0)
               WHERE rli.account_id IS NULL or rli.account_id = ''";

        $r = $this->db->query($sql);
        $this->log('SQL Ran, Updated ' . $this->db->getAffectedRowCount($r) . ' Rows');

        $this->log('Done Syncing Accounts to RLI Table');
    }
}
