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
 * SugarJobRemoveReassignedItems
 *
 * Class to run a job which will remove any reassigned items from the forecast worksheet upon commit
 *
 */
class SugarJobRemoveReassignedItems implements RunnableSchedulerJob
{

    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * @param SchedulersJob $job
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }


    /**
     * @param string $data The job data set for this particular Scheduled Job instance
     * @return boolean true if the run succeeded; false otherwise
     */
    public function run($data)
    {
        $args = json_decode(html_entity_decode($data), true);
        $this->job->runnable_ran = true;
        $fw = new ForecastWorksheet();

        $fw->processRemoveChunk($args);

        $this->job->succeedJob();
        return true;
    }

}
