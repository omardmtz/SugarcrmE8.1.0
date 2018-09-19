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
 * This mapping property handles the creation of an object mapping.
 * Note that an ObjectProperty cannot be stacked on top as a regular
 * MultiFieldProperty and needs to be created on a dedicated field.
 *
 */
class ObjectProperty extends RawProperty implements PropertyInterface
{
    /**
     * @var string
     */
    protected $type = 'object';

    /**
     * Dynamic mapping
     * @var false|true|strict
     */
    protected $dynamic = false;

    /**
     * Enable/disable parsing
     * @var boolean
     */
    protected $enabled = true;

    /**
     * Object mapping properties
     * @var PropertyInterface[]
     */
    protected $properties = array();

    /**
     * include_in_all
     * @var boolean
     * @deprecated
     */
    protected $includeInall = false;

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        $mapping = array_merge(
            $this->mapping,
            array(
                'type' => $this->type,
                'dynamic' => $this->dynamic,
                'enabled' => $this->enabled,
            )
        );

        // Only add properties if any are set
        if (!empty($this->properties)) {
            $mapping['properties'] = $this->properties;
        }

        return $mapping;
    }

    /**
     * Set dynamic option:
     *  false:  Disable dynamic mapping (default)
     *  true:   Enable dynamic mapping
     *  strict: Disable dynamic mapping and fail indexing
     *          of documents containing fields not defined
     *          in the mapping.
     *
     * @param false|true|strict $value
     */
    public function setDynamic($value)
    {
        $this->dynamic = $value;
    }

    /**
     * Set enabled flag
     * @param boolean $toggle
     */
    public function setEnabled($toggle)
    {
        $this->enabled = $toggle;
    }

    /**
     * Set include_in_all flag
     * @param boolean $toggle
     * @deprecated
     */
    public function setIncludeInAll($toggle)
    {
        $this->includeInall = $toggle;
    }

    /**
     * Add new property
     * @param string $name Property name
     * @param PropertyInterface $property
     */
    public function addProperty($name, PropertyInterface $property)
    {
        if (isset($this->properties[$name])) {
            throw new MappingException("Field '{$name}' already exists on object");
        }
        $this->properties[$name] = $property->getMapping();
    }
}
