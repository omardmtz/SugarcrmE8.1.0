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
 * Description of PMSETerminateValidator
 *
 */
class PMSETerminateValidator extends PMSEBaseValidator implements PMSEValidate
{
    /**
     *
     * @param PMSERequest $request
     * @return \PMSERequest
     */
    public function validateRequest(PMSERequest $request)
    {
        // This should be done right away
        $bean = $request->getBean();
        if (empty($bean)) {
            $request->invalidate();
            return $request;
        }

        $flowData = $request->getFlowData();
        if ($flowData['evn_id'] == 'TERMINATE') {
            $paramsRelated = $this->validateParamsRelated($bean, $flowData);
            $this->validateExpression($bean, $flowData, $request, $paramsRelated);
        }
        return $request;
    }

    /**
     *
     * @param type $bean
     * @param type $flowData
     * @param type $request
     * @param type $paramsRelated
     * @return type
     */
    public function validateExpression($bean, $flowData, $request, $paramsRelated = array())
    {
        // Start with trimming our criteria for evaluation
        $criteria = trim($flowData['evn_criteria']);

        // If the expression evaluates to terminate, handle that
        $valid = $criteria !== '' && $criteria !== '[]';
        if ($valid && $this->getEvaluator()->evaluateExpression($criteria, $bean, $paramsRelated)) {
            $request->setResult('TERMINATE_CASE');
        }

        // Used for logging
        $condition = $this->getEvaluator()->condition();
        $this->getLogger()->debug("Eval: $condition returned " . ($request->isValid()));

        return $request;
    }

    /**
     *
     * @param type $bean
     * @param type $flowData
     * @param type $externalAction
     * @return array
     */
    public function validateParamsRelated($bean, $flowData)
    {
        $paramsRelated = array();
        if (!PMSEEngineUtils::isTargetModule($flowData, $bean)) {
            $paramsRelated = array(
                'replace_fields' => array(
                    $flowData['rel_element_relationship'] => $flowData['rel_element_module']
                )
            );
        }

        $this->getLogger()->debug("Parameters related returned :" . print_r($paramsRelated, true));
        return $paramsRelated;
    }
}
