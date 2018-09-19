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

class SugarJobAddActivitySubscriptions implements RunnableSchedulerJob
{
    protected $job;

    /**
     * This method implements setJob from RunnableSchedulerJob. It sets the
     * SchedulersJob instance for the class.
     *
     * @param SchedulersJob $job the SchedulersJob instance set by the job queue
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * Executes a job to add activity subscriptions.
     * @param $data
     * @return bool
     */
    public function run($data)
    {
        try {
            $data                  = unserialize($data);
            $subscriptionsBeanName = BeanFactory::getBeanClass('Subscriptions');
            $subscriptionsBeanName::addActivitySubscriptions($data);
            $this->job->succeedJob();
            return true;
        } catch (Exception $e) {
            $this->job->failJob($e->getMessage());
            return false;
        }
    }
}
