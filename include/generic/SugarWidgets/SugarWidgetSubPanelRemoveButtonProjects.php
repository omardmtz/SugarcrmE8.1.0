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

// $Id: SugarWidgetSubPanelRemoveButton.php 14149 2006-06-19 23:11:32 +0000 (Mon, 19 Jun 2006) awu $



class SugarWidgetSubPanelRemoveButtonProjects extends SugarWidgetField
{
    public function displayHeaderCell($layout_def)
	{
		return '&nbsp;';
	}

    public function displayList($layout_def)
	{
		global $app_strings;
		
		global $current_user;
		
		$parent_record_id = $_REQUEST['record'];
		$parent_module = $_REQUEST['module'];

		if ($layout_def['module'] == 'Holidays'){
			$action = 'DeleteHolidayRelationship';
		}
		else if ($layout_def['module'] == 'Users' || $layout_def['module'] == 'Contacts'){
			$action = 'DeleteResourceRelationship';
		}
		else{
			$action = 'DeleteRelationship';
		}

		$record = $layout_def['fields']['ID'];
		$current_module=$layout_def['module'];	
		$hideremove=false;			
		
		$return_module = $_REQUEST['module'];
		$return_action = 'SubPanelViewer';
		$subpanel = $layout_def['subpanel_id'];
		$return_id = $_REQUEST['record'];
		
		
		$focus = BeanFactory::getBean('Project', $return_id);
		
		if ($current_user->id == $focus->assigned_user_id || is_admin($current_user)){
			$is_owner = true;
		}
		else{
			$is_owner = false;
		}
				
		if (isset($layout_def['linked_field_set']) && !empty($layout_def['linked_field_set'])) {
			$linked_field= $layout_def['linked_field_set'] ;
		} else {
			$linked_field = $layout_def['linked_field'];
		}
		$refresh_page = 0;
		if(!empty($layout_def['refresh_page'])){
			$refresh_page = 1;
		}
		$return_url = "index.php?module=$return_module&action=$return_action&subpanel=$subpanel&record=$return_id&sugar_body_only=1&inline=1";

		$icon_remove_text = strtolower($app_strings['LBL_ID_FF_REMOVE']);
		$icon_remove_html = SugarThemeRegistry::current()->getImage( 'delete_inline', 'align="absmiddle" border="0"',null,null,'.gif','');//setting alt to blank on purpose on subpanels for 508
		$remove_url = $layout_def['start_link_wrapper']
			. "index.php?module=$parent_module"
			. "&action=$action"
			. "&record=$parent_record_id"
			. "&linked_field=$linked_field"
			. "&linked_id=$record"
			. "&return_url=" . urlencode(urlencode($return_url))
			. "&refresh_page=1"
			. $layout_def['end_link_wrapper'];
		$remove_confirmation_text = $app_strings['NTC_REMOVE_CONFIRMATION'];
		//based on listview since that lets you select records
		if($layout_def['ListView'] && !$hideremove && $is_owner) {
			return '<a href="' . $remove_url . '"'
			. ' class="listViewTdToolsS1"'
			. " onclick=\"return confirm('$remove_confirmation_text');\""
			. ">$icon_remove_html&nbsp;$icon_remove_text</a>";
		}else{
			return '';
		}
	}
}
?>