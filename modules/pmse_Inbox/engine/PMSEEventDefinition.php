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
 *
 * @deprecated
 */
class PMSEEventDefinition
{
    private $relDepBean;

    private $event;

    private $definition;

    private $db;

    public function __construct()
    {
        global $db;
        $this->relDepBean = BeanFactory::newBean('pmse_BpmRelatedDependency'); //new BpmRelatedDependency();
        $this->event = BeanFactory::newBean('pmse_BpmnEvent'); //new BpmnEvent();
        $this->definition = BeanFactory::newBean('pmse_BpmEventDefinition'); //new BpmEventDefinition();
        $this->db = $db;
    }

    /**
     * That method decode the event criteria string and insert into an array when
     * the expType is MODULE for evaluate purpose.
     * @param string eventCriteria
     * @param object event
     * @return array
     * @codeCoverageIgnore
     */
    private function processEventCriteria($eventCriteria, $event)
    {
        $criteria = json_decode($eventCriteria);
        $resultArray = array();
        if (is_array($criteria)) {
            foreach ($criteria as $token) {
                if ($token->expType == 'MODULE') {
                    $tmpObj = new stdClass();
                    $tmpObj->pro_id = $event->pro_id;
                    $tmpBean = BeanFactory::getBean('pmse_BpmProcessDefinition', $tmpObj->pro_id);
                    $tmpObj->rel_process_module = $tmpBean->pro_module;
                    $tmpObj->rel_element_id = $event->evn_id;
                    $tmpObj->rel_element_type = $event->evn_type . '_EVENT';
                    $tmpObj->rel_element_relationship = $token->expModule;

                    if ($tmpObj->rel_process_module == $token->expModule) {
                        $tmpObj->rel_element_module = $token->expModule;
                    } else {
                        // @codeCoverageIgnoreStart
                        $relBean = new Relationship();
                        $tmpObj->rel_element_module = $relBean->get_other_module($token->expModule,
                            $tmpObj->rel_process_module, $this->db);
                        // @codeCoverageIgnoreEnd
                    }

                    $resultArray[] = $tmpObj;
                }
            }
        }
        return $resultArray;
    }

    /**
     * That method removes all related dependencies by evn_id and pro_id
     * @param object event
     * @codeCoverageIgnore
     */
    private function removeEventRelatedDependencies($event)
    {
        $this->relDepBean->retrieve_by_string_fields(array(
                'rel_element_id' => $event->evn_id,
                'pro_id' => $event->pro_id
            ));
        $this->relDepBean->deleted = 1;
        $this->relDepBean->save();
    }

    /**
     * That method creates all dependencies related to this event and save them
     * @param array resultArray
     * @codeCoverageIgnore
     */
    private function createEventRelatedDependencies($resultArray)
    {
        foreach ($resultArray as $object) {
            //$relObject = new BpmRelatedDependency();
            $relObject = BeanFactory::newBean('pmse_BpmRelatedDependency');
            foreach ($object as $attrib => $value) {
                $relObject->$attrib = $value;
            }
            $relObject->save();
            $var = 2;
        }
    }

    /**
     * That method process the event related dependencies, here process criterias too.
     * @param object event
     * @codeCoverageIgnore
     */
    public function processEventRelatedDependencies($event)
    {
        $relatedArray = $this->processEventCriteria($event->evn_criteria, $event);
        $this->removeEventRelatedDependencies($event);
        $this->createEventRelatedDependencies($relatedArray);
    }
}