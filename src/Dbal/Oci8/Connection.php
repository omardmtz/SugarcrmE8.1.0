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

namespace Sugarcrm\Sugarcrm\Dbal\Oci8;

use Doctrine\DBAL\Driver\OCI8\OCI8Connection as BaseConnection;

/**
 * Oci8 connection
 */
class Connection extends BaseConnection
{
    /**
     * @param resource $connection
     */
    public function __construct($connection)
    {
        $this->dbh = $connection;
    }
}
