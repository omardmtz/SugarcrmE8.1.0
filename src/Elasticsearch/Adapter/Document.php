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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Adapter;

use Elastica\Document as BaseDocument;

/**
 *
 * Adapter class for \Elastica\Document
 *
 */
class Document extends BaseDocument
{
    /**
     * Check whether the document has data
     * @return boolean
     */
    public function hasData()
    {
        return (!empty($this->_data));
    }

    /**
     * Set data field value
     * @param string $field Field name
     * @param mixed $value
     */
    public function setDataField($field, $value)
    {
        $this->_data[$field] = $value;
    }

    /**
     * Remove data field
     * @param string $field
     */
    public function removeDataField($field)
    {
        if (isset($this->_data[$field])) {
            unset($this->_data[$field]);
        }
    }
}
