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
 * CRON driver for job queue that ships jobs outside
 * @api
 */
class SugarCronRemoteJobs extends SugarCronJobs
{
    /**
     * URL for remote job server
     * @var string
     */
    protected $jobserver;

    /**
     * Just in case we'd ever need to override...
     * @var string
     */
    protected $submitURL = "submitJob";

    /**
     * REST client
     * @var string
     */
    protected $client;

    public function __construct()
    {
        parent::__construct();
        if(!empty($GLOBALS['sugar_config']['job_server'])) {
            $this->jobserver = $GLOBALS['sugar_config']['job_server'];
        }
        $this->setClient(new SugarHttpClient());
    }

    /**
    * Set client to talk to SNIP
    * @param SugarHttpClient $client
    */
    public function setClient(SugarHttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Return ID for this client
     * @return string
     */
    public function getMyId()
    {
        return 'CRON'.$GLOBALS['sugar_config']['unique_key'].':'.md5($this->jobserver);
    }

    /**
     * Execute given job
     * @param SchedulersJob $job
     */
    public function executeJob($job)
    {
        $data = http_build_query(array("data" => json_encode(array("job" => $job->id, "client" => $this->getMyId(), "instance" => $GLOBALS['sugar_config']['site_url']))));
        $response = $this->client->callRest($this->jobserver.$this->submitURL, $data);
        if(!empty($response)) {
            $result = json_decode($response, true);
            if(empty($result) || empty($result['ok']) || $result['ok'] != $job->id) {
                $GLOBALS['log']->debug("CRON Remote: Job {$job->id} not accepted by server: $response");
                $this->jobFailed($job);
                $job->failJob("Job not accepted by server: $response");
            }
        } else {
            $GLOBALS['log']->debug("CRON Remote: REST request failed for job {$job->id}");
            $this->jobFailed($job);
            $job->failJob("Could not connect to job server");
        }
    }

}

