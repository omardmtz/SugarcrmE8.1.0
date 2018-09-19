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



class SugarWidgetFieldusername extends SugarWidgetFieldName
{
    public function displayInput($layout_def)
 {
        $selected_users = empty($layout_def['input_name0']) ? '' : $layout_def['input_name0'];
 		$str = '<select multiple="true" size="3" name="' . $layout_def['name'] . '[]">' . get_select_options_with_id(get_user_array(false), $selected_users) . '</select>';
 		return $str;
 }
}
