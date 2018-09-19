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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Opportunities List Dashboard',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Opportunities Record Dashboard',

    'LBL_MODULE_NAME' => 'Opportunities',
    'LBL_MODULE_NAME_SINGULAR' => 'Opportunity',
    'LBL_MODULE_TITLE' => 'Opportunities: Home',
    'LBL_SEARCH_FORM_TITLE' => 'Opportunity Search',
    'LBL_VIEW_FORM_TITLE' => 'Opportunity View',
    'LBL_LIST_FORM_TITLE' => 'Opportunity List',
    'LBL_OPPORTUNITY_NAME' => 'Opportunity Name:',
    'LBL_OPPORTUNITY' => 'Opportunity:',
    'LBL_NAME' => 'Opportunity Name',
    'LBL_INVITEE' => 'Contacts',
    'LBL_CURRENCIES' => 'Currencies',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Name',
    'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
    'LBL_LIST_DATE_CLOSED' => 'Expected Close Date',
    'LBL_LIST_AMOUNT' => 'Likely',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Converted Amount',
    'LBL_ACCOUNT_ID' => 'Account ID',
    'LBL_CURRENCY_RATE' => 'Currency Rate',
    'LBL_CURRENCY_ID' => 'Currency ID',
    'LBL_CURRENCY_NAME' => 'Currency Name',
    'LBL_CURRENCY_SYMBOL' => 'Currency Symbol',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Opportunity - Currency Update',
    'UPDATE_DOLLARAMOUNTS' => 'Update U.S. Dollar Amounts',
    'UPDATE_VERIFY' => 'Verify Amounts',
    'UPDATE_VERIFY_TXT' => 'Verifies that the amount values in Opportunities are valid decimal numbers with only numeric characters(0-9) and decimals(.)',
    'UPDATE_FIX' => 'Fix Amounts',
    'UPDATE_FIX_TXT' => 'Attempts to fix any invalid amounts by creating a valid decimal from the current amount. Any modified amount is backed up in the amount_backup database field. If you run this and notice bugs, do not rerun it without restoring from the backup as it may overwrite the backup with new invalid data.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Update the U.S. Dollar amounts for Opportunities based on the current set Currency rates. This value is used to calculate Graphs and List View Currency Amounts.',
    'UPDATE_CREATE_CURRENCY' => 'Creating New Currency:',
    'UPDATE_VERIFY_FAIL' => 'Record Failed Verification:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Current Amount:',
    'UPDATE_VERIFY_FIX' => 'Running Fix would give',
    'UPDATE_INCLUDE_CLOSE' => 'Include Closed Records',
    'UPDATE_VERIFY_NEWAMOUNT' => 'New Amount:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'New Currency:',
    'UPDATE_DONE' => 'Done',
    'UPDATE_BUG_COUNT' => 'Bugs Found and Attempted to Resolve:',
    'UPDATE_BUGFOUND_COUNT' => 'Bugs Found:',
    'UPDATE_COUNT' => 'Records Updated:',
    'UPDATE_RESTORE_COUNT' => 'Record Amounts Restored:',
    'UPDATE_RESTORE' => 'Restore Amounts',
    'UPDATE_RESTORE_TXT' => 'Restores amount values from the backups created during fix.',
    'UPDATE_FAIL' => 'Could not update - ',
    'UPDATE_NULL_VALUE' => 'Amount is NULL setting it to 0 -',
    'UPDATE_MERGE' => 'Merge Currencies',
    'UPDATE_MERGE_TXT' => 'Merge multiple Currencies into a single Currency. If there are multiple Currency records for the same Currency, you merge them together. This will also merge the Currencies for all other modules.',
    'LBL_ACCOUNT_NAME' => 'Account Name:',
    'LBL_CURRENCY' => 'Currency:',
    'LBL_DATE_CLOSED' => 'Expected Close Date:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Expected Close Date Timestamp',
    'LBL_TYPE' => 'Type:',
    'LBL_CAMPAIGN' => 'Campaign:',
    'LBL_NEXT_STEP' => 'Next Step:',
    'LBL_LEAD_SOURCE' => 'Lead Source',
    'LBL_SALES_STAGE' => 'Sales Stage',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Probability (%)',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_DUPLICATE' => 'Possible Duplicate Opportunity',
    'MSG_DUPLICATE' => 'The Opportunity record you are about to create might be a duplicate of an Opportunity record that already exists. Opportunity records containing similar names are listed below.<br>Click Save to continue creating this new Opportunity, or click Cancel to return to the module without creating the Opportunity.',
    'LBL_NEW_FORM_TITLE' => 'Create Opportunity',
    'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
    'LNK_CREATE' => 'Create Deal',
    'LNK_OPPORTUNITY_LIST' => 'View Opportunities',
    'ERR_DELETE_RECORD' => 'A record number must be specified to delete the Opportunity.',
    'LBL_TOP_OPPORTUNITIES' => 'My Top Open Opportunities',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Are you sure you want to remove this Contact from the Opportunity?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Are you sure you want to remove this Opportunity from the project?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Opportunities',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'History',
    'LBL_RAW_AMOUNT' => 'Raw Amount',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documents',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projects',
    'LBL_ASSIGNED_TO_NAME' => 'Assigned to:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned User',
    'LBL_LIST_SALES_STAGE' => 'Sales Stage',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'My Closed Opportunities',
    'LBL_TOTAL_OPPORTUNITIES' => 'Total Opportunities',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Closed Won Opportunities',
    'LBL_ASSIGNED_TO_ID' => 'Assigned User:',
    'LBL_CREATED_ID' => 'Created by ID',
    'LBL_MODIFIED_ID' => 'Modified by ID',
    'LBL_MODIFIED_NAME' => 'Modified by User Name',
    'LBL_CREATED_USER' => 'Created User',
    'LBL_MODIFIED_USER' => 'Modified User',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Campaign Opportunity',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projects',
    'LABEL_PANEL_ASSIGNMENT' => 'Assignment',
    'LNK_IMPORT_OPPORTUNITIES' => 'Import Opportunities',
    'LBL_EDITLAYOUT' => 'Edit Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Campaign ID',
    'LBL_OPPORTUNITY_TYPE' => 'Opportunity Type',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigned User Name',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigned User ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modified By ID',
    'LBL_EXPORT_CREATED_BY' => 'Created By ID',
    'LBL_EXPORT_NAME' => 'Name',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Related Contacts\' Emails',
    'LBL_FILENAME' => 'Attachment',
    'LBL_PRIMARY_QUOTE_ID' => 'Primary Quote',
    'LBL_CONTRACTS' => 'Contracts',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contracts',
    'LBL_PRODUCTS' => 'Quoted Line Items',
    'LBL_RLI' => 'Revenue Line Items',
    'LNK_OPPORTUNITY_REPORTS' => 'View Opportunity Reports',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Quotes',
    'LBL_TEAM_ID' => 'Team ID',
    'LBL_TIMEPERIODS' => 'Time Periods',
    'LBL_TIMEPERIOD_ID' => 'Time Period ID',
    'LBL_COMMITTED' => 'Committed',
    'LBL_FORECAST' => 'Include in Forecast',
    'LBL_COMMIT_STAGE' => 'Commit Stage',
    'LBL_COMMIT_STAGE_FORECAST' => 'Forecast',
    'LBL_WORKSHEET' => 'Worksheet',

    'TPL_RLI_CREATE' => 'An Opportunity must have an associated Revenue Line Item.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Create a Revenue Line Item.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Quoted Line Items',
    'LBL_RLI_SUBPANEL_TITLE' => 'Revenue Line Items',

    'LBL_TOTAL_RLIS' => '# of Total Revenue Line Items',
    'LBL_CLOSED_RLIS' => '# of Closed Revenue Line Items',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'You cannot delete Opportunities that contain closed Revenue Line Items',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'One or more of the selected records contains closed Revenue Line Items and cannot be deleted.',
    'LBL_INCLUDED_RLIS' => '# of Included Revenue Line Items',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Quotes',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Opportunity Hierarchy',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Set the Expected Close Date field on the resulting Opportunity records to be the earliest or latest close dates of the existing Revenue Line Items',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Pipeline Total is ',

    'LBL_OPPORTUNITY_ROLE'=>'Opportunity Role',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'By clicking Confirm, you will be erasing ALL Forecasts data and changing your Opportunities View. If this is not what you intended, click cancel to return to previous settings.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'By clicking Confirm, you will be erasing ALL Forecasts data and changing your Opportunities View. '
        .'Also ALL Process Definitions with a target module of Revenue Line Items will be disabled. '
        .'If this is not what you intended, click cancel to return to previous settings.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'If all Revenue Line Items are closed and at least one was won,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'the Opportunity Sales Stage is set to "Closed Won".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'If all Revenue Line Items are in the "Closed Lost" Sales Stage,',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'the Opportunity Sales Stage is set to "Closed Lost".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'If any Revenue Line Items are still open,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'the Opportunity will be marked with the least-advanced Sales Stage.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'After you initiate this change, the Revenue Line Item summarization notes will be built in the background. When the notes are complete and available, a notification will be sent to the email address on your user profile. If your instance is set up for {{forecasts_module}}, Sugar will also send you a notification when your {{module_name}} records are synced to the {{forecasts_module}} module and available for new {{forecasts_module}}. Please note that your instance must be configured to send email via Admin > Email Settings in order for the notifications to be sent.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'After you initiate this change, Revenue Line Item records will be created for each existing {{module_name}} in the background. When the Revenue Line Items are complete and available, a notification will be sent to the email address on your user profile. Please note that your instance must be configured to send email via Admin > Email Settings in order for the notification to be sent.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module allows you to track individual sales and the line items belonging to those sales from start to finish. Each {{module_name}} record represents a header for a group of {{revenuelineitems_module}} as well as relating to other important records such as {{quotes_module}}, {{contacts_module}}, etc. Each {{revenuelineitems_singular_module}} is the prospective sale of a particular product and includes relevant sale data. Each {{revenuelineitems_singular_module}} will typically progress through several Sales Stages until it is marked either "Closed Won" or "Closed Lost". The {{module_name}} record reflects the amount and expected close date of its {{revenuelineitems_module}}. {{plural_module_name}} and {{revenuelineitems_module}} can be leveraged even further by using Sugar\'s {{forecasts_singular_module}}ing module to understand and predict sales trends as well as focus work to achieve sales quotas.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module allows you to track individual sales and the line items belonging to those sales from start to finish. Each {{module_name}} record represents a header for a group of {{revenuelineitems_module}} as well as relating to other important records such as {{quotes_module}}, {{contacts_module}}, etc.

- Edit this record\'s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels, including {{revenuelineitems_module}}, by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{plural_module_name}} module allows you to track individual sales and the line items belonging to those sales from start to finish. Each {{module_name}} record represents a header for a group of {{revenuelineitems_module}} as well as relating to other important records such as {{quotes_module}}, {{contacts_module}}, etc.

To create an {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.
3. After saving, use the {{revenuelineitems_module}} subpanel to add line items to the {{module_name}}.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sync to Marketo&reg;',
    'LBL_MKTO_ID' => 'Marketo Lead ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 Sales Opportunities',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Displays top ten Opportunities in a bubble chart.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'My Opportunities',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "My Team's Opportunities",
);
