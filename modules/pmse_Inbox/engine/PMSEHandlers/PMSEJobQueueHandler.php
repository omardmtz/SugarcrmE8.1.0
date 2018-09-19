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
 * Description of PMSEJobQueue
 * The PMSEJobQueue class creates and assign a queued job that executes a BPM
 * element such as a gateway, activity or event.
 *
 */
class PMSEJobQueueHandler extends PMSEAbstractRequestHandler
{
    /**
     * @inheritDoc
     */
    protected $requestType = 'queue';

    /**
     * List of valid fields for a process
     * @var array
     */
    protected $validFields = array(
        'evn_criteria',
        'rel_element_module',
        'rel_element_relationship',
        'rel_process_module',
        'new_with_id',
        'cas_delayed',
        'cas_finished',
        'cas_started',
        'cas_delay_duration',
        'cas_duration',
        'cas_queue_duration',
        'cas_due_date',
        'cas_finish_date',
        'cas_start_date',
        'cas_delegate_date',
        'cas_sugar_action',
        'cas_sugar_object_id',
        'cas_sugar_module',
        'cas_flow_status',
        'cas_thread',
        'cas_user_id',
        'bpmn_type',
        'bpmn_id',
        'pro_id',
        'cas_id',
        'cas_index',
        'id',
    );

    /**
     * Retrieve the Scheduler Job object
     * @return SchedulersJob
     * @codeCoverageIgnore
     */
    public function getSchedulersJob()
    {
        return BeanFactory::newBean('SchedulersJobs');
    }

    /**
     * Retrieve the Sugar Job Queue object
     * @return SugarJobQueue
     * @codeCoverageIgnore
     */
    public function getSugarJobQueue()
    {
        return new SugarJobQueue();
    }

    /**
     * Get the current user
     * @return User
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        global $current_user;
        return $current_user;
    }

    /**
     * Submit a Job top the Sugar job queue handler
     * @param Object $params
     * @return string
     */
    public function submitPMSEJob($params)
    {
        // Grab our jobber
        $job = $this->getSchedulersJob();

        // Set some properties now
        $job->name = "PMSE Job - {$params->id}";

        //data we are passing to the job
        $job->data = json_encode($this->filterData($params->data));

        //function to call
        $job->target = "function::PMSEJobRun";
        $job->message = "Executing a PMSE queued task.";

        //set the user the job runs as
        $job->assigned_user_id = $this->getCurrentUser()->id;

        //push into the queue to run
        return $this->getSugarJobQueue()->submitJob($job);
    }

    /**
     * Filters process data against a list of valid fields
     * @param array $dataArray Process data array
     * @return array
     */
    public function filterData($dataArray)
    {
        foreach ($dataArray as $key => $value) {
            if (!in_array($key, $this->validFields)) {
                unset($dataArray[$key]);
            }
        }

        return $dataArray;
    }
}
