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

namespace Sugarcrm\Sugarcrm\Bean\Visibility\Strategy;

use DBManager;
use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy;
use SugarQuery;

/**
 * Denies access to all data
 */
final class DenyAll implements Strategy
{
    public function applyToQuery(SugarQuery $query, $table)
    {
        $query->where()->addRaw('1 != 1');
    }

    public function applyToFrom(DBManager $db, $query, $table)
    {
        return $query;
    }

    public function applyToWhere(DBManager $db, $query, $table)
    {
        $conditions = [];

        if ($query) {
            $conditions[] = $query;
        }

        $conditions[] = '1 != 1';

        return implode(' AND ', $conditions);
    }
}
