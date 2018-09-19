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

 * Description:  
 ********************************************************************************/






global $current_user;
//Only allow admins to enter this screen
if (!is_admin($current_user)&& !is_admin_for_any_module($current_user)) {
	$GLOBALS['log']->error("Non-admin user ($current_user->user_name) attempted to enter the WorkFlow EditView screen");
	session_destroy();
	include('modules/Users/Logout.php');
}

global $mod_strings;
global $app_list_strings;
global $app_strings;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;


$workflow_object = BeanFactory::newBean('WorkFlow');
if(isset($_REQUEST['workflow_id']) && isset($_REQUEST['workflow_id'])) {
    $workflow_object->retrieve($_REQUEST['workflow_id']);
} else {
	sugar_die("You shouldn't be here");
}


$focus = BeanFactory::newBean('WorkFlowAlertShells');

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}




if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->name), true); 

$GLOBALS['log']->info("WorkFlowAlertShells edit view");

$xtpl=new XTemplate ('modules/WorkFlowAlertShells/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])){
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
} else {
	$xtpl->assign("RETURN_MODULE", "WorkFlow");
}
if (isset($_REQUEST['return_action'])){
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
} else {
	$xtpl->assign("RETURN_ACTION", "DetailView");
}	
if (isset($_REQUEST['return_id'])){
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	$xtpl->assign("RETURN_ID", $_REQUEST['workflow_id']);
}	

if (isset($_REQUEST['alert_type'])){
	//Possible invite alert
	$focus->alert_type = $_REQUEST['alert_type'];
}

$xtpl->assign("PRINT_website", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());



$xtpl->assign("ID", $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign("ALERT_TEXT", nl2br($focus->alert_text));

//Specifc handling forks here for dealing with bridging alert shells used for meeting/call invites
	$xtpl->assign("ALERT_TYPE", get_select_options_with_id($app_list_strings['wflow_alert_type_dom'], $focus->alert_type));

		$source_type_array = $app_list_strings['wflow_source_type_dom'];
	
	if($focus->alert_type=="Email"){
		unset($source_type_array['System Default']);
	}	
		$xtpl->assign("SOURCE_TYPE", get_select_options_with_id($source_type_array, $focus->source_type));



////////////CUSTOM TEMPLATE////////
$template_where = "base_module='".$workflow_object->base_module."'";
$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name', $template_where, 'name');

$xtpl->assign("EMAIL_TEMPLATE_OPTIONS", get_select_options_with_id($email_templates_arr, $focus->custom_template_id));





//Set parent ID
if(empty($focus->parent_id)){
	$focus->parent_id = $workflow_object->id;
}


$xtpl->assign('PARENT_ID', $focus->parent_id);
$xtpl->assign('WORKFLOW_TYPE', $workflow_object->type);


global $current_user;


//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");
$xtpl->out("main");

$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
