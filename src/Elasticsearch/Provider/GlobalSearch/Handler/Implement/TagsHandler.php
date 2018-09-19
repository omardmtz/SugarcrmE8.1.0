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

use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\AbstractHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\MappingHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\ProcessDocumentHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;

/**
 *
 * Tags handler
 *
 */
class TagsHandler extends AbstractHandler implements
    MappingHandlerInterface,
    ProcessDocumentHandlerInterface
{
    /**
     * Field name to use for tag Ids
     * @var string
     */
    const TAGS_FIELD = 'tags';

    /**
     * @var \Tag
     */
    protected $tagSeed;

    /**
     * {@inheritdoc}
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        $document->setDataField(self::TAGS_FIELD, $this->retrieveTagIds($bean));
    }

    /**
     * Retrieve the value of a given field from the database.
     * @param string $beanId the id of the associated bean
     * @return array
     */
    protected function retrieveTagIds(\SugarBean $bean)
    {
        // setup seed bean once
        if (empty($this->tagSeed)) {
            $this->tagSeed = \BeanFactory::newBean("Tags");
        }

        return $this->tagSeed->getTagIdsByBean($bean);
    }

    /**
     * {@inheritdoc}
     */
    public function buildMapping(Mapping $mapping, $field, array $defs)
    {
        // We only handle 'tag' fields of 'tag' type
        if ($defs['name'] !== 'tag' || $defs['type'] !== 'tag') {
            return;
        }

        // we just need an not_analyzed field here
        $property = new MultiFieldProperty();
        $property->setType('keyword');
        $mapping->addCommonField(self::TAGS_FIELD, 'tags', $property);
    }
}
