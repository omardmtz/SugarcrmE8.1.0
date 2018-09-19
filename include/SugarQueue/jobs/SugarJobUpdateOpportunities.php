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
 * SugarJobUpdateOpportunities.php
 *
 * Class to run a job which should upgrade every old opp with commit stage, date_closed_timestamp,
 * best/worst cases and related product
 */
class SugarJobUpdateOpportunities extends JobNotification implements RunnableSchedulerJob {

    /**
     * @var SchedulersJob
     */
    protected $job;


    /**
     * The Label that will be used for the subject line
     *
     * @var string
     */
    protected $subjectLabel = 'LBL_JOB_NOTIFICATION_OPP_FORECAST_SYNC_SUBJECT';

    /**
     * The Label that will be used for the body of the notification and email
     *
     * @var string
     */
    protected $bodyLabel = 'LBL_JOB_NOTIFICATION_OPP_FORECAST_SYNC_BODY';

    /**
     * Include the help link
     *
     * @var bool
     */
    protected $includeHelpLink = true;

    /**
     * What module is the help link for
     *
     * @var string
     */
    protected $helpModule = 'Opportunities';

    /**
     * @param SchedulersJob $job
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * @param $data
     * @return bool
     */
    public function run($data)
    {
        $this->job->runnable_ran = true;
        $this->job->runnable_data = $data;

        $data = json_decode($data, true);

        Activity::disable();
        $ftsSearch = \Sugarcrm\Sugarcrm\SearchEngine\SearchEngine::getInstance();
        $ftsSearch->setForceAsyncIndex(true);

        foreach ($data as $row) {
            /* @var $opp Opportunity */
            $opp = BeanFactory::getBean('Opportunities', $row['id']);
            $opp->save(false);
        }

        $ftsSearch->setForceAsyncIndex(
            SugarConfig::getInstance()->get('search_engine.force_async_index', false)
        );
        Activity::restoreToPreviousState();

        $this->job->succeedJob();
        $this->notifyAssignedUser();
        return true;
    }

    /**
     * This function creates a job for to run the SugarJobUpdateOpportunities class
     * @param integer $perJob
     * @returns array|string An array of the jobs that were created, unless there
     * is one, then just that job's id
     */
    public static function updateOpportunitiesForForecasting($perJob = 100)
    {
        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from(BeanFactory::newBean('Opportunities'));
        $sq->orderBy('date_closed');

        $rows = $sq->execute();

        if (empty($rows)) {
            return false;
        }

        $chunks = array_chunk($rows, $perJob);

        $jobs = array();
        // process the first job now
        $job = static::createJob($chunks[0], true);
        $jobs[] = $job->id;
        // run the first job
        $self = new self();
        $self->setJob($job);
        $self->sendNotifications = false;
        $self->run($job->data);

        $job_group = md5(microtime());

        for ($i = 1; $i < count($chunks); $i++) {
            $jobs[] = static::createJob($chunks[$i], false, $job_group);
        }

        // if only one job was created, just return that id
        if (count($jobs) == 1) {
            return array_shift($jobs);
        }

        return $jobs;
    }

    /**
     * @param array $data The data for the Job
     * @param bool $returnJob When `true` the job will be returned, otherwise the job id will be returned
     * @param string|null $job_group The Group that this job belongs to
     * @return SchedulersJob|String
     */
    public static function createJob(array $data, $returnJob = false, $job_group = null)
    {
        global $current_user;

        /* @var $job SchedulersJob */
        $job = BeanFactory::newBean('SchedulersJobs');
        $job->name = "Update Old Opportunities";
        $job->target = "class::SugarJobUpdateOpportunities";
        $job->data = json_encode($data);
        $job->retry_count = 0;
        $job->assigned_user_id = $current_user->id;
        if (!is_null($job_group)) {
            $job->job_group = $job_group;
        }
        $job_queue = new SugarJobQueue();
        $job_queue->submitJob($job);

        if ($returnJob === true) {
            return $job;
        }

        return $job->id;
    }
}
