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


class TemplateCurrencyId extends TemplateId
{
    public $max_size = 25;
    public $type = 'currency_id';

    public function get_field_def()
    {
        $def = parent::get_field_def();
        $def['type'] = $this->type;
        $def['vname'] = 'LBL_CURRENCY_ID';
        $def['dbType'] = 'id';
        $def['studio'] = false;
        $def['function'] = 'getCurrencies';
        $def['function_bean'] = 'Currencies';
        return $def;
    }

    public function save($df)
    {
        if (!$df->fieldExists($this->name)) {
            parent::save($df);
        }
    }

    public function delete($df)
    {
        if (!$df->fieldExists(null, 'currency')) {
            parent::delete($df);
        }
    }
}
