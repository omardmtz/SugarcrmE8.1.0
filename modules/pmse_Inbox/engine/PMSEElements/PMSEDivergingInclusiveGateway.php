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


class PMSEDivergingInclusiveGateway extends PMSEDivergingGateway
{
    /**
     *
     * @param type $flowData
     * @param type $bean
     * @param type $externalAction
     * @return type
     */
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        $flowAction = 'CREATE';
        $filters = $this->filterFlows(
            'ALL',
            $this->retrieveFollowingFlows($flowData),
            $bean,
            $flowData
        );

        $routeAction = empty($filters) ? 'WAIT' : 'ROUTE';

        return $this->prepareResponse($flowData, $routeAction, $flowAction, $filters);
    }
}
