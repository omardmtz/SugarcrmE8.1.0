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


use Sugarcrm\Sugarcrm\Util\Files\FileLoader;
use Sugarcrm\Sugarcrm\ProcessManager;
use Sugarcrm\Sugarcrm\ProcessManager\Registry;
use  Sugarcrm\Sugarcrm\Util\Arrays\ArrayFunctions\ArrayFunctions;

class PMSEUserTask extends PMSEActivity
{

    protected $engineFields;

    /**
     * @var array List of calendar type modules
     */
    protected $calendarModules = array('Calls', 'Meetings');

    public function __construct()
    {
        $this->engineFields = array(
            'idInbox',
            'idFlow',
            'date_entered',
            'date_modified',
            'created_by_name',
            'team_name',
            '__sugar_url',
        );

        parent::__construct();
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
        $redirectAction = $this->processAction($flowData, $externalAction, $arguments);
        $saveBeanData = !empty($arguments) ? true : false;
        switch ($redirectAction) {
            case 'ASSIGN':
                $userId = $this->userAssignmentHandler->taskAssignment($flowData);
                $activityDefinitionBean = $this->retrieveBean('pmse_BpmActivityDefinition', $flowData['bpmn_id']);
                $flowData['cas_adhoc_actions'] = $this->getActionButtons($activityDefinitionBean);
                $flowData['cas_flow_status'] = 'FORM';
                $flowData['cas_assignment_method'] = $activityDefinitionBean->act_assignment_method;
                $flowAction = 'CREATE';
                $routeAction = 'WAIT';
                $saveBeanData = false;
                break;
            case 'REASSIGN':
                $flowData['cas_index']--;
                $flowData['cas_adhoc_type'] = isset($arguments['adhoc_type']) ? $arguments['adhoc_type'] : $flowData['cas_adhoc_type'];
                $flowData['user_name'] = isset($arguments['user_name']) ? $arguments['user_name'] : '';
                $flowData['full_name'] = isset($arguments['full_name']) ? $arguments['full_name'] : '';
                $flowData['taskName'] = isset($arguments['taskName']) ? $arguments['taskName'] : '';
                $flowData['evn_type'] = 'REASSIGN';
                $flowData['idInbox'] = isset($arguments['flow_id']) ? $arguments['flow_id'] : '';
                $this->userAssignmentHandler->adhocReassign(
                    $flowData,
                    $arguments['adhoc_user'],
                    false,
                    isset($arguments['reassign_form'])
                );
                $userId = $flowData['cas_user_id'];
                $flowData['cas_flow_status'] = 'FORM';
                $flowAction = 'CLOSE';
                $routeAction = 'WAIT';
                break;
            case 'ROUND_TRIP':
                $flowData['cas_index']--;
                $this->userAssignmentHandler->roundTripReassign($flowData);
                $flowData['cas_flow_status'] = 'FORM';
                $userId = $flowData['cas_user_id'];
                $flowAction = 'CLOSE';
                $routeAction = 'WAIT';
                break;
            case 'ONE_WAY':
                $flowData['cas_index']--;
                $this->userAssignmentHandler->oneWayReassign($flowData);
                $flowData['cas_flow_status'] = 'FORM';
                $userId = $flowData['cas_user_id'];
                $flowAction = 'CLOSE';
                $routeAction = 'WAIT';
                break;
            case 'ROUTE':
                $userId = $flowData['cas_user_id'];
                $flowData['cas_flow_status'] = 'FORM';
                $flowAction = 'UPDATE';
                $routeAction = 'ROUTE';
                break;
        }

        $flowData['cas_user_id'] = $userId;
        $flowData['assigned_user_id'] = $userId;

        if ($saveBeanData) {
            $this->lockFlowRoute($arguments['idFlow']);
            $this->saveBeanData($arguments);
        }

        $result = $this->prepareResponse($flowData, $routeAction, $flowAction);
        return $result;
    }

    public function processAction($flowData, $externalAction, $arguments= array())
    {
        switch ($externalAction) {
            case '':
                $action = 'ASSIGN';
                break;
            case 'REASSIGN':
                $action = 'REASSIGN';
                break;
            case 'ROUTE':
                $action = !empty($arguments['taskContinue']) ? 'ROUTE' : $this->processUserAction($flowData);
                break;
            case 'APPROVE':
            case 'REJECT':
            default:
                $action = 'ROUTE';
                break;
        }
        return $action;
    }

    /**
     * Process the response based on the EXternal action and the type of
     * @param type $flowData
     * @return string
     */
    public function processUserAction($flowData)
    {
        $flowData['cas_index']--;
        switch (true) {
            case $this->userAssignmentHandler->isRoundTrip($flowData):
                $action = 'ROUND_TRIP';
                break;
            case ($this->userAssignmentHandler->isOneWay($flowData) && !$this->userAssignmentHandler->previousIsNormal($flowData)):
                $action = 'ONE_WAY';
                break;
            default:
                $action = 'ROUTE';
                break;
        }
        return $action;
    }

    /**
     * Saving the bean data if sent through the engine
     * @param type $beanData
     * @codeCoverageIgnore
     */
    public function saveBeanData($beanData)
    {
        global $current_user;
        $fields = $beanData;
        $sfh = new SugarFieldHandler();

        $bpmInboxId = $fields['idInbox'];
        $moduleName = $fields['moduleName'];
        $moduleId = $fields['beanId'];

        foreach ($beanData as $key => $value) {
            if (in_array($key, $this->engineFields)) {
                unset($fields[$key]);
            }
        }
        //modified_by_name => Current
        if (!isset($moduleName) || $moduleName == '') {
            $sugarApiExceptionMissingParameter = new SugarApiExceptionMissingParameter(
                'Error: Missing argument moduleName.'
            );
            PMSELogger::getInstance()->alert($sugarApiExceptionMissingParameter->getMessage());
            throw $sugarApiExceptionMissingParameter;
        }

        //If Process is Completed break...
        $bpmI = PMSEEngineUtils::getBPMInboxStatus($bpmInboxId);
        if ($bpmI === false) {
            $sugarApiExceptionEditConflict = new SugarApiExceptionEditConflict('Error: Process status complete.');
            PMSELogger::getInstance()->alert($sugarApiExceptionEditConflict->getMessage());
            throw $sugarApiExceptionEditConflict;
        }

        $beanObject = BeanFactory::getBean($moduleName, $moduleId);

        if (in_array($moduleName, $this->calendarModules)) {
            $isCalendarModule = true;
        } else {
            $isCalendarModule = false;
        }
        if ($isCalendarModule) {
            $calendarEvents = new CalendarEvents();
            $wasRecurring = $calendarEvents->isEventRecurring($beanObject);
        }

        $historyData = ProcessManager\Factory::getPMSEObject('PMSEHistoryData');
        $historyData->setModule($moduleName);

        //If a module includes custom save/editview logic in Save.php, use that instead of a direct save.
        if (isModuleBWC($beanObject->module_dir) &&
            file_exists("modules/{$beanObject->module_dir}/Save.php")
        ) {
            foreach ($fields as $key => $value) {
                $historyData->lock(!array_key_exists($key, $beanObject->fetched_row));
                if (isset($beanObject->$key)) {
                    $historyData->verifyRepeated($beanObject->$key, $value);
                    $historyData->savePredata($key, $beanObject->$key);
                    $beanObject->$key = $value;
                    $historyData->savePostdata($key, $value);
                }
            }
            global $disable_redirects;
            $disable_redirects = true;

            $_REQUEST['record'] = $beanObject->id;
            include FileLoader::validateFilePath("modules/{$beanObject->module_dir}/Save.php");

            $disable_redirects = false;
        } else {
            try {
                $api = new RestService();
                $api->user = $current_user;
                $api->getRequest();
                $helper = ApiHelper::getHelper($api, $beanObject);
                $beanPopulate = $helper->populateFromApi($beanObject, $beanData);
            } catch (SugarApiExceptionRequestMethodFailure $conflict) {
                $conflict->setExtraData("record", $beanObject);
                throw $conflict;
            }
            if ($beanPopulate !== true){
                foreach ($beanObject->field_defs as $fieldName => $properties) {
                    if ( !isset($fields[$fieldName])) {
                        // They aren't trying to modify this field
                        continue;
                    }

                    $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
                    $field = $sfh->getSugarField($type);
                    $field->setOptions("");

                    if ($field != null) {
                        // validate submitted data
                        if (!$field->apiValidate($beanObject, $fields, $fieldName, $properties)) {
                            $sugarApiExceptionInvalidParameter = new SugarApiExceptionInvalidParameter(
                                'Invalid field value: ' . $fieldName . ' in module: ' . $beanObject->module_name
                            );
                            PMSELogger::getInstance()->alert($sugarApiExceptionInvalidParameter->getMessage());
                            throw $sugarApiExceptionInvalidParameter;
                        }
                        $historyData->verifyRepeated($beanObject->$fieldName, $fields[$fieldName]);
                        $historyData->savePredata($fieldName, $beanObject->$fieldName);
                        $field->apiSave($beanObject, $fields, $fieldName, $properties);
                        $historyData->savePostdata($fieldName, $fields[$fieldName]);
                    }
                }
            }

            PMSEEngineUtils::saveAssociatedBean($beanObject);

            // Now deal with the related records
            $moduleApi = new ModuleApi();
            $moduleApi->updateRelatedRecords($api, $beanObject, $beanData);

            // if switching from non recurring to recurring, need to save recurring events
            if ($isCalendarModule && !$wasRecurring && $calendarEvents->isEventRecurring($beanObject)) {
                $calendarEvents->saveRecurringEvents($beanObject);
            }
        }

        $fields['log_data'] = $historyData->getLog();
        $this->caseFlowHandler->saveFormAction($fields);
    }

    /**
     * Lock the flow id in order to allow only one request of an element
     * at a time
     * @param type $id
     */
    public function lockFlowRoute($id)
    {
        $registry = Registry\Registry::getInstance();

        // Simplified logic here... get all locked flows or a default array...
        $flows = $registry->get('locked_flows', array());

        // If the flow id is not currently in the locked flow arrray, set it...
        if (!isset($flows[$id])) {
            $flows[$id] = 1;
        }

        // Reregister the locked_flows, or set it fresh depending on state
        $registry->set('locked_flows', $flows, true);
    }

    /**
     * Get the action buttons for the pmse_BpmActivityDefinition bean
     * @param pmse_BpmActivityDefinition $bean
     * @return string
     */
    public function getActionButtons(pmse_BpmActivityDefinition $bean)
    {
        if ($bean->act_response_buttons == 'ROUTE') {
            $buttons = json_encode(array('link_cancel', 'route', 'edit', 'continue'));
        } else {
            $buttons = json_encode(array('link_cancel', 'approve', 'reject', 'edit'));
        }
        return $buttons;
    }
}
