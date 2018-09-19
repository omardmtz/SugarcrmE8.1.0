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



/** @var ACLRole $role */
$role = BeanFactory::newBean('ACLRoles');
$tbaConfigurator = new TeamBasedACLConfigurator();

if(isset($_REQUEST['record']))$role->id = $_POST['record'];
if(!empty($_REQUEST['name'])){
	$role->name = $_POST['name'];
	$role->description = $_POST['description'];
	$role->save();
	//if duplicate
	if(isset($_REQUEST['isduplicate']) && !empty($_REQUEST['isduplicate'])){
	    //duplicate actions
	    $role_actions=$role->getRoleActions($_REQUEST['isduplicate']);
	    foreach($role_actions as $module){
	        foreach($module as $type){
	            foreach($type as $act){
	                $role->setAction($role->id, $act['id'], $act['aclaccess']);
	            }
	        }
	    }
	    // duplicate field ACL
	    $fields = ACLField::getACLFieldsByRole($_REQUEST['isduplicate']);
	    foreach($fields as $field){
            ACLField::setAccessControl($field['category'], $role->id, $field['name'], $field['aclaccess']);
	    }
	}
}else{
    ob_clean();	
    $flc_module = 'All';
    foreach($_POST as $name=>$value){
    	if(substr_count($name, 'act_guid') > 0){
            $name = str_replace('act_guid', '', $name);
            $aclAction = BeanFactory::getBean('ACLActions', $name);
            if ($tbaConfigurator->isValidAccess($value) &&
                !$tbaConfigurator->isEnabledForModule($aclAction->category)
            ) {
                $value = $tbaConfigurator->getFallbackByAccess($value);
            }
    		$role->setAction($role->id,$name, $value);
    	}
    	if(substr_count($name, 'flc_guid') > 0){
    		$flc_module = $_REQUEST['flc_module'];
    		$name = str_replace('flc_guid', '', $name);
            if ($tbaConfigurator->isValidAccess($value) &&
                !$tbaConfigurator->isEnabledForModule($flc_module)
            ) {
                $value = $tbaConfigurator->getFallbackByAccess($value);
            }
    		ACLField::setAccessControl($flc_module, $role->id, $name, $value);
    	}
    	
    }
    //Need to nuke the ACl cache when ACL roles change.
    $role->clearCaches();
    $role->updateUsersACLInfo();

    echo json_encode(array('role_id' => $role->id, 'module' => $flc_module));
    sugar_cleanup(true);
}

header("Location: index.php?module=ACLRoles&action=DetailView&record=". $role->id);
