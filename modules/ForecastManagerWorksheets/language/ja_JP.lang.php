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
    'LBL_MODULE_NAME' => '売上予測マネージャワークシート',
    'LBL_MODULE_NAME_SINGULAR' => '売上予測マネージャワークシート',
    'LNK_NEW_OPPORTUNITY' => '商談作成',
    'LBL_MODULE_TITLE' => '売上予測マネージャワークシート',
    'LBL_LIST_FORM_TITLE' => '確定売上予測',
    'LNK_UPD_FORECAST' => '売上予測マネージャワークシート',
    'LNK_QUOTA' => '見方割当て',
    'LNK_FORECAST_LIST' => '売上予測履歴の表示',
    'LBL_FORECAST_HISTORY' => '売上予測: 履歴',
    'LBL_FORECAST_HISTORY_TITLE' => '履歴',

    //var defs
    'LBL_TIMEPERIOD_NAME' => '期間',
    'LBL_USER_NAME' => 'ユーザ名',
    'LBL_REPORTS_TO_USER_NAME' => '上司',

    //forecast table
    'LBL_FORECAST_ID' => '売上予測ID',
    'LBL_FORECAST_TIME_ID' => '期間ID',
    'LBL_FORECAST_TYPE' => '売上予測タイプ',
    'LBL_FORECAST_OPP_COUNT' => 'トータル商談数',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'パイプライン商談数',
    'LBL_FORECAST_OPP_WEIGH'=> '加重金額',
    'LBL_FORECAST_USER' => 'ユーザ',
    'LBL_DATE_COMMITTED'=> '確定日',
    'LBL_DATE_ENTERED' => '作成日',
    'LBL_DATE_MODIFIED' => '更新日',
    'LBL_CREATED_BY' => '作成者',
    'LBL_DELETED' => '削除済み',
    'LBL_MODIFIED_USER_ID'=>'更新者',
    'LBL_WK_VERSION' => 'バージョン',
    'LBL_WK_REVISION' => '版',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => '期間:',
    'LBL_QC_OPPORTUNITY_COUNT' => '商談数:',
    'LBL_QC_WEIGHT_VALUE' => '加重金額:',
    'LBL_QC_COMMIT_VALUE' => '確定金額:',
    'LBL_QC_COMMIT_BUTTON' => '確定',
    'LBL_QC_WORKSHEET_BUTTON' => 'ワークシート',
    'LBL_QC_ROLL_COMMIT_VALUE' => '総確定金額:',
    'LBL_QC_DIRECT_FORECAST' => '私個人の売上予測:',
    'LBL_QC_ROLLUP_FORECAST' => '私のグループの売上予測:',
    'LBL_QC_UPCOMING_FORECASTS' => '私の売上予測',
    'LBL_QC_LAST_DATE_COMMITTED' => '前回の確定日:',
    'LBL_QC_LAST_COMMIT_VALUE' => '前回の確定金額:',
    'LBL_QC_HEADER_DELIM'=> 'To',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "商談",
    'LBL_OW_ACCOUNTNAME' => "取引先",
    'LBL_OW_REVENUE' => "金額",
    'LBL_OW_WEIGHTED' => "加重金額",
    'LBL_OW_MODULE_TITLE'=> '商談ワークシート',
    'LBL_OW_PROBABILITY'=>'確度',
    'LBL_OW_NEXT_STEP'=>'次のステップ:',
    'LBL_OW_DESCRIPTION'=>'詳細',
    'LBL_OW_TYPE'=>'タイプ',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'個人レポート',
    'LBL_FDR_OPPORTUNITIES'=>'売上予測中の商談:',
    'LBL_FDR_WEIGH'=>'商談の加重金額:',
    'LBL_FDR_COMMIT'=>'確定金額',
    'LBL_FDR_DATE_COMMIT'=>'確定日',

    //detail view.
    'LBL_DV_HEADER' => '売上予測: ワークシート',
    'LBL_DV_MY_FORECASTS' => '私の売上予測',
    'LBL_DV_MY_TEAM' => "私のチームの予算" ,
    'LBL_DV_TIMEPERIODS' => '期間:',
    'LBL_DV_FORECAST_PERIOD' => '売上予測期間',
    'LBL_DV_FORECAST_OPPORTUNITY' => '商談予測',
    'LBL_SEARCH' => '選択',
    'LBL_SEARCH_LABEL' => '選択',
    'LBL_COMMIT_HEADER' => '売上予測の確定',
    'LBL_DV_LAST_COMMIT_DATE' =>'前回の確定日:',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'最終確定値:',
    'LBL_DV_FORECAST_ROLLUP' => '総売上予測',
    'LBL_DV_TIMEPERIOD' => '期間:',
    'LBL_DV_TIMPERIOD_DATES' => '期間:',
    'LBL_LOADING_COMMIT_HISTORY' => 'コミット履歴をロードしています...',

    //list view
    'LBL_LV_TIMPERIOD'=> '期間',
    'LBL_LV_TIMPERIOD_START_DATE'=> '開始日',
    'LBL_LV_TIMPERIOD_END_DATE'=> '終了日',
    'LBL_LV_TYPE'=> '売上予測タイプ',
    'LBL_LV_COMMIT_DATE'=> '確定日',
    'LBL_LV_OPPORTUNITIES'=> '商談',
    'LBL_LV_WEIGH'=> '加重金額',
    'LBL_LV_COMMIT'=> '確定金額',

    'LBL_COMMIT_NOTE'=> '選択した期間に確定する金額を入力してください:',

    'LBL_COMMIT_MESSAGE'=> 'この金額を確定しますか？',
    'ERR_FORECAST_AMOUNT' => '確定金額は必須であり、数値である必要があります。',

    // js error strings
    'LBL_FC_START_DATE' => '開始日',
    'LBL_FC_USER' => 'スケジュール',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'売上予測のためのアクティブな期間がありません。',
    'LBL_FDR_ADJ_AMOUNT'=>'調整済み値',
    'LBL_SAVE_WOKSHEET'=>'ワークシート保存',
    'LBL_RESET_WOKSHEET'=>'ワークシートリセット',
    'LBL_SHOW_CHART'=>'チャートの表示',
    'LBL_RESET_CHECK'=>'ログインユーザの指定された期間のワークシートが削除されます。継続しますか？',

    'LBL_CURRENCY' => '通貨',
    'LBL_CURRENCY_ID' => '通貨ID',
    'LBL_CURRENCY_RATE' => '通貨レート',
    'LBL_BASE_RATE' => '基本レート',

    'LBL_QUOTA' => 'ノルマ',
    'LBL_QUOTA_ADJUSTED' => 'ノルマ（調整済）',

    'LBL_FORECAST' => '売上予測',
    'LBL_COMMIT_STAGE' => 'コミットステージ',
    'LBL_SALES_STAGE' => 'ステージ',
    'LBL_AMOUNT' => '金額',
    'LBL_PERCENT' => 'パーセント完了',
    'LBL_DATE_CLOSED' => 'クローズ予定日',
    'LBL_PRODUCT_ID' => '商品ID',
    'LBL_QUOTA_ID' => 'ノルマID',
    'LBL_VERSION' => 'バージョン',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{{full_name}}（自分）',

    'LBL_EDITABLE_INVALID' => '{{field_name}}は無効な数値',
    'LBL_EDITABLE_INVALID_RANGE' => '数値は{{min}} から {{max}}の間であるべきです',
    'LBL_HISTORY_LOG' => '最終確定',
    'LBL_NO_COMMIT' => '以前のコミットはありません',

    'LBL_MANGER_SAVED' => '保存されたマネージャー'

);
