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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Filter;

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Visibility;

/**
 *
 * Filter interface
 *
 */
interface FilterInterface
{
    /**
     * Set visibility provider
     * @param Visibility $provider
     */
    public function setProvider(Visibility $provider);

    /**
     * Build filter
     * @param array $options
     * @return \Elastica\Query\AbstractQuery
     */
    public function buildFilter(array $options = array());
}
