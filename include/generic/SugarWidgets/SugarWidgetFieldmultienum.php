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


class SugarWidgetFieldMultiEnum extends SugarWidgetFieldEnum {
    public function queryFilternot_one_of($layout_def)
    {
		$arr = array ();
		foreach ($layout_def['input_name0'] as $value) {
			array_push($arr, "'".$GLOBALS['db']->quote($value)."'");
		}
	    $reporter = $this->layout_manager->getAttribute("reporter");

    	$col_name = $this->_get_column_select($layout_def) . " NOT LIKE " ;
    	$arr_count = count($arr);
    	$query = "";
    	foreach($arr as $key=>$val) {
    		$query .= $col_name;
            $value = preg_replace("/^'/", "'%^", $val, 1);
            $value = preg_replace("/'$/", "^%'", $value, 1);
			$query .= $value;
			if ($key != ($arr_count - 1))
    			$query.= " AND " ;
    	}
		return '('.$query.')';        
	}
        
    public function queryFilterone_of($layout_def)
    {
		$arr = array ();
		foreach ($layout_def['input_name0'] as $value) {
			array_push($arr, "'".$GLOBALS['db']->quote($value)."'");
		}
	    $reporter = $this->layout_manager->getAttribute("reporter");

    	$col_name = $this->_get_column_select($layout_def) . " LIKE " ;
    	$arr_count = count($arr);
    	$query = "";
    	foreach($arr as $key=>$val) {
    		$query .= $col_name;
            $value = preg_replace("/^'/", "'%^", $val, 1);
            $value = preg_replace("/'$/", "^%'", $value, 1);
			$query .= $value;
			if ($key != ($arr_count - 1))
    			$query.= " OR " ;	
    	}
		return '('.$query.')';        
	}

	public function queryFilteris($layout_def) {
		$input_name0 = $layout_def['input_name0'];
		if (is_array($layout_def['input_name0'])) {
			$input_name0 = $layout_def['input_name0'][0];
		}

		// Bug 40022
		// IS filter doesn't add the carets (^) to multienum custom field values  
		$input_name0 = $this->encodeMultienumCustom($layout_def, $input_name0);
		
		return $this->_get_column_select($layout_def)." = ".$this->reporter->db->quoted($input_name0)."\n";
	}

	public function queryFilteris_not($layout_def) {
		$input_name0 = $layout_def['input_name0'];
		if (is_array($layout_def['input_name0'])) {
			$input_name0 = $layout_def['input_name0'][0];
		}

		// Bug 50549
		// IS NOT filter doesn't add the carets (^) to multienum custom field values  
		$input_name0 = $this->encodeMultienumCustom($layout_def, $input_name0);

        $field_name = $this->_get_column_select($layout_def);
        $input_name0 = $this->reporter->db->quoted($input_name0);
        return "{$field_name} <> {$input_name0} OR ({$field_name} IS NULL)\n";
	}
	
    /**
     * Returns an OrderBy query for multi-select. We treat multi-select the same as a normal field because
     * the values stored in the database are in the format ^A^,^B^,^C^ though not necessarily in that order.
     * @param  $layout_def
     * @return string
     */
    public function queryOrderBy($layout_def) {
        return SugarWidgetReportField::queryOrderBy($layout_def);
    }
    
    /**
     * Function checks if the multienum field is custom, and escapes it with carets (^) if it is
     * @param array $layout_def field layout definition
     * @param string $value value to be escaped
     * @return string
     */
    private function encodeMultienumCustom($layout_def, $value) {
    	$field_def = $this->reporter->getFieldDefFromLayoutDef($layout_def);
    	// Check if it is a custom field
		if (!empty($field_def['source']) && ($field_def['source'] == 'custom_fields' || ($field_def['source'] == 'non-db' && !empty($field_def['ext2']) && !empty($field_def['id']))) && !empty($field_def['real_table']))
		{
			$value = encodeMultienumValue(array($value)); 
		}
		return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function _get_column_select($layout_def)
    {
        $column = parent::_get_column_select($layout_def);
        return $this->reporter->db->convert($column, 'text2char');
    }
}
?>
