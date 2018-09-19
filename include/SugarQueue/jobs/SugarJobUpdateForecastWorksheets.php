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
 * SugarJobUpdateForecastWorksheets
 *
 * Class to run a job which will create the ForecastWorksheet entries for the timeperiod and user
 *
 */
class SugarJobUpdateForecastWorksheets implements RunnableSchedulerJob
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

        /* @var $admin Administration */
        $settings = Forecast::getSettings();

        if ($settings['is_setup'] == false) {
            $GLOBALS['log']->fatal("Forecast Module is not setup. " . __CLASS__ . " should not be running");
            return false;
        }

        $args = json_decode(html_entity_decode($data), true);
        $this->job->runnable_ran = true;
        $worksheet = BeanFactory::newBean('ForecastWorksheets');

        // use the processWorksheetDataChunk to run the code.
        $worksheet->processWorksheetDataChunk($args['forecast_by'], $args['data']);

        $this->job->succeedJob();
        return true;
    }

}
