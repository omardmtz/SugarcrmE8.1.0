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
/*********************************************************************************

 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/





/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">

function verify_data(form) {
	var isError = false;
	var errorMessage = "";

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}
</script>

EOQ;

return $the_script;



}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
	global $app_strings;
	global $app_list_strings;
	global $mod_strings;
	global $currentModule;
	global $current_user;
	global $timedate;
	
	$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
	$form = new XTemplate ('modules/Campaigns/Forms.html');

	$module_select = empty($_REQUEST['module_select']) ? ''
		: $_REQUEST['module_select'];
	$form->assign('MOD', $mod_strings);
	$form->assign('APP', $app_strings);
	$form->assign('THEME', SugarThemeRegistry::current()->__toString());
	$form->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
	$form->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], "Planning"));
	$form->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['campaign_type_dom'], ""));

	$form->assign("USER_ID", $current_user->id);
	$form->assign("TEAM_ID", sprintf('<input type="hidden" name="team_id" value="%s">', $current_user->default_team));


	$form->assign("CALENDAR_LANG", "en");
	$form->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
	$form->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

	$form->parse('main');
	$the_form .= $form->text('main');

	
	$focus = BeanFactory::newBean('Campaigns');
	
	
	$javascript = new javascript();
	$javascript->setFormName('quick_save');
	$javascript->setSugarBean($focus);
	$javascript->addRequiredFields('');
	$jscript = $javascript->getScript();

	$the_form .= $jscript . get_left_form_footer();
	return $the_form;


}

?>