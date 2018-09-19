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
    'LBL_MODULE_NAME' => '預測管理員工作表',
    'LBL_MODULE_NAME_SINGULAR' => '預測管理員工作表',
    'LNK_NEW_OPPORTUNITY' => '建立商機',
    'LBL_MODULE_TITLE' => '預測管理員工作表',
    'LBL_LIST_FORM_TITLE' => '已提交預測',
    'LNK_UPD_FORECAST' => '預測管理員工作表',
    'LNK_QUOTA' => '查看配額',
    'LNK_FORECAST_LIST' => '檢視預測歷史',
    'LBL_FORECAST_HISTORY' => '預測：歷史',
    'LBL_FORECAST_HISTORY_TITLE' => '歷史',

    //var defs
    'LBL_TIMEPERIOD_NAME' => '時間週期',
    'LBL_USER_NAME' => '使用者名稱',
    'LBL_REPORTS_TO_USER_NAME' => '報表發送對象',

    //forecast table
    'LBL_FORECAST_ID' => '預測 ID',
    'LBL_FORECAST_TIME_ID' => '時間週期 ID',
    'LBL_FORECAST_TYPE' => '預測類型',
    'LBL_FORECAST_OPP_COUNT' => '總商機計數',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => '案源商機計數',
    'LBL_FORECAST_OPP_WEIGH'=> '加權金額',
    'LBL_FORECAST_USER' => '使用者',
    'LBL_DATE_COMMITTED'=> '提交日期',
    'LBL_DATE_ENTERED' => '輸入日期',
    'LBL_DATE_MODIFIED' => '修改日期',
    'LBL_CREATED_BY' => '建立人',
    'LBL_DELETED' => '已刪除',
    'LBL_MODIFIED_USER_ID'=>'修改人',
    'LBL_WK_VERSION' => '版本',
    'LBL_WK_REVISION' => '修訂',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => '時間週期：',
    'LBL_QC_OPPORTUNITY_COUNT' => '商機計數：',
    'LBL_QC_WEIGHT_VALUE' => '加權金額︰',
    'LBL_QC_COMMIT_VALUE' => '提交金額：',
    'LBL_QC_COMMIT_BUTTON' => '提交',
    'LBL_QC_WORKSHEET_BUTTON' => '工作表',
    'LBL_QC_ROLL_COMMIT_VALUE' => '總提交金額：',
    'LBL_QC_DIRECT_FORECAST' => '我的直接預測：',
    'LBL_QC_ROLLUP_FORECAST' => '我的群組預測：',
    'LBL_QC_UPCOMING_FORECASTS' => '我的預測',
    'LBL_QC_LAST_DATE_COMMITTED' => '上次提交日期：',
    'LBL_QC_LAST_COMMIT_VALUE' => '上次提交金額：',
    'LBL_QC_HEADER_DELIM'=> '至',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "商機",
    'LBL_OW_ACCOUNTNAME' => "帳戶",
    'LBL_OW_REVENUE' => "金額",
    'LBL_OW_WEIGHTED' => "加權金額",
    'LBL_OW_MODULE_TITLE'=> '商機工作表',
    'LBL_OW_PROBABILITY'=>'可能性',
    'LBL_OW_NEXT_STEP'=>'下一步',
    'LBL_OW_DESCRIPTION'=>'描述',
    'LBL_OW_TYPE'=>'類型',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'直屬員工',
    'LBL_FDR_OPPORTUNITIES'=>'預測中的商機：',
    'LBL_FDR_WEIGH'=>'商機加權金額：',
    'LBL_FDR_COMMIT'=>'已提交金額',
    'LBL_FDR_DATE_COMMIT'=>'提交日期',

    //detail view.
    'LBL_DV_HEADER' => '預測：工作表',
    'LBL_DV_MY_FORECASTS' => '我的預測',
    'LBL_DV_MY_TEAM' => "我的小組的預測" ,
    'LBL_DV_TIMEPERIODS' => '時間週期：',
    'LBL_DV_FORECAST_PERIOD' => '預測時間週期',
    'LBL_DV_FORECAST_OPPORTUNITY' => '預測商機',
    'LBL_SEARCH' => '選擇',
    'LBL_SEARCH_LABEL' => '選擇',
    'LBL_COMMIT_HEADER' => '預測提交',
    'LBL_DV_LAST_COMMIT_DATE' =>'上次提交日期：',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'上次提交金額：',
    'LBL_DV_FORECAST_ROLLUP' => '預測彙總',
    'LBL_DV_TIMEPERIOD' => '時間週期：',
    'LBL_DV_TIMPERIOD_DATES' => '日期範圍：',
    'LBL_LOADING_COMMIT_HISTORY' => '載入提交歷史...',

    //list view
    'LBL_LV_TIMPERIOD'=> '時間週期',
    'LBL_LV_TIMPERIOD_START_DATE'=> '開始日期',
    'LBL_LV_TIMPERIOD_END_DATE'=> '結束日期',
    'LBL_LV_TYPE'=> '預測類型',
    'LBL_LV_COMMIT_DATE'=> '提交日期',
    'LBL_LV_OPPORTUNITIES'=> '商機',
    'LBL_LV_WEIGH'=> '加權金額',
    'LBL_LV_COMMIT'=> '已提交金額',

    'LBL_COMMIT_NOTE'=> '為選取的時間週期選取您想要提交的金額。',

    'LBL_COMMIT_MESSAGE'=> '您想要提交這些金額嗎？',
    'ERR_FORECAST_AMOUNT' => '必須填寫提交金額，且必須為一個數字。',

    // js error strings
    'LBL_FC_START_DATE' => '開始日期',
    'LBL_FC_USER' => '排程',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'預測模組沒有使用中時間週期。',
    'LBL_FDR_ADJ_AMOUNT'=>'已調整的金額',
    'LBL_SAVE_WOKSHEET'=>'儲存工作表',
    'LBL_RESET_WOKSHEET'=>'重置工作表',
    'LBL_SHOW_CHART'=>'檢視圖表',
    'LBL_RESET_CHECK'=>'已選取時間週期的所有工作表資料和已登入的使用者都將被刪除。確定要繼續嗎？',

    'LBL_CURRENCY' => '貨幣',
    'LBL_CURRENCY_ID' => '貨幣 ID',
    'LBL_CURRENCY_RATE' => '貨幣匯率',
    'LBL_BASE_RATE' => '基底匯率',

    'LBL_QUOTA' => '配額',
    'LBL_QUOTA_ADJUSTED' => '配額（已調整）',

    'LBL_FORECAST' => '預測',
    'LBL_COMMIT_STAGE' => '提交階段',
    'LBL_SALES_STAGE' => '階段',
    'LBL_AMOUNT' => '金額',
    'LBL_PERCENT' => '百分比',
    'LBL_DATE_CLOSED' => '預計結束日期',
    'LBL_PRODUCT_ID' => '產品 ID',
    'LBL_QUOTA_ID' => '配額 ID',
    'LBL_VERSION' => '版本',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{{full_name}} (me)&#x200E;',

    'LBL_EDITABLE_INVALID' => '{{field_name}} 值無效',
    'LBL_EDITABLE_INVALID_RANGE' => '必須為 {{min}} 到 {{max}} 之間的值',
    'LBL_HISTORY_LOG' => '上次提交',
    'LBL_NO_COMMIT' => '無上次提交記錄',

    'LBL_MANGER_SAVED' => '管理員已儲存'

);
