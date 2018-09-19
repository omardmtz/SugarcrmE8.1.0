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

class SugarUpgradeOpportunitySyncToForecastWorksheet extends UpgradeScript
{
    public $order = 2190;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from anything before 7.0?
        if (!version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        $settings = Opportunity::getSettings();
        if ($settings['opps_view_by'] !== 'Opportunities') {
            $this->log('Not using Opportunities; Skipping Upgrade Script');
            return;
        }

        $this->log('Updating Opportunity Rows in Forecast Worksheet');

        $fields = array(
            'name',
            array('opportunity_name' => 'name'),
            array('opportunity_id' => 'id'),
            'account_id',
            'account_name',
            array('likely_case' => 'amount'),
            'best_case',
            'base_rate',
            'worst_case',
            'currency_id',
            'date_closed',
            'date_closed_timestamp',
            'sales_stage',
            'probability',
            'commit_stage',
            'assigned_user_id',
            'created_by',
            'date_entered',
            'team_id',
            'team_set_id',
            'sales_status',
            'description',
            'next_step',
            'lead_source',
            array('product_type' => 'opportunity_type'),
            'campaign_id',
            'campaign_name'
        );

        $sqlSet = "%s=(SELECT %s from opportunities o WHERE o.id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')";

        $sqlSetArray = array();

        foreach ($fields as $field) {
            $key = $field;
            if (is_array($field)) {
                reset($field);
                $key = key($field);
                $field = array_shift($field);
            }

            switch ($field) {
                case 'account_name':
                    $sqlSetArray[] = sprintf(
                        "%s = (SELECT DISTINCT a.name FROM accounts a INNER JOIN accounts_opportunities ac on
                        ac.account_id = a.id and ac.deleted = 0 WHERE
                        ac.opportunity_id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')",
                        $field
                    );
                    break;
                case 'account_id':
                    $sqlSetArray[] = sprintf(
                        "%s = (SELECT DISTINCT a.id FROM accounts a INNER JOIN accounts_opportunities ac on
                        ac.account_id = a.id and ac.deleted = 0 WHERE
                        ac.opportunity_id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')",
                        $field
                    );
                    break;
                case 'campaign_name':
                    $sqlSetArray[] = sprintf(
                        "%s = (SELECT DISTINCT c.name FROM campaigns c INNER JOIN opportunities o on
                            o.campaign_id = c.id WHERE o.id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')",
                        $field
                    );
                    break;
                case 'campaign_id':
                    $sqlSetArray[] = sprintf(
                        "%s = (SELECT DISTINCT c.id FROM campaigns c INNER JOIN opportunities o on
                            o.campaign_id = c.id WHERE o.id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')",
                        $field
                    );
                    break;
                default:
                    $sqlSetArray[] = sprintf($sqlSet, $key, $field);
                    break;
            }
        }

        $sql = "update forecast_worksheets SET " . join(",", $sqlSetArray) . "
          where exists (SELECT * from opportunities o WHERE o.id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')";

        $r = $this->db->query($sql);

        $this->log('SQL Ran, Updated ' . $this->db->getAffectedRowCount($r) . ' Rows');

        $sql_delete = "update forecast_worksheets SET deleted = 1 WHERE exists
                (SELECT * from opportunities o WHERE o.deleted = 1 and
                  o.id = forecast_worksheets.parent_id and forecast_worksheets.parent_type = 'Opportunities')";
        $this->db->query($sql_delete);
    }
}
