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
 * Description of PMSERelatedDependencyWrapper
 *
 */
class PMSERelatedDependencyWrapper
{
    /**
     *
     * @var type
     */
    protected $relationship;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     * @var type
     */
    protected $relatedModule;

    /**
     * Class constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->logger = PMSELogger::getInstance();
        $this->relationship = BeanFactory::newBean('Relationships');
        $this->relatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getRelationship()
    {
        return $this->relationship;
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
     * @param type $relationship
     * @codeCoverageIgnore
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
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
     * @param $relatedModule
     * @codeCoverageIgnore
     */
    public function setRelatedModule($relatedModule)
    {
        $this->relatedModule = $relatedModule;
    }

    /**
     *
     * @param type $module
     * @param type $id
     * @return type
     * @codeCoverageIgnore
     */
    public function getBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }

    /**
     *
     * @param type $id
     * @return type
     * @codeCoverageIgnore
     */
    public function getRelatedDependency($id = null)
    {
        return BeanFactory::getBean('pmse_BpmRelatedDependency', $id);
    }

    /**
     * That method process the event related dependencies, here process criterias too.
     * @param object event
     */
    public function processRelatedDependencies($eventData)
    {
        $this->logger->info("Processing related Dependencies");
        $relatedArray = $this->processEventCriteria($eventData['evn_criteria'], $eventData);
        $this->removeRelatedDependencies($eventData);
        $this->createRelatedDependencies($relatedArray);
    }

    /**
     *
     * @param type $eventCriteria
     * @param type $eventData
     * @return \stdClass
     */
    public function processEventCriteria($eventCriteria, $eventData)
    {

        $criteria = json_decode($eventCriteria);
        $this->logger->debug("Obtaining dependencies for the following criteria" . print_r($criteria, true));

        $resultArray = array();
        if ($eventData['evn_behavior'] !== 'CATCH') {
            return $resultArray;
        }

        if (is_array($criteria) && !empty($criteria)) {
            $criteriaModules = array();
            foreach ($criteria as $token) {
                if ($token->expType == 'MODULE') {
                    $tmpObj = new stdClass();
                    $tmpObj->pro_id = $eventData['pro_id'];
                    $processDefBean = $this->getBean('pmse_BpmProcessDefinition', $eventData['pro_id']);
                    $tmpObj->rel_process_module = $processDefBean->pro_module;
                    $tmpObj->rel_element_id = $eventData['id'];
                    $tmpObj->rel_element_type = $eventData['evn_type'] . '_EVENT';
                    $tmpObj->rel_element_relationship = $token->expModule;
                    $tmpObj->pro_module = $processDefBean->pro_module;
                    $tmpObj->pro_status = $processDefBean->pro_status;
                    $tmpObj->pro_locked_variables = $processDefBean->pro_locked_variables;
                    $tmpObj->pro_terminate_variables = $processDefBean->pro_terminate_variables;
                    $tmpObj->evn_id = $eventData['id'];

                    foreach ($eventData as $key => $value) {
                        if ($key != 'id') {
                            $tmpObj->$key = $value;
                        }
                    }

                    $this->getRelatedElementModule($tmpObj, $token);

                    if (!in_array($tmpObj->rel_element_module, $criteriaModules)) {
                        $resultArray[] = $tmpObj;
                        $criteriaModules[] = $tmpObj->rel_element_module;
                    }

                }
            }
            if (empty($resultArray)) {
                $tmpObj = new stdClass();
                $tmpObj->pro_id = $eventData['pro_id'];
                $processDefBean = $this->getBean('pmse_BpmProcessDefinition', $eventData['pro_id']);
                $tmpObj->pro_module = $processDefBean->pro_module;
                $tmpObj->pro_status = $processDefBean->pro_status;
                $tmpObj->pro_locked_variables = $processDefBean->pro_locked_variables;
                $tmpObj->pro_terminate_variables = $processDefBean->pro_terminate_variables;
                $tmpObj->evn_id = $eventData['id'];
                foreach ($eventData as $key => $value) {
                    if ($key != 'id') {
                        $tmpObj->$key = $value;
                    }
                }
                $resultArray[] = $tmpObj;
            }
        } else {
            $tmpObj = new stdClass();
            $tmpObj->pro_id = $eventData['pro_id'];
            $processDefBean = $this->getBean('pmse_BpmProcessDefinition', $eventData['pro_id']);
            $tmpObj->pro_module = $processDefBean->pro_module;
            $tmpObj->pro_status = $processDefBean->pro_status;
            $tmpObj->pro_locked_variables = $processDefBean->pro_locked_variables;
            $tmpObj->pro_terminate_variables = $processDefBean->pro_terminate_variables;
            $tmpObj->evn_id = $eventData['id'];
            foreach ($eventData as $key => $value) {
                if ($key != 'id') {
                    $tmpObj->$key = $value;
                }
            }
            $resultArray[] = $tmpObj;
        }
        return $resultArray;
    }

    /**
     *
     * @param type $tmpObject
     * @param type $tmpToken
     */
    public function getRelatedElementModule($tmpObject, $tmpToken)
    {
        $this->logger->debug("Obtaining Related Module for the token: " . print_r($tmpToken, true));

        if ($tmpObject->rel_process_module == $tmpToken->expModule) {
            $tmpObject->rel_element_module = $tmpToken->expModule;
        } else {
            $tmpObject->rel_element_module = $this->relatedModule->getRelatedModuleName($tmpObject->rel_process_module, $tmpToken->expModule);
        }
    }

    /**
     * That method removes all related dependencies by evn_id and pro_id
     * @param object event
     *
     */
    public function removeRelatedDependencies($eventData)
    {
        $this->logger->debug("Removing Related Dependencies for the event: " . print_r($eventData, true));

        $relatedDependency = $this->getRelatedDependency();
        while ($element = $relatedDependency->retrieve_by_string_fields(array(
                'evn_id' => $eventData['id'],
                'pro_id' => $eventData['pro_id']
            ))) {
            $element->deleted = 1;
            $element->save();
        }
    }

    /**
     * This method removes all pending event flows that are 'sleeping' and are
     * associated this this event.
     *
     * @param $eventData Object Event
     */
    public function removeActiveTimerEvents($eventData)
    {
        $this->logger->debug("Removing sleeping timer events for the event: " . print_r($eventData, true));

        $bpmFlowBean = BeanFactory::newBean('pmse_BpmFlow');
        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($bpmFlowBean);
        $sq->where()->equals('bpmn_id', $eventData['id'])->equals('cas_flow_status','SLEEPING');
        $result = $sq->execute();

        foreach ($result as $row) {
            $e = BeanFactory::getBean('pmse_BpmFlow', $row['id']);
            $e->cas_flow_status = 'DELETED';
            $e->save();

            // Update 'Process' to ERROR status
            $cas_id = $e->cas_id;
            $cf = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
            $cf->changeCaseStatus($cas_id, 'TERMINATED');
        }
    }

    /**
     * That method creates all dependencies related to this event and save them
     * @param array resultArray
     */
    public function createRelatedDependencies($resultArray)
    {
        foreach ($resultArray as $object) {
            $relatedDependency = $this->getBean('pmse_BpmRelatedDependency');
            foreach ($object as $attrib => $value) {
                $relatedDependency->$attrib = $value;
            }
            $relatedDependency->new_with_id = false;
            $relatedDependency->save();
        }
        $this->logger->debug("Creating " . count($resultArray) . " Related Dependencies.");

    }
}
