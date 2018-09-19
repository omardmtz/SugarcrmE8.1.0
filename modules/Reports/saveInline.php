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

$module = $_REQUEST['save_module'];
$record = $_REQUEST['save_record'];
$field_value = $_REQUEST['save_value'];
$field = $_REQUEST['save_field_name'];
$type = $_REQUEST['type'];

$bean = BeanFactory::getBean($module, $record);
if ($type != 'currency')
    $bean->$field = $field_value;
else {
    $bean->$field = unformat_number($field_value);
}

$bean->save(false);

$ret_array = array();
$ret_array['id'] = $record;
$ret_array['field'] = $field;
if ($type != 'currency')
    $ret_array['value'] = $bean->$field;
else {
    global $locale;
    $params = array();
    $params['currency_id'] = $_REQUEST['currency_id'];
    $params['convert'] = false;
    $params['currency_symbol'] = $_REQUEST['currency_symbol'];

    $ret_array['currency_formatted_value']  = currency_format_number($bean->$field, $params);
    $ret_array['formatted_value'] = format_number($bean->$field);
}

header("Content-Type: application/json");
$json = getJSONobj();
echo $json->encode($ret_array);
?>
