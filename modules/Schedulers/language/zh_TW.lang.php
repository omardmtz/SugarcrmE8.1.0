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
'LBL_OOTB_WORKFLOW'		=> '處理工作流程工作',
'LBL_OOTB_REPORTS'		=> '執行報表產生排程工作',
'LBL_OOTB_IE'			=> '檢查輸入信箱',
'LBL_OOTB_BOUNCE'		=> '夜間執行過流程已退回推廣活動電子郵件',
'LBL_OOTB_CAMPAIGN'		=> '夜間執行大量電子郵件推廣活動',
'LBL_OOTB_PRUNE'		=> '在月份第 1 天剪除資料庫',
'LBL_OOTB_TRACKER'		=> '剪除追蹤器表格',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> '剪除舊記錄清單',
'LBL_OOTB_REMOVE_TMP_FILES' => '移除暫存檔案',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => '移除診斷工具檔案',
'LBL_OOTB_REMOVE_PDF_FILES' => '移除暫存 PDF 檔案',
'LBL_UPDATE_TRACKER_SESSIONS' => '更新 tracker_sessions 表格',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => '執行電子郵件提醒通知',
'LBL_OOTB_CLEANUP_QUEUE' => '清理工作佇列',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => '建立未來時間週期',
'LBL_OOTB_HEARTBEAT' => 'Sugar 活動訊號',
'LBL_OOTB_KBCONTENT_UPDATE' => '更新 KBContent 文章。',
'LBL_OOTB_KBSCONTENT_EXPIRE' => '發佈經核准的文章和「過期 KB 文章」。',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => '重建非規範化團隊安全性數據',

// List Labels
'LBL_LIST_JOB_INTERVAL' => '間隔：',
'LBL_LIST_LIST_ORDER' => '排程器：',
'LBL_LIST_NAME' => '排程器：',
'LBL_LIST_RANGE' => '範圍：',
'LBL_LIST_REMOVE' => '移除：',
'LBL_LIST_STATUS' => '狀態：',
'LBL_LIST_TITLE' => '排程清單：',
'LBL_LIST_EXECUTE_TIME' => '將在以下時間執行：',
// human readable:
'LBL_SUN'		=> '星期日',
'LBL_MON'		=> '週一',
'LBL_TUE'		=> '星期二',
'LBL_WED'		=> '星期三',
'LBL_THU'		=> '星期四',
'LBL_FRI'		=> '星期五',
'LBL_SAT'		=> '週六',
'LBL_ALL'		=> '每天',
'LBL_EVERY_DAY'	=> '每天',
'LBL_AT_THE'	=> '在',
'LBL_EVERY'		=> '每',
'LBL_FROM'		=> '發件者',
'LBL_ON_THE'	=> '在',
'LBL_RANGE'		=> '至',
'LBL_AT' 		=> ' 在',
'LBL_IN'		=> ' 於',
'LBL_AND'		=> ' 和',
'LBL_MINUTES'	=> ' 分鐘',
'LBL_HOUR'		=> ' 小時',
'LBL_HOUR_SING'	=> ' 小時',
'LBL_MONTH'		=> ' 月',
'LBL_OFTEN'		=> ' 盡可能頻繁。',
'LBL_MIN_MARK'	=> ' 分標',


// crontabs
'LBL_MINS' => '分鐘',
'LBL_HOURS' => '小時',
'LBL_DAY_OF_MONTH' => '日期',
'LBL_MONTHS' => '月',
'LBL_DAY_OF_WEEK' => '天',
'LBL_CRONTAB_EXAMPLES' => '上述使用標準 Crontab 標記法。',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron 規格依據伺服器時區 (',
'LBL_CRONTAB_SERVER_TIME_POST' => ') 執行。請相應指定排程器執行時間。',
// Labels
'LBL_ALWAYS' => '若遺失',
'LBL_CATCH_UP' => '則始終執行',
'LBL_CATCH_UP_WARNING' => '如果此工作執行時間較長，則取消核取。',
'LBL_DATE_TIME_END' => '結束日期和時間',
'LBL_DATE_TIME_START' => '開始日期和時間',
'LBL_INTERVAL' => '時間間隔',
'LBL_JOB' => '工作',
'LBL_JOB_URL' => '工作 URL',
'LBL_LAST_RUN' => '上次成功執行',
'LBL_MODULE_NAME' => 'Sugar 排程器',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar 排程器',
'LBL_MODULE_TITLE' => '排程器',
'LBL_NAME' => '工作名稱',
'LBL_NEVER' => '從不',
'LBL_NEW_FORM_TITLE' => '新排程',
'LBL_PERENNIAL' => '永久',
'LBL_SEARCH_FORM_TITLE' => '排程器搜尋',
'LBL_SCHEDULER' => '排程器：',
'LBL_STATUS' => '狀態',
'LBL_TIME_FROM' => '使用中起始於',
'LBL_TIME_TO' => '使用中結束於',
'LBL_WARN_CURL_TITLE' => 'cURL 警告：',
'LBL_WARN_CURL' => '警告：',
'LBL_WARN_NO_CURL' => '此系統不包含已啟用/編譯至 PHP 模組 (--curl=/path/to/curl_library) 的 cURL 程式庫。請與您的管理員取得連絡，以解決此問題。沒有 cURL 功能，「排程器」無法執行其工作。',
'LBL_BASIC_OPTIONS' => '基礎設定',
'LBL_ADV_OPTIONS'		=> '進階選項',
'LBL_TOGGLE_ADV' => '顯示進階選項',
'LBL_TOGGLE_BASIC' => '顯示基礎選項',
// Links
'LNK_LIST_SCHEDULER' => '排程器',
'LNK_NEW_SCHEDULER' => '建立排程器',
'LNK_LIST_SCHEDULED' => '已排程工作',
// Messages
'SOCK_GREETING' => "\n這是「SugarCRM 排程器服務」的介面。\n[ 可用精靈指令：啟動|重新啟動|關閉|狀態 ]\n如需退出，則輸入「退出」。如需關閉服務，則輸入「關閉」。\n",
'ERR_DELETE_RECORD' => '您必須指定記錄編號才能刪除排程。',
'ERR_CRON_SYNTAX' => '無效 Cron 語法',
'NTC_DELETE_CONFIRMATION' => '確定要刪除此記錄嗎？',
'NTC_STATUS' => '將狀態設為「非使用」中，從「排程器」下拉式清單中移除此排程',
'NTC_LIST_ORDER' => '設定此排程在「排程器」下拉式清單中的顯示順序',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => '如需設定 Windows 排程器',
'LBL_CRON_INSTRUCTIONS_LINUX' => '如需設定 Crontab',
'LBL_CRON_LINUX_DESC' => '注意：為執行「Sugar 排程器」，請新增以下行至 Crontab 檔案：',
'LBL_CRON_WINDOWS_DESC' => '注意：為執行 Sugar 排程器，使用「Windows 已排程工作」來建立要執行的批次檔。批次檔應包含以下指令：',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> '工作記錄',
'LBL_EXECUTE_TIME'			=> '執行時間',

//jobstrings
'LBL_REFRESHJOBS' => '重新整理工作',
'LBL_POLLMONITOREDINBOXES' => '檢查輸入郵件帳戶',
'LBL_PERFORMFULLFTSINDEX' => '全文檢索搜尋索引系統',
'LBL_SUGARJOBREMOVEPDFFILES' => '移除暫存 PDF 檔案',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => '發佈經核准的文章和「過期 KB 文章」。',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch 佇列排程器',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => '移除診斷工具檔案',
'LBL_SUGARJOBREMOVETMPFILES' => '移除暫存檔案',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => '重建非規範化團隊安全性數據',

'LBL_RUNMASSEMAILCAMPAIGN' => '夜間執行大量電子郵件推廣活動',
'LBL_ASYNCMASSUPDATE' => '執行非同步大量更新',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '夜間執行過流程已退回推廣活動電子郵件',
'LBL_PRUNEDATABASE' => '在月份第 1 天剪除資料庫',
'LBL_TRIMTRACKER' => '剪除追蹤器表格',
'LBL_PROCESSWORKFLOW' => '處理工作流程工作',
'LBL_PROCESSQUEUE' => '執行報表產生排程工作',
'LBL_UPDATETRACKERSESSIONS' => '更新追蹤器工作階段表格',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => '建立未來時間週期',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar 活動訊號',
'LBL_SENDEMAILREMINDERS'=> '執行電子郵件提醒傳送',
'LBL_CLEANJOBQUEUE' => '清理工作佇列',
'LBL_CLEANOLDRECORDLISTS' => '清理舊記錄清單',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

