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
'LBL_OOTB_WORKFLOW'		=> 'ワークフロータスクを実行',
'LBL_OOTB_REPORTS'		=> 'レポート生成タスクを実行',
'LBL_OOTB_IE'			=> 'インバウンドメール受信箱を確認',
'LBL_OOTB_BOUNCE'		=> 'バウンスしたキャンペーンメールの処理を夜間に実行',
'LBL_OOTB_CAMPAIGN'		=> 'キャンペーンEメールの送信を夜間に実行',
'LBL_OOTB_PRUNE'		=> '月初め（１日）に不要なデータベースのデータを削除',
'LBL_OOTB_TRACKER'		=> '月初め（１日）に不要なトラッカーテーブルを削除',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> '古いレコードリストを取り除く',
'LBL_OOTB_REMOVE_TMP_FILES' => '一時ファイルを削除',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => '診断ツールのファイルを削除',
'LBL_OOTB_REMOVE_PDF_FILES' => '一時PDFファイルを削除',
'LBL_UPDATE_TRACKER_SESSIONS' => 'tracker_sessions テーブルを更新',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Eメールリマインダ通知を実行',
'LBL_OOTB_CLEANUP_QUEUE' => 'ジョブキューの削除',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => '将来の期間を作成',
'LBL_OOTB_HEARTBEAT' => 'Sugarハートビート',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent の記事を更新します。',
'LBL_OOTB_KBSCONTENT_EXPIRE' => '承認された記事を公開し、KB 記事を期限切れにします。',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflowでスケジュール済みのジョブ',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => '非正規チームのセキュリティ データを再構築',

// List Labels
'LBL_LIST_JOB_INTERVAL' => '間隔:',
'LBL_LIST_LIST_ORDER' => 'スケジューラー:',
'LBL_LIST_NAME' => 'スケジューラー:',
'LBL_LIST_RANGE' => '期間:',
'LBL_LIST_REMOVE' => '削除:',
'LBL_LIST_STATUS' => 'ステータス:',
'LBL_LIST_TITLE' => 'スケジュール一覧',
'LBL_LIST_EXECUTE_TIME' => '開始予定時刻:',
// human readable:
'LBL_SUN'		=> '日曜日',
'LBL_MON'		=> '月曜日',
'LBL_TUE'		=> '火曜日',
'LBL_WED'		=> '水曜日',
'LBL_THU'		=> '木曜日',
'LBL_FRI'		=> '金曜日',
'LBL_SAT'		=> '土曜日',
'LBL_ALL'		=> '毎日',
'LBL_EVERY_DAY'	=> '毎日',
'LBL_AT_THE'	=> 'At the',
'LBL_EVERY'		=> 'ごと',
'LBL_FROM'		=> 'From ',
'LBL_ON_THE'	=> '毎正',
'LBL_RANGE'		=> '終了',
'LBL_AT' 		=> 'at',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'と',
'LBL_MINUTES'	=> ' 分間 ',
'LBL_HOUR'		=> '時',
'LBL_HOUR_SING'	=> '時',
'LBL_MONTH'		=> '月',
'LBL_OFTEN'		=> 'できるだけ頻繁に実行',
'LBL_MIN_MARK'	=> '分',


// crontabs
'LBL_MINS' => '分',
'LBL_HOURS' => '時',
'LBL_DAY_OF_MONTH' => '日',
'LBL_MONTHS' => '月',
'LBL_DAY_OF_WEEK' => '曜日',
'LBL_CRONTAB_EXAMPLES' => '上記は標準のcrontab表記を用いています。',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'crontabはサーバのタイムゾーン（',
'LBL_CRONTAB_SERVER_TIME_POST' => '）に応じて実行されます。スケジューラーの実行時間もそれに沿って設定してください。',
// Labels
'LBL_ALWAYS' => '常に',
'LBL_CATCH_UP' => '実行していなければ実行',
'LBL_CATCH_UP_WARNING' => 'このジョブの実行に時間がかかる場合はチェックをはずしてください。',
'LBL_DATE_TIME_END' => '終了日時',
'LBL_DATE_TIME_START' => '開始日時',
'LBL_INTERVAL' => '間隔',
'LBL_JOB' => 'ジョブ',
'LBL_JOB_URL' => 'ジョブURL',
'LBL_LAST_RUN' => '前回の実行',
'LBL_MODULE_NAME' => 'Sugarスケジューラー',
'LBL_MODULE_NAME_SINGULAR' => 'Sugarスケジューラー',
'LBL_MODULE_TITLE' => 'スケジューラー',
'LBL_NAME' => 'ジョブ名',
'LBL_NEVER' => 'なし',
'LBL_NEW_FORM_TITLE' => 'スケジューラー作成',
'LBL_PERENNIAL' => '無期限',
'LBL_SEARCH_FORM_TITLE' => 'スケジューラー検索',
'LBL_SCHEDULER' => 'スケジューラー:',
'LBL_STATUS' => 'ステータス',
'LBL_TIME_FROM' => '開始時間',
'LBL_TIME_TO' => '終了時間',
'LBL_WARN_CURL_TITLE' => 'cURL警告:',
'LBL_WARN_CURL' => '警告:',
'LBL_WARN_NO_CURL' => 'このシステムはcURLライブラリが有効になっていないか、PHPモジュールにコンパイルされていません(--with-curl=/path/to/curl_library)。この問題を解決するためにはシステム管理者に連絡してください。cURLライブラリがないとスケジューラーはジョブを実行できません。',
'LBL_BASIC_OPTIONS' => '基本オプション',
'LBL_ADV_OPTIONS'		=> '拡張オプション',
'LBL_TOGGLE_ADV' => '拡張オプション',
'LBL_TOGGLE_BASIC' => '基本オプション',
// Links
'LNK_LIST_SCHEDULER' => 'スケジューラー',
'LNK_NEW_SCHEDULER' => 'スケジューラー作成',
'LNK_LIST_SCHEDULED' => 'スケジュール済みジョブ',
// Messages
'SOCK_GREETING' => "これはSugarCRMスケジューラーのインターフェースです。<br />[ 使用可能なデーモンコマンド: start|restart|shutdown|status ]<br />終了するには「quit」を、サービスをシャットダウンするには「shutdown」を入力してください。",
'ERR_DELETE_RECORD' => 'スケジュールを削除する場合はレコード番号を指定してください。',
'ERR_CRON_SYNTAX' => '不正なcron文法',
'NTC_DELETE_CONFIRMATION' => '本当にこのレコードを削除してよいですか？',
'NTC_STATUS' => 'このスケジューラーをドロップダウンからはずす場合はステータスを非アクティブにしてください。',
'NTC_LIST_ORDER' => 'このスケジュールがドロップダウンで表示される順番を指定してください。',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Windowsのスケジューラーを設定するには',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Crontab設定方法',
'LBL_CRON_LINUX_DESC' => '以下を参考にcron.phpを実行する行をcrontabに追加:',
'LBL_CRON_WINDOWS_DESC' => '以下の例を参考にcron.phpを実行するバッチファイルを作成してください:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'アクティブなジョブ',
'LBL_EXECUTE_TIME'			=> '実行時間',

//jobstrings
'LBL_REFRESHJOBS' => 'ジョブを更新',
'LBL_POLLMONITOREDINBOXES' => 'インバウンドメールのアカウントを確認',
'LBL_PERFORMFULLFTSINDEX' => 'フルテキスト検索インデックスシステム',
'LBL_SUGARJOBREMOVEPDFFILES' => '一時PDFファイルを削除',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => '承認された記事を公開し、KB 記事を期限切れにします。',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch キュー スケジューラ',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => '診断ツールファイルを削除',
'LBL_SUGARJOBREMOVETMPFILES' => '一時ファイルを削除',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => '非正規チームのセキュリティ データを再構築',

'LBL_RUNMASSEMAILCAMPAIGN' => '夜間にキャンペーンの一括Eメール送信を実行',
'LBL_ASYNCMASSUPDATE' => '非同期一括アップデートを実行',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '夜間にキャンペーンのバウンスEメールを処理',
'LBL_PRUNEDATABASE' => '月初め（１日）に不要なデータベースのデータを削除',
'LBL_TRIMTRACKER' => '不要なトラッカーテーブルを削除',
'LBL_PROCESSWORKFLOW' => 'ワークフロータスクを実行',
'LBL_PROCESSQUEUE' => 'スケジュ－ルされたレポートを作成',
'LBL_UPDATETRACKERSESSIONS' => 'トラッカーセッションテーブルを更新',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => '将来の期間を作成',
'LBL_SUGARJOBHEARTBEAT' => 'Sugarハートビート',
'LBL_SENDEMAILREMINDERS'=> 'Eメールリマインダ送信を実行',
'LBL_CLEANJOBQUEUE' => 'ジョブキューのクリーンアップ',
'LBL_CLEANOLDRECORDLISTS' => '古いレコードリストをクリーンアップする',
'LBL_PMSEENGINECRON' => 'Advanced Workflowスケジューラー',
);

