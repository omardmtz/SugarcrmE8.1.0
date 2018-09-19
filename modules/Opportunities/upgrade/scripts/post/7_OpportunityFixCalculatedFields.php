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
 * Update fields that have been modified to be calculated.
 */
class SugarUpgradeOpportunityFixCalculatedFields extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if ((!$this->toFlavor('ent') && !$this->toFlavor('ult')) || !version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        $settings = Opportunity::getSettings();
        if ($settings['opps_view_by'] !== 'RevenueLineItems') {
            $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
            return;
        }

        // get the get_widget helper and the StandardField Helper
        require_once('modules/DynamicFields/FieldCases.php');

        // we are working with opportunities
        $module = 'Opportunities';
        $bean = BeanFactory::newBean('Opportunities');

        // the field set we need
        $fields = array(
            'best_case',
            'amount',
            'worst_case',
            'date_closed'
        );

        // loop over each field
        foreach($fields as $field) {
            // get the field defs
            $field_defs = $bean->getFieldDefinition($field);
            // load the field type up
            $f = get_widget($field_defs['type']);

            // populate the row from the vardefs that were loaded
            $f->populateFromRow($field_defs);
            // lets make sure that the calculated is true
            $f->calculated = true;

            // now lets save, since these are OOB field, we use StandardField
            $df = new StandardField($module);
            $df->setup($bean);
            $f->module = $bean;
            $f->save($df);
        }
    }
}
