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
 * Low level mapping property interface
 *
 */
interface PropertyInterface
{
    /**
     * Get property mapping definition
     * @return array
     */
    public function getMapping();

    /**
     * Set mapping explicitly
     * @param array $mapping
     */
    public function setMapping(array $mapping);

    /**
     * Add copy_to field definition
     * @param string $field Field name to copy to
     */
    public function addCopyTo($field);
}
