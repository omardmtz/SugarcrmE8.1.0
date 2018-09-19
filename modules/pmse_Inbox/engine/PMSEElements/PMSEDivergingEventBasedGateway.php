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


class PMSEDivergingEventBasedGateway extends PMSEDivergingGateway
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
        $routeAction = 'ROUTE';
        $flowAction = 'CREATE';
        $nonFlowElements = $this->getNextShapeElements($flowData);

        foreach ($nonFlowElements as $element) {
            if ($element['evn_type'] != 'INTERMEDIATE' || $element['evn_behavior'] != 'CATCH' || empty($element)) {
                $routeAction = 'WAIT';
            }
        }

        return $this->prepareResponse($flowData, $routeAction, $flowAction);
    }
}
