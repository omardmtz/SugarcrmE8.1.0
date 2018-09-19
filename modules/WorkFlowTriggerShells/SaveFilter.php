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


	$focus->show_past = 0;
	$focus->field = $_POST['trigger_lhs_field'];

	$focus->save();
	$parent_id = $focus->id;

	
///////////////////////////////////HANDLE THE COUNT COMPONENTS (BASE) & (RELATED)	
	

	
		$base_list = & $focus->get_linked_beans('expressions','Expression');
		if(isset($base_list[0]) && $base_list[0]!='') {
			$base_id = $base_list[0]->id;
		} else {
			$base_id = "";	
		}

		$base_object = BeanFactory::newBean('Expressions');
				
		if(!empty($base_id) && $base_id!=""){
			$base_object->retrieve($base_id);
			$base_is_update = true;
		}	

		$base_object->operator = $_POST['trigger_operator'];
		$base_object->rhs_value = $_POST['trigger_rhs_value'];
		$base_object->exp_type = $_POST['trigger_exp_type'];
		$base_object->lhs_field = $_POST['trigger_lhs_field'];
		$base_object->lhs_module = $_POST['trigger_lhs_module'];
		
		if(!empty($_POST['type']) && $_POST['type']=="filter_rel_field"){
			$base_object->lhs_type = "rel_module";
		} else {
			$base_object->lhs_type = "base_module";			
		}		
		
		$base_object->parent_id = $parent_id;
		$base_object->parent_type = "expression";
		$base_object->save();

		
/////////////////////////////////////////////////////////////////////		
		
$workflow_object = $focus->glue_trigger_filters($base_object);
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
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&workflow_id=$parent_id&parent_id=$parent_id&special_action=refresh");
?>
