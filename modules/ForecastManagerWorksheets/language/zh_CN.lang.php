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
    'LBL_MODULE_NAME' => '预测经理工作表',
    'LBL_MODULE_NAME_SINGULAR' => '预测经理工作表',
    'LNK_NEW_OPPORTUNITY' => '创建商业机会',
    'LBL_MODULE_TITLE' => '预测经理工作表',
    'LBL_LIST_FORM_TITLE' => '提交的销售预测',
    'LNK_UPD_FORECAST' => '预测经理工作表',
    'LNK_QUOTA' => '查看定额',
    'LNK_FORECAST_LIST' => '查看预测历史记录',
    'LBL_FORECAST_HISTORY' => '预测：历史纪录',
    'LBL_FORECAST_HISTORY_TITLE' => '历史记录',

    //var defs
    'LBL_TIMEPERIOD_NAME' => '时段',
    'LBL_USER_NAME' => '用户名',
    'LBL_REPORTS_TO_USER_NAME' => '汇报对象',

    //forecast table
    'LBL_FORECAST_ID' => '预测 ID',
    'LBL_FORECAST_TIME_ID' => '时段 ID',
    'LBL_FORECAST_TYPE' => '预测类型',
    'LBL_FORECAST_OPP_COUNT' => '总的商业机会计数',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => '商业机会管道计数',
    'LBL_FORECAST_OPP_WEIGH'=> '加权金额',
    'LBL_FORECAST_USER' => '用户',
    'LBL_DATE_COMMITTED'=> '提交的日期',
    'LBL_DATE_ENTERED' => '输入的日期',
    'LBL_DATE_MODIFIED' => '修改的日期',
    'LBL_CREATED_BY' => '创建人',
    'LBL_DELETED' => '已删除',
    'LBL_MODIFIED_USER_ID'=>'修改人',
    'LBL_WK_VERSION' => '版本',
    'LBL_WK_REVISION' => '修订版本',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => '时段:',
    'LBL_QC_OPPORTUNITY_COUNT' => '商业机会总数：',
    'LBL_QC_WEIGHT_VALUE' => '加权金额：',
    'LBL_QC_COMMIT_VALUE' => '承诺金额：',
    'LBL_QC_COMMIT_BUTTON' => '提交',
    'LBL_QC_WORKSHEET_BUTTON' => '工作单',
    'LBL_QC_ROLL_COMMIT_VALUE' => '汇总承诺金额：',
    'LBL_QC_DIRECT_FORECAST' => '我的直接预测：',
    'LBL_QC_ROLLUP_FORECAST' => '我的组预测：',
    'LBL_QC_UPCOMING_FORECASTS' => '我的预测：',
    'LBL_QC_LAST_DATE_COMMITTED' => '上次提交日期：',
    'LBL_QC_LAST_COMMIT_VALUE' => '上次承诺金额：',
    'LBL_QC_HEADER_DELIM'=> '到',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "商业机会",
    'LBL_OW_ACCOUNTNAME' => "帐户",
    'LBL_OW_REVENUE' => "数量",
    'LBL_OW_WEIGHTED' => "加权金额",
    'LBL_OW_MODULE_TITLE'=> '商业机会工作表',
    'LBL_OW_PROBABILITY'=>'成交概率',
    'LBL_OW_NEXT_STEP'=>'下一步',
    'LBL_OW_DESCRIPTION'=>'说明',
    'LBL_OW_TYPE'=>'类型:',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'直接报告',
    'LBL_FDR_OPPORTUNITIES'=>'预测中的商业机会：',
    'LBL_FDR_WEIGH'=>'加权商业机会金额：',
    'LBL_FDR_COMMIT'=>'已提交的金额',
    'LBL_FDR_DATE_COMMIT'=>'提交日期',

    //detail view.
    'LBL_DV_HEADER' => '预测：工作表',
    'LBL_DV_MY_FORECASTS' => '我的预测：',
    'LBL_DV_MY_TEAM' => "我的团队的预测" ,
    'LBL_DV_TIMEPERIODS' => '时段：',
    'LBL_DV_FORECAST_PERIOD' => '预测时段',
    'LBL_DV_FORECAST_OPPORTUNITY' => '预测商业机会',
    'LBL_SEARCH' => '选择',
    'LBL_SEARCH_LABEL' => '选择',
    'LBL_COMMIT_HEADER' => '预测提交',
    'LBL_DV_LAST_COMMIT_DATE' =>'上次提交日期：',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'上一次承诺金额：',
    'LBL_DV_FORECAST_ROLLUP' => '预测汇总',
    'LBL_DV_TIMEPERIOD' => '时间段:',
    'LBL_DV_TIMPERIOD_DATES' => '日期范围：',
    'LBL_LOADING_COMMIT_HISTORY' => '加载提交历史记录…',

    //list view
    'LBL_LV_TIMPERIOD'=> '时段',
    'LBL_LV_TIMPERIOD_START_DATE'=> '开始日期：',
    'LBL_LV_TIMPERIOD_END_DATE'=> '结束日期',
    'LBL_LV_TYPE'=> '预测类型',
    'LBL_LV_COMMIT_DATE'=> '提交的日期',
    'LBL_LV_OPPORTUNITIES'=> '商业机会',
    'LBL_LV_WEIGH'=> '加权金额',
    'LBL_LV_COMMIT'=> '已提交的金额',

    'LBL_COMMIT_NOTE'=> '为选择的时段提交输入的金额：',

    'LBL_COMMIT_MESSAGE'=> '您要提交这些金额吗？',
    'ERR_FORECAST_AMOUNT' => '必须提交数字金额。',

    // js error strings
    'LBL_FC_START_DATE' => '开始日期：',
    'LBL_FC_USER' => '安排为',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'没有可用的预测时段。',
    'LBL_FDR_ADJ_AMOUNT'=>'调整后的金额',
    'LBL_SAVE_WOKSHEET'=>'保存工作表',
    'LBL_RESET_WOKSHEET'=>'重设工作表',
    'LBL_SHOW_CHART'=>'查看图表',
    'LBL_RESET_CHECK'=>'所有选择时段中的工作表数据和登录的用户将被删除，继续吗？',

    'LBL_CURRENCY' => '货币:',
    'LBL_CURRENCY_ID' => '货币 ID',
    'LBL_CURRENCY_RATE' => '汇率',
    'LBL_BASE_RATE' => '基本利率',

    'LBL_QUOTA' => '定额',
    'LBL_QUOTA_ADJUSTED' => '定额（调整后的）',

    'LBL_FORECAST' => '销售预测',
    'LBL_COMMIT_STAGE' => '提交阶段',
    'LBL_SALES_STAGE' => '阶段',
    'LBL_AMOUNT' => '金额',
    'LBL_PERCENT' => '百分比',
    'LBL_DATE_CLOSED' => '预期结束日期',
    'LBL_PRODUCT_ID' => '产品编号：',
    'LBL_QUOTA_ID' => '定额编号',
    'LBL_VERSION' => '版本',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{{full_name}} (me)&#x200E;',

    'LBL_EDITABLE_INVALID' => '{{field_name}} 无效值',
    'LBL_EDITABLE_INVALID_RANGE' => '值必须在 {{min}} 和 {{max}} 之间',
    'LBL_HISTORY_LOG' => '上一次提交',
    'LBL_NO_COMMIT' => '无上次提交记录',

    'LBL_MANGER_SAVED' => '保存的管理器'

);
