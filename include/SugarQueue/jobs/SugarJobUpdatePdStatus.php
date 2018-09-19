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
 * Class SugarJobUpdatePdStatus
 * Used to mass update Process Definition statuses
 */
class SugarJobUpdatePdStatus implements RunnableSchedulerJob
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
     * @return bool true on success
     */
    public function run($data)
    {
        $data = json_decode($data, true);
        $status = $data['status'];

        if ($status !== 'ACTIVE' && $status !== 'INACTIVE') {
            $this->job->failJob("Unknown status found: $status. Expected ACTIVE or INACTIVE");
            return false;
        }

        foreach ($data['ids'] as $id) {
            $bean = BeanFactory::getBean('pmse_Project', $id);
            if (!$bean) {
                $GLOBALS['log']->fatal("pmse_Project bean with id $id not found. Skipping");
                continue;
            }

            // Update the status for the bean
            if ($bean->prj_status !== $status) {
                $bean->prj_status = $status;
                $bean->save();
                $bean->saveRelatedBeans();
            }
        }
        $this->job->succeedJob();
        return true;
    }
}
