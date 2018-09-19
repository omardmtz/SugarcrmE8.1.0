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

class TemplatePricingFormula extends TemplateText{
    public $type = 'pricing-formula';
    public $ext1 = 'pricing_formula_dom';
    public $default_value = '';

    function get_field_def()
    {
        $def = parent::get_field_def();
        $def['options'] = !empty($this->options) ? $this->options : $this->ext1;
        $def['default'] = !empty($this->default) ? $this->default : $this->default_value;

        return $def;
    }
}
