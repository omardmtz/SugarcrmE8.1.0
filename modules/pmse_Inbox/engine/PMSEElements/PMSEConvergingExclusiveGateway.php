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



class PMSEConvergingExclusiveGateway extends PMSEConvergingGateway
{
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        $routeAction = 'WAIT';
        $flowAction = 'NONE';
        $filters = array();
        $previousFlows = $this->retrievePreviousFlows('PASSED', $flowData['bpmn_id'], $flowData['cas_id']);
        $reached = false;
        if (sizeof($previousFlows) === 1) {
            $routeAction = 'ROUTE';
            $flowAction = 'CREATE';
            $reached = true;
        }
        $result =  $this->prepareResponse($flowData, $routeAction, $flowAction, $filters);
        if ($reached) {
            $result['create_thread'] = true;
        }
        $result['close_thread'] = true;
        return $result;
    }
}
