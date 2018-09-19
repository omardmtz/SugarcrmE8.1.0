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
 * Class that takes a parser expression to be evaluated and then return a value true or false
 * the expression to be entered in json
 *
 */
class PMSEEvaluator
{
    /**
     * Object of the class PMSEExpressionEvaluator
     * @var PMSEExpressionEvaluator
     */
    protected $expressionEvaluator;

    /**
     * Object of the class PMSECriteriaEvaluator
     * @var PMSECriteriaEvaluator
     */
    protected $criteriaEvaluator;

    /**
     * Object of the class DataParserGateway
     * @var object
     */
    protected $parser;

    /**
     * Constructor
     * initialize variables with classes parser and evaluation
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->expressionEvaluator = ProcessManager\Factory::getPMSEObject('PMSEExpressionEvaluator');
        $this->criteriaEvaluator = ProcessManager\Factory::getPMSEObject('PMSECriteriaEvaluator');
        $this->parser = ProcessManager\Factory::getPMSEObject('PMSEDataParserGateway');
    }


    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     *
     * @return PMSEExpressionEvaluator
     */
    function getExpressionEvaluator()
    {
        return $this->expressionEvaluator;
    }

    /**
     *
     * @return PMSECriteriaEvaluator
     */
    function getCriteriaEvaluator()
    {
        return $this->criteriaEvaluator;
    }

    /**
     *
     * @param PMSEExpressionEvaluator $expressionEvaluator
     */
    function setExpressionEvaluator($expressionEvaluator)
    {
        $this->expressionEvaluator = $expressionEvaluator;
    }

    /**
     *
     * @param PMSECriteriaEvaluator $criteriaEvaluator
     */
    function setCriteriaEvaluator($criteriaEvaluator)
    {
        $this->criteriaEvaluator = $criteriaEvaluator;
    }


    /**
     *
     * @param type $parser
     * @codeCoverageIgnore
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parsing and evaluation of expression
     * the expression is in json
     * @global object $current_user this is the current user object
     * @global object $beanList list of all modules of sugar
     * @param json $expression expression to evaluate
     * @param object $evaluatedBean this is the bean object
     * @param array $params if additional parameters
     * @param bool $returnToken DEPRECATED AS OF 7.9 AND WILL BE REMOVED IN A FUTURE RELEASE
     * @return bool
     */
    public function evaluateExpression($expression, $evaluatedBean, $params = array(), $returnToken = false)
    {
        global $current_user;
        global $beanList;
        $expression = json_decode(html_entity_decode($expression));
        if (isset($params['replace_fields']) && !empty($params['replace_fields'])) {
            foreach ($expression as $expKey => $expVal) {
                foreach ($expVal as $attrKey => $attrVal) {
                    foreach ($params['replace_fields'] as $fieldKey => $fieldVal) {
                        if ($attrVal == $fieldKey) {
                            $expression[$expKey]->$attrKey = $fieldVal;
                        }
                    }
                }
            }
        }

        $parsedArray = $this->parser->parseCriteriaArray (
            $expression,
            $evaluatedBean,
            $current_user,
            $beanList,
            $params
        );

        $parsedArray = $this->criteriaEvaluator->evaluateCriteriaTokenList($parsedArray);
        $resultArray = $this->expressionEvaluator->processExpression($parsedArray);

        if (empty($resultArray)) {
            // Empty $resultArray means that criteria is not defined or empty,
            // so return true is correct.
            $result = true;
        } else {
            $result = $resultArray[0];
            $expSubtype = PMSEEngineUtils::getExpressionSubtype($result);
            if (isset($expSubtype)) {
                $type = strtolower($expSubtype);
                switch ($type) {
                    case 'currency':
                        $result = json_encode($result);
                        break;
                    case 'email':
                    case 'html':
                    case 'phone':
                    case 'string':
                    case 'textarea':
                    case 'textfield':
                    case 'url':
                        if (count($resultArray) > 1) {
                            $result = '';
                            foreach ($resultArray as $item) {
                                $result .= $item->expValue;
                            }
                        } else {
                            $result = $result->expValue;
                        }
                        break;
                    default:
                        $result = $result->expValue;
                }
            }
        }

        return $result;
    }

    /**
     * Returns the operations performed with evaluation
     * @return string contains what I evaluated
     * @codeCoverageIgnore
     */
    public function condition()
    {
        return $this->expressionEvaluator->getCondition();
    }
}
