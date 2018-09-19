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

use DBManager;

/**
 * Visibility applied to plain SQL
 */
interface Sql
{
    /**
     * Apply visibility filter to the FROM part of the query
     *
     * @param DBManager $db
     * @param string $query Original query
     * @param string $table Table to apply the visibility rules to
     *
     * @return string Modified query
     */
    public function applyToFrom(DBManager $db, $query, $table);

    /**
     * Apply visibility filter to the WHERE part of the query
     *
     * @param DBManager $db
     * @param string $query Original query
     * @param string $table The table to apply the visibility rules to
     *
     * @return string Modified query
     */
    public function applyToWhere(DBManager $db, $query, $table);
}
