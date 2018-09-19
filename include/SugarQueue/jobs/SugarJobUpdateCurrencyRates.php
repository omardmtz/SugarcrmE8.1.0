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


/**
 * CurrencyRateSchedulerJob.php
 *
 * This class implements RunnableSchedulerJob and provides the support for
 * updating currency rates per module.
 *
 */
class SugarJobUpdateCurrencyRates implements RunnableSchedulerJob
{

    /**
     * @var $job the job object
     */
    protected $job;

    /**
     * This method implements setJob from RunnableSchedulerJob and sets the SchedulersJob instance for the class
     *
     * @param SchedulersJob $job the SchedulersJob instance set by the job queue
     *
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * This method implements the run function of RunnableSchedulerJob and handles processing a SchedulersJob
     *
     * @param Mixed $data parameter passed in from the job_queue.data column when a SchedulerJob is run
     * @return bool true on success, false on error
     */
    public function run($data)
    {
        // Searches across modules for rate update scheduler jobs and executes them.
        // Each module that has currency rates in its model(s) *must* have a scheduler
        // job defined in order to update its rates when a currency rate is updated.
        $globPaths = array(
            'custom/modules/*/jobs/Custom*CurrencyRateUpdate.php',
            'modules/*/jobs/*CurrencyRateUpdate.php'
        );
        foreach ($globPaths as $entry)
        {
            $jobFiles = glob($entry, GLOB_NOSORT);

            if(!empty($jobFiles))
            {
                foreach($jobFiles as $jobFile)
                {
                    $jobClass = basename($jobFile,'.php');
                    require_once($jobFile);
                    if(!class_exists($jobClass)) {
                        $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array(get_class($this),'uknown class: '.$jobClass)));
                        continue;
                    }
                    $jobObject = new $jobClass;
                    $data = $this->job->data;
                    $jobObject->run($data);
                }
            }
        }

        $this->job->succeedJob();
        return true;
    }

}