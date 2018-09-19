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

use Sugarcrm\Sugarcrm\ProcessManager;

/**
 * Parses a condition of a business rule to a standard criterion to the value of the condition
 * one json is entered as a parameter to parser and get the new value of each case
 * a new json will be returned to the field value and data type
 *
 */
class PMSEBusinessRuleConversor
{
    /**
     *
     * @var type 
     */
    protected $evaluatedBean;
    
    /**
     * Case modulo
     * @var type string
     */
    protected $baseModule;

    protected $pmseRelatedModule;

    /**
     * The bean handler manages operations related to the Sugar Beans
     *
     * @var PMSEBeanHandler $beanHandler
     */
    protected $beanHandler;

    public function __construct()
    {
        $this->pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
        $this->beanHandler = ProcessManager\Factory::getPMSEObject('PMSEBeanHandler');
    }

    /**
     * Gets the module case
     * @return string name module case
     * @codeCoverageIgnore
     */
    public function getBaseModule()
    {
        return $this->baseModule;
    }

    /**
     * Sets the module case
     * @param string $baseModule set name module case
     * @codeCoverageIgnore
     */
    public function setBaseModule($baseModule)
    {
        $this->baseModule = $baseModule;
    }

    /**
     * Sets the bean
     * @param object $name set object bean
     * @codeCoverageIgnore
     */
    public function setEvaluatedBean($evaluatedBean)
    {
        $this->evaluatedBean = $evaluatedBean;
    }

    /**
     * Method that parser the conditions
     * @param object $businessRule token contains all the conditions and results
     * @return object
     */
    public function transformBusinessRule($businessRule)
    {
        if (isset($businessRule)) {
            $this->baseModule = $businessRule->base_module;
            foreach ($businessRule->ruleset as $key => $ruleset) {
                $businessRule->ruleset[$key]->conditions = $this->transformCondition($ruleset->conditions);
            }
        }
        return $businessRule;
    }

    /**
     * Method that adds an AND if there various business rules
     * @param array $conditionList token contains all the conditions
     * @return array
     */
    public function transformCondition($conditionList = array())
    {
        $criteriaArray = array();
        $counter = 0;
        
        if (is_array($conditionList) && empty($conditionList)) {
            $criteriaArray = $this->retrieveDefaultCondition();
        }
        
        foreach ($conditionList as $condition) {
            if ($counter > 0) {
                $andObject = new stdClass();
                $andObject->expValue = "AND";
                $andObject->expType = "LOGIC";
                $andObject->expLabel = "AND";
                $criteriaArray[] = $andObject;
            }
            $criteriaArray[] = $this->transformToken($condition);
            $counter++;
        }
        return $criteriaArray;
    }

    /**
     * Transforms a type expression business rules in a standard token
     * @param object $businessRuleToken token original
     * @return object token modified
     */
    public function transformToken($businessRuleToken)
    {
        $criteriaToken = new stdClass();
        if (is_object($businessRuleToken)) {
            $criteriaToken->expField = $businessRuleToken->variable_name;
            $criteriaToken->expOperator = $this->transformConditionOperator($businessRuleToken->condition);
            $criteriaToken->expDirection = "after";
            $criteriaToken->expType = "MODULE";
            // Add the value, subtype, and any other properties needed based on those.
            $this->addValueToTransformedToken($criteriaToken, $businessRuleToken);
            $separator = $criteriaToken->expSubtype == "STRING" ? "&" : "";
            $criteriaToken->expLabel = $criteriaToken->expField . " " . $businessRuleToken->condition . " " . $separator . $criteriaToken->expValue . $separator;
            $criteriaToken->expModule = $businessRuleToken->variable_module;
        }
        return $criteriaToken;
    }

    /**
     * Use the supplied business rule token to calculate and add the expValue, expSubtype,
     * and any other needed properties (such as expCurrency) to the supplied criteria token.
     * @param object $criteriaToken The criteria token to add the properties to. Is mutated.
     * @param object $businessRuleToken The business rule token used to calculate the fields.
     */
    public function addValueToTransformedToken($criteriaToken, $businessRuleToken)
    {
        // Process the value to account for complicated values and expressions.
        $valueToken = $this->processValueExpression($businessRuleToken->value);
        // Check if the value we're supposed to get is of a type the above function can't do well.
        $type = $this->evaluatedBean->field_defs[$criteriaToken->expField]['type'];
        switch (strtolower($type)) {
            // Currency is spat out encoded as a json containing all the things needed.
            case 'currency':
                $parsed = json_decode($valueToken->value);
                // This needs to be hard coded because processValueExpression returns type string.
                $criteriaToken->expSubtype = 'currency';
                $criteriaToken->expValue = $parsed->expValue;
                /*
                 * Currency values need an extra field called expCurrency to specify the id for the
                 * currency the amount is in (USD, EUR, GBP, etc.). Currently business
                 * rules store that value in expField instead of expCurrency, but also checking
                 * for expCurrency allows this to work if that is ever fixed.
                 */
                $criteriaToken->expCurrency = (isset($parsed->expCurrency)) ? $parsed->expCurrency : $parsed->expField;
                break;
            default:
                $criteriaToken->expSubtype = $valueToken->type;
                $criteriaToken->expValue = $valueToken->value;
        }
    }

    /**
     * Process that evaluates the expression
     * variable of type sugar or int, float, double and bool
     * @param array $businessRuleValueToken array containing all the tokens
     * @return object
     */
    public function processValueExpression($businessRuleValueToken)
    {
        $response = new stdClass();
        $response->value = $this->beanHandler->processValueExpression($businessRuleValueToken, $this->evaluatedBean);
        $response->type = gettype($response->value);
        return $response;
    }

    /**
     * Operator transforms a literal syntax
     * @param string $condition operator sign
     * @return string operator literal equivalent
     */
    public function transformConditionOperator($condition)
    {
        switch ($condition) {
            case '=':
                return 'equals';
                break;
            case '==':
                return 'equals';
                break;
            case '!=':
                return 'not_equals';
                break;
            case '<>':
                return 'not_equals';
                break;
            case '>=':
                return 'major_equals_than';
                break;
            case '<=':
                return 'minor_equals_than';
                break;
            case '<':
                return 'minor_than';
                break;
            case '>':
                return 'major_than';
                break;
            case 'equals':
            case 'not_equals':
            case 'starts_with':
            case 'ends_with':
            case 'contains':
            case 'does_not_contain':
                return $condition;
                break;
            default:
                break;
        }
    }

    /**
     * Method that returns the value to returned in a business rule
     * @param array $conclusions values ​​where this conclusion
     * @return json
     */
    public function getReturnValue($conclusions)
    {
        foreach ($conclusions as $conclusion) {
            if ($conclusion->conclusion_type == "return") {
                $valueToken = $this->processValueExpression($conclusion->value);
                return json_encode($valueToken);
            }
        }
    }

    /**
     * Method that sets the value of the conclusion
     * @param array $conclusions
     * @param array $appData
     * @return array
     */
    public function processAppData($conclusions, $appData = array())
    {
        if (isset($conclusions)) {
            foreach ($conclusions as $conclusion) {
                if ($conclusion->conclusion_type == 'variable') {
                    $valueToken = $this->processValueExpression($conclusion->value);
                    $type = $this->evaluatedBean->field_defs[$conclusion->conclusion_value]['type'];
                    switch ($type) {
                        case 'currency':
                            $currencyFields = json_decode($valueToken->value);
                            if (!empty($currencyFields) && (!empty($currencyFields->expField)) && (!empty($currencyFields->expValue))) {
                                $valueToken->value = $currencyFields->expValue;
                            }
                            break;
                        case 'date':
                        case 'datetimecombo':
                            $valueToken->value = $this->processDateValue($type, $valueToken->value);
                            break;
                        default:
                    }
                    $appData[$conclusion->conclusion_value] = $valueToken->value;
                }
            }
        }
        return $appData;
    }

    /**
     * Converts a date/datetime string to the corresponding object
     * @param string $type The type of the input string
     * @param string $value The input date/datetime string
     * @return DateTime
     */
    private function processDateValue($type, $value)
    {
        $field = new stdClass();
        switch ($type) {
            case 'date':
                $field->type = 'Date';
                break;
            case 'datetimecombo':
                $field->type = 'Datetime';
                break;
            default:
                return null;
        }
        $task = ProcessManager\Factory::getPMSEObject('PMSEScriptTask');
        return $task->getDBDate($field, $value);
    }

    /**
     * Converts the conclusion to a string
     * @param array $conclusions DEPRECATED AS OF 7.9 AND WILL BE REMOVED IN A FUTURE RELEASE
     * @param array $appData
     * @return string
     */
    public function processConditionResult($conclusions = array(), $appData = array())
    {
        $result = '';
        foreach ($appData as $key => $value) {
            $value = is_string($value) ? "'" . $value . "'" : $value;
            $result .= "{::" . $this->baseModule . "::" . $key . "::} = " . $value . ";";
        }
        return $result;
    }
    
    public function retrieveDefaultCondition()
    {
        $condition = new stdClass();
        $condition->expValue = true;
        $condition->expLabel = 'true';
        $condition->expType = 'CONSTANT';
        $condition->expSubtype = 'BOOLEAN';
        return array($condition);
    }
    
}

?>
