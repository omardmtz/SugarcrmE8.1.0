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






global $mod_strings;
global $app_strings;
global $app_list_strings;
global $focus, $support_coming_due, $support_expired;
$focus = BeanFactory::newBean('WorkFlow');
$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("WORKFLOW", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=WorkFlow&action=index");
}

$access = get_workflow_admin_modules_for_user($current_user);
if ((!is_admin($current_user) && !is_admin_for_any_module($current_user)) || (!empty($focus->base_module) && empty($access[$focus->base_module])))
{
   sugar_die("Unauthorized access to WorkFlow.");
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$params = array();
$params[] = "<a href='index.php?module=WorkFlow&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
$params[] = $focus->get_summary_text();

echo getClassicModuleTitle("WorkFlow", $params, true);

$GLOBALS['log']->info("WorkFlow detail view");

$xtpl=new XTemplate ('modules/WorkFlow/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('ID', $focus->id);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));

if ($focus->status==1){
	$status = "Active";
} else {
	$status = "Inactive";
}
//UI Parameters
$xtpl->assign('FIRE_ORDER', $app_list_strings['wflow_fire_order_dom'][$focus->fire_order]);
$xtpl->assign('STATUS', $app_list_strings['user_status_dom'][$status]);
$xtpl->assign('TYPE', $app_list_strings['wflow_type_dom'][$focus->type]);
$xtpl->assign('RECORD_TYPE', $app_list_strings['wflow_record_type_dom'][$focus->record_type]);
$xtpl->assign('BASE_MODULE', $app_list_strings['moduleList'][$focus->base_module]);

$detailView->processListNavigation($xtpl, "WORKFLOW", $offset, $focus->is_AuditEnabled());
global $current_user;



// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$buttons = array(
    '<input title="'.$app_strings['LBL_EDIT_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_EDIT_BUTTON_KEY'].'" class="button" onclick="this.form.return_module.value=\'WorkFlow\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\''.$focus->id.'\'; this.form.action.value=\'EditView\'" type="submit" name="EditWorkFlow" id="EditWorkFlow" value="'.$app_strings['LBL_EDIT_BUTTON_LABEL'].'"> ',
    '<input title="'.$app_strings['LBL_DUPLICATE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_DUPLICATE_BUTTON_KEY'].'" class="button" onclick="this.form.return_module.value=\'WorkFlow\'; this.form.return_action.value=\'index\'; this.form.isDuplicate.value=true; this.form.action.value=\'EditView\'" type="submit" name="DuplicateWorkFlow" id="DuplicateWorkFlow" value="'.$app_strings['LBL_DUPLICATE_BUTTON_LABEL'].'">',
    '<input title="'.$app_strings['LBL_DELETE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_DELETE_BUTTON_KEY'].'" class="button" onclick="this.form.return_module.value=\'WorkFlow\'; this.form.return_action.value=\'ListView\'; this.form.action.value=\'Delete\'; return confirm(\''.$app_strings['NTC_DELETE_CONFIRMATION'].'\')" type="submit" name="DeleteWorkFlow" id="DeleteWorkFlow" value="'.$app_strings['LBL_DELETE_BUTTON_LABEL'].'">'
);

$javascript = new javascript();

require_once('include/SugarSmarty/plugins/function.sugar_action_menu.php');
$action_buttons = smarty_function_sugar_action_menu(array(
    'id' => 'ACLRoles_EditView_action_menu',
    'buttons' => $buttons,
), $xtpl);
$javascript->addActionMenu();
$xtpl->assign('ACTION_MENU', $action_buttons);

$xtpl->parse("main");
$xtpl->out("main");


//Sub Panels

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();

if($sub_xtpl->var_exists('subpanel', 'SUBTRIGGERS')){
	ob_start();
echo "<p>\n";
global $focus_triggers_list, $focus_alerts_list, $focus_trigger_filters_list, $focus_actions_list;
// Now get the list of cases that match this one.
$focus_triggers_list = $focus->get_linked_beans('triggers','WorkFlowTriggerShell');
$focus_trigger_filters_list = $focus->get_linked_beans('trigger_filters','WorkFlowTriggerShell');

include('modules/WorkFlowTriggerShells/SubPanelView.php');

echo "</p>\n";


$subtriggers =  ob_get_contents();
ob_end_clean();
}



if($sub_xtpl->var_exists('subpanel', 'SUBALERTS')){
	ob_start();
echo "<p>\n";

// Now get the list of cases that match this one.
$focus_alerts_list = $focus->get_linked_beans('alerts','WorkFlowAlertShell');
include('modules/WorkFlowAlertShells/SubPanelView.php');

echo "</p>\n";
$subalerts =  ob_get_contents();
ob_end_clean();
}
if($sub_xtpl->var_exists('subpanel', 'SUBACTIONS')){
	ob_start();
echo "<p>\n";

// Now get the list of cases that match this one.
$focus_actions_list = $focus->get_linked_beans('actions','WorkFlowActionShell');
include('modules/WorkFlowActionShells/SubPanelView.php');

echo "</p>\n";
$subactions =  ob_get_contents();

ob_end_clean();
}

ob_start();
echo $old_contents;

if(!empty($subtriggers))$sub_xtpl->assign('SUBTRIGGERS', $subtriggers);

if(!empty($subalerts))$sub_xtpl->assign('SUBALERTS', $subalerts);
if(!empty($subactions))$sub_xtpl->assign('SUBACTIONS', $subactions);

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

echo $javascript->getScript(true, false);
?>
