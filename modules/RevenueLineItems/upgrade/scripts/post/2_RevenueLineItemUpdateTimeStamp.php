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

class SugarUpgradeRevenueLineItemUpdateTimeStamp extends UpgradeScript
{
    public $order = 2180;
    public $type = self::UPGRADE_DB;

    public function run()
    {

        // if we are coming from 7.6, don't run this again.
        if (version_compare($this->from_version, '7.6', '>=')) {
            $this->log('Timestamps already added; Skipping Upgrade Script');
            return;
        }

        // are we going to 7.6 or newer?
        // if we are and we are not using RLI's this can be skipped
        $settings = Opportunity::getSettings();
        if (version_compare($this->to_version, '7.6', '>=') && $settings['opps_view_by'] !== 'RevenueLineItems') {
            $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
            return;
        }

        $this->log('Updating Revenue Line Items TimeStamp fields');
        $sql = "select id, date_closed from revenue_line_items where deleted = 0";
        $results = $this->db->query($sql);

        $updateSql = "UPDATE revenue_line_items SET date_closed_timestamp = '%d' where id = '%s'";
        while ($row = $this->db->fetchRow($results)) {
            $this->db->query(
                sprintf(
                    $updateSql,
                    strtotime($row['date_closed']),
                    $row['id']
                )
            );
        }

        $this->log('Done Updating Revenue Line Items TimeStamp fields');
    }
}
