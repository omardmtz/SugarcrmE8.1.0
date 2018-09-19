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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

$record = InputValidation::getService()->getValidInputRequest('record', 'Assert\Guid');
$module = InputValidation::getService()->getValidInputRequest('merge_module', 'Assert\Mvc\ModuleName');

$focus = BeanFactory::newBean('MergeRecords');
$focus->load_merge_bean($module, true, $record);

foreach($focus->merge_bean->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		if(is_array($value) && !empty($focus->merge_bean->field_defs[$field]['isMultiSelect'])) {
            if(empty($value[0])) {
                unset($value[0]);
            }
            $value = encodeMultienumValue($value);
        }
        $focus->merge_bean->$field = $value;
    } elseif (isset($focus->merge_bean->field_defs[$field]['type'])
        && $focus->merge_bean->field_defs[$field]['type'] == 'bool') {
		$focus->merge_bean->$field = 0;
	}
}

foreach($focus->merge_bean->additional_column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		if(is_array($value) && !empty($focus->merge_bean->field_defs[$field]->properties['isMultiSelect'])) {
            if(empty($value[0])) {
                unset($value[0]);
            }
            $value = encodeMultienumValue($value);
        }
		$focus->merge_bean->$field = $value;
	}
}

global $check_notify;

$_REQUEST['useEmailWidget'] = true;
if (isset($_POST['date_entered'])) {
	// set this to true so we won't unset date_entered when saving
    $focus->merge_bean->update_date_entered = true;
}
$focus->merge_bean->save($check_notify);
unset($_REQUEST['useEmailWidget']);

$return_id = $focus->merge_bean->id;
$return_module = $focus->merge_module;
$return_action = 'DetailView';

//handle realated data.

$linked_fields=$focus->merge_bean->get_linked_fields();

$exclude = explode(',', $_REQUEST['merged_links']);


$merged = InputValidation::getService()->getValidInputPost(
    'merged_ids',
    array(
        'Assert\All' => array(
            'constraints' => 'Assert\Guid',
        ),
    )
);

if (is_array($merged)) {
    foreach ($merged as $id) {
        $mergesource = BeanFactory::getBean($focus->merge_module, $id);
        //kbrill Bug #13826
        foreach ($linked_fields as $name => $properties) {
        	if ($properties['name']=='modified_user_link' || in_array($properties['name'], $exclude))
        	{
        		continue;
        	}
            if (isset($properties['duplicate_merge'])) {
                if ($properties['duplicate_merge']=='disabled' or
                    $properties['duplicate_merge']=='false' or
                    $properties['name']=='assigned_user_link') {
                    continue;
                }
            }
            if ($name == 'accounts' && $focus->merge_bean->module_dir == 'Opportunities')
            	continue;

            if ($name == 'teams') {
				$teamSetField = new SugarFieldTeamset('Teamset');
			    if($teamSetField != null){
			       $teamSetField->save($focus->merge_bean, $_REQUEST, 'team_name', '');
                   $focus->merge_bean->teams->setSaved(FALSE);
			       $focus->merge_bean->teams->save();
			       $focus->merge_bean->save();
			    }
            	continue;
            }

            if ($mergesource->load_relationship($name)) {
                //check to see if loaded relationship is with email address
                $relName=$mergesource->$name->getRelatedModuleName();
                if (!empty($relName) and strtolower($relName)=='emailaddresses'){
                    //handle email address merge
                    handleEmailMerge($focus,$name,$mergesource->$name->get());
                }else{
                    $data=$mergesource->$name->get();
                    if (is_array($data)) {
                        if ($focus->merge_bean->load_relationship($name) ) {
                            foreach ($data as $related_id) {
                                //add to primary bean
                                $focus->merge_bean->$name->add($related_id);
                            }
                        }
                    }
                }
            }
        }
        //END Bug #13826
        //delete the child bean, this action will cascade into related data too.
        $mergesource->mark_deleted($mergesource->id);
    }
}
$GLOBALS['log']->debug("Merged record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");


//This function will compare the email addresses to be merged and only add the email id's
//of the email addresses that are not duplicates.
//$focus - Merge Bean
//$name - name of relationship (email_addresses)
//$data - array of email id's that will be merged into existing bean.
function handleEmailMerge($focus,$name,$data){
    $mrgArray = array();
    //get the email id's to merge
    $existingData=$data;

    //make sure id's to merge exist and are in array format

    //get the existing email id's
    $focus->merge_bean->load_relationship($name);
    $exData=$focus->merge_bean->$name->get();

    if (!is_array($existingData) || empty($existingData)) {
        return ;
    }
        //query email and retrieve existing email address
        $exEmailQuery = 'Select id, email_address from email_addresses where id in (';
            $first = true;
            foreach($exData as $id){
                if($first){
                    $exEmailQuery .= " '$id' ";
                    $first = false;
                }else{
                    $exEmailQuery .= ", '$id' ";
                    $first = false;
                }
            }
        $exEmailQuery .= ')';

        $exResult = $focus->merge_bean->db->query($exEmailQuery);
        while(($row=$focus->merge_bean->db->fetchByAssoc($exResult))!= null) {
            $existingEmails[$row['id']]=$row['email_address'];
        }


        //query email and retrieve email address to be linked.
        $newEmailQuery = 'Select id, email_address from email_addresses where id in (';
            $first = true;
            foreach($existingData as $id){
                if($first){
                    $newEmailQuery .= " '$id' ";
                    $first = false;
                }else{
                    $newEmailQuery .= ", '$id' ";
                    $first = false;
                }
            }
        $newEmailQuery .= ')';

        $newResult = $focus->merge_bean->db->query($newEmailQuery);
        while(($row=$focus->merge_bean->db->fetchByAssoc($newResult))!= null) {
            $newEmails[$row['id']]=$row['email_address'];
        }

        //compare the two arrays and remove duplicates
        foreach($newEmails as $k=>$n){
            if(!in_array($n,$existingEmails)){
                $mrgArray[$k] = $n;
            }
        }

        //add email id's.
        foreach ($mrgArray as $related_id=>$related_val) {
            //add to primary bean
            $focus->merge_bean->$name->add($related_id);
        }
}
?>
