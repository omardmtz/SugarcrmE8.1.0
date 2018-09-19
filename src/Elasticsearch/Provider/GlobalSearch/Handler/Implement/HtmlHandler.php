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
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\AbstractHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\ProcessDocumentHandlerInterface;

/**
 *
 * Auto increment field handler
 *
 */
class HtmlHandler extends AbstractHandler implements ProcessDocumentHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        foreach ($this->getFtsHtmlFields($bean->module_name) as $field) {
            if (isset($bean->$field)) {
                //$bean->$field contains the encoded html entities, e.g., "&lt;p&gt;To connect
                //your device to the Internet, use any application that accesses the Internet. &lt;/p&gt;"
                $value = html_entity_decode($bean->$field);

                //Html fields are stored including Html tags in database, which will be stripped
                //before sending over to Elastic
                $value = strip_tags($value);
                $document->setDataField($field, $value);
            }
        }
    }

    /**
     * Get HTML fields for module.
     * @param string $module
     * @return array
     */
    protected function getFtsHtmlFields($module)
    {
        return $this->provider->getContainer()->metaDataHelper->getFtsHtmlFields($module);
    }
}
