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
 * Purpose: When going from Pro -> Ent, to select all RLI records and move them over
 * to the forecast_worksheets table as Pro uses Opps for the worksheets table. This
 * causes the RLIs to show up on the table instead of the Opps (or nothing)
 *
 * Class SugarUpgradeRevenueLineItemCreateForecastWorksheetRecords
 */
class SugarUpgradeRevenueLineItemCreateForecastWorksheetRecords extends UpgradeScript
{
    public $order = 2120;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we going to 7.6 or newer?
        // if we are and we are not using RLI's this can be skipped
        $settings = Opportunity::getSettings();
        if (version_compare($this->to_version, '7.6', '>=') && $settings['opps_view_by'] !== 'RevenueLineItems') {
            $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
            return;
        }

        $q = "SELECT '' as id,
                     rli.name,
                     rli.id as parent_id,
                     'RevenueLineItems' as parent_type,
                     1 as draft
                FROM revenue_line_items rli
                LEFT JOIN forecast_worksheets fw
                ON rli.id = fw.parent_id AND fw.parent_type = 'RevenueLineItems'
                WHERE fw.id IS NULL";

        $this->log('Running Select SQL: ' . $q);
        $r = $this->db->query($q);

        $this->log('Found ' . $this->db->getRowCount($r) . ' RLIs to add to ForecastWorksheets');

        $this->insertRows($r);
    }
    /**
     * Process all the results and insert them back into the db
     *
     * @param resource $results
     */
    protected function insertRows($results)
    {
        $insertSQL = "INSERT INTO forecast_worksheets (
                        id,
                        name,
                        parent_id,
                        parent_type,
                        draft) values";

        /* @var $fw ForecastWorksheets */
        $fw = BeanFactory::newBean('ForecastWorksheets');

        while ($row = $this->db->fetchByAssoc($results)) {
            $row['id'] = create_guid();
            foreach ($row as $key => $value) {
                $row[$key] = $this->db->massageValue($value, $fw->getFieldDefinition($key));
            }

            $q = $insertSQL . ' (' . join(',', $row) . ');';

            $this->db->query($q);
        };
    }
}
