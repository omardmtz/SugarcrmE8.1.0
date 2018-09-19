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
    'LBL_MODULE_NAME' => '工作佇列',
    'LBL_MODULE_NAME_SINGULAR' => '工作佇列',
    'LBL_MODULE_TITLE' => '工作佇列：首頁',
    'LBL_MODULE_ID' => '工作佇列',
    'LBL_TARGET_ACTION' => '動作',
    'LBL_FALLIBLE' => '易錯',
    'LBL_RERUN' => '重新執行',
    'LBL_INTERFACE' => '介面',
    'LINK_SCHEDULERSJOBS_LIST' => '檢視工作佇列',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => '設定',
    'LBL_CONFIG_PAGE' => '工作佇列設定',
    'LBL_JOB_CANCEL_BUTTON' => '取消',
    'LBL_JOB_PAUSE_BUTTON' => '暫停',
    'LBL_JOB_RESUME_BUTTON' => '繼續',
    'LBL_JOB_RERUN_BUTTON' => '重新排入佇列',
    'LBL_LIST_NAME' => '名稱',
    'LBL_LIST_ASSIGNED_USER' => '請求者',
    'LBL_LIST_STATUS' => '狀態',
    'LBL_LIST_RESOLUTION' => '解決方式',
    'LBL_NAME' => '工作名稱',
    'LBL_EXECUTE_TIME' => '執行時間',
    'LBL_SCHEDULER_ID' => '排程器',
    'LBL_STATUS' => '工作狀態',
    'LBL_RESOLUTION' => '結果',
    'LBL_MESSAGE' => '訊息',
    'LBL_DATA' => '工作資料',
    'LBL_REQUEUE' => '失敗后重試',
    'LBL_RETRY_COUNT' => '最大重試次數',
    'LBL_FAIL_COUNT' => '失敗',
    'LBL_INTERVAL' => '嘗試之間的最小時間間隔',
    'LBL_CLIENT' => '擁有用戶端',
    'LBL_PERCENT' => '完成百分比',
    'LBL_JOB_GROUP' => '工作群組',
    'LBL_RESOLUTION_FILTER_QUEUED' => '解決方式已排入佇列',
    'LBL_RESOLUTION_FILTER_PARTIAL' => '部分解決方式',
    'LBL_RESOLUTION_FILTER_SUCCESS' => '完整解決方式',
    'LBL_RESOLUTION_FILTER_FAILURE' => '解決方式失敗',
    'LBL_RESOLUTION_FILTER_CANCELLED' => '解決方式已取消',
    'LBL_RESOLUTION_FILTER_RUNNING' => '解決方式正在執行',
    // Errors
    'ERR_CALL' => "無法調用功能：%s",
    'ERR_CURL' => "無 CURL－無法執行 URL 工作",
    'ERR_FAILED' => "意外失敗，請檢查 PHP 記錄和 sugarcrm.log",
    'ERR_PHP' => "%s [%d]：%s 中的 %s（行 %d）",
    'ERR_NOUSER' => "工作未指定使用者 ID",
    'ERR_NOSUCHUSER' => "未找到使用者 ID %s",
    'ERR_JOBTYPE' => "未知工作類型：%s",
    'ERR_TIMEOUT' => "逾時強制失敗",
    'ERR_JOB_FAILED_VERBOSE' => 'CRON 執行中工作 %1$s (%2$s) 失敗',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => '無法載入 ID 為 %s 的 Bean',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => '無法找到路由 %s 的處理常式',
    'ERR_CONFIG_MISSING_EXTENSION' => '此佇列的延伸模組未安裝',
    'ERR_CONFIG_EMPTY_FIELDS' => '部分欄位為空',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => '工作佇列設定',
    'LBL_CONFIG_MAIN_SECTION' => '主設定',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman 設定',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP 設定',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs 設定',
    'LBL_CONFIG_SERVERS_TITLE' => '工作佇列設定說明',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>主設定部分。</b></p>
<ul>
    <li>執行器：
    <ul>
    <li><i>標準</i> - 僅使用一個背景工作流程。</li>
    <li><i>平行</i> - 使用一些背景工作流程。</li>
    </ul>
    </li>
    <li>配接器：
    <ul>
    <li><i>預設佇列</i> - 這只會使用不含任何訊息佇列的「Sugar 資料庫」。</li>
    <li><i>Amazon SQS</i> - 「Amazon 簡單佇列服務」是由 Amazon.com 引進的分散式佇列訊息服務。
    它可支援透過 Web 服務應用程式以程式設計方式傳送訊息，作為一種在網際網路上通訊的方式。</li>
    <li><i>RabbitMQ</i> - 是一款開放源訊息代理軟體（有時稱為訊息導向中介軟體），
    執行「進階訊息佇列通訊協定 (AMQP)」。</li>
    <li><i>Gearman</i> - 是一種開放源應用程式架構，用於將合適的電腦
    工作散發至多個電腦，從而能夠更快完成大型工作。</li>
    <li><i>立即</i> - 類似於預設佇列，但是在新增后立即執行工作。</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS 設定說明',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS 設定部分。</b></p>
<ul>
    <li>存取金鑰 ID：<i>輸入 Amazon SQS 的存取金鑰 ID 編號</i></li>
    <li>秘密存取金鑰：<i>輸入 Amazon SQS 的秘密存取金鑰</i></li>
    <li>區域：<i>輸入 Amazon SQS 伺服器的區域</i></li>
    <li>佇列名稱：<i>輸入 Amazon SQS 伺服器的佇列名稱</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP 設定說明',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP 設定部分。</b></p>
<ul>
    <li>伺服器 URL：<i>輸入訊息佇列伺服器的 URL。</i></li>
    <li>登入：<i>輸入 RabbitMQ 的登入資料</i></li>
    <li>密碼：<i>輸入 RabbitMQ 的密碼</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman 設定說明',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman 設定部分。</b></p>
<ul>
    <li>伺服器 URL：<i>輸入訊息佇列伺服器的 URL。</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => '配接器',
    'LBL_CONFIG_QUEUE_MANAGER' => '執行器',
    'LBL_SERVER_URL' => '伺服器 URL',
    'LBL_LOGIN' => '登入',
    'LBL_ACCESS_KEY' => '存取金鑰 ID',
    'LBL_REGION' => '區域',
    'LBL_ACCESS_KEY_SECRET' => '秘密存取金鑰',
    'LBL_QUEUE_NAME' => '配接器名稱',
);
