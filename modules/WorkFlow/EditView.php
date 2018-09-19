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
$workflow_modules = get_workflow_admin_modules_for_user($current_user);
if (!is_admin($current_user) && empty($workflow_modules)) {
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

$focus = BeanFactory::newBean('WorkFlow');

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$access = get_workflow_admin_modules_for_user($current_user);
if ((!is_admin($current_user) && !is_admin_for_any_module($current_user)) || (!empty($focus->base_module) && empty($access[$focus->base_module])))
{
   sugar_die("Unauthorized access to WorkFlow.");
}
$old_workflow_id = $focus->id;
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$params = array();
$params[] = "<a href='index.php?module=WorkFlow&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
if(empty($focus->id)){
	$params[] = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
}else{
	$params[] = "<a href='index.php?module=WorkFlow&action=DetailView&record={$focus->id}'>{$focus->name}</a>";
	$params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
}
echo getClassicModuleTitle("WorkFlow", $params, true);

$GLOBALS['log']->info("WorkFlow edit view");

$xtpl=new XTemplate ('modules/WorkFlow/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$returnAction = '';
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $returnAction = $_REQUEST['return_action'];
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
    $returnAction = 'index';
}
$xtpl->assign("RETURN_ACTION", $returnAction);
$xtpl->assign("PRINT_website", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());

$focus_triggers_list = $focus->get_linked_beans('triggers','WorkFlowTriggerShell');
$focus_trigger_filters_list = $focus->get_linked_beans('trigger_filters','WorkFlowTriggerShell');
$focus_all_triggers_list = array_merge($focus_triggers_list, $focus_trigger_filters_list);
if(count($focus_all_triggers_list) > 0){
	$xtpl->assign('DISABLE_TYPE', "disabled");
	$xtpl->assign('DISABLE_BASE_MODULE', "disabled");
//end if we shouldn't be able to change the type or base module
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$xtpl->assign("OLD_ID", $old_workflow_id);
    $xtpl->assign('IS_DUPLICATE', "true");
    $xtpl->assign('DISABLE_BASE_MODULE', "disabled");
    $xtpl->assign('BASE_MODULE_FIELDNAME', 'base_module_disabled');
    $xtpl->assign('DUPLICATE_BASE_MODULE', "<input name=\"base_module\" type=\"hidden\" value={$focus->base_module} \>");
} else  {
    $xtpl->assign('BASE_MODULE_FIELDNAME', 'base_module');
}

$xtpl->assign("ID", $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->id=="" || $focus->status==1){
	$status = "Active";
} else {
	$status = "Inactive";
}

$xtpl->assign("STATUS", get_select_options_with_id($app_list_strings['user_status_dom'],$status));

$xtpl->assign("FIRE_ORDER", get_select_options_with_id($app_list_strings['wflow_fire_order_dom'],$focus->fire_order));
$xtpl->assign("TYPE", get_select_options_with_id($app_list_strings['wflow_type_dom'],$focus->type));
$xtpl->assign("RECORD_TYPE", get_select_options_with_id($app_list_strings['wflow_record_type_dom'],$focus->record_type));
$xtpl->assign("BASE_MODULE", get_select_options_with_id($focus->get_module_array(),$focus->base_module));



$cancel = "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' id='cancel_workflow'
        accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' ";
if(isset($_REQUEST['return_action']))
{
    $cancel .= "onclick='this.form.action.value=\"".$returnAction."\";";
    if(isset($_REQUEST['return_module']))
    {
        $cancel .= "this.form.module.value=\"".$_REQUEST['return_module']."\";";
        if(isset($_REQUEST['return_id']))
        {
            $cancel .= "this.form.record.value=\"".$_REQUEST['return_id']."\";";
        }
    }
    $cancel .= "'";
}
$cancel .= " type='submit' name='button' value='".$app_strings['LBL_CANCEL_BUTTON_LABEL']."'>";



$buttons = array(
    "<input title='".$app_strings['LBL_SAVE_BUTTON_TITLE']."'' id='save_workflow'
        accessKey='".$app_strings['LBL_SAVE_BUTTON_KEY']."'' class='button'
        onclick='this.form.action.value=\"Save\"; return check_form(\"EditView\");'
        type='submit' name='button' value='".$app_strings['LBL_SAVE_BUTTON_LABEL']."' >",
    $cancel
);

require_once('include/SugarSmarty/plugins/function.sugar_action_menu.php');
$action_buttons = smarty_function_sugar_action_menu(array(
    'id' => 'ACLRoles_EditView_action_menu',
    'buttons' => $buttons,
    'flat' => true
), $xtpl);

$xtpl->assign('ACTION_MENU', $action_buttons);

global $current_user;


//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");
$xtpl->out("main");

$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addActionMenu();
echo $javascript->getScript();

?>
