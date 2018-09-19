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
 * Abstract class for Filter Aggregation.
 *
 */
abstract class FilterAggregation extends AbstractAggregation
{
    /**
     * {@inheritdoc}
     */
    protected $acceptedOptions = array(
        'field',
    );

    /**
     * {@inheritdoc}
     */
    protected $options = array(
    );

    /**
     * {@inheritdoc}
    */
    public function build($id, array $filters)
    {
        // Add our own filter to the stack
        $filters[] = $this->getAggFilter($this->options['field']);

        $agg = new \Elastica\Aggregation\Filter($id);
        $agg->setFilter($this->buildFilters($filters));
        return $agg;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter($filterDefs)
    {
        if (!is_bool($filterDefs)) {
            return false;
        }

        return $filterDefs ? $this->getAggFilter($this->options['field']) : false;
    }

    /**
     * {@inheritdoc}
     */
    public function parseResults($id, array $results)
    {
        return array(
            'count' => empty($results['doc_count']) ? 0 : $results['doc_count'],
        );
    }

    /**
     * Get aggregation filter definition
     * @param string $field
     * @return \Elastica\Query\AbstractQuery
     */
    abstract protected function getAggFilter($field);
}
