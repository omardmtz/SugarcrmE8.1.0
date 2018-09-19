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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

if (!is_admin($current_user)) {
    sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}


$focus = BeanFactory::newBean('CustomQueries');

if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	$focus->retrieve($_REQUEST['record']);
	$focus->get_custom_results();
	$old_column_array = $focus->get_column_array();
	$is_edit = true;
}

	foreach($focus->column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = InputValidation::getService()->getValidInputRequest($field);
			$focus->$field = $value;
		}
	}
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = InputValidation::getService()->getValidInputRequest($field);
			$focus->$field = $value;
			
		}
	}

	
	
	

//Check if query has an error or not
	
	//run valid test	
	$query_error = $focus->get_custom_results(true, false, true);
	
	if($query_error['result']=="Error"){
		
		$record = $focus->id;

		if(!empty($focus->id) && $focus->id!=''){
			$edit='edit=true';
		}

		$GLOBALS['log']->debug("Saved record with id of ".$return_id);

		header("Location: index.php?action=RepairQuery&module=CustomQueries&record=$record&$edit&error_msg=".$query_error['msg']."");
		exit;

	}

//End check for query error


	
$focus->custom_query = $focus->statis_query;
require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if (!isset($_POST['query_locked'])) $focus->query_locked = 'off';
$focus->save();


//only run this if this is an is_edit query scenario
if(!empty($is_edit) && $is_edit==true){

	//only run this if this is a query that is part of a data set that has custom layout enabled
	//only do if column binding is affected.  If the names are the same, do not
	//do a check here the above two conditions.
	$check_bind = $focus->check_broken_bind($old_column_array);

	if($check_bind==true){
		$_REQUEST['return_action'] = "BindMapView";
		$_SESSION['old_column_array'] = $old_column_array;
	//end if we need to check binding conditions
	} else {
	//check to see if any new columns exist in the CSQL query
	
		$temp_select = $focus->repair_column_binding(true);
        $temp_unselect = array();

		foreach($old_column_array as $key => $value){
	
			//eliminate direct matches
			if(!empty($temp_select[$value])){
				unset($temp_select[$value]);
			//end eliminate direct matches
			}
            else {
                $temp_unselect[$value] = $value;
            }

		//end foreach
		}
		
		//if anything is left in the temp_select, then add this as a new column
		foreach($temp_select as $key => $value){
			$focus->add_column_to_layouts($value);

		}

        foreach($temp_unselect as $key => $value){
            $focus->remove_layout($value);
        }
		
	//end if else	
	}	
	
//checking if is edit is true
} else {
	$old_column_array = "";
}


$return_id = $focus->id;
//exit;
$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "CustomQueries";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='edit=true';
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$edit&old_column_array=$old_column_array");
?>
