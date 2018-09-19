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

class SugarUpgradeOpportunityWithRevenueLineItems extends UpgradeScript
{
    public $order = 2050;
    public $version = "7.6.0.0";

    protected $validFlavors = array('ent', 'ult');

    public function __construct($upgrader)
    {
        $this->type = self::UPGRADE_CUSTOM | self::UPGRADE_DB;
        parent::__construct($upgrader);
    }

    public function run()
    {
        // if we are not going to ent or ult, we need to kick out
        if (!in_array(strtolower($this->to_flavor), $this->validFlavors)) {
            return;
        }
        // to run this we must be coming any version of 7 before 7.6
        if (version_compare($this->from_version, '7.0', '>=') && version_compare($this->from_version, '7.6', '<')) {
            SugarAutoLoader::load('modules/Opportunities/include/OpportunityWithRevenueLineItem.php');
            SugarAutoLoader::load('modules/ModuleBuilder/Module/StudioModuleFactory.php');

            // clear out the studio module cache cause it will be wrong!
            StudioModuleFactory::clearModuleCache('Opportunities');
            // in the upgrade, we only want to do the metadata conversion
            $converter = new OpportunityWithRevenueLineItem();
            $converter->setIsUpgrade(true);
            $converter->doMetadataConvert();

            // just on the off chance that the formula got put into a custom file, we need to make sure it contains
            // the new hotness
            $this->fixRollupFormulas();

            $admin = BeanFactory::newBean('Administration');
            $admin->saveSetting('Opportunities', 'opps_view_by', 'RevenueLineItems', 'base');
            Opportunity::getSettings(true);
        }
    }

    protected function fixRollupFormulas()
    {
        $oldFormula = 'rollupCurrencySum($revenuelineitems, "{{field}}")';
        $newFormula = 'rollupConditionalSum($revenuelineitems, "{{field}}", "sales_stage", forecastSalesStages(true, false))';

        // the field set we need
        $fields = array(
            'best_case' => 'best_case',
            'amount' => 'likely_case',
            'worst_case' => 'worst_case'
        );

        // get the get_widget helper and the StandardField Helper
        SugarAutoLoader::load('modules/DynamicFields/FieldCases.php');
        SugarAutoLoader::load('modules/ModuleBuilder/parsers/StandardField.php');

        // we are working with opportunities
        $bean = BeanFactory::newBean('Opportunities');

        // loop over each field
        foreach ($fields as $field => $rollup_field) {
            // get the field defs
            $field_defs = $bean->getFieldDefinition($field);
            // load the field type up
            $f = get_widget($field_defs['type']);

            // populate the row from the vardefs that were loaded
            $f->populateFromRow($field_defs);

            if ($f->formula == str_replace('{{field}}', $rollup_field, $oldFormula)) {
                $f->formula = str_replace('{{field}}', $rollup_field, $newFormula);
                // now lets save, since these are OOB field, we use StandardField
                $df = new StandardField($bean->module_name);
                $df->setup($bean);
                $f->module = $bean;
                $f->save($df);
            }
        }

        // lets fix up the data now to excluded closed lost
        $this->fixRollupAmountsToExcludeClosedLostValues();
    }

    protected function fixRollupAmountsToExcludeClosedLostValues()
    {
        $sql = "SELECT opportunity_id               AS opp_id,
                          Sum(likely_case)             AS likely,
                          Sum(worst_case)              AS worst,
                          Sum(best_case)               AS best
                   FROM   (SELECT rli.opportunity_id,
                                  CASE
                                    WHEN rli.sales_stage = 'Closed Lost' THEN 0
                                    ELSE (rli.likely_case/rli.base_rate)
                                  END AS likely_case,
                                  CASE
                                    WHEN rli.sales_stage = 'Closed Lost' THEN 0
                                    ELSE (rli.worst_case/rli.base_rate)
                                  END AS worst_case,
                                  CASE
                                    WHEN rli.sales_stage = 'Closed Lost' THEN 0
                                    ELSE (rli.best_case/rli.base_rate)
                                  END AS best_case
                           FROM   revenue_line_items rli
                           WHERE  rli.deleted = 0) t
                   GROUP  BY t.opportunity_id";

        $results = $this->db->query($sql);

        $sql = "UPDATE opportunities SET
                        amount=(%f*base_rate),
                        best_case=(%f*base_rate),
                        worst_case=(%f*base_rate)
                    WHERE id = '%s' and total_revenue_line_items != closed_revenue_line_items";
        while ($row = $this->db->fetchRow($results)) {
            $this->db->query(
                sprintf(
                    $sql,
                    $row['likely'],
                    $row['best'],
                    $row['worst'],
                    $row['opp_id']
                )
            );
        }
    }
}
