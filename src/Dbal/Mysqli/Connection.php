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

namespace Sugarcrm\Sugarcrm\Dbal\Mysqli;

use Doctrine\DBAL\Driver\Mysqli\MysqliConnection as BaseConnection;
use Sugarcrm\Sugarcrm\Dbal\SetConnectionTrait;

/**
 * MySQLi connection
 */
class Connection extends BaseConnection
{
    /**
     * @var \Doctrine\DBAL\Driver\Mysqli\MysqliStatement[]
     */
    protected $statements = array();

    use SetConnectionTrait;

    /**
     * @param resource $connection
     */
    public function __construct($connection)
    {
        $this->setConnection($connection);
    }

    /**
     * {@inheritdoc}
     *
     * Reuse existing statements
     */
    public function prepare($prepareString)
    {
        $hash = md5($prepareString);
        if (isset($this->statements[$hash])) {
            $stmt = $this->statements[$hash];
        } else {
            $stmt = $this->statements[$hash] = parent::prepare($prepareString);
        }

        return $stmt;
    }
}
