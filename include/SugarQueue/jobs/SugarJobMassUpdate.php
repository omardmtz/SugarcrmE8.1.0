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
use Sugarcrm\Sugarcrm\ProcessManager\Registry;

/**
 * @api
 */
class SugarJobMassUpdate implements RunnableSchedulerJob
{

    /**
     * the ids of the child jobs
     */
    protected $workJobIds = array();

    /**
     * @var int number of records to be updated/deleted at one time
     */
    protected $chunkSize = 500;

    /**
     * constructor
     */
    public function __construct()
    {
        //TODO: Creation of Activities are turned off for mass update.
        //TODO: It will be turned on when job queue, asynchronous processing, activity Stream performance has been handled after 7.0
        Activity::disable();

        if (!empty($GLOBALS['sugar_config']['mass_actions']['mass_update_chunk_size'])) {
            $this->chunkSize = $GLOBALS['sugar_config']['mass_actions']['mass_update_chunk_size'];
        }
    }

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
     * Setting global variables expected by down stream classes (MassUpdate, SearchForm2, etc)
     *
     */
    public static function preProcess($mu_params)
    {
        // classes downstream rely heavily on $_POST and $_REQUEST
        // until we rewrite the whole thing, we need to modify $_POST and $_REQUEST for mass update to work
        $_POST = array_merge($_POST, $mu_params);
        $_REQUEST['massupdate'] = true;
        if (isset($mu_params['uid'])) {
            if (is_array($mu_params['uid'])) {
                $_REQUEST['uid'] = implode(',', $mu_params['uid']);
            } else {
                $_REQUEST['uid'] = $mu_params['uid'];
            }
        }
        if (!empty($mu_params['entire'])) {
            $_REQUEST['entire'] = $mu_params['entire'];
            $_REQUEST['select_entire_list'] = 1;
        }
    }

    /**
     * Create a job queue consumer for mass update
     *
     * @param Mixed $data job queue data
     * @param String $jobType job type - 'init' for parent job, 'work' for child job
     * @return String id of the created job
     */
    public function createJobQueueConsumer($data, $jobType = "init")
    {
        $job = new SchedulersJob();
        $job->name = 'MassUpdate_'.$jobType;
        $job->target = "class::SugarJobMassUpdate";

        $data['_jobType_'] = $jobType;
        $job->data = json_encode($data);

        $job->assigned_user_id = $GLOBALS['current_user']->id;

        $queue = new SugarJobQueue();
        $queue->submitJob($job);

        return $job->id;
    }


    /**
     * Main function that handles the asynchronous massupdate.
     *
     * @param $data job queue data
     */
    public function run($data)
    {
        /*
          - type:init
            - perform filter to get all records to be updated, including id
            - create child jobs (type=work), each job has up to $this->chunkSize records
          - type:work
            - do update/delete
         */

        $data = json_decode(from_html($data), true);

        if (empty($data) || !is_array($data) || empty($data['_jobType_'])) {
            $this->job->failJob('Invalid job data.');
            return false;
        }

        switch ($data['_jobType_'])
        {
            // this is the parent job, find out all the records to be updated and create child jobs
            case 'init':

                // if uid is already provided, use them
                if (isset($data['uid'])) {
                    if (!is_array($data['uid'])) {
                        $data['uid'] = explode(',', $data['uid']);
                    }

                    $uidChunks = array_chunk($data['uid'], $this->chunkSize);
                    foreach ($uidChunks as $chunk) {
                        $tmpData = $data;
                        $tmpData['uid'] = $chunk;
                        $this->workJobIds[] = $this->createJobQueueConsumer($tmpData, 'work');
                    }
                }
                // if updating entire list, use filter
                else if (!empty($data['entire'])) {
                    // call filter api to get the ids then create a job queue for each chunk
                    $filterApi = new FilterApi();
                    $api = new RestService();
                    $api->user = $GLOBALS['current_user'];
                    $nextOffset = 0;
                    $filterArgs = array('module'=>$data['module'], 'fields'=>'id');
                    if (isset($data['filter'])) {
                        $filterArgs['filter'] = $data['filter'];
                    }
                    $uidArray = array();

                    // max_num does not need to set to chunkSize, it can be any size that makes sense
                    $filterArgs['max_num'] = $this->chunkSize;

                    // start getting all the ids
                    while ($nextOffset != -1) { // still have records to be fetched
                        $filterArgs['offset'] = $nextOffset;
                        $result = $filterApi->filterList($api, $filterArgs);
                        $nextOffset = $result['next_offset'];
                        foreach ($result['records'] as $record) {
                            if (!empty($record['id'])) {
                                $uidArray[] = $record['id'];
                            }
                        }
                        // create one child job for each chunk
                        if (count($uidArray)) {
                            $uidChunks = array_chunk($uidArray, $this->chunkSize);
                            foreach ($uidChunks as $chunk) {
                                $tmpData = $data;
                                $tmpData['uid'] = $chunk;
                                $this->workJobIds[] = $this->createJobQueueConsumer($tmpData, 'work');
                            }
                        }
                    }
                } else {
                    $this->job->failJob('Neither uid nor entire specified.');
                    return false;
                }

                $this->job->succeedJob('Child jobs created.');

                // return the ids of the child jobs that have been created
                return $this->workJobIds;

            // this is the child job, do update
            case 'work':
                return $this->workJob($this->job, $data);

            default:
                break;
        }

        return true;
    }

    /**
     *  Update records and mark
     *
     * @param $job SchedulersJob object associated with this job
     * @param $data array of job data
     */
    protected function workJob($job, $data)
    {
        if (!isset($data['uid'])) {
            $job->failJob('No uid found.');
            return false;
        }

        // mass update
        $newData = $data;
        if (isset($newData['entire'])) {
            unset($newData['entire']);
        }

        try {
            $this->runUpdate($data);
        } catch (Exception $e) {
            $job->failJob($e->getMessage());
            return false;
        }

        $job->succeedJob('All records processed for this chunk.');

        return true;
    }

    /**
     *  Update records.
     *
     * @param $data array of job data
     */
    public function runUpdate($data)
    {
        // Get the data down to just the list of fields
        $module = $data['module'];
        unset($data['module']);
        $action = $data['action'];
        unset($data['action']);
        $ids = is_array($data['uid'])?$data['uid']:array();
        unset($data['uid']);
        unset($data['filter']);
        unset($data['entire']);
        $prospectLists = isset($data['prospect_lists'])?$data['prospect_lists']:array();
        unset($data['prospect_lists']);

        $seed = BeanFactory::newBean($module);
        $fakeApi = new RestService();
        $fakeApi->user = $GLOBALS['current_user'];
        $helper = ApiHelper::getHelper($fakeApi, $seed);

        $failed = 0;
        foreach ($ids as $id) {
            // Doing a full retrieve because we are writing we may need dependent fields for workflow that we don't know about
            $bean = BeanFactory::retrieveBean($module,$id);
            if ($bean == null) {
                // Team permissions may have changed, or a deletion, we won't hold it against them
                continue;
            }

            if (!$bean->aclAccess($action)) {
                // ACL's might not let them modify this bean, but we should still do the rest
                continue;
            }

            if ($action == 'delete') {
                $bean->mark_deleted($id);
                continue;
            }

            try {
                $errors = $helper->populateFromApi($bean, $data, array('massUpdate'=>true));
                $check_notify = $helper->checkNotify($bean);
                // Before calling save, we need to clear out any existing AWF
                // triggered start events so they can continue to trigger.
                Registry\Registry::getInstance()->drop('triggered_starts');
                $bean->save($check_notify);
            } catch (SugarApiException $e) {
                // ACL's might not let them modify this bean, but we should still do the rest
                $failed++;
                continue;
            }
        }

        if (count($prospectLists) > 0) {

            $massupdate = new MassUpdate();

            foreach ($prospectLists as $listId) {
                if ($action == 'save') {
                    $success = $massupdate->add_prospects_to_prospect_list($module, $listId, $ids);
                } else {
                    $success = $massupdate->remove_prospects_from_prospect_list($module, $listId, $ids);
                }
            }
            if (!$success) {
                $GLOBALS['log']->error("Could not add prospects to prospect list, could not find a relationship to the ProspectLists module.");
            }
        }

        return array(
            'failed' => $failed,
        );
    }

}
