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
 * SugarJobCreateRevenueLineItems
 *
 * Class to run a job which will create the Revenue Line Items for all the Opportunities.
 *
 */
class SugarJobCreateRevenueLineItems extends JobNotification implements RunnableSchedulerJob
{

    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * The Label that will be used for the subject line
     *
     * @var string
     */
    protected $subjectLabel = 'LBL_JOB_NOTIFICATION_OPPS_WITH_RLIS_SUBJECT';

    /**
     * The Label that will be used for the body of the notification and email
     *
     * @var string
     */
    protected $bodyLabel = 'LBL_JOB_NOTIFICATION_OPPS_WITH_RLIS_BODY';

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
     * @param string $data The job data set for this particular Scheduled Job instance
     * @return boolean true if the run succeeded; false otherwise
     */
    public function run($data)
    {
        $settings = Opportunity::getSettings();

        if ((isset($settings['opps_view_by']) && $settings['opps_view_by'] !== 'RevenueLineItems')) {
            $GLOBALS['log']->fatal("Opportunity are not being used with Revenue Line Items. " . __CLASS__ . " should not be running");
            return false;
        }

        $args = json_decode(html_entity_decode($data), true);
        $this->job->runnable_ran = true;

        // use the processWorksheetDataChunk to run the code.
        SugarAutoLoader::load('modules/Opportunities/include/OpportunityWithRevenueLineItem.php');
        OpportunityWithRevenueLineItem::processOpportunityIds($args['data']);

        $this->job->succeedJob();
        $this->notifyAssignedUser();
        return true;
    }
}
