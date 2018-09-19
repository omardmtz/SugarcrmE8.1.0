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



$past_object = "";
$future_object = "";



$past_remove = false;


$focus = BeanFactory::newBean('WorkFlowTriggerShells');


if(!empty($_POST['record']) && $_POST['record']!=""){
	$focus->retrieve($_POST['record']);
}

foreach($focus->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$focus->$field = $_POST[$field];
		
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->$field = $value;
		
	}
}

if (!isset($_REQUEST['mod_past_trigger'])){

	$focus->show_past = 0;
} else {

	$focus->show_past = 1;	
}	

	$focus->save();
	$parent_id = $focus->id;



///////////save the future and if necssary past exp objects.
if(!empty($_REQUEST['type']) && $_REQUEST['type']=="compare_specific"){
	


	///////////////////////////////////HANDLE THE TRIGGER COMPONENTS (PAST) & (FUTURE)

	$past_list = $focus->get_linked_beans('past_triggers','Expression');
	if(!empty($past_list[0])){
		$past_id = $past_list[0]->id;
	} else {
		$past_id = "";
	}

	$past_object = BeanFactory::newBean('Expressions');

	//Is there past to capture
	if($focus->show_past==1){
		//(PAST)

		if(!empty($past_id) && $past_id!=""){
			$past_object->retrieve($past_id);
			$past_is_update = true;
		}

		$past_object->lhs_module = $_POST['past_trigger_lhs_module'];
		$past_object->lhs_field = $_POST['past_trigger_lhs_field'];
		$past_object->operator = $_POST['past_trigger_operator'];
		$past_object->exp_type = $_POST['past_trigger_exp_type'];
		$past_object->rhs_value = $_POST['past_trigger_rhs_value'];
		

		$past_object->parent_id = $parent_id;
		$past_object->parent_type = "past_trigger";
		$past_object->save();
	} else {
		//Show past is turned off

		//Do we need to remove the past, if we don't need it anymore
		if(!empty($past_id) && $past_id!=""){
			$past_object->mark_deleted($past_id);
			$past_object = BeanFactory::newBean('Expressions');
		}

		//end if else past is on or off
	}




	//(FUTURE)

	$future_list = $focus->get_linked_beans('future_triggers','Expression');

	if(!empty($future_list[0])){
		$future_id = $future_list[0]->id;
	} else {
		$future_id = "";
	}
	$future_object = BeanFactory::newBean('Expressions');

	if(!empty($future_id) && $future_id!=""){
		$future_object->retrieve($future_id);
		$future_is_update = true;
	}

	$future_object->lhs_module = $_POST['future_trigger_lhs_module'];
	$future_object->lhs_field = $_POST['future_trigger_lhs_field'];
	$future_object->operator = $_POST['future_trigger_operator'];
	$future_object->exp_type = $_POST['future_trigger_exp_type'];
	$future_object->rhs_value = $_POST['future_trigger_rhs_value'];

	if(!empty($_POST['future_trigger_time_int']) || $_POST['future_trigger_time_int'] == '0'){
		$future_object->ext1 = $_POST['future_trigger_time_int'];
	}

	$future_object->parent_id = $parent_id;
	$future_object->parent_type = "future_trigger";
	$future_object->save();



	//END HANDLE TRIGGER COMPONENTS (PAST) & (FUTURE)
	//////////////////////////////////////////////////////////////////////////////////

	
//end if this type is compare_specific	
}

if(!empty($_REQUEST['type']) && 
	( $_REQUEST['type']=="compare_change" || $_REQUEST['type']=="compare_any_time"
	)
){

	$past_object = "";
	$future_object = "";
	
	//rearrange this after you move the functions around for glueing
	
//end if compare_change
}

$workflow_object = $focus->glue_triggers($past_object, $future_object);
$focus->save();



$workflow_object->write_workflow();

$return_id = $focus->parent_id;

if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "WorkFlowTriggerShells";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "CreateStep1";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
//exit;
header(
    'Location: index.php?' . http_build_query(
        array(
            'action' => $return_action,
            'module' => $return_module,
            'record' => $return_id,
            'workflow_id' => $workflow_object->id,
            'parent_id' => $parent_id,
            'special_action' => 'refresh'
        )
    )
);
