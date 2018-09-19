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

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = BeanFactory::newBean('Roles');

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == '1') {
	$focus->id = "";
	unset($_REQUEST['record']);
}
global $theme;



$GLOBALS['log']->info("Role Edit View");
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->name), true);
$xtpl=new XTemplate ('modules/Roles/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("JAVASCRIPT", get_set_focus_js() . get_chooser_js() . get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DESCRIPTION", $focus->description);

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

$xtpl->assign("TAB_CHOOSER", $chooser->display());

$xtpl->parse("main");
$xtpl->out("main");

$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();


?>
