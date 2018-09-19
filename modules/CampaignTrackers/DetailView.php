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






global $app_strings;
global $mod_strings;

$focus = BeanFactory::getBean('CampaignTrackers', $_REQUEST['record']);

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->tracker_name), true);



$GLOBALS['log']->info("campaign tracker detail view");

$xtpl=new XTemplate ('modules/CampaignTrackers/DetailView.html');
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
	$xtpl->assign("RETURN_ID", $focus->campaign_id);
}
 
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
if (!empty($_REQUEST['campaign_name'])) {
	$xtpl->assign("CAMPAIGN_NAME", $_REQUEST['campaign_name']);
} else  {
	$xtpl->assign("CAMPAIGN_NAME", $focus->campaign_name);
}

if (!empty($_REQUEST['campaign_id'])) {
	$xtpl->assign("CAMPAIGN_ID", $_REQUEST['campaign_id']);
} else {
	$xtpl->assign("CAMPAIGN_ID", $focus->campaign_id);
}
$xtpl->assign("TRACKER_NAME", $focus->tracker_name);
$xtpl->assign("TRACKER_URL", $focus->tracker_url);
$xtpl->assign("MESSAGE_URL", $focus->message_url);
$xtpl->assign("TRACKER_KEY", $focus->tracker_key);

if (!empty($focus->is_optout) && $focus->is_optout == 1) {
	$xtpl->assign("IS_OPTOUT_CHECKED","checked");
}


//$xtpl->assign("CREATED_BY", $focus->created_by_name);
//$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
//$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
//$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->parse("main");
$xtpl->out("main");
?>