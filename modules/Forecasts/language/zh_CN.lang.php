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
    'LBL_FORECASTS_DASHBOARD' => '预测仪表板',

    //module strings.
    'LBL_MODULE_NAME' => '预测',
    'LBL_MODULE_NAME_SINGULAR' => '销售预测',
    'LNK_NEW_OPPORTUNITY' => '创建商业机会',
    'LBL_MODULE_TITLE' => '预测',
    'LBL_LIST_FORM_TITLE' => '提交的销售预测',
    'LNK_UPD_FORECAST' => '预测工作表',
    'LNK_QUOTA' => '查看定额',
    'LNK_FORECAST_LIST' => '查看预测历史记录',
    'LBL_FORECAST_HISTORY' => '预测：历史记录',
    'LBL_FORECAST_HISTORY_TITLE' => '历史',

    //var defs
    'LBL_TIMEPERIOD_NAME' => '时段',
    'LBL_USER_NAME' => '用户名',
    'LBL_REPORTS_TO_USER_NAME' => '汇报对象',

    //forecast table
    'LBL_FORECAST_ID' => '预测 ID',
    'LBL_FORECAST_TIME_ID' => '时段 ID',
    'LBL_FORECAST_TYPE' => '预测类型',
    'LBL_FORECAST_OPP_COUNT' => '商业机会',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => '商业机会管道计数',
    'LBL_FORECAST_OPP_WEIGH'=> '加权金额',
    'LBL_FORECAST_OPP_COMMIT' => '可能情形',
    'LBL_FORECAST_OPP_BEST_CASE'=>'最好情形',
    'LBL_FORECAST_OPP_WORST'=>'最坏情形',
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
    'LBL_QC_HEADER_DELIM'=> '至',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "商业机会",
    'LBL_OW_ACCOUNTNAME' => "帐户",
    'LBL_OW_REVENUE' => "金额",
    'LBL_OW_WEIGHTED' => "加权金额",
    'LBL_OW_MODULE_TITLE'=> '商业机会工作表',
    'LBL_OW_PROBABILITY'=>'成交概率',
    'LBL_OW_NEXT_STEP'=>'下一步',
    'LBL_OW_DESCRIPTION'=>'说明',
    'LBL_OW_TYPE'=>'类型',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'直接报告',
    'LBL_FDR_OPPORTUNITIES'=>'预测中的商业机会：',
    'LBL_FDR_WEIGH'=>'加权商业机会金额：',
    'LBL_FDR_COMMIT'=>'已提交的金额',
    'LBL_FDR_DATE_COMMIT'=>'承诺日期',

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
    'LBL_LV_TIMPERIOD_START_DATE'=> '开始日期',
    'LBL_LV_TIMPERIOD_END_DATE'=> '结束日期',
    'LBL_LV_TYPE'=> '预测类型',
    'LBL_LV_COMMIT_DATE'=> '提交的日期',
    'LBL_LV_OPPORTUNITIES'=> '商业机会',
    'LBL_LV_WEIGH'=> '加权金额',
    'LBL_LV_COMMIT'=> '已提交的金额',

    'LBL_COMMIT_NOTE' => '为选择的时段提交输入的金额：',
    'LBL_COMMIT_TOOLTIP' => '确认承诺金额：在工作表中更改值',
    'LBL_COMMIT_MESSAGE' => '您要提交这些金额吗？',
    'ERR_FORECAST_AMOUNT' => '必须提交数字金额。',

    // js error strings
    'LBL_FC_START_DATE' => '开始日期',
    'LBL_FC_USER' => '安排为',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'没有可用的预测时段。',
    'LBL_FDR_ADJ_AMOUNT'=>'调整后的金额',
    'LBL_SAVE_WOKSHEET'=>'保存工作表',
    'LBL_RESET_WOKSHEET'=>'重设工作表',
    'LBL_SHOW_CHART'=>'查看图表',
    'LBL_RESET_CHECK'=>'所有选择时段中的工作表数据和登录的用户将被删除，继续吗？',

    'LB_FS_LIKELY_CASE'=>'可能情形',
    'LB_FS_WORST_CASE'=>'最坏情形',
    'LB_FS_BEST_CASE'=>'最好情形',
    'LBL_FDR_WK_LIKELY_CASE'=>'估计可能情形',
    'LBL_FDR_WK_BEST_CASE'=> '估计最好情形',
    'LBL_FDR_WK_WORST_CASE'=>'估计最坏情形',
    'LBL_FDR_C_BEST_CASE'=>'最好情形',
    'LBL_FDR_C_WORST_CASE'=>'最坏情形',
    'LBL_FDR_C_LIKELY_CASE'=>'可能情形',
    'LBL_QC_LAST_BEST_CASE'=>'上次承诺金额（最好情形）：',
    'LBL_QC_LAST_LIKELY_CASE'=>'上次承诺金额（可能情形）：',
    'LBL_QC_LAST_WORST_CASE'=>'上次承诺金额（最坏情形）：',
    'LBL_QC_ROLL_BEST_VALUE'=>'汇总承诺金额（最好情形）：',
    'LBL_QC_ROLL_LIKELY_VALUE'=>'汇总承诺金额（可能情形）：',
    'LBL_QC_ROLL_WORST_VALUE'=>'汇总承诺金额（最坏情形）：',
    'LBL_QC_COMMIT_BEST_CASE'=>'承诺金额（最好情形）：',
    'LBL_QC_COMMIT_LIKELY_CASE'=>'承诺金额（可能情形）：',
    'LBL_QC_COMMIT_WORST_CASE'=>'承诺金额（最坏情形）：',
    'LBL_CURRENCY' => '货币:',
    'LBL_CURRENCY_ID' => '货币 ID',
    'LBL_CURRENCY_RATE' => '汇率',
    'LBL_BASE_RATE' => '基本利率',

    'LBL_QUOTA' => '定额',
    'LBL_QUOTA_ADJUSTED' => '定额（调整后的）',

    'LBL_FORECAST_FOR'=>'预测工作表为：',
    'LBL_FMT_ROLLUP_FORECAST'=>'（汇总）',
    'LBL_FMT_DIRECT_FORECAST'=>'（直属）',

    //labels used by the chart.
    'LBL_GRAPH_TITLE'=>'销售预测历史',
    'LBL_GRAPH_QUOTA_ALTTEXT'=>'定额为 %s',
    'LBL_GRAPH_COMMIT_ALTTEXT'=>'承诺金额为 %s',
    'LBL_GRAPH_OPPS_ALTTEXT'=>'完成商业机会的价值是 %s',

    'LBL_GRAPH_QUOTA_LEGEND'=>'定额',
    'LBL_GRAPH_COMMIT_LEGEND'=>'已提交的预测',
    'LBL_GRAPH_OPPS_LEGEND'=>'完成的商业机会',
    'LBL_TP_QUOTA'=>'定额:',
    'LBL_CHART_FOOTER'=>'预测历史<br>定额 vs. 预测金额 vs. 完成商业机会价值',
    'LBL_TOTAL_VALUE'=>'总计：',
    'LBL_COPY_AMOUNT'=>'总金额',
    'LBL_COPY_WEIGH_AMOUNT'=>'总加权金额',
    'LBL_WORKSHEET_AMOUNT'=>'总估算金额',
    'LBL_COPY'=>'复制价值',
    'LBL_COMMIT_AMOUNT'=>'承诺价值总和。',
    'LBL_CUMULATIVE_TOTAL'=>'累计总数',
    'LBL_COPY_FROM'=>'复制价值从：',

    'LBL_CHART_TITLE'=>'定额 vs. 承诺 vs. 实际',

    'LBL_FORECAST' => '销售预测',
    'LBL_COMMIT_STAGE' => '提交阶段',
    'LBL_SALES_STAGE' => '销售阶段：',
    'LBL_AMOUNT' => '数量',
    'LBL_PERCENT' => '完成百分比',
    'LBL_DATE_CLOSED' => '预期完成日期：',
    'LBL_PRODUCT_ID' => '产品编号：',
    'LBL_QUOTA_ID' => '定额编号',
    'LBL_VERSION' => '版本',
    'LBL_CHART_BAR_LEGEND_CLOSE' => '隐藏条形图图例',
    'LBL_CHART_BAR_LEGEND_OPEN' => '显示条形图图例',
    'LBL_CHART_LINE_LEGEND_CLOSE' => '隐藏线形图图例',
    'LBL_CHART_LINE_LEGEND_OPEN' => '显示线形图图例',

    //Labels for forecasting history log and endpoint
    'LBL_ERROR_NOT_MANAGER' => 'Error: user {0} does not have manager access to request forecasts for {1}错误：用户{0}没有管理员权限请求 {1} 的销售预测',
    'LBL_UP' => '向上',
    'LBL_DOWN' => '向下',
    'LBL_PREVIOUS_COMMIT' => '最新提交：',

    'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => '设置预测',
    'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => '更新预测',
    'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
    'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
    'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}, 和{{{third}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => '可能 {{{direction}}} {{{from}}} 到 {{{to}}}',
    'LBL_COMMITTED_HISTORY_BEST_CHANGED' => '最佳的{{{direction}}} {{{from}}} 到 {{{to}}}',
    'LBL_COMMITTED_HISTORY_WORST_CHANGED' => '最坏的{{{direction}}} {{{from}}} 到 {{{to}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_SAME' => '可能保持不变',
    'LBL_COMMITTED_HISTORY_BEST_SAME' => '最好保持不变',
    'LBL_COMMITTED_HISTORY_WORST_SAME' => '最差保持不变',


    'LBL_COMMITTED_THIS_MONTH' => '本月在 {0}',
    'LBL_COMMITTED_MONTHS_AGO' => '{0} 月前 {1}',

    //Labels for jsTree implementation
    'LBL_TREE_PARENT' => '父类',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{0} (me)&#x200E;',

    //Labels for worksheet items
    'LBL_EXPECTED_OPPORTUNITIES' => '预期的商业机会',
    'LBL_DISPLAYED_TOTAL' => '显示全部',
    'LBL_TOTAL' => '总计',
    'LBL_OVERALL_TOTAL' => '总和',
    'LBL_EDITABLE_INVALID' => ' {0} 无效值',
    'LBL_EDITABLE_INVALID_RANGE' => '值必须在 {0} 和 {1} 之间',
    'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => '在您的工作表中有未保存的更改。',
    'LBL_WORKSHEET_EXPORT_CONFIRM' => '仅导出保存或提交的数据。单击取消中止，确认导出保存的数据。',
    'LBL_WORKSHEET_ID' => '工作表 ID',

    // Labels for Chart Options
    'LBL_DATA_SET' => '数据设置：',
    'LBL_GROUP_BY' => '通过分组：',
    'LBL_CHART_OPTIONS' => '图表选项',
    'LBL_CHART_AMOUNT' => '数量',
    'LBL_CHART_TYPE' => '图表类型',

    // Labels for Data Filters
    'LBL_FILTERS' => '过滤',

    // Labels for toggle buttons
    'LBL_MORE' => '更多',
    'LBL_LESS' => '少于',

    // Labels for Progress
    'LBL_PROJECTED' => '预计',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => '可能高于定额',
    'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => '可能低于定额',
    'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => '最好高于定额',
    'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => '最好低于定额',
    'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => '最差高于定额',
    'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => '最差低于定额',
    'LBL_CLOSED' => '谈成结束',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => '可能高于关闭',
    'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => '可能低于关闭',
    'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => '最好高于关闭',
    'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => '最好低于关闭',
    'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => '最差高于关闭',
    'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => '最差低于关闭',
    'LBL_REVENUE' => '收入',
    'LBL_PIPELINE_REVENUE' => '管道收入',
    'LBL_PIPELINE_OPPORTUNITIES' => '管道机会',
    'LBL_LOADING' => '加载中 ...',
    'LBL_IN_FORECAST' => '预测中',

    // Actions Dropdown
    'LBL_ACTIONS' => '操作',
    'LBL_EXPORT_CSV' => '导出 CSV',
    'LBL_CANCEL' => '取消',

    'LBL_CHART_FORECAST_FOR' => '预测 {0}',
    'LBL_FORECAST_TITLE' => '预测：{0}',
    'LBL_CHART_INCLUDED' => '包括',
    'LBL_CHART_NOT_INCLUDED' => '不包括',
    'LBL_CHART_ADJUSTED' => '（调整后的）',
    'LBL_SAVE_DRAFT' => '保存草稿',
    'LBL_CHANGES_BY' => '按 {0}改变',
    'LBL_FORECAST_SETTINGS' => '设置',

    // config panels strings
    'LBL_FORECASTS_CONFIG_TITLE' => '预测设置',

    'LBL_FORECASTS_MISSING_STAGE_TITLE' => '预测配置错误：',
    'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => '预测模块配置不正确，并不再可用。赢得销售阶段和销售阶段失败是从可用的销售阶段值缺失的。请与您的系统管理员联系。',
    'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => '预测模块访问错误：',
    'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => '您无法访问预测模块，请联系您的管理员。',

    'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG' => '您无权限访问预测模块记录。请联系您的管理员。',

    // Panel and BreadCrumb Labels
    'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => '工作表布局',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => '范围',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => '方案',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => '时间周期',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => '变量',

    // Admin UI
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => '预测设定',
    'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => '时段',
    'LBL_FORECASTS_CONFIG_TITLE_RANGES' => '预测范围',
    'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => '方案',
    'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => '工作表列',
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => '查看预测工作表',

    'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => '预测人',

    'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => '财政年度开始日期：',

    'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => '配置将在预测模块在使用的时间段。<br><br>请注意，初始设置后无法更改时间段设置。<br><br>首先，通过选择会计年度的开始日期。然后选择要预测时间段的类型。日期范围会根据您的选择自动计算时间期间。子时间周期是预测工作表的基础。<br><br>在可见的未来和过去的时间段将决定可见分时段预测模块的数量。用户能够查看和编辑在可见子期间的预测数字。',
    'LBL_FORECASTS_CONFIG_HELP_RANGES' => '配置您希望对 {{forecastByModule}} 进行分类的方式。<br><br>请注意，在第一次提交之后, “范围”设置将不能再更改。对于升级后的实例，“范围”设置与现有“预测”数据一起锁定。<br><br>您可以根据概率范围选择两个或多个类别，或者创建不基于概率的类别。<br><br>在自定义类别的左边，有一些复选框；使用这些复选框来确定将在提交和报告给经理的“预测”金额中包括哪些范围。<br><br>用户可以从工作表中手动更改包含/排除状态和 {{forecastByModule}} 类别。',
    'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => '选择您想让用户填写的每个 {{forecastByModuleSingular}} 预测列。请注意可能的金额会绑定{{forecastByModule}}中显示的金额，原因是不能隐藏这个可能列。',
    'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => '选择您想在预测模块中查看哪些列。将结合工作表字段列表，并允许用户选择如何配置其视图。',
    'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => '我是预测帮助文本的一个占位符',
    'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => '已保存预测配置设置。',

    // timeperiod config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => '设置初始后不能更改时间段设置。',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => '配置用于预测模块的时间段。',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => '选择您的组织用于会计的年度类型。',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD' => '选择时间段类型',
    'LBL_FORECASTS_CONFIG_LEAFPERIOD' => '选择您想要查看您的时间周期的子周期：',
    'LBL_FORECASTS_CONFIG_START_DATE' => '选择财政年度开始日期',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => '选择要在工作表查看未来时间段数目。<br><i>这个数值用于选定的基本时间段。例如，每年时间段选择 2 将显示 8 代表未来几个季度</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => '选择在工作表中要查看过去的时间段数量。<br><i>该数量适用于所选的基本时间段。例如，选择季度时间段 2 将显示过去 6 个月</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => '已选开始日期显示财年可能跨了两年。请选择使用哪一年作为财政年：',
    'LBL_FISCAL_YEAR' => '财政年度',

    // worksheet layout config
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => '选择如何填充预测工作表：',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => '商业机会',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => '营收单项',
    'LBL_REVENUELINEITEM_NAME' => '营收单项名称',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => '工作表将填充：',

    // ranges config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => '在预测模块中首次保存草稿或提交后无法更改范围设置。然而为了升级实例，范围设置不能在初始设置后更改，因预测数据已通过升级解决。',
    'LBL_FORECASTS_CONFIG_RANGES' => '预测范围选项：',
    'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => '选择您要对 {{forecastByModule}} 进行分类的方式。',
    'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => '此选项使用户可指定 {{forecastByModule}} 以包含或排除预测。',
    'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => '此选项使用户能够对其 {{forecastByModule}} 进行分类，不包括在提交但若一切顺利情况下完全颠倒和有潜力的关闭，将从预测中排除 {{forecastByModule}}。',
    'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => '自定义范围：该选项使用户能够对其 {{forecastByModule}} 进行分类以提交到承诺的范围、排除的范围和您所安装的任何其他预测。',
    'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => '排除范围是默认情况下从  0% 到先前预测范围的最小值。',

    'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => '输入范围名称',

    // scenarios config
    //TODO-sfa refactors the code references for scenarios to be scenarios (SFA-337).
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => '选择要包含在预测工作表上的方案。',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => '可能基于 {{forecastByModule}} 模块中输入的金额。',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => '可能',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => '最佳',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => '最差',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => '在总数中显示预计的方案',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => '显示可能出现的案例总数',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => '显示最佳案例总数',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => '显示最差案例总数',

    // variables config
    'LBL_FORECASTS_CONFIG_VARIABLES' => '变量',
    'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => '指标表的公式依赖于{{forecastByModule}} 销售阶段，需要从pipleline 排除，也就是说，关闭和丢失的{{forecastByModule}} 。',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => '请选择代表关闭或丢失 {{forecastByModule}} 的销售阶段：',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => '请选择代表关闭和赢得了 {{forecastByModule}} 的销售阶段：',
    'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => '因此管道公式将:',

    'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => '成功：',
    'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => '您成功地设置了您的预测模块。加载模块请稍候。',
    'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => '已保存预测配置设置。重新加载模块请稍候。',
    // Labels for Success Messages:
    'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => '为所选的时间段，您保存了预测工作表作为草稿。',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => '您已提交了预测',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => '您提交了您的预测到{{manager}}',

    // custom ranges
    'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => '自定义范围',
    'LBL_UNAUTH_FORECASTS' => '未经授权访问预测设置。',
    'LBL_FORECASTS_RANGES_BASED_TITLE' => '基于概率的范围',
    'LBL_FORECASTS_CUSTOM_BASED_TITLE' => '基于概率的自定义范围',
    'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' =>'范围不是基于概率',

    // worksheet columns config
    'LBL_DISCOUNT' => '折扣:',
    'LBL_OPPORTUNITY_STATUS' => '商业机会状态',
    'LBL_OPPORTUNITY_NAME' => '商业机会名称',
    'LBL_PRODUCT_TEMPLATE' => '产品目录',
    'LBL_CAMPAIGN' => '市场活动',
    'LBL_TEAMS' => '团队',
    'LBL_CATEGORY' => '类别：',
    'LBL_COST_PRICE' => '成本价格',
    'LBL_TOTAL_DISCOUNT_AMOUNT' => '折扣总额',
    'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => '选择哪些列应显示为工作表视图。默认情况下，将选择以下字段：',

    // forecast details dashlet
    'LBL_DASHLET_FORECAST_NOT_SETUP' => '尚未配置预测，需要设置才能使用这个小部件。请与您的系统管理员联系。',
    'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => '尚未配置预测，需要设置才能使用这个工具。',
    'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => '请单击此处配置预测模块。',
    'LBL_DASHLET_MY_PIPELINE' => '我的管道',
    'LBL_DASHLET_MY_TEAMS_PIPELINE' => "我团队的管道",
    'LBL_DASHLET_PIPELINE_CHART_NAME' => '预测管道图表',
    'LBL_DASHLET_PIPELINE_CHART_DESC' => '显示当前管道图表。',
    'LBL_FORECAST_DETAILS_DEFICIT' => '赤字',
    'LBL_FORECAST_DETAILS_SURPLUS' => '盈余',
    'LBL_FORECAST_DETAILS_SHORT' => '缺少于',
    'LBL_FORECAST_DETAILS_EXCEED' => '超过于',
    'LBL_FORECAST_DETAILS_NO_DATA' => '无数据',
    'LBL_FORECAST_DETAILS_MEETING_QUOTA' => '会议配额',

    'LBL_ASSIGN_QUOTA_BUTTON' => '分配定额',
    'LBL_ASSIGNING_QUOTA' => '正分配定额',
    'LBL_QUOTA_ASSIGNED' => '已成功分配定额。',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => '预测访问错误',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => '您没有访问配置预测。请联系您的管理员。',
    'WARNING_DELETED_RECORD_RECOMMIT_1' => '这个记录包含在 ',
    'WARNING_DELETED_RECORD_RECOMMIT_2' => '它将被删除，您需要重新提交您的 ',

    'LBL_DASHLET_MY_FORECAST' => '我的预测',
    'LBL_DASHLET_MY_TEAMS_FORECAST' => "我团队的预测",

    'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => '您未保存更改。您确定您想对工作表进行排序和放弃更改吗？',

    // Forecasts Records View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模块内含{{opportunities_singular_module}}记录，用于建立{{forecastworksheets_module}} 和预测销售。用户可以从个人、团队、销售组织三个层面争取销售业绩{{quotas_module}} 。在用户开始访问{{plural_module_name}}模块之前，一位管理员必须选择本组织所需的时间段、范围、方案等。

销售代表使用{{plural_module_name}}模块来处理其分配{{forecastby_module}} 随着当前时间段进行。这些用户将按照他们希望关闭的 {{forecastby_module}} 来提交其个人销售预测总数。销售经理与其他销售代表一样处理他们负责的{{forecastby_singular_module}} 记录。此外，经理们也要整合他们下属所承诺的总额以便预测团队于各个时间段的配额。另外，可扩展的业务信息框也可以展示多种业务分析，包括从个人工作表分析和分析经理团队工作表等提供更多见解。'
);
