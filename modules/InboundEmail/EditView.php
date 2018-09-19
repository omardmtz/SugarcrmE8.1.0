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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

$_REQUEST['edit']='true';

require_once('include/templates/TemplateGroupChooser.php');

use Sugarcrm\Sugarcrm\Util\Serialized;

// GLOBALS
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

if (!$current_user->isAdminForModule("InboundEmail")) {
    sugar_die(translate('ERR_NOT_ADMIN'));
}

$focus = BeanFactory::newBean('InboundEmail');
$focus->checkImap();
$javascript = new javascript();
$email = BeanFactory::newBean('Emails');
/* Start standard EditView setup logic */

$domMailBoxType = $app_list_strings['dom_mailbox_type'];

if(isset($_REQUEST['record'])) {
	$GLOBALS['log']->debug("In InboundEmail edit view, about to retrieve record: ".$_REQUEST['record']);
	$result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
else
{
    $request = InputValidation::getService();
    $request_mailbox_type = $request->getValidInputRequest('mailbox_type');
    if ($request_mailbox_type) {
        $focus->mailbox_type = $request_mailbox_type;
    }

    //Default to imap protocol for new accounts.
    $focus->protocol = 'imap';
}

if($focus->mailbox_type == 'bounce')
{
    unset($domMailBoxType['pick']);
    unset($domMailBoxType['createcase']);
}
else
    unset($domMailBoxType['bounce']);

$isDuplicate = isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true';
if($isDuplicate) {
	$GLOBALS['log']->debug("isDuplicate found - duplicating record of id: ".$focus->id);
	$origin_id = $focus->id;
	$focus->id = "";
}

$GLOBALS['log']->info("InboundEmail Edit View");
/* End standard EditView setup logic */

/* Start custom setup logic */
// status drop down
$status = get_select_options_with_id_separate_key($app_list_strings['user_status_dom'],$app_list_strings['user_status_dom'], $focus->status);
// default MAILBOX value
if(empty($focus->mailbox)) {
	$mailbox = 'INBOX';
} else {
	$mailbox = $focus->mailbox;
}

// service options breakdown
$tls = '';
$notls = '';
$cert = '';
$novalidate_cert = '';
$ssl = '';
if(!empty($focus->service)) {
	// will always have 2 values: /tls || /notls and /validate-cert || /novalidate-cert
	$exServ = explode('::', $focus->service);
	if($exServ[0] == 'tls') {
		$tls = "CHECKED";
	} elseif($exServ[5] == 'notls') {
		$notls = "CHECKED";
	}
	if($exServ[1] == 'validate-cert') {
		$cert = "CHECKED";
	} elseif($exServ[4] == 'novalidate-cert') {
		$novalidate_cert = 'CHECKED';
	}
	if(isset($exServ[2]) && !empty($exServ[2]) && $exServ[2] == 'ssl') {
		$ssl = "CHECKED";
	}
}
$mark_read = '';
if($focus->delete_seen == 0 || empty($focus->delete_seen)) {
	$mark_read = 'CHECKED';
}

// mailbox type

if ($focus->is_personal) {
	array_splice($domMailBoxType, 1, 1);
} // if
$mailbox_type = get_select_options_with_id($domMailBoxType, $focus->mailbox_type);

// auto-reply email template
$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name', '','name',true);

if(!empty($focus->stored_options)) {
	$storedOptions = Serialized::unserialize($focus->stored_options, array(), true);
	$from_name = $storedOptions['from_name'];
	$from_addr = $storedOptions['from_addr'];

	$reply_to_name = (isset($storedOptions['reply_to_name'])) ? $storedOptions['reply_to_name'] : "";
	$reply_to_addr = (isset($storedOptions['reply_to_addr'])) ? $storedOptions['reply_to_addr'] : "";

	$trashFolder = (isset($storedOptions['trashFolder'])) ? $storedOptions['trashFolder'] : "";
	$sentFolder = (isset($storedOptions['sentFolder'])) ? $storedOptions['sentFolder'] : "";
	$distrib_method = (isset($storedOptions['distrib_method'])) ? $storedOptions['distrib_method'] : "";
	$create_case_email_template = (isset($storedOptions['create_case_email_template'])) ? $storedOptions['create_case_email_template'] : "";
	$email_num_autoreplies_24_hours = (isset($storedOptions['email_num_autoreplies_24_hours'])) ? $storedOptions['email_num_autoreplies_24_hours'] : $focus->defaultEmailNumAutoreplies24Hours;

	if($storedOptions['only_since']) {
		$only_since = 'CHECKED';
	} else {
		$only_since = '';
	}
	if(isset($storedOptions['filter_domain']) && !empty($storedOptions['filter_domain'])) {
		$filterDomain = $storedOptions['filter_domain'];
	} else {
		$filterDomain = '';
	}
	if(!isset($storedOptions['leaveMessagesOnMailServer']) || $storedOptions['leaveMessagesOnMailServer'] == 1) {
		$leaveMessagesOnMailServer = 1;
	} else {
		$leaveMessagesOnMailServer = 0;
	} // else
} else { // initialize empty vars for template
    $from_name = $current_user->name;
    $from_addr = $current_user->email1;
	$reply_to_name = '';
	$reply_to_addr = '';
	$only_since = '';
	$filterDomain = '';
	$trashFolder = '';
	$sentFolder = '';
	$distrib_method ='';
	$create_case_email_template='';
	$leaveMessagesOnMailServer = 1;
	$email_num_autoreplies_24_hours = $focus->defaultEmailNumAutoreplies24Hours;
} // else

// return action
if(isset($focus->id)) {
	$return_action = 'DetailView';
    $validatePass = FALSE;
} else {
	$return_action = 'ListView';
    $validatePass = TRUE;
}

// javascript
$javascript->setSugarBean($focus);
$javascript->setFormName('EditView');

//If we are creating a duplicate, remove the email_password from being required since this
//can be derived from the InboundEmail we are duplicating from
// Bug 47863 - email_password shouldn't be required on a modified Inbound Email account
// either.
if(($isDuplicate || !$validatePass) && isset($focus->required_fields['email_password']))
{
   unset($focus->required_fields['email_password']);
}

$javascript->addRequiredFields();
$javascript->addFieldGeneric('email_user', 'alpha', $mod_strings['LBL_LOGIN'], true);
$javascript->addFieldGeneric('email_password', 'alpha', $mod_strings['LBL_PASSWORD'], $validatePass);
$javascript->addFieldRange('email_num_autoreplies_24_hours', 'int', $mod_strings['LBL_MAX_AUTO_REPLIES'], true, "", 1, $focus->maxEmailNumAutoreplies24Hours);

$r = $focus->db->query('SELECT value FROM config WHERE name = \'fromname\'');
$a = $focus->db->fetchByAssoc($r);
$default_from_name = $a['value'];
$r = $focus->db->query('SELECT value FROM config WHERE name = \'fromaddress\'');
$a = $focus->db->fetchByAssoc($r);
$default_from_addr = $a['value'];

/* End custom setup logic */


// TEMPLATE ASSIGNMENTS
if ($focus->mailbox_type == 'template') {
	$xtpl = new XTemplate('modules/InboundEmail/EmailAccountTemplateEditView.html');
} else {
	$xtpl = new XTemplate('modules/InboundEmail/EditView.html');
}
// if no IMAP libraries available, disable Save/Test Settings
if(!function_exists('imap_open')) {
	$xtpl->assign('IE_DISABLED', 'DISABLED');
}
// standard assigns
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('THEME', SugarThemeRegistry::current()->__toString());
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('MODULE', 'InboundEmail');
$xtpl->assign('RETURN_MODULE', 'InboundEmail');
$xtpl->assign('RETURN_ID', $focus->id);
$xtpl->assign('RETURN_ACTION', $return_action);
// module specific
$xtpl->assign("EMAIL_OPTIONS", $mod_strings['LBL_EMAIL_OPTIONS']);
$xtpl->assign('MODULE_TITLE', getClassicModuleTitle('InboundEmail', array($mod_strings['LBL_MODULE_NAME'],$focus->name), true));
$xtpl->assign('ID', $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('STATUS', $status);
$xtpl->assign('SERVER_URL', $focus->server_url);
$xtpl->assign('USER', $focus->email_user);
$xtpl->assign('ORIGIN_ID', isset($origin_id)?$origin_id:'');
// Don't send password back
$xtpl->assign('HAS_PASSWORD', empty($focus->email_password)?0:1);
$xtpl->assign('TRASHFOLDER', $trashFolder);
$xtpl->assign('SENTFOLDER', $sentFolder);
$xtpl->assign('MAILBOX', $mailbox);
$xtpl->assign('TLS', $tls);
$xtpl->assign('NOTLS', $notls);
$xtpl->assign('CERT', $cert);
$xtpl->assign('NOVALIDATE_CERT', $novalidate_cert);
$xtpl->assign('SSL', $ssl);

$protocol = filterInboundEmailPopSelection($app_list_strings['dom_email_server_type']);
$xtpl->assign('PROTOCOL', get_select_options_with_id($protocol, $focus->protocol));
$xtpl->assign('MARK_READ', $mark_read);
$xtpl->assign('MAILBOX_TYPE', htmlspecialchars($focus->mailbox_type, ENT_COMPAT, 'UTF-8'));
$xtpl->assign('TEMPLATE_ID', $focus->template_id);
$xtpl->assign('EMAIL_TEMPLATE_OPTIONS', get_select_options_with_id($email_templates_arr, $focus->template_id));
$xtpl->assign('ONLY_SINCE', $only_since);
$xtpl->assign('FILTER_DOMAIN', $filterDomain);
$xtpl->assign('EMAIL_NUM_AUTOREPLIES_24_HOURS', $email_num_autoreplies_24_hours);
if(!empty($focus->port)) {
	$xtpl->assign('PORT', $focus->port);
}
// groups
$groupId = "";
$is_auto_import = "";
$allow_outbound = '';
if(isset($focus->id))
	$groupId = $focus->group_id;
else
{
	$groupId = create_guid();
	$is_auto_import = 'checked';
    $xtpl->assign('EMAIL_PASS_REQ_SYMB', $app_strings['LBL_REQUIRED_SYMBOL']);
}

$xtpl->assign('GROUP_ID', $groupId);
// auto-reply stuff
$xtpl->assign('FROM_NAME', $from_name);
$xtpl->assign('FROM_ADDR', $from_addr);
$xtpl->assign('DEFAULT_FROM_NAME', $default_from_name);
$xtpl->assign('DEFAULT_FROM_ADDR', $default_from_addr);
$xtpl->assign('REPLY_TO_NAME', $reply_to_name);
$xtpl->assign('REPLY_TO_ADDR', $reply_to_addr);
$createCaseRowStyle = "display:none";
if($focus->template_id) {
	$xtpl->assign("EDIT_TEMPLATE","visibility:inline");
} else {
	$xtpl->assign("EDIT_TEMPLATE","visibility:hidden");
}
if($focus->port == 110 || $focus->port == 995) {
	$xtpl->assign('DISPLAY', "display:''");
} else {
	$xtpl->assign('DISPLAY', "display:none");
}
$leaveMessagesOnMailServerStyle = "display:none";
if($focus->is_personal) {
	$xtpl->assign('DISABLE_GROUP', 'DISABLED');
	$xtpl->assign('EDIT_GROUP_FOLDER_STYLE', "display:none");
	$xtpl->assign('CREATE_GROUP_FOLDER_STYLE', "display:none");
	$xtpl->assign('MAILBOX_TYPE_STYLE', "display:none");
	$xtpl->assign('AUTO_IMPORT_STYLE', "display:none");
} else {
	$folder = new SugarFolder();
	$xtpl->assign('CREATE_GROUP_FOLDER_STYLE', "display:''");
	$xtpl->assign('MAILBOX_TYPE_STYLE', "display:''");
	$xtpl->assign('AUTO_IMPORT_STYLE', "display:''");
	$ret = $folder->getFoldersForSettings($current_user);

	//For existing records, do not allow
	$is_auto_import_disabled = "";
	if (!empty($focus->groupfolder_id))
	{
		$is_auto_import = "checked";
	    $xtpl->assign('EDIT_GROUP_FOLDER_STYLE', "visibility:inline");
		$leaveMessagesOnMailServerStyle = "display:''";
		$allow_outbound = (isset($storedOptions['allow_outbound_group_usage']) && $storedOptions['allow_outbound_group_usage'] == 1) ? 'CHECKED'  : '';
	}
	else
	{
		$xtpl->assign('EDIT_GROUP_FOLDER_STYLE', "visibility:hidden");
	}

	$xtpl->assign('ALLOW_OUTBOUND_USAGE', $allow_outbound);
	$xtpl->assign('IS_AUTO_IMPORT', $is_auto_import);

	if ($focus->isMailBoxTypeCreateCase())
		$createCaseRowStyle = "display:''";

}

$xtpl->assign('hasGrpFld',$focus->groupfolder_id == null ? '' : 'checked="1"');
$xtpl->assign('LEAVEMESSAGESONMAILSERVER_STYLE', $leaveMessagesOnMailServerStyle);
$xtpl->assign('LEAVEMESSAGESONMAILSERVER', get_select_options_with_id($app_list_strings['dom_int_bool'], $leaveMessagesOnMailServer));
$distributionMethod = get_select_options_with_id($app_list_strings['dom_email_distribution_for_auto_create'], $distrib_method);
$xtpl->assign('DISTRIBUTION_METHOD', $distributionMethod);
$xtpl->assign('CREATE_CASE_ROW_STYLE', $createCaseRowStyle);
$xtpl->assign('CREATE_CASE_EMAIL_TEMPLATE_OPTIONS', get_select_options_with_id($email_templates_arr, $create_case_email_template));
if(!empty($create_case_email_template)) {
	$xtpl->assign("CREATE_CASE_EDIT_TEMPLATE","visibility:inline");
} else {
	$xtpl->assign("CREATE_CASE_EDIT_TEMPLATE","visibility:hidden");
}

$quicksearch_js = "";
$teamArr = array();
$teamArr[''] = $app_strings['LBL_NONE'];
$xtpl->assign('PERSONAL', $focus->is_personal);
if($focus->is_personal) {
	$rT = $focus->db->query('SELECT id, name FROM teams WHERE id = \''.$focus->team_id.'\'');
} else {
	$rT = $focus->db->query('SELECT id, name FROM teams WHERE deleted = 0 AND private = 0');
}
while($a = $focus->db->fetchByAssoc($rT)) {
	$teamArr[$a['id']] = $a['name'];
}

$code = "";
if(!empty($focus->team_id)) $my_team_id = $focus->team_id;
else $my_team_id = '';
$team_id = get_select_options_with_id($teamArr, $my_team_id);
if($focus->is_personal) {
	$code = "<select name='team_id' id ='team_id' tabindex='211' disabled >{$team_id}</select>";
} else {
$qsd = QuickSearchDefaults::getQuickSearchDefaults();

$sqs_objects = array(
                     'team_name' => $qsd->getQSTeam(),
);


$teamSetField = new EmailSugarFieldTeamsetCollection($focus, $focus->field_defs, "get_non_private_teams_array");
$sqs_objects = array_merge($sqs_objects, $teamSetField->createQuickSearchCode(false));
$json = getJSONobj();
$quicksearch_js = '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
//add custom fields to validation
foreach ($javascript->sugarbean->field_defs as $field => $value) {
    if(isset($value['custom_type']))
    {
        if ($value['custom_type'] != 'link')
        {
            // fixing bug #49015: The same error message is shown three times
            // all required fields were added to validate before
            if(!isset($value['required']) || !$value['required'])
            {
                //if not required, then just pass in to validate
                $javascript->addField($field, false);
            }
        }
    }
}
$javascript->addFieldGeneric('team_name', 'varchar', $app_strings['LBL_TEAM'] ,'true');
$code = $teamSetField->get_code();
}
$xtpl->assign("TEAM_SET_FIELD", $code);

$xtpl->assign('JAVASCRIPT', get_set_focus_js(). $javascript->getScript() . $quicksearch_js);

require_once('include/SugarSmarty/plugins/function.sugar_help.php');
$tipsStrings = array(
    'LBL_SSL_DESC',
    'LBL_ASSIGN_TO_TEAM_DESC',
    'LBL_ASSIGN_TO_GROUP_FOLDER_DESC',
    'LBL_FROM_ADDR_DESC',
    'LBL_CREATE_CASE_HELP',
    'LBL_CREATE_CASE_REPLY_TEMPLATE_HELP',
    'LBL_ALLOW_OUTBOUND_GROUP_USAGE_DESC',
    'LBL_AUTOREPLY_HELP',
    'LBL_FILTER_DOMAIN_DESC',
    'LBL_MAX_AUTO_REPLIES_DESC',
);
$smarty = null;
$tips = array();
foreach ($tipsStrings as $string)
{
    if (!empty($mod_strings[$string]))
    {
        $tips[$string] = smarty_function_sugar_help(array(
            'text' => $mod_strings[$string]
        ), $smarty);
    }
}
$xtpl->assign('TIPS', $tips);

//Overrides for bounce mailbox accounts
if ($focus->mailbox_type == 'bounce')
{
    $xtpl->assign('MODULE_TITLE', getClassicModuleTitle('InboundEmail', array($mod_strings['LBL_BOUNCE_MODULE_NAME'],$focus->name), true));
    $xtpl->assign("EMAIL_OPTIONS", $mod_strings['LBL_EMAIL_BOUNCE_OPTIONS']);
    $xtpl->assign('MAILBOX_TYPE_STYLE', "display:none");
    $xtpl->assign('AUTO_IMPORT_STYLE', "display:none");
}
elseif ($focus->mailbox_type == 'createcase')
    $xtpl->assign("IS_CREATE_CASE", 'checked');

else if( $focus->is_personal == '1')
     $xtpl->assign('MODULE_TITLE', getClassicModuleTitle('InboundEmail', array($mod_strings['LBL_PERSONAL_MODULE_NAME'],$focus->name), true));

$xtpl->parse("main");
$xtpl->out("main");
?>
