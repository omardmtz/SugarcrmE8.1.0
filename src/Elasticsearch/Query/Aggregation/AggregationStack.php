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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Query\Aggregation;

/**
 *
 * Aggregation stack
 *
 */
class AggregationStack implements \IteratorAggregate
{
    /**
     * @var AggregationInterface[]
     */
    protected $stack = array();

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->stack);
    }

    /**
     * Add aggregation object
     * @param string $id Aggregation identifier
     * @param AggregationInterface $aggregation
     */
    public function addAggregation($id, AggregationInterface $aggregation)
    {
        $this->stack[$id] = $aggregation;
    }

    /**
     * Get aggregation by id
     * @param string $id
     * @return AggregationInterface|false
     */
    public function getById($id)
    {
        return (isset($this->stack[$id])) ? $this->stack[$id] : false;
    }

    /**
     * Get filters
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Build aggregations
     * @param array $filterDefs
     * @return \Elastica\Aggregation\AbstractAggregation[]
     */
    public function buildAggregations(array $filterDefs)
    {
        // create filters first
        $this->filters = $filters = $this->buildFilters($filterDefs);

        // now build each aggregation and apply all filters except for its own
        // to each aggregation and register them on the query object
        $aggs = array();
        foreach ($this->stack as $id => $agg) {
            $aggFilters = $this->getAggFiltersForAggId($id, $filters);
            $aggs[] = $agg->build($id, $aggFilters);
        }
        return $aggs;
    }

    /**
     * Build filters for all aggregations
     * @param array $filterDefs
     * @return array
     */
    protected function buildFilters(array $filterDefs)
    {
        $filters = array();
        foreach ($this->stack as $id => $agg) {
            if (isset($filterDefs[$id])) {
                if ($filter = $agg->buildFilter($filterDefs[$id])) {
                    $filters[$id] = $filter;
                }
            }
        }
        return $filters;
    }

    /**
     * Get aggregation filter for given id. This basically means that we take
     * the full list of created filters and filter them by id.
     * @param unknown $id
     * @param array $filters
     * @return unknown
     */
    protected function getAggFiltersForAggId($id, array $filters)
    {
        if (isset($filters[$id])) {
            unset($filters[$id]);
        }
        return $filters;
    }
}
