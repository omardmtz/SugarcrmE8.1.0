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
use Sugarcrm\Sugarcrm\Security\Crypto\Blowfish;

class SugarWidgetFieldEncrypt extends SugarWidgetReportField
{
    function queryFilterEquals(&$layout_def)
    {
        $search_value = Blowfish::encode(Blowfish::getKey('encrypt_field'), $layout_def['input_name0']);
        return $this->_get_column_select($layout_def)."='".$GLOBALS['db']->quote($search_value)."'\n";
    }

    function queryFilterNot_Equals_Str(&$layout_def)
    {
        $search_value = Blowfish::encode(Blowfish::getKey('encrypt_field'), $layout_def['input_name0']);
        return $this->_get_column_select($layout_def)."!='".$GLOBALS['db']->quote($search_value)."'\n";
    }

    function displayList($layout_def) {
            return $this->displayListPlain($layout_def);
    }

    function displayListPlain($layout_def) {
            $value= $this->_get_list_value($layout_def);

            $value = Blowfish::decode(Blowfish::getKey('encrypt_field'), $value);
            return $value;
    }
       
}
