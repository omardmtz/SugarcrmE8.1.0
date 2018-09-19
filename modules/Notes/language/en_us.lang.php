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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Notes List Dashboard',

    'ERR_DELETE_RECORD' => 'You must specify a record number to delete the Account.',
    'LBL_ACCOUNT_ID' => 'Account ID:',
    'LBL_CASE_ID' => 'Case ID:',
    'LBL_CLOSE' => 'Close:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Contact ID:',
    'LBL_CONTACT_NAME' => 'Contact:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notes',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_EMAIL_ADDRESS' => 'Email Address:',
    'LBL_EMAIL_ATTACHMENT' => 'Email Attachment',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Email Attachment For',
    'LBL_FILE_MIME_TYPE' => 'Mime Type',
    'LBL_FILE_EXTENSION' => 'File Extension',
    'LBL_FILE_SOURCE' => 'File Source',
    'LBL_FILE_SIZE' => 'File Size',
    'LBL_FILE_URL' => 'File URL',
    'LBL_FILENAME' => 'Attachment:',
    'LBL_LEAD_ID' => 'Lead ID:',
    'LBL_LIST_CONTACT_NAME' => 'Contact',
    'LBL_LIST_DATE_MODIFIED' => 'Last Modified',
    'LBL_LIST_FILENAME' => 'Attachment',
    'LBL_LIST_FORM_TITLE' => 'Note List',
    'LBL_LIST_RELATED_TO' => 'Related To',
    'LBL_LIST_SUBJECT' => 'Subject',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Contact',
    'LBL_MODULE_NAME' => 'Notes',
    'LBL_MODULE_NAME_SINGULAR' => 'Note',
    'LBL_MODULE_TITLE' => 'Notes: Home',
    'LBL_NEW_FORM_TITLE' => 'Create Note or Add Attachment',
    'LBL_NEW_FORM_BTN' => 'Add a Note',
    'LBL_NOTE_STATUS' => 'Note',
    'LBL_NOTE_SUBJECT' => 'Subject:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes & Attachments',
    'LBL_NOTE' => 'Note:',
    'LBL_OPPORTUNITY_ID' => 'Opportunity ID:',
    'LBL_PARENT_ID' => 'Parent ID:',
    'LBL_PARENT_TYPE' => 'Parent Type',
    'LBL_EMAIL_TYPE' => 'Email Type',
    'LBL_EMAIL_ID' => 'Email ID',
    'LBL_PHONE' => 'Phone:',
    'LBL_PORTAL_FLAG' => 'Display in Portal?',
    'LBL_EMBED_FLAG' => 'Embed in email?',
    'LBL_PRODUCT_ID' => 'Quoted Line Item ID:',
    'LBL_QUOTE_ID' => 'Quote ID:',
    'LBL_RELATED_TO' => 'Related To:',
    'LBL_SEARCH_FORM_TITLE' => 'Note Search',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Subject:',
    'LNK_IMPORT_NOTES' => 'Import Notes',
    'LNK_NEW_NOTE' => 'Create Note or Attachment',
    'LNK_NOTE_LIST' => 'View Notes',
    'LBL_MEMBER_OF' => 'Member of:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned User',
    'LBL_OC_FILE_NOTICE' => 'Please login to server to view file',
    'LBL_REMOVING_ATTACHMENT' => 'Removing attachment...',
    'ERR_REMOVING_ATTACHMENT' => 'Failed to remove attachment...',
    'LBL_CREATED_BY' => 'Created By',
    'LBL_MODIFIED_BY' => 'Modified By',
    'LBL_SEND_ANYWAYS' => 'Are you sure you want to send/save the email without subject?',
    'LBL_LIST_EDIT_BUTTON' => 'Edit',
    'LBL_ACTIVITIES_REPORTS' => 'Activities Report',
    'LBL_PANEL_DETAILS' => 'Details',
    'LBL_NOTE_INFORMATION' => 'Overview',
    'LBL_MY_NOTES_DASHLETNAME' => 'My Notes',
    'LBL_EDITLAYOUT' => 'Edit Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'First Name',
    'LBL_LAST_NAME' => 'Last Name',
    'LBL_EXPORT_PARENT_TYPE' => 'Related To Module',
    'LBL_EXPORT_PARENT_ID' => 'Related To ID',
    'LBL_DATE_ENTERED' => 'Date Created',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_DELETED' => 'Deleted',
    'LBL_REVENUELINEITEMS' => 'Revenue Line Items',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module consists of individual {{plural_module_name}} that contain text or an attachment pertinent to the related record. {{module_name}} records can be related to one record in most modules via the flex relate field and can also be related to a single {{contacts_singular_module}}. {{plural_module_name}} can hold generic text about a record or even an attachment related to the record. There are various ways you can create {{plural_module_name}} in Sugar such as via the {{plural_module_name}} module, importing {{plural_module_name}}, via History subpanels, etc. Once the {{module_name}} record is created, you can view and edit information pertaining to the {{module_name}} via the {{plural_module_name}} record view. Each {{module_name}} record may then relate to other Sugar records such as {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, and many others.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of individual {{plural_module_name}} that contain text or an attachment pertinent to the related record.

- Edit this record\'s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.',
);
