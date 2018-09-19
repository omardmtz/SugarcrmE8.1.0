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
$past_remove = false;


$focus = BeanFactory::newBean('WorkFlowActionShells');


if(!empty($_POST['record'])){
	$focus->retrieve($_POST['record']);
	$is_new = false;
} else {
	$is_new = true;
}

foreach($focus->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$focus->$field = $_POST[$field];

	}
}
if(isset($_POST['rel1_type'])){
	$focus->rel_module_type = $_POST['rel1_type'];
}

$focus->save();
$parent_id = $focus->id;




	////////////////REL1 TYPE FILTER
	$rel1_list = & $focus->get_linked_beans('rel1_action_fil','Expression');

	if(!empty($rel1_list[0])){
		$rel1_filter_id = $rel1_list[0]->id;
	} else {
		$rel1_filter_id = "";
	}
	$rel1_object = BeanFactory::newBean('Expressions');

	//Checked if there is an advanced filter
	if($focus->rel_module_type!="filter"){
		//no advanced filter
		if($rel1_filter_id!=""){
			//remove existing filter;
			$rel1_object->mark_deleted($rel1_filter_id);
		}

	//end if no adv filter
	} else {
	//Rel1 Filter exists

		$rel1_object->parent_id = $parent_id;
		$rel1_object->handleSave("rel1_", "rel1_action_fil", $rel1_filter_id);

	//end if rel1 filter exists
	}
	/////////////////END REL1 TYPE FILTER


////////////////Handle the WorkFlowAction records

	$total_field_count = $_REQUEST['total_field_count'];

for ($i = 0; $i <= $total_field_count; $i++) {
    if (!empty($_REQUEST['set_type'][$i])) {
        //this attribute is set, so lets store or update
        $action_object = BeanFactory::newBean('WorkFlowActions');
        if (!empty($_REQUEST['action_id'][$i])) {
            $action_object->retrieve($_REQUEST['action_id'][$i]);
            //end if action id is already present
		}

        foreach ($action_object->column_fields as $field) {
            $action_object->populate_from_save($field, $i);
        }

		$action_object->parent_id = $focus->id;
		$action_object->save();

    } else {
        //possibility exists that this attribute is being removed
        if (!empty($_REQUEST['action_id'][$i])) {
            //delete attribute
            BeanFactory::deleteBean('WorkFlowActions', $_REQUEST['action_id'][$i]);
            //end if to remove attribute
        }
    }
}


//Rewrite the workflow files
$workflow_object = $focus->get_workflow_object();

//  If this action_module is Meeting or Call then create a bridging object
if($is_new==true){
	$focus->check_for_invitee_bridge($workflow_object);
}

$workflow_object->write_workflow();

$workflow_id = $focus->parent_id;






$return_id = $focus->id;

if(!empty($_POST['return_module'])) $return_module = $_POST['return_module'];
else $return_module = "WorkFlowActionShells";
if(!empty($_POST['return_action'])) $return_action = $_POST['return_action'];
else $return_action = "CreateStep1";
if(!empty($_POST['return_id'])) $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
//exit;
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&workflow_id=$workflow_id&special_action=refresh");
?>
