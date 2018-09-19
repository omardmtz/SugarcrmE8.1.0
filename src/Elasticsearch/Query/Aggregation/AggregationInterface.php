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

/**
 *
 * Aggregation builder interface
 *
 */
interface AggregationInterface
{
    /**
     * Set user context
     * @param \User $user
     */
    public function setUser(\User $user);

    /**
     * Set options
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Set option
     * @param string $option
     * @param mixed $value
     */
    public function setOption($option, $value);

    /**
     * Build mapping
     * @param Mapping $mapping
     * @param string $field
     * @param array $defs
     */
    public function buildMapping(Mapping $mapping, $field, array $defs);

    /**
     * Build aggregation for query purposes
     * @param string $id Aggregation identifier
     * @param array $filters
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function build($id, array $filters);

    /**
     * Build aggregation filter
     * @param array|boolean $options
     * @return mixed
     */
    public function buildFilter($filterDefs);

    /**
     * Parse raw aggregation results
     * @param string $id
     * @param array $results
     * @return array
     */
    public function parseResults($id, array $results);
}
