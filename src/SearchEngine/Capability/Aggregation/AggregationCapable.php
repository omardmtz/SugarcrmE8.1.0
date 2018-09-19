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

namespace Sugarcrm\Sugarcrm\SearchEngine\Capability\Aggregation;

use Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch\GlobalSearchCapable;

/**
 *
 * Aggregation (facet) capability implying the GlobalSearch capability
 *
 */
interface AggregationCapable extends GlobalSearchCapable
{
    /**
     * Query cross module aggregations, default false.
     * @param boolean $toggle
     * @return AggregationCapable
     */
    public function queryCrossModuleAggs($toggle);

    /**
     * Query module specific aggregations, default empty.
     * @param array $modules
     * @return AggregationCapable
     */
    public function queryModuleAggs(array $modules);

    /**
     * Set aggregation filters
     * @param array $filters
     * @return AggregationCapable
     */
    public function aggFilters(array $filters);
}
