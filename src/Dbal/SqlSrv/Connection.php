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

use Doctrine\DBAL\Driver\SQLSrv\SQLSrvConnection as BaseConnection;

/**
 * MS SQL Server connection
 */
class Connection extends BaseConnection
{
    /**
     * @var \Doctrine\DBAL\Driver\SQLSrv\SQLSrvStatement[]
     */
    protected $statements = array();

    /**
     * @param resource $connection
     */
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * {@inheritdoc}
     *
     * Reuse existing statements
     */
    public function prepare($sql)
    {
        $hash = md5($sql);
        if (isset($this->statements[$hash])) {
            $stmt = $this->statements[$hash];
        } else {
            $stmt = $this->statements[$hash] = new Statement($this->conn, $sql, $this->lastInsertId);
        }

        return $stmt;
    }
}
