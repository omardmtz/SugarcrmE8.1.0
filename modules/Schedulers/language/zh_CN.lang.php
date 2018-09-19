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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> '处理工作流任务',
'LBL_OOTB_REPORTS'		=> '运行报表生成计划任务',
'LBL_OOTB_IE'			=> '检查收件箱',
'LBL_OOTB_BOUNCE'		=> '运行每晚处理退回的市场活动邮件',
'LBL_OOTB_CAMPAIGN'		=> '运行每晚批量运行邮件市场活动',
'LBL_OOTB_PRUNE'		=> '每月 1 号精简数据库',
'LBL_OOTB_TRACKER'		=> '清理跟踪器表',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> '删减旧的记录列表',
'LBL_OOTB_REMOVE_TMP_FILES' => '移除临时文件',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => '移除诊断工具文件',
'LBL_OOTB_REMOVE_PDF_FILES' => '移除临时 PDF 文件',
'LBL_UPDATE_TRACKER_SESSIONS' => '更新 tracker_sessions 表',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => '运行电子邮件提醒通知',
'LBL_OOTB_CLEANUP_QUEUE' => '清理任务队列',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => '创建未来时段',
'LBL_OOTB_HEARTBEAT' => 'Sugar 中心',
'LBL_OOTB_KBCONTENT_UPDATE' => '更新 KBContent 文章。',
'LBL_OOTB_KBSCONTENT_EXPIRE' => '发布已核准的文章和过期的知识库文章。',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => '重建非规范化团队安全性数据',

// List Labels
'LBL_LIST_JOB_INTERVAL' => '间隔：',
'LBL_LIST_LIST_ORDER' => '计划任务：',
'LBL_LIST_NAME' => '计划任务：',
'LBL_LIST_RANGE' => '范围：',
'LBL_LIST_REMOVE' => '移除：',
'LBL_LIST_STATUS' => '状态：',
'LBL_LIST_TITLE' => '计划任务列表：',
'LBL_LIST_EXECUTE_TIME' => '执行时间：',
// human readable:
'LBL_SUN'		=> '星期日',
'LBL_MON'		=> '星期一',
'LBL_TUE'		=> '星期二',
'LBL_WED'		=> '星期三',
'LBL_THU'		=> '星期四',
'LBL_FRI'		=> '星期五',
'LBL_SAT'		=> '星期六',
'LBL_ALL'		=> '每天',
'LBL_EVERY_DAY'	=> '每天',
'LBL_AT_THE'	=> '在',
'LBL_EVERY'		=> '每个',
'LBL_FROM'		=> '从',
'LBL_ON_THE'	=> '于',
'LBL_RANGE'		=> '到',
'LBL_AT' 		=> '在',
'LBL_IN'		=> '在',
'LBL_AND'		=> '和',
'LBL_MINUTES'	=> '分钟',
'LBL_HOUR'		=> '小时',
'LBL_HOUR_SING'	=> '小时',
'LBL_MONTH'		=> '月',
'LBL_OFTEN'		=> '尽可能频繁。',
'LBL_MIN_MARK'	=> '分钟标示',


// crontabs
'LBL_MINS' => '分钟',
'LBL_HOURS' => '小时',
'LBL_DAY_OF_MONTH' => '日期',
'LBL_MONTHS' => '月',
'LBL_DAY_OF_WEEK' => '天',
'LBL_CRONTAB_EXAMPLES' => '使用上述标准 crontab 符号。',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  '计时程序规格基于服务器所在的时区运行 (',
'LBL_CRONTAB_SERVER_TIME_POST' => ')。 请相应地确定计划任务的执行时间。',
// Labels
'LBL_ALWAYS' => '始终',
'LBL_CATCH_UP' => '错过时执行',
'LBL_CATCH_UP_WARNING' => '如果此任务的执行时间较长，请取消选择。',
'LBL_DATE_TIME_END' => '结束日期和时间',
'LBL_DATE_TIME_START' => '开始日期与时间',
'LBL_INTERVAL' => '间隔',
'LBL_JOB' => '任务',
'LBL_JOB_URL' => '任务 URL',
'LBL_LAST_RUN' => '上一次成功运行',
'LBL_MODULE_NAME' => 'Sugar 计划任务',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar 计划任务',
'LBL_MODULE_TITLE' => '计划任务',
'LBL_NAME' => '任务名称',
'LBL_NEVER' => '从不',
'LBL_NEW_FORM_TITLE' => '新增计划任务',
'LBL_PERENNIAL' => '永久',
'LBL_SEARCH_FORM_TITLE' => '搜索计划任务',
'LBL_SCHEDULER' => '计划任务:',
'LBL_STATUS' => '状态',
'LBL_TIME_FROM' => '启用从',
'LBL_TIME_TO' => '启用到',
'LBL_WARN_CURL_TITLE' => 'CURL 警告：',
'LBL_WARN_CURL' => '警告：',
'LBL_WARN_NO_CURL' => '本系统未启用 cURL 库/编译成 PHP 模块 (--with-curl=/path/to/curl_library)。请联系管理员解决这个问题。否则计划任务将不能正常运行。',
'LBL_BASIC_OPTIONS' => '基本设置',
'LBL_ADV_OPTIONS'		=> '高级选项',
'LBL_TOGGLE_ADV' => '显示高级选项',
'LBL_TOGGLE_BASIC' => '显示基本选项',
// Links
'LNK_LIST_SCHEDULER' => '计划任务',
'LNK_NEW_SCHEDULER' => '新建计划任务',
'LNK_LIST_SCHEDULED' => '已安排的任务',
// Messages
'SOCK_GREETING' => "\n这是 SugarCRM 计划任务服务的界面。\n[ 可用守护进程的命令：开始|重启|关闭|状态 ]\n如需退出，请输入‘quit’。如需关闭服务，请输入‘shutdown’。\n",
'ERR_DELETE_RECORD' => '必须指定记录编号才能删除计划任务。',
'ERR_CRON_SYNTAX' => '无效 Cron 语法',
'NTC_DELETE_CONFIRMATION' => '您确定要删除这条记录吗？',
'NTC_STATUS' => '设置状态为“禁用”，计划任务会从下拉列表中移除。',
'NTC_LIST_ORDER' => '设置此计划任务在计划任务下拉列表中的显示顺序',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => '设置 Windows 的计划任务',
'LBL_CRON_INSTRUCTIONS_LINUX' => '设置 Crontab',
'LBL_CRON_LINUX_DESC' => '注意：为运行 Sugar 计划任务，请在您的 crontab 文件中增加这一行：',
'LBL_CRON_WINDOWS_DESC' => '注意：为运行 Sugar 计划任务，请使用 Windows 已安排的任务来创建要运行的批处理文件。批处理文件应包含以下命令：',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> '任务日志',
'LBL_EXECUTE_TIME'			=> '执行时间',

//jobstrings
'LBL_REFRESHJOBS' => '刷新任务',
'LBL_POLLMONITOREDINBOXES' => '检查收件帐户',
'LBL_PERFORMFULLFTSINDEX' => '全文搜索索引系统',
'LBL_SUGARJOBREMOVEPDFFILES' => '移除临时 PDF 文件',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => '发布已核准的文章和过期的知识库文章。',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch 队列工作计划',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => '移除诊断工具文件',
'LBL_SUGARJOBREMOVETMPFILES' => '移除临时文件',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => '重建非规范化团队安全性数据',

'LBL_RUNMASSEMAILCAMPAIGN' => '运行每晚批量运行邮件市场活动',
'LBL_ASYNCMASSUPDATE' => '执行异步大规模更新',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '运行每晚处理退回的营销邮件',
'LBL_PRUNEDATABASE' => '每月 1 号精简数据库',
'LBL_TRIMTRACKER' => '清除访问记录',
'LBL_PROCESSWORKFLOW' => 'w执行工作流程',
'LBL_PROCESSQUEUE' => '执行队列',
'LBL_UPDATETRACKERSESSIONS' => '更新跟踪器会话表',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => '创建未来时段',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar 中心',
'LBL_SENDEMAILREMINDERS'=> '运行电子邮件提醒发送',
'LBL_CLEANJOBQUEUE' => '清理任务队列',
'LBL_CLEANOLDRECORDLISTS' => '清除旧记录列表',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

