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
    'LBL_MODULE_NAME' => 'ジョブ キュー',
    'LBL_MODULE_NAME_SINGULAR' => 'ジョブ キュー',
    'LBL_MODULE_TITLE' => 'ジョブキュー: ホーム',
    'LBL_MODULE_ID' => 'ジョブ キュー',
    'LBL_TARGET_ACTION' => 'アクション',
    'LBL_FALLIBLE' => '当てにならない',
    'LBL_RERUN' => '再実行',
    'LBL_INTERFACE' => 'インターフェイス',
    'LINK_SCHEDULERSJOBS_LIST' => 'ジョブ キューの表示',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => '構成',
    'LBL_CONFIG_PAGE' => 'ジョブ キューの設定',
    'LBL_JOB_CANCEL_BUTTON' => 'キャンセル',
    'LBL_JOB_PAUSE_BUTTON' => '一時停止',
    'LBL_JOB_RESUME_BUTTON' => '再開',
    'LBL_JOB_RERUN_BUTTON' => 'キューに再登録する',
    'LBL_LIST_NAME' => '名前',
    'LBL_LIST_ASSIGNED_USER' => '要求者',
    'LBL_LIST_STATUS' => 'ステータス',
    'LBL_LIST_RESOLUTION' => '解決',
    'LBL_NAME' => 'ジョブ名',
    'LBL_EXECUTE_TIME' => '実行時間',
    'LBL_SCHEDULER_ID' => 'スケジューラー',
    'LBL_STATUS' => 'ジョブステータス',
    'LBL_RESOLUTION' => '結果',
    'LBL_MESSAGE' => 'メッセージ',
    'LBL_DATA' => '日付',
    'LBL_REQUEUE' => '障害時に再実行',
    'LBL_RETRY_COUNT' => '最大の再実行回数',
    'LBL_FAIL_COUNT' => '障害',
    'LBL_INTERVAL' => '最低の実行間隔',
    'LBL_CLIENT' => '所有クライアント',
    'LBL_PERCENT' => 'パーセント完了',
    'LBL_JOB_GROUP' => 'ジョブグループ',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'キューに並ぶ解決',
    'LBL_RESOLUTION_FILTER_PARTIAL' => '部分的な解決',
    'LBL_RESOLUTION_FILTER_SUCCESS' => '完全な解決',
    'LBL_RESOLUTION_FILTER_FAILURE' => '解決の失敗',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'キャンセルされた解決',
    'LBL_RESOLUTION_FILTER_RUNNING' => '実行中の解決',
    // Errors
    'ERR_CALL' => "ファンクションが読み出せません: %s",
    'ERR_CURL' => "CURLなし - URLジョブを実行できません",
    'ERR_FAILED' => "予期しない障害です　PHPログとsugarcrm.logを確認してください",
    'ERR_PHP' => "%s [%d]: 行 %d の %s に %s があります",
    'ERR_NOUSER' => "ジョブにユーザIDが指定されていません",
    'ERR_NOSUCHUSER' => "ユーザID %s が見つかりません",
    'ERR_JOBTYPE' => "不明のジョブタイプ: %s",
    'ERR_TIMEOUT' => "タイムアウトにより強制的に中止されました",
    'ERR_JOB_FAILED_VERBOSE' => 'CRON実行時にジョブ %1$s (%2$s) で障害が発生しました',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Id: %s の bean をロードできません',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'ルート %s のハンドラが見つかりません',
    'ERR_CONFIG_MISSING_EXTENSION' => 'このキューの拡張機能はインストールされていません',
    'ERR_CONFIG_EMPTY_FIELDS' => '一部のフィールドが空です',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'ジョブ キューの設定',
    'LBL_CONFIG_MAIN_SECTION' => 'メイン構成',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman 構成',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP 構成',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs 構成',
    'LBL_CONFIG_SERVERS_TITLE' => 'ジョブ キュー構成のヘルプ',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>メイン構成セクション</b></p>
<ul>
    <li>ランナー:
    <ul>
    <li><i>標準</i> - ワーカーに使用するプロセスは1件のみ。</li>
    <li><i>平行</i> - ワーカーに複数プロセスを使用する。</li>
    </ul>
    </li>
    <li>アダプター:
    <ul>
    <li><i>デフォルト キュー</i> -これは Sugar のデータベースをメッセージ キューなしでのみ使用します。</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service はAmazon.comにより
    導入された分散型キュー メッセージサービスです。
    ウェブ サービス アプリケーション経由でインターネット上で通信する手段としてプログラム的な
    メッセージ送信をサポートします。 </li>
    <li><i>RabbitMQ</i> - はオープンソースのメッセ―ジ ブローカー ソフトウェア (メッセージ指向ミドルウェアと呼ばれることもあります) で、
    Advanced Message Queuing Protocol (AMQP)を実装しています。</li>
    <li><i>Gearman</i> - はオープンソースのアプリケーション フレームワークで、大規模なタスクをより迅速に実行するために適切なコンピュータータスクを複数のコンピューターに分配するように設計されています。</li>
    <li><i>即時</i> - デフォルト キューと似ていますが、追加直後にタスクを実施します。</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS 構成のヘルプ',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS 構成セクション</b></p>
<ul>
    <li>アクセス キー ID: <i>Amazon SQS のアクセス キー ID を入力します</i></li>
    <li>秘密のアクセス キー: <i>Amazon SQSの秘密のアクセス キーを入力します</i></li>
    <li>地域: <i>Amazon SQS サーバーの地域を入力します</i></li>
    <li>キュー名: <i>Amazon SQS サーバーのキュー名を入力します</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP 構成のヘルプ',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP 構成セクション</b></p>
<ul>
    <li>サーバー URL: <i>Enter your message queue server's URL.</i></li>
    <li>Login: <i>RabbitMQ のログインを入力します</i></li>
    <li>Password: <i>RabbitMQ のパスワードを入力します</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman 構成のヘルプ',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman 構成セクション</b></p>
<ul>
    <li>サーバー URL: <i>メッセージ キュー サーバーの URL を入力します</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'アダプター',
    'LBL_CONFIG_QUEUE_MANAGER' => 'ランナー',
    'LBL_SERVER_URL' => 'サーバー URL',
    'LBL_LOGIN' => 'ログイン',
    'LBL_ACCESS_KEY' => 'アクセスキー ID',
    'LBL_REGION' => '地域',
    'LBL_ACCESS_KEY_SECRET' => '秘密のアクセス キー',
    'LBL_QUEUE_NAME' => 'アダプター名',
);
