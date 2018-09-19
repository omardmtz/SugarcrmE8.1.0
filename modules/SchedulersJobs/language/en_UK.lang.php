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
    'LBL_MODULE_NAME' => 'Job Queue',
    'LBL_MODULE_NAME_SINGULAR' => 'Job Queue',
    'LBL_MODULE_TITLE' => 'Job Queue: Home',
    'LBL_MODULE_ID' => 'Job Queue',
    'LBL_TARGET_ACTION' => 'Action',
    'LBL_FALLIBLE' => 'Fallible',
    'LBL_RERUN' => 'Rerun',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'View Job Queue',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuration',
    'LBL_CONFIG_PAGE' => 'Job Queue Settings',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancel',
    'LBL_JOB_PAUSE_BUTTON' => 'Pause',
    'LBL_JOB_RESUME_BUTTON' => 'Resume',
    'LBL_JOB_RERUN_BUTTON' => 'Requeue',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_ASSIGNED_USER' => 'Requested by',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Resolution',
    'LBL_NAME' => 'Job Name',
    'LBL_EXECUTE_TIME' => 'Execute Time',
    'LBL_SCHEDULER_ID' => 'Scheduler',
    'LBL_STATUS' => 'Job Status',
    'LBL_RESOLUTION' => 'Result',
    'LBL_MESSAGE' => 'Messages',
    'LBL_DATA' => 'Job Data',
    'LBL_REQUEUE' => 'Retry on failure',
    'LBL_RETRY_COUNT' => 'Maximum retries',
    'LBL_FAIL_COUNT' => 'Failures',
    'LBL_INTERVAL' => 'Minimum interval between tries',
    'LBL_CLIENT' => 'Owning client',
    'LBL_PERCENT' => 'Percent complete',
    'LBL_JOB_GROUP' => 'Job group',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolution Queued',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolution Partial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolution Complete',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Resolution Failure',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolution Cancelled',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resolution Running',
    // Errors
    'ERR_CALL' => "Cannot call function: %s",
    'ERR_CURL' => "No CURL - cannot run URL jobs",
    'ERR_FAILED' => "Unexpected failure, please check PHP logs and sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s in %s on line %d",
    'ERR_NOUSER' => "No User ID specified for the job",
    'ERR_NOSUCHUSER' => "User ID %s not found",
    'ERR_JOBTYPE' => "Unknown job type: %s",
    'ERR_TIMEOUT' => "Forced failure on timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Job %1$s (%2$s) failed in CRON run',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Cannot load bean with id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Cannot find handler for route %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Extension for this queue is not installed',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Some of the fields are empty',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Job Queue Settings',
    'LBL_CONFIG_MAIN_SECTION' => 'Main Configuration',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman Configuration',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP Configuration',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs Configuration',
    'LBL_CONFIG_SERVERS_TITLE' => 'Job Queue Configuration Help',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Main Configuration Section.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> - use only one process for workers.</li>
    <li><i>Parallel</i> - use a few processes for workers.</li>
    </ul>
    </li>
    <li>Adaptor:
    <ul>
    <li><i>Default Queue</i> - This will use only Sugar's Database without any message queue.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service is a distributed queue
    messaging service introduced by Amazon.com.
    It supports programmatic sending of messages via web service applications as a way to
    communicate over the Internet.</li>
    <li><i>RabbitMQ</i> - is open source message broker software (sometimes called message-oriented middleware)
    that implements the Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> - is an open source application framework designed to distribute appropriate computer
    tasks to multiple computers, so large tasks can be done more quickly.</li>
    <li><i>Immediate</i> - Like the default queue but executes task immediately after adding.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS Configuration Help',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS Configuration Section.</b></p>
<ul>
    <li>Access Key ID: <i>Enter your access key id number for Amazon SQS</i></li>
    <li>Secret Access Key: <i>Enter your secret access key for Amazon SQS</i></li>
    <li>Region: <i>Enter the region of Amazon SQS server</i></li>
    <li>Queue Name: <i>Enter queue name of Amazon SQS server</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP Configuration Help',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP Configuration Section.</b></p>
<ul>
    <li>Server URL: <i>Enter your message queue server's URL.</i></li>
    <li>Login: <i>Enter your login for RabbitMQ</i></li>
    <li>Password: <i>Enter your password for RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman Configuration Help',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman Configuration Section.</b></p>
<ul>
    <li>Server URL: <i>Enter your message queue server's URL.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptor',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Server URL',
    'LBL_LOGIN' => 'Login',
    'LBL_ACCESS_KEY' => 'Access Key ID',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Secret Access Key',
    'LBL_QUEUE_NAME' => 'Adaptor Name',
);
