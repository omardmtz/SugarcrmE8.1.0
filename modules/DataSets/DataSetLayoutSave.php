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




$is_update = false;
$head_is_update = false;
$body_is_update = false;
$to_pdf = true;

$focus = new DataSet_Layout();

	if(!empty($_REQUEST['layout_id']) && $_REQUEST['layout_id']!=""){
		$focus->retrieve($_REQUEST['layout_id']);
		$is_update = true;
	}


//Update Data Set Layout Controller////////////////////////////////////////////
	if($is_update==true){
		if(!empty($_REQUEST['direction'])){
			$to_pdf = false;
			$controller = new Controller();
			$magnitude = 1;
			$direction = $_REQUEST['direction'];
			$controller->init($focus, "Save");
			$controller->change_component_order($magnitude, $direction, $focus->parent_id);
		}
	//End Update Data Set Layout Contoller
	}	

//New or Update DataSet Layout

foreach($focus->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$focus->$field = $_POST[$field];
		
	}
}

if (!isset($_POST['hide_column'])) $focus->hide_column = '0';

$focus->save();
$layout_id = $focus->id;
$return_id = $focus->id;
$parent_id = $focus->id;

//New or Update DataSet Attribute Head////////////////////////////////////////////
if(!empty($_REQUEST['direction'])){
	//only update controller information, ignore attribute stuff
} else {
	$rem_head_att = false;	
	$head_object = new DataSet_Attribute();
	if(!empty($_REQUEST['head_att_id']) && $_REQUEST['head_att_id']!=""){
		$head_object->retrieve($_REQUEST['head_att_id']);
		$head_is_update = true;
		//check to see if we are removing this attribute (Unchecking);
		if (empty($_POST['modify_head'])) {
			//set remove attribute flag
			$rem_head_att = true;
		}
	
	//end if update
	} else {
	//Still no head attribute, keep it this way	
		if (empty($_POST['modify_head'])) {
			//set remove attribute flag
			$rem_head_att = true;
		}	
			
	}	
	
	foreach($head_object->column_fields as $field) {
		$actual_field = "head__".$field;
		//echo $actual_field."<BR>";
		if(isset($_POST[$actual_field])){
			$head_object->$field = $_POST[$actual_field];
		}
	//end foreach
	}

	if (!isset($_POST['head__wrap'])) $head_object->wrap = '0';
	$head_object->parent_id = $parent_id;
	$head_object->attribute_type = "Head";
	
if($rem_head_att==false){	
	$head_object->save();
} else {
	$head_object->mark_deleted($head_object->id);
}	

//end if controller information only
}

//New or Update DataSet Attribute Body////////////////////////////////////////////
if(!empty($_REQUEST['direction'])){
	//only update controller information, ignore attribute stuff
} else {
	$rem_body_att = false;	
	$body_object = new DataSet_Attribute();
	if(!empty($_REQUEST['body_att_id']) && $_REQUEST['body_att_id']!=""){
		$body_object->retrieve($_REQUEST['body_att_id']);
		$body_is_update = true;
		//check to see if we are removing this attribute (Unchecking);
		if (empty($_POST['modify_body'])) {
			//set remove attribute flag
			$rem_body_att = true;
		}
	
	//end if update nested
	}  else {
	//Still no body attribute, keep it this way	
		if (empty($_POST['modify_body'])) {
			//set remove attribute flag
			$rem_body_att = true;
		}	
	}	
	
	foreach($body_object->column_fields as $field) {
		$actual_field = "body__".$field;
		//echo $actual_field."<BR>";
		if(isset($_POST[$actual_field])){
			$body_object->$field = $_POST[$actual_field];
		}
	//end foreach
	}

	if (!isset($_POST['body__wrap'])) $body_object->wrap = '0';
	$body_object->parent_id = $parent_id;
	$body_object->attribute_type = "Body";

	if($rem_body_att==false){	
		$body_object->save();
	} else {
		$body_object->mark_deleted($body_object->id);
	}	

//end if controller information only
}


	
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "DataSets";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "LayoutPopup";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&to_pdf=$to_pdf&layout_record=$layout_id");
?>
