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
 * Geo point property.
 *
 */
class GeoProperty extends RawProperty implements PropertyInterface
{
    /**
     * @var string
     */
    protected $type = 'geo_point';

    /**
     * Field data settings
     * @var array
     */
    protected $fieldData = array();

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        // base mapping
        $mapping = array_merge(
            $this->mapping,
            array(
                'type' => $this->type,
            )
        );

        // field data
        if (!empty($this->fieldData)) {
            $mapping['fielddata'] = $this->fieldData;
        }

        return $mapping;
    }

    /**
     * Set field data
     * @param array $fieldData
     */
    public function setFieldData(array $fieldData)
    {
        $this->fieldData = $fieldData;
    }
}
