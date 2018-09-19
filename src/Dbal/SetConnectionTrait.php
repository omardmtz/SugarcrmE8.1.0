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

namespace Sugarcrm\Sugarcrm\Dbal;

/**
 * Contains shared implementation of setting connection resource on the connection object
 */
trait SetConnectionTrait
{
    /**
     * @var resource|\mysqli
     */
    protected $conn;

    /**
     * Sets connection on the object
     *
     * @param resource|\mysqli $connection Connection resource or object
     */
    protected function setConnection($connection)
    {
        $re = new \ReflectionProperty(get_parent_class($this), '_conn');
        $re->setAccessible(true);
        $re->setValue($this, $connection);

        $this->conn = $connection;
    }
}
