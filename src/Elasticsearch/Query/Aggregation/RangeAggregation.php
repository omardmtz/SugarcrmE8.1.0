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
 * The implementation class for Range Aggregation.
 *
 */
class RangeAggregation extends AbstractAggregation
{
    /**
     * {@inheritdoc}
     */
    protected $acceptedOptions = array(
        'field',
        'ranges',
    );

    /**
     * Acceptable range filter options
     */
    protected $acceptedRangeOptions = array(
        'from',
        'to',
    );

    /**
     * {@inheritdoc}
     */
    public function build($id, array $filters)
    {
        $range = new \Elastica\Aggregation\Range($id);
        $range->setField($this->options['field']);

        // apply range definitions
        foreach ($this->options['ranges'] as $rangeDef) {
            $range->addParam('ranges', $rangeDef);
        }

        if (empty($filters)) {
            return $range;
        }

        return $this->wrapFilter($id, $range, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter($filterDefs)
    {
        if (!is_array($filterDefs) || empty($filterDefs)) {
            return false;
        }

        $filter = new \Elastica\Query\BoolQuery();
        foreach ($filterDefs as $rangeId) {

            if (!isset($this->options['ranges'])) {
                continue;
            }

            // create range filter
            $rangeFilter = new \Elastica\Query\Range();
            $rangeOptions = array_intersect_key(
                $this->options['ranges'][$rangeId],
                array_flip($this->acceptedRangeOptions)
            );

            $rangeFilter->addField($this->options['field'], $rangeOptions);

            // add it to the pile
            $filter->addShould($rangeFilter);
        }
        return $filter;
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
}
