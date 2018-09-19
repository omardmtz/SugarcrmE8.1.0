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

class SugarUpgradeForecastWorksheetHasDraftRecord extends UpgradeScript
{
    public $order = 2210;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from 6.7 but before 7.0
        if (!version_compare($this->from_version, '6.7.0', '>=') ||
            !version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        // we need to anything other than ENT and ULT
        if (!$this->fromFlavor('pro')) {
            return;
        }

        $this->log('Creating Forecast Worksheet Draft Records');

        $sql = "SELECT '' as id, " .
                      "fw.name, " .
                      "fw.date_entered, " .
                      "fw.date_modified, " .
                      "fw.modified_user_id, " .
                      "fw.created_by, " .
                      "fw.description, " .
                      "fw.deleted, " .
                      "fw.assigned_user_id, " .
                      "fw.team_id, " .
                      "fw.team_set_id, " .
                      "fw.parent_id, " .
                      "fw.parent_type, " .
                      "fw.likely_case, " .
                      "fw.best_case, " .
                      "fw.worst_case, " .
                      "fw.base_rate, " .
                      "fw.currency_id, " .
                      "fw.date_closed, " .
                      "fw.date_closed_timestamp, " .
                      "fw.sales_stage, " .
                      "fw.probability, " .
                      "fw.commit_stage, " .
                      "1 as draft, " .
                      "fw.opportunity_id, " .
                      "fw.opportunity_name, " .
                      "fw.account_name, " .
                      "fw.account_id, " .
                      "fw.campaign_id, " .
                      "fw.campaign_name, " .
                      "fw.product_template_id, " .
                      "fw.product_template_name, " .
                      "fw.category_id, " .
                      "fw.category_name, " .
                      "fw.sales_status, " .
                      "fw.next_step, " .
                      "fw.lead_source, " .
                      "fw.product_type, " .
                      "fw.list_price, " .
                      "fw.cost_price, " .
                      "fw.discount_price, " .
                      "fw.discount_amount, " .
                      "fw.quantity, " .
                      "fw.total_amount " .
              "FROM forecast_worksheets fw " .
              "LEFT JOIN forecast_worksheets fw2 " .
              "ON fw.parent_type = fw2.parent_type " .
                  "AND fw.parent_id = fw2.parent_id " .
                  "AND fw2.draft = 1 " .
              "WHERE fw.deleted = 0 " .
                  "AND fw.draft = 0 " .
                  "AND fw2.id IS NULL";

        $results = $this->db->query($sql);

        $insertSQL = 'INSERT INTO forecast_worksheets ';

        /* @var $fw ForecastWorksheet */
        $fw = BeanFactory::newBean('ForecastWorksheets');

        while ($row = $this->db->fetchByAssoc($results)) {
            $row['id'] = create_guid();
            foreach ($row as $key => $value) {
                $fieldDefs = $fw->getFieldDefinition($key);
                $convertedValue = $this->db->fromConvert($value, $this->db->getFieldType($fieldDefs));
                $row[$key] = $this->db->massageValue($convertedValue, $fieldDefs);
            }

            $this->db->query($insertSQL . '(' . join(',', array_keys($row)) . ') VALUES (' . join(',', $row) . ');');
        }

        $this->log('Done Creating Forecast Worksheet Draft Records');
    }
}
