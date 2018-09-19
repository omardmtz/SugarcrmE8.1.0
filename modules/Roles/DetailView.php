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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





require_once('modules/Roles/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$focus = BeanFactory::newBean('Roles');

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("ROLE", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->get_summary_text()), true);


$GLOBALS['log']->info("Role detail view");

$xtpl=new XTemplate ('modules/Roles/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("RETURN_MODULE", "Roles");
$xtpl->assign("RETURN_ACTION", "DetailView");
$xtpl->assign("ACTION", "EditView");

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));

$detailView->processListNavigation($xtpl, "ROLE", $offset);

require_once("include/templates/TemplateGroupChooser.php");

$chooser = new TemplateGroupChooser();
$controller = new TabController();
$chooser->args['id'] = 'edit_tabs';

if(isset($_REQUEST['record']))
{
	$chooser->args['values_array'][0] = $focus->query_modules(1);
	$chooser->args['values_array'][1] = $focus->query_modules(0);

	foreach ($chooser->args['values_array'][0] as $key=>$value)
	{
		$chooser->args['values_array'][0][$value] = $app_list_strings['moduleList'][$value];
		unset($chooser->args['values_array'][0][$key]);
	}

	foreach ($chooser->args['values_array'][1] as $key=>$value)
	{
		$chooser->args['values_array'][1][$value] = $app_list_strings['moduleList'][$value];
		unset($chooser->args['values_array'][1][$key]);

	}
}
else
{
	$chooser->args['values_array'] = $controller->get_tabs_system();
	foreach ($chooser->args['values_array'][0] as $key=>$value)
	{
		$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
	}
	foreach ($chooser->args['values_array'][1] as $key=>$value)
	{
	$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
	}

}
	
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_ALLOWED_MODULES'];
$chooser->args['right_label'] =  $mod_strings['LBL_DISALLOWED_MODULES'];
$chooser->args['title'] =  $mod_strings['LBL_ASSIGN_MODULES'];

$chooser->args['disable'] = true;
$xtpl->assign("TAB_CHOOSER", $chooser->display());

$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

$subpanel = new SubPanelTiles($focus, 'Roles');
echo $subpanel->display();
?>