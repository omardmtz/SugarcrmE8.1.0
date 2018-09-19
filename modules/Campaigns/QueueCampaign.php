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

 * Description: Schedules email for delivery. emailman table holds emails for delivery.
 * A cron job polls the emailman table and delivers emails when intended send date time is reached.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





global $timedate;
global $current_user;
global $mod_strings;

$campaign = BeanFactory::getBean('Campaigns', $_REQUEST['record']);
$err_messages=array();

$test=false;
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] =='test') {
	$test=true;
}

//this is to account for the case of sending directly from summary page in wizards
$from_wiz =false;
if (isset($_REQUEST['wiz_mass'])) {
    $mass[] = $_REQUEST['wiz_mass'];
    $_POST['mass'] = $mass;
    $from_wiz =true;
}
if (isset($_REQUEST['from_wiz'])) {
    $from_wiz =true;
}

//if campaign status is 'sending' disallow this step.
if (!empty($campaign->status) && $campaign->status == 'sending') {
	$err_messages[]=$mod_strings['ERR_SENDING_NOW'];
}
$current_date = $campaign->db->now();

//start scheduling now.....
foreach ($_POST['mass'] as $message_id) {

	//fetch email marketing definition.


	$marketing = BeanFactory::getBean('EmailMarketing', $message_id);

	//make sure that the marketing message has a mailbox.
	//
	if (empty($marketing->inbound_email_id)) {

		echo "<p>";
		echo "<h4>{$mod_strings['ERR_NO_MAILBOX']}</h4>";
		echo "<BR><a href='index.php?module=EmailMarketing&action=EditView&record={$marketing->id}'>$marketing->name</a>";
		echo "</p>";
		sugar_die('');
	}


	global $timedate;
	$mergedvalue=$timedate->merge_date_time($marketing->date_start,$marketing->time_start);
	if($test) {
	    $send_date_time = $timedate->getNow()->get("-60 seconds")->asDb();
	} else {
	    $send_date_time = $timedate->to_db($mergedvalue);
	}

	//find all prospect lists associated with this email marketing message.
	if ($marketing->all_prospect_lists == 1) {
		$query="SELECT prospect_lists.id prospect_list_id from prospect_lists ";
		$query.=" INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = prospect_lists.id";
        $query.=" WHERE plc.campaign_id=" . $campaign->db->quoted($campaign->id);
		$query.=" AND prospect_lists.deleted=0";
		$query.=" AND plc.deleted=0";
		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";
		}
	} else {
		$query="select email_marketing_prospect_lists.* FROM email_marketing_prospect_lists ";
		$query.=" inner join prospect_lists on prospect_lists.id = email_marketing_prospect_lists.prospect_list_id";
        $query.=" WHERE prospect_lists.deleted=0 and email_marketing_id = " . $campaign->db->quoted($message_id) .
            " and email_marketing_prospect_lists.deleted=0";

		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";
		}
	}
	$result=$campaign->db->query($query);
	while (($row=$campaign->db->fetchByAssoc($result))!=null ) {
		$prospect_list_id=$row['prospect_list_id'];

		//delete all messages for the current campaign and current email marketing message.
        $delete_emailman_query="delete from emailman where campaign_id=".$campaign->db->quoted($campaign->id) .
            " and marketing_id=" . $campaign->db->quoted($message_id) .
            " and list_id=".$campaign->db->quoted($prospect_list_id);
		$campaign->db->query($delete_emailman_query);
        $auto = $campaign->db->getAutoIncrementSQL("emailman", "id");

		$insert_query= "INSERT INTO emailman (date_entered, user_id, campaign_id, marketing_id,list_id, related_id, related_type, send_date_time";
		$insert_query.= empty($auto)?"":",id";
		$insert_query.=')';
        $insert_query.= " SELECT $current_date,".$campaign->db->quoted($current_user->id).",plc.campaign_id,".
            $campaign->db->quoted($message_id).",plp.prospect_list_id, plp.related_id, plp.related_type,".
            $campaign->db->convert($campaign->db->quoted($send_date_time), 'datetime');
		$insert_query.= empty($auto)?"":",$auto";
		$insert_query.= " FROM prospect_lists_prospects plp ";
		$insert_query.= "INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = plp.prospect_list_id ";
        $insert_query.= "WHERE plp.prospect_list_id = ".$campaign->db->quoted($prospect_list_id);
		$insert_query.= "AND plp.deleted=0 ";
		$insert_query.= "AND plc.deleted=0 ";
        $insert_query.= "AND plc.campaign_id=".$campaign->db->quoted($campaign->id);

		$campaign->db->query($insert_query);
	}
}

//delete all entries from the emailman table that belong to the exempt list.
//TODO:SM: may want to move this to query clause above instead
if (!$test) {
    $delete_query =  "DELETE FROM emailman WHERE emailman.campaign_id='{$campaign->id}' AND (emailman.related_id, emailman.related_type) IN
    	(SELECT plp.related_id, plp.related_type FROM prospect_lists_prospects plp
    		INNER JOIN prospect_lists pl ON pl.id = plp.prospect_list_id
    		INNER JOIN prospect_list_campaigns plc ON plp.prospect_list_id = plc.prospect_list_id
    		WHERE plp.deleted = 0 AND plc.deleted = 0 AND pl.deleted = 0 AND pl.list_type = 'exempt' AND  plc.campaign_id = '{$campaign->id}')
    	";
    $campaign->db->query($delete_query);
}

$return_module=isset($_REQUEST['return_module'])?$_REQUEST['return_module']:'Campaigns';
$return_action=isset($_REQUEST['return_action'])?$_REQUEST['return_action']:'DetailView';
$return_id=$_REQUEST['record'];

if ($test) {
	//navigate to EmailManDelivery..
	$header_URL = "Location: index.php?action=EmailManDelivery&module=EmailMan&campaign_id={$_REQUEST['record']}&return_module={$return_module}&return_action={$return_action}&return_id={$return_id}&mode=test";
    if($from_wiz){$header_URL .= "&from_wiz=true";}
} else {
	//navigate back to campaign detail view...
	$header_URL = "Location: index.php?action={$return_action}&module={$return_module}&record={$return_id}";
    if($from_wiz){$header_URL .= "&from=send";}
}
$GLOBALS['log']->debug("about to post header URL of: $header_URL");
header($header_URL);
