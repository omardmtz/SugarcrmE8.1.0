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

class SugarWidgetFieldFullname extends SugarWidgetFieldName
{
    function displayListPlain($layout_def)
    {
        $module = $this->reporter->all_fields[$layout_def['column_key']]['module'];
        $fields = $this->reporter->createNameList($layout_def['table_key']);
        if(empty($fields)) {
            return '';
        }
        $data = array();
        foreach($fields as $field) {
            $field['fields'] = $layout_def['fields'];
            $data[$field['name']] = $this->_get_list_value($field);
        }
        return $GLOBALS['locale']->formatName($module, $data);
    }
}
