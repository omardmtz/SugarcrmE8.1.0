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

 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



$focus = BeanFactory::newBean('Tasks');
if (!isset($prefix)) $prefix='';

global $timedate;
$time_format = $timedate->get_user_time_format();
$time_separator = ":";
if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
   $time_separator = $match[1];
}

if(!empty($_POST[$prefix.'due_meridiem'])) {
	$_POST[$prefix.'time_due'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_due'],$timedate->get_time_format(), $_POST[$prefix.'due_meridiem']);
}

if(!empty($_POST[$prefix.'start_meridiem'])) {
	$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(), $_POST[$prefix.'start_meridiem']);
}

if(isset($_POST[$prefix.'time_due']) && !empty($_POST[$prefix.'time_due'])) {
   $_POST[$prefix.'date_due'] = $_POST[$prefix.'date_due'] . ' ' . $_POST[$prefix.'time_due'];
}

if(isset($_POST[$prefix.'time_start']) && !empty($_POST[$prefix.'time_start'])) {
   $_POST[$prefix.'date_start'] = $_POST[$prefix.'date_start'] . ' ' . $_POST[$prefix.'time_start'];
}

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}

if (isCloseAndCreateNewPressed()) $focus->status = 'Completed';
if (!isset($_POST['date_due_flag'])) $focus->date_due_flag = 0;
if (!isset($_POST['date_start_flag'])) $focus->date_start_flag = 0;
if($focus->date_due_flag != 'off' && $focus->date_due_flag != 1) {
   $focus->date_due = '';
   $focus->time_due = '';
}

//if only the time is passed in, without a date, then string length will be 7
if (isset($_REQUEST['date_due']) && strlen(trim($_REQUEST['date_due']))<8 ){
    //no date set, so clear out field, and set the rest flag to true
    $focus->date_due_flag = 1;
    $focus->date_due = '';    
}

//if only the time is passed in, without a date, then string length will be 7
if (isset($_REQUEST['date_start']) && strlen(trim($_REQUEST['date_start']))<8){
    //no date set, so clear out field, and set the rest flag to true
    $focus->date_start_flag = 1;
    $focus->date_start = '';
}



///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['inbound_email_id']) && !empty($_REQUEST['inbound_email_id'])) {
	// fake this case like it's already saved.
	$focus->save();
	
	$email = BeanFactory::getBean('Emails', $_REQUEST['inbound_email_id']);
	$email->parent_type = 'Tasks';
	$email->parent_id = $focus->id;
	$email->assigned_user_id = $current_user->id;
	$email->status = 'read';
	$email->save();
	$email->load_relationship('tasks');
	$email->tasks->add($focus->id);
	
	header("Location: index.php?&module=Emails&action=EditView&type=out&inbound_email_id=".$_REQUEST['inbound_email_id']."&parent_id=".$email->parent_id."&parent_type=".$email->parent_type.'&start='.$_REQUEST['start'].'&assigned_user_id='.$current_user->id);
	exit();
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////	

// CCL - Bugs 41103 and 43751.  41103 address the issue where the parent_id is set, but
// the relate_id field overrides the relationship.  43751 fixes the problem where the relate_id and
// parent_id are the same value (in which case it should just use relate_id) by adding the != check
if ((!empty($_REQUEST['relate_id']) && !empty($_REQUEST['parent_id'])) && ($_REQUEST['relate_id'] != $_REQUEST['parent_id']))
{
	$_REQUEST['relate_id'] = false;
}

// avoid undefined index
if (!isset($GLOBALS['check_notify'])) {
	$GLOBALS['check_notify'] = false;
}
$focus->save($GLOBALS['check_notify']);
$return_id = $focus->id;

handleRedirect($return_id,'Tasks');
?>
