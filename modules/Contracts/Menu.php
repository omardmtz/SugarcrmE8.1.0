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

// $Id: Menu.php 45763 2009-04-01 19:16:18Z majed $

global $mod_strings;
global $current_user;

if (ACLController :: checkAccess('Contracts', 'edit', true))
{
    $module_menu[] = array ('index.php?module=Contracts&action=EditView&return_module=Contracts&return_action=DetailView', $mod_strings['LNK_NEW_CONTRACT'], 'CreateContracts');
}

if (ACLController :: checkAccess('Contracts', 'list', true))
{
    $module_menu[] = array ('index.php?module=Contracts&action=index', $mod_strings['LNK_CONTRACT_LIST'], 'Contracts');
}

if (ACLController :: checkAccess('Contracts', 'detail', true)) {
	
	$admin = Administration::getSettings();
}

if (ACLController::checkAccess('Contracts', 'import', true))
{
    $module_menu[] = array(
    	"index.php?module=Import&action=Step1&import_module=Contracts&return_module=Contracts&return_action=index",
        $mod_strings['LNK_IMPORT_CONTRACTS'],
    	"Import",
    	'Contracts'
	);
}

?>
