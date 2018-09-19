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

/*
 * Record List API implementation
 */

class PMSEEngineApi extends SugarApi
{
    /**
     * PMSECaseFlowHandler object
     * @var PMSECaseFlowHandler
     */
    private $caseFlowHandler;

    /**
     * PMSEUserAssignmentHandler object
     * @var PMSEUserAssignmentHandler
     */
    private $userAssignmentHandler;
    /**
     * PMSECaseWrapper object
     * @var PMSECaseWrapper
     */
    private $caseWrapper;

    public function __construct()
    {
        $this->caseFlowHandler = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
        $this->userAssignmentHandler = ProcessManager\Factory::getPMSEObject('PMSEUserAssignmentHandler');
        $this->pmse = PMSE::getInstance();
        $this->wrapper = ProcessManager\Factory::getPMSEObject('PMSEWrapper');
        $this->caseWrapper = ProcessManager\Factory::getPMSEObject('PMSECaseWrapper');
    }

    public function registerApiRest()
    {
        return array(
            'recordListCreate' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'engine_route'),
                'pathVars' => array('module', ''),
                'jsonParams' => array('filter'),
                'method' => 'engineRoute',
                'keepSession' => true,
                'shortHelp' => 'Evaluates the response of the user form Show Process [Approve, Reject, Route]',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_engine_route_help.html',
            ),
            'engineClaim' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'engine_claim'),
                'pathVars' => array('module', ''),
                'method' => 'engineClaim',
                'keepSession' => true,
                'shortHelp' => 'Claims the processes to the current user',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_engine_claim_help.html',
            ),
            'historyLogList' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox','historyLog','?'),
                'pathVars' => array('module','','filter'),
                'method' => 'retrieveHistoryLog',
                'keepSession' => true,
                'shortHelp' => 'Returns the history log for a process',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_retrieve_history_log_help.html',
            ),
            'noteList' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox','note_list','?'),
                'pathVars' => array('module','','cas_id'),
                'method' => 'getNotes',
                'keepSession' => true,
                'shortHelp' => 'Returns the notes list for a process',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_get_notes_help.html',
            ),
            'savenoteList' => array(
                'reqType' => 'POST',
                'path' => array('pmse_Inbox','save_notes'),
                'pathVars' => array('module',''),
                'method' => 'saveNotes',
                'keepSession' => true,
                'shortHelp' => 'Creates a new note for a process',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_save_notes_help.html',
            ),
            'deletenoteList' => array(
                'reqType' => 'DELETE',
                'path' => array('pmse_Inbox', 'delete_notes', '?'),
                'pathVars' => array('module', '', 'id'),
                'method' => 'deleteNotes',
                'keepSession' => true,
                'shortHelp' => 'Deletes a note for a process',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_delete_notes_help.html',
            ),
            'saveReassignRecord' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'ReassignForm'),
                'pathVars' => array('module', ''),
                'method' => 'reassignRecord',
                'keepSession' => true,
                'shortHelp' => 'Update the user assigned to the record associated to the process. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'saveAdhocReassign' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'AdhocReassign'),
                'pathVars' => array('module', ''),
                'method' => 'adhocReassign',
                'keepSession' => true,
                'shortHelp' => 'Update the process user associated to the process. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'getReassignRecord' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'ReassignForm', '?', '?'),
                'pathVars' => array('module', '', 'data', 'flowId'),
                'method' => 'getReassign',
                'keepSession' => true,
                'shortHelp' => 'Retrieve information for Assign to new user window. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'getAdhocReassign' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'AdhocReassign', '?', '?'),
                'pathVars' => array('module', '', 'data', 'flowId'),
                'method' => 'getAdhoc',
                'keepSession' => true,
                'shortHelp' => 'Retrieve information for Change Process User window. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'getChangeCaseUser' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'changeCaseUser', '?'),
                'pathVars' => array('module', '', 'cas_id'),
                'method' => 'changeCaseUser',
                'keepSession' => true,
                'shortHelp' => 'Retrieve information for Change Process User window. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'getUserListByTeam' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'userListByTeam', '?'),
                'pathVars' => array('module', '', 'id'),
                'method' => 'userListByTeam',
                'keepSession' => true,
                'shortHelp' => 'Retrieve users associated to a Team. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'reactivateFlows' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'reactivateFlows'),
                'pathVars' => array('module', ''),
                'jsonParams' => array('filter'),
                'method' => 'reactivateFlows',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Call methods to re-execute processes. DEPRECATED',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_deprecate.html',
            ),
            'reassignFlows' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'reassignFlows'),
                'pathVars' => array('module', ''),
                'jsonParams' => array('filter'),
                'method' => 'reassignFlows',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Call methods to reassign processes',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_reassign_flows_help.html',
            ),
            'getReassignFlows' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'reassignFlows', '?'),
                'pathVars' => array('module', '', 'record'),
                'jsonParams' => array('filter'),
                'method' => 'getReassignFlows',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Retrieve information to reassign processes',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_get_reassign_flows_help.html',
            ),
            'getModuleCase' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'case', '?', '?'),
                'pathVars' => array('module', 'case', 'id', 'idflow'),
                'method' => 'selectCase',
                'keepSession' => true,
                'shortHelp' => 'Retrieve information of the process record',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_select_case_help.html',
            ),
            // PMSE specific endpoint that allows bypassing of locked field ACLs
            'getCaseRecord' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'caseRecord', '?', '?'),
                'pathVars' => array('', '', 'module', 'record'),
                'method' => 'getCaseRecord',
            ),
            'cancelCases' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'cancelCases'),
                'pathVars' => array('module', ''),
                'jsonParams' => array('filter'),
                'method' => 'cancelCase',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Call methods to cancel a process',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_cancel_case_help.html',
            ),
            'getUnattendedCases' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'unattendedCases'),
                'pathVars' => array('module', ''),
                'method' => 'getUnattendedCases',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Retrieves the processes to show on Unattended Process view',
                'longHelp' =>  'modules/pmse_Inbox/clients/base/api/help/process_get_unattended_cases_help.html',
            ),
            'getSettingsEngine' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'settings'),
                'pathVars' => array('module', ''),
                'method' => 'getSettingsEngine',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Retrieve settings for the PA engine',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_get_settings_engine_help.html',
            ),
            'putSettingsEngine' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Inbox', 'settings'),
                'pathVars' => array('module', ''),
                'method' => 'putSettingsEngine',
                'keepSession' => true,
                'acl' => 'adminOrDev',
                'shortHelp' => 'Update settings for the PA engine',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_put_settings_engine_help.html',
            ),
        );
    }

    public function getNotes(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);

        $notesBean = BeanFactory::newBean('pmse_BpmNotes');
        $queryOptions = array('add_deleted' => true);
        $fields = array(
            'id',
            'date_entered',
            'date_modified',
            'cas_id',
            'cas_index',
            'not_user_id',
            'not_user_recipient_id',
            'not_type',
            'not_date',
            'not_status',
            'not_availability',
            'not_content',
            'not_recipients',
        );


        $q = new SugarQuery();
        $q->from($notesBean, $queryOptions);
        $q->distinct(false);
        $q->where()
            ->equals('cas_id', $args['cas_id']);
        $q->orderBy('date_entered', 'ASC');

        $q->joinTable('users', array('alias' => 'users', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('users.id', 'not_user_id')
            ->equals('users.deleted', 0);
        $fields[] = array("users.first_name", 'first_name');
        $fields[] = array("users.last_name", 'last_name');
        $fields[] = array("users.picture", 'picture');

        $q->select($fields);

        $rows = $q->execute();

        foreach($rows as $key => $value) {
            $rows[$key]['date_entered'] = PMSEEngineUtils::getDateToFE($value['date_entered'], 'datetime');
            $rows[$key]['date_modified'] = PMSEEngineUtils::getDateToFE($value['date_modified'], 'datetime');
        }

        $records['rowList'] = $rows;
        $records['totalRows'] = count($rows);
        $records['currentOffset'] = 0;
        $records['currentDate'] = TimeDate::getInstance()->nowDb();
        return $records;
    }

    public function saveNotes(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        global $current_user;
        //Create Notes
        $data = $args['data'];
        $notes = BeanFactory::newBean('pmse_BpmNotes');
        $notes->cas_id = $data['cas_id'];
        $notes->cas_index = $data['cas_index'];
        $notes->not_user_id = $current_user->id;
        $notes->not_user_recipient_id = null;
        $notes->not_type = (isset($data['not_type']) && !empty($data['not_type']) ? $data['not_type'] : 'GENERAL');
        $notes->not_date = '';
        $notes->not_status = 'ACTIVE';
        $notes->not_availability = '';
        $notes->not_content = $data['not_content'];
        $notes->not_recipients = '';
        $notes->save();
        return array('success' => true, 'id' => $notes->id, 'date_entered' => PMSEEngineUtils::getDateToFE($notes->date_entered,'datetime'));    }

    public function deleteNotes(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $notesBean = BeanFactory::getBean('pmse_BpmNotes', $args['id']);
        $notesBean->mark_deleted($notesBean->id);
        return array('id' => $args['id']);
    }

    public function retrieveHistoryLog(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $historyLog = ProcessManager\Factory::getPMSEObject('PMSEHistoryLogWrapper');
        $res = $historyLog->_get($args);
        return array('success' => true, 'result' => $res->result);
    }

    public function engineRoute(ServiceBase $api, array $args)
    {
        return $this->doEngineRoute($args);
    }

    /**
     * Process the request in the engine
     *
     * @param array $args Arguments array built by the service base
     * @return array The success status of the call
     */
    public function doEngineRoute(array $args)
    {
        // Needed to tell the save process to ignore locked field enforcement
        Registry\Registry::getInstance()->set('skip_locked_field_checks', true);

        // The handler will call to the preprocessor in this step
        $h = ProcessManager\Factory::getPMSEObject('RequestHandler', 'Direct');
        $h->executeRequest($args, false, null, strtoupper($args['frm_action']));

        // return the success request array
        return array('success' => true);
    }

    public function engineClaim(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $cas_id = $args['cas_id'];
        $cas_index = $args['cas_index'];

        $flowBean = BeanFactory::newBean('pmse_BpmFlow');
        $flowBean->retrieve_by_string_fields(array(
            'cas_id' => $cas_id,
            'cas_index' => $cas_index,
        ));

        if ($flowBean->cas_started != 1) {
            //get the bpm_activity_definition record, to check if it is SELFSERVICE
            if ($flowBean->cas_flow_status== 'FORM' && $flowBean->bpmn_type == 'bpmnActivity') {
                $activityDefinitionBean = BeanFactory::getBean('pmse_BpmActivityDefinition', $flowBean->bpmn_id);
                if (trim($activityDefinitionBean->act_assignment_method) == 'selfservice') {
                    global $current_user;
                    $flowBean->assigned_user_id = $current_user->id;
                    $flowBean->cas_user_id =  $current_user->id;
                    $flowBean->cas_assignment_method = 'static';
                }
            }
            $flowBean->cas_start_date = TimeDate::getInstance()->nowDb();
            $flowBean->cas_started = 1;
            $flowBean->save();
        }
        return array('success' => true);
    }

    public function reassignRecord(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $case = $args['data'];

        $cas_id = $case['cas_id'];
        $cas_index = $case['cas_index'];
        $caseData['cas_id'] = $cas_id;
        $caseData['cas_index'] = $cas_index;
        $case['not_type'] = "REASSIGN";
        $case['frm_comment'] = $case['reassign_comment'];
        $case['not_user_recipient_id'] = $case['reassign_user'];
        $case['cas_index'] = $cas_index;
        $this->caseFlowHandler->saveFormAction($case);
        $this->userAssignmentHandler->reassignRecord($caseData, $case['reassign_user']);
        return $case;
    }

    public function adhocReassign(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $case = $args['data'];
        $result = array('success' => true);
        $bean = BeanFactory::retrieveBean($case['moduleName'], $case['beanId']);
        // The handler will call to the preprocessor in this step
        $h = ProcessManager\Factory::getPMSEObject('RequestHandler', 'Direct');
        $h->executeRequest($case, false, $bean, 'REASSIGN');
        if(!empty($args['data']['not_content'])){
            $this->saveNotes($api,$args);
        }
        return $result;
    }

    public function getReassign(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $flowBeanObject = BeanFactory::getBean('pmse_BpmFlow', $args['flowId']);
        $args['cas_id'] = $flowBeanObject->cas_id;
        $args['cas_index'] = $flowBeanObject->cas_index;
        $result = array();
        $result['success'] = false;
        if (empty($args['cas_id']) && empty($args['cas_index'])) {
            return $result;
        }
        switch ($args['data']) {
            case 'users':
                $result = $this->getUsersListReassign($flowBeanObject, $args);
                $result['success'] = true;
                break;
            default:
                $result['data'] = $this->getFormDataReassign($args);
                $result['success'] = true;
                break;
        }
        return $result;
    }

    /**
     * This method gets the user list by case id and case index.
     * That user list is needed for reasign to listed user
     * @param array $args
     * @return array
     */
    public function getUsersListReassign($beanFlow, $filter = null)
    {
        $resultArray = array();
        $records = array();
        $resultArray['next_offset'] = -1;
        $userList = $this->userAssignmentHandler->getReassignableUserList($beanFlow, true, $filter);
        $i = 0;
        foreach ($userList as $user) {
            if ($i == (int)$filter['max_num']) {
                if (count($userList) > $filter['max_num']) {
                    array_pop($userList);               }
                $resultArray['next_offset'] = (int) ($filter['max_num'] + $filter['offset']);
                continue;
            }
            $tmpArray = array();
            $tmpArray['id'] = $user->id;
            $tmpArray['full_name'] = $user->full_name;
            $records[] = $tmpArray;
            $i++;
        }
        $resultArray['records'] = $records;
        return $resultArray;
    }

    /**
     * This method gets the form data from BpmnActivity or BpmnEvent, for purposes of reassignment of the case
     * @param array $args
     * @return array
     */
    public function getFormDataReassign(array $args)
    {
        $bpmFlow = new BpmFlow();
        $orderBy = '';
        $where = "cas_id='{$args['cas_id']}' AND cas_index='{$args['cas_index']}'";
        $joinedTables = array(
            array('INNER', 'bpmn_process', 'bpmn_process.pro_id=bpm_flow.pro_id'),
        );
        $flowList = $bpmFlow->getSelectRows($orderBy, $where, 0, -1, -1, array(), $joinedTables);
        foreach ($flowList['rowList'] as $flow) {
            switch ($flow['bpmn_type']) {
                case 'bpmnActivity':
                    $objectBean = new BpmnActivity();
                    $objectBean->retrieve_by_string_fields(array('act_id' => $flow['bpmn_id']));
                    $taskName = $objectBean->act_name;
                    break;
                case 'bpmnEvent':
                    $objectBean = new BpmnEvent();
                    $objectBean->retrieve_by_string_fields(array('evn_id' => $flow['bpmn_id']));
                    $taskName = $objectBean->evn_name;
                    break;
                default:
                    break;
            }
            $processName = $flow['pro_name'];
        }
        return array("process_name" => $processName, "process_task" => $taskName);
    }

    /**
     * GET data with client object.
     * Returns an object by id, with all user list, and form data. that id is passed into an array named args.
     * return the object constructed with the success attribute set to true if
     * records are obtained,the method returns false otherwise
     * @param array $args
     * @return object
     */
    public function getAdhoc(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $flowBeanObject = BeanFactory::getBean('pmse_BpmFlow', $args['flowId']);
        $args['cas_id'] = $flowBeanObject->cas_id;
        $args['cas_index'] = $flowBeanObject->cas_index;
        $result = array();
        $result['success'] = false;
        if (empty($args['cas_id']) && empty($args['cas_index'])) {
            return $result;
        }
        switch ($args['data']) {
            case 'users':
                $result = $this->getUsersListAdhoc($flowBeanObject, $args);
                $result['success'] = true;
                break;
            default:
                $result['data'] = $this->getFormDataAdhoc($args);
                $result['success'] = true;
                break;
        }
        return $result;
    }

    /**
     * This method gets the user list by case id and case index.
     * That user list is needed for reasign to listed user
     * @param array $args
     * @return array
     */
    public function getUsersListAdhoc($beanFlow, $filter = null)
    {
        $resultArray = array();
        $records = array();
        $resultArray['next_offset'] = -1;
        $userList = $this->userAssignmentHandler->getAdhocAssignableUserList($beanFlow, false, $filter);
        $i = 0;
        foreach ($userList as $user) {
            if ($i == (int)$filter['max_num']) {
                if (count($userList) > $filter['max_num']) {
                    array_pop($userList);               }
                $resultArray['next_offset'] = (int) ($filter['max_num'] + $filter['offset']);
                continue;
            }
            $tmpArray = array();
            $tmpArray['id'] = $user->id;
            $tmpArray['full_name'] = $user->full_name;
            $records[] = $tmpArray;
            $i++;
        }
        $resultArray['records'] = $records;
        return $resultArray;
    }

    /**
     * This method gets the form data from BpmnActivity or BpmnEvent, for purposes of reassignment of the case
     * @param array $args
     * @return array
     */
    public function getFormDataAdhoc(array $args)
    {
        $bpmFlow = BeanFactory::newBean('pmse_BpmFlow'); //new BpmFlow();
        $orderBy = '';
        $where = "cas_id='{$args['cas_id']}' AND cas_index='{$args['cas_index']}'";
        $joinedTables = array(
            array('INNER', 'bpmn_process', 'bpmn_process.pro_id=bpm_flow.pro_id'),
        );
        $flowList = $bpmFlow->getSelectRows($orderBy, $where, 0, -1, -1, array(), $joinedTables);
        foreach ($flowList['rowList'] as $flow) {
            switch ($flow['bpmn_type']) {
                case 'bpmnActivity':
                    $objectBean = new BpmnActivity();
                    $objectBean->retrieve_by_string_fields(array('act_id' => $flow['bpmn_id']));
                    $taskName = $objectBean->act_name;
                    break;
                case 'bpmnEvent':
                    $objectBean = new BpmnEvent();
                    $objectBean->retrieve_by_string_fields(array('evn_id' => $flow['bpmn_id']));
                    $taskName = $objectBean->evn_name;
                    break;
                default:
                    break;
            }
            $processName = $flow['pro_name'];
        }
        return array("process_name" => $processName, "process_task" => $taskName);
    }

    /**
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function changeCaseUser(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $time_data = $GLOBALS['timedate'];
        global $current_user;
        global $db;
        $res = array(); //new stdClass();
        $res['success'] = true;
        $get_actIds = "SELECT pmse_inbox.name,pmse_inbox.cas_id,cas_user_id,cas_delegate_date, cas_due_date, act_expected_time, act_assignment_method, act_assign_team, cas_index, act_assignment_method FROM pmse_inbox
                        LEFT JOIN pmse_bpm_flow ON pmse_inbox.cas_id = pmse_bpm_flow.cas_id
                        LEFT JOIN pmse_bpmn_activity ON pmse_bpm_flow.bpmn_type = 'bpmnActivity' and pmse_bpm_flow.bpmn_id  = pmse_bpmn_activity.id
                        INNER JOIN pmse_bpm_activity_definition ON pmse_bpmn_activity.id = pmse_bpm_activity_definition.id
                        WHERE pmse_inbox.cas_id = " . $args['cas_id'] . " AND cas_flow_status = 'FORM';";
        $result = $db->query($get_actIds);
        $tmpArray = array();

        while ($row = $db->fetchByAssoc($result)) {
            $time = json_decode(base64_decode($row['act_expected_time']));
            $tmpArray[] = array(
                'act_name' => $row['act_name'],
                'cas_id' => $row['cas_id'],
                'cas_user_id' => $row['cas_user_id'],
                'cas_delegate_date' => $time_data->to_display_date_time($row['cas_delegate_date'], true, true,
                    $current_user),
                'cas_due_date' => $time_data->to_display_date_time($row['cas_due_date'], true, true, $current_user),
                'act_assignment_method' => $row['act_assignment_method'],
                'act_assign_team' => $row['act_assign_team'],
                'cas_index' => $row['cas_index'],
                'act_expected_time' => $time->time . ' ' . $time->unit,
            );
        }
        $res['result'] = $tmpArray;
        return $res;
    }

    /**
     * Method that returns the user roles as Sugar
     * @return object
     */
    public function userListByTeam(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        global $db;
        $res = array(); //new stdClass();
        $res['success'] = true;
        $teams = (isset($args['id']) && !empty($args['id'])) ? "AND teams.id ='" . $args['id'] . "'" : '';
        $get_actIds = "SELECT DISTINCT(users.id) as id,first_name,last_name  FROM teams
                        LEFT JOIN team_memberships ON team_id = teams.id
                        INNER JOIN users ON users.id = team_memberships.user_id
                        WHERE private = 0 " . $teams;
        $result = $db->query($get_actIds);
        $tmpArray = array();

        while ($row = $db->fetchByAssoc($result)) {
            $tmpArray[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
        }
        $res['result'] = $tmpArray;
        return $res;
    }

    /**
     *
     * @global type $db
     * @global type $current_user
     * @param type $api
     * @param type $args
     * @return boolean
     */
    public function updateChangeCaseFlow(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        global $db;
        $res = array(); //new stdClass();
        $res['success'] = true;
        global $current_user;
        $today = TimeDate::getInstance()->nowDb();
        foreach ($args['data'] as $value) {
            if (is_array($value)) {
                $to = $value['cas_user_id'];
                $from = $value['old_cas_user_id'];
                $cas_id = $value['cas_id'];
                $cas_index = $value['cas_index'];


                $flowBean = BeanFactory::newBean('pmse_BpmFlow'); //new BpmFlow();
                //$flows = $flowBean->getSelectRows('', "cas_id = '$cas_id' and cas_index = '$cas_index'");

                $flows = $this->wrapper->getSelectRows($flowBean, '', "cas_id = '$cas_id' and cas_index = '$cas_index'",
                    0, -1, -1);
                $update_activity = "update pmse_bpm_flow set cas_flow_status = 'CLOSED', cas_user_id = $current_user->id where cas_id = " . $value['cas_id'] . " and cas_index = " . $value['cas_index'] . ";";
                $resultUpdate = $db->Query($update_activity);

                $query = "SELECT MAX(cas_index) as cas_index FROM pmse_bpm_flow WHERE cas_id = $cas_id";
                $result = $db->Query($query);
                $row = $db->fetchByAssoc($result);
                //create a BpmFlow row
                $flow = BeanFactory::newBean('pmse_BpmFlow'); //new BpmFlow();
                $flow->cas_id = $cas_id;
                $flow->cas_index = $row['cas_index'] + 1;
                $flow->cas_previous = $flows['rowList'][0]['bpmn_id'];
                $flow->pro_id = $flows['rowList'][0]['pro_id'];
                $flow->bpmn_id = $flows['rowList'][0]['bpmn_id'];
                $flow->bpmn_type = $flows['rowList'][0]['bpmn_type'];
                $flow->cas_user_id = $to;
                $flow->cas_thread = $flows['rowList'][0]['cas_thread'];
                $flow->cas_flow_status = $flows['rowList'][0]['cas_flow_status'];
                $flow->cas_sugar_module = $flows['rowList'][0]['cas_sugar_module'];
                $flow->cas_sugar_object_id = $flows['rowList'][0]['cas_sugar_object_id'];
                $flow->cas_sugar_action = $flows['rowList'][0]['cas_sugar_action'];
                $flow->cas_delegate_date = ($to != $from) ? $today : $flows['rowList'][0]['cas_delegate_date']; //$flows['rowList'][0]['cas_delegate_date'];
                $flow->cas_start_date = $flows['rowList'][0]['cas_start_date']; //all start events are started inmediately
                $flow->cas_finish_date = $flows['rowList'][0]['cas_finish_date'];
                $ts1 = strtotime($flows['rowList'][0]['cas_delegate_date']);
                $ts2 = strtotime($flows['rowList'][0]['cas_due_date']);
                $today_a = strtotime($today);
                $expectedTime = $ts2 - $ts1;
                //$expectedTime = date_diff($flows['rowList'][0]['cas_delegate_date'], $flows['rowList'][0]['cas_due_date']);
                (!empty($expectedTime)) ? $dueDate = date('Y-m-d H:i:s', $today_a + $expectedTime) : $dueDate = null;
                $flow->cas_due_date = ($to != $from) ? $dueDate : $flows['rowList'][0]['cas_due_date'];
                $flow->cas_queue_duration = $flows['rowList'][0]['cas_queue_duration'];
                $flow->cas_duration = $flows['rowList'][0]['cas_duration'];
                $flow->cas_delay_duration = $flows['rowList'][0]['cas_delay_duration'];
                $flow->cas_started = $flows['rowList'][0]['cas_started']; //all start events are started inmediately
                $flow->cas_finished = $flows['rowList'][0]['cas_finished'];
                $flow->cas_delayed = $flows['rowList'][0]['cas_delayed'];
                $flow->new_with_id = true;
                $flow->save();

                //$logger = new ADAMLogger();
                //$logger->log('INFO', "The admin has changed the assigned user [$from] by [$to], for case task with id [$cas_id]");
            }
        }
        return $res;
    }

    /**
     * Reactivate/Re-execute a flow list, the list is passed in the as an array
     * of id's in the $args['cas_id'] parameter.
     * @param type $api
     * @param type $args
     * @return boolean
     */
    public function reactivateFlows(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $h = ProcessManager\Factory::getPMSEObject('RequestHandler', 'Engine');
        foreach ($args['cas_id'] as $value) {
            $val['cas_id'] = $value;
            // The handler will call to the preprocessor in this step
            $h->executeRequest($val, false, null, 'RESUME_EXECUTION');
        }
        // return the success request array
        return array('success' => true);
    }

    /**
     * Cancel a case or multiple cases based on the $args['cas_id'] parameter
     * that should be an array of cases id's the case to be cancelled it shouldn't
     * be with CANCELLED, TERMINATED or COMPLETED status.
     * @param type $api
     * @param type $args
     * @return type
     */
    public function cancelCase(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $result = array('success' => true);
        try {
            foreach ($args['cas_id'] as $id) {
                $flowBean = BeanFactory::newBean('pmse_BpmFlow');
                $inboxBean = BeanFactory::newBean('pmse_Inbox');
                $inboxBean->retrieve_by_string_fields(array('cas_id' => $id));
                $flowBean->retrieve_by_string_fields(array('cas_id' => $id));
                $bean = BeanFactory::retrieveBean($flowBean->cas_sugar_module, $flowBean->cas_sugar_object_id);
                $auxCasId['cas_id'] = $id;
                if (($inboxBean->cas_status != 'CANCELLED' && $inboxBean->cas_status != 'TERMINATED' && $inboxBean->cas_status != 'COMPLETED')) {
                    $this->caseFlowHandler->terminateCase($auxCasId, $bean, 'CANCELLED');
                }

            }
        } catch (Exception $ex) {
            $result = array('success' => false, 'message' => $ex->getMessage());
        }
        return $result;
    }

    public function reassignFlows(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $result = array('success' => true);
        try {
            foreach ($args['flow_data'] as $flow) {
                $this->userAssignmentHandler->reassignCaseToUser($flow, $flow['user_id']);
            }
        } catch (Exception $ex) {
            $result = array('success' => false, 'message' => $ex->getMessage());
        }
        return $result;
    }

    /**
     * Returns a list of all activities that can be reassigned
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarQueryException
     */
    public function getReassignFlows(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $result = array('success' => true);

        // This is set to -1 because this API is not considering the max_num or
        // offset values and always will return all occurrences
        $result['next_offset'] = -1;

        $bpmFlow = BeanFactory::newBean('pmse_BpmFlow');

        $queryOptions = array('add_deleted' => (!isset($options['add_deleted']) || $options['add_deleted']) ? true : false);
        if ($queryOptions['add_deleted'] == false) {
            $options['select'][] = 'deleted';
        }
        $q = new SugarQuery();
        $q->from($bpmFlow, $queryOptions);
        $q->distinct(false);
        $fields = array(
            'cas_id',
            'cas_index',
            'cas_task_start_date',
            'cas_delegate_date',
            'cas_flow_status',
            'cas_user_id',
            'cas_due_date',
            'cas_sugar_module',
            'bpmn_id'
        );

        $q->where()
                ->equals('cas_flow_status', 'FORM');

        if (!empty($args['record'])) {
            $q->where()
                    ->equals('cas_id', $args['record']);
        }

        //INNER JOIN BPMN ACTIVITY
        $q->joinTable('pmse_bpmn_activity', array('alias' => 'activity', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('activity.id', 'bpmn_id')
                ->equals('activity.deleted', 0);
        $fields[] = array("activity.name", 'act_name');

        //INNER JOIN BPMN ACTIVITY DEFINTION
        $q->joinTable('pmse_bpm_activity_definition',
            array('alias' => 'activity_definition', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('activity_definition.id', 'activity.id')
            ->equals('activity_definition.deleted', 0);
        $fields[] = array("activity_definition.act_assignment_method", 'act_assignment_method');
        $fields[] = array("activity_definition.act_expected_time", 'act_expected_time');

        $q->select($fields);

        $rows = $q->execute();

        foreach ($rows as $key => $row) {
            //Expected time section
            $casData = new stdClass();
            $casData->cas_task_start_date = $row['cas_task_start_date'];
            $casData->cas_delegate_date = $row['cas_delegate_date'];
            $expectedTime = (!empty($row['act_expected_time'])) ? json_decode(base64_decode($row['act_expected_time'])) : '';
            $dueDate = (!empty($expectedTime) && !empty($expectedTime->time)) ? PMSEEngineUtils::processExpectedTime($expectedTime, $casData) : '';
            $expectedTime = PMSEEngineUtils::getExpectedTimeLabel($expectedTime);
            $rows[$key]['cas_expected_time'] = $expectedTime;
            $delegateDate = $rows[$key]['cas_delegate_date'];
            $rows[$key]['cas_delegate_date'] = !empty($delegateDate) ? PMSEEngineUtils::getDateToFE($delegateDate, 'datetime') : '';
            $rows[$key]['cas_due_date'] = !empty($dueDate) ? PMSEEngineUtils::getDateToFE($dueDate->format('Y-m-d H:i:s'), 'datetime') : '';
            //User section
            $user = BeanFactory::getBean("Users", $row["cas_user_id"]);
            $rows[$key]['assigned_user'] = $user->full_name;

            // Remove form list all attended cases
            if (!empty($args['unattended'])) {
                if (ACLAction::userHasAccess($row["cas_user_id"],$row['cas_sugar_module'], 'access')) {
                    if (!($user->status != 'Active' || $user->employee_status != 'Active')) {
                        unset($rows[$key]);
                    }
                }
            }
        }

        if (!empty($args['q'])) {
            $rowsLoad = array();
            foreach ($rows as $key => $row) {
                if (strstr(strtolower($row['assigned_user']), strtolower($args['q']))) {
                    $rowsLoad = $rows;
                }
            }
            $rows = $rowsLoad;
        }

        $rows = array_values($rows);
        $result['records'] = $rows;

        return $result;
    }

    public function getUnattendedCases(ServiceBase $api, array $args)
    {
        global $db;
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);

        $queryOptions = array('add_deleted' => true);
        // Get unatteneded cases based on user and employee statis
        $arrayUnattendedCases = $this->getUnattendedCasesByFlow();

        // Get unauthorized cases based on module access
        $arrayUnauthorizedCases = $this->getUnauthorizedCasesByFlow();
        //Get Cases IN TODO
        $beanInbox = BeanFactory::newBean('pmse_Inbox');
        $fields = array(
            'id',
            'assigned_user_id',
            'date_modified',
            'date_entered',
            'name',
            'cas_id',
            'cas_title',
            'cas_status',
            'pro_title',
            'pro_id',
            'cas_init_user'
        );
        $q = new SugarQuery();
        $q->from($beanInbox, $queryOptions);
        $q->distinct(false);
        $q->where()
                ->equals('cas_status', 'IN PROGRESS');

        $q->select($fields);
        if ($args['module_list'] == 'all' && !empty($args['q'])) {
            $q->where()->queryAnd()
                ->addRaw("pmse_inbox.pro_title LIKE '%" . $args['q'] . "%' ");
        } else if (!empty($args['q'])){
            switch($args['module_list']){
                case translate('LBL_CAS_ID', 'pmse_Inbox'):
                $q->where()->queryAnd()
                    ->addRaw("pmse_inbox.cas_id = " . $db->quoted($args['q']));
                    break;
                case translate('LBL_PROCESS_DEFINITION_NAME', 'pmse_Inbox'):
                $q->where()->queryAnd()
                    ->addRaw("pmse_inbox.pro_title LIKE '%" . $args['q'] . "%'");
                    break;
                case translate('LBL_OWNER', 'pmse_Inbox'):
                    $q->where()->queryAnd()
                        ->addRaw("pmse_inbox.cas_init_user LIKE '%" . $args['q'] . "%'");
                    break;
            }
        }

        if (isset($args['order_by'])) {
            $columnToSort = explode(":", $args["order_by"]);
            $q->orderBy($columnToSort[0], empty($columnToSort[1]) ? "asc" : $columnToSort[1]);
        }

        $rows = $q->execute();

        $rows_aux = array();

        $result = array();

        $arrayCases = array_merge($arrayUnattendedCases, $arrayUnauthorizedCases);
        foreach ($arrayCases as $key => $row) {
            $result[] = $row['cas_id'];
        }

        if (!empty($rows)) {
            foreach ($rows as $key => $row) {
                $arrayId = array_search($row['cas_id'], $result);
                if ($arrayId !== false) {
                    // Get the assigned bean early. This allows us to check for a bean
                    // id to determine if the bean has been deleted or not. This bean
                    // will also be used later to get the assigned user of the record.
                    $params = array('erased_fields' => true);
                    $assignedBean = BeanFactory::getBean($arrayCases[$arrayId]['cas_sugar_module'], $arrayCases[$arrayId]['cas_sugar_object_id'], $params);

                    $row = PMSEEngineUtils::appendNameFields($assignedBean, $row);

                    $usersBean = BeanFactory::getBean('Users', $arrayCases[$arrayId]['cas_user_id']);
                    $row['cas_user_full_name'] = $usersBean->full_name;
                    $processBean = BeanFactory::getBean('pmse_BpmnProcess', $row['pro_id']);
                    $row['prj_id'] = $processBean->prj_id;
                    $prjUsersBean = BeanFactory::getBean('Users', $processBean->assigned_user_id);
                    $row['prj_user_id_full_name'] = $prjUsersBean->full_name;
                    $row['cas_sugar_object_id'] = $arrayCases[$arrayId]['cas_sugar_object_id'];
                    $row['cas_sugar_module'] = $arrayCases[$arrayId]['cas_sugar_module'];
                    $assignedUsersBean = BeanFactory::getBean('Users', $assignedBean->assigned_user_id);
                    $row['assigned_user_name'] = $assignedUsersBean->full_name;
                    $row['date_entered'] = PMSEEngineUtils::getDateToFE($row['date_entered'], 'datetime');
                    $rows_aux[] = $row;
                }
            }
        }

        return array('next_offset' => '-1', 'records' => $rows_aux);
    }

    private function getUnattendedCasesByFlow()
    {
        $queryOptions = array('add_deleted' => true);

        //GET CASES ID WITH INACTIVE USERS
        $beanFlow = BeanFactory::newBean('pmse_BpmFlow');
        $q = new SugarQuery();
        $q->from($beanFlow, $queryOptions);
        $q->distinct(true);
        $fields = array('cas_id','cas_sugar_module','cas_sugar_object_id','cas_user_id');

        //INNER JOIN USERS TABLE
        $q->joinTable('users', array('alias' => 'users', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('users.id', 'cas_user_id');

        $q->where()
                ->equals('cas_flow_status', 'FORM')
                ->in('cas_sugar_module', PMSEEngineUtils::getSupportedModules());

        $q->where()
                ->queryOr()
                ->notequals('users.status', 'Active')
                ->notequals('users.employee_status', 'Active');

        $q->select($fields);

        $rows = $q->execute();

        return $rows;
    }

    /**
     * Return all the processes that have activities associated with an unauthorized user
     * @return array
     * @throws SugarQueryException
     */
    private function getUnauthorizedCasesByFlow()
    {
        $queryOptions = array('add_deleted' => true);

        $beanFlow = BeanFactory::newBean('pmse_BpmFlow');
        $q = new SugarQuery();
        $q->from($beanFlow, $queryOptions);
        $q->distinct(true);
        $fields = array('cas_id','cas_sugar_module','cas_sugar_object_id','cas_user_id');

        $q->where()
            ->equals('cas_flow_status', 'FORM')
            ->in('cas_sugar_module', PMSEEngineUtils::getSupportedModules());

        $q->select($fields);

        $rows = $q->execute();

        $return = array();

        foreach ($rows as $row) {
            if (!ACLAction::userHasAccess($row['cas_user_id'],$row['cas_sugar_module'],'access')) {
                $return[$row['cas_id']] = $row;
            }
        }

        return $return;
    }

    public function selectCase(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        $returnArray = array();
        $bpmFlow = BeanFactory::retrieveBean('pmse_BpmFlow', $args['idflow']);
        $returnArray['case']['flow'] = $bpmFlow->fetched_row;

        $activity = BeanFactory::getBean('pmse_BpmActivityDefinition', $bpmFlow->bpmn_id);
        if ($api->user->id != $bpmFlow->cas_user_id) {
            $teamsBean = BeanFactory::newBean('Teams');
            $teamArray = $teamsBean->getArrayAllAvailable(false, $api->user);
            if (($activity->act_assignment_method == 'selfservice' && empty($teamArray[$bpmFlow->cas_user_id]))
                || $activity->act_assignment_method == 'static'
                || $activity->act_assignment_method == 'balanced'
            ) {
                $sugarApiExceptionNotAuthorized = new SugarApiExceptionNotAuthorized('EXCEPTION_NOT_AUTHORIZED');
                PMSELogger::getInstance()->alert($sugarApiExceptionNotAuthorized->getMessage());
                throw $sugarApiExceptionNotAuthorized;
            }
        }

        $reclaimCaseByUser = false;
        if (isset($bpmFlow->cas_adhoc_type) && ($bpmFlow->cas_adhoc_type === '') && ($bpmFlow->cas_start_date == '') && ($activity->act_assignment_method == 'selfservice')) {
            $reclaimCaseByUser = true;
        }
        if ($reclaimCaseByUser) {
            $listButtons = array('claim', 'cancel');
        } elseif (isset($bpmFlow->cas_adhoc_type) && ($bpmFlow->cas_adhoc_type === '') && ($activity->act_response_buttons == '' || $activity->act_response_buttons == 'APPROVE')) {
            $listButtons = array('link_cancel', 'approve', 'reject', 'edit');
        } else {
            $listButtons = array('link_cancel', 'route', 'edit');
        }

        if (!$reclaimCaseByUser && !empty($bpmFlow->cas_adhoc_actions)) {
            $listButtons = json_decode($bpmFlow->cas_adhoc_actions);
        }
        $continue = array_search('continue', $listButtons);
        if ($continue !== false) {
            unset($listButtons[$continue]);
            $returnArray['case']['taskContinue'] = true;
        }
        $returnArray['case']['reclaim'] = $reclaimCaseByUser;
        $returnArray['case']['buttons'] = $this->getButtons($listButtons, $activity);
        $returnArray['case']['readonly'] = json_decode(base64_decode($activity->act_readonly_fields));
        $returnArray['case']['required'] = json_decode(base64_decode($activity->act_required_fields));

        $data_aux = new stdClass();
        $data_aux->cas_task_start_date = $returnArray['case']['flow']['cas_task_start_date'];
        $data_aux->cas_delegate_date = $returnArray['case']['flow']['cas_delegate_date'];

        // Commenting out below line. We don't want due date to be calculated dynamically. Once a process due date is set it should stay.
        // $returnArray['case']['title']['time'] = $this->caseWrapper->expectedTime($activity->act_expected_time, $data_aux);
        $returnArray['case']['title']['time'] = $this->caseWrapper->processDueDateTime($returnArray['case']['flow']['cas_due_date']);
        $bpmnProcess = BeanFactory::retrieveBean('pmse_BpmnProcess', $bpmFlow->pro_id);
        $returnArray['case']['title']['process'] = $bpmnProcess->name;
        $bpmnActivity = BeanFactory::retrieveBean('pmse_BpmnActivity', $bpmFlow->bpmn_id);
        $returnArray['case']['title']['activity'] = $bpmnActivity->name;
        $returnArray['case']['inboxId'] = $bpmnActivity->name;
        $returnArray['case']['flowId'] = $args['idflow'];
        $returnArray['case']['inboxId'] = $args['id'];
        return $returnArray;
    }

    /**
     * Gets a case record and bypasses locked field ACLs to allow for edits in
     * case management
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getCaseRecord(ServiceBase $api, array $args)
    {
        // Tell the Locked Field ACLs to not set
        Registry\Registry::getInstance()->set('skip_locked_field_checks', true);

        // See if we can intelligently find the right ModuleApi to load. Start by
        // getting a clone of the request object
        $apiReq = $api->getRequest();
        $req = clone $apiReq;

        // This will need to be removed from the current path
        $remove = 'pmse_Inbox/caseRecord/';

        // Change the path to be the module api path
        $req->parsePath(str_replace($remove, '', $req->getRawPath()));

        // Get the route, if there is one, otherwise use some defaults
        $route = $api->findRoute($req);

        // Fallback to ModuleApi::retrieveRecord when necessary, which should
        // cover a bulk of use cases
        $class = 'ModuleApi';
        $method = 'retrieveRecord';

        // This mimics logic from ServiceBase, but does away with exceptions since
        // if these criteria aren't met, we can simply fall back to ModuleApi
        if (!empty($route) && SugarAutoLoader::requireWithCustom($route['file']) && class_exists($route['className'])) {
            $class = $route['className'];
            $method = $route['method'];
        }

        // Create our object and consume it
        $moduleApi = new $class;
        return $moduleApi->$method($api, $args);
    }

    public function overrideButtons($flow, $listButtons)
    {
        if($flow->cas_adhoc_type == 'ONE_WAY') {
            $flowBean = BeanFactory::newBean('pmse_BpmFlow');
            $q = new SugarQuery();
            $q->select(array('cas_adhoc_type'));
            $q->from($flowBean);
            $q->where()
                ->queryAnd()
                ->equals('cas_id', $flow->cas_id)
                ->equals('cas_index', $flow->cas_previous);
            $result = $q->execute();
            if ($result[0]['cas_adhoc_type'] == 'ROUND_TRIP') {
                $listButtons = array('link_cancel', 'route', 'edit');
            } else {
                $listButtons = array('link_cancel', 'approve', 'reject', 'edit');
            }
        }
        return $listButtons;
    }

    public function getButtons($listButtons, $activity)
    {
        $module_name = 'pmse_Inbox';
        $buttons = array(
            'link_cancel' => array(
                'type' => 'button',
                'name' => 'cancel_button',
                'label' => translate('LBL_CANCEL_BUTTON_LABEL', $module_name),
                'css_class' => 'btn-invisible btn-link',
                'showOn' => 'edit',
                'events' => array(
                    'click' => 'button:cancel_button:click',
                ),
            ),
            'approve' => array(
                'type' => 'rowaction',
                'event' => 'case:approve',
                'name' => 'approve_button',
                'label' => translate('LBL_PMSE_LABEL_APPROVE', $module_name),
                'css_class' => 'btn btn-success',
            ),
            'reject' => array(
                'type' => 'rowaction',
                'event' => 'case:reject',
                'name' => 'reject_button',
                'label' => translate('LBL_PMSE_LABEL_REJECT', $module_name),
                'css_class' => 'btn btn-danger',
            ),
            'route' => array(
                'type' => 'rowaction',
                'event' => 'case:route',
                'name' => 'reject_button',
                'label' => translate('LBL_PMSE_LABEL_ROUTE', $module_name),
                'css_class' => 'btn btn-primary',
            ),
            'claim' => array(
                'type' => 'rowaction',
                'event' => 'case:claim',
                'name' => 'reject_button',
                'label' => translate('LBL_PMSE_LABEL_CLAIM', $module_name),
                'css_class' => 'btn btn-success',
            ),
            'cancel' => array(
                'type' => 'actiondropdown',
                'name' => 'main_dropdown',
                'primary' => true,
                'buttons' => array(
                    array(
                        'type' => 'rowaction',
                        'event' => 'case:cancel',
                        'name' => 'Cancel',
                        'label' => translate('LBL_PMSE_LABEL_CANCEL', $module_name),
                    ),
                    array(
                        'type' => 'rowaction',
                        'name' => 'history',
                        'label' => translate('LBL_PMSE_LABEL_HISTORY', $module_name),
                        'event' => 'case:history',
                    ),
                    array(
                        'type' => 'rowaction',
                        'name' => 'status',
                        'label' => translate('LBL_PMSE_LABEL_STATUS', $module_name),
                        'event' => 'case:status',
                    ),
                ),
            ),
            'edit' => array(
                'type' => 'actiondropdown',
                'name' => 'main_dropdown',
                'primary' => true,
                'showOn' => 'view',
                'buttons' => array(
                    array(
                        'type' => 'rowaction',
                        'event' => 'button:edit_button:click',
                        'name' => 'edit_button',
                        'label' => translate('LBL_EDIT_BUTTON_LABEL', $module_name),
                        'acl_action' => 'edit',
                    ),
                    array(
                        'type' => 'rowaction',
                        'name' => 'history',
                        'label' => translate('LBL_PMSE_LABEL_HISTORY', $module_name),
                        'event' => 'case:history',
                    ),
                    array(
                        'type' => 'rowaction',
                        'name' => 'status',
                        'label' => translate('LBL_PMSE_LABEL_STATUS', $module_name),
                        'event' => 'case:status',
                    ),
                    array(
                        'type' => 'rowaction',
                        'name' => 'add-notes',
                        'label' => translate('LBL_PMSE_LABEL_ADD_NOTES', $module_name),
                        'event' => 'case:add:notes',
                    ),
                ),
            ),
        );

        if (($activity->act_reassign || $activity->act_adhoc) && !in_array("claim", $listButtons)) {
            $buttons['edit']['buttons'][] = $buttons['cancel']['buttons'][] = array(
                'type' => 'divider',
            );
        }

        if ($activity->act_reassign && !in_array("claim", $listButtons)) {
            $buttons['edit']['buttons'][] = $buttons['cancel']['buttons'][] = array(
                'type' => 'rowaction',
                //'event' => '',
                'name' => 'find_duplicates_button',
                'label' => translate('LBL_PMSE_LABEL_CHANGE_OWNER', $module_name),
                'event' => 'case:change:owner',
            );
        }

        if ($activity->act_adhoc && !in_array("claim", $listButtons)) {
            $buttons['edit']['buttons'][] = $buttons['cancel']['buttons'][] = array(
                'type' => 'rowaction',
                //'event' => '',
                'name' => 'duplicate_button',
                'label' => translate('LBL_PMSE_LABEL_REASSIGN', $module_name),
                'event' => 'case:reassign',
            );
        }

        $arrayReturn = array();
        foreach ($listButtons as $button) {
            $arrayReturn[] = $buttons[$button];
        }
        return $arrayReturn;
    }

    public function getSettingsEngine(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        global $sugar_config;
        $settings = array();

        // If fields is specified then just retrieve a limited set
        if (isset($args['fields'])) {
            $fields = explode(",", $args['fields']);
            foreach ($fields as $field) {
                if (isset($sugar_config['pmse_settings_default'][$field])) {
                    $settings[$field] = $sugar_config['pmse_settings_default'][$field];
                }
            }
        } else {
            // else retrieve everything
            $settings = $sugar_config['pmse_settings_default'];
        }

        return $settings;
    }

    public function putSettingsEngine(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyUserAccess($api, $args);
        if (!empty($args['data'])) {
            $cfg = new Configurator();
            $cfg->config['pmse_settings_default'] = $args['data'];
            $cfg->handleOverride();
        }
        return array('success' => true, 'data' => $args['data']);
    }
}
