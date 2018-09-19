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

class SugarUpgradeOpportunityFixSalesStageDefault extends UpgradeScript
{
    public $order = 6500;
    public $version = "7.5.0.0";
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // this always needs to be ran

        // get the get_widget helper and the StandardField Helper
        require_once('modules/DynamicFields/FieldCases.php');

        // we are working with opportunities
        $bean = BeanFactory::newBean('Opportunities');

        // get the field defs
        $field_defs = $bean->getFieldDefinition('sales_stage');
        // load the field type up
        $f = get_widget($field_defs['type']);
        // populate the row from the vardefs that were loaded
        $f->populateFromRow($field_defs);

        $this->log('Current Sales Stage Default is: ' . var_export($f->default, true));

        // lets always make sure that the default is in the list of options
        if (isset($f->options) && isset($GLOBALS['app_list_strings'][$f->options])) {
            if (!in_array($f->default, array_keys($GLOBALS['app_list_strings'][$f->options]))) {
                $this->log(var_export($f->default, true) . ' Is Not In The List Of Options');
                $options = $GLOBALS['app_list_strings'][$f->options];
                reset($options);
                $f->default = $f->default_value = key($options);
                $this->log('New Sales Stage Default Is: ' . var_export($f->default, true));

                // save the changes to the field
                $df = new StandardField($bean->module_name);
                $df->setup($bean);
                $f->module = $bean;
                $f->save($df);
            }
        }
    }
}
