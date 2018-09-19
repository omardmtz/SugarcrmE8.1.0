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

namespace Sugarcrm\Sugarcrm\Dbal\SqlSrv;

use Doctrine\DBAL\Driver\SQLSrv\Driver as BaseDriver;
use Doctrine\DBAL\Platforms\SQLServer2008Platform as Base2008Platform;
use Doctrine\DBAL\Platforms\SQLServer2012Platform as Base2012Platform;
use Sugarcrm\Sugarcrm\Dbal\Platforms\SqlSrv2008Platform;
use Sugarcrm\Sugarcrm\Dbal\Platforms\SqlSrv2012Platform;

/**
 * MS SQL Server driver
 */
class Driver extends BaseDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        return new Connection($params['connection']);
    }

    /**
     * {@inheritdoc}
     *
     * Replaces SQL Server platform implementation with a custom version
     */
    public function createDatabasePlatformForVersion($version)
    {
        $platform = parent::createDatabasePlatformForVersion($version);

        if ($platform instanceof Base2012Platform) {
            return new SqlSrv2012Platform();
        }

        if ($platform instanceof Base2008Platform) {
            return new SqlSrv2008Platform();
        }

        return $platform;
    }
}
