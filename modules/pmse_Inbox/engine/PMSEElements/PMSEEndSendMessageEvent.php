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
 * Description of PMSEEndSendMessageEvent
 *
 */
class PMSEEndSendMessageEvent extends PMSEEndEvent
{
    /**
     *
     * @var type
     */
    protected $logger;

    /**
     *
     * @var type
     */
    protected $definitionBean;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->definitionBean = BeanFactory::newBean('pmse_BpmEventDefinition');
        parent::__construct();

    }

    /**
     * @param $id
     * @return array
     * @codeCoverageIgnore
     */
    public function retrieveDefinitionData($id)
    {
        $this->definitionBean->retrieve($id);
        return ($this->definitionBean->toArray());
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
        $definitionData = $this->retrieveDefinitionData($flowData['bpmn_id']);

        $json = htmlspecialchars_decode($definitionData['evn_params']);
        $addresses = $this->emailHandler->processEmailsFromJson($bean, $json, $flowData);
        $template_id = $definitionData['evn_criteria'];
        // probably it's necessary publish the result of the sending to the log
        // so we store it in that variable.
        $resultSend = $this->emailHandler->sendTemplateEmail(
            $flowData['cas_sugar_module'],
            $flowData['cas_sugar_object_id'], 
            $addresses, $template_id
        );
        // since all the actions from now on are exactly the same as the PMSEEndEvent
        // run method we just call the parent implementation
        return parent::run($flowData, $bean, $externalAction, $arguments);
    }

}
