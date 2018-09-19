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
if(ACLController::checkAccess('Bugs', 'edit', true))
$module_menu [] =	Array("index.php?module=Bugs&action=EditView&return_module=Bugs&return_action=DetailView", $mod_strings['LNK_NEW_BUG'],"CreateBugs", 'Bugs');
if(ACLController::checkAccess('Bugs', 'list', true))
$module_menu [] =		Array("index.php?module=Bugs&action=index&return_module=Bugs&return_action=DetailView", $mod_strings['LNK_BUG_LIST'],"Bugs", 'Bugs');
if(ACLController::checkAccess('Bugs', 'list', true))$module_menu[] =Array("index.php?module=Reports&action=index&view=bugs", $mod_strings['LNK_BUG_REPORTS'],"BugReports", 'Bugs');
if(ACLController::checkAccess('Bugs', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Bugs&return_module=Bugs&return_action=index", $mod_strings['LNK_IMPORT_BUGS'],"Import", 'Bugs');

?>