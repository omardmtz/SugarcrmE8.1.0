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

class SugarWidgetFieldFloat extends SugarWidgetFieldInt
{
 function displayList($layout_def)
 {
 	
    $vardef = $this->getVardef($layout_def);

    if ( isset($vardef['precision']) ) {
        $precision = $vardef['precision'];
    } else {
        $precision = null;
    }
	return format_number(parent::displayListPlain($layout_def), $precision, $precision);
 }

 function displayListPlain($layout_def)
 {
     return $this->displayList($layout_def);
 }
 function queryFilterEquals(&$layout_def)
 {	
    return $this->_get_column_select($layout_def)."= ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }
                                                                                 
 function queryFilterNot_Equals(&$layout_def)
 {
        $field_name = $this->_get_column_select($layout_def);
        $input_name0 = $GLOBALS['db']->quote(unformat_number($layout_def['input_name0']));
        return "{$field_name} != {$input_name0} OR ({$field_name} IS NULL)\n";
 }
                                                                                 
 function queryFilterGreater(&$layout_def)
 {
    return $this->_get_column_select($layout_def)." > ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }
                                                                                 
 function queryFilterLess(&$layout_def)
 {
	return $this->_get_column_select($layout_def)." < ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }

    public function queryFilterGreater_Equal(&$layout_def)
    {
        return $this->_get_column_select($layout_def) . " >= " . $GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])) . "\n";
    }

    public function queryFilterLess_Equal(&$layout_def)
    {
        return $this->_get_column_select($layout_def) . " <= " . $GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])) . "\n";
    }

 function queryFilterBetween(&$layout_def)
 {
	return $this->_get_column_select($layout_def)." BETWEEN ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])). " AND " . $GLOBALS['db']->quote(unformat_number($layout_def['input_name1'])) . "\n";
 }


}
