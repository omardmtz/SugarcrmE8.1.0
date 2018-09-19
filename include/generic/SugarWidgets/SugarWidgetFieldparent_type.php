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


class SugarWidgetFieldparent_type extends SugarWidgetFieldEnum
{
    public function __construct(&$layout_manager)
    {
        parent::__construct($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');  
    }
   
    function displayListPlain($layout_def) {
        $value= $this->_get_list_value($layout_def);
        if (isset($layout_def['widget_type']) && $layout_def['widget_type'] =='checkbox') {
            if ($value != '' &&  ($value == 'on' || intval($value) == 1 || $value == 'yes'))  
            {
                return "<input name='checkbox_display' class='checkbox' type='checkbox' disabled='true' checked>";
            }
            return "<input name='checkbox_display' class='checkbox' type='checkbox' disabled='true'>";
        }
        return $value;
    }    
}
