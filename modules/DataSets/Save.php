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
 ********************************************************************************/

global $app_strings;
global $current_user;

if (!is_admin($current_user)) {
    sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}





//if for a list order change in report maker
if(!empty($_REQUEST['data_set_id']) && $_REQUEST['data_set_id']!=""){
	$_POST['data_set_id'] = $_REQUEST['data_set_id'];
}
//if for removing a data set in a report maker
if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	$_POST['record'] = $_REQUEST['record'];
}


//Setup variables so no errors occur
$handle_custom_layout = false;
$custom_layout = false;



$focus = BeanFactory::newBean('DataSets');
$controller = new Controller();

if(!empty($_POST['data_set_id'])){
	
	//if we are saving from the adddatasetform
	$focus->retrieve($_POST['data_set_id']);

	//is the direction set, if not, then this is a brand new addition to the report
	if(!empty($_REQUEST['direction'])){
		$magnitude = 1;
		$direction = $_REQUEST['direction'];

		$controller->init($focus, "Save");
		$controller->change_component_order($magnitude, $direction, $focus->report_id);
		
	} else {
	//brand new
		$controller->init($focus, "New");
		$controller->change_component_order("", "", $_REQUEST['report_id']);	
	}		
		
	
} else {

	$focus->retrieve($_POST['record']);
	
	
///////////////////////////Start Handle Disabling and Enabling the Custom Layout Tool
	if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	//exist
		//if we are have switched the queries we are using in this data set
		if(!empty($_REQUEST['query_id']) && $_REQUEST['query_id']!= $focus->query_id){
			$handle_custom_layout=true;
			$custom_layout=true;
			$remove_layout=true;	
		}
			
	} else {
	//new
			$handle_custom_layout=true;
			$custom_layout=true;		
	}

////////////////////////////////End Handling Disabling and Enabling Tool
	
}	

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if(empty($_POST['data_set_id'])){

	if (!isset($_POST['exportable'])) $focus->exportable = '0';
	if (!isset($_POST['header'])) $focus->header = '0';
	if (!isset($_POST['use_prev_header'])) $focus->use_prev_header = '0';
	if (!isset($_POST['prespace_y'])) $focus->prespace_y = '0';
}

//If you are removing a data set from a report
	if(!empty($_REQUEST['rem_dataset']) && $_REQUEST['rem_dataset']=="Y"){

		//First Adjust list_order information accordingly
		$controller->init($focus, "Delete");
		$controller->delete_adjust_order($focus->report_id);

		//Now remove the report_id
		$focus->report_id = "";
	
	}

	
$focus->save();
$return_id = $focus->id;

	//Only handle it if there is a change or this is new and we need to enable
	if($handle_custom_layout==true){
		if($custom_layout==true){
			if($remove_layout==true){
			$focus->disable_custom_layout();
			}
			$focus->enable_custom_layout();
		} else {
			$focus->disable_custom_layout();
		}
	//end handle custom layout	
	}
	

	
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "DataSets";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
//echo "index.php?action=$return_action&module=$return_module&record=$return_id";
//exit;
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
