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
 * SugarJobCreateRevenueLineItemNotes
 *
 * Class to run a job which will create the Revenue Line Items for all the Opportunities.
 *
 */
class SugarJobCreateRevenueLineItemNotes extends JobNotification implements RunnableSchedulerJob
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
    protected $subjectLabel = 'LBL_JOB_NOTIFICATION_RLI_NOTE_SUBJECT';

    /**
     * The Label that will be used for the body of the notification and email
     *
     * @var string
     */
    protected $bodyLabel = 'LBL_JOB_NOTIFICATION_RLI_NOTE_BODY';

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

        if ((isset($settings['opps_view_by']) && $settings['opps_view_by'] !== 'Opportunities')) {
            $GLOBALS['log']->fatal("Opportunity are being used with Revenue Line Items. " . __CLASS__ . " should not be running");
            return false;
        }

        $args = json_decode(html_entity_decode($data), true);
        $this->job->runnable_ran = true;

        $labels = $args['labels'];
        $data = $args['chunk'];

        $currencies = array();

        Activity::disable();
        // disable the fts index as well
        $ftsSearch = \Sugarcrm\Sugarcrm\SearchEngine\SearchEngine::getInstance();
        $ftsSearch->setForceAsyncIndex(true);

        foreach ($data as $opp_id => $rli_data) {
            /* @var $opp Opportunity */
            $opp = BeanFactory::getBean('Opportunities', $opp_id);
            /* @var $note Note */
            $note = BeanFactory::newBean('Notes');

            $note->parent_id = $opp_id;
            $note->parent_type = 'Opportunities';
            $note->assigned_user_id = $opp->assigned_user_id;
            $note->created_by = $opp->created_by;
            $note->name = 'Previous Associated Revenue Line Items';

            $desc = '';

            foreach ($rli_data as $rli) {
                $desc .= $rli['name'] . "\n";
                foreach ($rli as $field => $value) {
                    if (isset($labels[$field])) {
                        if ($field === 'currency_id') {
                            if (!isset($currencies[$value])) {
                                $currencies[$value] = SugarCurrency::getCurrencyByID($value);
                            }
                            $desc .= " - " . $labels[$field] . ": " . $currencies[$value]->name . "\n";
                        } elseif ($field !== 'name' && $field !== 'opportunity_id') {
                            $desc .= " - " . $labels[$field] . ": " . $value . "\n";
                        }
                    }
                }

                $desc .= "\n\n";
            }

            $note->description = trim($desc);

            $note->save();
        }

        // set it back to the default value from the config.
        $ftsSearch->setForceAsyncIndex(
            SugarConfig::getInstance()->get('search_engine.force_async_index', false)
        );
        Activity::restoreToPreviousState();

        $this->job->succeedJob();
        $this->notifyAssignedUser();
        return true;
    }
}
