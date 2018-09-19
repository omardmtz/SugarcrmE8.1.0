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

class SugarWidgetFieldEnum extends SugarWidgetReportField
{
    public function queryFilterEmpty($layout_def)
    {
        $column = $this->_get_column_select($layout_def);
        return "(coalesce(" . $this->reporter->db->convert($column, "length") . ",0) = 0 OR $column = '^^')";
    }

    public function queryFilterNot_Empty($layout_def)
    {
        $column = $this->_get_column_select($layout_def);
        return "(coalesce(" . $this->reporter->db->convert($column, "length") . ",0) > 0 AND $column != '^^' )\n";
    }

	public function queryFilteris($layout_def) {
        $input_name0 = $this->getInputValue($layout_def);
		return $this->_get_column_select($layout_def)." = ".$this->reporter->db->quoted($input_name0)."\n";
	}

	public function queryFilteris_not($layout_def) {
        $input_name0 = $this->reporter->db->quoted($this->getInputValue($layout_def));
        $field_name = $this->_get_column_select($layout_def);
        return "{$field_name} <> {$input_name0} OR ({$field_name} IS NULL AND {$input_name0} IS NOT NULL)";
	}

	public function queryFilterone_of($layout_def) {
		$arr = array ();
		foreach ($layout_def['input_name0'] as $value)
        {
            $arr[] = $this->reporter->db->quoted($value);
		}
		$str = implode(",", $arr);
		return $this->_get_column_select($layout_def)." IN (".$str.")\n";
	}

	public function queryFilternot_one_of($layout_def) {
		$arr = array ();
		foreach ($layout_def['input_name0'] as $value)
        {
            $arr[] = $this->reporter->db->quoted($value);
		}
		$str = implode(",", $arr);
		return $this->_get_column_select($layout_def)." NOT IN (".$str.")\n";
	}

    function displayList($layout_def)
    {
        if(!empty($layout_def['column_key'])){
            $field_def = $this->reporter->all_fields[$layout_def['column_key']];
        }else if(!empty($layout_def['fields'])){
            $field_def = $layout_def['fields'];
        }
        $cell = $this->displayListPlain($layout_def);
        $str = $cell;
        global $sugar_config;
        if (isset ($sugar_config['enable_inline_reports_edit']) && $sugar_config['enable_inline_reports_edit']) {
            $module = $this->reporter->all_fields[$layout_def['column_key']]['module'];
            $name = $layout_def['name'];
            $layout_def['name'] = 'id';
            $key = $this->_get_column_alias($layout_def);
            $key = strtoupper($key);

            //If the key isn't in the layout fields, skip it
            if (!empty($layout_def['fields'][$key]))
            {
                $record = $layout_def['fields'][$key];
                $field_name = $field_def['name'];
                $field_type = $field_def['type'];
                $div_id = $field_def['module'] ."&$record&$field_name";
                $str = "<div id='$div_id'>" . $cell . "&nbsp;"
                     . SugarThemeRegistry::current()->getImage(
                        "edit_inline",
                        "border='0' alt='Edit Layout' align='bottom' onClick='SUGAR.reportsInlineEdit.inlineEdit(" .
                        "\"$div_id\",\"$cell\",\"$module\",\"$record\",\"$field_name\",\"$field_type\");'"
                       )
                     . "</div>";
            }
        }
        return $str;
    }
    public function displayListPlain($layout_def)
    {
		if(!empty($layout_def['column_key'])){
			$field_def = $this->reporter->all_fields[$layout_def['column_key']];
		}else if(!empty($layout_def['fields'])){
			$field_def = $layout_def['fields'];
		}

		if (!empty($layout_def['table_key'] ) &&( empty ($field_def['fields']) || empty ($field_def['fields'][0]) || empty ($field_def['fields'][1]))){
			$value = $this->_get_list_value($layout_def);
		}else if(!empty($layout_def['name']) && !empty($layout_def['fields'])){
			$key = strtoupper($layout_def['name']);
			$value = $layout_def['fields'][$key];
		}
		$cell = '';

        $list = getOptionsFromVardef($field_def);
        if ($list && isset($list[$value])) {
            $cell = $list[$value];
        } elseif (is_array($list)) {
            // $list returned from getOptionsFromVardef could also be array containing translation for options.
            $cell = $list;
        }

        if (is_array($cell)) {

			//#22632
			$value = unencodeMultienum($value);
			$cell=array();
			foreach($value as $val){
				$returnVal = translate($field_def['options'],$field_def['module'],$val);
				if(!is_array($returnVal)){
					array_push( $cell, translate($field_def['options'],$field_def['module'],$val));
				}
			}
			$cell = implode(", ",$cell);
		}
		return $cell;
	}

	public function queryOrderBy($layout_def) {
		$field_def = $this->reporter->all_fields[$layout_def['column_key']];
		if (!empty ($field_def['sort_on'])) {
			$order_by = $layout_def['table_alias'].".".$field_def['sort_on'];
		} else {
			$order_by = $this->_get_column_select($layout_def);
		}

        $list = getOptionsFromVardef($field_def);
        if ($list === false) {
            $list = array();
        }

		if (empty ($layout_def['sort_dir']) || $layout_def['sort_dir'] == 'a') {
			$order_dir = "ASC";
		} else {
			$order_dir = "DESC";
		}
		return $this->reporter->db->orderByEnum($order_by, $list, $order_dir);
    }

    public function displayInput($layout_def) {
        global $app_list_strings;

        if(!empty($layout_def['remove_blank']) && $layout_def['remove_blank']) {
            if ( isset($layout_def['options']) &&  is_array($layout_def['options']) ) {
                $ops = $layout_def['options'];
            }
            elseif (isset($layout_def['options']) && isset($app_list_strings[$layout_def['options']])){
            	$ops = $app_list_strings[$layout_def['options']];
                if(array_key_exists('', $app_list_strings[$layout_def['options']])) {
             	   unset($ops['']);
	            }
            }
            else{
            	$ops = array();
            }
        }
        else {
            $ops = $app_list_strings[$layout_def['options']];
        }

        $str = '<select multiple="true" size="3" name="' . $layout_def['name'] . '[]">';
        $str .= get_select_options_with_id($ops, $layout_def['input_name0']);
        $str .= '</select>';
        return $str;
    }
}
