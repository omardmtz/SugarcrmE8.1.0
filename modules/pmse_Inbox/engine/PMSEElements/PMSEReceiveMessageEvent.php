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


class PMSEReceiveMessageEvent extends PMSEIntermediateEvent
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
     * @param type $flowData
     * @param type $bean
     * @param type $externalAction
     * @return type
     */
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        $result = $this->prepareResponse($flowData, 'WAIT', 'NONE');
        if (empty($externalAction)) {
            $flowData['cas_flow_status'] = 'WAITING';
            $flowData['cas_due_date'] = date('Y-m-d H:i:s', strtotime("+1 seconds"));
            $result = $this->prepareResponse($flowData, 'WAIT', 'CREATE');
        } else {
            if ($externalAction === 'EVALUATE_RELATED_MODULE' || $externalAction === 'EVALUATE_MAIN_MODULE') {
                $usesEBGateway = $this->checkIfUsesAnEventBasedGateway($flowData['cas_id'], $flowData['cas_previous']);
                $this->checkIfExistEventBased($flowData['cas_id'], $flowData['cas_previous'], $usesEBGateway);

                // Only set the bean on the result if there is one to set
                if ($procBean = $this->getProcessBean($flowData, $bean)) {
                    // We only need to modify the result if there is a process bean
                    $result = $this->prepareResponse($flowData, 'ROUTE', 'UPDATE');
                    $result['process_bean'] = $procBean;
                }
            }
        }
        return $result;
    }

    /**
     * Determines if a bean should be sent back at all, and if so, which one
     * @param array $flowData The current flow data set
     * @param SugarBean|null $bean The trigger bean, if passed
     * @return SugarBean The proper bean, if there is one
     */
    protected function getProcessBean(array $flowData, SugarBean $bean = null)
    {
        if ($bean === null || $bean->id === $flowData['cas_sugar_object_id']) {
            return BeanFactory::getBean($flowData['cas_sugar_module'], $flowData['cas_sugar_object_id']);
        }

        return null;
    }
}
