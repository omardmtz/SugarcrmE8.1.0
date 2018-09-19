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
global $focus;
$focus = BeanFactory::newBean('ReportMaker');
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
else {
	header("Location: index.php?module=ReportMaker&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$params = array();
$params[] = $focus->get_summary_text();
echo getClassicModuleTitle("ReportMaker", $params, true);

$GLOBALS['log']->info("ReportMaker detail view");

$xtpl=new XTemplate ('modules/ReportMaker/DetailView.html');
$sub_xtpl = new XTemplate ('modules/ReportMaker/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("GRIDLINE", $gridline);

$xtpl->assign("ID", $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('TITLE', $focus->title);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));


$xtpl->assign("REPORT_ALIGN", $app_list_strings['report_align_dom'][$focus->report_align]);

$xtpl->assign("TEAM", $focus->assigned_name);

global $current_user;

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

if (SugarACL::checkAccess('DataSets', 'edit')) {
    $xtpl->parse('edit_button');
    $xtpl->assign('EDIT_BUTTON', $xtpl->text('edit_button'));
}

if (SugarACL::checkAccess('DataSets', 'delete')) {
    $xtpl->parse('delete_button');
    $xtpl->assign('DELETE_BUTTON', $xtpl->text('delete_button'));
}

$xtpl->parse("main");
$xtpl->out("main");

//Show the datasets

$old_contents = ob_get_contents();
ob_end_clean();

if($sub_xtpl->var_exists('subpanel', 'SUBDATASETS')){
ob_start();
global $focus_list;
$focus_list = $focus->get_data_sets("ORDER BY list_order_y ASC");
include('modules/DataSets/SubPanelView.php');
echo "<BR>\n";
$subdatasets =ob_get_contents();
ob_end_clean();
}

ob_start();
echo $old_contents;

if(!empty($subdatasets))$sub_xtpl->assign('SUBDATASETS', $subdatasets);

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

	
		
?>
