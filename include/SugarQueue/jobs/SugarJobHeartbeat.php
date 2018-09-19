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
 * SugarJobHeartbeat
 *
 * This class is intended to ensure that we continue to receive heartbeats even if no users are logging in.
 */
class SugarJobHeartbeat implements RunnableSchedulerJob
{

    /**
     * @var SchedulersJob
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
        $systemInfo = $this->getSystemInfo();

        if ($systemInfo->getActiveUsersXDaysCount(3) == 0) {
            if (!$this->sendHeartbeat($systemInfo->getInfo())) {
                $this->job->failJob("Unable to send heartbeat");
                return false;
            }
        }

        $this->job->succeedJob();
        return true;
    }

    /**
     * Sends $info to heartbeat server
     *
     * @param $info
     * @return bool
     */
    protected function sendHeartbeat($info)
    {
        $license = Administration::getSettings('license');
        $client = $this->getClient();
        $client->sugarHome($license->settings['license_key'], $info);
        return !$client->getError();
    }

    /**
     * Returns SugarSystemInfo object
     *
     * @return SugarSystemInfo
     */
    protected function getSystemInfo()
    {
        return SugarSystemInfo::getInstance();
    }

    /**
     * Get SugarHeartbeatClient object
     *
     * @return SugarHeartbeatClient
     */
    protected function getClient()
    {
        SugarAutoLoader::requireWithCustom('include/SugarHeartbeat/SugarHeartbeatClient.php', true);
        $clientClass = SugarAutoLoader::customClass('SugarHeartbeatClient');
        return new $clientClass();
    }
}
