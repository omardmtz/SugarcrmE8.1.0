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
 * This mapping property handles the primary field for a multi field setup.
 * Additional fields can be added on top of this object using the
 * MultiFieldProperty.
 *
 */
class MultiFieldBaseProperty extends RawProperty implements PropertyInterface
{
    /**
     * Base mapping
     * {@inheritdoc}
     */
    protected $mapping = array(
        'type' => 'keyword',
        'index' => false,
    );

    /**
     * Multi field properties
     * @var array
     */
    protected $fields = array();

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        $mapping = $this->mapping;

        // Only add fields if any are set
        if (!empty($this->fields)) {
            $mapping['fields'] = $this->fields;
        }

        return $mapping;
    }

    /**
     * Add multi field property
     * @param string $name
     * @param MultiFieldProperty $property
     * @throws MappingException
     */
    public function addField($name, MultiFieldProperty $property)
    {
        if (isset($this->fields[$name])) {
            throw new MappingException("Field '{$name}' already exists as multi field");
        }
        $this->fields[$name] = $property->getMapping();
    }
}
