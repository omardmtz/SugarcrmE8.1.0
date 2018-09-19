<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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


$modListHeader = array();
require_once('include/modules.php');
require_once('config.php');

/** @var Localization $locale */
global $sugar_config,
       $current_language,
       $app_list_strings,
       $app_strings,
       $locale,
       $timedate;

$language         = $sugar_config['default_language']; // here we'd better use English, because pdf coding problem.
$app_list_strings = return_app_list_strings_language($language);
$app_strings      = return_application_language($language);

$reportSchedule = new ReportSchedule();
$reportSchedule->handleFailedReports();
$reportsToEmail = $reportSchedule->get_reports_to_email();

//Process Enterprise Schedule reports via CSV
//bug: 23934 - enable Advanced reports
require_once('modules/ReportMaker/process_scheduled.php');

global $report_modules,
       $modListHeader,
       $current_user;

$queue = new SugarJobQueue();
foreach ($reportsToEmail as $scheduleInfo) {
    $job = BeanFactory::newBean('SchedulersJobs');
    $job->name = 'Send Scheduled Report ' . $scheduleInfo['report_id'];
    $job->assigned_user_id = $scheduleInfo['user_id'];
    $job->target = 'class::SugarJobSendScheduledReport';
    $job->data = $scheduleInfo['id'];
    $job->job_group = $scheduleInfo['report_id'];
    $queue->submitJob($job);
}

DBManagerFactory::getInstance()->commit();
