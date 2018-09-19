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


global $current_user;

$global_currency_obj = null;

function get_currency()
{
        global $global_currency_obj;
        if (empty($global_currency_obj))
        {
            $global_currency_obj = BeanFactory::newBean('Currencies')->getUserCurrency();
        }
        return $global_currency_obj;
}


class SugarWidgetFieldCurrency extends SugarWidgetFieldInt
{
    public function __construct(&$layout_manager)
    {
        parent::__construct($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');
    }


    public function displayList($layout_def)
    {
            global $current_user;

            if (empty($layout_def['group_function'])) {
                $currency = $this->getCurrency($layout_def);
                $symbol = $currency['currency_symbol'];
                $currency_id = $currency['currency_id'];
            } else {
                $currency = $current_user->getPreference('currency_show_preferred')
                    ? SugarCurrency::getUserLocaleCurrency()
                    : SugarCurrency::getBaseCurrency();
                $currency_id = $currency->id;
                $symbol = $currency->symbol;
            }

            $layout_def['currency_symbol'] = $symbol;
            $layout_def['currency_id'] = $currency_id;
            $display = $this->displayListPlain($layout_def);
            
        if(!empty($layout_def['column_key'])){
            $field_def = $this->reporter->all_fields[$layout_def['column_key']];
        }else if(!empty($layout_def['fields'])){
            $field_def = $layout_def['fields'];
        }
        $record = '';
        if ($layout_def['table_key'] == 'self' && isset($layout_def['fields']['PRIMARYID']))
            $record = $layout_def['fields']['PRIMARYID'];
        else if (isset($layout_def['fields'][strtoupper($layout_def['table_alias']."_id")])){
            $record = $layout_def['fields'][strtoupper($layout_def['table_alias']."_id")];
        }
        if (!empty($record)) {
	        $field_name = $layout_def['name'];
	        $field_type = $field_def['type'];
	        $module = $field_def['module'];

	        $div_id = $module ."&$record&$field_name";
	        $str = "<div id='$div_id'>".$display;
            global $sugar_config;
            if (isset ($sugar_config['enable_inline_reports_edit']) && $sugar_config['enable_inline_reports_edit']) {
                $str .= "&nbsp;" .SugarThemeRegistry::current()->getImage("edit_inline","border='0' alt='Edit Layout' align='bottom' onClick='SUGAR.reportsInlineEdit.inlineEdit(\"$div_id\",\"$value\",\"$module\",\"$record\",\"$field_name\",\"$field_type\",\"$currency_id\",\"$symbol\");'");
            }
	        $str .= "</div>";
	        return $str;
        }
        else
            return $display;
    }

    public function displayListPlain($layout_def)
    {
        $value = parent::displayListPlain($layout_def);
        $row_currency = $this->getCurrency($layout_def);
        $format_id = $row_currency['currency_id'];

        global $current_user;
        // when the group by function is empty, and we should show the user prefered currency, it should convert it
        if (empty($layout_def['group_function']) && $current_user->getPreference('currency_show_preferred')) {
            $user_currency = SugarCurrency::getUserLocaleCurrency();

            if ($user_currency->id != $row_currency['currency_id']) {
                $value = SugarCurrency::convertAmount($value, $row_currency['currency_id'], $user_currency->id);
                $format_id = $user_currency->id;
            }
        }

        return SugarCurrency::formatAmountUserLocale($value, $format_id);
    }
 function queryFilterEquals(&$layout_def)
 {
     return $this->_get_column_select($layout_def)."=".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }

 function queryFilterNot_Equals(&$layout_def)
 {
     return $this->_get_column_select($layout_def)."!=".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }

 function queryFilterGreater(&$layout_def)
 {
     return $this->_get_column_select($layout_def)." > ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }

 function queryFilterLess(&$layout_def)
 {
     return $this->_get_column_select($layout_def)." < ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0']))."\n";
 }

 function queryFilterBetween(&$layout_def){
     return $this->_get_column_select($layout_def)." > ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])). " AND ". $this->_get_column_select($layout_def)." < ".$GLOBALS['db']->quote(unformat_number($layout_def['input_name1']))."\n";
 }
 public function queryFilterGreater_Equal(&$layout_def)
 {
     return $this->_get_column_select($layout_def) . " >= " . $GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])) . "\n";
 }
 public function queryFilterLess_Equal(&$layout_def)
 {
     return $this->_get_column_select($layout_def) . " <= " . $GLOBALS['db']->quote(unformat_number($layout_def['input_name0'])) . "\n";
 }

 function isSystemCurrency(&$layout_def)
 {
     if (strpos($layout_def['name'],'_usdoll') === false) {
         return false;
     } else {
         return true;
     }
 }

 function querySelect(&$layout_def)
 {
    // add currency column to select
    $table = $this->getCurrencyIdTable($layout_def);
    if($table) {
        return $this->_get_column_select($layout_def)." ".$this->_get_column_alias($layout_def)." , ".$table.".currency_id ". $this->getTruncatedColumnAlias($this->_get_column_alias($layout_def)."_currency") . "\n";
    }
    return $this->_get_column_select($layout_def)." ".$this->_get_column_alias($layout_def)."\n";
 }

 function queryGroupBy($layout_def)
 {
    // add currency column to group by
    $table = $this->getCurrencyIdTable($layout_def);
    if($table) {
        return $this->_get_column_select($layout_def)." , ".$table.".currency_id \n";
    }
    return $this->_get_column_select($layout_def)." \n";
 }

 function getCurrencyIdTable($layout_def)
 {
     $db = DBManagerFactory::getInstance();

    // We need to fetch the currency id as well
    if ( !$this->isSystemCurrency($layout_def) && empty($layout_def['group_function'])) {

        if ( !empty($layout_def['table_alias']) ) {
            $table = $layout_def['table_alias'];
        } else {
            $table = '';
        }

        $real_table = '';
        if (!empty($this->reporter->all_fields[$layout_def['column_key']]['real_table']))
            $real_table = $this->reporter->all_fields[$layout_def['column_key']]['real_table'];

        if(!empty($table)) {
            $cols = $db->get_columns($real_table);
            $add_currency_id = isset($cols['currency_id']) ? true : false;

            if(!$add_currency_id && preg_match('/.*?_cstm$/i', $real_table)) {
                $table = str_replace('_cstm', '', $table);
                $cols = $db->get_columns($table);
                $add_currency_id = isset($cols['currency_id']) ? true : false;
            }
            if($add_currency_id) {
                return $table;
            }
        }
    }
    return false;
 }

    /**
     * Return currency for layout_def
     * @param $layout_def mixed
     * @return array Array with currency symbol and currency ID
     */
    protected function getCurrency($layout_def)
    {
        $currency_id = false;
        $currency_symbol = false;
        if(isset($layout_def['currency_symbol']) && isset($layout_def['currency_id']))
        {
            $currency_symbol = $layout_def['currency_symbol'];
            $currency_id = $layout_def['currency_id'];
        }
        else
        {
            $key = strtoupper(isset($layout_def['varname']) ? $layout_def['varname'] : $this->_get_column_alias($layout_def));
            if ( $this->isSystemCurrency($layout_def) )
            {
                $currency_id = '-99';
            }
            elseif (isset($layout_def['fields'][$key.'_CURRENCY']))
            {
                $currency_id = $layout_def['fields'][$key.'_CURRENCY'];
            }
            elseif(isset($layout_def['fields'][$this->getTruncatedColumnAlias($this->_get_column_alias($layout_def)."_currency")]))
            {
                $currency_id = $layout_def['fields'][$this->getTruncatedColumnAlias($this->_get_column_alias($layout_def)."_currency")];
            }
            if($currency_id)
            {
                $currency = BeanFactory::getBean('Currencies', $currency_id);
                if(!empty($currency ->symbol))
                {
                    $currency_symbol = $currency ->symbol;
                }
            }
        }
        return array('currency_symbol' => $currency_symbol, 'currency_id' => $currency_id);
    }
}

