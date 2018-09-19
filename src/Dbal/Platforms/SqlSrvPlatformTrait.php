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

namespace Sugarcrm\Sugarcrm\Dbal\Platforms;

/**
 * @link https://github.com/doctrine/dbal/issues/2372
 */
trait SqlSrvPlatformTrait
{
    /**
     * Replaces every sequence of whitespaces with a single space
     *
     * @param string $query
     * @return string
     */
    protected function formatQuery($query)
    {
        return preg_replace('/\s+/', ' ', $query);
    }
}
