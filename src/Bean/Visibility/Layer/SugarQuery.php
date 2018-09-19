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

namespace Sugarcrm\Sugarcrm\Bean\Visibility\Layer;

use SugarQuery as Query;
use SugarQueryException;

/**
 * Visibility applied to SugarQuery
 */
interface SugarQuery
{
    /**
     * Apply visibility filter to SugarQuery
     *
     * @param Query $query
     * @param string $table The table to apply the visibility rules to
     *
     * @return void
     * @throws SugarQueryException
     */
    public function applyToQuery(Query $query, $table);
}
