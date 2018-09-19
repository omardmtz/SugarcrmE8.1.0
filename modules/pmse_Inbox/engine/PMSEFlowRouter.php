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

/**
 * The flow router class is in charge of manage all the routing and derivation
 * of all the instances of PMSEElement subclasses and also of evaluating the
 * execution result of those classes.
 *
 */
class PMSEFlowRouter
{

    /**
     * The case flow handler attribute manages all the Database operations
     * related to the pmse_bpm_flow table.
     * @var type
     */
    private $caseFlowHandler;

    /**
     * This attribute is in charge of manage the
     * queue and run of asynchronous elements
     * @var type
     */
    private $jobQueueHandler;

    /**
     * PMSE Logger object
     *
     * @var
     */
    protected $logger;

    /**
     * Flow Router Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->caseFlowHandler = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
        $this->jobQueueHandler = ProcessManager\Factory::getPMSEObject('PMSEJobQueueHandler');
        $this->logger = PMSELogger::getInstance();
    }

    /**
     * Retrieve the CaseFlowHandler class
     * @codeCoverageIgnore
     */
    public function getCaseFlowHandler()
    {
        return $this->caseFlowHandler;
    }

    /**
     * Set the case flow handler attribute
     * @codeCoverageIgnore
     */
    public function setCaseFlowHandler($flow)
    {
        $this->caseFlowHandler = $flow;
    }

    /**
     * Get the Element Runner attribute
     * @codeCoverageIgnore
     * @deprecated
     */
    public function getPmseElementRunner()
    {
        return $this->pmseElementRunner;
    }

    /**
     *
     * @codeCoverageIgnore
     * @deprecated
     */
    public function getElementStack()
    {
        return $this->elementStack;
    }

    /**
     *
     * @codeCoverageIgnore
     * @deprecated since version 0.00001
     */
    public function setPmseElementRunner($pmseElementRunner)
    {
        $this->pmseElementRunner = $pmseElementRunner;
    }

    /**
     * Set the Job queue handler class.
     * @return type
     * @codeCoverageIgnore
     */
    public function getJobQueueHandler()
    {
        return $this->jobQueueHandler;
    }

    /**
     * Set The JobQueueHandler attribute
     * @param type $jobQueueHandler
     * @codeCoverageIgnore
     */
    public function setJobQueueHandler($jobQueueHandler)
    {
        $this->jobQueueHandler = $jobQueueHandler;
    }

    /**
     * Retrieve the flow data using a case id and case index parameters
     * @codeCoverageIgnore
     * @deprecated
     */
    public function retrieveData($caseID, $caseIndex, $threadIndex)
    {
        $result = $this->caseFlowHandler->retrieveData($caseID, $caseIndex, $threadIndex);
        return $result;
    }

    /**
     * Retrieve the instance of the element to be executed
     * @param type $flowData
     * @return PMSEElement
     * @codeCoverageIgnore
     */
    public function retrieveElement($flowData)
    {
        $result = $this->caseFlowHandler->retrieveElementByType($flowData);
        return $result;
    }

    /**
     * Run the engine recursively this method is the main entry point for
     * the engine, modifications and updates to this method should be done
     * with extreme precaution.
     *
     * @param type $flowData
     * @param type $createThread
     * @param type $bean
     * @return boolean
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
//    public function runEngine($flowData, $createThread = false, $bean = null, $externalAction = '')
//    {
//        $routeData = $this->routeFlow($flowData, $createThread, $bean, $externalAction);
//        if (!empty($routeData['next_elements'])) {
//            $createThread = sizeof($routeData['next_elements']) > 1;
//            foreach ($routeData['next_elements'] as $elementData) {
//                $this->runEngine($elementData, $createThread, $bean);
//            }
//        } else {
//            // Quick fix to the 0 output printed by some element, 
//            // Please don't remove until the fix to the element is committed
//            // FIXED ob_get_clean();
//            return true;
//        }
//        return false;
//    }

    /**
     * This entry point is used by the RunJob function that wakes up queued jobs
     *
     * @param type $flowData
     * @param type $createThread
     * @param type $bean
     * @param type $executionResult
     * @return boolean
     * @deprecated
     * @codeCoverageIgnore
     */
//    public function wakeUpEngine($flowData, $createThread = false, $bean = null, $executionResult = array())
//    {
//        $elements = $this->retrieveFollowingElements($executionResult, $flowData);
//        $createThread = sizeof($elements) > 1;
//        foreach ($elements as $elementData) {
//            $this->runEngine($elementData, $createThread, $bean);
//        }
//    }

    /**
     * The route Flow is the method that manages the execution data results and
     * also save the new flow and close the previous one, modifications and updates
     * to this method should be done with extreme caution.
     *
     * @param type $resultData Description
     * @param type $previousFlowData
     * @param type $createThread
     * @return type
     */
    public function routeFlow($resultData, $previousFlowData, $createThread = false)
    {

        switch ($resultData['flow_action']) {
            case 'UPDATE':
                unset($resultData['flow_data']['cas_index']); // The case index should not be overriden
                unset($resultData['flow_data']['cas_previous']); // The case index should not be overriden
                $resultData['processed_flow'] = $this->caseFlowHandler->saveFlowData($resultData['flow_data'], false,
                    $resultData['flow_id']);
                break;
            case 'CREATE':
                if (isset($resultData['create_thread']) && $resultData['create_thread']) {
                    $createThread = $resultData['create_thread'];
                }
                $resultData['processed_flow'] = $this->caseFlowHandler->saveFlowData($resultData['flow_data'], $createThread);
                if (!empty($resultData['previous_flows'])) {
                    foreach ($resultData['previous_flows'] as $flow) {
                        $resultData['previous_closed_flow'] = $this->caseFlowHandler->closePreviousFlow($flow);
                    }
                } else {
                    $resultData['previous_closed_flow'] = $this->caseFlowHandler->closePreviousFlow($previousFlowData);
                }
                break;
            case 'CLOSE':
                $resultData['processed_flow'] = null;
                $resultData['previous_closed_flow'] = $this->caseFlowHandler->closePreviousFlow($previousFlowData);
                break;
            case 'NONE':
            default :
                if (isset($resultData['close_thread']) && $resultData['close_thread']) {
                    $this->caseFlowHandler->closeThreadByThreadIndex($previousFlowData['cas_id'], $previousFlowData['cas_thread']);
                }
                $resultData['processed_flow'] = $resultData['flow_data'];
                break;
        }
        $resultData['next_elements'] = $this->retrieveFollowingElements($resultData, $resultData['processed_flow']);
        return $resultData;
    }

    /**
     * It processes an element based in the assumption that should be executed
     * Synchronously or Asynchronously
     *
     * @param array $flowData
     * @param type $bean
     * @param type $externalAction
     * @return type
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
//    public function processElement($flowData, $bean, $externalAction = '')
//    {
//        $executionResult = array();
//        $pmseElement = $this->retrieveElement($flowData);
//        switch ($pmseElement->getExecutionMode()) {
//            case 'ASYNC':
//                $executionResult = $this->queueJob($flowData, $bean);
//                break;
//            case 'SYNC':
//            default:
//                $executionResult = $pmseElement->run($flowData, $bean, $externalAction);
//                break;
//        }
//        return $executionResult;
//    }

    /**
     * This retrieve the set of next elements to be executed based on the
     * route_action response attribute
     *
     * @param array $executionResult
     * @param array $flowData
     * @return array
     */
    public function retrieveFollowingElements($executionResult, $flowData)
    {
        switch ($executionResult['route_action']) {
//            case 'QUEUE':
//                $this->queueJob($flowData);
            case 'NONE':
            case 'WAIT':
            case 'SLEEP':
            case 'FORM':
            case 'FREEZE':
            case 'ERROR':
            case 'INVALID':
                $result = array();
                break;
            case 'QUEUE':
                $this->queueJob($flowData);
            case 'ROUTE':
            default:
                $result = $this->filterFlows(
                    $this->caseFlowHandler->retrieveFollowingElements($flowData), $executionResult['flow_filters']
                );
                break;
        }
        return $result;
    }

    /**
     * This method uses the Job Queue Handler instance in order to save a new
     * job in the Sugar Job Queue
     *
     * @param type $flowData
     * @return type
     */
    public function queueJob($flowData)
    {
        $jobData = new stdClass();
        $jobData->id = $flowData['cas_id'] . '-' . $flowData['cas_index'];
        $jobData->data = $flowData;
        return $this->jobQueueHandler->submitPMSEJob($jobData);
    }


    /**
     * Freeze the execution of a determined element.
     *
     * @param type $flowData
     * @return type
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
//    public function invalidateElement(PMSEElement $element, $flowData)
//    {
//        $flowData['cas_flow_status'] = 'INVALID';
//        return $element->prepareResponse($flowData, 'INVALID', 'CREATE');
//    }

    /**
     * This function filters the returning flows based on the execution response
     *
     * @param type $nextElements
     * @param type $filterFlows
     * @return type
     */
    public function filterFlows($nextElements, $filterFlows = array())
    {
        if (!empty($filterFlows)) {
            foreach ($nextElements as $key => $element) {
                if (!in_array($element['bpmn_id'], $filterFlows)) {
                    unset($nextElements[$key]);
                }
            }
        }
        return array_values($nextElements);
    }


}
