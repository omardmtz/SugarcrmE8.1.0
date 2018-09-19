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

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

$idpConfig = new Config(\SugarConfig::getInstance());

global $mod_strings, $app_strings;
global $current_user, $sugar_config;

$module_menu=Array();
if($GLOBALS['current_user']->isAdminForModule('Users')
)
{

	$module_menu = Array(
		Array("index.php?module=Users&action=EditView&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_USER'],"CreateUsers"),
		Array("index.php?module=Users&action=EditView&usertype=group&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_GROUP_USER'],"CreateUsers")
	);


	if (isset($sugar_config['enable_web_services_user_creation']) && $sugar_config['enable_web_services_user_creation']) {
		$module_menu[] = Array("index.php?module=Users&action=EditView&usertype=portal&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_PORTAL_USER'],"CreateUsers");
	}
	$module_menu[] = Array("index.php?module=Users&action=EditView&usertype=portal&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_PORTAL_USER'],"CreateUsers");
	$module_menu[] = Array("index.php?module=Users&action=ListView&return_module=Users&return_action=DetailView", $mod_strings['LNK_USER_LIST'],"Users");

	$module_menu[] = Array("index.php?module=Users&action=reassignUserRecords", $mod_strings['LNK_REASSIGN_RECORDS'],"ReassignRecords");
    if (!$idpConfig->isIDMModeEnabled()) {
        $module_menu[] = [
            "index.php?module=Import&action=Step1&import_module=Users&return_module=Users&return_action=index",
            $mod_strings['LNK_IMPORT_USERS'],
            "Import",
            'Contacts',
        ];
    }
}
/*
	array_push($module_menu, Array("index.php?module=Users&action=EditTabs&return_module=Users&return_action=DetailView", $mod_strings['LNK_EDIT_TABS'],"Users"))
*/
?>
