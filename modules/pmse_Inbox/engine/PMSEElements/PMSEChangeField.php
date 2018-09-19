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

class PMSEChangeField extends PMSEScriptTask
{
    protected $beanList;
    protected $currentUser;
    protected $evaluator;
    protected $pmseRelatedModule;

    /**
     *
     * @global type $beanList
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        global $beanList, $current_user;
        $this->beanList = $beanList;
        $this->currentUser = $current_user;
        $this->evaluator = ProcessManager\Factory::getPMSEObject('PMSEEvaluator');
        $this->pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
        parent::__construct();
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getBeanList()
    {
        return $this->beanList;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     *
     * @param type $beanList
     * @codeCoverageIgnore
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
    }

    /**
     *
     * @param type $currentUser
     * @codeCoverageIgnore
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
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
     * $response['flow_filters'] = array('first_id', 'second_id');
     * //This attribute is used to filter the execution of the following elements
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
        global $beanList;
        switch ($externalAction) {
            case 'RESUME_EXECUTION':
                $flowAction = 'UPDATE';
                break;
            default :
                $flowAction = 'CREATE';
                break;
        }

        $isRelated = false;
        $bpmnElement = $this->retrieveDefinitionData($flowData['bpmn_id']);
        $act_field_module = $bpmnElement['act_field_module'];
        $act_fields = htmlspecialchars_decode($bpmnElement['act_fields']);
        $fields = json_decode($act_fields);
        $ifields = 0;

        $idMainModule = $bean->id;
        $moduleName = $bean->module_name;

        $this->logger->info("[{$flowData['cas_id']}][{$flowData['cas_index']}] Getting $moduleName ID: $idMainModule");

        //Save original bean of project definition
        $beanModule = $bean;

        if (!isset($beanList[$act_field_module])) {
            $bean = $this->pmseRelatedModule->getRelatedModule($bean, $act_field_module);
            $isRelated = true;
        }

        if (isset($bean) && is_object($bean)) {
            $historyData = $this->retrieveHistoryData($moduleName);
            if ($act_field_module == $moduleName || $isRelated) {
                foreach ($fields as $field) {
                    if (isset($bean->field_defs[$field->field])) {
                        // check if of type link
                        if ((isset($bean->field_defs[$field->field]['type'])) &&
                            ($bean->field_defs[$field->field]['type'] == 'link') &&
                            !(empty($bean->field_defs[$field->field]['name']))) {

                            // if its a link then go through cases on basis of "name" here.
                            // Currently only supporting teams
                            switch ($bean->field_defs[$field->field]['name']) {
                                case 'teams':
                                    PMSEEngineUtils::changeTeams($bean, $field);
                                    break;
                            }

                        }
                        else if (isset($bean->field_defs[$field->field]['type']) &&
                            $bean->field_defs[$field->field]['type'] == 'multienum') {
                            $bean->{$field->field} = encodeMultienumValue($field->value);
                        } else {
                            if (!$this->emailHandler->doesPrimaryEmailExists($field, $bean, $historyData)) {
                                $historyData->savePredata($field->field, $bean->{$field->field});
                                $newValue = '';
                                if (is_array($field->value)) {
                                    // Handle regular evaluation of values
                                    $newValue = $this->beanHandler->processValueExpression($field->value, $beanModule);
                                    // For null values only
                                    if (!isset($newValue)) {
                                        // Used to set these fields to null in db
                                        $newValue = '';
                                    } else {
                                        // Handle special field type processing
                                        $newValue = $this->handleFieldTypeProcessing($newValue, $field, $bean);
                                    }
                                } else {
                                    if ($field->field == 'assigned_user_id') {
                                        $field->value = $this->getCustomUser($field->value, $beanModule);
                                    }
                                    $newValue = $this->beanHandler->mergeBeanInTemplate($beanModule, $field->value);
                                }
                                if (!empty($bean->field_defs[$field->field]['required'])) {
                                    $invalid = false;
                                    switch (gettype($newValue)) {
                                        case 'boolean':
                                        case 'integer':
                                        case 'double':
                                            break;
                                        case 'string':
                                            $invalid = !strlen($newValue);
                                            break;
                                        default:
                                            $invalid = empty($newValue);
                                    }
                                    if ($invalid) {
                                        throw new PMSEElementException('Cannot fill a required field ' . $field->field . ' with an empty value', $flowData, $this);
                                    }
                                }

                                // Finally, set the new value of the field onto
                                // the bean
                                $bean->{$field->field} = $newValue;
                            }
                        }

                        $historyData->savePostdata($field->field, $field->value);
                        $ifields++;
                    }
                }

                $bean->new_with_id = false;
                PMSEEngineUtils::saveAssociatedBean($bean);

                $params = array();
                $params['cas_id'] = $flowData['cas_id'];
                $params['cas_index'] = $flowData['cas_index'];
                $params['act_id'] = $bpmnElement['id'];
                $params['pro_id'] = $bpmnElement['pro_id'];
                $params['user_id'] = $this->currentUser->id;
                $params['frm_action'] = 'Event Changed Fields';
                $params['frm_comment'] = 'Changed Fields Applied';
                $params['log_data'] = $historyData->getLog();
                $this->caseFlowHandler->saveFormAction($params);
            } else {
                $this->logger->warning(
                    "[{$flowData['cas_id']}][{$flowData['cas_index']}] "
                    . "Trying to use '$act_field_module' fields to be set in $moduleName"
                );
            }
            $this->logger->info(
                "[{$flowData['cas_id']}][{$flowData['cas_index']}] "
                . "number of fields changed: {$ifields}"
            );
        } else {
            $this->logger->info(
                "[{$flowData['cas_id']}][{$flowData['cas_index']}] "
                . "Fields cannot be changed, none Module was set."
            );
        }
        return $this->prepareResponse($flowData, 'ROUTE', $flowAction);
    }

    /**
     * @deprecated
     * @param $value
     * @param $fieldType
     * @return bool|float|string
     */
    public function postProcessValue($value, $fieldType)
    {
        global $timedate;
        switch (strtolower($fieldType)) {
            case 'date':
                $date = $timedate->fromIsoDate($value);
                $value = $date->asDbDate();
                break;
            case 'datetime':
            case 'datetimecombo':
                $date = $timedate->fromIso($value);
                $value = $date->asDb();
                break;
            case 'float':
            case 'double':
            case 'integer':
                $value = (double)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'boolean':
                $value = (boolean)$value;
                break;
        }
        return $value;
    }

    /**
     * handle certain types of field types where the bean may need to be modified as well
     * @param type value
     * @param type field
     * @param type bean
     * @return value
     */
    public function handleFieldTypeProcessing($value, $field, $bean)
    {
        // Handle certain fields that require special handling
        switch (strtolower($field->type)) {
            case 'currency':
                // For currency fields, the return value is a json encoded string. So need to json_decode.
                $currencyFields = json_decode($value);
                if (!empty($currencyFields) && (!empty($currencyFields->expField)) && (!empty($currencyFields->expValue))) {
                    // we need to take into account the type of currency too
                    $bean->currency_id = $currencyFields->expField;
                    $value = $currencyFields->expValue;
                }
                break;

            case 'datetime':
            case 'date':
                $value = $this->getDBDate($field, $value);
                break;
        }

        return $value;
    }
}
