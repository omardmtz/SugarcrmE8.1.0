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

global $mod_strings, $app_strings, $sugar_config;

		
if(ACLController::checkAccess('Accounts', 'edit', true))$module_menu[]=Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=index", $mod_strings['LNK_NEW_ACCOUNT'],"CreateAccounts", 'Accounts');

if(ACLController::checkAccess('Accounts', 'list', true))$module_menu[]=Array("index.php?module=Accounts&action=index&return_module=Accounts&return_action=DetailView", $mod_strings['LNK_ACCOUNT_LIST'],"Accounts", 'Accounts');

if (ACLController::checkAccess('Accounts', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=Reports&action=index&view=accounts',
        $mod_strings['LNK_ACCOUNT_REPORTS'],
        'AccountReports',
        'Accounts',
    );
}

if(ACLController::checkAccess('Accounts', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Accounts&return_module=Accounts&return_action=index", $mod_strings['LNK_IMPORT_ACCOUNTS'],"Import", 'Accounts');
