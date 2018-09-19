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



class PMSEConvergingParallelGateway extends PMSEConvergingGateway
{
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        $routeAction = 'WAIT';
        $flowAction = 'NONE';
        $filters = array();
        $complete = false;
        $previousFlows = $this->retrievePreviousFlows('PASSED', $flowData['bpmn_id'], $flowData['cas_id']);
        $totalFlows = $this->retrievePreviousFlows('ALL', $flowData['bpmn_id']);
        if (sizeof($previousFlows) === sizeof($totalFlows)) {
            $routeAction = 'ROUTE';
            $flowAction = 'CREATE';
            $complete = true;
        }
        $result = $this->prepareResponse($flowData, $routeAction, $flowAction, $filters);
        if ($complete) {
            $result['previous_flows'] = $previousFlows;
            $result['create_thread'] = true;
        } else {
            $result['previous_flows'] = array();
            $result['close_thread'] = true;
        }

        return $result;
    }

}
