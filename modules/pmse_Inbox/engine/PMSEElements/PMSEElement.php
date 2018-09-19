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

class PMSEElement implements PMSERunnable
{
    /**
     * The case Flow handler manages the operations related to the BpmFlow
     * Bean or flow operations.
     *
     * @var PMSECaseFlowHandler $caseFlowHandler
     */
    protected $caseFlowHandler;

    /**
     * The bean handler manages operations related to the Sugar Beans
     *
     * @var PMSEBeanHandler $beanHandler
     */
    protected $beanHandler;

    /**
     * The execution mode is the execution mode of the element, it could be
     * 'SYNC' for Synchronous elements and processes and
     * 'ASYNC' for Asynchronous elements and processes.
     *
     * @var string
     */
    protected $executionMode;

    /**
     * The user assignment handler manages the teams and users operations.
     *
     * @var PMSEUserAssignmentHandler $userAssignmentHandler
     */
    protected $userAssignmentHandler;

    /**
     * The email handler manages all the operations related to the sending
     * of emails using the engine
     *
     * @var
     */
    protected $emailHandler;

    /**
     * The db handler is an abstraction of the global sugar $db
     * Database Handler object
     * @var type
     */
    protected $dbHandler;

    /**
     * PMSE Logger object
     *
     * @var
     */
    protected $logger;

    /**
     * Class constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        global $db;
        $this->executionMode = 'DEFAULT';
        $this->caseFlowHandler = ProcessManager\Factory::getPMSEObject('PMSECaseFlowHandler');
        $this->userAssignmentHandler = ProcessManager\Factory::getPMSEObject('PMSEUserAssignmentHandler');
        $this->beanHandler = ProcessManager\Factory::getPMSEObject('PMSEBeanHandler');
        $this->emailHandler = ProcessManager\Factory::getPMSEObject('PMSEEmailHandler');
        $this->dbHandler = $db;
        $this->logger = PMSELogger::getInstance();
    }

    /**
     *
     * @return SugarQuery $sugarQueryObject
     * @codeCoverageIgnore
     */
    public function retrieveSugarQueryObject()
    {
        return new SugarQuery();
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
     * @return string
     * @codeCoverageIgnore
     */
    public function getExecutionMode()
    {
        return $this->executionMode;
    }

    /**
     *
     * @param string $executionMode
     * @codeCoverageIgnore
     */
    public function setExecutionMode($executionMode)
    {
        $this->executionMode = $executionMode;
    }

    /**
     *
     * @return PMSECaseFlowHandler
     * @codeCoverageIgnore
     */
    public function getCaseFlowHandler()
    {
        return $this->caseFlowHandler;
    }

    /**
     *
     * @param PMSECaseFlowHandler $caseFlowHandler
     * @codeCoverageIgnore
     */
    public function setCaseFlowHandler($caseFlowHandler)
    {
        $this->caseFlowHandler = $caseFlowHandler;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getBeanHandler()
    {
        return $this->beanHandler;
    }

    /**
     *
     * @param PMSEBeanHandler $beanHandler
     * @codeCoverageIgnore
     */
    public function setBeanHandler(PMSEBeanHandler $beanHandler)
    {
        $this->beanHandler = $beanHandler;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getUserAssignmentHandler()
    {
        return $this->userAssignmentHandler;
    }

    /**
     *
     * @param PMSEUserAssignmentHandler $userAssignmentHandler
     * @codeCoverageIgnore
     */
    public function setUserAssignmentHandler(PMSEUserAssignmentHandler $userAssignmentHandler)
    {
        $this->userAssignmentHandler = $userAssignmentHandler;
    }

    /**
     * @param mixed $emailHandler
     * @codeCoverageIgnore
     */
    public function setEmailHandler($emailHandler)
    {
        $this->emailHandler = $emailHandler;
    }

    /**
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getEmailHandler()
    {
        return $this->emailHandler;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getDbHandler()
    {
        return $this->dbHandler;
    }

    /**
     *
     * @param type $dbHandler
     * @codeCoverageIgnore
     */
    public function setDbHandler($dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     *
     * @return type
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
     * @param array $flowData
     * @param SugarBean $bean
     * @param string $externalAction The external action for the router
     * @return array
     * @codeCoverageIgnore
     */
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        $result = $this->prepareResponse($flowData, 'ROUTE', 'CREATE');
        return $result;
    }

    /**
     * This method sorts all the execution data in one single array that
     * is evaluated by the Flow Router class.
     *
     * An example could be like this one:
     *
     * $response['route_action'] = 'ROUTE'; //The action that should process the Router
     * $response['flow_action'] = 'CREATE'; //The record action that should process the router
     * $response['flow_data'] = $flowData; //The current flowData
     * $response['flow_filters'] = array('first_id', 'second_id'); //This attribute is used to filter the execution of the following elements
     * $response['flow_id'] = $flowData['id']; // The flowData id if present
     *
     * @param type $data
     * @param type $routeAction
     * @param type $dataId
     * @return array
     */
    public function prepareResponse($data = array(), $routeAction = 'ROUTE', $flowAction = 'CREATE', $filters = array())
    {
        $response = array();
        $response['route_action'] = $routeAction;
        $response['flow_action'] = $flowAction;
        $response['flow_data'] = $data;
        $response['flow_filters'] = $filters;
        $response['flow_id'] = isset($data['id']) ? $data['id'] : '';
        return $response;
    }

    /**
     * Retrieving the current date using a date object
     * @return type
     * @codeCoverageIgnore
     */
    public function getCurrentDate()
    {
        return TimeDate::getInstance()->nowDb();
    }

    /**
     *
     * @param type $flowData
     */
    public function getNextShapeElements($flowData)
    {
        $nextElements = $this->caseFlowHandler->retrieveFollowingElements($flowData);
        $eventBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnEvent');

        $nextShapeElements = array();
        foreach ($nextElements as $element) {
            $sugarQueryObject = $this->retrieveSugarQueryObject();
            $sugarQueryObject->select(array(
                'id',
                'evn_type',
                'evn_behavior'
            ));

            $sugarQueryObject->from($eventBean, array('alias' => 'a'));
            $sugarQueryObject->joinTable('pmse_bpmn_flow', array('joinType' => 'LEFT', 'alias' => 'b'))
                ->on()->equalsField('b.flo_element_dest', 'a.id');
            $sugarQueryObject->where()->queryAnd()
                ->addRaw('b.id=\'' . $element['bpmn_id'] . '\'');

            $queryResult = $sugarQueryObject->execute();
            $result = array_pop($queryResult);
            array_push($nextShapeElements, $result);
        }
        return $nextShapeElements;
    }
}
