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
 * Generic terms aggregation
 *
 */
class TermsAggregation extends AbstractAggregation
{
    /**
     * {@inheritdoc}
     */
    protected $acceptedOptions = array(
        'field',
        'size',
        'order',
    );

    /**
     * {@inheritdoc}
     */
    protected $options = array(
        'size' => 5,
        'order' => array('_count', 'desc'),
    );

    /**
     * {@inheritdoc}
     */
    public function build($id, array $filters)
    {
        $terms = new \Elastica\Aggregation\Terms($id);
        $this->applyOptions($terms);

        if (empty($filters)) {
            return $terms;
        }

        return $this->wrapFilter($id, $terms, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter($filterDefs)
    {
        if (!is_array($filterDefs) || empty($filterDefs)) {
            return false;
        }

        $filter = new \Elastica\Query\Terms();
        $filter->setTerms($this->options['field'], $filterDefs);
        return $filter;
    }
}
