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

 * Description:  controls which link show up in the upper right hand corner of the app
 ********************************************************************************/

global $app_strings, $current_user;
global $sugar_config, $sugar_version, $sugar_flavor, $server_unique_key, $current_language, $action;

 if(!isset($global_control_links)){
 	$global_control_links = array();
	$sub_menu = array();
 }

if(SugarThemeRegistry::current()->name != 'Classic')
$global_control_links['profile'] = array(
'linkinfo' => array($app_strings['LBL_PROFILE'] => 'index.php?module=Users&action=EditView&record='.$GLOBALS['current_user']->id),
'submenu' => ''
);

$global_control_links['employees'] = array(
'linkinfo' => array($app_strings['LBL_EMPLOYEES']=> 'index.php?module=Employees&action=index&query=true'),
'submenu' => ''
);
if (
        is_admin($current_user)
		|| $current_user->isDeveloperForAnyModule()

        ) $global_control_links['admin'] = array(

'linkinfo' => array($app_strings['LBL_ADMIN'] => 'index.php?module=Administration&action=index'),
'submenu' => ''
);
/* no longer goes in the menubar - now implemented in the bottom bar.
$global_control_links['training'] = array(
'linkinfo' => array($app_strings['LBL_TRAINING'] => 'javascript:void(window.open(\'http://support.sugarcrm.com\'))'),
'submenu' => ''
 );
$global_control_links['help'] = array(
    'linkinfo' => array($app_strings['LNK_HELP'] => ' javascript:void window.open(\'index.php?module=Administration&action=SupportPortal&view=documentation&version='.$sugar_version.'&edition='.$sugar_flavor.'&lang='.$current_language.'&help_module='.$GLOBALS['module'].'&help_action='.$action.'&key='.$server_unique_key.'\')'),
    'submenu' => ''
 );
*/
$global_control_links['users'] = array(
'linkinfo' => array($app_strings['LBL_LOGOUT'] => 'index.php?module=Users&action=Logout'),
'submenu' => ''
);

$global_control_links['about'] = array('linkinfo' => array($app_strings['LNK_ABOUT'] => 'index.php?module=Home&action=About'),
'submenu' => ''
);

foreach(SugarAutoLoader::existing('custom/include/globalControlLinks.php', SugarAutoLoader::loadExtension("links")) as $file) {
    include $file;
}
