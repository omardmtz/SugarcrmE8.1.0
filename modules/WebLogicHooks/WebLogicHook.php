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


class WebLogicHook extends SugarBean implements RunnableSchedulerJob
{
    public $id;
    public $name;
    public $webhook_target_module;
    public $request_method;
    public $url;
    public $trigger_event;

    public $table_name = 'weblogichooks';
    public $object_name = 'WebLogicHook';
    public $module_dir = 'WebLogicHooks';
    public $new_schema = true;
    public $importable = false;

    /**
     * @var $job the job object
     */
    protected $job;

    /**
     * Default Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    protected function getActionArray()
    {
        return array(1, $this->name, 'modules/WebLogicHooks/WebLogicHook.php', 'WebLogicHook', 'dispatchRequest', $this->id);
    }

    public function save($check_notify = false)
    {
        $hook = $this->getActionArray();
        if (!empty($this->fetched_row)) {
            $oldhook = $hook;
            // since remove_logic_hook compares 1, 3 and 4
            $oldhook[3] = 'WebLogicHook';
            remove_logic_hook($this->webhook_target_module, $this->trigger_event, $oldhook);
        }
        parent::save($check_notify);
        $hook[5] = $this->id;
        check_logic_hook_file($this->webhook_target_module, $this->trigger_event, $hook);
    }

    /**
     * Dispatch request.
     * @param SugarBean $seed a bean that fired event
     * @param string $event event name
     * @param array $arguments event arguments 
     * @param string $id web logic hook id
     */
    public function dispatchRequest(SugarBean $seed, $event, $arguments, $id)
    {
        $this->retrieve($id);
        if (empty($this->id)) {
            return;
        }

        $jobData = array(
            'url' => $this->url,
            'request_method' => $this->request_method,
            'payload' => $this->formatRequestData($seed, $event, $arguments)
        );

        $job = new SchedulersJob();
        $job->assigned_user_id = $this->created_by;
        $job->name = 'Dispatch Web Logic Hook';
        $job->status = SchedulersJob::JOB_STATUS_QUEUED;
        $job->target = 'class::' . get_class($this);
        $job->data = serialize($jobData);
        $job->execute_time = $GLOBALS['timedate']->nowDb();
        $job->save();
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
     * This method implements the run function of RunnableSchedulerJob and handles processing a SchedulersJob
     *
     * @param Mixed $data parameter passed in from the job_queue.data column when a SchedulerJob is run
     * @return bool true on success, false on error
     */
    public function run($data)
    {
        $data = unserialize($data);
        $payload = json_encode($data['payload']);

        $curlHandler = curl_init();

        $options = array(
            CURLOPT_HTTPHEADER      => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CONNECTTIMEOUT  => 5,
            CURLOPT_TIMEOUT         => 1,
            CURLOPT_MAXREDIRS       => 1,
            CURLOPT_USERAGENT       => 'SugarCrm',
            CURLOPT_VERBOSE         => false,
            CURLOPT_URL             => $data['url'],
            CURLOPT_HTTPHEADER      => array(
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($payload)
            ),
            CURLOPT_POSTFIELDS      => $payload,
            CURLOPT_CUSTOMREQUEST   => $data['request_method'],
        );

        curl_setopt_array($curlHandler, $options);

        if (false === curl_exec($curlHandler)) {
            $GLOBALS['log']->error('WebLogicHook failed: ' . curl_error($curlHandler));
        }

        curl_close($curlHandler);
        $this->job->succeedJob();
        return true;
    }

    public function mark_deleted($id)
    {
        if ($this->id != $id) {
            $this->retrieve($id);
            // does not exist - no need to delete
            if (empty($this->id)) {
                return;
            }
        }
        remove_logic_hook($this->webhook_target_module, $this->trigger_event, $this->getActionArray());
        parent::mark_deleted($id);
    }

    private function formatRequestData(SugarBean $bean, $event, array $arguments)
    {
        global $current_user;

        $data = array();
        $sfh = new SugarFieldHandler();

        $arguments['bean'] =  get_class($bean);

        if (isset($bean->id)) {
            $data['id'] = $bean->id;
        }

        if (!SugarACL::moduleSupportsACL($bean->webhook_target_module) || $bean->ACLAccess('detail')) {
            $fieldList = $bean->field_defs;

            $this->ACLFilterFieldList($fieldList, array(
                'bean' => $bean
            ));

            $service = new RestService();
            $service->user = $current_user;
            foreach ($fieldList as $fieldName => $properties) {
                $fieldType = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
                $field = $sfh->getSugarField($fieldType);
                if ('link' !== $fieldType && !empty($field) && (isset($bean->$fieldName)  || 'relate' === $fieldType)) {
                    $field->apiFormatField($data, $bean, array(), $fieldName, $properties, array(), $service);
                }
            }
        }

        $arguments['data'] = $this->decodeHTML($data);
        $arguments['dataChanges'] = $this->decodeHTML($arguments['dataChanges']);
        $arguments['event'] = $event;

        return $arguments;
    }

    private function decodeHTML($data)
    {
        $returnData = array();

        $db = DBManagerFactory::getInstance();
        foreach ($data as $key => $value) {
            $returnData[$key] = $db->decodeHTML($value);
            if (is_array($value)) {
                $returnData[$key] = $this->decodeHTML($value);
            }
        }

        return $returnData;
    }
}
