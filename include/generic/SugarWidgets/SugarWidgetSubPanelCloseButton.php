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

// $Id: SugarWidgetSubPanelCloseButton.php 40493 2008-10-13 21:10:05Z jmertic $


//TODO Rename this to close button field
class SugarWidgetSubPanelCloseButton extends SugarWidgetField
{
    public function displayList($layout_def)
	{
		global $app_strings;
        global $subpanel_item_count;
		$return_module = $_REQUEST['module'];
		$return_id = $_REQUEST['record'];
		$module_name = $layout_def['module'];
		$record_id = $layout_def['fields']['ID'];
        $unique_id = $layout_def['subpanel_id']."_close_".$subpanel_item_count; //bug 51512

		// calls and meetings are held.
		$new_status = 'Held';
		
		switch($module_name)
		{
			case 'Tasks':
				$new_status = 'Completed';
				break;
		}
        
		if ($layout_def['EditView']) {
		    $html = "<a id=\"$unique_id\" onclick='SUGAR.util.closeActivityPanel.show(\"$module_name\",\"$record_id\",\"$new_status\",\"subpanel\",\"{$layout_def['subpanel_id']}\");' >".$app_strings['LNK_CLOSE']."</a>";
		    return $html;
		} else {
		    return '';
		}

	}
}
