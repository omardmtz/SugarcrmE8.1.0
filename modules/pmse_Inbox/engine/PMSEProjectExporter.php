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
 * Exports a record of Project
 *
 * This class extends the class PMSEExporter to export a record
 * from the tables related with a pmse_Project to transport it from one instance to another.
 * @package PMSE
 * @codeCoverageIgnore
 */
class PMSEProjectExporter extends PMSEExporter
{

    public function __construct()
    {
        $this->bean = BeanFactory::newBean('pmse_Project'); //new BpmEmailTemplate();
        $this->uid = 'id';
        $this->name = 'name';
        $this->extension = 'bpm';
    }

    /**
     * Method to retrieve a record of the database to export.
     * @param array $args
     * @return array
     */
    public function getProject(array $args)
    {
        $this->bean->retrieve($args['id']);

        $project = array();

        if ($this->bean->fetched_row != false) {
            $project= PMSEEngineUtils::unsetCommonFields($this->bean->fetched_row, array('name', 'description', 'assigned_user_id'));
            $project['process'] = $this->getProjectProcess($this->bean->id);
            $project['diagram'] = $this->getProjectDiagram($this->bean->id);
            $project['definition'] = $this->getProcessDefinition($this->bean->id);
            $project['dynaforms'] = $this->getDynaforms($this->bean->id);

            return array("metadata" => $this->getMetadata(), "project" => $project);
        } else {
            return array("error" => true);
        }
    }

    /**
     * Get the project process data
     * @return array
     */
    public function getProjectProcess($prjID)
    {
        $processBean = BeanFactory::newBean('pmse_BpmnProcess');
        $processData = array();
        $processBean->retrieve_by_string_fields(array("prj_id" => $prjID));
        if (!empty($processBean->fetched_row)) {
            $processData = PMSEEngineUtils::unsetCommonFields($processBean->fetched_row, array('name', 'description'));
            $processData = PMSEEngineUtils::sanitizeKeyFields($processData);
        }
        return $processData;
    }

    /**
     * Get the project Diagram data with a determined Project Id
     * @param string $prjID
     * @return array
     */
    public function getProjectDiagram($prjID)
    {
        $diagramBean = BeanFactory::newBean('pmse_BpmnDiagram'); //new BpmnDiagram();
        $diagramData = array();

        if ($diagramBean->retrieve_by_string_fields(array("prj_id" => $prjID))) {
            $diagramData = PMSEEngineUtils::unsetCommonFields($diagramBean->fetched_row, array('name', 'description', 'assigned_user_id'));

            //Get Activities
            $diagramData['activities'] = $this->getElementData($prjID, 'bpmnActivity');

            //Get Events
            $diagramData['events'] = $this->getElementData($prjID, 'bpmnEvent');

            //Get Gateways
            $diagramData['gateways'] = $this->getElementData($prjID, 'bpmnGateway');

            //Get Artifacts
            $diagramData['artifacts'] = $this->getArtifactData($prjID);

            //Get Flows
            $diagramData['flows'] = $this->getFlowData($prjID);

        }
        return $diagramData;
    }

    private function getElementData($prjID, $element)
    {
        $definition = $this->getElementDefinitions($element);
        if (empty($definition)){
            return array();
        }

        $activityBean = BeanFactory::newBean($definition['element']['module']);

        $q = new SugarQuery();
        $q->from($activityBean, array('add_deleted' => true));
        $q->distinct(false);
        $fields = $this->getFields($definition['element']['module'], array('id', 'name'));

        //INNER JOIN BOUND TABLE
        $q->joinTable('pmse_bpmn_bound', array('alias' => 'bound', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('id', 'bound.bou_element')
            ->equals('bound.deleted', 0);
        $fields = array_merge($fields, $this->getFields('pmse_BpmnBound', array(), 'bound'));

        //INNER JOIN DEFINITION TABLE
        $q->joinTable($definition['definition']['table'], array('alias' => 'def', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('id', 'def.id')
            ->equals('def.deleted', 0);
        $fields = array_merge($fields, $this->getFields($definition['definition']['module'], array(), 'def'));

        $q->where()
            ->equals('prj_id', $prjID)
            ->equals('bound.bou_element_type', $element);

        $q->select($fields);

        return $q->execute();
    }

    private function getArtifactData($prjID)
    {
        $artifactBean = BeanFactory::newBean('pmse_BpmnArtifact');

        $q = new SugarQuery();
        $q->from($artifactBean, array('add_deleted' => true));
        $q->distinct(false);
        $fields = $this->getFields('pmse_BpmnArtifact', array('id', 'name'));

        //INNER JOIN BOUND TABLE
        $q->joinTable('pmse_bpmn_bound', array('alias' => 'bound', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('id', 'bound.bou_element')
            ->equals('bound.deleted', 0);
        $fields = array_merge($fields, $this->getFields('pmse_BpmnBound', array(), 'bound'));

        $q->where()
            ->equals('prj_id', $prjID)
            ->equals('bound.bou_element_type', 'bpmnArtifact');

        $q->select($fields);

        return $q->execute();
    }

    private function getFlowData($prjID)
    {
        $flowBean = BeanFactory::newBean('pmse_BpmnFlow');

        $q = new SugarQuery();
        $q->from($flowBean, array('add_deleted' => true));
        $q->distinct(false);
        $fields = $this->getFields('pmse_BpmnFlow', array('id', 'name'));

        $q->where()
            ->equals('prj_id', $prjID);

        $q->select($fields);

        return $q->execute();
    }

    /**
     * Get the Process Definition data
     * @return array
     */
    public function getProcessDefinition($prjID)
    {
        $definitionBean = BeanFactory::newBean('pmse_BpmProcessDefinition');
        $definitionData = array();
        $definitionBean->retrieve_by_string_fields(array("prj_id" => $prjID));
        if (!empty($definitionBean->fetched_row)) {
            $definitionData = PMSEEngineUtils::unsetCommonFields($definitionBean->fetched_row,
                array('name', 'description'));
            $definitionData = PMSEEngineUtils::sanitizeKeyFields($definitionBean->fetched_row);
        }
        return $definitionData;
    }

    /**
     * Get the object list of dyanform records
     * @return array
     */
    public function getDynaForms($prjID)
    {
        $dynaFormBean = BeanFactory::newBean('pmse_BpmDynaForm');

        $q = new SugarQuery();
        $q->from($dynaFormBean, array('add_deleted' => true));
        $q->distinct(false);
        $fields = $this->getFields('pmse_BpmDynaForm', array('id'));

        $q->where()
            ->equals('prj_id', $prjID);

        $q->select($fields);

        return $q->execute();
    }

    /**
     * Additional processing to the Business Rules Data.
     * @param array $conditionArray
     * @return array
     */
    public function processBusinessRulesData($conditionArray = array())
    {
        if (is_array($conditionArray)) {
            foreach ($conditionArray as $key => $value) {
                if (isset($value->expType) && $value->expType == 'BUSINESS_RULES') {
                    $activityBeam = BeanFactory::getBean('pmse_BpmnActivity', $value->expField);
                    $conditionArray[$key]->expField = $activityBeam->act_uid;
                }
            }
        }
        return $conditionArray;
    }

    private function getFields($module, $except = array(), $alias = '')
    {
        $result = array();
        $rows = PMSEEngineUtils::getAllFieldsBean($module);
        $rows = PMSEEngineUtils::unsetCommonFields($rows, $except, true);
        if (!empty($alias)) {
            foreach ($rows as $value) {
                $result[] = array($alias . '.' . $value, $alias . '_' . $value);
            }
        } else {
            $result = $rows;
        }
        return $result;
    }

    private function getElementDefinitions($element) {
        $result = array();
        switch ($element){
            case 'bpmnActivity':
                $result = array(
                    'element' => array(
                        'module' => 'pmse_BpmnActivity',
                        'table' => 'pmse_bpmn_activity',
                    ),
                    'definition' => array(
                        'module' => 'pmse_BpmActivityDefinition',
                        'table' => 'pmse_bpm_activity_definition'
                    )
                );
                break;
            case 'bpmnEvent':
                $result = array(
                    'element' => array(
                        'module' => 'pmse_BpmnEvent',
                        'table' => 'pmse_bpmn_event',
                    ),
                    'definition' => array(
                        'module' => 'pmse_BpmEventDefinition',
                        'table' => 'pmse_bpm_event_definition'
                    )
                );
                break;
            case 'bpmnGateway':
                $result = array(
                    'element' => array(
                        'module' => 'pmse_BpmnGateway',
                        'table' => 'pmse_bpmn_gateway',
                    ),
                    'definition' => array(
                        'module' => 'pmse_BpmGatewayDefinition',
                        'table' => 'pmse_bpm_gateway_definition'
                    )
                );
                break;
        }
        return $result;
    }
}
