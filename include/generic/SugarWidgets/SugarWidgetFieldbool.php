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

class SugarWidgetFieldBool extends SugarWidgetReportField
{

 function queryFilterEquals(&$layout_def)
 {

		$bool_val = $layout_def['input_name0'][0];
		if ($bool_val == 'yes' || $bool_val == '1')
		{
			return "(".$this->_get_column_select($layout_def)." LIKE 'on' OR ".$this->_get_column_select($layout_def)."='1')\n";
		} else {
            return "(" . $this->_get_column_select($layout_def) . " IS NOT NULL AND " .
                $this->_get_column_select($layout_def) . "='0')\n";
		}
 }

    /**
     * Compose GROUP BY expression for boolean field
     *
     * @param array $layout_def
     * @return string
     */
    public function queryGroupBy($layout_def)
    {
        $db = $this->reporter->db;

        // explicitly cast NULL to empty value which depends on field type for proper grouping
        $alias = parent::queryGroupBy($layout_def);
        $alias = $db->convert(
            $alias,
            'IFNULL',
            array($db->emptyValue($layout_def['type']))
        );

        return $alias;
    }

    function displayListPlain($layout_def)
    {
        $value = $this->_get_list_value($layout_def);
        $name = $layout_def['name'];
        $layout_def['name'] = 'id';
        $key = $this->_get_column_alias($layout_def);
        $key = strtoupper($key);
        
        if(empty($layout_def['fields'][$key]))
        {
            $layout_def['name'] = $name;
            global $app_list_strings;
            if (empty($value)) {
                $value = $app_list_strings['dom_switch_bool']['off'];
            }   
            else {
                $value = $app_list_strings['dom_switch_bool']['on'];
            } 
            return $value;
        }

        $on_or_off = 'CHECKED';
        if ( empty($value) ||  $value == 'off')
        {
            $on_or_off = '';
        }
        $cell = "<input name='checkbox_display' class='checkbox' type='checkbox' disabled $on_or_off>";
        return  $cell;
    }
    
 function queryFilterStarts_With(&$layout_def)
 {
    return $this->queryFilterEquals($layout_def);
 }    
 
    public function displayInput($layout_def)
    {
        global $app_strings;
        
        $yes = $no = $default = '';
        if (isset($layout_def['input_name0']) && $layout_def['input_name0'] == 1) {
            $yes = ' selected="selected"';
        }
        elseif (isset($layout_def['input_name0']) && $layout_def['input_name0'] == 'off') {
            $no = ' selected="selected"';
        }
        else {
            $default = ' selected="selected"';
        }
        
        $str = <<<EOHTML
<select id="{$layout_def['name']}" name="{$layout_def['name']}">
 <option value="" {$default}></option>
 <option value = "off" {$no}> {$app_strings['LBL_SEARCH_DROPDOWN_NO']}</option>
 <option value = "1" {$yes}> {$app_strings['LBL_SEARCH_DROPDOWN_YES']}</option>
</select>
EOHTML;
        
        return $str;
    }
    

}

?>
