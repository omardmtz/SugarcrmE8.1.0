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


global $mod_strings;
global $app_strings;

$focus = BeanFactory::newBean('Emails');

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
else {
	header("Location: index.php?module=Emails&action=index");
}

//needed when creating a new email with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
echo getClassicModuleTitle($mod_strings['LBL_SEND'], array($mod_strings['LBL_SEND']), true);

$GLOBALS['log']->info("Email detail view");

$xtpl=new XTemplate ('modules/Emails/Status.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
if (isset($focus->parent_type))
{
	$xtpl->assign("PARENT_MODULE", $focus->parent_type);
    $xtpl->assign('PARENT_TYPE', $app_list_strings['record_type_display'][$focus->parent_type]);
}
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("NAME", $focus->name);
//$xtpl->assign("SENT_BY_USER_NAME", $focus->sent_by_user_name);
$xtpl->assign("DATE_SENT", $focus->date_start." ".$focus->time_start);
if ($focus->status == 'sent')
{
$xtpl->assign("STATUS", $mod_strings['LBL_MESSAGE_SENT']);
}
else
{
$xtpl->assign("STATUS", "<font color=red>".$mod_strings['LBL_ERROR_SENDING_EMAIL']."</font>");
}

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main");
$xtpl->out("main");

?>
