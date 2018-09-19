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

    //module strings.
    'LBL_MODULE_NAME' => 'Forecast Manager Worksheets',
    'LBL_MODULE_NAME_SINGULAR' => 'Forecast Manager Worksheet',
    'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
    'LBL_MODULE_TITLE' => 'Forecast Manager Worksheets',
    'LBL_LIST_FORM_TITLE' => 'Committed Forecasts',
    'LNK_UPD_FORECAST' => 'Forecast Manager Worksheet',
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

    'LBL_COMMIT_NOTE'=> 'Enter amounts that you would like to commit for the selected Time Period:',

    'LBL_COMMIT_MESSAGE'=> 'Do you want to commit these amounts?',
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

    'LBL_CURRENCY' => 'Currency',
    'LBL_CURRENCY_ID' => 'Currency ID',
    'LBL_CURRENCY_RATE' => 'Currency Rate',
    'LBL_BASE_RATE' => 'Base Rate',

    'LBL_QUOTA' => 'Quota',
    'LBL_QUOTA_ADJUSTED' => 'Quota (Adjusted)',

    'LBL_FORECAST' => 'Forecast',
    'LBL_COMMIT_STAGE' => 'Commit Stage',
    'LBL_SALES_STAGE' => 'Stage',
    'LBL_AMOUNT' => 'Amount',
    'LBL_PERCENT' => 'Percent',
    'LBL_DATE_CLOSED' => 'Expected Close Date',
    'LBL_PRODUCT_ID' => 'Product ID',
    'LBL_QUOTA_ID' => 'Quota ID',
    'LBL_VERSION' => 'Version',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{{full_name}} (me)&#x200E;',

    'LBL_EDITABLE_INVALID' => 'Invalid Value for {{field_name}}',
    'LBL_EDITABLE_INVALID_RANGE' => 'Value must be between {{min}} and {{max}}',
    'LBL_HISTORY_LOG' => 'Last Commit',
    'LBL_NO_COMMIT' => 'No Previous Commit',

    'LBL_MANGER_SAVED' => 'Manager Saved'

);
