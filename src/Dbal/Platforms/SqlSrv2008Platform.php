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

use Doctrine\DBAL\Platforms\SQLServer2008Platform as BasePlatform;

/**
 * Temporary implementation of SQL Server 2008 platform for fixing Doctrine DBAL issues
 */
class SqlSrv2008Platform extends BasePlatform
{
    use SqlSrvPlatformTrait;

    /**
     * {@inheritDoc}
     *
     * Formats original query before parsing
     *
     * @link https://github.com/doctrine/dbal/issues/2372
     */
    protected function doModifyLimitQuery($query, $limit, $offset = null)
    {
        $query = $this->formatQuery($query);
        return parent::doModifyLimitQuery($query, $limit, $offset);
    }
}
