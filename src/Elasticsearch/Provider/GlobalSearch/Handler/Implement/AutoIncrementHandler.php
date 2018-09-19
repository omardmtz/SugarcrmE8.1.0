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
class AutoIncrementHandler extends AbstractHandler implements ProcessDocumentHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        foreach ($this->getFtsAutoIncrementFields($bean->module_name) as $field) {
            if (!isset($bean->$field)) {
                $value = $this->retrieveFieldByQuery($bean, $field);
                if (!empty($value)) {
                    $document->setDataField($field, $value);
                }
            }
        }
    }

    /**
     * Retrieve the value of a given field from the database.
     * @param \SugarBean $bean
     * @param $fieldName The name of the field
     * @return $string
     */
    protected function retrieveFieldByQuery(\SugarBean $bean, $fieldName)
    {
        $sq = new \SugarQuery();
        $sq->select(array($fieldName));
        $sq->from($bean, array('team_security' => false));
        $sq->where()->equals("id", $bean->id);
        $result = $sq->execute();

        // expect only one record
        if (!empty($result)) {
            return $result[0][$fieldName];
        } else {
            return null;
        }
    }

    /**
     * Get auto increment fields for module.
     * @param string $module
     * @return array
     */
    protected function getFtsAutoIncrementFields($module)
    {
        return $this->provider->getContainer()->metaDataHelper->getFtsAutoIncrementFields($module);
    }
}
