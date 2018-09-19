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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => 'Tasks List Dashboard',

  'LBL_MODULE_NAME' => 'Tasks',
  'LBL_MODULE_NAME_SINGULAR' => 'Task',
  'LBL_TASK' => 'Tasks:',
  'LBL_MODULE_TITLE' => 'Tasks: Home',
  'LBL_SEARCH_FORM_TITLE' => 'Task Search',
  'LBL_LIST_FORM_TITLE' => 'Task List',
  'LBL_NEW_FORM_TITLE' => 'Create Task',
  'LBL_NEW_FORM_SUBJECT' => 'Subject:',
  'LBL_NEW_FORM_DUE_DATE' => 'Due Date:',
  'LBL_NEW_FORM_DUE_TIME' => 'Due Time:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Close',
  'LBL_LIST_SUBJECT' => 'Subject',
  'LBL_LIST_CONTACT' => 'Contact',
  'LBL_LIST_PRIORITY' => 'Priority',
  'LBL_LIST_RELATED_TO' => 'Related to',
  'LBL_LIST_DUE_DATE' => 'Due Date',
  'LBL_LIST_DUE_TIME' => 'Due Time',
  'LBL_SUBJECT' => 'Subject:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Due Date:',
  'LBL_DUE_TIME' => 'Due Time:',
  'LBL_PRIORITY' => 'Priority:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Due Date & Time:',
  'LBL_START_DATE_AND_TIME' => 'Start Date & Time:',
  'LBL_START_DATE' => 'Start Date:',
  'LBL_LIST_START_DATE' => 'Start Date',
  'LBL_START_TIME' => 'Start Time:',
  'LBL_LIST_START_TIME' => 'Start Time',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => 'None',
  'LBL_CONTACT' => 'Contact:',
  'LBL_EMAIL_ADDRESS' => 'Email Address:',
  'LBL_PHONE' => 'Phone:',
  'LBL_EMAIL' => 'Email Address:',
  'LBL_DESCRIPTION_INFORMATION' => 'Description Information',
  'LBL_DESCRIPTION' => 'Description:',
  'LBL_NAME' => 'Name:',
  'LBL_CONTACT_NAME' => 'Contact Name',
  'LBL_LIST_COMPLETE' => 'Complete:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'No Due Date',
  'LBL_DATE_START_FLAG' => 'No Start Date',
  'ERR_DELETE_RECORD' => 'You must specify a record number to delete the contact.',
  'ERR_INVALID_HOUR' => 'Please enter an hour between 0 and 24',
  'LBL_DEFAULT_PRIORITY' => 'Medium',
  'LBL_LIST_MY_TASKS' => 'My Open Tasks',
  'LNK_NEW_TASK' => 'Create Task',
  'LNK_TASK_LIST' => 'View Tasks',
  'LNK_IMPORT_TASKS' => 'Import Tasks',
  'LBL_CONTACT_FIRST_NAME'=>'Contact First Name',
  'LBL_CONTACT_LAST_NAME'=>'Contact Last Name',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned User',
  'LBL_ASSIGNED_TO_NAME'=>'Assigned to:',
  'LBL_LIST_DATE_MODIFIED' => 'Date Modified',
  'LBL_CONTACT_ID' => 'Contact ID:',
  'LBL_PARENT_ID' => 'Parent ID:',
  'LBL_CONTACT_PHONE' => 'Contact Phone:',
  'LBL_PARENT_NAME' => 'Parent Type:',
  'LBL_ACTIVITIES_REPORTS' => 'Activities Report',
  'LBL_EDITLAYOUT' => 'Edit Layout' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Overview',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notes',
  'LBL_REVENUELINEITEMS' => 'Revenue Line Items',
  //For export labels
  'LBL_DATE_DUE' => 'Date Due',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigned User Name',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigned User ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modified By ID',
  'LBL_EXPORT_CREATED_BY' => 'Created By ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Related To Module',
  'LBL_EXPORT_PARENT_ID' => 'Related To ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Task closed successfully.',
  'LBL_ASSIGNED_USER' => 'Assigned to',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion. {{module_name}} records can be related to one record in most modules via the flex relate field and can also be related to a single {{contacts_singular_module}}. There are various ways you can create {{plural_module_name}} in Sugar such as via the {{plural_module_name}} module, duplication, importing {{plural_module_name}}, etc. Once the {{module_name}} record is created, you can view and edit information pertaining to the {{module_name}} via the {{plural_module_name}} record view. Depending on the details on the {{module_name}}, you may also be able to view and edit the {{module_name}} information via the Calendar module. Each {{module_name}} record may then relate to other Sugar records such as {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, and many others.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favourite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalise the new record and return to the previous page.',

);
