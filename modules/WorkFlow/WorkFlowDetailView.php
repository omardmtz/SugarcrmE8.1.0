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
 * $Header$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



global $app_strings;
global $mod_strings;

$focus = BeanFactory::newBean('EmailTemplates');

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("EMAIL_TEMPLATE", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new note with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['account_id'];
}
echo getClassicModuleTitle($mod_strings['LBL_MODULE_ID'], array($mod_strings['LBL_ALERT_TEMPLATES'],$focus->name), true);

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl=new XTemplate ('modules/WorkFlow/WorkFlowDetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("MOD_EMAILS", return_module_language($GLOBALS['current_language'], 'EmailTemplates'));

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("BASE_MODULE", $focus->base_module);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("FROM_NAME", $focus->from_name);
$xtpl->assign("FROM_ADDRESS", $focus->from_address);
$xtpl->assign("SUBJECT", $focus->subject);
if(!empty($focus->body)){
	$xtpl->assign("ALT_CHECKED", 'checked');
}
$xtpl->assign("BODY", $focus->body);
$xtpl->assign("BODY_HTML", from_html($focus->body_html));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
if ( $focus->published == 'on')
{
$xtpl->assign("PUBLISHED","CHECKED");
}

///////////////////////////////////////////////////////////////////////////////
////	NOTES (attachements, etc.)
///////////////////////////////////////////////////////////////////////////////
$attachments = '';
$note = BeanFactory::newBean('Notes');
//FIXME: notes.email_type should be EmailTemplates
$notes_list = $note->get_full_list("notes.name", "notes.email_id=" . $GLOBALS['db']->quoted($focus->id), true);
if(!empty($notes_list)) {
    for($i=0; $i<count($notes_list); $i++) {
        $the_note = $notes_list[$i];
        $attachments .= "<a href=\"index.php?entryPoint=download&id={$the_note->id}&type=Notes\">".$the_note->name."</a><br />";
    }
}
$xtpl->assign("ATTACHMENTS", $attachments);

global $current_user;
if((is_admin($current_user)|| is_admin_for_any_module($current_user)) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>");
}

$xtpl->assign("DESCRIPTION", $focus->description);

$detailView->processListNavigation($xtpl, "EMAIL_TEMPLATE", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');


$xtpl->parse("main");

$xtpl->out("main");

?>
