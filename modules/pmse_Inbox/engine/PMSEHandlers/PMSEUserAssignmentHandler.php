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
 * Description of PMSEUserAssignmentHandler
 *
 */
class PMSEUserAssignmentHandler
{
    /**
     *
     * @var PMSEWrapper
     */
    private $wrapper;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     *
     * Class constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->wrapper = ProcessManager\Factory::getPMSEObject('PMSEWrapper');
        $this->logger = PMSELogger::getInstance();
    }

    /**
     *
     * @return PMSEWrapper
     * @codeCoverageIgnore
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     *
     * @return PMSELogger
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
     * @param PMSEWrapper $wrapper
     * @codeCoverageIgnore
     */
    public function setWrapper($wrapper)
    {
        $this->wrapper = $wrapper;
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
     *
     * @param type $flowData
     * @return type
     */
    public function taskAssignment($flowData)
    {
        $activityBean = $this->retrieveBean('pmse_BpmnActivity'); //new BpmnActivity();
        $activityDefinitionBean = $this->retrieveBean('pmse_BpmActivityDefinition'); //new BpmActivityDefinition();
        $actId = $flowData['bpmn_id'];
        $activities = $activityBean->get_list('pmse_bpmn_activity.id', "pmse_bpmn_activity.id = '$actId'");
        $activityRow = get_object_vars($activities['list'][0]);
        $currentUserId = $flowData['cas_user_id'];
        $currentSugarId = $flowData['cas_sugar_object_id'];
        $currentSugarModule = $flowData['cas_sugar_module'];
        $today =  TimeDate::getInstance()->nowDb();

        $activitiesDef = $activityDefinitionBean->get_list('pmse_bpm_activity_definition.id',
            "pmse_bpm_activity_definition.id = '$actId' ", 0, -1, -1, array());
        if (!isset($activitiesDef['list'][0])) {
            //$this->bpmLog('ERROR', "[$flowData['cas_id']][$flowData['cas_index']] Activity Definition not found using act_id: $actId");
            $this->logger->error("[{$flowData['cas_id']}][{$flowData['cas_index']}] Activity Definition not found using act_id: $actId");
            $activityDefRow = array();
        } else {
            $activityDefRow = get_object_vars($activitiesDef['list'][0]);
        }
        $bpmnElement = array_merge($activityRow, $activityDefRow);
        //todo: throw an error if something was wrong
        //$expectedTimeObject = json_decode(base64_decode($activityDefRow['act_expected_time']));
        $caseData = new stdClass();
        $caseData->cas_start_date = '';
        $caseData->cas_delegate_date = $today;

        //$expectedTime = PMSEEngineUtils::processExpectedTime($expectedTimeObject, $caseData);
        //$dueDate = (!empty($expectedTime)) ? date('Y-m-d H:i:s', $expectedTime) : null;
        $activityType = $bpmnElement['act_task_type'];

        if ($activityType == 'SCRIPTTASK') {
            $cas_flow_status = 'SCRIPT';
            $cas_sugar_action = $activityType;
            //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] next flow is a script");
            $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] next flow is a script");
        } else {
            $cas_flow_status = 'FORM';
            $cas_sugar_action = $bpmnElement['act_type'];

            //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] next flow is an activity");
            $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] next flow is an activity");
            //check assignment rules
            $assignUser = (isset($bpmnElement['act_assign_user']) == true) ? $bpmnElement['act_assign_user'] : 'unknown';
            $assign_method = (isset($bpmnElement['act_assignment_method']) == true) ? strtolower($bpmnElement['act_assignment_method']) : 'unknown';
            $assign_team = (isset($bpmnElement['act_assign_team']) == true) ? $bpmnElement['act_assign_team'] : 'unknown';
            //$last_assigned = $bpmnElement['act_last_user_assigned'];

            if ($assign_method == 'static') {
                switch ($assignUser) {
                    case 'owner':
                        $currentUserId = $this->getRecordOwnerId($currentSugarId, $currentSugarModule);
                        break;
                    case 'supervisor':
                        $currentUserId = $this->getSupervisorId($currentUserId);
                        break;
                    case 'currentuser':
                        $currentUserId = $currentUserId; //$this->getCurrentUserId();
                        break;
                    default:
                        $currentUserId = $assignUser;
                        break;
                }
                //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] form assigned to user '$currentUserId'");
                $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] form assigned to user '$currentUserId'");
            } elseif ($assign_method == 'selfservice') {
                $currentUserId = $assign_team;
                //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] form assigned to team $currentUserId (Selfservice)");
                $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] form assigned to team $currentUserId (Selfservice)");
            } elseif ($assign_method == 'balanced') {
                $currentUserId = $this->getNextUserUsingRoundRobin($actId);
                //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] form assigned to user $currentUserId (Round Robin)");
                $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] form assigned to user $currentUserId (Round Robin)");
            } else {
                //$this->bpmLog('INFO', "[$flowData['cas_id']][$flowData['cas_index']] 'unknown' assigned to user $currentUserId");
                $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] 'unknown' assigned to user $currentUserId");
            }
            //parent::execute($flowData, $bean);
        }
        return $currentUserId;
    }

    /**
     * Realize the adhoc reassignment passing the caseData and the userID parameter and also
     * if the isRoundTripReassign option
     * @param type $caseData
     * @param type $userId
     * @param type $isRoundTripReassign
     * @return boolean
     */
    public function adhocReassign($caseData, $userId, $isRoundTripReassign = false, $isFormRequest = false)
    {
        $today = TimeDate::getInstance()->nowDb();
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseData['cas_user_id'] = $userId;

        $flowRow = $this->retrieveBean('pmse_BpmFlow');
        $flowRow->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));

        $selectFields = array("max(cas_index) as max_index");
        $maxIndexFlow = $this->wrapper->getSelectRows($caseBean, '', 'cas_id=' . $caseData['cas_id'], 0, -1, -1,
            $selectFields, array());

        $newFlowRow = $this->retrieveBean('pmse_BpmFlow');
        $newFlowRow->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));

        $newFlowRow->id = null;
        $newFlowRow->cas_index = $maxIndexFlow['rowList'][0]['max_index'] + 1;
        //$this->setCloseStatusInCaseFlow($caseData['cas_id'], $caseData['cas_index']);
        $newFlowRow->cas_previous = $caseData['cas_index'];
        $newFlowRow->cas_adhoc_type = isset($caseData['cas_adhoc_type']) ? $caseData['cas_adhoc_type'] : $flowRow->cas_adhoc_type;
        $newFlowRow->cas_task_start_date = !isset($flowRow->cas_task_start_date) ? $flowRow->cas_delegate_date : $flowRow->cas_task_start_date;
        $newFlowRow->cas_delegate_date = $today;
        if ($newFlowRow->cas_adhoc_type == 'ONE_WAY') {
            $newFlowRow->cas_adhoc_actions = $flowRow->cas_adhoc_actions;
        } else {
            if ($isFormRequest) {
                $newFlowRow->cas_adhoc_actions = json_encode(array('link_cancel', 'route', 'edit'));
            } else {
                $newFlowRow->cas_adhoc_actions = $caseData['cas_adhoc_actions'];
            }
        }

        if ($newFlowRow->cas_adhoc_type != $flowRow->cas_adhoc_type) {
            $newFlowRow->cas_adhoc_parent_id = $flowRow->id;
        } else {
            $newFlowRow->cas_adhoc_parent_id = $flowRow->cas_adhoc_parent_id;
        }

        if ($isRoundTripReassign) {
            $newFlowRow->cas_reassign_level--;
        } else {
            $newFlowRow->cas_reassign_level++;
        }

        if ($newFlowRow->cas_reassign_level <= 0) {
            $newFlowRow->cas_adhoc_type = "";
        }

        $caseData['cas_index'] = $newFlowRow->cas_index;
        //$caseBean->new_with_id = true;
        $newFlowRow->save();
        //$caseBean->create($flowRow);


        return $this->reassignCaseToUser($caseData, $userId);
    }

    /**
     * Reassign case to the first user.
     * @param type $caseData
     * @param type $userId
     * @return boolean
     */
    public function originReassign($caseData, $userId)
    {
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseData['cas_user_id'] = $userId;

        $where = 'cas_id=' . $caseData['cas_id'] . ' AND cas_index=' . $caseData['cas_index'];

        $flowList = $caseBean->get_list('', $where);
        $flowRow = $flowList['list'][0];
        $selectFields = array("max(cas_index) as max_index");
        $maxIndexFlow = $this->wrapper->getSelectRows($caseBean, '', 'cas_id=' . $caseData['cas_id'], 0, -1, -1,
            $selectFields, array());

        $newFlowRow = $this->retrieveBean('pmse_BpmFlow');
        $newFlowRow->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $newFlowRow->id = null;
        $newFlowRow->cas_index = $maxIndexFlow['rowList'][0]['max_index'] + 1;
        $newFlowRow->cas_previous = $caseData['cas_index'];
        $newFlowRow->cas_adhoc_type = $caseData['cas_adhoc_type'];
        $newFlowRow->cas_adhoc_parent_id = $caseData['cas_adhoc_parent_id'];
        $newFlowRow->cas_adhoc_actions = $caseData['cas_adhoc_actions'];
        $newFlowRow->cas_task_start_date = isset($flowRow->cas_task_start_date) ? $flowRow->cas_delegate_date : $flowRow->cas_task_start_date;
        $newFlowRow->cas_reassign_level = $caseData['cas_reassign_level'];

        $caseData['cas_index'] = $newFlowRow->cas_index;
        $newFlowRow->save();
        return $this->reassignCaseToUser($caseData, $userId);
    }

    /**
     * Reassign a case to a determined user (alias for method reassignRecordToUser)
     * @param type $caseData
     * @param type $reassignToUser
     * @return type
     * @codeCoverageIgnore
     */
    public function reassignRecord($caseData, $reassignToUser = '')
    {
        return $this->reassignRecordToUser($caseData, $reassignToUser);
    }

    /**
     * Round trip reassign of a case.
     * @param type $caseData
     */
    public function roundTripReassign($caseData)
    {
        $db = DBManagerFactory::getInstance();
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $previousFlow = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $where = sprintf(
            'bpmn_id=%s AND cas_id=%d AND bpmn_type=%s AND bpmn_id=%s AND cas_reassign_level=%d AND cas_index=
                (SELECT max(cas_index) FROM pmse_bpm_flow WHERE cas_id=%d AND cas_thread=%d AND cas_reassign_level=%d)',
            $db->quoted($caseBean->bpmn_id),
            $caseData['cas_id'],
            $db->quoted($caseBean->bpmn_type),
            $db->quoted($caseBean->bpmn_id),
            ($caseBean->cas_reassign_level - 1),
            $caseData['cas_id'],
            $caseData['cas_thread'],
            ($caseBean->cas_reassign_level - 1)
        );
        $previousFlowRecord = $previousFlow->get_full_list('', $where);
        //$previousFlowRecord = $this->wrapper->getSelectRows($previousFlow, '', $where);
        $previousFlowRecord = $previousFlowRecord[0];
        $caseData['cas_adhoc_actions'] = $previousFlowRecord->cas_adhoc_actions;
        $this->adhocReassign($caseData, $previousFlowRecord->cas_user_id, true);
    }

    /**
     * Check if the task and step executed is of type Round Trip
     * @param type $caseData
     * @return boolean
     */
    public function isRoundTrip($caseData)
    {
        $result = false;
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        if ($caseBean->bpmn_type == 'bpmnActivity' && $caseBean->cas_adhoc_type == 'ROUND_TRIP') {
            $result = true;
        }
        return $result;
    }

    /**
     * This executes the reassignment of a case if that the reassignment should be in one way.
     * @param type $caseData
     */
    public function oneWayReassign($caseData)
    {
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $firstDerivatedFlow = $this->retrieveBean('pmse_BpmFlow', $caseBean->cas_adhoc_parent_id); //new BpmFlow();
        $originalFlow = $this->retrieveBean('pmse_BpmFlow', $firstDerivatedFlow->cas_adhoc_parent_id); //new BpmFlow();
        $caseData['cas_adhoc_type'] = $originalFlow->cas_adhoc_type;
        $caseData['cas_adhoc_parent_id'] = $originalFlow->cas_adhoc_parent_id;
        $caseData['cas_adhoc_actions'] = $originalFlow->cas_adhoc_actions;
        $caseData['cas_reassign_level'] = $originalFlow->cas_reassign_level;
        $this->originReassign($caseData, $originalFlow->cas_user_id);
    }

    /**
     * Check if the reassignment task is one way
     * @param type $caseData
     * @return boolean
     */
    public function isOneWay($caseData)
    {
        $result = false;
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        if ($caseBean->bpmn_type == 'bpmnActivity' && $caseBean->cas_adhoc_type == 'ONE_WAY') {
            $result = true;
        }
        return $result;
    }

    /**
     * Check if the reassignment task is one way
     * @param type $caseData
     * @return boolean
     */
    public function previousIsNormal($caseData)
    {
        $result = false;
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseBean->cas_id,
                'cas_index' => $caseBean->cas_previous
            ));
        if ($caseBean->bpmn_type == 'bpmnActivity' && $caseBean->cas_adhoc_type == '') {
            $result = true;
        }
        return $result;
    }

    /**
     * Reassign a case to a determined user.
     * @param type $caseData
     * @param type $userId
     * @return boolean
     */
    public function reassignCaseToUser($caseData, $userId)
    {
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $caseBean->cas_user_id = $userId;
        if ($caseBean->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reassign the ownership to a determined user
     * @param type $caseData
     * @param type $userId
     * @return boolean
     */
    public function reassignRecordToUser($caseData, $userId)
    {
        $caseBean = $this->retrieveBean('pmse_BpmFlow'); //$this->beanFactory->getBean('BpmFlow');
        $caseBean->retrieve_by_string_fields(array(
                'cas_id' => $caseData['cas_id'],
                'cas_index' => $caseData['cas_index']
            ));
        $beanObject = $this->retrieveBean($caseBean->cas_sugar_module,
            $caseBean->cas_sugar_object_id); //$this->beanFactory->getBean($caseBean->cas_sugar_module);
        //$beanObject->retrieve($caseBean->cas_sugar_object_id);
        $beanObject->assigned_user_id = $userId;

        if (PMSEEngineUtils::saveAssociatedBean($beanObject)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Whenever a reassignment with round trip takes place, there exists a list of users
     * already reassigned using this option, this method obtains that list.
     *
     * @param type $caseId
     * @param type $bpmnId
     * @param type $bpmnType
     * @param type $casReassignLevel
     * @return type
     */
    public function getReassignedUserList($caseId, $bpmnId, $bpmnType, $casReassignLevel = 0)
    {
        $flowBean = $this->retrieveBean('pmse_BpmFlow'); //new BpmFlow();
        $where = 'cas_id=' . $caseId . ' AND bpmn_id=\'' . $bpmnId . '\' AND cas_reassign_level<=' . $casReassignLevel . ' AND bpmn_type=\'' . $bpmnType . '\' AND cas_index IN (SELECT max(cas_index) FROM pmse_bpm_flow WHERE cas_id=' . $caseId . ' AND bpmn_id=\'' . $bpmnId . '\' AND cas_reassign_level<=' . $casReassignLevel . ' GROUP BY cas_reassign_level)';
        $reassignList = $flowBean->get_full_list('', $where);
        //$reassignList = $this->wrapper->getSelectRows($flowBean, '', $where);
        //$reassignList = $reassignList['rowList'];
        $assignedUsers = array();
        foreach ($reassignList as $reassign) {
            $assignedUsers[] = $reassign->cas_user_id;
        }
        return $assignedUsers;
    }

    /**
     * Get the list of assignable users to a determinate task
     * (alias for the getAssignableUserList method)
     * @param type $caseId
     * @param type $caseIndex
     * @param type $fullList
     * @return type
     * @codeCoverageIgnore
     */
    public function getReassignableUserList($beanFlow, $fullList = false, $filter = null)
    {
        return $this->getAssignableUserList($beanFlow, $fullList, 'REASSIGN', $filter);
    }

    /**
     * Get the list of assignable users to a determinate task using adhoc reassignment
     * (alias for the getAssignableUserList method)
     * @param type $caseId
     * @param type $caseIndex
     * @param type $fullList
     * @return type
     * @codeCoverageIgnore
     */
    public function getAdhocAssignableUserList($beanFlow, $fullList = false, $filter = null)
    {
        return $this->getAssignableUserList($beanFlow, $fullList, 'ADHOC', $filter);
    }

    /**
     * Get the user list of assignable users
     * @param $beanFlow
     * @param bool $fullList
     * @param string $type
     * @return array
     */
    public function getAssignableUserList($beanFlow, $fullList = false, $type = 'ADHOC', $filter = null)
    {
        $membersList = array();
        $membersIds = array();
        $reassignedUsers = array();
        $assignableUsers = array();
        if (!$fullList) {
            $reassignedUsers = $this->getReassignedUserList($beanFlow->cas_id, $beanFlow->bpmn_id, $beanFlow->bpmn_type,
                $beanFlow->cas_reassign_level);
        }
        $activityDefinition = $this->retrieveBean('pmse_BpmActivityDefinition');
        $memberList = array();
        if ($beanFlow->bpmn_type == 'bpmnActivity') {
            $activityDefinition->retrieve($beanFlow->bpmn_id);
            $teamBean = $this->retrieveBean('Teams'); //$this->beanFactory->getBean('Teams');
            $teamId = ($type == 'ADHOC') ? $activityDefinition->act_adhoc_team : $activityDefinition->act_reassign_team;
            if ($teamId == 'current_team') {
                global $current_user;
                $teamList = $teamBean->getTeamsByUser($current_user->id);
                foreach ($teamList as $team) {
                    if ($team->id != '1' && $team->id != 1) {
                        $teamProxy = $teamBean->getTeamObject();
                        $teamProxy->setTeamObject($team);
                        $teamBean->setTeamObject($teamProxy);
                        $members = $teamBean->getMembers();
                        foreach ($members as $member) {
                            if (!in_array($member->id, $membersIds)) {
                                $membersList[] = $member;
                                $membersIds[] = $member->id;
                            }
                        }
                        $memberList = array_merge($members, $memberList);
                    }
                }
            } else {
                $teamBean = $this->retrieveBean('Teams', $teamId);
                //$membersList = $teamBean->get_team_members(true, $filter);
                $membersList = $this->getTeamMembers($teamBean, $filter);
                usort($membersList, function ($a, $b) {
                    return strcmp($a->full_name, $b->full_name);
                });
            }
        }
        if (!empty($membersList)) {
            foreach ($membersList as $member) {
                if (!in_array($member->user_id, $reassignedUsers)) {
                    $assignableUsers[] = $member;
                }
            }
        }
        return $assignableUsers;
    }

    /**
     * Get the next user assigned to a task if the assignment is of type Round Robin,
     * which is a form of sequential assignment inside a user group or team.
     * @param $act_id
     * @return int
     */
    public function getNextUserUsingRoundRobin($act_id)
    {
        //getting record from bpm_activity_definition
        $beanBpmActivity = $this->retrieveBean('pmse_BpmActivityDefinition', $act_id);
        $assign_team = $beanBpmActivity->act_assign_team;
        $last_assigned = $beanBpmActivity->act_last_user_assigned;

        if (empty($assign_team)) {
            //set default team to global
            $assign_team = '1';
        }

        $q = $this->prepareTeamUserIdsQuery($assign_team);
        $q->where()->gt('id', $last_assigned);
        $q->limit(1);
        $nextUserId = $q->execute();

        if (!$nextUserId) {
            $q = $this->prepareTeamUserIdsQuery($assign_team);
            $q->limit(1);
            $nextUserId = $q->execute();
        }

        $nextUserId = $nextUserId ? $nextUserId[0]['id'] : '';

        //updating last user selected
        $beanBpmActivity->act_last_user_assigned = $nextUserId;
        $beanBpmActivity->save();

        return $nextUserId;
    }

    /**
     * Gets all members of a team who are both active users and active employees
     *
     * @param $teamId
     * @return SugarQuery
     */
    protected function prepareTeamUserIdsQuery($teamId)
    {
        $q = new SugarQuery();
        $q->select(array('id'));
        $q->from(BeanFactory::newBean('Users'));

        $q->joinTable('team_memberships', array('alias' => 'membership'))->on()
            ->equals('membership.team_id', $teamId)
            ->equalsField('membership.user_id', 'id')
            ->equals('membership.explicit_assign', 1)
            ->equals('membership.deleted', 0);

        $q->where()
            ->equals('status', 'Active')
            ->equals('employee_status', 'Active');
        $q->orderBy('id', 'ASC');

        return $q;
    }

    /**
     * Get the id of the current user
     * @global type $current_user
     * @return type
     */
    public function getCurrentUserId()
    {
        global $current_user;
        return $current_user->id;
    }

    /**
     * Get the id of the owner of the current record.
     * @param type $currentSugarId
     * @param type $currentSugarModule
     * @return string
     */
    public function getRecordOwnerId($currentSugarId, $currentSugarModule)
    {
        $bean = $this->retrieveBean($currentSugarModule, $currentSugarId);
        if (isset($bean->assigned_user_id)) {
            $currentUserId = $bean->assigned_user_id;
        } elseif (isset($bean->created_by)) {
            $currentUserId = $bean->created_by;
        } else {
            $currentUserId = 'unknown';
        }
        return $currentUserId;
    }

    /**
     * Get the supervisor id from a determined user.
     * @global type $db
     * @param type $currentUserId
     * @return type
     */
    public function getSupervisorId($currentUserId)
    {
        global $db;
        $supervisor = $currentUserId;

        $query = "select reports_to_id from users where id = '$currentUserId' ";
        $result = $db->Query($query);
        $row = $db->fetchByAssoc($result);

        if (is_array($row)) {
            if (isset($row['reports_to_id']) && trim($row['reports_to_id']) != '') {
                $supervisor = $row['reports_to_id'];
            }
        }
        return $supervisor;
    }

    private function getTeamMembers($teamBean, $args)
    {
        // Set up the defaults
        $options['limit'] = 20;
        $options['offset'] = 0;
        $options['add_deleted'] = true;

        if (!empty($args['max_num'])) {
            $options['limit'] = (int) $args['max_num'];
        }

        if (!empty($args['deleted'])) {
            $options['add_deleted'] = false;
        }

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset'] = (int) $args['offset'];
            }
        }

        // Get the list of members
        $membersBean = BeanFactory::newBean('TeamMemberships');

        $fields = array(
            'user_id'
        );

        $q = new SugarQuery();
        $q->from($membersBean, array('add_deleted' => true));
        $q->distinct(false);

        $q->joinTable('users', array('alias' => 'users', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('users.id', 'user_id')
            ->equals('users.deleted', 0);

        $fields[] = array('users.first_name', 'first_name');
        $fields[] = array('users.last_name', 'last_name');
        $fields[] = array('users.status', 'status');

        $q->where()
            ->equals('team_id', $teamBean->id)
            ->equals('explicit_assign', 1)
            ->notEquals('users.status', 'Inactive');

        $q->where()
            ->queryOr()
            ->starts('users.first_name', $args['filter'] . '%')
            ->starts('users.last_name', $args['filter'] . '%');

        $q->select($fields);
        
        $q->limit($options['limit'] + 1);
        $q->offset($options['offset']);

        $member_list = $q->execute();

        $user_list = Array();

        foreach($member_list as $current_member)
        {
            $user = BeanFactory::getBean('Users', $current_member['user_id']);
            if($user->status == 'Active'){
                $user_list[] = $user;
            }
        }

        return $user_list;
    }
}

