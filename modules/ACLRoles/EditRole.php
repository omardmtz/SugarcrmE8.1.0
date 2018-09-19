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

global $app_list_strings, $modInvisList;

$sugar_smarty = new Sugar_Smarty();

$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
//mass localization
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$role = BeanFactory::newBean('ACLRoles');
$role_name = '';
$return= array('module'=>'ACLRoles', 'action'=>'index', 'record'=>'');

$request = InputValidation::getService();
$record = $request->getValidInputRequest('record', 'Assert\Guid');
$isDuplicate = $request->getValidInputRequest('isDuplicate');

if (!empty($record)) {
	$role->retrieve($record);
	$categories = ACLRole::getRoleActions($record);
	
	$role_name =  $role->name;
	if (!empty($isDuplicate)) {
		//role id is stripped here in duplicate so anything using role id after this will not have it
		$role->id = '';
	} else {
		$return['record']= $role->id;
		$return['action']='DetailView';
	}
	
} else {
	$categories = ACLRole::getRoleActions('');
}

// Skipping modules that have 'hidden_to_role_assignment' property
foreach ($categories as $name => $category) {
	if (isset($dictionary[$name]) &&
		isset($dictionary[$name]['hidden_to_role_assignment']) &&
		$dictionary[$name]['hidden_to_role_assignment']
	) {
		unset($categories[$name]);
	}
}

if (in_array('Project', $modInvisList)) {
    unset($categories['Project']);
    unset($categories['ProjectTask']);
}
$sugar_smarty->assign('ROLE', $role->toArray());
$tdwidth = 10;

$returnModule = $request->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName');
$returnAction = $request->getValidInputRequest('return_action');
$returnRecord = $request->getValidInputRequest('return_record', 'Assert\Guid');
if ($returnModule !== null) {
	$return['module'] = $returnModule;
	if($returnAction !== null) {
		$return['action'] = $returnAction;
	}
	if($returnRecord !== null) {
		$return['record'] = $returnRecord;
	}
}
$categoryName = $request->getValidInputRequest('category_name');

$sugar_smarty->assign('RETURN', $return);
$names = ACLAction::setupCategoriesMatrix($categories);
if(!empty($names))$tdwidth = 100 / sizeof($names);
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('CATEGORY_NAME', $categoryName);
$sugar_smarty->assign('TDWIDTH', $tdwidth);
$sugar_smarty->assign('ACTION_NAMES', $names);

$actions = null;
if (isset($categories[$_REQUEST['category_name']]['module'])) {
    $actions = $categories[$categoryName]['module'];
}

$sugar_smarty->assign('ACTIONS', $actions);
ob_clean();

if($categoryName == 'All'){
	echo $sugar_smarty->fetch('modules/ACLRoles/EditAllBody.tpl');	
}else{
//WDong Bug 23195: Strings not localized in Role Management.
echo getClassicModuleTitle(
    $categoryName,
    array($app_list_strings['moduleList'][$categoryName]),
    false
);
echo $sugar_smarty->fetch('modules/ACLRoles/EditRole.tpl');
if (!isset($dictionary[$categoryName]['hide_fields_to_edit_role']) ||
    $dictionary[$categoryName]['hide_fields_to_edit_role'] === false
) {
    echo ACLFieldsEditView::getView($categoryName, $role->id);
}
echo '</form>';
}
sugar_cleanup(true);
