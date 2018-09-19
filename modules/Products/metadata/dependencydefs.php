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
$fields = array(
    'category_name',
    'list_price',
    'cost_price',
    'tax_class',
    'mft_part_num',
    'weight'
);

$dependencies['Products']['read_only_fields'] = array(
    'hooks' => array("edit"),
    //Trigger formula for the dependency. Defaults to 'true'.
    'trigger' => 'true',
    'triggerFields' => array('product_template_id'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(),
);

foreach ($fields as $field) {
    $dependencies['Products']['read_only_fields']['actions'][] = array(
        'name' => 'ReadOnly', //Action type
        //The parameters passed in depend on the action type
        'params' => array(
            'target' => $field,
            'label' => $field . '_label', //normally <field>_label
            'value' => 'not(equal($product_template_id,""))', //Formula
        ),
    );
}
