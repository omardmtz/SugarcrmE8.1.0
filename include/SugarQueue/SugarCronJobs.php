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
use Sugarcrm\Sugarcrm\ProcessManager\Registry;
/**
 * CRON driver for job queue
 * @api
 */
class SugarCronJobs
{
    /**
     * Max number of jobs per cron run
     * @var int
     */
    public $max_jobs = 10;
    /**
     * Max time per cron run
     * @var int
     */
    public $max_runtime = 1800; // 30 minutes
    /**
     * Min time between cron runs
     * @var int
     */
    public $min_interval = 30;

    /**
     * Lock file to ensure the jobs aren't run too fast
     * @var string
     */
    public $lockfile;

    /**
     * Currently running job
     * @var SchedulersJob
     */
    public $job;

    /**
     * Is current queue run OK?
     * @var bool
     */
    public $runOk = true;

    /**
     * Should the driver print reports to stdout?
     * @var bool
     */
    public $verbose = false;

    /**
     * This allows to disable schedulers cycle, e.g. for testing
     * @var bool
     */
    public $disable_schedulers = false;

    /**
     * Should we enforce the job limit with pcntl?
     * We may put it to false for testing
     * @var bool
     */
    public $enforceHardLimit = false;

    public function __construct()
    {
        $this->queue = new SugarJobQueue();
        $this->lockfile = sugar_cached("modules/Schedulers/lastrun");
        if (!empty($GLOBALS['sugar_config']['cron']['max_cron_jobs'])) {
            $this->max_jobs = $GLOBALS['sugar_config']['cron']['max_cron_jobs'];
        }
        if (!empty($GLOBALS['sugar_config']['cron']['max_cron_runtime'])) {
            $this->max_runtime = $GLOBALS['sugar_config']['cron']['max_cron_runtime'];
        }
        if (isset($GLOBALS['sugar_config']['cron']['min_cron_interval'])) {
            $this->min_interval = $GLOBALS['sugar_config']['cron']['min_cron_interval'];
        }
        if (isset($GLOBALS['sugar_config']['cron']['enforce_runtime'])) {
            $this->enforceHardLimit = $GLOBALS['sugar_config']['cron']['enforce_runtime'];
        }
    }

    /**
     * Remember last time it was run
     */
    protected function markLastRun()
    {
        if(!file_put_contents($this->lockfile, time())) {
            $GLOBALS['log']->fatal('Scheduler cannot write PID file.  Please check permissions on '.$this->lockfile);
        }
    }

    /**
     * Check if we aren't running jobs too frequently
     * @return bool OK to run?
     */
    public function throttle()
    {
        if($this->min_interval == 0) {
            return true;
        }
        create_cache_directory($this->lockfile);
        if(!file_exists($this->lockfile)) {
            $this->markLastRun();
            return true;
        } else {
            $ts = file_get_contents($this->lockfile);
            $this->markLastRun();
            $now = time();
            if($now - $ts < $this->min_interval) {
                // run too frequently
                return false;
            }
        }
        return true;
    }

    /**
     * What to do if one of the jobs failed
     * @param SchedulersJob $job
     */
    protected function jobFailed($job = null)
    {
        $this->runOk = false;
        if(!empty($job)) {
            $GLOBALS['log']->fatal("Job {$job->id} ({$job->name}) failed in CRON run");
            if($this->verbose) {
                printf(translate('ERR_JOB_FAILED_VERBOSE', 'SchedulersJobs'), $job->id, $job->name);
            }
        }
    }

    /**
     * Shutdown handler to be called if something breaks in the middle of the job
     */
    public function unexpectedExit()
    {
        if (!empty($this->job)) {
            $this->jobFailed($this->job);
            $this->job->failJob(translate('ERR_FAILED', 'SchedulersJobs'));
            $this->job = null;
        }
    }

    /**
     * Return ID for this client
     * @return string
     */
    public function getMyId()
    {
        return 'CRON'.$GLOBALS['sugar_config']['unique_key'].':'.getmypid();
    }

    /**
     * Set hard execution limit
     * @param int $limit
     */
    protected function setTimeLimit($limit)
    {
        if (function_exists('pcntl_alarm') && $this->enforceHardLimit) {
            pcntl_alarm($limit);
        }
    }

    /**
     * Reset execution limit
     */
    protected function clearTimeLimit()
    {
        if (function_exists('pcntl_alarm') && $this->enforceHardLimit) {
            pcntl_alarm(0);
        }
    }

    /**
     * Execute given job
     * @param SchedulersJob $job
     */
    public function executeJob($job)
    {
        // Before calling save, we need to clear out any existing registered AWF
        // triggered start events so they can continue to trigger.
        Registry\Registry::getInstance()->drop('triggered_starts');
        $this->setTimeLimit($this->max_runtime);
        $res = $this->job->runJob();
        $this->clearTimeLimit();
        if (!$res) {
            // if some job fails, change run status
            $this->jobFailed($this->job);
        }
        // If the job produced a session, destroy it - we won't need it anymore
        if (session_id()) {
            session_destroy();
        }
    }

    /**
     * Run CRON cycle:
     * - cleanup
     * - schedule new jobs
     * - execute pending jobs
     */
    public function runCycle()
    {
        // throttle
        if(!$this->throttle()) {
            $GLOBALS['log']->fatal("Job runs too frequently, throttled to protect the system.");
            return;
        }
        // clean old stale jobs
        if(!$this->queue->cleanup()) {
            $this->jobFailed();
        }
        // run schedulers
        if(!$this->disable_schedulers) {
            $this->queue->runSchedulers();
        }
        // run jobs
        $cutoff = time()+$this->max_runtime;
        if ($this->enforceHardLimit) {
            set_time_limit(2*$this->max_runtime); // allow some space for normal exit
        }
        register_shutdown_function(array($this, "unexpectedExit"));
        $myid = $this->getMyId();
        for($count=0;$count<$this->max_jobs;$count++) {
            $this->job = $this->queue->nextJob($myid);
            if(empty($this->job)) {
                return;
            }
            $this->executeJob($this->job);
            if(time() >= $cutoff) {
                break;
            }
        }
        $this->job = null;
    }

    /**
     * Check if the queue run was fine
     */
    public function runOk()
    {
        return $this->runOk;
    }
}
