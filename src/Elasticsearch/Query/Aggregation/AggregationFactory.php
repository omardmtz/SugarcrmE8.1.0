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
 * Aggregation Factory loads a specific aggregation based on the given type.
 *
 */
class AggregationFactory
{
    /**
     * Local cache
     * @var array
     */
    protected static $loaded = array();

    /**
     * Load aggregation object. Be careful as this is a cached factory.
     * @param string $type
     * @return AggregationInterface
     */
    public static function get($type)
    {
        if (!isset(self::$loaded[$type])) {
            self::$loaded[$type] = self::create($type);
        }
        return self::$loaded[$type];
    }

    /**
     * Create new aggregation object
     * @param string $type
     * @return AggregationInterface
     */
    public static function create($type)
    {
        $className = \SugarAutoLoader::customClass(sprintf(
            'Sugarcrm\Sugarcrm\Elasticsearch\Query\Aggregation\%sAggregation',
            ucfirst($type)
        ));
        return new $className();
    }
}
