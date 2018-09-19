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

global $mod_strings,$app_strings;
if(ACLController::checkAccess('Cases', 'edit', true))
$module_menu [] = Array("index.php?module=Cases&action=EditView&return_module=Cases&return_action=DetailView", $mod_strings['LNK_NEW_CASE'],"CreateCases");
if(ACLController::checkAccess('Cases', 'list', true))
$module_menu [] = Array("index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView", $mod_strings['LNK_CASE_LIST'],"Cases");
if(ACLController::checkAccess('Cases', 'list', true))$module_menu[] =Array("index.php?module=Reports&action=index&view=cases", $mod_strings['LNK_CASE_REPORTS'],"CaseReports", 'Cases');
if(ACLController::checkAccess('Cases', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Cases&return_module=Cases&return_action=index", $mod_strings['LNK_IMPORT_CASES'],"Import", 'Cases');


?>