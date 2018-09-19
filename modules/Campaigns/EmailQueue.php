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







global $timedate;
global $current_user;

$db = DBManagerFactory::getInstance();

$campaign = BeanFactory::getBean('Campaigns', $_REQUEST['record']);

$query = "SELECT prospect_list_id as id FROM prospect_list_campaigns WHERE campaign_id=" .
    $db->quoted($campaign->id) .
    " AND deleted=0";

$fromName = $_REQUEST['from_name'];
$fromEmail = $_REQUEST['from_address'];
$date_start = $_REQUEST['date_start'];
$time_start = $_REQUEST['time_start'];
$template_id = $_REQUEST['email_template'];

$dateval = $timedate->merge_date_time($date_start, $time_start);


$listresult = $campaign->db->query($query);

while($list = $campaign->db->fetchByAssoc($listresult))
{
	$prospect_list = $list['id'];
	$focus = BeanFactory::getBean('ProspectLists', $prospect_list);

    $query = "SELECT prospect_id,contact_id,lead_id FROM prospect_lists_prospects WHERE prospect_list_id=" .
        $db->quoted($focus->id) .
        " AND deleted=0";
	$result = $focus->db->query($query);

	while($row = $focus->db->fetchByAssoc($result))
	{
		$prospect_id = $row['prospect_id'];
		$contact_id = $row['contact_id'];
		$lead_id = $row['lead_id'];
		
		if($prospect_id <> '')
		{
			$moduleName = "Prospects";
			$moduleID = $row['prospect_id'];
		}
		if($contact_id <> '')
		{
			$moduleName = "Contacts";
			$moduleID = $row['contact_id'];
		}
		if($lead_id <> '')
		{
			$moduleName = "Leads";
			$moduleID = $row['lead_id'];
		}
		
		$mailer = BeanFactory::newBean('EmailMan');
		$mailer->module = $moduleName;
		$mailer->module_id = $moduleID;
		$mailer->user_id = $current_user->id;
		$mailer->list_id = $prospect_list;
		$mailer->template_id = $template_id;
		$mailer->from_name = $fromName;
		$mailer->from_email = $fromEmail;
		$mailer->send_date_time = $dateval;
		$mailer->save();
	}
	
	
}


$header_URL = "Location: index.php?action=DetailView&module=Campaigns&record={$_REQUEST['record']}";
$GLOBALS['log']->debug("about to post header URL of: $header_URL");

header($header_URL);
?>