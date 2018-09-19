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

use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;

/**
 *
 * Abstract aggregation builder
 *
 */
abstract class AbstractAggregation implements AggregationInterface
{
    /**
     * User context
     * @var \User
     */
    protected $user;

    /**
     * List of possible configuration
     * @var array
     */
    protected $acceptedOptions = array();

    /**
     * Aggregation configuration options
     * @var array
     */
    protected $options = array();

    /**
     * Flag to indicate we use a filtered query
     * @var boolean
     */
    protected $filtered = false;

    /**
     * {@inheritdoc}
     */
    public function setUser(\User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option, $value)
    {
        if (in_array($option, $this->acceptedOptions)) {
            $this->options[$option] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildMapping(Mapping $mapping, $field, array $defs)
    {
        $property = new MultiFieldProperty();
        $property->setType('keyword');
        $mapping->addCommonField($field, 'agg', $property);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResults($id, array $results)
    {
        // When we wrapped in a filter we need to go one level deeper
        if ($this->filtered) {
            $buckets = $results[$id]['buckets'];
        } else {
            $buckets = $results['buckets'];
        }

        $parsed = array();
        foreach ($buckets as $bucket) {
            $parsed[$bucket['key']] = $bucket['doc_count'];
        }

        return $parsed;
    }

    /**
     * Apply configuration options through callbacks on the aggregation
     * object being passed in. This needs to be explicitly called from
     * the build phase by the implementing class if needed.
     *
     * @param \Elastica\Aggregation\AbstractAggregation $agg
     */
    protected function applyOptions(\Elastica\Aggregation\AbstractAggregation $agg)
    {
        foreach ($this->options as $option => $value) {
            $method = 'set' . ucfirst($option);
            if (method_exists($agg, $method)) {
                $value = is_array($value) ? $value : array($value);
                call_user_func_array(array($agg, $method), $value);
            }
        }
    }

    /**
     * Build boolean filter for given filters
     * @param \Elastica\Query\AbstractQuery[] $filters
     * @return \Elastica\Query\BoolQuery
     */
    protected function buildFilters(array $filters)
    {
        $result = new \Elastica\Query\BoolQuery();
        foreach ($filters as $filter) {
            $result->addMust($filter);
        }
        return $result;
    }

    /**
     * Wrap aggregation into filter
     * @param string $id
     * @param \Elastica\Aggregation\AbstractAggregation $agg
     * @param array $filters
     * @return \Elastica\Aggregation\Filter
     */
    protected function wrapFilter($id, \Elastica\Aggregation\AbstractAggregation $agg, array $filters)
    {
        $this->filtered = true;
        $filterAgg = new \Elastica\Aggregation\Filter($id);
        $filterAgg->setFilter($this->buildFilters($filters));
        $filterAgg->addAggregation($agg);
        return $filterAgg;
    }
}
