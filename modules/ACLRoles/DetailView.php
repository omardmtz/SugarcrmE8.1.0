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

global $dictionary;

$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);

$sugar_smarty->assign('APP_LIST', $app_list_strings);
$role = BeanFactory::getBean('ACLRoles', $_REQUEST['record']);
$categories = ACLRole::getRoleActions($_REQUEST['record']);
$names = ACLAction::setupCategoriesMatrix($categories);

// Skipping modules that have 'hidden_to_role_assignment' property
$hidden_categories = array(
    "Campaigns",
    "EmailTemplates",
    "EmailMarketing",
    "Forecasts",
    "PdfManager",
    "Reports"
);
foreach ($categories as $name => $category) {
    $objName = BeanFactory::getObjectName($name) ?: $name;
    if (isset($dictionary[$objName])) {
        if (!empty($dictionary[$objName]['hidden_to_role_assignment'])) {
            unset($categories[$name]);
        }
        if (!empty($dictionary[$objName]['hide_fields_to_edit_role'])) {
            $hidden_categories[] = $name;
        }
    }
}

$categories2=$categories;
foreach($hidden_categories as $v){
	if (isset($categories2[$v])) {
	   unset($categories2[$v]);
	}
}
$sugar_smarty->assign('CATEGORIES2', $categories2);
if(!empty($names))$tdwidth = 100 / sizeof($names);
$sugar_smarty->assign('ROLE', $role->toArray());
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('TDWIDTH', $tdwidth);
$sugar_smarty->assign('ACTION_NAMES', $names);

$return= array('module'=>'ACLRoles', 'action'=>'DetailView', 'record'=>$role->id);
$sugar_smarty->assign('RETURN', $return);
$params = array();
$params[] = "<a href='index.php?module=ACLRoles&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
$params[] = $role->get_summary_text();
echo getClassicModuleTitle("ACLRoles", $params, true);
$hide_hide_supanels = true;

echo $sugar_smarty->fetch('modules/ACLRoles/DetailView.tpl');
//for subpanels the variable must be named focus;
$focus =& $role;
$_REQUEST['module'] = 'ACLRoles';

$subpanel = new SubPanelTiles($role, 'ACLRoles');

echo $subpanel->display();



?>
