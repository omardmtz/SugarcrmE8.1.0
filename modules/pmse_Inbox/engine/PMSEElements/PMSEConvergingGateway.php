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




class PMSEConvergingGateway extends PMSEGateway
{
    public function retrievePreviousFlows($type, $elementId, $casId = '')
    {
        $sugarQuery = $this->retrieveSugarQueryObject();
        $flowBean = $this->caseFlowHandler->retrieveBean('pmse_BpmnFlow');
        
        $sugarQuery->select(array('a.id'));
        $sugarQuery->select()->fieldRaw('b.cas_id');
        $sugarQuery->select()->fieldRaw('b.cas_index');
        $sugarQuery->select()->fieldRaw('b.cas_thread');

        $sugarQuery->from($flowBean, array('alias' => 'a'));
        
        switch ($type){
            case 'PASSED':
                $joinType = 'INNER';
                $whereClause = 'b.bpmn_type=\'bpmnFlow\' AND b.cas_id=\''.$casId.'\' AND';
                break;
            case 'ALL':
            default:
                $joinType = 'LEFT';
                $whereClause = '';
                break;
        };

        $sugarQuery->joinTable('pmse_bpm_flow', array('joinType' => $joinType, 'alias' => 'b'))
            ->on()->equalsField('a.id', 'b.bpmn_id');
        $sugarQuery->where()->queryAnd()
            ->addRaw("{$whereClause} a.flo_element_dest='{$elementId}' AND a.flo_element_dest_type='bpmnGateway'");
        $flows = $sugarQuery->execute();

        $filteredFlows = array();
        foreach ($flows as $element) {
            if (!array_key_exists($element['id'], $filteredFlows)) {
                $filteredFlows[$element['id']] = $element;
            }
        }
        return $filteredFlows;
    }
}
