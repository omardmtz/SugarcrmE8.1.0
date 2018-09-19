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

class PMSEBusinessRule extends PMSEScriptTask
{
    /**
     *
     * @param type $appData
     * @param type $global
     * @return \PMSEBusinessRuleReader
     * @codeCoverageIgnore
     */
    public function getBusinessRuleReader($appData, $global)
    {
        $reader = ProcessManager\Factory::getPMSEObject('PMSEBusinessRuleReader');
        $reader->init($appData, $global);
        return $reader;
    }

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
     * @param type $flowData
     * @param type $bean
     * @param type $externalAction
     * @return type
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
        
        $logBR = '';
        $bpmnElement = $this->retrieveDefinitionData($flowData['bpmn_id']);

        $definitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmActivityDefinition', $bpmnElement['id']);
        $processDefinitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmProcessDefinition', $definitionBean->pro_id);


        $rst_id = $definitionBean->act_fields;
        $rst_module = $definitionBean->act_field_module;
        // TODO: Probably the act_module field will be used instead pro_module
        $sugarModule = $processDefinitionBean->pro_module;

        $sugarRecord = $flowData['cas_sugar_object_id'];

        //get the ruleset
        $query = "select * from pmse_business_rules where id = '$rst_id'";
        $this->logger->debug("Script: {$query}");
        $result = $this->dbHandler->Query($query);
        $row = $this->dbHandler->fetchByAssoc($result);

        if (!is_array($row)) {
            $logBR .= "Error, Business Rule not defined!";
            $rst_name = '';
        } else {
            $rst_name = $row['name'];
        }

        if (is_array($row) && $rst_name != '' && !empty($sugarModule) && !empty($sugarRecord)) {
            $encodedRstDefinition = $row['rst_definition'];
            $rst_definition = base64_decode($encodedRstDefinition);
            $rst_definition = htmlspecialchars_decode($rst_definition, ENT_QUOTES);
            $logBR .= "executing $rst_name \n";
            $bean = $this->caseFlowHandler->retrieveBean($sugarModule, $sugarRecord);

            //go thru fetched_row to obtain the fields array, and then use the
            //bean property for each field.
            $fields = $bean->field_defs;
            $appData = array();
            foreach ($fields as $field => $value) {
                if (isset($bean->{$field}) && !is_object($bean->{$field})) {
                    $appData[$field] = $bean->{$field};
                } else {
                    $appData[$field] = null;
                }
            }

            $global = array();
            if (isset($bean->name)) {
                $logBR .= "Bean: $sugarModule::$sugarRecord {$bean->name}\n";
            } else {
                $logBR .= "Bean: $sugarModule::$sugarRecord\n";
            }

            $rules = $this->getBusinessRuleReader($appData, $global);

            $rstSourceDefinition = $row['rst_source_definition'];
            $rstSourceDefinition = htmlspecialchars_decode($rstSourceDefinition, ENT_QUOTES);
            $res = $rules->parseRuleSetJSON($sugarModule, $rstSourceDefinition, $row['rst_type']);
            $logBR .= $res['log'];

            $this->logger->debug($logBR);

            $returnBR = $res['return'];
            $newAppData = $res['newAppData'];

            //if data was changed inside the BR, we need to update the Bean
            $historyData = $this->retrieveHistoryData($sugarModule);
            if (is_array($newAppData) && count($newAppData) > 0) {
                foreach ($newAppData as $key => $value) {
                    // if the $key attribute doesn't exists it's not saved.
                    // so it's not necessarily validate the attribute existence.
                    //if (isset($bean->{$key})) {
                    $historyData->savePredata($key, $bean->{$key});
                    $bean->{$key} = $value;
                    $historyData->savePostData($key, $value);
                    //}
                }
                PMSEEngineUtils::saveAssociatedBean($bean);
            }

            //saving the return value in bpm_form_action table
            $params = array();
            $params['cas_id'] = $flowData['cas_id'];
            $params['cas_index'] = $flowData['cas_index'];
            $params['act_id'] = $bpmnElement['id'];
            $params['pro_id'] = $processDefinitionBean->pro_id;
            $params['user_id'] = $this->getCurrentUser()->id;
            // TODO: find a better fix since probably a ; symbol could be part of a business rules evaluation response
            $params['frm_action'] = str_replace(";", "", $returnBR);
            $params['frm_comment'] = $logBR;
            $params['log_data'] = $historyData->getLog();
            $this->caseFlowHandler->saveFormAction($params);
        } else {
            $this->logger->warning("$logBR");
            $returnBR = null;
        }
        return $this->prepareResponse($flowData, 'ROUTE', $flowAction);
    }

}
