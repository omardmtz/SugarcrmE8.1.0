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
global $current_user;


global $mod_strings;
global $app_list_strings;
global $app_strings;

$focus = BeanFactory::newBean('ReportMaker');

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$params = array();
if(empty($focus->id)){
	$params[] = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
}else{
	$params[] = "<a href='index.php?module=ReportMaker&action=DetailView&record={$focus->id}'>".$focus->get_summary_text()."</a>";
	$params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
}
echo getClassicModuleTitle("ReportMaker", $params, true);

$GLOBALS['log']->info("Report Maker edit view");

$xtpl=new XTemplate ('modules/ReportMaker/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

global $timedate;

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("PRINT_website", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());



$xtpl->assign("ID", $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('TITLE', $focus->title);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));


$xtpl->assign("REPORT_ALIGN", get_select_options_with_id($app_list_strings['report_align_dom'], $focus->report_align));


if (empty($focus->id) && !isset($_REQUEST['isDuplicate'])) {
	$xtpl->assign("TEAM_OPTIONS", get_select_options_with_id(get_team_array(), $current_user->default_team));
}
else {
	$xtpl->assign("TEAM_OPTIONS", get_select_options_with_id(get_team_array(), $focus->team_id));
}



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
