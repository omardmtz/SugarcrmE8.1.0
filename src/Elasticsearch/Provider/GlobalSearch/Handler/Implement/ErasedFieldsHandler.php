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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement;

use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Repository;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\GlobalSearch;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\AbstractHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\MappingHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\ProcessDocumentHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;

/**
 *
 * ErasedFields handler
 *
 */
class ErasedFieldsHandler extends AbstractHandler implements
    MappingHandlerInterface,
    ProcessDocumentHandlerInterface
{
    /**
     * Field name
     * @var string
     */
    const ERASEDFIELDS_FIELD = 'erased_fields';

    /**
     * {@inheritdoc}
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        $document->setDataField(self::ERASEDFIELDS_FIELD, $this->retrieveErasedFields($bean));
    }

    /**
     * Retrieve the value of a given field from the database.
     * @param \SugarBean $bean, associated bean
     * @return array
     */
    protected function retrieveErasedFields(\SugarBean $bean)
    {
        return json_encode($this->getErasedFieldsRepository()->getBeanFields($bean->table_name, $bean->id));
    }

    /**
     * {@inheritdoc}
     */
    public function buildMapping(Mapping $mapping, $field, array $defs)
    {
        // create a new field named as 'erased_fields' for this module
        if (!$mapping->hasProperty(self::ERASEDFIELDS_FIELD)) {
            $property = new MultiFieldProperty();
            $property->setType('keyword');
            $mapping->addCommonField(self::ERASEDFIELDS_FIELD, self::ERASEDFIELDS_FIELD, $property);
        }
    }

    /**
     *
     * @return Repository
     */
    protected function getErasedFieldsRepository()
    {
        return Container::getInstance()->get(Repository::class);
    }
}
