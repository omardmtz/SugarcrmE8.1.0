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


class PMSEDivergingExclusiveGateway extends PMSEDivergingGateway
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
            'SINGLE',
            $this->retrieveFollowingFlows($flowData),
            $bean,
            $flowData
        );
        
        switch ($externalAction) {
            case 'RESUME_EXECUTION':
                $flowAction = 'UPDATE';
                break;
        }

        if (empty($filters)) {
            throw new PMSEElementException('The gateway probably doesn\'t have any configuration', $flowData, $this);
        } else {
            $routeAction = 'ROUTE';
        }

        return $this->prepareResponse($flowData, $routeAction, $flowAction, $filters);
    }
}
