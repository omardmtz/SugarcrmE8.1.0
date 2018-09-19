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
use Sugarcrm\Sugarcrm\Util\Arrays\ArrayFunctions\ArrayFunctions;

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $current_user;

$focus = BeanFactory::newBean('InboundEmail');
$focus->disable_row_level_security = true;
if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
} elseif(!empty($_REQUEST['origin_id'])) {
    $focus->retrieve($_REQUEST['origin_id']);
    unset($focus->id);
    unset($focus->groupfolder_id);
}
foreach($focus->column_fields as $field) {
    if($field == 'email_password' && empty($_REQUEST['email_password']) && !empty($_REQUEST['email_user'])) {
        continue;
    }
	if(isset($_REQUEST[$field])) {
		if ($field != "group_id") {
			$focus->$field = trim(InputValidation::getService()->getValidInputRequest($field));
		}
	}
}
foreach($focus->additional_column_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$focus->$field = trim(InputValidation::getService()->getValidInputRequest($field));
	}
}
foreach($focus->required_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$focus->$field = trim(InputValidation::getService()->getValidInputRequest($field));
	}
}

if(!empty($_REQUEST['email_password'])) {
    $focus->email_password = $_REQUEST['email_password'];
}

$focus->protocol = $_REQUEST['protocol'];

if( isset($_REQUEST['is_create_case']) && $_REQUEST['is_create_case'] == 'on' )
    $focus->mailbox_type = 'createcase';
else
{
    if( empty($focus->mailbox_type) || $focus->mailbox_type == 'createcase' )
        $focus->mailbox_type = 'pick';
}

/////////////////////////////////////////////////////////
////	SERVICE STRING CONCATENATION
$useSsl = (isset($_REQUEST['ssl']) && $_REQUEST['ssl'] == 1) ? true : false;
$optimum = $focus->getSessionConnectionString($focus->server_url, $focus->email_user, $focus->port, $focus->protocol);
if (empty($optimum)) {
	$optimum = $focus->findOptimumSettings($useSsl, $focus->email_user, $focus->email_password, $focus->server_url, $focus->port, $focus->protocol, $focus->mailbox);
} // if
$delimiter = $focus->getSessionInboundDelimiterString($focus->server_url, $focus->email_user, $focus->port, $focus->protocol);

//added check to ensure the $optimum['serial']) is not empty.
if (ArrayFunctions::is_array_access($optimum) && (count($optimum) > 0) && !empty($optimum['serial'])) {
	$focus->service = $optimum['serial'];
} else {
	// no save
	// allowing bad save to allow Email Campaigns configuration to continue even without IMAP
	$focus->service = "::::::".$focus->protocol."::::"; // save bogus info.
	$error = "&error=true";
}
////	END SERVICE STRING CONCAT
/////////////////////////////////////////////////////////

if(isset($_REQUEST['mark_read']) && $_REQUEST['mark_read'] == 1) {
	$focus->delete_seen = 0;
} else {
	$focus->delete_seen = 0;
}

// handle stored_options serialization
if(isset($_REQUEST['only_since']) && $_REQUEST['only_since'] == 1) {
	$onlySince = true;
} else {
	$onlySince = false;
}
$stored_options = array();
$stored_options['from_name'] = trim($_REQUEST['from_name']);
$stored_options['from_addr'] = trim($_REQUEST['from_addr']);
$stored_options['reply_to_name'] = trim($_REQUEST['reply_to_name']);
$stored_options['reply_to_addr'] = trim($_REQUEST['reply_to_addr']);
$stored_options['only_since'] = $onlySince;
$stored_options['filter_domain'] = $_REQUEST['filter_domain'];
$stored_options['email_num_autoreplies_24_hours'] = $_REQUEST['email_num_autoreplies_24_hours'];
$stored_options['allow_outbound_group_usage'] = isset($_REQUEST['allow_outbound_group_usage']) ? true : false;

if (!$focus->isPop3Protocol()) {
	$stored_options['trashFolder'] = (isset($_REQUEST['trashFolder']) ? trim($_REQUEST['trashFolder']) : "");
	$stored_options['sentFolder'] = (isset($_REQUEST['sentFolder']) ? trim($_REQUEST['sentFolder']) : "");
} // if
if ( $focus->isMailBoxTypeCreateCase() || ($focus->mailbox_type == 'createcase' && empty($_REQUEST['id']) ) )
{
	$stored_options['distrib_method'] = (isset($_REQUEST['distrib_method'])) ? $_REQUEST['distrib_method'] : "";
	$stored_options['create_case_email_template'] = (isset($_REQUEST['create_case_template_id'])) ? $_REQUEST['create_case_template_id'] : "";
} // if
$storedOptions['folderDelimiter'] = $delimiter;

////////////////////////////////////////////////////////////////////////////////
////    CREATE MAILBOX QUEUE
////////////////////////////////////////////////////////////////////////////////
if (!isset($focus->id)) {
	$groupId = "";
	if (isset($_REQUEST['group_id']) && empty($_REQUEST['group_id'])) {
		$groupId = $_REQUEST['group_id'];
	} else {
		$groupId = create_guid();
	}
	$focus->group_id = $groupId;
}

// teams
if(!empty($_REQUEST['team_id'])) {
	$focus->team_id = $_REQUEST['team_id'];
}

$sfh = new SugarFieldHandler();

foreach($focus->field_defs as $field=>$def) {
	$type = !empty($def['custom_type']) ? $def['custom_type'] : $def['type'];
	if (($type == 'team_list') || ($type == 'teamset')) {
		$sf = $sfh->getSugarField(ucfirst($type), true);
	    if($sf != null){
	        $sf->save($focus, $_POST, $field, $def);
	    } // if
	} // if

} // if

if( isset($_REQUEST['is_auto_import']) && $_REQUEST['is_auto_import'] == 'on' )
{
    if( empty($focus->groupfolder_id) )
    {
        $groupFolderId = $focus->createAutoImportSugarFolder();
        $focus->groupfolder_id = $groupFolderId;
    }
    $stored_options['isAutoImport'] = true;
}
else
{
    $focus->groupfolder_id = "";
    //If the user is turning the auto-import feature off then remove all previous subscriptions.
    if( !empty($focus->fetched_row['groupfolder_id'] ) )
    {
        $GLOBALS['log']->debug("Clearining all subscriptions to folder id: {$focus->fetched_row['groupfolder_id']}");
        $f = new SugarFolder();
        $f->clearSubscriptionsForFolder($focus->fetched_row['groupfolder_id']);
        //Now delete the old group folder.
        $f->retrieve($focus->fetched_row['groupfolder_id']);
        $f->delete();
    }
    $stored_options['isAutoImport'] = false;
}

if (!empty($focus->groupfolder_id))
{
	if ($_REQUEST['leaveMessagesOnMailServer'] == "1")
		$stored_options['leaveMessagesOnMailServer'] = 1;
	else
		$stored_options['leaveMessagesOnMailServer'] = 0;
}

$focus->stored_options = base64_encode(serialize($stored_options));
$GLOBALS['log']->info('----->InboundEmail now saving self');


////////////////////////////////////////////////////////////////////////////////
////    SEND US TO SAVE DESTINATION
////////////////////////////////////////////////////////////////////////////////

//When an admin is creating an IE account we do not want their private team to be added
//or they may be included in a round robin assignment.
$previousTeamAccessCheck = isset($GLOBALS['sugar_config']['disable_team_access_check']) ? $GLOBALS['sugar_config']['disable_team_access_check'] : null;
$GLOBALS['sugar_config']['disable_team_access_check'] = TRUE;

$monitor_fields = array(
    'name',
    'status',
    'team_id',
    'team_set_id',
    'acl_team_set_id',
);

$current_monitor_fields = array();
foreach ($monitor_fields as $singleField) {
    if(isset($focus->fetched_row[$singleField])) {
        $current_monitor_fields[$singleField] = $focus->fetched_row[$singleField];
    }
}

$focus->save();

//Reset the value so no other saves are affected.
$GLOBALS['sugar_config']['disable_team_access_check'] = $previousTeamAccessCheck;

//For new group IE accounts, create default subscriptions for all direct team members.
if( empty($_REQUEST['id']) && empty($focus->groupfolder_id) )
    $focus->createUserSubscriptionsForGroupAccount();

//Only sync IE accounts with a group folder.  Need to sync new records as team set assignment is processed
//after save.
if( !empty($focus->groupfolder_id) )
{
    foreach ($monitor_fields as $singleField)
    {
        //Check if the value is being changed during save.
        if($current_monitor_fields[$singleField] != $focus->$singleField)
            syncSugarFoldersWithBeanChanges($singleField, $focus);
    }
}

//check if we are in campaigns module
if($_REQUEST['module'] == 'Campaigns'){
    //this is coming from campaign wizard, Just set the error message if it exists and skip the redirect
    if(!empty($error)){
        $_REQUEST['error'] = true;
    }
}else{

    //this is a normal Inbound Email save, so set up the url and reirect
    $_REQUEST['return_id'] = $focus->id;

    $edit='';
    if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") {
        $return_module = $_REQUEST['return_module'];
    } else {
        $return_module = "InboundEmail";
    }
    if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") {
        $return_action = $_REQUEST['return_action'];
    } else {
        $return_action = "DetailView";
    }
    if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") {
        $return_id = $_REQUEST['return_id'];
    }
    if(!empty($_REQUEST['edit'])) {
        $return_id='';
        $edit='&edit=true';
    }

    $GLOBALS['log']->debug("Saved record with id of ".$return_id);


    header("Location: index.php?module=$return_module&action=$return_action&record=$return_id$edit$error");
}

/**
 * Certain updates to the IE account need to be reflected in the related SugarFolder since they are
 * created automatically.  Only valid for IE accounts with auto import turned on.
 *
 * @param string $fieldName The field name that changed
 * @param SugarBean $focus The InboundEmail bean being saved.
 */
function syncSugarFoldersWithBeanChanges($fieldName, $focus)
{
    $f = new SugarFolder();
    $f->retrieve($focus->groupfolder_id);

    switch ($fieldName)
    {
        case 'name':
        case 'team_id':
        case 'team_set_id':
        case 'acl_team_set_id':
            $f->$fieldName = $focus->$fieldName;
            $f->save();
            break;

        case 'status':
            if($focus->status == 'Inactive')
                $f->clearSubscriptionsForFolder($focus->groupfolder_id);
            else if($focus->mailbox_type != 'bounce' )
                $f->addSubscriptionsToGroupFolder();
            break;
    }
}

