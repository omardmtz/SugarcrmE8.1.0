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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Query\Parser;

use Sugarcrm\Sugarcrm\Elasticsearch\Exception\QueryBuilderException;

/**
 * Class BasicTerms
 * @package Sugarcrm\Sugarcrm\Elasticsearch\Query\Parser
 */
class BasicTerms
{
    /**
     * operator for this term
     * @var bool|string
     */
    protected $operator;

    /**
     * list of terms, the entry of terms could be either string or BasicTerms object
     * @var array
     */
    protected $terms = array();

    /**
     * ctor
     * @param $operator
     * @param array $terms
     */
    public function __construct($operator, array $terms = array())
    {

        $this->operator = TermParserHelper::getOperator($operator);
        if (!$this->operator) {
            throw new QueryBuilderException("invalid operator: " . $operator);
        }
        $this->terms = $terms;
    }

    /**
     * return operator
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * to get terms
     * @return array
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * to add a term to the $terms, ignore if it is empty or has no terms
     * @param $term
     */
    public function addTerm($term)
    {
        if (!empty($term) && (is_string($term) || ($term instanceof BasicTerms && $term->hasTerm()))) {
            $this->terms[] = $term;
        }
    }

    /**
     * check if it contains terms
     * @return bool
     */
    public function hasTerm()
    {
        return !empty($this->terms);
    }

    /**
     * export the object to array with format: array('OPERATOR' => array of terms),
     * also combine multiple terms into one string deliminted by space if it is 'OR' operator
     * @return array
     */
    public function toArray()
    {
        // ignore the first operator if is single next structure
        if (count($this->terms) === 1) {
            if (!is_string($this->terms[0])) {
                return $this->terms[0]->toArray();
            }
        }

        $operand = array();
        $singleTerms = array();
        foreach ($this->terms as $term) {
            if (is_string($term)) {
                if (TermParserHelper::isOrOperator($this->operator)) {
                    $singleTerms[] = $term;
                } else {
                    $operand[] = $term;
                }
            } else {
                $operand[] = $term->toArray();
            }
        }

        // combine All 'OR' terms into one string with space deliminator
        if (!empty($singleTerms)) {
            $singleTermsStr = implode(' ', $singleTerms);
            $operand[] = $singleTermsStr;
        }

        return array($this->operator => $operand);
    }
}
