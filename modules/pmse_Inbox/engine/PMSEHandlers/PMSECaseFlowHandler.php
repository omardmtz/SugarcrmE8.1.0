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

class PMSECaseFlowHandler
{
    /**
     * pmse_BpmFlow bean
     * @var pmse_BpmFlow
     */
    protected $bpmFlow;

    /**
     * pmse_BpmnFlow bean
     * @var pmse_BpmnFlow
     */
    protected $bpmnFlow;

    /**
     * SugarQuery object
     * @var SugarQuery
     */
    protected $sugarQueryObject;

    /**
     * DBManager object
     * @var DBManager
     */
    protected $db;

    /**
     * Class Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->sugarQueryObject = new SugarQuery();
    }

    /**
     * @return DBManager
     */
    public function getDb()
    {
        if (!$this->db) {
            $this->db = DBManagerFactory::getInstance();
        }
        return $this->db;
    }

    /**
     * @param DBManager $db
     */
    public function setDb(DBManager $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @param type $module
     * @param type $beanId
     * @return type
     * @codeCoverageIgnore
     */
    public function retrieveBean($module, $beanId = null)
    {
        return BeanFactory::getBean($module, $beanId);
    }

    /**
     * Select PMSEElement implementation, but since the PMSEExecuter is now
     * in charge of that role this function is irrelevant
     * @param type $elementName
     * @return PMSEElement
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
    public function retrievePMSEElement($elementName)
    {
        return ProcessManager\Factory::getElement($elementName);
    }

    /**
     *
     * @return \SugarQuery
     * @codeCoverageIgnore
     */
    public function retrieveSugarQueryObject()
    {
        return new SugarQuery();
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getBpmFlow()
    {
        if (is_null($this->bpmFlow)) {
            $this->bpmFlow = BeanFactory::newBean('pmse_BpmFlow');
        }
        return $this->bpmFlow;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getBpmnFlow()
    {
        if (is_null($this->bpmnFlow)) {
            $this->bpmnFlow = BeanFactory::newBean('pmse_BpmnFlow');
        }
        return $this->bpmnFlow;
    }

    /**
     *
     * @param type $bpmFlow
     * @codeCoverageIgnore
     */
    public function setBpmFlow($bpmFlow)
    {
        $this->bpmFlow = $bpmFlow;
    }

    /**
     *
     * @param type $bpmnFlow
     * @codeCoverageIgnore
     */
    public function setBpmnFlow($bpmnFlow)
    {
        $this->bpmnFlow = $bpmnFlow;
    }

    /**
     *
     * @param type $flowData
     * @return type
     */
    public function retrieveFlowData($flowData)
    {
        // set the bpmFlow attribute in this line for performance reasons
        $this->getBpmFlow();
        $sugarQueryObject = $this->retrieveSugarQueryObject();
        $sugarQueryObject->select(array(
            'id',
            'cas_id',
            'cas_index',
            'bpmn_type',
            'bpmn_id',
            'pro_id',
            'cas_user_id',
            'cas_thread',
            'cas_sugar_object_id',
            'cas_sugar_module'
        ));

        $sugarQueryObject->from($this->bpmFlow);
        $sugarQueryObject->where()->queryAnd()
            ->addRaw('cas_index=' . $flowData['cas_index'] . ' AND cas_id=' . $flowData['cas_id']);

        $currentFlow = $sugarQueryObject->execute();
        return $currentFlow[0];
    }

    /**
     *
     * @param type $flowData
     * @return int
     */
    public function retrieveMaxIndex($flowData)
    {
        if (!isset($flowData['cas_id']) || empty($flowData['cas_id'])) {
            return 0;
        }

        // set the bpmFlow attribute in this line for performance reasons
        $this->getBpmFlow();

        $query = sprintf(
            'SELECT MAX(cas_index) max_index FROM %s flow WHERE flow.deleted = 0 AND flow.cas_id = %s',
            $this->bpmFlow->getTableName(),
            $flowData['cas_id']
        );
        $result = (int) $this->getDb()->getOne($query);
        return max(1, $result);
    }

    /**
     *
     * @param array $flowData
     * @return string
     */
    public function retrieveFollowingElements($flowData)
    {
        // Bail out early if we don't have what we need
        if (!isset($flowData['bpmn_type'], $flowData['bpmn_id'])) {
            return array();
        }

        $flowData['id'] = '';

        $isFlow = $flowData['bpmn_type'] == 'bpmnFlow';
        $bpmnFlowBean = $this->retrieveBean('pmse_BpmnFlow');
        $sugarQueryObject = $this->retrieveSugarQueryObject();
        if ($isFlow) {
            $fields = array(
                array('flo_element_dest', 'bpmn_id'),
                array('flo_element_dest_type', 'bpmn_type'),
            );
            $where = "id='" . $flowData['bpmn_id'] . "'";
        } else {
            $fields = array(
                array('id', 'bpmn_id'),
            );
            $where = "flo_element_origin_type='" . $flowData['bpmn_type'] . "' AND flo_element_origin='" . $flowData['bpmn_id'] . "'";
        }

        $sugarQueryObject->select($fields);
        $sugarQueryObject->from($bpmnFlowBean);
        $sugarQueryObject->where()
            ->queryAnd()
            ->addRaw($where);

        $flows = $sugarQueryObject->execute();

        foreach ($flows as $key => $flow) {
            $flow = array_merge($flowData, $flow);
            if (!$isFlow) {
                $flow['bpmn_type'] = 'bpmnFlow';
            }
            $flows[$key] = $flow;
        }

        return $flows;
    }

    /**
     *
     * @param type $caseID
     * @param type $caseIndex
     * @param type $threadIndex
     * @return type
     */
    public function retrieveData($caseID, $caseIndex, $threadIndex)
    {
        $flowParams = array('cas_id' => $caseID, 'cas_index' => $caseIndex, 'cas_thread' => $threadIndex);
        $flowData = $this->retrieveFlowData($flowParams);
        $bpmnElement = $this->retrieveElementByType($flowData);

        $result['flow_data'] = $flowData;
        $result['pmse_element'] = $bpmnElement;
        return $result;
    }

    /**
     *
     * @param type $flowData
     * @return type
     * @deprecated since version pmse2
     * @codeCoverageIgnore
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
     *
     * @param type $id
     * @return boolean
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
    public function retrieveActivityElement($id)
    {
        $bpmnBean = $this->retrieveBean('pmse_BpmnActivity');
        $definitionBean = $this->retrieveBean('pmse_BpmActivityDefinition');
        $bpmnBean->retrieve($id);
        $definitionBean->retrieve($id);

        $bpmElement = false;
        switch ($bpmnBean->act_task_type) {
            case 'SCRIPTTASK':
                switch ($bpmnBean->act_script_type) {
                    case 'BUSINESS_RULE':
                        $bpmElement = $this->retrievePMSEElement('PMSEBusinessRule');
                        break;
                    case 'CHANGE_FIELD':
                        $bpmElement = $this->retrievePMSEElement('PMSEChangeField');
                        break;
                    case 'ASSIGN_TEAM':
                        $bpmElement = $this->retrievePMSEElement('PMSERoundRobin');
                        break;
                    case 'ASSIGN_USER':
                        $bpmElement = $this->retrievePMSEElement('PMSEAssignUser');
                        break;
                    case 'ADD_RELATED_RECORD':
                        $bpmElement = $this->retrievePMSEElement('PMSEAddRelatedRecord');
                        break;
                }
                break;
            case 'USERTASK':
                $bpmElement = $this->retrievePMSEElement('PMSEUserTask');
                break;
            default :
                $bpmElement = false;
                break;
        }

        $bpmElement->setExecutionMode($definitionBean->execution_mode);
        $raw = $bpmElement->getExecutionMode();
        return $bpmElement;
    }

    /**
     *
     * @param type $id
     * @return boolean
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
    public function retrieveEventElement($id)
    {
        $bpmnBean = $this->retrieveBean('pmse_BpmnEvent');
        $definitionBean = $this->retrieveBean('pmse_BpmEventDefinition');
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
     * Method migrated to the PMSEExecuter Class
     * @param type $id
     * @return boolean
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
    public function retrieveGatewayElement($id)
    {
        $bpmnBean = $this->retrieveBean('pmse_BpmnGateway');
        $definitionBean = $this->retrieveBean('pmse_BpmGatewayDefinition');
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
     * @deprecated since version pmse2
     * @codeCoverageIgnore
     */
    public function retrieveFlowElement($id)
    {
        $bpmnBean = $this->retrieveBean('pmse_BpmnFlow');
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

    public function prepareFlowData($flowData)
    {
        $flowData['cas_current_index'] = $flowData['cas_index'];
        $flowData['max_index'] = $this->retrieveMaxIndex($flowData);
        $preparedData = $this->processFlowData($flowData);
        return $preparedData;
    }

    public function saveFlowData($flowData, $createThread = false, $flowId = null)
    {
        if ($createThread) {
            $flowData['cas_thread'] = $this->createThread($flowData);
        }

        $flowBean = $this->retrieveBean('pmse_BpmFlow', $flowId);
        foreach ($flowData as $key => $value) {
            if (property_exists($flowBean, $key)) {
                $flowBean->$key = $value;
            }
        }
        if ($flowId !== null) {
            $flowBean->new_with_id = false;
        }
        $flowBean->save();

        // If this is a new flow, handle the setting of locked fields
        if (empty($flowId)) {
            $this->setLockedFieldsOnBean($flowData);
        }

        return $flowBean->toArray();
    }

    /**
     * Sets the locked field relationship on the target bean if need be
     * @param array $flowData
     */
    public function setLockedFieldsOnBean(array $flowData)
    {
        // Only set this for new flows, since it will be removed when the case is
        // terminated
        if (isset($flowData['cas_flow_status']) && $flowData['cas_flow_status'] === 'NEW') {
            // There are, so check if we need to add this PD rel to the target bean
            $target = BeanFactory::getBean($flowData['cas_sugar_module'], $flowData['cas_sugar_object_id']);

            // Get the process definition bean
            $pd = $this->retrieveBean('pmse_BpmProcessDefinition', $flowData['pro_id']);

            // Add the bean for relationship now
            $this->addLockedFields($target, $pd);
        }
    }

    /**
     * Removes the locked field relationship from a target bean if need be
     * @param SugarBean $bean
     * @param string $proId
     */
    public function deleteLockFieldsFromBean(SugarBean $bean, $proId)
    {
        // Get the process definition bean
        $pd = $this->retrieveBean('pmse_BpmProcessDefinition', $proId);

        // And remove it from the target bean
        $this->removeLockedFields($bean, $pd);
    }

    /**
     * Adds a process definition bean as a relate bean to this bean
     * @param SugarBean $bean
     * @param pmse_BpmProcessDefinition $pd
     */
    public function addLockedFields(SugarBean $bean, pmse_BpmProcessDefinition $pd)
    {
        // Only add a related record if we have a record to start with
        if ($bean->id) {
            // Get our locked field rel field
            $relField = $bean->getLockedFieldRelField();

            // Try to load the relationship field...
            if ($bean->load_relationship($relField)) {
                // And if it works, get our current related PDs and...
                $current = $bean->getLockedFieldRelBeans();

                // Add the pd to it if it is not already related
                if (!isset($current[$pd->id])) {
                    $bean->$relField->add($pd);
                    PMSEEngineUtils::markModuleHavingLockedFields($bean->getModuleName());
                }
            }
        }
    }

    /**
     * Removes a process definition bean relationship from this bean
     * @param SugarBean $bean
     * @param  pmse_BpmProcessDefinition $pd
     */
    public function removeLockedFields(SugarBean $bean, pmse_BpmProcessDefinition $pd)
    {
        // Only handle this if we have a record to start with
        if ($bean->id) {
            // Get our current related PDs
            $current = $bean->getLockedFieldRelBeans();

            // Delete this pd from it if it is related
            if (isset($current[$pd->id])) {
                // Get our locked field rel field
                $relField = $bean->getLockedFieldRelField();

                if (!$bean->$relField->delete($bean->id, $pd->id)) {
                    // Log the failure
                    $msg = sprintf(
                        "Failed to delete locked fields rel of PD %s from %s",
                        $pd->id,
                        $bean->module_dir
                    );

                    LoggerManager::getLogger()->fatal($msg);
                }

                PMSEEngineUtils::resetModuleLockedFieldsCache($bean->getModuleName());
            }
        }
    }

    /**
     * Handles removal of locked field relationship from a bean
     * @param int $casId Case ID
     */
    public function handleTerminatedFlowRelatedBeans($casId)
    {
        // We need the flow bean for SugarQuery
        $flow = $this->retrieveBean('pmse_BpmFlow');

        // Get the query object
        $q = $this->retrieveSugarQueryObject();

        // We only need these fields for checking/handling
        $fields = array(
            'pro_id',
            'cas_flow_status',
            'cas_sugar_module',
            'cas_sugar_object_id',
        );

        // Build the query
        $q->select($fields);
        $q->from($flow)
          ->where()
          ->equals('cas_id', $casId);

        // Get our rows of the flow
        $rows = $q->execute();

        // And get our count for comparison
        $c = count($rows);

        // We really only want to do this if there are rows, which there should
        // always be
        if ($c) {
            // Set a check value here to see if all rows are closed/terminated
            $v = 0;

            // Loop and check
            foreach ($rows as $row) {
                if (in_array($row['cas_flow_status'], array('CLOSED', 'TERMINATED'))) {
                    $v++;
                }
            }

            // If the number of closed/terminated matches the numer of rows, they
            // are all closed, so we need to remove the locked fields rel from the
            // target bean
            if ($v === $c) {
                $bean = $this->retrieveBean($row['cas_sugar_module'], $row['cas_sugar_object_id']);
                $this->deleteLockFieldsFromBean($bean, $row['pro_id']);
            }
        }
    }

    public function processFlowData($flowData)
    {
        $today = TimeDate::getInstance()->nowDb();
        $_date = TimeDate::getInstance()->getNow()->add(new DateInterval('P1D'));
        $dueDate = $_date->asDb();

        if ($flowData['bpmn_type'] === 'bpmnActivity') {
            $beanActivityDefinition = BeanFactory::getBean('pmse_BpmActivityDefinition', $flowData['bpmn_id']);
            $casData = new stdClass();
            $casData->cas_task_start_date = null;
            $casData->cas_delegate_date = $today;
            $expectedTime = (!empty($beanActivityDefinition->act_expected_time)) ? json_decode(base64_decode($beanActivityDefinition->act_expected_time)) : '';
            $_dueDate = (!empty($expectedTime) && !empty($expectedTime->time)) ? PMSEEngineUtils::processExpectedTime($expectedTime, $casData) : '';
            if (!empty($_dueDate)) {
                $dueDate = $_dueDate->format('Y-m-d H:i:s');
            } else {
                $dueDate = '';
            }
        }

        $preparedFlow = array();
        $preparedFlow['id'] = isset($flowData['id']) ? $flowData['id'] : '';
        $preparedFlow['cas_id'] = $flowData['cas_id'];
        $preparedFlow['cas_index'] = $flowData['max_index'] + 1;
        $preparedFlow['cas_previous'] = $flowData['cas_current_index'];
        $preparedFlow['pro_id'] = $flowData['pro_id'];
        $preparedFlow['bpmn_id'] = $flowData['bpmn_id'];
        $preparedFlow['bpmn_type'] = $flowData['bpmn_type'];
        $preparedFlow['cas_user_id'] = $flowData['cas_user_id'];
        $preparedFlow['cas_thread'] = $flowData['cas_thread'];
        $preparedFlow['cas_flow_status'] = 'NEW';
        $preparedFlow['cas_sugar_module'] = $flowData['cas_sugar_module'];
        $preparedFlow['cas_sugar_object_id'] = $flowData['cas_sugar_object_id'];
        $preparedFlow['cas_sugar_action'] = 'None';
        $preparedFlow['cas_delegate_date'] = $today;
        $preparedFlow['cas_start_date'] = '';
        $preparedFlow['cas_finish_date'] = '';
        $preparedFlow['cas_due_date'] = $dueDate;
        $preparedFlow['cas_queue_duration'] = 0;
        $preparedFlow['cas_duration'] = 0;
        $preparedFlow['cas_delay_duration'] = 0;
        $preparedFlow['cas_started'] = 0;
        $preparedFlow['cas_finished'] = 0;
        $preparedFlow['cas_delayed'] = 0;
        // Changed from an explicit true to a programmatic decision
        // If there is an id but not a create date and create user, then it is new with id
        $preparedFlow['new_with_id'] = !empty($flowData['id']) && (empty($flowData['date_entered']) && empty($flowData['created_by']));
        //Set these attributes if a related record is being evaluated
        $preparedFlow['rel_process_module'] = isset($flowData['rel_process_module']) ? $flowData['rel_process_module'] : '';
        $preparedFlow['rel_element_relationship'] = isset($flowData['rel_element_relationship']) ? $flowData['rel_element_relationship'] : '';
        $preparedFlow['rel_element_module'] = isset($flowData['rel_element_module']) ? $flowData['rel_element_module'] : '';
        //Set these attributes if a start event is being evaluated
        $preparedFlow['evn_criteria'] = isset($flowData['evn_criteria']) ? $flowData['evn_criteria'] : '';

        return $preparedFlow;
    }

    public function createThread($flowData)
    {
        $parentThreadIndex = $flowData['cas_thread'];
        $thread = $this->retrieveBean('pmse_BpmThread'); //new BpmThread();
        $currentThreadIndex = 0;

        $sugarQueryObject = $this->retrieveSugarQueryObject();
        // retrieve the max thread index
        $sugarQueryObject->select(array('cas_thread_index'));
        $sugarQueryObject->from($thread);
        $sugarQueryObject->where()
            ->queryAnd()
            ->addRaw('cas_id=' . $flowData['cas_id']);

        $rows = $sugarQueryObject->execute();

        foreach ($rows as $value) {
            $currentThreadIndex = max($currentThreadIndex, (int)$value['cas_thread_index']);
        }

        //create BpmThread record
        $thread->cas_id = $flowData['cas_id'];
        $thread->cas_thread_index = $currentThreadIndex + 1;
        $thread->cas_thread_parent = $parentThreadIndex;
        $thread->cas_thread_status = 'OPEN';
        $thread->cas_flow_index = $flowData['cas_index'];
        $thread->cas_thread_tokens = 0;
        $thread->cas_thread_passes = 0;
        $thread->new_with_id = true;
        $thread->save();
        //return the thread index
        $this->closeThreadByThreadIndex($flowData['cas_id'], $parentThreadIndex);
        return $thread->cas_thread_index;
    }

    public function closePreviousFlow($flowData)
    {
        if (isset($flowData['cas_id']) && isset($flowData['cas_index'])) {
            $this->closeFlow($flowData['cas_id'], $flowData['cas_index']);
        }
        return $flowData;
    }

    public function closeFlow($casId, $casIndex)
    {
        $flowBean = $this->retrieveBean('pmse_BpmFlow');
        $params = array('cas_id' => $casId, 'cas_index' => $casIndex);
        $flowBean->retrieve_by_string_fields($params);
        $flowBean->cas_flow_status = 'CLOSED';
        $flowBean->cas_finish_date = TimeDate::getInstance()->nowDb();
        $return = $flowBean->save();

        // Handle checking for locked field relationship
        $this->handleTerminatedFlowRelatedBeans($casId);

        return $return;
    }

    /**
     * Set close to a Bpm Thread Record
     * @global type $db
     * @param type $cas_id
     * @param type $cas_thread_index
     * @return boolean
     */
    public function closeThreadByThreadIndex($cas_id, $cas_thread_index)
    {
        $q = $this->retrieveSugarQueryObject();
        $threadBean = $this->retrieveBean('pmse_BpmThread');
        $fields = array('id');
        $q->from($threadBean, array('add_deleted' => true));
        $q->where()
            ->equals('cas_id', $cas_id)
            ->equals('cas_thread_index', $cas_thread_index);

        $q->select($fields);
        $rows = $q->execute();

        if (!is_array($rows)) {
            return;
        }
        foreach ($rows as $row) {
            $threadBean = $this->retrieveBean('pmse_BpmThread', $row['id']);
            $threadBean->cas_thread_status = 'CLOSED';
            $threadBean->save();
        }
    }

    /**
     * Set close to a Bpm Thread Record
     * @global type $db
     * @param type $cas_id
     * @param type $cas_thread_index
     * @return boolean
     */
    public function closeThreadByCaseIndex($cas_id, $cas_index)
    {
        $db = $this->getDb();

        //get current values
        $flowBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $flowBean->retrieve_by_string_fields(array('cas_id' => $cas_id, 'cas_index' => $cas_index));
        $currentThreadIndex = $flowBean->cas_thread;

        $query = "update pmse_bpm_thread set " .
            " cas_flow_index = $cas_index, " .
            " cas_thread_status = 'CLOSED' " .
            " where cas_id = $cas_id and cas_thread_index = $currentThreadIndex ";
        $db->query($query, true, "Error updating bpm_thread record ");
        //$this->bpmLog('DEBUG', "[$cas_id][$cas_index] thread $currentThreadIndex closed");
    }

    /**
     * This method sets the cas_status of a case to any status
     * depending of the parameters passed to it.
     * TODO: the method should be called by anyone that wants to change a
     * case status, and also needs to be transformed to a SugarQuery object
     * @global type $db
     * @param type $cas_id
     * @param type $status
     */
    public function changeCaseStatus($cas_id, $status = 'IN PROGRESS')
    {
        $db = $this->getDb();

        $query = "update pmse_inbox set " .
            " cas_status = '{$status}' " .
            " where cas_id = $cas_id  AND cas_status<>'COMPLETED' AND cas_status<>'TERMINATED'";
        $db->query($query, true, "Error updating bpm_inbox record ");
    }

    /**
     * This method sets the cas_status of a case to COMPLETED or TERMINATED
     * depending of the method used to close it.
     * TODO: the method should only call the changeCaseStatus method since the
     * implementation is basically the same, and also needs to be transformed to
     * a SugarQuery object
     * @global type $db
     * @param type $cas_id
     * @param type $status
     */
    public function closeCase($cas_id, $status = 'COMPLETED')
    {
        $db = $this->getDb();

        $today = TimeDate::getInstance()->nowDb();
        $query = "update pmse_inbox set " .
            " cas_status = '{$status}', " .
            " cas_finish_date = '" . $today . "' " .
            " where cas_id = $cas_id ";
        $db->query($query, true, "Error updating bpm_inbox record ");

        //$this->bpmLog('DEBUG', "[$cas_id][] has been marked as completed");
    }

    /**
     * Updates the bpm_flow table with data from $data based on criteria in $where
     * @param array $data
     * @param array $where
     * @return boolean
     */
    protected function updateFlowData(array $data, array $where)
    {
        // We'll need this bean
        $flow = $this->getBpmFlow();

        // And we'll need the DBManager as well
        $db = $this->getDb();

        // Build the SET SQL
        $sets = array();
        foreach ($data as $k => $v) {
            // Non numeric values must be quoted
            $set = is_numeric($v) ? $v : $db->quoted($v);
            $sets[] = "$k = $set";
        }
        $setSql = implode(',', $sets);

        // Build the WHERE SQL
        $wheres = array();
        foreach ($where as $v) {
            if (!isset($v[0], $v[1])) {
                continue;
            }

            // DB quote non numeric values
            $val = is_numeric($v[1]) ? $v[1] : $db->quoted($v[1]);

            // If there was a third element passed in the array, that is the operator
            $op = isset($v[2]) ? $v[2] : '=';

            // Finish the expression
            $wheres[] = "{$v[0]} $op $val";
        }

        // Support only AND operators for now
        $whereSql = implode(' AND ', $wheres);

        $sql = "UPDATE {$flow->table_name}
                SET
                    $setSql";

        if (!empty($wheres)) {
            $sql .= "WHERE $whereSql";
        }

        // Run the query now
        $db->query($sql, true, 'Error updating the BPM Flow table');

        // Send back a result, for now will always be true
        return true;
    }

    /**
     * Set the terminated status to the cas_flow_status field.
     * @param type $casId
     */
    public function terminateCaseFlow($casId)
    {
        $data = array(
            'cas_finish_date' => TimeDate::getInstance()->nowDb(),
            'cas_finished' => 1,
            'cas_flow_status' => 'TERMINATED',
        );

        $where = array(
            array('cas_id', $casId),
            array('cas_flow_status', 'CLOSED', '!='),
            array('cas_flow_status', 'TERMINATED', '!='),
        );

        // Handle the update of the flow
        $return = $this->updateFlowData($data, $where);

        // Handle checking for locked field relationship
        $this->handleTerminatedFlowRelatedBeans($casId);

        return $return;
    }

    /**
     * Set the close status for the Thread with the cas_id and cas_thread_index as parameters
     * @global type $db
     * @param type $cas_id
     * @param type $cas_thread_index
     * @return boolean
     */
    public function setCloseStatusForThisThread($casId, $casThreadIndex)
    {
        $data = array(
            'cas_finish_date' => TimeDate::getInstance()->nowDb(),
            'cas_finished' => 1,
            'cas_flow_status' => 'TERMINATED',
        );

        $where = array(
            array('cas_id', $casId),
            array('cas_thread', $casThreadIndex),
            array('cas_flow_status', 'CLOSED', '!='),
        );

        // Handle the update of the flow
        $return = $this->updateFlowData($data, $where);

        // Handle checking for locked field relationship
        $this->handleTerminatedFlowRelatedBeans($casId);

        return $return;
    }

    /**
     * Save the Form Action data if a form has been sent or a Business rule been executed.
     * @global type $current_user
     * @param type $params
     */
    public function saveFormAction($params = array())
    {
        global $current_user;

        $cas_id = $params['cas_id'];
        $cas_index = $params['cas_index'];
        $flowBeanObject = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $flowBeanObject->retrieve_by_string_fields(array('cas_id' => $cas_id, 'cas_index' => $cas_index));

        //ADD COMMENT IN BPM_NOTES
        if (isset($params['not_type']) && !empty($params['not_type'])) {
            $noteBean = $this->retrieveBean('pmse_BpmNotes');
            $noteBean->cas_id = $cas_id;
            $noteBean->cas_index = $cas_index;
            $noteBean->not_user_id = $current_user->id;
            $noteBean->not_user_recipient_id = $params['not_user_recipient_id'];
            $noteBean->not_type = $params['not_type'];
            $noteBean->not_date = date("Y-m-d H:i:s");
            $noteBean->not_content = $params['frm_comment'];
            $noteBean->save();
        }

        $formActionBeanObject = $this->retrieveBean('pmse_BpmFormAction'); //new BpmFormAction();
        $formActionBeanObject->act_id = $flowBeanObject->bpmn_id;
        $formActionBeanObject->pro_id = $flowBeanObject->pro_id;
        //$formActionBeanObject->new_with_id = true;
        $previousFormActionBeanObject = $this->retrieveBean('pmse_BpmFormAction'); //new BpmFormAction();
        $previousFormActionBeanObject->retrieve_by_string_fields(array('cas_id' => $cas_id, 'frm_last' => 1));

        if (isset($previousFormActionBeanObject->fetched_row) && !empty($previousFormActionBeanObject->fetched_row)) {
            $formActionBeanObject->frm_index = $previousFormActionBeanObject->frm_index + 1;
            $formActionBeanObject->frm_last = 1;
            $previousFormActionBeanObject->frm_last = 2;
            //TODO check & verify the fix for the error in frm_last, field empty
            $previousFormActionBeanObject->save();
        } else {
            $formActionBeanObject->frm_index = 1;
            $formActionBeanObject->frm_last = 1;
        }

        // find a better fix but since the id is no longer required is better
        // pull of that parameter.
        unset($params['id']);

        foreach ($params as $key => $value) {
            $formActionBeanObject->$key = $value;
        }

        if (isset($params['Type'])) {
            $frmAction = $params['Type'];
        } else if (isset($params['frm_action'])) {
            $frmAction = $params['frm_action'];
        } else {
            $frmAction = null;
        }
        $formActionBeanObject->frm_action = $frmAction;
        $formActionBeanObject->frm_action = $formActionBeanObject->frm_action=='Approve'?'Approved':$formActionBeanObject->frm_action;
        $formActionBeanObject->frm_action = $formActionBeanObject->frm_action=='Reject'?'Rejected':$formActionBeanObject->frm_action;

        $currentDate = new DateTime();
        $formActionBeanObject->frm_date = $currentDate->format('Y-m-d H:i:s');

        $formActionBeanObject->user_id = $current_user->id;
        if (!isset($params['form_user_id'])) {
            $formActionBeanObject->frm_user_id = $current_user->id;
            $formActionBeanObject->frm_user_name = $current_user->user_name;
        }
        $formActionBeanObject->save();
    }

    public function retrieveFlowIterations($flowData)
    {
        $flowBean = $this->retrieveBean('pmse_BpmFlow');

        $this->sugarQueryObject = $this->retrieveSugarQueryObject();
        $this->sugarQueryObject->select(array('id'));
        $this->sugarQueryObject->from($flowBean);
        $this->sugarQueryObject->where()
            ->queryAnd()
            ->addRaw('cas_id=' . $flowData['cas_id'] . ' AND bpmn_id=\'' . $flowData['bpmn_id'] . '\'');

        $rows = $this->sugarQueryObject->execute();
        return $rows;
    }

    public function terminateCase($flowData, $bean, $inboxStatus = 'COMPLETED')
    {
        //$this->closeThreadByCaseIndex($flowData['cas_id'], $flowData['cas_index']);
        //check the list of open threads
        $query = "select cas_thread_index, cas_flow_index from  pmse_bpm_thread where cas_id = {$flowData['cas_id']} and cas_thread_status = 'OPEN' ";
        $result = $bean->db->Query($query);

        while ($row = $bean->db->fetchByAssoc($result)) {

            $this->closeThreadByThreadIndex($flowData['cas_id'], $row['cas_thread_index']);
            $this->setCloseStatusForThisThread($flowData['cas_id'], $row['cas_thread_index']);
            //$this->bpmLog('DEBUG', "[$cas_id][$cas_index] thread {$row['cas_thread_index']} closed by Terminate End Event");
        }
        // Change status in flow when Activity status is FORM
        $this->terminateCaseFlow($flowData['cas_id']);
        //finally close the entire case
        $this->closeCase($flowData['cas_id'], $inboxStatus);
    }

    public function numberOfCasesByStatus($flowData, $status = 'IN PROGRESS')
    {
        $q = $this->retrieveSugarQueryObject();
        $q->select->setCountQuery();
        $q->from(BeanFactory::newBean('pmse_BpmFlow'));
        $q->where()->equals("cas_id", $flowData['cas_id']);
        $q->where()->equals("cas_flow_status", $status);
        return (int) $q->getOne();
    }
}
