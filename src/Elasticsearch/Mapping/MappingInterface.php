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

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\ProviderCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\RawProperty;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\ObjectProperty;
use SugarBean;

/**
 *
 * Mapping interface
 *
 */
interface MappingInterface
{
    /**
     * Build mapping
     * @param ProviderCollection $providers
     */
    public function buildMapping(ProviderCollection $providers);

    /**
     * Compile mapping properties
     * @return array
     */
    public function compile();

    /**
     * Create a module only field. This field will be added on top
     * of a MultiFieldBase. If the base does not exist it will be created.
     * The module field is prefixed with the "{Module}__" prefix.
     * @param string $baseField The non-prefixed base field name
     * @param string $field The (multi) field name to create the mapping for
     * @param MultiFieldProperty $property The mapping properties of the field
     */
    public function addModuleField($baseField, $field, MultiFieldProperty $property);

    /**
     * Create a common field. This field will be added on top
     * of a MultiFieldBase. If the base does not exist it will be created.
     * The common field is prefixed with the "Common__" prefix.
     * @param string $baseField The non-prefixed base field name
     * @param string $field The (multi) field name to create the mapping for
     * @param MultiFieldProperty $property The mapping properties of the field
     */
    public function addCommonField($baseField, $field, MultiFieldProperty $property);

    /**
     * Add object (or nested) property mapping. Note that this cannot be used
     * as a multi field base. The field name will be prefixed with the
     * "{Module}__" prefix automatically in the mapping to avoid mapping
     * conflicts between modules within the same index having the same
     * field name.
     *
     * @param string $field
     * @param ObjectProperty $property
     */
    public function addModuleObjectProperty($field, ObjectProperty $property);

    /**
     * Add object (or nested) property mapping. Note that this cannot be used
     * as a multi field base. The field name will be prefixed with the
     * "Common__" prefix automatically in the mapping to avoid mapping
     * conflicts between modules within the same index having the same
     * field name.
     *
     * @param string $field
     * @param ObjectProperty $property
     */
    public function addCommonObjectProperty($field, ObjectProperty $property);

    /**
     * Add multi field mapping. This should be the primary method to be used
     * to build the mapping as most fields are string based. Multi fields
     * have the ability to define different analyzers for every sub field.
     * During indexing the value for each multi field only has to be send once
     * instead of being duplicated.
     *
     * If no base field exists, the base field will be automatically created
     * by calling addNotAnalyzedField. If this is not desirable, create the
     * multi field base explicitly before adding multi field definitions on
     * top of it (i.e. by using addNotIndexedField).
     *
     * @param string $baseField Base field name
     * @param string $field Name of the multi field
     * @param MultiFieldProperty $property
     * @deprecated Use MappingInterface::addModuleField or MappingInterface::addCommonField
     */
    public function addMultiField($baseField, $field, MultiFieldProperty $property);

    /**
     * Add a not_analyzed string field to the mapping. This field can be used
     * as a base field to add additional multi field definitions. This is the
     * default base created when calling addMultiField.
     *
     * @param string $field Field name
     * @param array $copyTo Optional copy_to definition
     * @deprecated Use MappingInterface::addModuleField or MappingInterface::addCommonField
     */
    public function addNotAnalyzedField($field, array $copyTo = array());

    /**
     * Add not indexed field to the mapping which can be used as a multi field
     * base field. This is an alternative to addNotAnalyzedField for use cases
     * where the field content is expected to be big (+32'766 bytes) resulting
     * in not being able to use a not_analyzed field.
     *
     * @param string $field Field name
     * @param array $copyTo Optional copy_to definition
     * @deprecated Use MappingInterface::addModuleField or MappingInterface::addCommonField
     */
    public function addNotIndexedField($field, array $copyTo = array());

    /**
     * Add object (or nested) property mapping. Note that this cannot be used
     * as a multi field base. Also fields which are created like this can have
     * mapping collisions. If this is the case, one needs to create separate
     * indices to isolate the conflicting modules if any. It is encouraged to
     * use Mapping::addModuleObjectProperty instead.
     *
     * @param string $field
     * @param ObjectProperty $property
     */
    public function addObjectProperty($field, ObjectProperty $property);

    /**
     * Add raw property mapping. It is encouraged to use higher level property
     * objects instead and the respective methods on this class to configure
     * them instead of using a raw property. Use this method with caution. Note
     * that this cannot be used as a multi field base.
     *
     * @param string $field Field name
     * @param RawProperty $property
     */
    public function addRawProperty($field, RawProperty $property);

    /**
     * Verify if given property (field) exists.
     *
     * @param string $field Field name
     * @return boolean
     */
    public function hasProperty($field);

    /**
     * Get given property (field). It is advized to call hasProperty before
     * trying to access it as an exception is thrown if non-existant.
     *
     * @param string $field Field name
     * @throws MappingException When
     * @return PropertyInterface
     */
    public function getProperty($field);

    /**
     * Get module for current mapping object
     * @return string
     */
    public function getModule();

    /**
     * Get seed bean
     * @return SugarBean
    */
    public function getBean();

    /**
     * Add field to be excluded from _source
     * @param string $field
     */
    public function excludeFromSource($field);

    /**
     * Get excluded fields from _source
     * @return array
    */
    public function getSourceExcludes();
}
