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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $timedate;
global $current_user;

$request = InputValidation::getService();
$meridiem = $request->getValidInputPost('meridiem');
$timeStartPost = $request->getValidInputPost('time_start');
if(!empty($meridiem)){
	$timeStartPost = $_POST['time_start'] = $timedate->merge_time_meridiem(
		$timeStartPost,
		$timedate->get_time_format(),
		$meridiem
	);
}

$timeStartRequest = $request->getValidInputRequest('time_start');
$dateStartRequest = $request->getValidInputRequest('date_start');
$dateStartPost = $request->getValidInputPost('date_start');

if (empty($timeStartRequest)) {
    $timeToFix = date($timedate->get_date_time_format(), strtotime($dateStartPost));
	$dateStartRequest = $_REQUEST['date_start'] = $timeToFix;
	$dateStartPost = $_POST['date_start'] = $timeToFix;
} else {
	$dateStartRequest = $_REQUEST['date_start'] = $dateStartRequest . ' ' . $timeStartRequest;
	$dateStartPost = $_POST['date_start'] = $dateStartPost . ' ' . $timeStartPost;
}

$record = $request->getValidInputPost('record', 'Assert\Guid');

$marketing = BeanFactory::newBean('EmailMarketing');
if (!empty($record)) {
	$marketing->retrieve($record);
}
if(!$marketing->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}

$assignedUserId = $request->getValidInputPost('assigned_user_id', 'Assert\Guid');

if (!empty($assignedUserId) && ($marketing->assigned_user_id != $assignedUserId) && ($assignedUserId != $current_user->id)) {
	$check_notify = TRUE;
}
else {
	$check_notify = FALSE;
}
foreach($marketing->column_fields as $field)
{
	if ($field == 'all_prospect_lists') {
		if(isset($_POST[$field]) && $_POST[$field]='on' )
		{
			$marketing->$field = 1;
		} else {
			$marketing->$field = 0;			
		}
	}else {
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$marketing->$field = $value;
		}
	}
}

foreach($marketing->additional_column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$marketing->$field = $value;

	}
}
$campaignId = $request->getValidInputRequest('campaign_id', 'Assert\Guid');


$marketing->campaign_id = $campaignId;
$marketing->save($check_notify);

//add prospect lists to campaign.
$marketing->load_relationship('prospectlists');
$prospectlists=$marketing->prospectlists->get();
if ($marketing->all_prospect_lists==1) {
	//remove all related prospect lists.
	if (!empty($prospectlists)) {
		$marketing->prospectlists->delete($marketing->id);
	}
} else {
	if (is_array($_REQUEST['message_for'])) {
		foreach ($_REQUEST['message_for'] as $prospect_list_id) {
			
			$key=array_search($prospect_list_id,$prospectlists);
			if ($key === null or $key === false) {
				$marketing->prospectlists->add($prospect_list_id);			
			} else {
				unset($prospectlists[$key]);
			}
		}
		if (count($prospectlists) != 0) {
			foreach ($prospectlists as $key=>$list_id) {
				$marketing->prospectlists->delete($marketing->id,$list_id);				
			}	
		}
	}
}

$action = $request->getValidInputRequest('action');

if($action != 'WizardMarketingSave'){
    $header_URL = "Location: index.php?action=DetailView&module=Campaigns&record={$campaignId}";
    $GLOBALS['log']->debug("about to post header URL of: $header_URL");
    header($header_URL);
}
