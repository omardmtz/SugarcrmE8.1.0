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

class CurrencyHooks
{
    /**
     * Checks to see if the conversion_rate updated on save, then schedules a job to update all the currencies in the
     * app that use them.
     * @param Currency $bean
     * @param $event
     * @param $args
     */
    public function updateCurrencyConversion(Currency $bean, $event, $args)
    {
        if ($args['isUpdate'] && $bean->fetched_row['conversion_rate'] != $bean->conversion_rate) {
            $job = $this->getSchedulersJobs();
            $job->name = 'SugarJobUpdateCurrencyRates: ' . $bean->id;
            $job->target = 'class::SugarJobUpdateCurrencyRates';
            $job->data = $bean->id;
            $job->retry_count = 0;
            $job->assigned_user_id = $bean->modified_user_id;
            $jobQueue = $this->getSugarJobQueue();
            $jobQueue->submitJob($job);
        }
    }

    /**
     * gets a new instance of the SchedulersJobs bean
     * @return null|SugarBean
     */
    protected function getSchedulersJobs()
    {
        return BeanFactory::newBean('SchedulersJobs');
    }

    /**
     * gets a new instance of the SugarJobQueue
     * @return SugarJobQueue
     */
    protected function getSugarJobQueue()
    {
        return new SugarJobQueue();
    }
}
