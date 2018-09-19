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


class PMSEGatewayDefinitionWrapper
{
    private $gateway;
    private $gatewayDefinition;
    private $flowBean;
    private $processDefinition;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->gateway = BeanFactory::newBean('pmse_BpmnGateway');
        $this->gatewayDefinition = BeanFactory::newBean("pmse_BpmGatewayDefinition");
        $this->flowBean = BeanFactory::newBean('pmse_BpmnFlow');

    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getGatewayDefinition()
    {
        return $this->gatewayDefinition;
    }

    /**
     *
     * @param type $gateway
     * @codeCoverageIgnore
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     *
     * @param type $gatewayDefinition
     * @codeCoverageIgnore
     */
    public function setGatewayDefinition($gatewayDefinition)
    {
        $this->gatewayDefinition = $gatewayDefinition;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getFlowBean()
    {
        return $this->flowBean;
    }

    /**
     *
     * @param type $flowBean
     * @codeCoverageIgnore
     */
    public function setFlowBean($flowBean)
    {
        $this->flowBean = $flowBean;
    }

    /**
     *
     * @param type $obj
     * @codeCoverageIgnore
     */
    public function setBpmnGateway($obj)
    {
        $this->gateway = $obj;
    }

    /**
     *
     * @param type $obj
     * @codeCoverageIgnore
     */
    public function setBpmnFlow($obj)
    {
        $this->flowBean = $obj;
    }

    public function _get(array $args)
    {
        $result = array("success" => true);
        $this->gateway->retrieve_by_string_fields(array('gat_uid' => $args['record']));
        $orderBy = "flo_eval_priority ASC";
        $where = "flo_element_origin='" . $this->gateway->id . "' AND flo_element_origin_type='bpmnGateway' AND flo_type!='DEFAULT'";
        $resultArray = $this->flowBean->get_full_list($orderBy, $where);
        $data = array();
        if (is_array($resultArray)) {
            foreach ($resultArray as $key => $value) {
                $tmpObject = new stdClass();
                $tmpObject->flo_uid = $value->flo_uid;
                $tmpObject->flo_condition = $value->flo_condition;
                //            $json = '{"foo-bar": 12345}';
                //            $json = '[{"expDirection":"after","expFieldType":"TextField","expModule":"lead_direct_reports","expField":"account_name","expOperator":"equals","expValue":"Rodrigo","expType":"MODULE","expLabel":"Account Name == &quot;Rodrigo&quot;"}]';
                //
                //            $obj = json_decode($json);
                //            $tmpObject->flo_condition = $obj;
                $data[] = $tmpObject;
                $result['success'] = true;
            }
        }
        $result['data'] = $data;

        return $result;
    }

    /**
     * POST data with client object.
     * This method overrides the bpmGatewayDefinition record object. that object is passed
     * into an array named args.
     * @param array $args
     * @return array with succes value and object id
     */
    public function _post(array $args)
    {
        $data = array("success" => false);
        return $data;
    }

    /**
     * PUT data with client object.
     * This method updates the bpmGatewayDefinition record object. that object is passed
     * into an array named args.
     * @param array $args
     * @return array the succes value
     */
    public function _put(array $args)
    {
        $data = array("success" => false);

        if (isset($args['record']) && count($args) > 0) {
//        if (count($args['data']) > 0) {
            if ($this->gateway->retrieve_by_string_fields(array('gat_uid' => $args['record']))) {
                if ($this->gateway->fetched_row != false) {
                    $orderCounter = 0;
                    foreach ($args['data'] as $key => $value) {
//                        if (is_array($value)) {
                        $this->flowBean->retrieve_by_string_fields(array('flo_uid' => $value['flo_uid']));
                        $this->flowBean->flo_condition = $value['flo_condition'];
                        $this->flowBean->flo_eval_priority = $orderCounter;
                        $this->flowBean->save();
                        $orderCounter++;
//                        }
                    }
                    $data = array("success" => true);
                }
            }
        }
        return $data;
    }


}