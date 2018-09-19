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
	if(ACLController::checkAccess('Contacts', 'edit', true))$module_menu[] = Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=index", $mod_strings['LNK_NEW_CONTACT'],"CreateContacts", 'Contacts');

	if(ACLController::checkAccess('Contacts', 'import', true))$module_menu[] =Array("index.php?module=Contacts&action=ImportVCard", $mod_strings['LNK_IMPORT_VCARD'],"CreateContacts", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'list', true))$module_menu[] =Array("index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_CONTACT_LIST'],"Contacts", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'list', true))$module_menu[] =Array("index.php?module=Reports&action=index&view=contacts", $mod_strings['LNK_CONTACT_REPORTS'],"ContactReports", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Contacts&return_module=Contacts&return_action=index", $mod_strings['LNK_IMPORT_CONTACTS'],"Import", 'Contacts');
