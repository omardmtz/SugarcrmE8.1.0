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
    'LBL_MODULE_NAME' => '任务队列',
    'LBL_MODULE_NAME_SINGULAR' => '任务队列',
    'LBL_MODULE_TITLE' => '任务队列：首页',
    'LBL_MODULE_ID' => '任务队列',
    'LBL_TARGET_ACTION' => '动作',
    'LBL_FALLIBLE' => '易犯错误的',
    'LBL_RERUN' => '重新运行',
    'LBL_INTERFACE' => '界面',
    'LINK_SCHEDULERSJOBS_LIST' => '查看任务队列',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => '配置',
    'LBL_CONFIG_PAGE' => '任务队列设置',
    'LBL_JOB_CANCEL_BUTTON' => '取消',
    'LBL_JOB_PAUSE_BUTTON' => '暂停',
    'LBL_JOB_RESUME_BUTTON' => '重试',
    'LBL_JOB_RERUN_BUTTON' => '重新排队',
    'LBL_LIST_NAME' => '名称',
    'LBL_LIST_ASSIGNED_USER' => '申请者',
    'LBL_LIST_STATUS' => '状态',
    'LBL_LIST_RESOLUTION' => '分析',
    'LBL_NAME' => '任务名称',
    'LBL_EXECUTE_TIME' => '执行时间',
    'LBL_SCHEDULER_ID' => '计划任务',
    'LBL_STATUS' => '任务状态',
    'LBL_RESOLUTION' => '结果',
    'LBL_MESSAGE' => '消息',
    'LBL_DATA' => '任务数据',
    'LBL_REQUEUE' => '失败重试',
    'LBL_RETRY_COUNT' => '最大重试次数',
    'LBL_FAIL_COUNT' => '失败',
    'LBL_INTERVAL' => '重试最短间隔',
    'LBL_CLIENT' => '亏欠客户',
    'LBL_PERCENT' => '完成百分比',
    'LBL_JOB_GROUP' => '任务组',
    'LBL_RESOLUTION_FILTER_QUEUED' => '排队等候的分析',
    'LBL_RESOLUTION_FILTER_PARTIAL' => '部分分析',
    'LBL_RESOLUTION_FILTER_SUCCESS' => '完整的分析',
    'LBL_RESOLUTION_FILTER_FAILURE' => '分析失败',
    'LBL_RESOLUTION_FILTER_CANCELLED' => '分析取消',
    'LBL_RESOLUTION_FILTER_RUNNING' => '分析运行',
    // Errors
    'ERR_CALL' => "无法调用功能：%s",
    'ERR_CURL' => "无 CURL - 无法运行 URL 任务",
    'ERR_FAILED' => "无法预测的失败，请检查 PHP 日志和 sugarcrm. 日志",
    'ERR_PHP' => "%s [%d]：%s 在 %s 于行 %d",
    'ERR_NOUSER' => "该任务未指定用户 ID",
    'ERR_NOSUCHUSER' => "用户编号 %s 未找到",
    'ERR_JOBTYPE' => "未知任务类型：%s",
    'ERR_TIMEOUT' => "超时被迫失败",
    'ERR_JOB_FAILED_VERBOSE' => '任务 %1$s (%2$s) 在 CRON 运行中失败',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => '无法同时加载 Bean 和 id：%s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => '无法找到路径的处理器 %s',
    'ERR_CONFIG_MISSING_EXTENSION' => '未安装此队列的扩展',
    'ERR_CONFIG_EMPTY_FIELDS' => '一些字段为空',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => '任务队列设置',
    'LBL_CONFIG_MAIN_SECTION' => '主要配置',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman 配置',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP 配置',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => '亚马逊简单队列服务 (Amazon-sqs) 配置',
    'LBL_CONFIG_SERVERS_TITLE' => '任务队列配置帮助',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>主配置部分。</b></p>
<ul>
    <li>操作人员：
    <ul>
    <li><i>标准</i> -仅使用一个员工流程</li>
    <li><i>并行</i> - 使用数个员工流程</li>
    </ul>
    </li>
    <li>适配器：
    <ul>
    <li><i>默认队列</i> - 将仅使用 Sugar 的数据库，无任何消息队列。</li>
    <li><i>亚马逊简单队列服务 (Amazon SQS) </i> - 亚马逊简单队列服务是一项分配队列消息服务，由 Amazon.com 引进。
    它通过网络服务应用程序为程序化消息发送提供支持，是一种通过因特网通讯的方法。</li>
    <li><i>RabbitMQ</i> - 是开放资源消息代理软件（有时称为消息导向中间软件），执行高级消息
    队列协议 (AMQP)。</li>
    <li><i>Gearman</i> - 是一个开放资源应用程序框架，旨在将适当的计算机任务分配至多台计算机，以便更快速地完成大型任务。</li>
    <li><i>Immediate</i> - 像默认队列一样，但添加后可立即执行任务。</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => '亚马逊简单队列服务 (Amazon SQS) 配置帮助',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>亚马逊简单队列服务 (Amazon SQS) 配置部分</b></p>
<ul>
    <li>访问密匙 ID：<i>输入您的 Amazon SQS 访问密匙编号</i></li>
    <li>秘密访问密钥：<i>输入您的 Amazon SQS 秘密访问密匙</i></li>
    <li>区域<i>输入 Amazon SQS 服务器区域</i></li>
    <li>队列名称：<i>输入 Amazon SQS 服务器队列名称</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP 配置帮助',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP 配置部分。</b></p>
<ul>
    <li>服务器 URL：<i>输入消息队列服务器的 URL。</i></li>
    <li>登录：<i>输入RabbitMQ 登录信息</i></li>
    <li>密码：<i>输入 RabbitMQ 密码</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman 配置帮助',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman 配置部分。</b></p>
<ul>
    <li>服务器 URL：<i>输入消息队列服务器的 URL。</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => '适配器',
    'LBL_CONFIG_QUEUE_MANAGER' => '操作人员',
    'LBL_SERVER_URL' => '服务器 URL',
    'LBL_LOGIN' => '登录',
    'LBL_ACCESS_KEY' => '访问密匙 ID',
    'LBL_REGION' => '区域',
    'LBL_ACCESS_KEY_SECRET' => '秘密访问密匙',
    'LBL_QUEUE_NAME' => '适配器名称',
);
