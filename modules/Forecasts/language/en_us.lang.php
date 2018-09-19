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
    'LBL_FORECASTS_DASHBOARD' => 'Forecasts Dashboard',

    //module strings.
    'LBL_MODULE_NAME' => 'Forecasts',
    'LBL_MODULE_NAME_SINGULAR' => 'Forecast',
    'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
    'LBL_MODULE_TITLE' => 'Forecasts',
    'LBL_LIST_FORM_TITLE' => 'Committed Forecasts',
    'LNK_UPD_FORECAST' => 'Forecast Worksheet',
    'LNK_QUOTA' => 'View Quotas',
    'LNK_FORECAST_LIST' => 'View Forecast History',
    'LBL_FORECAST_HISTORY' => 'Forecasts: History',
    'LBL_FORECAST_HISTORY_TITLE' => 'History',

    //var defs
    'LBL_TIMEPERIOD_NAME' => 'Time Period',
    'LBL_USER_NAME' => 'User Name',
    'LBL_REPORTS_TO_USER_NAME' => 'Reports To',

    //forecast table
    'LBL_FORECAST_ID' => 'Forecast ID',
    'LBL_FORECAST_TIME_ID' => 'Time Period ID',
    'LBL_FORECAST_TYPE' => 'Forecast Type',
    'LBL_FORECAST_OPP_COUNT' => 'Total Opportunity Count',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'Pipeline Opportunity Count',
    'LBL_FORECAST_OPP_WEIGH'=> 'Weighted Amount',
    'LBL_FORECAST_OPP_COMMIT' => 'Likely Case',
    'LBL_FORECAST_OPP_BEST_CASE'=>'Best Case',
    'LBL_FORECAST_OPP_WORST'=>'Worst Case',
    'LBL_FORECAST_USER' => 'User',
    'LBL_DATE_COMMITTED'=> 'Date Committed',
    'LBL_DATE_ENTERED' => 'Date Entered',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_CREATED_BY' => 'Created by',
    'LBL_DELETED' => 'Deleted',
    'LBL_MODIFIED_USER_ID'=>'Modified By',
    'LBL_WK_VERSION' => 'Version',
    'LBL_WK_REVISION' => 'Revision',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => 'Time Period:',
    'LBL_QC_OPPORTUNITY_COUNT' => 'Opportunity Count:',
    'LBL_QC_WEIGHT_VALUE' => 'Weighted Amount:',
    'LBL_QC_COMMIT_VALUE' => 'Commit Amount:',
    'LBL_QC_COMMIT_BUTTON' => 'Commit',
    'LBL_QC_WORKSHEET_BUTTON' => 'Worksheet',
    'LBL_QC_ROLL_COMMIT_VALUE' => 'Rollup Commit Amount:',
    'LBL_QC_DIRECT_FORECAST' => 'My Direct Forecast:',
    'LBL_QC_ROLLUP_FORECAST' => 'My Group Forecast:',
    'LBL_QC_UPCOMING_FORECASTS' => 'My Forecasts',
    'LBL_QC_LAST_DATE_COMMITTED' => 'Last Commit Date:',
    'LBL_QC_LAST_COMMIT_VALUE' => 'Last Commit Amount:',
    'LBL_QC_HEADER_DELIM'=> 'To',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "Opportunity",
    'LBL_OW_ACCOUNTNAME' => "Account",
    'LBL_OW_REVENUE' => "Amount",
    'LBL_OW_WEIGHTED' => "Weighted Amount",
    'LBL_OW_MODULE_TITLE'=> 'Opportunity Worksheet',
    'LBL_OW_PROBABILITY'=>'Probability',
    'LBL_OW_NEXT_STEP'=>'Next Step',
    'LBL_OW_DESCRIPTION'=>'Description',
    'LBL_OW_TYPE'=>'Type',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'Direct Report',
    'LBL_FDR_OPPORTUNITIES'=>'Opportunities in Forecast:',
    'LBL_FDR_WEIGH'=>'Weighted Amount of Opportunities:',
    'LBL_FDR_COMMIT'=>'Committed Amount',
    'LBL_FDR_DATE_COMMIT'=>'Commit Date',

    //detail view.
    'LBL_DV_HEADER' => 'Forecasts:Worksheet',
    'LBL_DV_MY_FORECASTS' => 'My Forecasts',
    'LBL_DV_MY_TEAM' => "My Team's Forecasts" ,
    'LBL_DV_TIMEPERIODS' => 'Time Periods:',
    'LBL_DV_FORECAST_PERIOD' => 'Forecast Time Period',
    'LBL_DV_FORECAST_OPPORTUNITY' => 'Forecast Opportunities',
    'LBL_SEARCH' => 'Select',
    'LBL_SEARCH_LABEL' => 'Select',
    'LBL_COMMIT_HEADER' => 'Forecast Commit',
    'LBL_DV_LAST_COMMIT_DATE' =>'Last Commit Date:',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'Last Commit Amounts:',
    'LBL_DV_FORECAST_ROLLUP' => 'Forecast Rollup',
    'LBL_DV_TIMEPERIOD' => 'Time Period:',
    'LBL_DV_TIMPERIOD_DATES' => 'Date Range:',
    'LBL_LOADING_COMMIT_HISTORY' => 'Loading Commit History...',

    //list view
    'LBL_LV_TIMPERIOD'=> 'Time Period',
    'LBL_LV_TIMPERIOD_START_DATE'=> 'Start Date',
    'LBL_LV_TIMPERIOD_END_DATE'=> 'End Date',
    'LBL_LV_TYPE'=> 'Forecast Type',
    'LBL_LV_COMMIT_DATE'=> 'Date Committed',
    'LBL_LV_OPPORTUNITIES'=> 'Opportunities',
    'LBL_LV_WEIGH'=> 'Weighted Amount',
    'LBL_LV_COMMIT'=> 'Committed Amount',

    'LBL_COMMIT_NOTE' => 'Enter amounts that you would like to commit for the selected Time Period:',
    'LBL_COMMIT_TOOLTIP' => 'To enable Commit: Change a value in the worksheet',
    'LBL_COMMIT_MESSAGE' => 'Do you want to commit these amounts?',
    'ERR_FORECAST_AMOUNT' => 'Commit amount is required and must be a number.',

    // js error strings
    'LBL_FC_START_DATE' => 'Start Date',
    'LBL_FC_USER' => 'Schedule For',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'No active Time Periods for the Forecasts module.',
    'LBL_FDR_ADJ_AMOUNT'=>'Adjusted Amount',
    'LBL_SAVE_WOKSHEET'=>'Save Worksheet',
    'LBL_RESET_WOKSHEET'=>'Reset Worksheet',
    'LBL_SHOW_CHART'=>'View Chart',
    'LBL_RESET_CHECK'=>'All worksheet data for the selected Time Period and logged in user will be removed. Continue?',

    'LB_FS_LIKELY_CASE'=>'Likely Case',
    'LB_FS_WORST_CASE'=>'Worst Case',
    'LB_FS_BEST_CASE'=>'Best Case',
    'LBL_FDR_WK_LIKELY_CASE'=>'Est. Likely Case',
    'LBL_FDR_WK_BEST_CASE'=> 'Est. Best Case',
    'LBL_FDR_WK_WORST_CASE'=>'Est. Worst Case',
    'LBL_FDR_C_BEST_CASE'=>'Best Case',
    'LBL_FDR_C_WORST_CASE'=>'Worst Case',
    'LBL_FDR_C_LIKELY_CASE'=>'Likely Case',
    'LBL_QC_LAST_BEST_CASE'=>'Last Commit Amount (Best Case):',
    'LBL_QC_LAST_LIKELY_CASE'=>'Last Commit Amount (Likely Case):',
    'LBL_QC_LAST_WORST_CASE'=>'Last Commit Amount (Worst Case):',
    'LBL_QC_ROLL_BEST_VALUE'=>'Rollup Commit Amount (Best Case):',
    'LBL_QC_ROLL_LIKELY_VALUE'=>'Rollup Commit Amount (Likely Case):',
    'LBL_QC_ROLL_WORST_VALUE'=>'Rollup Commit Amount (Worst Case):',
    'LBL_QC_COMMIT_BEST_CASE'=>'Commit Amount (Best Case):',
    'LBL_QC_COMMIT_LIKELY_CASE'=>'Commit Amount (Likely Case):',
    'LBL_QC_COMMIT_WORST_CASE'=>'Commit Amount (Worst Case):',
    'LBL_CURRENCY' => 'Currency',
    'LBL_CURRENCY_ID' => 'Currency ID',
    'LBL_CURRENCY_RATE' => 'Currency Rate',
    'LBL_BASE_RATE' => 'Base Rate',

    'LBL_QUOTA' => 'Quota',
    'LBL_QUOTA_ADJUSTED' => 'Quota (Adjusted)',

    'LBL_FORECAST_FOR'=>'Forecast Worksheet for: ',
    'LBL_FMT_ROLLUP_FORECAST'=>'(Rollup)',
    'LBL_FMT_DIRECT_FORECAST'=>'(Direct)',

    //labels used by the chart.
    'LBL_GRAPH_TITLE'=>'Forecast History',
    'LBL_GRAPH_QUOTA_ALTTEXT'=>'Quota for %s',
    'LBL_GRAPH_COMMIT_ALTTEXT'=>'Committed Amount for %s',
    'LBL_GRAPH_OPPS_ALTTEXT'=>'Value of Opportunities closed in %s',

    'LBL_GRAPH_QUOTA_LEGEND'=>'Quota',
    'LBL_GRAPH_COMMIT_LEGEND'=>'Committed Forecast',
    'LBL_GRAPH_OPPS_LEGEND'=>'Closed Opportunities',
    'LBL_TP_QUOTA'=>'Quota:',
    'LBL_CHART_FOOTER'=>'Forecast History <br>Quota vs Forecast Amount vs Closed Opportunity Value',
    'LBL_TOTAL_VALUE'=>'Totals:',
    'LBL_COPY_AMOUNT'=>'Total amount',
    'LBL_COPY_WEIGH_AMOUNT'=>'Total weighted amount',
    'LBL_WORKSHEET_AMOUNT'=>'Total estimated amounts',
    'LBL_COPY'=>'Copy Values',
    'LBL_COMMIT_AMOUNT'=>'Sum of Committed values.',
    'LBL_CUMULATIVE_TOTAL'=>'Cumulative Total',
    'LBL_COPY_FROM'=>'Copy value from:',

    'LBL_CHART_TITLE'=>'Quota vs. Committed vs. Actual',

    'LBL_FORECAST' => 'Forecast',
    'LBL_COMMIT_STAGE' => 'Commit Stage',
    'LBL_SALES_STAGE' => 'Stage',
    'LBL_AMOUNT' => 'Amount',
    'LBL_PERCENT' => 'Percent',
    'LBL_DATE_CLOSED' => 'Expected Close',
    'LBL_PRODUCT_ID' => 'Product ID',
    'LBL_QUOTA_ID' => 'Quota ID',
    'LBL_VERSION' => 'Version',
    'LBL_CHART_BAR_LEGEND_CLOSE' => 'Hide bar legend',
    'LBL_CHART_BAR_LEGEND_OPEN' => 'Show bar legend',
    'LBL_CHART_LINE_LEGEND_CLOSE' => 'Hide line legend',
    'LBL_CHART_LINE_LEGEND_OPEN' => 'Show line legend',

    //Labels for forecasting history log and endpoint
    'LBL_ERROR_NOT_MANAGER' => 'Error: user {0} does not have manager access to request Forecasts for {1}',
    'LBL_UP' => 'up',
    'LBL_DOWN' => 'down',
    'LBL_PREVIOUS_COMMIT' => 'Latest Commit:',

    'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => 'Setup Forecast',
    'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => 'Updated Forecast',
    'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
    'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
    'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}, and {{{third}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => 'likely {{{direction}}} {{{from}}} to {{{to}}}',
    'LBL_COMMITTED_HISTORY_BEST_CHANGED' => 'best {{{direction}}} {{{from}}} to {{{to}}}',
    'LBL_COMMITTED_HISTORY_WORST_CHANGED' => 'worst {{{direction}}} {{{from}}} to {{{to}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_SAME' => 'likely stayed the same',
    'LBL_COMMITTED_HISTORY_BEST_SAME' => 'best stayed the same',
    'LBL_COMMITTED_HISTORY_WORST_SAME' => 'worst stayed the same',


    'LBL_COMMITTED_THIS_MONTH' => 'This month on {0}',
    'LBL_COMMITTED_MONTHS_AGO' => '{0} months ago on {1}',

    //Labels for jsTree implementation
    'LBL_TREE_PARENT' => 'Parent',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{0} (me)&#x200E;',

    //Labels for worksheet items
    'LBL_EXPECTED_OPPORTUNITIES' => 'Expected Opportunities',
    'LBL_DISPLAYED_TOTAL' => 'Displayed Total',
    'LBL_TOTAL' => 'Total',
    'LBL_OVERALL_TOTAL' => 'Overall Total',
    'LBL_EDITABLE_INVALID' => 'Invalid Value for {0}',
    'LBL_EDITABLE_INVALID_RANGE' => 'Value must be between {0} and {1}',
    'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => 'You have unsaved changes in your Worksheet.',
    'LBL_WORKSHEET_EXPORT_CONFIRM' => 'Only saved or committed data will be exported. Cancel to abort. Confirm to export saved data.',
    'LBL_WORKSHEET_ID' => 'Worksheet ID',

    // Labels for Chart Options
    'LBL_DATA_SET' => 'Data Set:',
    'LBL_GROUP_BY' => 'Group By:',
    'LBL_CHART_OPTIONS' => 'Chart Options',
    'LBL_CHART_AMOUNT' => 'Amount',
    'LBL_CHART_TYPE' => 'Type',

    // Labels for Data Filters
    'LBL_FILTERS' => 'Filters',

    // Labels for toggle buttons
    'LBL_MORE' => 'More',
    'LBL_LESS' => 'Less',

    // Labels for Progress
    'LBL_PROJECTED' => 'Projected',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => 'Likely above Quota',
    'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => 'Likely below Quota',
    'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => 'Best above Quota',
    'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => 'Best below Quota',
    'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => 'Worst above Quota',
    'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => 'Worst below Quota',
    'LBL_CLOSED' => 'Closed Won',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => 'Likely above Closed',
    'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => 'Likely below Closed',
    'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => 'Best above Closed',
    'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => 'Best below Closed',
    'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => 'Worst above Closed',
    'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => 'Worst below Closed',
    'LBL_REVENUE' => 'Revenue',
    'LBL_PIPELINE_REVENUE' => 'Pipeline Revenue',
    'LBL_PIPELINE_OPPORTUNITIES' => 'Pipeline Opportunities',
    'LBL_LOADING' => 'Loading',
    'LBL_IN_FORECAST' => 'In Forecast',

    // Actions Dropdown
    'LBL_ACTIONS' => 'Actions',
    'LBL_EXPORT_CSV' => 'Export CSV',
    'LBL_CANCEL' => 'Cancel',

    'LBL_CHART_FORECAST_FOR' => ' for {0}',
    'LBL_FORECAST_TITLE' => 'Forecast: {0}',
    'LBL_CHART_INCLUDED' => 'Included',
    'LBL_CHART_NOT_INCLUDED' => 'Not Included',
    'LBL_CHART_ADJUSTED' => ' (Adjusted)',
    'LBL_SAVE_DRAFT' => 'Save Draft',
    'LBL_CHANGES_BY' => 'Changes by {0}',
    'LBL_FORECAST_SETTINGS' => 'Settings',

    // config panels strings
    'LBL_FORECASTS_CONFIG_TITLE' => 'Forecasts Setup',

    'LBL_FORECASTS_MISSING_STAGE_TITLE' => 'Forecasts Configuration Error:',
    'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => 'The Forecasts module has been improperly configured and is no longer available. Sales Stage Won and Sales Stage Lost are missing from the available Sales Stages values. Please contact your Administrator.',
    'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => 'Forecasts Access Error:',
    'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => 'You do not have access to the Forecasts module. Please contact your Administrator.',

    'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG' => 'You do not have access to the Forecasts module\'s records. Please contact your Administrator.',

    // Panel and BreadCrumb Labels
    'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => 'Worksheet Layout',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => 'Ranges',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => 'Scenarios',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => 'Time Periods',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => 'Variables',

    // Admin UI
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => 'Forecast Settings',
    'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => 'Time Period',
    'LBL_FORECASTS_CONFIG_TITLE_RANGES' => 'Forecast Ranges',
    'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => 'Scenarios',
    'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => 'Worksheet Columns',
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => 'View Forecast Worksheet By',

    'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => 'Forecast By',

    'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => 'Fiscal year start date:',

    'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => 'Conﬁgure the Time Period that will be used in the Forecasts module. <br><br>Please note that Time Period settings cannot be changed after initial setup.<br><br>Start by choosing the Start Date of your ﬁscal year. Then choose the type of Time Period for the Forecast. The date range for the Time Periods will be automatically calculated based on your selections. The Sub Time Period is the base for the Forecast worksheet. <br><br>The viewable future and past Time Periods will determine the number of visible sub-periods in the Forecasts module. The users are able to view and edit the Forecast numbers in the visible sub-periods.',
    'LBL_FORECASTS_CONFIG_HELP_RANGES' => 'Configure how you would like to categorize {{forecastByModule}}. <br><br>Please note that the Range settings cannot be changed after the first commit. For upgraded instances, the Range setting is locked in with existing Forecast data.<br><br>You may select two or more categories based on probability ranges or create categories which are not based on probability. <br><br>There are check-boxes to the left of your custom categories; use these to decide which ranges will be included within the Forecast amount committed and reported to managers. <br><br>A user may change the include/exclude status and category of {{forecastByModule}} manually from their worksheet.',
    'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => 'Select the columns you would like the user to fill out for their Forecasts of each {{forecastByModuleSingular}}. Please note the Likely amount is tied to the amount shown in {{forecastByModule}}; for this reason the Likely column cannot be hidden.',
    'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => 'Select which columns you would like to view in the Forecast module. The list of fields will combine the worksheet and allow the user to choose how to configure its view.',
    'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => 'I am a placeholder for Forecast By how-to text!',
    'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => 'Forecasts configuration settings have been saved.',

    // timeperiod config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => 'Time Period settings cannot be changed after initial setup.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => 'Configure the Time Periods used for the Forecasts module.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => 'Select the type of year your organization uses for accounting.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD' => 'Choose the type of Time Period',
    'LBL_FORECASTS_CONFIG_LEAFPERIOD' => 'Choose the sub period that you want to view your Time Period over:',
    'LBL_FORECASTS_CONFIG_START_DATE' => 'Choose fiscal year start date',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => 'Choose the number of future Time Periods to view in the worksheet.<br><i>This number applies to the base Time Period selected. For example, choosing 2 with Yearly Time Period will show 8 future Quarters</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => 'Choose the number of past Time Periods to view in the worksheet.<br><i>This number applies to the base Time Period selected. For example, choosing 2 with Quarterly Time Period will show 6 past Months</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => 'The chosen start date indicates the fiscal year may span across two years. Please choose which year to use as the Fiscal Year:',
    'LBL_FISCAL_YEAR' => 'Fiscal Year',

    // worksheet layout config
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => 'Select how to populate the Forecast worksheet:',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => 'Opportunities',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => 'Revenue Line Items',
    'LBL_REVENUELINEITEM_NAME' => 'Revenue Line Item Name',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => 'Worksheets will be populated with:',

    // ranges config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => 'Range settings cannot be changed after first save draft or commit in the Forecasts module. For an upgraded instance however, Range settings cannot be changed after the initial setup as the Forecasts data is already available through the upgrade.',
    'LBL_FORECASTS_CONFIG_RANGES' => 'Forecast Range Options:',
    'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => 'Select the way you would like to categorize {{forecastByModule}}.',
    'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => 'This option gives a user the ability to specify {{forecastByModule}} that will be included or excluded from a Forecast.',
    'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => 'This option gives a user the ability to categorize their {{forecastByModule}} that are not included in the commit but are upside and have the potential of closing if everything goes well and {{forecastByModule}} that are to be excluded from the Forecast.',
    'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => 'Custom Ranges: This option gives a user the ability to categorize their {{forecastByModule}} to be committed into the Forecast into a committed range, excluded range and any others that you setup.',
    'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => 'The Exclude Range is from 0% to the minimum of the previous Forecast Range by default.',

    'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => 'Enter range name...',

    // scenarios config
    //TODO-sfa refactors the code references for scenarios to be scenarios (SFA-337).
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => 'Choose the Scenarios to include on the Forecast worksheet.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => 'Likely is based on the amount entered in the {{forecastByModule}} module.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => 'Likely',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => 'Best',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => 'Worst',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => 'Show projected scenarios in the totals',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => 'Show Likely Case Totals',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => 'Show Best Case Totals',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => 'Show Worst Case Totals',

    // variables config
    'LBL_FORECASTS_CONFIG_VARIABLES' => 'Variables',
    'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => 'The formulas for the Metrics Table rely on the sales stage for {{forecastByModule}} that need to be excluded from the pipleline, i.e., {{forecastByModule}} that are closed and lost.',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => 'Please select the Sales Stage that represent closed and lost {{forecastByModule}}:',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => 'Please select the Sales Stage that represent closed and won {{forecastByModule}}:',
    'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => 'Therefore the pipeline formula will be:',

    'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => 'Success:',
    'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => 'You successfully set up your Forecasts module. Please wait while the module loads.',
    'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => 'Forecasts configuration settings have been saved. Please wait while the module reloads.',
    // Labels for Success Messages:
    'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => 'You have saved the Forecast worksheet as a draft for the selected Time Period.',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => 'You have committed your Forecast',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => 'You have committed your Forecast to {{manager}}',

    // custom ranges
    'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => 'Custom Range',
    'LBL_UNAUTH_FORECASTS' => 'Unauthorized access to Forecast settings.',
    'LBL_FORECASTS_RANGES_BASED_TITLE' => 'Ranges based on probabilities',
    'LBL_FORECASTS_CUSTOM_BASED_TITLE' => 'Custom Ranges based on probabilities',
    'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' =>'Ranges not based on probabilities',

    // worksheet columns config
    'LBL_DISCOUNT' => 'Discount',
    'LBL_OPPORTUNITY_STATUS' => 'Opportunity Status',
    'LBL_OPPORTUNITY_NAME' => 'Opportunity Name',
    'LBL_PRODUCT_TEMPLATE' => 'Product Catalog',
    'LBL_CAMPAIGN' => 'Campaign',
    'LBL_TEAMS' => 'Teams',
    'LBL_CATEGORY' => 'Category',
    'LBL_COST_PRICE' => 'Cost Price',
    'LBL_TOTAL_DISCOUNT_AMOUNT' => 'Total Discount Amount',
    'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => 'Select which columns should be displayed for the worksheet view. By default, the following fields will be selected:',

    // forecast details dashlet
    'LBL_DASHLET_FORECAST_NOT_SETUP' => 'Forecasts has not been configured and needs to be setup in order to use this widget. Please contact your system administrator.',
    'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => 'Forecasts has not been configured and needs to be setup in order to use this widget.',
    'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => 'Please click here to configure the Forecast module.',
    'LBL_DASHLET_MY_PIPELINE' => 'My Pipeline',
    'LBL_DASHLET_MY_TEAMS_PIPELINE' => "My Team's Pipeline",
    'LBL_DASHLET_PIPELINE_CHART_NAME' => 'Forecast Pipeline Chart',
    'LBL_DASHLET_PIPELINE_CHART_DESC' => 'Displays current pipeline chart.',
    'LBL_FORECAST_DETAILS_DEFICIT' => 'Deficit',
    'LBL_FORECAST_DETAILS_SURPLUS' => 'Surplus',
    'LBL_FORECAST_DETAILS_SHORT' => 'Short by',
    'LBL_FORECAST_DETAILS_EXCEED' => 'Exceed by',
    'LBL_FORECAST_DETAILS_NO_DATA' => 'No Data',
    'LBL_FORECAST_DETAILS_MEETING_QUOTA' => 'Meeting Quota',

    'LBL_ASSIGN_QUOTA_BUTTON' => 'Assign Quota',
    'LBL_ASSIGNING_QUOTA' => 'Assigning Quota',
    'LBL_QUOTA_ASSIGNED' => 'Quotas have been successfully assigned.',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => 'Forecasts Access Error',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => 'You do not have access to configure Forecasts. Please contact your Administrator.',
    'WARNING_DELETED_RECORD_RECOMMIT_1' => 'This record was included in a ',
    'WARNING_DELETED_RECORD_RECOMMIT_2' => 'It will be removed and you will need to re-commit your ',

    'LBL_DASHLET_MY_FORECAST' => 'My Forecast',
    'LBL_DASHLET_MY_TEAMS_FORECAST' => "My Team's Forecast",

    'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => 'You have unsaved changes. Are you sure you want to sort the worksheet and discard changes?',

    // Forecasts Records View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module incorporates {{forecastby_singular_module}} records to build {{forecastworksheets_module}} and predict sales. Users can work towards sales {{quotas_module}} at the individual, team, and sales organization level. Before users can access the {{plural_module_name}} module, an administrator must select the organization\'s desired Time Periods, Ranges, and Scenarios.

Sales representatives use the {{plural_module_name}} module to work with their assigned {{forecastby_module}} as the current time period progresses. These users will commit total predictions for their personal sales based on the {{forecastby_module}} they expect to close. Sales managers work with their own {{forecastby_singular_module}} records similarly to other sales representatives. In addition, they aggregate their reportee\'s committed amounts to predict their total team\'s sales and work towards the team\'s quota for each time period. Additional insights are offered by the elements of the expandable Intelligence Pane including analysis for an individual\'s worksheet and analysis for a manager\'s team worksheets.'
);
