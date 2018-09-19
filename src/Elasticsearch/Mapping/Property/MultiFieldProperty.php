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

use Sugarcrm\Sugarcrm\Elasticsearch\Exception\MappingException;

/**
 *
 * Use this property to create multi field secondary fields. Most fields
 * are to be declared as multi fields to be able to analyse one given
 * by different analyzers. MultiFieldProperty objects can be added directly
 * on top of the mapping and will be stacked on a MultiFieldBaseProperty
 * automatically.
 *
 */
class MultiFieldProperty extends RawProperty implements PropertyInterface
{
    /**
     * Allowed elastic type supported by multi field
     * @var array
     */
    protected $allowedTypes = [
        'text',
        'keyword',
        'float',
        'double',
        'byte',
        'short',
        'integer',
        'long',
        'token_count',
        'date',
        'boolean',
    ];

    /**
     * @var string Core type
     */
    protected $type = 'text';

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        return array_merge(
            $this->mapping,
            array('type' => $this->type)
        );
    }

    /**
     * Set property type
     * @param string $type
     * @throws MappingException
     */
    public function setType($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new MappingException("Invalid type '{$type}' for MultiFieldProperty");
        }
        $this->type = $type;
    }
}
