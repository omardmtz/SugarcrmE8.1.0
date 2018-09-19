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



global $app_list_strings, $app_strings, $current_user;

$mod_strings = return_module_language($GLOBALS['current_language'], 'Users');

$focus = BeanFactory::getBean('Users', $_REQUEST['record']);
if ( !is_admin($focus) ) {
    $sugar_smarty = new Sugar_Smarty();
    $sugar_smarty->assign('MOD', $mod_strings);
    $sugar_smarty->assign('APP', $app_strings);
    $sugar_smarty->assign('APP_LIST', $app_list_strings);
    
    $categories = ACLAction::getUserActions($_REQUEST['record'],true);
    
    //clear out any removed tabs from user display
    if(!$GLOBALS['current_user']->isAdminForModule('Users')){
        $tabs = $focus->getPreference('display_tabs');
        global $modInvisList;
        if(!empty($tabs)){
            foreach($categories as $key=>$value){
                if(!in_array($key, $tabs) &&  !in_array($key, $modInvisList) ){
                    unset($categories[$key]);
                    
                }
            }
            
        }
    }
    
    $names = array();
    $names = ACLAction::setupCategoriesMatrix($categories);
    if(!empty($names))$tdwidth = 100 / sizeof($names);
    $sugar_smarty->assign('APP', $app_list_strings);
    $sugar_smarty->assign('CATEGORIES', $categories);
    $sugar_smarty->assign('TDWIDTH', $tdwidth);
    $sugar_smarty->assign('ACTION_NAMES', $names);
    
    $title = getClassicModuleTitle( '',array($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_ROLES_SUBPANEL_TITLE']), '');
    
    $sugar_smarty->assign('TITLE', $title);
    $sugar_smarty->assign('USER_ID', $focus->id);
    $sugar_smarty->assign('LAYOUT_DEF_KEY', 'UserRoles');
    echo $sugar_smarty->fetch('modules/ACLRoles/DetailViewUser.tpl');
    
    
    //this gets its layout_defs.php file from the user not from ACLRoles so look in modules/Users for the layout defs
    $modules_exempt_from_availability_check=array('Users'=>'Users','ACLRoles'=>'ACLRoles',);
    $subpanel = new SubPanelTiles($focus, 'UserRoles');
    
    echo $subpanel->display(true,true);
}
if ( empty($hideTeams) ) {
    $focus_list =$focus->get_my_teams(TRUE);
    
    // My Teams subpanel should not be displayed for group and portal users
    if(!($focus->is_group=='1' || $focus->portal_only=='1')){
        include('modules/Teams/SubPanelViewUsers.php');
        $SubPanel = new SubPanelViewUsers();
        $SubPanel->setFocus($focus);
        $SubPanel->setTeamsList($focus_list);
        $SubPanel->ProcessSubPanelListView("modules/Teams/SubPanelViewUsers.html", $mod_strings, 'DetailView');
    }
}