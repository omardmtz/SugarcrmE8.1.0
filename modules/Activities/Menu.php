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


global $mod_strings, $app_strings;
global $mod_strings;
if(ACLController::checkAccess('Calls', 'edit', true))$module_menu[]=Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"CreateCalls");
if(ACLController::checkAccess('Meetings', 'edit', true))$module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"CreateMeetings");
if(ACLController::checkAccess('Tasks', 'edit', true))$module_menu[]=Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"CreateTasks");
if(ACLController::checkAccess('Notes', 'edit', true))$module_menu[]=Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NEW_NOTE'],"CreateNotes");
if(ACLController::checkAccess('Calls', 'list', true))$module_menu[]=Array("index.php?module=Calls&action=index&return_module=Calls&return_action=DetailView", $mod_strings['LNK_CALL_LIST'],"Calls");
if(ACLController::checkAccess('Meetings', 'list', true))$module_menu[]=Array("index.php?module=Meetings&action=index&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_MEETING_LIST'],"Meetings");
if(ACLController::checkAccess('Tasks', 'list', true))$module_menu[]=Array("index.php?module=Tasks&action=index&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_TASK_LIST'],"Tasks");
if(ACLController::checkAccess('Notes', 'list', true))$module_menu[]=Array("index.php?module=Notes&action=index&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NOTE_LIST'],"Notes");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=day", $mod_strings['LNK_VIEW_CALENDAR'],"Calendar");
if(ACLController::checkAccess('Calls', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Calls&return_module=Calls&return_action=index", $mod_strings['LNK_IMPORT_CALLS'],"Import", 'Calls');
if(ACLController::checkAccess('Meetings', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Meetings&return_module=Meetings&return_action=index", $mod_strings['LNK_IMPORT_MEETINGS'],"Import", 'Meetings');
if(ACLController::checkAccess('Tasks', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Tasks&return_module=Tasks&return_action=index", $mod_strings['LNK_IMPORT_TASKS'],"Import", 'Tasks');
if(ACLController::checkAccess('Notes', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Notes&return_module=Notes&return_action=index", $mod_strings['LNK_IMPORT_NOTES'],"Import", 'Notes');
?>
