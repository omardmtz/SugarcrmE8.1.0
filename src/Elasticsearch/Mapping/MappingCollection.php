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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Mapping;

/**
 *
 * Mapping collection iterator
 *
 */
class MappingCollection implements \IteratorAggregate
{
    /**
     * @param array $modules Module list
     */
    public function __construct(array $modules)
    {
        foreach ($modules as $module) {
             $this->$module = new Mapping($module);
        }
    }

    /**
     * {@inheritdoc}
     * @return Mapping[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this);
    }
}
