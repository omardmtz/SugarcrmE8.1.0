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

class SugarWidgetFieldVarchar extends SugarWidgetReportField
{
    public function queryFilterEquals($layout_def)
 {
		return $this->_get_column_select($layout_def)."='".$GLOBALS['db']->quote($layout_def['input_name0'])."'\n";
 }

    public function queryFilterNot_Equals_Str($layout_def)
 {
        $field_name = $this->_get_column_select($layout_def);
        $input_name0 = $GLOBALS['db']->quote($layout_def['input_name0']);
        return "{$field_name} != '{$input_name0}' OR ({$field_name} IS NULL)\n";
 }

 function queryFilterContains(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '%".$GLOBALS['db']->quote($layout_def['input_name0'])."%'\n";
 }
  function queryFilterdoes_not_contain(&$layout_def)
 {
        $field_name = $this->_get_column_select($layout_def);
        $input_name0 = $GLOBALS['db']->quote($layout_def['input_name0']);
        return "{$field_name} NOT LIKE '%{$input_name0}%' OR ({$field_name} IS NULL)\n";
 }

 function queryFilterStarts_With(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '".$GLOBALS['db']->quote($layout_def['input_name0'])."%'\n";
 }

 function queryFilterEnds_With(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '%".$GLOBALS['db']->quote($layout_def['input_name0'])."'\n";
 }
 
    public function queryFilterone_of($layout_def)
 {
    foreach($layout_def['input_name0'] as $key => $value) {
        $layout_def['input_name0'][$key] = $GLOBALS['db']->quote($value); 
    }
    return $this->_get_column_select($layout_def) . " IN ('" . implode("','", $layout_def['input_name0']) . "')\n";
 }
  
    public function displayInput($layout_def)
 {
 		$str = '<input type="text" size="20" value="' . $layout_def['input_name0'] . '" name="' . $layout_def['name'] . '">';
 		return $str;
 }
}
