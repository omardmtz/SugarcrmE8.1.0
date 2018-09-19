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

class SugarUpgradeOpportunityUpdateRollupFields extends UpgradeScript
{
    public $order = 3030;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from anything before 7.0?
        if (!version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        $settings = Opportunity::getSettings();
        if ($settings['opps_view_by'] !== 'RevenueLineItems') {
            $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
            return;
        }

        $this->log('Updating Opportunity Rollup Fields');

        $sql = "SELECT opportunity_id               AS opp_id,
                          Sum(likely_case)             AS likely,
                          Sum(worst_case)              AS worst,
                          Sum(best_case)               AS best,
                          Max(t.date_closed)           AS date_closed,
                          Max(t.date_closed_timestamp) AS date_closed_timestamp,
                          Count(0)                     AS total,
                          ( won + lost )               AS total_closed,
                          lost
                   FROM   (SELECT rli.opportunity_id,
                                  (rli.likely_case/rli.base_rate) as likely_case,
                                  (rli.worst_case/rli.base_rate) as worst_case,
                                  (rli.best_case/rli.base_rate) as best_case,
                                  rli.date_closed,
                                  rli.date_closed_timestamp,
                                  CASE
                                    WHEN rli.sales_stage = 'Closed Lost' THEN 1
                                    ELSE 0
                                  end AS lost,
                                  CASE
                                    WHEN rli.sales_stage = 'Closed Won' THEN 1
                                    ELSE 0
                                  end AS won
                           FROM   revenue_line_items rli
                           WHERE  rli.deleted = 0) t
                   GROUP  BY t.opportunity_id, (won + lost), lost";

        $results = $this->db->query($sql);

        $sql = "UPDATE opportunities SET
                    amount=(%f*base_rate),best_case=(%f*base_rate),worst_case=(%f*base_rate),date_closed='%s',date_closed_timestamp='%s',
                    sales_status='%s',total_revenue_line_items='%d',closed_revenue_line_items='%d' WHERE id = '%s'";
        while ($row = $this->db->fetchRow($results)) {
            $row['sales_status'] = ($row['total'] == 0 || $row['total'] > $row['total_closed']) ?
                'In Progress' : ($row['lost'] == $row['total']) ?
                    'Closed Lost' : 'Closed Won';

            $this->db->query(
                sprintf(
                    $sql,
                    $row['likely'],
                    $row['best'],
                    $row['worst'],
                    $row['date_closed'],
                    $row['date_closed_timestamp'],
                    $row['sales_status'],
                    $row['total'],
                    $row['total_closed'],
                    $row['opp_id']
                )
            );
        }

        $this->log('Done Updating Opportunity Rollup Fields');
    }
}
