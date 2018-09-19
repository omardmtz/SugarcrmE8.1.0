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



require_once('modules/EmailMarketing/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = BeanFactory::newBean('EmailMarketing');

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

global $theme;



$GLOBALS['log']->info("EmailMarketing Edit View");

$xtpl=new XTemplate ('modules/EmailMarketing/DetailView.html');

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) {
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
} else {
	$xtpl->assign("RETURN_MODULE", 'Campaigns');
}
if (isset($_REQUEST['return_action'])) {
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
} else {
	$xtpl->assign("RETURN_ACTION", 'DetailView');
}
if (isset($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	if (!empty($focus->campaign_id)) {
		$xtpl->assign("RETURN_ID", $focus->campaign_id);
	}
}


if($focus->campaign_id) {
	$campaign_id=$focus->campaign_id;
}
else {
	$campaign_id=$_REQUEST['campaign_id'];
}
$xtpl->assign("CAMPAIGN_ID", $campaign_id);

$xtpl->assign("JAVASCRIPT", get_set_focus_js());
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("FROM_NAME", $focus->from_name);
$xtpl->assign("FROM_ADDR", $focus->from_addr);
$xtpl->assign("REPLY_TO_NAME", $focus->reply_to_name);
$xtpl->assign("REPLY_TO_ADDR", $focus->reply_to_addr);
$xtpl->assign("DATE_START", $focus->date_start);
$xtpl->assign("TIME_START", $focus->time_start);

$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name');
if($focus->template_id) {
	$xtpl->assign("EMAIL_TEMPLATE", $email_templates_arr[$focus->template_id]);
}

//include campaign utils..
require_once('modules/Campaigns/utils.php');
if (empty($_REQUEST['campaign_name'])) {

	$campaign = BeanFactory::getBean('Campaigns', $campaign_id);
	$campaign_name=$campaign->name;
} else {
	$campaign_name=$_REQUEST['campaign_name'];
}

$params = array();
$params[] = "<a href='index.php?module=Campaigns&action=index'>{$mod_strings['LNK_CAMPAIGN_LIST']}</a>";
$params[] = "<a href='index.php?module=Campaigns&action=DetailView&record={$campaign_id}'>{$campaign_name}</a>";
$params[] = $focus->name;

echo getClassicModuleTitle($focus->module_dir, $params, true);

if (!empty($focus->all_prospect_lists)) {
	$xtpl->assign("MESSAGE_FOR", $mod_strings['LBL_ALL_PROSPECT_LISTS']);
} else {
	$xtpl->assign("MESSAGE_FOR", $mod_strings['LBL_RELATED_PROSPECT_LISTS']);
}

if (!empty($focus->status)) {
	$xtpl->assign("STATUS",$app_list_strings['email_marketing_status_dom'][$focus->status]);
}
$emails=array();
$mailboxes=get_campaign_mailboxes($emails);
//_ppd($emails[$focus->inbound_email_id]);
if (!empty($focus->inbound_email_id) && isset($mailboxes[$focus->inbound_email_id])) {
	$xtpl->assign("FROM_MAILBOX_NAME", $mailboxes[$focus->inbound_email_id]."&nbsp;&lt;{$emails[$focus->inbound_email_id]}&gt;");
}

$xtpl->parse("main");
$xtpl->out("main");


$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

$subpanel = new SubPanelTiles($focus, 'EmailMarketing');

if ($focus->all_prospect_lists == 1) {
	$subpanel->subpanel_definitions->exclude_tab('prospectlists');
}
else {
	$subpanel->subpanel_definitions->exclude_tab('allprospectlists');

}

echo $subpanel->display();

?>