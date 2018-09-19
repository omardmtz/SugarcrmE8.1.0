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
 * This dependency set the commit_stage to the correct value and to read only when the sales stage
 * is Closed Won (include) or Closed Lost (exclude)
 */
$dependencies['Opportunities']['commit_stage_readonly_set_value'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('sales_stage'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'ReadOnly', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'commit_stage',
                'label' => 'commit_stage_label', //normally <field>_label
                'value' => 'isForecastClosed($sales_stage)', //Formula
            ),
        ),
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'commit_stage',
                'label' => 'commit_stage_label', //normally <field>_label
                'value' => 'ifElse(isForecastClosedWon($sales_stage), "include",
                ifElse(isForecastClosedLost($sales_stage), "exclude", $commit_stage))', //Formula
            ),
        )
    )
);

$dependencies['Opportunities']['set_base_rate'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('sales_stage'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'base_rate',
                'label' => 'base_rate_lable', //normally <field>_label
                // if this formula changes, make sure to update it in modules/Opportunities/include/OpportunityWithRevenueLineItem.php
                'value' => 'ifElse(isForecastClosed($sales_stage), $base_rate, currencyRate($currency_id))', //Formula
            ),
        )
    )
);

/**
 * This dependency set the best and worst values to equal likely when the sales stage is
 * set to closed won.
 */
$dependencies['Opportunities']['best_worst_sales_stage_read_only'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('sales_stage'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'ReadOnly', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'best_case',
                'label' => 'best_case_label', //normally <field>_label
                'value' => 'isForecastClosed($sales_stage)', //Formula
            ),
        ),
        array(
            'name' => 'ReadOnly', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'worst_case',
                'label' => 'worst_case_label', //normally <field>_label
                'value' => 'isForecastClosed($sales_stage)', //Formula
            ),
        ),
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'best_case',
                'label' => 'best_case_label',
                'value' => 'ifElse(isForecastClosed($sales_stage), $amount, $best_case)',
            ),
        ),
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'worst_case',
                'label' => 'worst_case_label',
                'value' => 'ifElse(isForecastClosed($sales_stage), $amount, $worst_case)',
            ),
        ),
    )
);

$dependencies['Opportunities']['likely_case_copy_when_closed'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('amount'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'best_case',
                'label' => 'best_case_label',
                'value' => 'ifElse(isForecastClosed($sales_stage), $amount, $best_case)',
            ),
        ),
        array(
            'name' => 'SetValue', //Action type
            //The parameters passed in depend on the action type
            'params' => array(
                'target' => 'worst_case',
                'label' => 'worst_case_label',
                'value' => 'ifElse(isForecastClosed($sales_stage), $amount, $worst_case)',
            ),
        ),
    )
);
