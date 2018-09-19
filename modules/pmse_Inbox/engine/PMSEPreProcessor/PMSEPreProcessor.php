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


use Sugarcrm\Sugarcrm\ProcessManager;
use Sugarcrm\Sugarcrm\ProcessManager\Registry;

class PMSEPreProcessor
{
    /**
     *
     * @var type
     */
    private static $instance;

    /**
     *
     * @var type
     */
    private $executer;

    /**
     *
     * @var PMSEValidator
     */
    private $validator;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     *
     * @var type
     */
    protected $caseFlowHandler;

    /**
     *
     * @var type
     */
    protected $evaluator;

    /**
     * Internal cache of flowData process ids to project ids. This will be used
     * in setting proper source subject information.
     * @var array
     */
    protected $flowProjectMap = [];

    /**
     * Pre Processor constructor method
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        $this->executer = ProcessManager\Factory::getPMSEObject('PMSEExecuter');
        $this->validator = ProcessManager\Factory::getPMSEObject('PMSEValidator');
        $this->caseFlowHandler = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
        $this->logger = PMSELogger::getInstance();
    }

    /**
     *
     * @return PMSEPreProcessor
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new PMSEPreProcessor();
        }
        return self::$instance;
    }

    /**
     * @param string $module
     * @param string $id
     * @codeCoverageIgnore
     * @return null|SugarBean
     */
    public function retrieveBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }

    /**
     *
     * @param type $module
     * @param type $id
     * @codeCoverageIgnore
     */
    public function retrieveRequest($module, $id = null)
    {
        return ProcessManager\Factory::getPMSEObject('PMSERequest');
    }

    /**
     *
     * @param type $module
     * @param type $id
     * @codeCoverageIgnore
     */
    public function retrieveSugarQuery()
    {
        return new SugarQuery();
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getExecuter()
    {
        return $this->executer;
    }

    /**
     *
     * @param type $executer
     * @codeCoverageIgnore
     */
    public function setExecuter($executer)
    {
        $this->executer = $executer;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     *
     * @param PMSELogger $logger
     * @codeCoverageIgnore
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param PMSEValidate $validator
     * @codeCoverageIgnore
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * Sets subject data into the registry so that the bean save call can use it
     * @param array $flowData
     */
    private function setSubjectData(array $flowData)
    {
        // Store prj_id, pro_id, bpmn_id and bpmn_type in the registry for later
        // use. prj_id is only set in flowData that comes from getAllEvents, so
        // we will more often than not need to get it from the pro_id of the flow
        // row
        if (isset($flowData['prj_id'])) {
            $project = $flowData['prj_id'];
        } else {
            // If we've already fetched this, don't do it again
            // pro_id should always be set
            if (isset($this->flowProjectMap[$flowData['pro_id']])) {
                $project = $this->flowProjectMap[$flowData['pro_id']];
            } else {
                $q = $this->retrieveSugarQuery();
                $q->from(BeanFactory::newBean('pmse_Project'), array('alias' => 'p'));
                $q->select(array(array('id', 'p')));
                $q->joinTable('pmse_bpm_process_definition', array('alias' => 'd'))
                    ->on()->equalsField('d.prj_id', 'p.id');
                $q->where()->equals('d.id', $flowData['pro_id']);
                $projectId = $q->getOne();

                if (isset($projectId)) {
                    // Cache it and write it
                    $project = $this->flowProjectMap[$flowData['pro_id']] = $projectId;
                } else {
                    // Just default to nothing... realistically, this should never
                    // happen
                    $project = '';
                }
            }
        }

        // What to store
        $store = [
            'project_id' => $project,
            'definition_id' => $flowData['pro_id'],
            'element_id' => $flowData['bpmn_id'],
            'element_type' => str_replace('bpmn', '', $flowData['bpmn_type']),
        ];

        // Store it
        Registry\Registry::getInstance()->set('process_attributes', $store, true);
    }

    /**
     * Processes a request
     * @param PMSERequest $request
     * @return boolean
     */
    public function processRequest(PMSERequest $request)
    {
        // Default the return
        $result = true;

        // Handle terminations first
        if ($request->getExternalAction() == 'TERMINATE_CASE') {
            $result = $this->terminateCaseByBeanAndProcess($request->getBean());
        } else {
            // Now handle actual processing of the request
            $flowDataList = $this->getFlowDataList($request);

            // Set the start time, outside of the loop since that is where it belongs
            Registry\Registry::getInstance()->set('pmse_start_time', microtime(true));

            // Loop the flowdata list and handle the actions necessary
            foreach ($flowDataList as $flowData) {
                // For handling Security Subject saving later on
                $this->setSubjectData($flowData);

                // Make sure we start fresh each time with validation and such
                $request->reset();

                // Process the flow data and also the bean object data
                $request->setFlowData($this->processFlowData($flowData));

                // Make sure we have a bean to work with
                $bean = $request->getBean();
                if (!$bean) {
                    // Try to get the bean from the flow data since we don't have
                    // one on the request
                    $bean = $this->processBean($bean, $flowData);
                    if (!$bean) {
                        // This should maybe mark the flow as completed or something
                        // since this flow will now live on in perpetuity. For now
                        // this is ok as realistically, this should never be the case
                        continue;
                    }
                }

                // Set the bean back onto the request for use in the validators
                $request->setBean($bean);

                // It's essential that the request should be initialized as valid
                // for the next flow. This does not actually validate anything,
                // it simply marks the request as valid to begin.
                $request->validate();

                // This does the actual validation of the flow data and request
                $validatedRequest = $this->validator->validateRequest($request);

                if ($validatedRequest->isValid()) {
                    $data = $validatedRequest->getFlowData();

                    if (!(isset($data['evn_type']) && $data['evn_type'] == 'GLOBAL_TERMINATE')) {
                        if (!PMSEEngineUtils::isTargetModule($flowData, $validatedRequest->getBean())) {
                            $parentBean = PMSEEngineUtils::getParentBean($flowData, $validatedRequest->getBean());
                            // Only when start bean is different of target module in PD
                            // should override original bean
                            $request->setBean($parentBean);
                        }

                        // Set the engine runner arguments
                        $exFlowData = $validatedRequest->getFlowData();
                        $exCreateThread = $validatedRequest->getCreateThread();
                        $exBean = $validatedRequest->getBean();
                        $exExternalAction = $validatedRequest->getExternalAction();
                        $exArguments = $validatedRequest->getArguments();

                        // Run the executer and capture the result
                        $res = $this->executer->runEngine(
                            $exFlowData,
                            $exCreateThread,
                            $exBean,
                            $exExternalAction,
                            $exArguments
                        );

                        // Stack the results for use later
                        $result = $result && $res;
                    }
                } else {
                    // We need this for the log message
                    $data = $request->getFlowData();

                    // Parse a log message
                    $msg = sprintf(
                        'Request not validated for element %s with id %s',
                        $data['bpmn_type'],
                        $data['bpmn_id']
                    );

                    // Log it
                    $this->logger->info($msg);

                    // Set the return value
                    $result = false;
                }

                if ($request->getResult() == 'TERMINATE_CASE') {
                    $result = $this->terminateCaseByBeanAndProcess($request->getBean(), $data);
                }
            }

            // Clear validator caches AFTER the loop completes so that the cache
            // is clear for future iterations
            $this->validator->clearValidatorCaches();
        }

        return $result;
    }

    public function retrieveCasesByBean($bean)
    {
        $sugarQuery = $this->retrieveSugarQuery();
        $flowBean = $this->retrieveBean('pmse_BpmFlow');
        $fields = array('cas_id', 'cas_index', 'pro_id');
        $sugarQuery->select($fields);
        $sugarQuery->from($flowBean, array('alias' => 'flow'));
        $sugarQuery->joinTable('pmse_bpm_process_definition', array(
            'alias' => 'definition',
        ))->on()->equalsField('definition.id', 'flow.pro_id');
        $sugarQuery->where()->queryAnd()
            ->addRaw("cas_sugar_object_id = '{$bean->id}' AND cas_sugar_module = '{$bean->module_name}' AND cas_flow_status <> 'CLOSED'");
        $sugarQuery->select->fieldRaw('definition.pro_terminate_variables');
        $flows = $sugarQuery->execute();
        return $flows;
    }

    public function retrieveProcessBean($bean, $flowData = array())
    {
        if (!PMSEEngineUtils::isTargetModule($flowData, $bean)) {
            $parentBean = PMSEEngineUtils::getParentBean($flowData, $bean);
        }
        return (!empty($parentBean) && is_object($parentBean)) ? $parentBean: $bean;
    }

    /**
     * Terminates a case by bean and process id
     * @param SugarBean $bean
     * @param array $data Flow data
     * @return boolean
     */
    public function terminateCaseByBeanAndProcess(SugarBean $bean, array $data = array())
    {
        // Gets the target module bean or the its parent
        $processBean = $this->retrieveProcessBean($bean, $data);

        // Gets flow data for a given record
        $flows = $this->retrieveCasesByBean($processBean);

        // Needed for checking inside the loop, but doesn't need to be check for
        // each iteration
        $isEmpty = empty($data);

        // Stack holder for what has been terminated already
        $needsTerm = array();

        // Loop and check
        foreach ($flows as $flow) {
            if ($isEmpty || $flow['pro_id'] == $data['pro_id']) {
                // If we haven't terminated this one yet, mark it as needed
                if (!isset($needsTerm[$flow['cas_id']])) {
                    $needsTerm[$flow['cas_id']] = true;
                }

                // If this case id needs to be terminated, terminate it
                if ($needsTerm[$flow['cas_id']]) {
                    $this->caseFlowHandler->terminateCase($flow, $processBean, 'TERMINATED');

                    // Then mark it as not needing check
                    $needsTerm[$flow['cas_id']] = false;
                }
            }
        }

        // This isn't exactly accurate, but its better than returning nothing.
        return true;
    }

    /**
     * Gets a list of valid links for a module name. This is used to filter the
     * flow list of unnecessary relationships
     * @param string $module The name of the module to get links for
     * @return array
     */
    public function getValidLinks($module)
    {
        // Default the return
        $return = [];

        $db = DBManagerFactory::getInstance();

        // Get our related link names/module names
        $sql = "
            SELECT
                DISTINCT rel_element_relationship, rel_element_module, pro_module
            FROM
                pmse_bpm_related_dependency rd
            LEFT JOIN
                pmse_bpm_flow flow ON rd.rel_element_id = flow.bpmn_id AND
                (flow.cas_flow_status IS NULL OR flow.cas_flow_status='WAITING')
            WHERE
                rd.deleted = 0 AND rd.pro_status != 'INACTIVE' AND " .
                $db->getNotEmptyFieldSQL('rd.rel_element_relationship') . " AND
                (rd.pro_module = :module OR rd.rel_element_module = :module)
        ";

        // Execute
        $stmt = $db->getConnection()
                ->executeQuery($sql, [':module' => $module]);

        // Loop and compare
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['rel_element_relationship'] != $module) {
                $seed = BeanFactory::newBean($row['pro_module']);
                $linkName = $row['rel_element_relationship'];
                if ($seed->load_relationship($linkName)) {
                    // Linkname should not be used as an index since there is a
                    // chance that link names are not unique across modules
                    $return[] = $seed->$linkName->getLinkForOtherSide();
                }
            }
        }

        return $return;
    }


    /**
     * Gets a list of related object ids based on a bean and a list of link names.
     * Optionally, will wrap the ids in DB quotes when needed
     * @param SugarBean $bean The primary bean
     * @param array $links Array of link names to load for the bean
     * @param boolean $quoted Whether to DB escape the results
     * @return array
     */
    protected function buildLinkedObjectIdList(SugarBean $bean, array $links = array(), $quoted = true)
    {
        // The bean will always be in the list of linked objects
        $list = [$bean->id];

        // Now loop over the link array and see if we need to add linked ids to
        // the list
        foreach ($links as $link) {
            if ($bean->load_relationship($link)) {
                $list = array_merge($list, $bean->$link->get());
            }
        }

        // For DB comparisons, we need to quote our values
        if ($quoted) {
            $db = DBManagerFactory::getInstance();
            $list = implode(",", array_map(
                function ($val) use ($db) {
                    return $db->quoted($val);
                },
                $list
            ));
        }

        return $list;
    }

    /**
     * Optimized version of get all events method.
     * @param SugarBean $bean
     * @return array
     */
    public function getAllEvents(SugarBean $bean)
    {
        // If the bean is not a bean at this point, just return an array
        if (empty($bean)) {
            return array();
        }

        // We'll need this for a few things here
        $moduleName = $bean->getModuleName();

        // Get our list of links to filter our flows by
        $links = $this->getValidLinks($moduleName);

        // Build a list of object ids that will work for our flows
        $objectIds = $this->buildLinkedObjectIdList($bean, $links);

        $evnParamsChar = $bean->db->convert('rd.evn_params', 'text2char');
        $sql = "
        SELECT
            rd.evn_id, rd.evn_uid, rd.prj_id, rd.pro_id, rd.evn_type,
            rd.evn_marker, rd.evn_is_interrupting, rd.evn_attached_to,
            rd.evn_cancel_activity, rd.evn_activity_ref,
            rd.evn_wait_for_completion, rd.evn_error_name,
            rd.evn_error_code, rd.evn_escalation_name,
            rd.evn_escalation_code, rd.evn_condition, rd.evn_message,
            rd.evn_operation_name, rd.evn_operation_implementation,
            rd.evn_time_date, rd.evn_time_cycle, rd.evn_time_duration,
            rd.evn_behavior, rd.evn_status, rd.evn_module,
            rd.evn_criteria, rd.evn_params, rd.evn_script,
            rd.rel_process_module, rd.rel_element_relationship,
            rd.rel_element_module, rd.pro_module, rd.pro_status,
            rd.pro_locked_variables, rd.pro_terminate_variables, rd.date_entered,
            flow.id, flow.cas_id, flow.cas_index, flow.bpmn_id, flow.bpmn_type,
            flow.cas_user_id, flow.cas_thread, flow.cas_sugar_module,
            flow.cas_sugar_object_id, flow.cas_flow_status
        FROM
            pmse_bpm_related_dependency rd
        LEFT JOIN
            pmse_bpm_flow flow ON rd.rel_element_id = flow.bpmn_id AND
            (flow.cas_flow_status IS NULL OR flow.cas_flow_status='WAITING')
        WHERE
            rd.deleted = 0 AND rd.pro_status != 'INACTIVE' AND (
                (
                    (rd.evn_type = 'START' AND rd.evn_module = :module) OR
                    (
                        rd.evn_type = 'GLOBAL_TERMINATE' AND
                        (flow.cas_flow_status IS NULL OR flow.cas_flow_status != 'WAITING') AND
                        rd.rel_element_module = :module AND
                        (flow.cas_sugar_object_id IS NULL OR flow.cas_sugar_object_id IN ($objectIds))
                    ) OR
                    (
                        rd.evn_type = 'INTERMEDIATE' AND
                        rd.evn_marker = 'MESSAGE' AND
                        rd.evn_behavior = 'CATCH' AND
                        flow.cas_flow_status = 'WAITING' AND
                        rd.rel_element_module = :module AND
                        (flow.cas_sugar_object_id IS NULL OR flow.cas_sugar_object_id IN ($objectIds))
                    )
                )
            )
            ORDER BY (
                CASE $evnParamsChar
                WHEN 'new' THEN 1
                WHEN 'updated' THEN 2
                ELSE 3
                END
            ) ASC, rd.date_entered ASC
        ";

        // Execute the query and get our results
        $stmt = DBManagerFactory::getInstance()
                ->getConnection()
                ->executeQuery($sql, [':module' => $moduleName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param type $data
     * @return type
     * @codeCoverageIgnore
     */
    public function getFlowById($id)
    {
        $flow = $this->retrieveBean('pmse_BpmFlow', $id);
        return array($flow->toArray());
    }

    /**
     * Gets flows by case id. This particular method only gets ERROR state flows.
     * @param int $casId
     * @return array
     */
    public function getFlowsByCasId($casId)
    {
        // Set our fields to select
        $select = [
            'id', 'deleted', 'assigned_user_id', 'cas_id', 'cas_index', 'pro_id',
            'pcas_previous', 'cas_reassign_level', 'bpmn_id', 'bpmn_type',
            'cas_user_id', 'cas_thread', 'cas_flow_status', 'cas_sugar_module',
            'cas_sugar_object_id', 'cas_sugar_action', 'cas_adhoc_type',
            'cas_task_start_date', 'cas_delegate_date', 'cas_start_date',
            'cas_finish_date', 'cas_due_date', 'cas_queue_duration', 'cas_duration',
            'cas_delay_duration', 'cas_started', 'cas_finished', 'cas_delayed',
        ];

        // Set up SugarQuery
        $q = new SugarQuery();
        $q->select($select);
        $q->from(BeanFactory::getBean('pmse_BpmFlow'));

        // Ensure proper order of data returned
        $q->orderBy('date_entered', 'ASC');

        // Handle the filtering
        $q->where()
          ->queryAnd()
          ->equals('cas_id', $casId)
          ->equals('cas_flow_status', 'ERROR');

        // Return the result
        return $q->execute();
    }

    /**
     *
     * @param PMSERequest $request
     * @return array
     */
    public function getFlowDataList(PMSERequest $request)
    {
        $args = $request->getArguments();
        $flows = array();
        switch ($request->getType()) {
            case 'direct':
                switch (true) {
                    case isset($args['idFlow']):
                        $flows = $this->getFlowById($args['idFlow']);
                        break;
                    case isset($args['flow_id']):
                        $flows = $this->getFlowById($args['flow_id']);
                        break;
                    case (isset($args['cas_id'])&&isset($args['cas_index'])):
                        $flows = $this->getFlowByCasIdCasIndex($args);
                        $args['idFlow'] = $flows[0]['id'];
                        $request->setArguments($args);
                        break;
                }

                break;
            case 'hook':
                $flows = $this->getAllEvents($request->getBean());
                break;
            case 'queue':
                $flows = $this->getFlowById($args['id']);
                break;
            case 'engine':
                $flows = $this->getFlowsByCasId($args['cas_id']);
                break;
        }

        return $flows;
    }

    public function getFlowByCasIdCasIndex($arguments)
    {
        $tmpBean = BeanFactory::newBean('pmse_BpmFlow');
        $q = new SugarQuery();
        $q->select(array('cas_sugar_module', 'cas_sugar_object_id', 'id'));
        $q->from($tmpBean);
        $q->where()->equals('cas_id', $arguments['cas_id']);
        $q->where()->equals('cas_index', $arguments['cas_index']);
        $result = $q->execute();
        $element = array_pop($result);
        $bean = BeanFactory::retrieveBean('pmse_BpmFlow', $element['id']);
        return array($bean->toArray());
    }

    /**
     *
     * @param type $flowData
     * @return type
     * @codeCoverageIgnore
     */
    public function processFlowData($flowData)
    {
        //TODO: Find a better and more generalistic approach
        $flowData['bpmn_id'] = (!isset($flowData['bpmn_id'])) ? $flowData['evn_id'] : $flowData['bpmn_id'];
        $flowData['bpmn_type'] = (!isset($flowData['bpmn_type'])) ? 'bpmnEvent' : $flowData['bpmn_type'];
        return $flowData;
    }

    /**
     * Instantiates the correct bean if a bean is not presented and the necessary
     * data exists on the flow data row
     * @param SugarBean $bean The SugarBean object... could be null
     * @param array $flowData
     * @return SugarBean|null
     * @codeCoverageIgnore
     */
    public function processBean($bean, $flowData)
    {
        if (is_null($bean) && isset($flowData['cas_sugar_module'], $flowData['cas_sugar_object_id'])) {
            $bean = BeanFactory::getBean($flowData['cas_sugar_module'], $flowData['cas_sugar_object_id']);
        }

        return $bean;
    }

}
