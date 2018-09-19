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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property;

/**
 *
 * Raw properties are possible but are very exceptional. Use this object
 * with caution when needed. Mostly other higher level mapping objects are
 * more appropriate to use. Use this as a last resort if nothing of the
 * other mapping properties fit your use case.
 *
 */
class RawProperty implements PropertyInterface
{
    /**
     * @var array Mapping definition
     */
    protected $mapping = array();

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * {@inheritdoc}
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * {@inheritdoc}
     */
    public function addCopyTo($field)
    {
        // initialize copy_to parameter if not set
        if (!isset($this->mapping['copy_to'])) {
            $this->mapping['copy_to'] = array();
        }

        // avoid duplicates just in case
        if (!in_array($field, $this->mapping['copy_to'])) {
            $this->mapping['copy_to'][] = $field;
        }
    }
}
