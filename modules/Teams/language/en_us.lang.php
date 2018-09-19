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

$mod_strings = array(
    'ERR_ADD_RECORD' => 'You must specify a record number to add a user to this team.',
    'ERR_DUP_NAME' => 'Team Name already existed, please choose another one.',
    'ERR_DELETE_RECORD' => 'You must specify a record number to delete this team.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Error.  The selected team <b>({0})</b> is a team you have chosen to delete.  Please select another team.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Error.  You may not delete a user whose private team has not been deleted.',
    'LBL_DESCRIPTION' => 'Description:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globally Visible',
    'LBL_INVITEE' => 'Team Members',
    'LBL_LIST_DEPARTMENT' => 'Department',
    'LBL_LIST_DESCRIPTION' => 'Description',
    'LBL_LIST_FORM_TITLE' => 'Team List',
    'LBL_LIST_NAME' => 'Name',
    'LBL_FIRST_NAME' => 'First Name:',
    'LBL_LAST_NAME' => 'Last Name:',
    'LBL_LIST_REPORTS_TO' => 'Reports To',
    'LBL_LIST_TITLE' => 'Title',
    'LBL_MODULE_NAME' => 'Teams',
    'LBL_MODULE_NAME_SINGULAR' => 'Team',
    'LBL_MODULE_TITLE' => 'Teams: Home',
    'LBL_NAME' => 'Team Name:',
    'LBL_NAME_2' => 'Team Name(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primary Team Name',
    'LBL_NEW_FORM_TITLE' => 'New Team',
    'LBL_PRIVATE' => 'Private',
    'LBL_PRIVATE_TEAM_FOR' => 'Private team for: ',
    'LBL_SEARCH_FORM_TITLE' => 'Team Search',
    'LBL_TEAM_MEMBERS' => 'Team Members',
    'LBL_TEAM' => 'Teams:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Users',
    'LBL_USERS' => 'Users',
    'LBL_REASSIGN_TEAM_TITLE' => 'There are records assigned to the following team(s): <b>{0}</b><br>Before deleting the team(s), you must first reassign these records to a new team.  Select a team to be used as the replacement.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Reassign',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Reassign',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Proceed to update the affected records to use the new team?',
    'LBL_REASSIGN_TABLE_INFO' => 'Updating table {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operation has completed successfully.',
    'LNK_LIST_TEAM' => 'Teams',
    'LNK_LIST_TEAMNOTICE' => 'Team Notices',
    'LNK_NEW_TEAM' => 'Create Team',
    'LNK_NEW_TEAM_NOTICE' => 'Create Team Notice',
    'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Are you sure you want to remove this user\\\'s membership?',
    'LBL_EDITLAYOUT' => 'Edit Layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Team-based Permissions',
    'LBL_TBA_CONFIGURATION_DESC' => 'Enable team access, and manage access by module.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Enable team-based permissions',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Select modules to enable',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Enabling team-based permissions will allow you to assign specific access rights to teams and users for individual modules, through Role Management.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Disabling team-based permissions for a module will revert any data associated with team-based permissions for that
 module, including any Process Definitions or Processes using the feature. This includes any Roles using the
 "Owner & Selected team" option for that module, and any team-based permissions data for records in that module.
 We also recommend that you use Quick Repair and Rebuild tool to clear your system cache after disabling team-based
 permissions for any module.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Warning:</strong> Disabling team-based permissions for a module will revert any data associated with
 team-based permissions for that module, including any Process Definitions or Processes using the feature. This
 includes any Roles using the "Owner & Selected team" option for that module, and any team-based permissions data
 for records in that module. We also recommend that you use Quick Repair and Rebuild tool to clear your system cache
 after disabling team-based permissions for any module.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Disabling team-based permissions for a module will revert any data associated with team-based permissions for that
 module, including any Process Definitions or Processes using the feature. This includes any Roles using the
 "Owner & Selected team" option for that module, and any team-based permissions data for records in that module.
 We also recommend that you use Quick Repair and Rebuild tool to clear your system cache after disabling team-based
 permissions for any module. If you do not have access to use Quick Repair and Rebuild, contact an administrator with
 access to the Repair menu.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Warning:</strong> Disabling team-based permissions for a module will revert any data associated with
 team-based permissions for that module, including any Process Definitions or Processes using the feature. This
 includes any Roles using the "Owner & Selected team" option for that module, and any team-based permissions data for
 records in that module. We also recommend that you use Quick Repair and Rebuild tool to clear your system cache after
 disabling team-based permissions for any module. If you do not have access to use Quick Repair and Rebuild, contact
 an administrator with access to the Repair menu.
STR
,
);
