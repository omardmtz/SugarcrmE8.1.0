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


class PMSERoundRobin extends PMSEScriptTask
{
    /**
     * This method prepares the response of the current element based on the
     * $bean object and the $flowData, an external action such as
     * ROUTE or ADHOC_REASSIGN could be also processed.
     *
     * This method probably should be override for each new element, but it's
     * not mandatory. However the response structure always must pass using
     * the 'prepareResponse' Method.
     *
     * As defined in the example:
     *
     * $response['route_action'] = 'ROUTE'; //The action that should process the Router
     * $response['flow_action'] = 'CREATE'; //The record action that should process the router
     * $response['flow_data'] = $flowData; //The current flowData
     * $response['flow_filters'] = array('first_id', 'second_id'); //This attribute is used to filter the execution of the following elements
     * $response['flow_id'] = $flowData['id']; // The flowData id if present
     *
     *
     * @param array $flowData
     * @param null $bean
     * @param string $externalAction
     * @param array $arguments
     * @return array
     */
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        switch ($externalAction) {
            case 'RESUME_EXECUTION':
                $flowAction = 'UPDATE';
                break;
            default :
                $flowAction = 'CREATE';
                break;
        }
        
        $bpmnElement = $this->retrieveDefinitionData($flowData['bpmn_id']);
        $act_assign_team = $bpmnElement['act_assign_team'];

        $teamBean = $this->retrieveTeamData($act_assign_team);

        $act_assignment_method = $bpmnElement['act_assignment_method'];
        if (isset($bean->team_id) && isset($teamBean->id) && ($teamBean->id == $act_assign_team)) {
            if (strtolower($act_assignment_method) == 'balanced') {
                $nextUser = $this->userAssignmentHandler->getNextUserUsingRoundRobin($bpmnElement['id']);
                if (isset($bpmnElement['act_update_record_owner']) && $bpmnElement['act_update_record_owner'] == 1) {
                    $historyData = $this->retrieveHistoryData($flowData['cas_sugar_module']);
                    $historyData->savePreData('assigned_user_id', $bean->assigned_user_id);
                    $bean->assigned_user_id = $nextUser;
                    $historyData->savePostData('assigned_user_id', $nextUser);

                    /** @var TeamSet $teamSetBean */
                    $teamSetBean = BeanFactory::newBean('TeamSets');
                    $teams = $teamSetBean->getTeams($bean->team_set_id);
                    if (!array_key_exists($act_assign_team, $teams)) {
                        $teamSet = array_keys($teams);
                        $teamSet[] = $act_assign_team;
                        $teamSetId = $teamSetBean->addTeams($teamSet);
                        $historyData->savePreData('team_set_id', $bean->team_set_id);
                        $bean->team_set_id = $teamSetId;
                        $historyData->savePostData('team_set_id', $teamSetId);
                    }
                }
                $flowData['cas_user_id'] = $nextUser;
            }

            PMSEEngineUtils::saveAssociatedBean($bean);

            $params = array();
            $params['cas_id'] = $flowData['cas_id'];
            $params['cas_index'] = $flowData['cas_index'];
            $params['act_id'] = $bpmnElement['id'];
            $params['pro_id'] = $bpmnElement['pro_id'];
            $params['user_id'] = $this->getCurrentUser()->id;
            $params['frm_action'] = 'Event Team Assign';
            $params['frm_comment'] = 'Team Assign Applied';
            if (isset($bpmnElement['act_update_record_owner']) && $bpmnElement['act_update_record_owner'] == 1) {
                $params['log_data'] = $historyData->getLog();
            }
            $this->caseFlowHandler->saveFormAction($params);
        }
        return $this->prepareResponse($flowData, 'ROUTE', $flowAction);
        
    }
}
