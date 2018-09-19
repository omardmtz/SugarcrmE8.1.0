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
  'LBL_TARGETS_LIST_DASHBOARD' => 'Targets List Dashboard',
  'LBL_TARGETS_RECORD_DASHBOARD' => 'Targets Record Dashboard',

  'LBL_MODULE_NAME' => 'Targets',
  'LBL_MODULE_NAME_SINGULAR' => 'Target',
  'LBL_MODULE_ID'   => 'Targets',
  'LBL_INVITEE' => 'Direct Reports',
  'LBL_MODULE_TITLE' => 'Targets: Home',
  'LBL_SEARCH_FORM_TITLE' => 'Target Search',
  'LBL_LIST_FORM_TITLE' => 'Target List',
  'LBL_NEW_FORM_TITLE' => 'New Target',
  'LBL_PROSPECT' => 'Target:',
  'LBL_BUSINESSCARD' => 'Business Card',
  'LBL_LIST_NAME' => 'Name',
  'LBL_LIST_LAST_NAME' => 'Last Name',
  'LBL_LIST_PROSPECT_NAME' => 'Target Name',
  'LBL_LIST_TITLE' => 'Title',
  'LBL_LIST_EMAIL_ADDRESS' => 'Email',
  'LBL_LIST_OTHER_EMAIL_ADDRESS' => 'Other Email',
  'LBL_LIST_PHONE' => 'Phone',
  'LBL_LIST_PROSPECT_ROLE' => 'Role',
  'LBL_LIST_FIRST_NAME' => 'First Name',
  'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
  'LBL_ASSIGNED_TO_ID'=>'Assigned To:',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_last_name' => 'LBL_LIST_LAST_NAME',
  'db_first_name' => 'LBL_LIST_FIRST_NAME',
  'db_title' => 'LBL_LIST_TITLE',
  'db_email1' => 'LBL_LIST_EMAIL_ADDRESS',
  'db_email2' => 'LBL_LIST_OTHER_EMAIL_ADDRESS',
//END DON'T CONVERT
  'LBL_EXISTING_PROSPECT' => 'Used an existing contact',
  'LBL_CREATED_PROSPECT' => 'Created a new contact',
  'LBL_EXISTING_ACCOUNT' => 'Used an existing account',
  'LBL_CREATED_ACCOUNT' => 'Created a new account',
  'LBL_CREATED_CALL' => 'Created a new call',
  'LBL_CREATED_MEETING' => 'Created a new meeting',
  'LBL_ADDMORE_BUSINESSCARD' => 'Add another business card',
  'LBL_ADD_BUSINESSCARD' => 'Enter Business Card',
  'LBL_NAME' => 'Name:',
  'LBL_FULL_NAME' => 'Name',
  'LBL_PROSPECT_NAME' => 'Target Name:',
  'LBL_PROSPECT_INFORMATION' => 'Overview',
  'LBL_MORE_INFORMATION' => 'More Information',
  'LBL_FIRST_NAME' => 'First Name:',
  'LBL_OFFICE_PHONE' => 'Office Phone:',
  'LBL_ANY_PHONE' => 'Any Phone:',
  'LBL_PHONE' => 'Phone:',
  'LBL_LAST_NAME' => 'Last Name:',
  'LBL_MOBILE_PHONE' => 'Mobile:',
  'LBL_HOME_PHONE' => 'Home:',
  'LBL_OTHER_PHONE' => 'Other Phone:',
  'LBL_FAX_PHONE' => 'Fax:',
  'LBL_STREET' => 'Street',
  'LBL_PRIMARY_ADDRESS_STREET' => 'Primary Address Street:',
  'LBL_PRIMARY_ADDRESS_CITY' => 'Primary Address City:',
  'LBL_PRIMARY_ADDRESS_COUNTRY' => 'Primary Address Country:',
  'LBL_PRIMARY_ADDRESS_STATE' => 'Primary Address State:',
  'LBL_PRIMARY_ADDRESS_POSTALCODE' => 'Primary Address Postal Code:',
  'LBL_ALT_ADDRESS_STREET' => 'Alternate Address Street:',
  'LBL_ALT_ADDRESS_CITY' => 'Alternate Address City:',
  'LBL_ALT_ADDRESS_COUNTRY' => 'Alternate Address Country:',
  'LBL_ALT_ADDRESS_STATE' => 'Alternate Address State:',
  'LBL_ALT_ADDRESS_POSTALCODE' => 'Alternate Address Postal Code:',
  'LBL_TITLE' => 'Title:',
  'LBL_DEPARTMENT' => 'Department:',
  'LBL_BIRTHDATE' => 'Birthdate:',
  'LBL_EMAIL_ADDRESS' => 'Email Address:',
  'LBL_OTHER_EMAIL_ADDRESS' => 'Other Email:',
  'LBL_ANY_EMAIL' => 'Email:',
  'LBL_ASSISTANT' => 'Assistant:',
  'LBL_ASSISTANT_PHONE' => 'Assistant Phone:',
  'LBL_DO_NOT_CALL' => 'Do Not Call:',
  'LBL_EMAIL_OPT_OUT' => 'Email Opt Out:',
  'LBL_PRIMARY_ADDRESS' => 'Primary Address:',
  'LBL_ALTERNATE_ADDRESS' => 'Other Address:',
  'LBL_ANY_ADDRESS' => 'Any Address:',
  'LBL_CITY' => 'City:',
  'LBL_STATE' => 'State:',
  'LBL_POSTAL_CODE' => 'Postal Code:',
  'LBL_COUNTRY' => 'Country:',
  'LBL_DESCRIPTION_INFORMATION' => 'Description Information',
  'LBL_ADDRESS_INFORMATION' => 'Address Information',
  'LBL_DESCRIPTION' => 'Description:',
  'LBL_PROSPECT_ROLE' => 'Role:',
  'LBL_OPP_NAME' => 'Opportunity Name:',
  'LBL_IMPORT_VCARD' => 'Import vCard',
  'LBL_IMPORT_VCARD_SUCCESS' => 'Target from vCard created succesfully',
  'LBL_IMPORT_VCARDTEXT' => 'Automatically create a new target by importing a vCard from your file system.',
  'LBL_DUPLICATE' => 'Possible Duplicate Targets',
  'MSG_SHOW_DUPLICATES' => 'The Target record you are about to create might be a duplicate of a Target record that already exists. Target records containing similar names and/or email addresses are listed below.<br>Click Create Target to continue creating this new Target, or select an existing target listed below.',
  'MSG_DUPLICATE' => 'The Target record you are about to create might be a duplicate of a Target record that already exists. Target records containing similar names and/or email addresses are listed below.<br>Click Save to continue creating this new Target, or click Cancel to return to the module without creating the Target.',
  'LNK_IMPORT_VCARD' => 'Create Target From vCard',
  'LNK_NEW_ACCOUNT' => 'Create Account',
  'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
  'LNK_NEW_CASE' => 'Create Case',
  'LNK_NEW_NOTE' => 'Create Note or Attachment',
  'LNK_NEW_CALL' => 'Log Call',
  'LNK_NEW_EMAIL' => 'Archive Email',
  'LNK_NEW_MEETING' => 'Schedule Meeting',
  'LNK_NEW_TASK' => 'Create Task',
  'LNK_NEW_APPOINTMENT' => 'Create Appointment',
  'LNK_IMPORT_PROSPECTS' => 'Import Targets',
  'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
  'NTC_REMOVE_CONFIRMATION' => 'Are you sure you want to remove this contact from the case?',
  'NTC_REMOVE_DIRECT_REPORT_CONFIRMATION' => 'Are you sure you want to remove this record as a direct report?',
  'ERR_DELETE_RECORD' => 'A record number must be specified to delete the contact.',
  'NTC_COPY_PRIMARY_ADDRESS' => 'Copy primary address to alternate address',
  'NTC_COPY_ALTERNATE_ADDRESS' => 'Copy alternate address to primary address',
  'LBL_SALUTATION' => 'Salutation',
  'LBL_SAVE_PROSPECT' => 'Save Target',
  'LBL_CREATED_OPPORTUNITY' =>'Created a new opportunity',
  'NTC_OPPORTUNITY_REQUIRES_ACCOUNT' => 'Creating an opportunity requires an account.\n Please either create a new account or select an existing one.',
  'LNK_SELECT_ACCOUNT' => "Select Account",
  'LNK_NEW_PROSPECT' => 'Create Target',
  'LNK_PROSPECT_LIST' => 'View Targets',
  'LNK_NEW_CAMPAIGN' => 'Create Campaign',
  'LNK_CAMPAIGN_LIST' => 'Campaigns',
  'LNK_NEW_PROSPECT_LIST' => 'Create Target List',
  'LNK_PROSPECT_LIST_LIST' => 'Target Lists',
  'LNK_IMPORT_PROSPECT' => 'Import Targets',
  'LBL_SELECT_CHECKED_BUTTON_LABEL' => 'Select Checked Targets',
  'LBL_SELECT_CHECKED_BUTTON_TITLE' => 'Select Checked Targets',
  'LBL_INVALID_EMAIL'=>'Invalid Email:',
  'LBL_DEFAULT_SUBPANEL_TITLE'=>'Targets',
  'LBL_PROSPECT_LIST' => 'Prospect List',
  'LBL_CONVERT_BUTTON_KEY' => 'V',
  'LBL_CONVERT_BUTTON_TITLE' => 'Convert Target',
  'LBL_CONVERT_BUTTON_LABEL' => 'Convert Target',
  'LBL_CONVERTPROSPECT'=>'Convert Target',
  'LNK_NEW_CONTACT'=>'New Contact',
  'LBL_CREATED_CONTACT'=>"Created a new contact",
  'LBL_BACKTO_PROSPECTS'=>'Back to Targets',
  'LBL_CAMPAIGNS'=>'Campaigns',
  'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE'=>'Campaign Log',
  'LBL_TRACKER_KEY'=>'Tracker Key',
  'LBL_LEAD_ID'=>'Lead Id',
  'LBL_LEAD' => 'Lead',
  'LBL_CONVERTED_LEAD'=>'Converted Lead',
  'LBL_ACCOUNT_NAME'=>'Account Name',
  'LBL_EDIT_ACCOUNT_NAME'=>'Account Name:',
  'LBL_CREATED_USER' => 'Created User',
  'LBL_MODIFIED_USER' => 'Modified User',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Campaigns',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'History',
  //For export labels
  'LBL_PHONE_HOME' => 'Phone Home',
  'LBL_PHONE_MOBILE' => 'Phone Mobile',
  'LBL_PHONE_WORK' => 'Phone Work',
  'LBL_PHONE_OTHER' => 'Phone Other',
  'LBL_PHONE_FAX' => 'Phone Fax',
  'LBL_CAMPAIGN_ID' => 'Campaign ID',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigned User Name',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigned User ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modified By ID',
  'LBL_EXPORT_CREATED_BY' => 'Created By ID',
  'LBL_EXPORT_EMAIL2'=>'Other Email Address',
  'LBL_RECORD_SAVED_SUCCESS' => 'You successfully created the {{moduleSingularLower}} <a href="#{{buildRoute model=this}}">{{full_name}}</a>.',
    //D&B Principal Identification
    'LBL_DNB_PRINCIPAL_ID' => 'D&B Principal Id',
    //Document title
    'TPL_BROWSER_SUGAR7_RECORDS_TITLE' => '{{module}} &raquo; {{appId}}',
    'TPL_BROWSER_SUGAR7_RECORD_TITLE' => '{{#if last_name}}{{#if first_name}}{{first_name}} {{/if}}{{last_name}} &raquo; {{/if}}{{module}} &raquo; {{appId}}',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{module_name}} module consists of individual people who are unqualified prospects that you have some information on, but is not yet a qualified {{leads_singular_module}}. Information (e.g. name, email address) regarding these {{plural_module_name}} are normally acquired from business cards you receive while attending various trades shows, conferences, etc. {{plural_module_name}} in Sugar are stand-alone records as they are not related to {{contacts_module}}, {{leads_module}}, {{accounts_module}}, or {{opportunities_module}}. There are various ways you can create {{plural_module_name}} in Sugar such as via the {{plural_module_name}} module, importing {{plural_module_name}}, etc. Once the {{module_name}} record is created, you can view and edit information pertaining to the {{module_name}} via the {{plural_module_name}} Record view.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{module_name}} module consists of individual people who are unqualified prospects that you have some information on, but is not yet a qualified {{leads_singular_module}}.

- Edit this record\'s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{module_name}} module consists of individual people who are unqualified prospects that you have some information on, but is not yet a qualified {{leads_singular_module}}.

To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.',

    'LBL_FILTER_PROSPECTS_REPORTS' => 'Targets\' reports',
    'LBL_DATAPRIVACY_BUSINESS_PURPOSE' => 'Business Purposes Consented for',
    'LBL_DATAPRIVACY_CONSENT_LAST_UPDATED' => 'Consent Last Updated',
);
