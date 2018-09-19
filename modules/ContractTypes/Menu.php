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
 * $Id$
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $current_user;
$module_menu  = Array();
if(ACLController::checkAccess('ContractTypes', 'edit', true) || (is_admin_for_module($current_user,'Contracts')))$module_menu[]=    Array("index.php?module=ContractTypes&action=EditView&return_module=ContractTypes&return_action=index", $mod_strings['LNK_NEW_CONTRACTTYPE'], "Contracts");
if(ACLController::checkAccess('ContractTypes', 'list', true) || (is_admin_for_module($current_user,'Contracts')))$module_menu[]=	Array("index.php?module=ContractTypes&action=index&return_module=ContractTypes&return_action=index", $mod_strings['LNK_CONTRACTTYPE_LIST'],"Contracts");
?>