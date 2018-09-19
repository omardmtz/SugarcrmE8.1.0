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
 * SugarJobSQLRunner.php
 *
 * sometimes you have thousands of SQL queries to run, and you
 * have to schedule them in batches. that is what SQLRunner is for.
 * SQLRunner is a very simple SQL query running scheduler job. It loops
 * over SQL statements supplied by the $data and executes them.
 * not a lot of validation goes on here, so construct queries carefully.
 * one failed query will stop the whole process.
 *
 * be sure you split your queries into manageable batches and feed them to
 * separate jobs, ie. don't overwhelm one job with thousands of queries.
 *
 */
class SugarJobSQLRunner implements RunnableSchedulerJob
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
        $data = unserialize($data);
        if (!is_array($data)) {
            // data must be array of arrays
            $this->job->failJob('invalid query data');
            return false;
        }
        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();

        foreach ($data as $query) {
            if (!is_string($query) || empty($query)) {
                // bad format? we're done
                $this->job->failJob('invalid query: ' . $query);
                return false;
            }
            $result = $db->query($query);
            if (!$result) {
                $this->job->failJob('query failed: ' . $query);
                return false;
            }
        }

        $this->job->succeedJob();
        return true;
    }

}
