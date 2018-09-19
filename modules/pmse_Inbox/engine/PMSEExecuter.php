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

class PMSEExecuter
{
    /**
     *
     * @var PMSECaseFlowHandler
     */
    protected $caseFlowHandler;

    /**
     *
     * @var PMSEFlowRouter
     */
    protected $flowRouter;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     *
     * @var int
     */
    protected $maxExecutionTimeout;

    /**
     *
     * @var int
     */
    protected $maxExecutionCycleNumber;

    /**
     *
     * @var array
     */
    protected $executedElements;

    /**
     *
     * @var array
     */
    protected $executionTime;

    /**
     * PMSEExecuter constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        global $sugar_config;
        $this->caseFlowHandler = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
        $this->flowRouter = ProcessManager\Factory::getPMSEObject('PMSEFlowRouter');
        $this->logger = PMSELogger::getInstance();

        $settings = $sugar_config['pmse_settings_default'];
        $this->maxExecutionCycleNumber = (int)$settings['error_number_of_cycles'];
        $this->maxExecutionTimeout = (int)$settings['error_timeout'];
        $this->executedElements = array();
        $this->executionTime = 0;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getCaseFlowHandler()
    {
        return $this->caseFlowHandler;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getFlowRouter()
    {
        return $this->flowRouter;
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
     * @param type $caseFlowHandler
     * @codeCoverageIgnore
     */
    public function setCaseFlowHandler($caseFlowHandler)
    {
        $this->caseFlowHandler = $caseFlowHandler;
    }

    /**
     *
     * @param type $flowRouter
     * @codeCoverageIgnore
     */
    public function setFlowRouter($flowRouter)
    {
        $this->flowRouter = $flowRouter;
    }

    /**
     *
     * @param type $logger
     * @codeCoverageIgnore
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param type $elementName
     * @return PMSEElement
     * @codeCoverageIgnore
     */
    public function retrievePMSEElement($elementName = '')
    {
        return ProcessManager\Factory::getElement($elementName);
    }

    /**
     *
     * @param type $flowData
     * @return type
     */
    public function retrieveElementByType($flowData)
    {
        switch ($flowData['bpmn_type']) {
            case 'bpmnActivity':
                $bpmnElement = $this->retrieveActivityElement($flowData['bpmn_id']);
                break;
            case 'bpmnEvent':
                $bpmnElement = $this->retrieveEventElement($flowData['bpmn_id']);
                break;
            case 'bpmnGateway':
                $bpmnElement = $this->retrieveGatewayElement($flowData['bpmn_id']);
                break;
            case 'bpmnFlow':
                $bpmnElement = $this->retrieveFlowElement($flowData['bpmn_id']);
                break;
            default:
                $bpmnElement = $this->retrievePMSEElement();
                break;
        }

        return $bpmnElement;
    }

    /**
     * Gets a proper PMSEElement class name from a script type or activity type
     * @param string $base The string to manipulate
     * @return string
     */
    protected function getElementName($base)
    {
        // Empty is invalid
        if (empty($base)) {
            return 'InvalidElement';
        }

        // Assign Team breaks convention
        if ($base === 'ASSIGN_TEAM') {
            return 'RoundRobin';
        }

        // Return a ACTION_TYPE to ActionType conversion
        return preg_replace_callback(
            '/_(.?)/',
            function ($data) {
                return strtoupper($data[1]);
            },
            ucfirst(strtolower($base))
        );
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function retrieveActivityElement($id)
    {
        $bpmnBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnActivity');
        $definitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmActivityDefinition');
        $bpmnBean->retrieve($id);
        $definitionBean->retrieve($id);

        // Empty element will load a PMSEElement by default
        $elementName = '';
        switch ($bpmnBean->act_task_type) {
            case 'SCRIPTTASK':
                $elementName = $this->getElementName($bpmnBean->act_script_type);
                break;
            case 'USERTASK':
                $elementName = 'UserTask';
                break;
        }

        $bpmElement = $this->retrievePMSEElement($elementName);
        $bpmElement->setExecutionMode($definitionBean->execution_mode);
        return $bpmElement;
    }

    /**
     *
     * @param type $id
     * @return boolean
     * @codeCoverageIgnore
     */
    public function retrieveEventElement($id)
    {
        $bpmnBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnEvent');
        $definitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmEventDefinition');
        $bpmnBean->retrieve($id);
        $definitionBean->retrieve($id);

        $bpmElement = false;

        switch ($bpmnBean->evn_type) {
            case 'START':
                $bpmElement = $this->retrievePMSEElement('PMSEStartEvent');
                break;
            case 'INTERMEDIATE':
                switch ($bpmnBean->evn_marker) {
                    case 'MESSAGE':
                        $bpmElement = $bpmnBean->evn_behavior === 'THROW' ? $this->retrievePMSEElement('PMSESendMessageEvent') : $this->retrievePMSEElement('PMSEReceiveMessageEvent');
                        break;
                    case 'TIMER':
                        $bpmElement = $this->retrievePMSEElement('PMSETimerEvent');
                        break;
                }
                break;
            case 'END':
                switch ($bpmnBean->evn_marker) {
                    case 'MESSAGE':
                        $bpmElement = $this->retrievePMSEElement('PMSEEndSendMessageEvent');
                        break;
                    case 'TERMINATE':
                        $bpmElement = $this->retrievePMSEElement('PMSETerminateEvent');
                        break;
                    default:
                        $bpmElement = $this->retrievePMSEElement('PMSEEndEvent');
                        break;
                }
                break;
            default :
                $bpmElement = false;
                break;
        }
        $bpmElement->setExecutionMode($definitionBean->execution_mode);
        return $bpmElement;
    }

    /**
     *
     * @param type $id
     * @return boolean
     * @codeCoverageIgnore
     */
    public function retrieveGatewayElement($id)
    {
        $bpmnBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnGateway');
        $definitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmGatewayDefinition');
        $bpmnBean->retrieve($id);
        $definitionBean->retrieve($id);
        $bpmElement = false;

        switch ($bpmnBean->gat_type) {
            case 'PARALLEL':
                $bpmElement = $bpmnBean->gat_direction == 'CONVERGING' ? $this->retrievePMSEElement('PMSEConvergingParallelGateway') : $this->retrievePMSEElement('PMSEDivergingParallelGateway');
                break;
            case 'EXCLUSIVE':
                $bpmElement = $bpmnBean->gat_direction == 'CONVERGING' ? $this->retrievePMSEElement('PMSEConvergingExclusiveGateway') : $this->retrievePMSEElement('PMSEDivergingExclusiveGateway');
                break;
            case 'INCLUSIVE':
                $bpmElement = $this->retrievePMSEElement('PMSEDivergingInclusiveGateway');
                break;
            case 'EVENTBASED':
                $bpmElement = $this->retrievePMSEElement('PMSEDivergingEventBasedGateway');
                break;
            default :
                $bpmElement = false;
                break;
        }
        $bpmElement->setExecutionMode($definitionBean->execution_mode);
        return $bpmElement;
    }

    /**
     *
     * @param type $id
     * @return boolean
     * @codeCoverageIgnore
     */
    public function retrieveFlowElement($id)
    {
        $bpmnBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnFlow');
        $bpmnBean->retrieve($id);
        $bpmElement = false;

        switch ($bpmnBean->flo_type) {
            case 'SEQUENCE':
            case 'DEFAULT':
                $bpmElement = $this->retrievePMSEElement('PMSESequenceFlow');
                break;
            default :
                $bpmElement = false;
                break;
        }
        return $bpmElement;
    }

    /**
     *
     * @param type $flowData
     * @param type $createThread
     * @param type $bean
     * @param type $externalAction
     * @param type $arguments
     * @return boolean
     * @codeCoverageIgnore
     */
    public function runEngine(
        $flowData,
        $createThread = false,
        $bean = null,
        $externalAction = '',
        $arguments = array()
    ) {

        // Load the bean if the request comes from a RESUME_EXECUTION related origin
        // like for example: a timer event execution.
        if (is_null($bean)) {
            $bean = BeanFactory::retrieveBean($flowData['cas_sugar_module'], $flowData['cas_sugar_object_id']);
        }

        // Validating unreferenced elements when cron jobs are executed, after MACAROON-518 shouldn't have
        // unreferenced elements. This will validate previous records created before this fix.
        if ($externalAction == 'WAKE_UP') {
            $elementBean = BeanFactory::getBean('pmse_BpmnEvent', $flowData['bpmn_id']);
            if (!isset($elementBean->id)) {

                // Setting active flow to deleted
                $fd = BeanFactory::getBean('pmse_BpmFlow', $flowData['id']);
                $fd->cas_flow_status = 'DELETED';
                $fd->save();

                // Updating process to error
                $cf = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
                $cf->changeCaseStatus($flowData['cas_id'], 'TERMINATED');

                // Exiting without errors
                return true;
            }
        }

        $preparedData = $this->caseFlowHandler->prepareFlowData($flowData, $createThread);
        $this->logger->debug("Begin process Element {$flowData['bpmn_type']}");

        try {
            $executionData = $this->processElement($preparedData, $bean, $externalAction, $arguments);
            if (isset($executionData['process_bean']) && !empty($executionData['process_bean'])) {
                $bean = $executionData['process_bean'];
            }
            $this->validateFailSafes($flowData, $executionData);
            $routeData = $this->flowRouter->routeFlow($executionData, $flowData, $createThread);
        } catch (PMSEElementException $e) {
            $element = $e->getElement();
            $flow = $e->getFlowData();
            $state = (empty($flow['id'])) ? 'CREATE' : 'UPDATE';
            $executionData = $element->prepareResponse($flow, 'ERROR', $state);
            // If the status is put into error then the Inbox record should be updated as well
            $this->caseFlowHandler->changeCaseStatus($executionData['flow_data']['cas_id'], 'ERROR');
            $routeData = $this->flowRouter->routeFlow($executionData, $flowData, $createThread);
        } catch (Exception $e) {
            $element = $this->retrievePMSEElement('');
            $status = $e->getCode() == 0 ? 'QUEUE' : 'ERROR';
            $preparedData['cas_flow_status'] = $status;

            // Programmatically determine whether to create or update the flow
            // This used to be hard coded to CREATE which would throw database
            // errors for id collisions on the flow table
            $state = $this->getExceptionState($preparedData);
            $executionData = $element->prepareResponse($preparedData, $status, $state);
            // If the status is put into error then the Inbox record should be updated as well
            if ($status == 'ERROR') {
                $this->caseFlowHandler->changeCaseStatus($executionData['flow_data']['cas_id'], 'ERROR');
            }
            $routeData = $this->flowRouter->routeFlow($executionData, $flowData, $createThread);
        }

        if ($externalAction == 'RESUME_EXECUTION'
            && $this->caseFlowHandler->numberOfCasesByStatus($flowData, 'ERROR') <= 0
        ) {
            $this->caseFlowHandler->changeCaseStatus($flowData['cas_id'], 'IN PROGRESS');
        }

        // If there are flow elements after this one that need processing, handle
        // them here
        if (!empty($routeData['next_elements'])) {
            // If there are more than one following flow - like from a parallel
            // gateway - then mark that as needing a thread
            $createThread = sizeof($routeData['next_elements']) > 1;

            // And if we need a thread, adjust the execution time accordingly
            if ($createThread) {
                // Execution time should not change. Execution time per thread
                // should be the same as it was when it started.
                $startTime = $this->executionTime;
            }

            // Now loop over the following elements and process them, setting the
            // execution time to the previous start time if necessary.
            foreach ($routeData['next_elements'] as $elementData) {
                // Reset execution time to start time if we are in a parallel thread
                // otherwise leave it as executionTime from before
                if ($createThread) {
                    $this->executionTime = $startTime;
                }

                // Now run the engine again
                $this->runEngine($elementData, $createThread, $bean);
            }
        } else {
            // Quick fix to the 0 output printed by some element,
            // TODO: Don't remove until the fix to the element is commited
            ob_get_clean();
            return true;
        }
        return true;
    }

    /**
     * Gets a database save state for a flow
     * @param array $data Flow data array
     * @return string
     */
    protected function getExceptionState($data)
    {
        // If the data does not have an id, or if it does not have a date entered AND created by
        if (empty($data['id']) || (empty($data['date_entered']) && empty($data['created_by']))) {
            // Then we will need to create the flow
            return 'CREATE';
        }

        // Otherwise, update the flow
        return 'UPDATE';
    }
    /**
     * It processes an element based in the assumption that should be executed
     * Synchronously or Asynchronously
     *
     * @param array $flowData
     * @param type $bean
     * @param type $externalAction
     * @return type
     * @codeCoverageIgnore
     */
    public function processElement($flowData, $bean, $externalAction = '', $arguments = array())
    {
        $startTime = microtime(true);
        $executionResult = array();
        $pmseElement = $this->retrieveElementByType($flowData);

        if (!empty($externalAction) && $externalAction == 'RESUME_EXECUTION') {
            $executionMode = $externalAction;
        } else {
            $executionMode = $pmseElement->getExecutionMode();
        }

        switch ($executionMode) {
            case 'RESUME_EXECUTION':
                $executionResult = $pmseElement->run($flowData, $bean, $externalAction, $arguments);
                $executionResult['flow_action'] = isset($executionResult['flow_action']) ?
                    $executionResult['flow_action'] :
                    'UPDATE';
                $executionResult['flow_id'] = $flowData['id'];
                break;
            case 'ASYNC':
                $flowData['cas_flow_status'] = 'QUEUE';
                $executionResult = $pmseElement->prepareResponse($flowData, 'QUEUE', 'CREATE');
                break;
            case 'SYNC':
            default:
                $executionResult = $pmseElement->run($flowData, $bean, $externalAction, $arguments);
                break;
        }
        $endTime = microtime(true);
        $executionResult['elapsed_time'] = $endTime - $startTime;
        return $executionResult;
    }

    public function retrievePMSEStartTime()
    {
        return Registry\Registry::getInstance()->get('pmse_start_time', 0);
    }

    public function validateExecutionTime($elementElapsedTime)
    {
        $this->executionTime += $elementElapsedTime;
        $maxExecutionTime = ini_get('max_execution_time');
        if ($maxExecutionTime == 0) {
            $maxExecutionTime = $this->maxExecutionTimeout;
        }
        $remain = $this->executionTime > $maxExecutionTime;
        if ($remain) {
            throw ProcessManager\Factory::getException('Execution', "Script execution is taking too much time", 1);
        } else {
            return $this->executionTime;
        }
    }

    public function validateNestedLoopCount($flowData)
    {
        // start elements doesn't have id, and they only execute once, so no need to count
        if (!empty($flowData['cas_sugar_object_id'])) {
            $sugar_id = $flowData['cas_sugar_object_id'];
            $element_id = $flowData['bpmn_id'];

            // If the element (activity, flow, etc) is triggered by the record for first time
            // initiate the counter by executedElements[record_id][element_id]
            // else increase the counter
            if (empty($this->executedElements[$sugar_id][$element_id])) {
                $this->executedElements[$sugar_id][$element_id] = 1;
            } else {
                $this->executedElements[$sugar_id][$element_id]++;
            }

            // if count exceed the cycle number throw a exception.
            $count = $this->executedElements[$sugar_id][$element_id];
            $limit = $this->maxExecutionCycleNumber;

            if ($count > $limit) {
                throw ProcessManager\Factory::getException('Execution', "Nested loops limit of {$limit} reached", 1);
            } else {
                return $count;
            }
        } else {
            return 0;
        }
    }

    public function validateFailSafes($flowData, $executionData)
    {
        $nestedCount = $this->validateNestedLoopCount($flowData);
        $executionTime = $this->validateExecutionTime($executionData['elapsed_time']);
        return array(
            'executionTime' => $executionTime,
            'nestedCount' => $nestedCount
        );
    }

    public function logErrorActivity($flowData, $bean)
    {
        $params = $this->processTags($flowData, $bean);
        $params['module_name'] = 'pmse_Inbox';
        $this->logger->activity('The task &0 of case &1 registered an execution error for the record &2 from module &3.',
            $params);
    }

    public function processTags($params, $bean)
    {
        $result = array();
        $tags = array('task', 'case', 'record', 'module');
        foreach ($tags as $value) {
            switch ($value) {
                case 'task':
                    $result['tags'][] = array(
                        "id" => $params['id'],
                        "name" => $params['bpmn_type'],
                        "module" => "pmse_Inbox"
                    );
                    break;
                case 'case':
                    $result['tags'][] = array(
                        "id" => $params['id'],
                        "name" => $params['cas_id'],
                        "module" => "pmse_Inbox"
                    );
                case 'record':
                    $result['tags'][] = array(
                        "id" => $bean->id,
                        "name" => $bean->name,
                        "module" => $bean->module_name
                    );
                    break;
                case 'module':
                    $result['tags'][] = array(
                        "id" => $bean->id,
                        "name" => $bean->module_name,
                        "module" => $bean->module_name
                    );
                    break;
                default:
                    break;
            }
        }
        return $result;
    }

}
