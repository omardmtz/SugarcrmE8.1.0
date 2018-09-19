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


class TemplateCurrency extends TemplateRange
{
    public $max_size = 25;
    public $len = 26;
    public $precision = 6;
    public $default = 0;
    public $type = 'currency';

    public function delete($df)
    {
        parent::delete($df);

        $field_defs = $df->bean->field_defs;
        foreach($field_defs as $id => $field) {
            if ($field['type'] == 'currency' && $id != $this->name) {
                return;
            }
        }

        //currency id
        $currency_id = new TemplateCurrencyId();
        $currency_id->name = 'currency_id';
        $currency_id->delete($df);

        //base_rate
        $base_rate = new TemplateCurrencyBaseRate();
        $base_rate->name = 'base_rate';
        $base_rate->delete($df);
    }

    public function save($df)
    {
        //the currency field
        $this->default = unformat_number($this->default);
        $this->default_value = $this->default;
        $this->related_fields = array(
            'currency_id',
            'base_rate'
        );
        parent::save($df);

        $df->addLabel('LBL_CURRENCY');

        //currency id
        $currency_id = new TemplateCurrencyId();
        $currency_id->name = 'currency_id';
        $currency_id->save($df);

        //base_rate
        $base_rate = new TemplateCurrencyBaseRate();
        $base_rate->name = 'base_rate';
        $base_rate->label = 'LBL_CURRENCY_RATE';
        $base_rate->save($df);

    }

    public function get_field_def()
    {
        $def = parent::get_field_def();
        if (isset($this->convertToBase) && $this->convertToBase === true) {
            $def['convertToBase'] = true;
        }
        $def['precision'] = (!empty($this->precision)) ? $this->precision : 6;
        $def['related_fields'] = array('currency_id', 'base_rate');
        return $def;
    }

    function get_db_type()
    {
        $precision = (!empty($this->precision)) ? $this->precision : 6;
        $len = (!empty($this->len)) ? $this->len : 26;
        return " " . sprintf($GLOBALS['db']->getColumnType("decimal_tpl"), $len, $precision);
    }
}
