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






$focus = BeanFactory::getBean('ProspectLists', $_POST['record']);

if (!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$check_notify = TRUE;
}
else {
	$check_notify = FALSE;
}

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

$focus->save($check_notify);
$return_id = $focus->id;


//Bug 33675 Duplicate target list
if( !empty($_REQUEST['duplicateId']) ){
	$copyFromProspectList = BeanFactory::getBean('ProspectLists', $_REQUEST['duplicateId']);
	$relations = $copyFromProspectList->retrieve_relationships('prospect_lists_prospects',array('prospect_list_id'=>$_REQUEST['duplicateId']),'related_id, related_type');
	if(count($relations)>0){
		foreach ($relations as $rel){
			$rel['prospect_list_id']=$return_id;
			$focus->set_relationship('prospect_lists_prospects', $rel, true);
		}
	}
	$focus->save();
}



if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "ProspectLists";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

if($return_action == "SaveCampaignProspectListRelationshipNew")
{
	$prospect_list_id = $focus->id;
    handleRedirect($return_id, $return_module, array("prospect_list_id" => $prospect_list_id));
}
else
{
	//eggsurplus Bug 23816: maintain VCR after an edit/save. If it is a duplicate then don't worry about it. The offset is now worthless.
 	$redirect_url = "Location: index.php?action=$return_action&module=$return_module&record=$return_id";
 	if(isset($_REQUEST['offset']) && empty($_REQUEST['duplicateSave'])) {
 	    $redirect_url .= "&offset=".$_REQUEST['offset'];
 	}
	$GLOBALS['log']->debug("Saved record with id of ".$return_id);
	handleRedirect($return_id, $return_module);

}
?>