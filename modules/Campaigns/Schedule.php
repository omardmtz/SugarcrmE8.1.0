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

 * Description: 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 






    $test=false;
//account for case when called from marketing wizard
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'WizardMarketing'){
    $_POST['return_module'] = $_REQUEST['return_module'];
    $_POST['return_action'] = 'TrackDetailView';
    $_POST['record'] = $_REQUEST['record'];    
}


if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'test') {
    $test=true;
    $_POST['mode'] = 'test';
}


global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $urlPrefix;
global $currentModule;

$current_module_strings = return_module_language($current_language, 'EmailMarketing');
if ($test)  {
	echo getClassicModuleTitle('Campaigns', array($current_module_strings['LBL_MODULE_SEND_TEST']), false);
} else {
	echo getClassicModuleTitle('Campaigns', array($current_module_strings['LBL_MODULE_SEND_EMAILS']), false);
}

$campaign_id = isset($_REQUEST['record']) ? $_REQUEST['record'] : false;

if (!empty($campaign_id)) {
	$campaign = BeanFactory::getBean('Campaigns', $campaign_id);
}

if ($campaign_id && isset($campaign) && $campaign->status == 'Inactive') {
	$ss = new Sugar_Smarty();

    $data = array($campaign->name);
    $ss->assign('campaignInactive', string_format(translate('LBL_CAMPAIGN_INACTIVE_SCHEDULE', 'Campaigns'), $data));

	$ss->display('modules/Campaigns/tpls/campaign-inactive.tpl');
} else {
	$focus = BeanFactory::newBean('EmailMarketing');
	if($campaign_id)
	{
		$where_clauses = Array();

		if(!empty($campaign_id)) array_push($where_clauses, "campaign_id = '".$GLOBALS['db']->quote($campaign_id)."'");

		$where = "";
		foreach($where_clauses as $clause)
		{
			if($where != "")
			$where .= " and ";
			$where .= $clause;
		}

		$GLOBALS['log']->info("Here is the where clause for the list view: $where");
	}

	$ListView = new ListView();
	$ListView->initNewXTemplate('modules/Campaigns/Schedule.html',$current_module_strings);

	if ($test)  {
		$ListView->xTemplateAssign("SCHEDULE_MESSAGE_HEADER",$current_module_strings['LBL_SCHEDULE_MESSAGE_TEST']);
	} else {
		$ListView->xTemplateAssign("SCHEDULE_MESSAGE_HEADER",$current_module_strings['LBL_SCHEDULE_MESSAGE_EMAILS']);
	}

	//force multi-select popup
	$ListView->process_for_popups=true;
	$ListView->multi_select_popup=true;
	//end
	$ListView->mergeduplicates = false;
	$ListView->show_export_button = false;
	$ListView->show_select_menu = false;
	$ListView->show_delete_button = false;
	$ListView->setDisplayHeaderAndFooter(false);
	$ListView->xTemplateAssign("RETURN_MODULE", isset($_POST['return_module']) ? $_POST['return_module'] : false);
	$ListView->xTemplateAssign("RETURN_ACTION", isset($_POST['return_action']) ? $_POST['return_action'] : false);
	$ListView->xTemplateAssign("RETURN_ID", isset($_POST['record']) ? $_POST['record'] : false);
	$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
	$ListView->setQuery($where, "", "name", "EMAILMARKETING");

	if ($test) {
			$ListView->xTemplateAssign("MODE",$_POST['mode']);
			//finds all marketing messages that have an association with prospect list of the test.
			//this query can be siplified using sub-selects.
			$query="select distinct email_marketing.id email_marketing_id from email_marketing ";
			$query.=" inner join email_marketing_prospect_lists empl on empl.email_marketing_id = email_marketing.id ";
			$query.=" inner join prospect_lists on prospect_lists.id = empl.prospect_list_id ";
			$query.=" inner join prospect_list_campaigns plc on plc.prospect_list_id = empl.prospect_list_id ";
			$query.=" where empl.deleted=0  ";
			$query.=" and prospect_lists.deleted=0 ";
			$query.=" and prospect_lists.list_type='test' ";
			$query.=" and plc.deleted=0 ";
            $query.=" and plc.campaign_id=" . $focus->db->quoted($campaign_id);
            $query.=" and email_marketing.campaign_id=" . $focus->db->quoted($campaign_id);
			$query.=" and email_marketing.deleted=0 ";
			$query.=" and email_marketing.all_prospect_lists=0 ";

			$seed=array();

			$result=$focus->db->query($query);
			while(($row=$focus->db->fetchByAssoc($result)) != null) {

				$bean = BeanFactory::getBean('EmailMarketing', $row['email_marketing_id']);
				$bean->mode='test';	
				$seed[]=$bean;
			}
			$query=" select email_marketing.id email_marketing_id from email_marketing ";
            $query.=" WHERE email_marketing.campaign_id=" . $focus->db->quoted($campaign_id);
			$query.=" and email_marketing.deleted=0 ";
			$query.=" and email_marketing.all_prospect_lists=1 ";

			$result=$focus->db->query($query);
			while(($row=$focus->db->fetchByAssoc($result)) != null) {

				$bean = BeanFactory::getBean('EmailMarketing', $row['email_marketing_id']);
				$bean->mode='test';	
				$seed[]=$bean;
			}

			$ListView->processListView($seed, "main", "EMAILMARKETING");
	} else {
		$ListView->processListView($focus, "main", "EMAILMARKETING");
	}
}
?>