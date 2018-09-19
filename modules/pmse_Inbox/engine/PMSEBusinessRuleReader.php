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
 * Class that an analysis of a business rule and evaluates
 * classes used PMSEBusinessRuleConversor where it parses a business rule and
 * PMSEExpressionEvaluator performs an evaluation of the conditions have a business rule
 *
 */

use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEBusinessRuleReader
{
    /**
     * Global evaluation extencion
     * @var string
     */
    public $extensionGlobal = 'G@';

    /**
     * additional variables necessary
     * @var array
     */
    public $appDataVar = array();

    /**
     * global variables
     * @var array
     */
    public $globalVar = array();

    /**
     * Object of class PMSEExpressionEvaluator
     * @var object
     */
    public $evaluator;
    
    /**
     * Object of class PMSEBusinessRuleConversor
     * @var object
     */
    public $businessRuleConversor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appDataVar = array();
        $this->globalVar = array();
        $this->businessRuleConversor = ProcessManager\Factory::getPMSEObject('PMSEBusinessRuleConversor');
        $this->evaluator = ProcessManager\Factory::getPMSEObject('PMSEEvaluator');
    }

    /**
     * Initializing the instance variables
     * @param array $appData
     * @param array $global
     */
    public function init($appData = array(), $global = array())
    {
        $this->appDataVar = $appData;
        $this->globalVar = $global;
    }

    /**
     * get object variable to analyze the business rule
     * @return object
     */
    public function getBusinessRuleParser()
    {
        return $this->businessRuleConversor;
    }

    /**
     * set object variable to analyze the business rule
     * @param object $businessRuleParser
     */
    public function setBusinessRuleParser($businessRuleParser)
    {
        $this->businessRuleConversor = $businessRuleParser;
    }

    /**
     * get variable object for evaluation
     * @return object
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     * set variable object for evaluation
     * @param object $evaluator
     */
    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     * Method that converts a standard business rule conditions and makes the evaluation of the condition
     * @param string $sugarModule the module case
     * @param json $ruleSetJSON the expression
     * @param string $type
     * @return array
     */
    public function parseRuleSetJSON($sugarModule, $ruleSetJSON, $type = 'single')
    {
        $res = '';
        $evaluatedBean = BeanFactory::getBean($sugarModule, $this->appDataVar['id']);
        $ruleSet = json_decode($ruleSetJSON);
        $appData = array();
        $successReturn = "";
        $this->businessRuleConversor->setBaseModule($ruleSet->base_module);
        foreach ($ruleSet->ruleset as $key => $rule) {
            $this->businessRuleConversor->setEvaluatedBean($evaluatedBean);
            $transformedCondition = $this->businessRuleConversor->transformCondition($rule->conditions);
            $transformedCondition = json_encode($transformedCondition);
            $evaluationResult = $this->evaluator->evaluateExpression($transformedCondition, $evaluatedBean);
            if ($evaluationResult) {
                $successReturn = $this->businessRuleConversor->getReturnValue($rule->conclusions);
                $appData = $this->businessRuleConversor->processAppData($rule->conclusions, $appData);
            }
            if ($type == 'single' && $evaluationResult) {
                break;
            }
        }
        if (count($appData)) {
            $res .= $this->businessRuleConversor->processConditionResult(array(), $appData);
        }
        $log = "The following condition: \n" . $transformedCondition . " has returned: \n" . json_encode($successReturn);
        $resultArray = array('log' => $log, 'return' => $successReturn, 'result' => $res, 'newAppData' => $appData);
        return $resultArray;
    }
}
