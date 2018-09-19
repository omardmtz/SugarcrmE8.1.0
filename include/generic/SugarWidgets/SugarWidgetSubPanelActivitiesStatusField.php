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

// $Id: SugarWidgetSubPanelActivitiesStatusField.php 45763 2009-04-01 19:16:18Z majed $




class SugarWidgetSubPanelActivitiesStatusField extends SugarWidgetField
{
    public function displayList($layout_def)
	{
		global $current_language;
		$app_list_strings = return_app_list_strings_language($current_language);
		
		$module = empty($layout_def['module']) ? '' : $layout_def['module'];
		
		if(isset($layout_def['varname']))
		{
			$key = strtoupper($layout_def['varname']);
		}
		else
		{
			$key = $this->_get_column_alias($layout_def);
			$key = strtoupper($key);
		}

		$value = $layout_def['fields'][$key];
		// cn: bug 5813, removing double-derivation of lang-pack value
		return $value;
	}
}
