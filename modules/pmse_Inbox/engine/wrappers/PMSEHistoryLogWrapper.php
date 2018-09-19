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
 * CRUD Wrapper to manage records related to History Logs
 *
 * An instance of this class can get, post, put and delete the record related to History Logs
 *
 */
class PMSEHistoryLogWrapper
{
    /**
     *
     * @var string
     */
    private $pre_data;

    /**
     *
     * @var string
     */
    private $post_data;

    /**
     *
     * @var long
     */
    private $start_time;

    /**
     *
     * @var long
     */
    private $end_time;

    /**
     *
     * @var object
     */
    private $formAction;

    /**
     *
     * @var object
     */
    private $flow;

    /**
     *
     * @var object
     */
    private $flowItself;

    /**
     *
     * @var integer
     */
    private $case_id;

    /**
     * Setting formActionBean, flowBean, userBean objects
     * @param int caseId
     */
    public function __construct($caseId = 0)
    {
        $this->case_id = $caseId;
        ///$this->formAction = new BpmFormAction();
        $this->formAction = BeanFactory::newBean('pmse_BpmFormAction');
        //$this->flowItself = new BpmnFlow();
        $this->flowItself = BeanFactory::newBean('pmse_BpmnFlow');
        //$this->flow = new BpmFlow();
        $this->flow = BeanFactory::newBean('pmse_BpmFlow');
        $this->data = new stdClass();
        $this->currentUser = new User();
        global $db;
        $this->db = $db;
    }

    /**
     * GET data with client object.
     * Returns assembly data object by id. that id is passed into an array named args.
     * return the object is constructed with the success attribute set to true if
     * records are obtained, false otherwise
     *
     * @param array $args
     * @return multitype:boolean Ambigous <string, array>
     */
    public function _get(array $args)
    {

        if (!isset($args) || !is_array($args) || sizeof($args) <= 0) {

            $this->data->error = translate('LBL_PMSE_ADAM_WRAPPER_HISTORYLOG_ARGUMENTEMPTY', 'pmse_Project');
            $this->data->result = false;
        } else {
            $this->case_id = (isset($args['filter']) ? $args['filter'] : 0);
            $this->data->case_fetch = $this->case_id;
            $this->data->result = $this->assemblyEntries();
            $this->data->success = sizeof($this->data->result) > 0;
        }


        return $this->data;
    }

    /**
     * POST data with client object.
     * This method overrides the bpmFormAction record object. that object is passed
     * into an array named args.
     *
     * @param array $args
     * @return object
     */
    public function _post(array $args)
    {
        //$result->message = translate('LBL_PMSE_ADAM_WRAPPER_HISTORYLOG_CANNOTUPDATELOG', 'pmse_Project');
        $this->data->success = false;
        return $this->data;
    }

    /**
     * PUT data with client object.
     * This method updates the bpmFormAction record object. that object is passed
     * into an array named args.
     * @param array $args
     * @return object
     */
    public function _put(array $args)
    {
        //$result = new stdClass();
        //$result->message = translate('LBL_PMSE_ADAM_WRAPPER_HISTORYLOG_CANNOTADDLOG', 'pmse_Project');
        $this->data->success = false;
        return $this->data;
    }

    /**
     * DELETE data with client object.
     * This method deletes the bpmFormAction record object by id. that object id is passed
     * into an array named args.
     * @param array $args
     * @return object
     */
    public function _delete(array $args)
    {
        //$result = new stdClass();
        //$this->data->message = translate('LBL_PMSE_ADAM_WRAPPER_HISTORYLOG_CANNOTDELETELOG', 'pmse_Project');
        $this->data->success = false;
        return $this->data;
    }

    /**
     *
     * Moved function from BpmInboxViewShowHistoryEntries class [view.showhistoryentries.php]
     * Using variable members and some fields added.
     */
    public function assemblyEntries()
    {
        $entries = array();
        $queryOptions = array('add_deleted' => true);
        $beanFlow = BeanFactory::newBean('pmse_BpmFlow');
        $fields = array(
            'id',
            'date_entered',
            'date_modified',
            'created_by',
            'cas_id',
            'cas_index',
            'pro_id',
            'cas_previous',
            'cas_reassign_level',
            'bpmn_id',
            'bpmn_type',
            'cas_assignment_method',
            'cas_user_id',
            'cas_thread',
            'cas_flow_status',
            'cas_sugar_module',
            'cas_sugar_object_id',
            'cas_sugar_action',
            'cas_adhoc_type',
            'cas_adhoc_parent_id',
            'cas_task_start_date',
            'cas_delegate_date',
            'cas_start_date',
            'cas_finish_date',
            'cas_due_date',
            'cas_queue_duration',
            'cas_duration',
            'cas_delay_duration',
            'cas_started',
            'cas_finished',
            'cas_delayed',
        );

        $q = new SugarQuery();
        $q->from($beanFlow, $queryOptions);
        $q->distinct(false);
        $q->where()
            ->equals('cas_id', $this->case_id);
        $q->orderBy('cas_index', 'ASC');

        $q->select($fields);

        $caseDerivations = $q->execute();

        foreach ($caseDerivations as $key => $caseData) {
            if ($caseData['bpmn_type'] == 'bpmnFlow') {
                // flow arrow
                continue;
            }

            if ($caseData['cas_previous'] == 0) {
                // start event, use the modifying user instead of the process user
                $caseData['cas_user_id'] = $caseData['created_by'];
            }

            $entry = $this->fetchUserType($caseData);

            $entry['due_date'] = !empty($caseData['cas_due_date']) ? PMSEEngineUtils::getDateToFE($caseData['cas_due_date'], 'datetime'): '';
            $entry['end_date'] = !empty($caseData['cas_finish_date']) ? PMSEEngineUtils::getDateToFE($caseData['cas_finish_date'], 'datetime'): '';
            $entry['current_date'] =  PMSEEngineUtils::getDateToFE(TimeDate::getInstance()->nowDb(), 'datetime');
            $entry['delegate_date'] = !empty($caseData['cas_delegate_date']) ? PMSEEngineUtils::getDateToFE($caseData['cas_delegate_date'], 'datetime'): '';
            $entry['start_date'] = !empty($caseData['cas_start_date']) ? PMSEEngineUtils::getDateToFE($caseData['cas_start_date'], 'datetime'): '';
            $entry['completed'] = true;
            $entry['cas_user_id'] = $caseData['cas_user_id'];

            $dataString = '';

            switch ($caseData['bpmn_type']) {
                case 'bpmnActivity':
                    $activityBean = BeanFactory::getBean('pmse_BpmnActivity', $caseData['bpmn_id']);
                    $name = sprintf(
                        translate('LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME', 'pmse_Inbox'),
                        $activityBean->name
                    );
                    if ($activityBean->act_task_type == 'USERTASK') {
                        // activity
                        if ($caseData['cas_assignment_method'] == 'selfservice') {
                            $this->setProcessUser($entry);
                            $dataString .= sprintf(
                                translate('LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE', 'pmse_Inbox'),
                                $name,
                                $this->getActivityModule($caseData)
                            );
                        } else {
                            if ($caseData['cas_flow_status'] == 'CLOSED') {
                                $formActionBean = BeanFactory::getBean('pmse_BpmFormAction');
                                $formActionBean->retrieve_by_string_fields(array('cas_id' => $caseData['cas_id'], 'act_id' => $caseData['bpmn_id']));
                                if ($formActionBean->frm_action) {
                                    $action = $formActionBean->frm_action;
                                } else {
                                    $action = translate('LBL_PMSE_HISTORY_LOG_ROUTED', 'pmse_Inbox');
                                }
                            } else {
                                $action = translate('LBL_PMSE_HISTORY_LOG_ASSIGNED', 'pmse_Inbox');
                            }
                            $dataString .= sprintf(
                                translate('LBL_PMSE_HISTORY_LOG_ACTIVITY', 'pmse_Inbox'),
                                $action,
                                $name,
                                $this->getActivityModule($caseData)
                            );
                        }
                    } else {
                        // action
                        switch ($activityBean->act_script_type) {
                            case 'ADD_RELATED_RECORD':
                                $type = translate('LBL_PMSE_CONTEXT_MENU_ADD_RELATED_RECORD', 'pmse_Project');
                                break;
                            case 'ASSIGN_TEAM':
                                $type = translate('LBL_PMSE_CONTEXT_MENU_ASSIGN_TEAM', 'pmse_Project');
                                break;
                            case 'ASSIGN_USER':
                                $type = translate('LBL_PMSE_CONTEXT_MENU_ASSIGN_USER', 'pmse_Project');
                                break;
                            case 'BUSINESS_RULE':
                                $type = translate('LBL_PMSE_CONTEXT_MENU_BUSINESS_RULE', 'pmse_Project');
                                break;
                            case 'CHANGE_FIELD':
                                $type = translate('LBL_PMSE_CONTEXT_MENU_CHANGE_FIELD', 'pmse_Project');
                                break;
                            default:
                                $type = $activityBean->act_script_type;
                        }
                        switch ($activityBean->act_script_type) {
                            case 'ASSIGN_USER':
                            case 'ASSIGN_TEAM':
                                $activityDefBean = BeanFactory::getBean('pmse_BpmActivityDefinition', $caseData['bpmn_id']);
                                if ($activityDefBean->act_update_record_owner == 1) {
                                    $action = translate('LBL_PMSE_HISTORY_LOG_AND', 'pmse_Inbox');
                                } else {
                                    $action = translate('LBL_PMSE_HISTORY_LOG_ON', 'pmse_Inbox');
                                }
                                $dataString .= sprintf(
                                    translate('LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION', 'pmse_Inbox'),
                                    $caseData['cas_id'],
                                    $action,
                                    $this->getActivityModule($caseData),
                                    $type,
                                    $name
                                );
                                break;
                            default:
                                $this->setProcessUser($entry);
                                $dataString .= sprintf(
                                    translate('LBL_PMSE_HISTORY_LOG_ACTION', 'pmse_Inbox'),
                                    $type,
                                    $name,
                                    $this->getActivityModule($caseData)
                                );
                        }
                    }
                    break;
                case 'bpmnEvent':
                    $eventBean = BeanFactory::getBean('pmse_BpmnEvent', $caseData['bpmn_id']);
                    $name = sprintf(
                        translate('LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME', 'pmse_Inbox'),
                        $eventBean->name
                    );
                    if ($caseData['cas_flow_status'] == 'CLOSED') {
                        $action = translate('LBL_PMSE_HISTORY_LOG_PROCESSED', 'pmse_Inbox');
                    } else {
                        $action = translate('LBL_PMSE_HISTORY_LOG_STARTED', 'pmse_Inbox');
                    }
                    switch ($eventBean->evn_type) {
                        case 'INTERMEDIATE':
                            if ($eventBean->evn_marker == 'TIMER') {
                                // wait event
                                $type = translate('LBL_PMSE_ADAM_DESIGNER_WAIT', 'pmse_Project');
                            } else {
                                if ($eventBean->evn_behavior == 'CATCH') {
                                    // receive message event
                                    $type = translate('LBL_PMSE_ADAM_DESIGNER_RECEIVE_MESSAGE', 'pmse_Project');
                                } else {
                                    // send message event
                                    $type = translate('LBL_PMSE_ADAM_DESIGNER_SEND_MESSAGE', 'pmse_Project');
                                }
                            }
                            $dataString .= sprintf(
                                translate('LBL_PMSE_HISTORY_LOG_EVENT', 'pmse_Inbox'),
                                $type,
                                $name,
                                $action
                            );
                            break;
                        case 'START':
                            // start event
                            $eventBean = BeanFactory::getBean('pmse_BpmEventDefinition', $caseData['bpmn_id']);
                            if ($eventBean->evn_params == 'new') {
                                $action = translate('LBL_PMSE_HISTORY_LOG_CREATED', 'pmse_Inbox');
                            } else {
                                $action = translate('LBL_PMSE_HISTORY_LOG_MODIFIED', 'pmse_Inbox');
                            }
                            $dataString .= sprintf(
                                translate('LBL_PMSE_HISTORY_LOG_START_EVENT', 'pmse_Inbox'),
                                $action,
                                $this->getActivityModule($caseData),
                                $caseData['cas_id']
                            );
                            break;
                        case 'END':
                            // end event
                            $type = translate('LBL_PMSE_HISTORY_LOG_END_EVENT', 'pmse_Inbox');
                            $dataString .= sprintf(
                                translate('LBL_PMSE_HISTORY_LOG_EVENT', 'pmse_Inbox'),
                                $type,
                                $name,
                                $action
                            );
                            break;
                        default:
                    }
                    break;
                case 'bpmnGateway':
                    $gatewayBean = BeanFactory::getBean('pmse_BpmnGateway', $caseData['bpmn_id']);
                    switch ($gatewayBean->gat_direction) {
                        case 'CONVERGING':
                            $direction = translate('LBL_PMSE_CONTEXT_MENU_CONVERGING', 'pmse_Project');
                            break;
                        case 'DIVERGING':
                            $direction = translate('LBL_PMSE_CONTEXT_MENU_DIVERGING', 'pmse_Project');
                            break;
                        default:
                            $direction = $gatewayBean->gat_direction;
                    }
                    switch ($gatewayBean->gat_type) {
                        case 'EVENTBASED':
                            $type = translate('LBL_PMSE_CONTEXT_MENU_EVENT_BASED_GATEWAY', 'pmse_Project');
                            break;
                        case 'EXCLUSIVE':
                            $type = translate('LBL_PMSE_CONTEXT_MENU_EXCLUSIVE_GATEWAY', 'pmse_Project');
                            break;
                        case 'INCLUSIVE':
                            $type = translate('LBL_PMSE_CONTEXT_MENU_INCLUSIVE_GATEWAY', 'pmse_Project');
                            break;
                        case 'PARALLEL':
                            $type = translate('LBL_PMSE_CONTEXT_MENU_PARELLEL_GATEWAY', 'pmse_Project');
                            break;
                        default:
                            $type = $gatewayBean->gat_type;
                    }
                    $name = sprintf(
                        translate('LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME', 'pmse_Inbox'),
                        $gatewayBean->name
                    );
                    $dataString .= sprintf(
                        translate('LBL_PMSE_HISTORY_LOG_GATEWAY', 'pmse_Inbox'),
                        $direction,
                        $type,
                        $name
                    );
                    break;
                default:
            }

            $dataString .= '.';
            $entry['data_info'] = $dataString;
            $entries[] = $entry;
        }
        return $entries;
    }

    /**
     * Get Module name by id. make the query and return the name of an sugar module
     * @param array $caseData
     * @return string
     */
    private function getActivityModule($caseData)
    {
        global $beanList;

        $activityDefinitionBean = BeanFactory::getBean('pmse_BpmActivityDefinition', $caseData['bpmn_id']);

        if (empty($activityDefinitionBean) ||
            empty($activityDefinitionBean->act_field_module) ||
            $activityDefinitionBean->act_field_module == $caseData['cas_sugar_module']) {
            return PMSEEngineUtils::getModuleLabelFromModuleName($beanList[$caseData['cas_sugar_module']]);
        }

        $relatedModule = $activityDefinitionBean->act_field_module;

        $sugarModuleBean = BeanFactory::getBean($caseData['cas_sugar_module'], $caseData['cas_sugar_object_id']);

        if ($sugarModuleBean == null) {
            return PMSEEngineUtils::getModuleLabelFromModuleName($relatedModule);
        }

        if (!$sugarModuleBean->load_relationship($relatedModule)) {
            return PMSEEngineUtils::getModuleLabelFromModuleName($relatedModule);
        }

        return PMSEEngineUtils::getModuleLabelFromModuleName($sugarModuleBean->$relatedModule->getRelatedModuleName());
    }
    
    /**
     * Get the relevant fields in the result array to the proper values
     * in the case of a generic Advanced Workflow user
     * @param array &$entry
     */
    private function setProcessUser(&$entry)
    {
        $entry['image'] = "label label-module label-pmse_Inbox pull-left";
        $entry['user'] = translate('LBL_PMSE_LABEL_PROCESS_AUTHOR', 'pmse_Inbox');
        $entry['script'] = true;
        $entry['show_user'] = false;
    }

    /**
     * Get the user data (type, image, name) by case user id. make the query and return an array
     * @param array $caseData
     * @return array ['image','user']
     */
    private function fetchUserType($caseData)
    {
        $entry = array('show_user' => true);

        $this->currentUser->retrieve($caseData['cas_user_id']);
        if ($caseData['cas_sugar_action'] == 'SCRIPTTASK') {
            $this->setProcessUser($entry);
        } else {
            if ($caseData['bpmn_type'] == 'bpmnActivity' ||
                $caseData['cas_sugar_action'] == 'DetailView' ||
                $caseData['cas_sugar_action'] == 'EditView' ||
                $caseData['cas_previous'] == 0
            ) {
                if (isset($this->currentUser->picture)) {
                    if ($this->currentUser->picture == '' || $this->currentUser->picture == null) {
                        $entry['image'] = 'include/images/default-profile.png';
                    } else {
                        $entry['image'] = 'index.php?entryPoint=download&id=' . $this->currentUser->picture . '&type=SugarFieldImage&isTempFile=1';
                    }
                } else {
                    $entry['image'] = 'include/images/default-profile.png';
                }
                $entry['user'] = $this->currentUser->full_name;
                global $current_user;
                $entry['current_user'] = $current_user->full_name;
            } else {
                //TODO check if there is other conditions to set.
                $this->setProcessUser($entry);
            }
        }
        // @codeCoverageIgnoreStart
        if (trim($entry['user']) == '') {
            $entry['user'] = sprintf(translate('LBL_PMSE_HISTORY_LOG_NOTFOUND_USER', 'pmse_Inbox'),
                $caseData['cas_user_id']);

        }
        // @codeCoverageIgnoreEnd
        return $entry;
    }

    /**
     * Get the action status and the action. Translate the action and obtain the equivalent string response
     * @param string $status
     * @param string $action
     * @return string
     */
    private function getActionStatusAndAction($status, $action)
    {
        if ($status == 'CLOSED') {
            $returnStatus = translate('LBL_PMSE_HISTORY_LOG_TASK_HAS_BEEN', 'pmse_Inbox');
        } else {
            if ($status == 'FORM') {
                $returnStatus = translate('LBL_PMSE_HISTORY_LOG_TASK_WAS', 'pmse_Inbox');
            } else {
                //status field is empty at this point.
                $returnStatus = translate('LBL_PMSE_HISTORY_LOG_TASK_WAS', 'pmse_Inbox');
            }
        }

        if ($action == 'SCRIPTTASK') {
            $returnAction = translate('LBL_PMSE_HISTORY_LOG_EDITED', 'pmse_Inbox');
        } else {
            if ($action == 'DetailView') {
                $returnAction = translate('LBL_PMSE_HISTORY_LOG_ROUTED', 'pmse_Inbox');
            } else {
                if ($action == 'None') {
                    $returnAction = translate('LBL_PMSE_HISTORY_LOG_CREATED', 'pmse_Inbox');
                } else {
                    $returnAction = translate('LBL_PMSE_HISTORY_LOG_DONE_UNKNOWN', 'pmse_Inbox');
                }
            }
        }
        return sprintf(" (%s %s)", $returnStatus, $returnAction);
    }

    /**
     * Set bpmFormAction object for this service instance.
     * @param mock instance $mock
     */
    public function setBpmFormAction($mock)
    {
        $this->formAction = $mock;
    }

    /**
     * Set bpmFlow object for this service instance.
     * @param mock instance $mock
     */
    public function setBpmFlow($mock)
    {
        $this->flow = $mock;
    }

    /**
     * Set bpmUser object for this service instance.
     * @param mock instance $mock
     */
    public function setUser($mock)
    {
        $this->currentUser = $mock;
    }

    /**
     * Set DB object for this service instance.
     * @param mock instance $mock
     */
    public function setDB($mock)
    {
        $this->db = $mock;
    }

}
