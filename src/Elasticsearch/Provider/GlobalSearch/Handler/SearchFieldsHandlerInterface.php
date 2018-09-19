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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler;

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\SearchFields;

/**
 *
 * Search Fields Handler interface
 *
 */
interface SearchFieldsHandlerInterface extends HandlerInterface
{
    /**
     * Build search fields
     * @param SearchFields $sf
     * @param string $module Module name
     * @param string $field Field name
     * @param array $defs Field definitions
     * @return array
     */
    public function buildSearchFields(SearchFields $sf, $module, $field, array $defs);

    /**
     * Return a list of supported searchable types
     * @return array
     */
    public function getSupportedTypes();
}
