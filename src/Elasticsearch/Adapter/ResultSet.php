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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Adapter;

use Sugarcrm\Sugarcrm\SearchEngine\Capability\Aggregation\ResultSetInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Aggregation\AggregationStack;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Result\ParserInterface;

/**
 *
 * Adapter class for \Elastica\ResultSet
 *
 */
class ResultSet implements \Iterator, \Countable, ResultSetInterface
{
    /**
     * @var \Elastica\ResultSet
     */
    protected $resultSet;

    /**
     * @var ParserInterface
     */
    protected $resultParser;

    /**
     * @var AggregationStack
     */
    protected $aggregationStack;

    /**
     * Ctor
     * @param \Elastica\ResultSet $resultSet
     */
    public function __construct(\Elastica\ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    /**
     * Set result parser
     * @param ParserInterface $parser
     */
    public function setResultParser(ParserInterface $parser)
    {
        $this->resultParser = $parser;
    }

    /**
     * Get result parser
     * @return ParserInterface $parser
     */
    public function getResultParser()
    {
        return $this->resultParser;
    }

    /**
     * Set aggregation stack
     * @param AggregationStack $aggregations
     */
    public function setAggregationStack(AggregationStack $stack)
    {
        $this->aggregationStack = $stack;
    }

    /**
     * Overload \Elastica\ResultSet
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array(array($this->resultSet, $method), $args);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = new Result($this->resultSet->current());
        if ($this->resultParser) {
            $current->setResultParser($this->resultParser);
        }
        return $current;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->resultSet->key();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->resultSet->next();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->resultSet->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->resultSet->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->resultSet->count();
    }

    /**
     * @return \Elastica\ResultSet
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    //// ResultSetInterface ////

    /**
     * {@inheritdoc}
     */
    public function getTotalHits()
    {
        return $this->resultSet->getTotalHits();
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryTime()
    {
        return $this->resultSet->getTotalTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregations()
    {
        $aggs = array();

        if (empty($this->aggregationStack)) {
            return $aggs;
        }

        // iterate raw results and use the stack to parse them
        foreach ($this->resultSet->getAggregations() as $id => $results) {

            if (!is_array($results)) {
                continue;
            }

            // try to get the aggregation from the stack
            if (!$agg = $this->aggregationStack->getById($id)) {
                continue;
            }

            $aggs[$id] = array(
                'results' => $agg->parseResults($id, $results),
            );
        }
        return $aggs;
    }
}
