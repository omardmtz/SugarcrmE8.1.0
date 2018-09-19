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

// $Id: SugarWidgetSubPanelTopSummaryButton.php 38033 2008-07-21 14:03:34Z jmertic $



class SugarWidgetSubPanelTopSummaryButton extends SugarWidgetSubPanelTopButton
{
    public function display(array $widget_data, $additionalFormFields = array())
	{
		
		
		global $app_strings;
		global $currentModule;

		$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => 'EditView',
			'field_to_name_array' => array(),
		);

		$json_encoded_php_array = $this->_create_json_encoded_popup_request($popup_request_data);
		$title = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_TITLE'];
		//$accesskey = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_KEY'];
		$value = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_LABEL'];
		$module_name = 'Activities';
		$id = $widget_data['focus']->id;
		$initial_filter = "&record=$id&module_name=$currentModule";
		if(ACLController::moduleSupportsACL($widget_data['module']) && !ACLController::checkAccess($widget_data['module'], 'detail', true)){
			$temp =  '<input disabled type="button" name="summary_button" id="summary_button"'
			. ' class="button"'
			. ' title="' . $title . '"'
			. ' value="' . $value . '"';
			return $temp;
		}
		return '<input type="button" name="summary_button" id="summary_button"'
			. ' class="button"'
			. ' title="' . $title . '"'
			. ' value="' . $value . '"'
			. " onclick='open_popup(\"$module_name\",600,400,\"$initial_filter\",false,false,$json_encoded_php_array);' />\n";
	}
}
