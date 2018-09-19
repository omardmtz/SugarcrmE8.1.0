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

///////////////////////////////////////////////////////////////////////////////
////	EMAIL SEND/SAVE SETUP
$focus = BeanFactory::newBean('Emails');

if(!isset($prefix)) {
	$prefix = '';
}
if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])) {
	$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'], $timedate->get_time_format(), $_POST[$prefix.'meridiem']);
}
//retrieve the record
if(isset($_POST['record']) && !empty($_POST['record'])) {
	$focus->retrieve($_POST['record']);

}
if(isset($_REQUEST['user_id'])) {
	$focus->assigned_user_id = $_REQUEST['user_id'];
}
if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}
if(!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$check_notify = TRUE;
}
//populate the fields of this Email
$allfields = array_merge($focus->column_fields, $focus->additional_column_fields);
foreach($allfields as $field) {
	if(isset($_POST[$field])) {
		$value = $_POST[$field];
		$focus->$field = $value;
	}
}

$focus->description = $_REQUEST['description_html'];
$focus->description_html = $_REQUEST['description_html'];

if (!isset($_REQUEST['to_addrs'])) {
	$_REQUEST['to_addrs'] = "";
}
if (!isset($_REQUEST['to_addrs_ids'])) {
	$_REQUEST['to_addrs_ids'] = "";
}
if (!isset($_REQUEST['to_addrs_names'])) {
	$_REQUEST['to_addrs_names'] = "";
}
if (!isset($_REQUEST['to_addrs_emails'])) {
	$_REQUEST['to_addrs_emails'] = "";
}

//compare the 3 fields and return list of contact_ids to link:
$focus->to_addrs_arr = $focus->parse_addrs($_REQUEST['to_addrs'], $_REQUEST['to_addrs_ids'], $_REQUEST['to_addrs_names'], $_REQUEST['to_addrs_emails']);

// make sure the cc_* and bcc_* fields are at least empty if not set
$fields_to_check = array(
	'cc_addrs',
	'cc_addrs_ids',
	'bcc_addrs',
	'bcc_addrs_ids',
	'cc_addrs_names',
	'cc_addrs_emails',
	'bcc_addrs_emails',
);
foreach ($fields_to_check as $field_to_check) {
	if (!isset($_REQUEST[$field_to_check])) {
		$_REQUEST[$field_to_check] = '';
	}
}

$focus->cc_addrs_arr = $focus->parse_addrs($_REQUEST['cc_addrs'], $_REQUEST['cc_addrs_ids'], $_REQUEST['cc_addrs_names'], $_REQUEST['cc_addrs_emails']);
$focus->bcc_addrs_arr = $focus->parse_addrs($_REQUEST['bcc_addrs'], $_REQUEST['bcc_addrs_ids'], $_REQUEST['to_addrs_names'], $_REQUEST['bcc_addrs_emails']);


if(!empty($_REQUEST['type'])) {
	$focus->type = $_REQUEST['type'];
} elseif(empty($focus->type)) { // cn: from drafts/quotes
	$focus->type = 'archived';
    $focus->state = Email::STATE_ARCHIVED;
}

///////////////////////////////////////////////////////////////////////////////
////	TEMPLATE PARSING
// cn: bug 7244 - need to pass an empty bean to parse email templates
$object_arr = array();
if(!empty($focus->parent_id)) {
	$object_arr[$focus->parent_type] = $focus->parent_id;
}
if(isset($focus->to_addrs_arr[0]['contact_id'])) {
	$object_arr['Contacts'] = $focus->to_addrs_arr[0]['contact_id'];
}
if(empty($object_arr)) {
	$object_arr = array('Contacts' => '123');
}

// do not parse email templates if the email is being saved as draft....
if($focus->type != 'draft' && count($object_arr) > 0) {
	require_once($beanFiles['EmailTemplate']);
	$focus->name = EmailTemplate::parse_template($focus->name, $object_arr);
	$focus->description = EmailTemplate::parse_template($focus->description, $object_arr);
    $focus->description_html = EmailTemplate::parse_template($focus->description_html, $object_arr, true);
}
////	END TEMPLATE PARSING
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	PREP FOR ATTACHMENTS
if(empty($focus->id)){
    $focus->id = create_guid();
    $focus->new_with_id = true;
}
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	ATTACHMENT HANDLING
$focus->handleAttachments();
////	END ATTACHMENT HANDLING
///////////////////////////////////////////////////////////////////////////////
$focus->status = 'draft';
$focus->state = Email::STATE_DRAFT;
if($focus->type == 'archived' ) {
	$focus->status= 'archived';
    $focus->state = Email::STATE_ARCHIVED;
	$focus->date_start = $_REQUEST['date_start'];
	$focus->time_start = $_REQUEST['time_start'] . $_REQUEST['meridiem'];
} elseif(($focus->type == 'out' || $focus->type == 'forward') && isset($_REQUEST['send']) && $_REQUEST['send'] == '1') {
	///////////////////////////////////////////////////////////////////////////
	////	REPLY PROCESSING
	$old = array('&lt;','&gt;');
	$new = array('<','>');

	if($_REQUEST['from_addr'] != $_REQUEST['from_addr_name'].' &lt;'.$_REQUEST['from_addr_email'].'&gt;') {
		if(false === strpos($_REQUEST['from_addr'], '&lt;')) { // we have an email only?
			$focus->from_addr = $_REQUEST['from_addr'];
			$focus->from_name = '';
		} else { // we have a compound string
			$newFromAddr =  str_replace($old, $new, $_REQUEST['from_addr']);
			$focus->from_addr = substr($newFromAddr, (1 + strpos($newFromAddr, '<')), (strpos($newFromAddr, '>') - strpos($newFromAddr, '<')) -1 );
			$focus->from_name = substr($newFromAddr, 0, (strpos($newFromAddr, '<') -1));
		}
	} elseif(!empty($_REQUEST['from_addr_email']) && isset($_REQUEST['from_addr_email'])) {
		$focus->from_addr = $_REQUEST['from_addr_email'];
		$focus->from_name = $_REQUEST['from_addr_name'];
	} else {
		$focus->from_addr = $focus->getSystemDefaultEmail();
	}
	////	REPLY PROCESSING
	///////////////////////////////////////////////////////////////////////////
	if($focus->send()) {
        $focus->status = 'sent';
        $focus->state = Email::STATE_ARCHIVED;
	} else {
		$focus->status = 'send_error';
        $focus->state = Email::STATE_DRAFT;
	}
}
$focus->to_addrs = $_REQUEST['to_addrs'];
$focus->cc_addrs = $_REQUEST['cc_addrs'];
$focus->bcc_addrs = $_REQUEST['bcc_addrs'];
$focus->from_addr = $_REQUEST['from_addr'];

// delete the existing relationship of all the email addresses with this email
$query = "update emails_email_addr_rel set deleted = 1 WHERE email_id = '{$focus->id}'";
$focus->db->query($query);

// delete al the relationship of this email with all the beans
if(!empty($_REQUEST['to_addrs_ids'])) {
    $exContactIds = explode(';', $_REQUEST['to_addrs_ids']);
} else {
    $exContactIds = array();
}

if(isset($_REQUEST['object_type']) && !empty($_REQUEST['object_type']) && isset($_REQUEST['object_id']) && !empty($_REQUEST['object_id'])) {
    //run linking code only if the object_id has not been linked as part of the contacts above and it is an OOB relationship
    $GLOBALS['log']->debug("CESELY".$_REQUEST['object_type']);
    if(!in_array($_REQUEST['object_id'],$exContactIds)){
        $rel = strtolower($_REQUEST['object_type']);
        if ($focus->load_relationship($rel)) {
        	$focus->$rel->add($_REQUEST['object_id']);
        	$GLOBALS['log']->debug("CESELY LOADED".$_REQUEST['object_type']);
        }
    }
}
////    END RELATIONSHIP LINKING
///////////////////////////////////////////////////////////////////////////////

// If came from email archiving edit view, this would have been set from form input.
if (!isset($focus->date_start))
{
    $timedate = TimeDate::getInstance();
	list($focus->date_start,  $focus->time_start) = $timedate->split_date_time($timedate->now());
}

$focus->date_sent = "";

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

//CCL - Bug: 40168 Fix ability to change date sent from saved emails
if($focus->type == 'archived') 
{	
  $focus->date_start = $_REQUEST['date_start'];
  $focus->time_start = $_REQUEST['time_start'] . $_REQUEST['meridiem'];
  $focus->date_sent = '';	
}

$focus->save(false);
////	END EMAIL SAVE/SEND SETUP
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	RELATIONSHIP LINKING
$focus->load_relationship('users');
$focus->users->add($current_user->id);

if(!empty($exContactIds)) {
	$focus->load_relationship('contacts');
	foreach($exContactIds as $contactId) {
		$contactId = trim($contactId);
		$focus->contacts->add($contactId);
	}
}

///////////////////////////////////////////////////////////////////////////////
////	PAGE REDIRECTION
///////////////////////////////////////////////////////////////////////////////
$return_id = $focus->id;

if(empty($_POST['return_module'])) {
	$return_module = "Emails";
} else {
	$return_module = $_POST['return_module'];
}
if(empty($_POST['return_action'])) {
	$return_action = "DetailView";
} else {
	$return_action = $_POST['return_action'];
}
$GLOBALS['log']->debug("Saved record with id of ".$return_id);

if($focus->type == 'draft') {
	if($return_module == 'Emails') {
		header("Location: index.php?module=$return_module&action=ListViewDrafts");
	} else {
        handleRedirect($return_id, 'Emails');
	}
} elseif($focus->type == 'out') {
	if($return_module == 'Home') {
		header('Location: index.php?module='.$return_module.'&action=index');
	}
	if(!empty($_REQUEST['return_id'])) {
		$return_id = $_REQUEST['return_id'];
	}
	header('Location: index.php?action='.$return_action.'&module='.$return_module.'&record='.$return_id.'&assigned_user_id='.$current_user->id.'&type=inbound');
} elseif(isset($_POST['return_id']) && $_POST['return_id'] != "") {
	$return_id = $_POST['return_id'];
}
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
