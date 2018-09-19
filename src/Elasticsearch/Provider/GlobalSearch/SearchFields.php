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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch;

/**
 *
 * SearchFields collection
 *
 */
class SearchFields implements \IteratorAggregate
{
    /**
     * @var Booster
     */
    protected $booster;

    /**
     * List of search fields
     * @var SearchField[]
     */
    protected $searchFields = [];

    /**
     * Ctor
     * @param Booster $booster
     */
    public function __construct(Booster $booster = null)
    {
        $this->booster = $booster;
    }

    /**
     * {@inheritdoc}
     * @return SearchField[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->searchFields);
    }

    /**
     * Add search field to the stack
     * @param SearchField $field
     * @param string $boostWeightId
     */
    public function addSearchField(SearchField $field, $boostWeightId = null)
    {
        // apply weighted boost value
        if ($this->booster && $boostWeightId !== null) {
            $field->setBoost($this->booster->getBoostValue($field->getDefs(), $boostWeightId));
        }
        $this->searchFields[] = $field;
    }
}
