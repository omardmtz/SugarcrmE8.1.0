<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

use Sugarcrm\Sugarcrm\ProcessManager;

if (empty($job_strings)) {
    $job_strings = array();
}

array_push($job_strings, 'PMSEEngineCron');

if (!function_exists("PMSEEngineCron")) {
    function PMSEEngineCron()
    {
//      Calls and Meetings modules uses this session variable on save function,
//      in order to do not send notification email to the owner. (within Advanced Workflow cron)
        $_SESSION['process_author_cron'] = true;
        $hookHandler = ProcessManager\Factory::getPMSEObject('PMSEHookHandler');
        $hookHandler->executeCron();
        unset($_SESSION['process_author_cron']);

        return true;
    }
}

if (!function_exists("PMSEJobRun")) {
    function PMSEJobRun($job)
    {
        if (!empty($job->data)) {
            $flowData = (array)json_decode($job->data);
            $externalAction = 'RESUME_EXECUTION';
            $jobQueueHandler = ProcessManager\Factory::getPMSEObject('PMSEJobQueueHandler');

            $jobQueueHandler->executeRequest($flowData, false, null, $externalAction);
        }

        return true;
    }
}

