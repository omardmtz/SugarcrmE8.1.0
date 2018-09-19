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
require_once('include/workflow/workflow_utils.php');
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

if(!empty($_REQUEST['workflow_id'])) {
    $workflow_object = BeanFactory::retrieveBean('WorkFlow', $_REQUEST['workflow_id']);
}
if(empty($workflow_object)) {
	sugar_die("You shouldn't be here");
}

$focus = BeanFactory::newBean('WorkFlowTriggerShells');

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}


if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME']), true);

$GLOBALS['log']->info("WorkFlow edit view");

$xtpl=new XTemplate ('modules/WorkFlowTriggerShells/EditView.html');
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

$xtpl->assign("PRINT_website", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());


//Set parent ID
if(empty($focus->parent_id)){
	$focus->parent_id = $workflow_object->id;
}


////////Get Fields////////

	if(!empty($workflow_object->type) && $workflow_object->type=="Normal"){
		$meta_array_type = "normal_trigger";
	} else {
		$meta_array_type = "time_trigger";
	}

	$temp_module = BeanFactory::newBean($workflow_object->base_module);
	$temp_module->call_vardef_handler($meta_array_type);
	$field_array = $temp_module->vardef_handler->get_vardef_array();



$field_select = get_select_options_with_id($field_array, $focus->field);
$xtpl->assign('FIELD_SELECT', $field_select);


$xtpl->assign("BASE_MODULE", $workflow_object->base_module);
$xtpl->assign("ID", $focus->id);
$xtpl->assign('PARENT_ID', $focus->parent_id);



//check if this is time based or not and then assign show_past accordingly
if ($workflow_object->type=="Time"){
	$xtpl->assign("MODIFY_PAST_DIV", "No");
} else {
	$xtpl->assign("MODIFY_PAST_DIV", "Yes");
	if ($focus->show_past == 1) $xtpl->assign("SHOW_PAST", "checked");
}

$xtpl->assign('WORKFLOW_TYPE', $workflow_object->type);



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
