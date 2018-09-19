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
 * The ADAMWrapperActivityDefinition is a class part of the family of wrapper classes
 * that encapsulates a certain amount of logix in order to create, read, update and
 * delete BpmActivityDefinition records
 *
 */
class PMSEActivityDefinitionWrapper
{

    /**
     * Activity bean attribute
     * @var \BpmnActivity
     */
    protected $activity;

    /**
     * Activity Definition bean attribute
     * @var \BpmActivityDefinition
     */
    protected $activityDefinition;

    /**
     * Process Definition bean attribute
     * @var \BpmProcessDefinition
     */
    protected $processDefinition;

    /**
     * ADAM Bean Factory attribute
     * @var \ADAMBeanFactory
     */
    protected $factory;

    /**
     * Class constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->activity = BeanFactory::newBean('pmse_BpmnActivity');
        $this->activityDefinition = BeanFactory::newBean('pmse_BpmActivityDefinition');
        $this->processDefinition = BeanFactory::newBean('pmse_BpmProcessDefinition');
        $this->factory = new BeanFactory();
    }

    /**
     * Set the process definition attribute
     * @param \BpmProcessDefinition $processDefinition
     * @codeCoverageIgnore
     */
    public function setProcessDefinition($processDefinition)
    {
        $this->processDefinition = $processDefinition;
    }

    /**
     * Set the ADAM Bean factory attribute
     * @param \ADAMBeanFactory $factory
     * @codeCoverageIgnore
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * Set the Definition bean attribute.
     * @param \BpmActivityDefinition $activityDefinitionBean
     * @codeCoverageIgnore
     */
    public function setActivityDefinition($activityDefinitionBean)
    {
        $this->activityDefinition = $activityDefinitionBean;
    }

    /**
     * Set the activity bean attribute.
     * @param \BpmnActivity $activityBean
     * @codeCoverageIgnore
     */
    public function setActivity($activityBean)
    {
        $this->activity = $activityBean;
    }

    /**
     * The _get method obtains and processes the data from a determined Activity Definition record
     * @param array $args
     * @return array
     */
    public function _get(array $args)
    {
        $result = array("success" => false);
//        $this->activity = new BpmnActivity();
        $this->activity->retrieve_by_string_fields(array('act_uid' => $args['record']));
        if ($this->activity->fetched_row != false) {
//            $this->activityDefinition = new BpmActivityDefinition();
            $this->activityDefinition->retrieve($this->activity->id);
            if ($this->activityDefinition->fetched_row != false) {
                $result['success'] = true;
                if (empty($this->activityDefinition->fetched_row['act_readonly_fields'])) {
                    $this->activityDefinition->fetched_row['act_readonly_fields'] = $this->getDefaultReadOnlyFields();
                } else {
                    $this->activityDefinition->fetched_row['act_readonly_fields'] = $this->getReadOnlyFields(array(),
                        json_decode(base64_decode($this->activityDefinition->fetched_row['act_readonly_fields'])));
                }
                if (empty($this->activityDefinition->fetched_row['act_required_fields'])) {
                    $this->activityDefinition->fetched_row['act_required_fields'] = $this->getDefaultRequiredFields();
                } else {
                    $this->activityDefinition->fetched_row['act_required_fields'] = $this->getRequiredFields(array(),
                        json_decode(base64_decode($this->activityDefinition->fetched_row['act_required_fields'])));
                }
                if (empty($this->activityDefinition->fetched_row['act_expected_time'])) {
                    $this->activityDefinition->fetched_row['act_expected_time'] = $this->getDefaultExpectedTime();
                } else {
                    $this->activityDefinition->fetched_row['act_expected_time'] = json_decode(base64_decode($this->activityDefinition->fetched_row['act_expected_time']));
                }
                if (empty($this->activityDefinition->fetched_row['act_related_modules'])) {
                    $this->activityDefinition->fetched_row['act_related_modules'] = $this->getRelatedModules($args['module']);
                } else {
                    $this->activityDefinition->fetched_row['act_related_modules'] = $this->getRelatedModules($args['module'],
                        json_decode(base64_decode($this->activityDefinition->fetched_row['act_related_modules']))); //json_decode(base64_decode($this->activityDefinition->fetched_row['act_related_modules']));
                }
                if (!empty($this->activityDefinition->fetched_row['act_fields'])) {
                    $act_fields = json_decode($this->activityDefinition->fetched_row['act_fields']);
                    $needs_save = false;
                    foreach ($act_fields as &$act_field) {
                        if ($act_field->type == 'team_list') {
                            $needs_save = true;
                            $value = [];
                            foreach ($act_field->value as $team_id) {
                                $team_bean = BeanFactory::getBean('Teams', $team_id);
                                $team = new stdClass();
                                $team->id = $team_id;
                                $team->valid = isset($team_bean->id);
                                $value[] = $team;
                            }
                            $act_field->value = $value;
                        }
                    }
                    unset($act_field);
                    if ($needs_save) {
                        $this->activityDefinition->fetched_row['act_fields'] = json_encode($act_fields);
                    }
                }
                $result = array_merge($result, $this->activityDefinition->fetched_row);
            }
        }
//        $result = ADAMEngineUtils::sanitizeKeyFields($result);
        return $result;
    }

    /**
     * The _post method creates a new Activity Definition record based on the args data array
     * @param array $args
     * @return multitype:boolean
     */
    public function _post(array $args)
    {
        //$primaryKeyField = $this->getPrimaryFieldName($this->activity);
        $this->activity->retrieve_by_string_fields(array('act_uid' => $args['act_uid']));
        $data = array("success" => false);
        if (count($args) > 0) {
            if ($this->activity->fetched_row != false) {
                $args['id'] = $this->activity->id; //$primaryKeyField;
                foreach ($args as $key => $value) {
                    if ($key == 'act_readonly_fields' || $key == 'act_required_fields' || $key == 'act_expected_time') {
                        $this->activityDefinition->$key = base64_encode(json_encode($args[$key]));
                    } else {
                        $this->activityDefinition->$key = $args[$key];
                    }
                }
                if ($this->activityDefinition->act_assignment_method == 'static') {
                    $this->activityDefinition->act_assign_team = '';
                } else {
                    $this->activityDefinition->act_assign_user = '';
                }

                $this->activityDefinition->new_with_id = true;
                $this->activityDefinition->save();
                if (!$this->activityDefinition->in_save) {
                    $data = array("success" => true);
                }
            }
        }
        return $data;
    }

    /**
     * The _put method updates an Activity Definition record based on the args data array
     * @param array $args
     * @return boolean
     */

    public function _put(array $args)
    {
        $data = array("success" => false);
        if (isset($args['record']) && count($args) > 0) {
            if ($this->activity->retrieve_by_string_fields(array('act_uid' => $args['record']))) {
                if ($this->activity->fetched_row != false) {


                    $args['id'] = $this->activity->id;
                    $this->activityDefinition->retrieve($this->activity->id);

                    $args = $args['data'];

                    foreach ($args as $key => $value) {
                        if ($key == 'act_readonly_fields' || $key == 'act_required_fields' || $key == 'act_expected_time' || $key == 'act_related_modules') {
                            $this->activityDefinition->$key = base64_encode(json_encode($args[$key]));
                        } else {
                            $this->activityDefinition->$key = !empty($args[$key]) ? $args[$key] : '';
                        }
                    }
                    if ($this->activity->act_type != 'TASK') {
                        if ($this->activityDefinition->act_assignment_method == 'static') {
                            $this->activityDefinition->act_assign_team = '';
                        } else {
                            $this->activityDefinition->act_assign_user = '';
                        }
                    }
                    $this->activityDefinition->save();
                    if (!$this->activityDefinition->in_save) {
                        $data = array("success" => true);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * The unimplemented method deletes an activity defintion record
     * @param array $args
     * @codeCoverageIgnore
     */
    public function _delete(array $args)
    {

    }

    /**
     * This method gets the list of default read only fields based on the process definition.
     * @return array
     */
    public function getDefaultReadOnlyFields()
    {
        $this->processDefinition->retrieve($this->activityDefinition->pro_id);
        $bean = $this->factory->getBean($this->processDefinition->pro_module);
        $fieldsData = isset($bean->field_defs) ? $bean->field_defs : array();

        $output = array();
        foreach ($fieldsData as $field) {
            if (isset($field['vname']) && PMSEEngineUtils::isValidField($field, 'RR')) {
                $tmpField = array();
                $tmpField['name'] = $field['name'];
                $tmpField['label'] = str_replace(':', '',
                    translate($field['vname'], $this->processDefinition->pro_module));
                $tmpField['readonly'] = false;
                $output[] = $tmpField;
            }
        }

        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['label']);
        }
        array_multisort($text, SORT_ASC, $output);

        return $output;
    }

    /**
     * This method gets the list of default required fields based on the process definition.
     * @return array
     */
    public function getDefaultRequiredFields()
    {
        $this->processDefinition->retrieve($this->activityDefinition->pro_id);
        $bean = $this->factory->getBean($this->processDefinition->pro_module);
        $fieldsData = isset($bean->field_defs) ? $bean->field_defs : array();

        $output = array();
        foreach ($fieldsData as $field) {
            if (isset($field['vname']) && PMSEEngineUtils::isValidField($field, 'RR')) {
                if ($field['type'] != 'bool' && $field['type'] != 'radioenum' ) {
                    if (!(isset($field['required']) && $field['required'])) {
                        $tmpField = array();
                        $tmpField['name'] = $field['name'];
                        $tmpField['label'] = str_replace(':', '',
                            translate($field['vname'], $this->processDefinition->pro_module));
                        $tmpField['required'] = false;
                        $output[] = $tmpField;
                    }
                }
            }
        }

        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['label']);
        }
        array_multisort($text, SORT_ASC, $output);

        return $output;
    }

    /**
     * Get the list of configured read only fields.
     * @param array $fields
     * @param arary $readOnlyFields
     * @return array
     */
    public function getReadOnlyFields($fields = array(), $readOnlyFields = array())
    {
        $fields = empty($fields) ? $this->getDefaultReadOnlyFields() : $fields;
        foreach ($fields as $key => $field) {
            if (in_array($field['name'], $readOnlyFields)) {
                $field['readonly'] = true;
                $fields[$key] = $field;
            }
        }
        return $fields;
    }

    /**
     * Get the list of configured required fields.
     * @param array $fields
     * @param array $requiredFields
     * @return array
     */
    public function getRequiredFields($fields = array(), $requiredFields = array())
    {
        $fields = empty($fields) ? $this->getDefaultRequiredFields() : $fields;
        foreach ($fields as $key => $field) {
            if (in_array($field['name'], $requiredFields)) {
                $field['required'] = true;
                $fields[$key] = $field;
            }
        }
        return $fields;
    }

    /**
     * get the default expected time
     * @return \stdClass
     * @codeCoverageIgnore
     */
    public function getDefaultExpectedTime()
    {
        $output = new stdClass();
        $output->time = "";
        $output->unit = "hour";
        return $output;
    }

    /**
     * Get the list of related modules
     * @global array $beanList
     * @param String $filter
     * @param String $json
     * @return array
     * @codeCoverageIgnore
     */
    public function getRelatedModules($filter, $json = null)
    {
//        $res = new stdClass();
//        $res->search = $filter;
//        $res->success = true;
        global $beanList;
        if (isset($beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $newModuleFilter = array_search($filter, $beanList);
        }
        $output_11 = array();
        $output_1m = array();
        $output = array();
        $moduleBean = $this->factory->getBean($newModuleFilter);
        //$relatedModules = is_object($moduleBean) ? $moduleBean->get_linked_fields() : array();
        $filter_11 = 'one-to-one';
        $filter_1m = 'one-to-many';
        $ajaxRelationships = '';
        if (is_object($moduleBean)) {
            //$relationships = new DeployedRelationships($newModuleFilter);
            $relationships = $this->getDeployedRelationships($newModuleFilter);
            $ajaxRelationships = $this->getAjaxRelationships($relationships);
            if ("ProjectTask" == $newModuleFilter) {
                //special case
                $newModuleFilter = "Project Tasks";
            }
            $options = array(
                //array('value'=>'add','text'=>translate('LBL_PMSE_LABEL_ADD', 'ProcessMaker'),'checked'=>false),
                array('value' => 'view', 'text' => translate('LBL_PMSE_LABEL_VIEW', 'ProcessMaker'), 'checked' => false)
            );
            foreach ($ajaxRelationships as $related) {
                if (($newModuleFilter == $related['lhs_module'] || strtolower($newModuleFilter) == $related['lhs_table']) && $related['rhs_module'] != $newModuleFilter && ($related['relationship_type'] == $filter_11 || $related['relationship_type'] == $filter_1m)) {
                    $tmpField = array();
                    $tmpField['value'] = $related['name'];
                    $tmpField['text'] = $related['rhs_module']; // . " (" . $related['name'] . " - " . $related['relationship_type'] . ")";
                    $tmpField['options'] = isset($json) ? $this->searchModules($related['relationship_name'], $options,
                        $json) : $options;
                    if ($related['relationship_type'] == $filter_11) {
                        $output_11[] = $tmpField;
                    } else {
                        if ($related['relationship_type'] == $filter_1m) {
                            $output_1m[] = $tmpField;
                        }
                    }
                }
            }
            $output = array_merge($output_11, $output_1m);
        }
        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['label']);
        }
        array_multisort($text, SORT_ASC, $output);

//        $res->result = $output;
        return $output;
    }

    /**
     * Get the relationshipt list.
     * @param UserRelationship $relationships
     * @return string
     */
    public function getAjaxRelationships($relationships)
    {
        $ajaxrels = array();
        $relationshipList = $relationships->getRelationshipList();
        foreach ($relationshipList as $relationshipName) {
            $rel = $relationships->get($relationshipName)->getDefinition();
            $rel ['lhs_module'] = translate($rel['lhs_module']);
            $rel ['rhs_module'] = translate($rel['rhs_module']);

            //#28668  , translate the relationship type before render it .
            switch ($rel['relationship_type']) {
                case 'one-to-one':
                    $rel['relationship_type_render'] = translate('LBL_ONETOONE');
                    break;
                case 'one-to-many':
                    $rel['relationship_type_render'] = translate('LBL_ONETOMANY');
                    break;
                case 'many-to-one':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOONE');
                    break;
                case 'many-to-many':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOMANY');
                    break;
                default:
                    $rel['relationship_type_render'] = '';
            }
            $rel ['name'] = $relationshipName;
            if ($rel ['is_custom'] && isset($rel ['from_studio']) && $rel ['from_studio']) {
                $rel ['name'] = $relationshipName . "*";
            }
            $ajaxrels [] = $rel;
        }
        return $ajaxrels;
    }

    /**
     * Search and set the options that are checked for add or view
     * @param String $relationshipsName
     * @param array $options
     * @param String $json
     * @return boolean
     */
    public function searchModules($relationshipsName, $options, $json)
    {
        $relation = $json->$relationshipsName;
        foreach ($relation as $value) {
            if ($value == 'add') {
                $options[0]['checked'] = true;
            }
            if ($value == 'view') {
                $options[1]['checked'] = true;
            }
        }
        return $options;
    }


    /**
     * Get the deployed relationships based in a module filter
     * @param type $newModuleFilter
     * @return \DeployedRelationships
     * @codeCoverageIgnore
     */
    private function getDeployedRelationships($newModuleFilter)
    {
        $relationships = new DeployedRelationships($newModuleFilter);
        return $relationships;
    }
}
