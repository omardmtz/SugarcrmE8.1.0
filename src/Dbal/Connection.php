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

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Portability\Connection as BaseConnection;
use Doctrine\DBAL\DBALException;
use Sugarcrm\Sugarcrm\Dbal\Query\QueryBuilder;

/**
 * {@inheritDoc}
 */
class Connection extends BaseConnection
{
    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $result = parent::connect();

        if ($result) {
            $re = new \ReflectionProperty(get_parent_class($this), 'portability');
            $re->setAccessible(true);
            $portability = $re->getValue($this);

            // unset the unneeded bits from the resulting value as the DBAL forcedly sets them
            // @link https://github.com/doctrine/dbal/issues/2644
            $portability &= ~(self::PORTABILITY_RTRIM | self::PORTABILITY_EMPTY_TO_NULL);

            $re->setValue($this, $portability);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @return \Sugarcrm\Sugarcrm\Dbal\Query\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this);
    }

    /**
     * {@inheritDoc}
     */
    public function executeQuery($query, array $params = array(), $types = array(), QueryCacheProfile $qcp = null)
    {
        try {
            return parent::executeQuery($query, $params, $types, $qcp);
        } catch (DBALException $e) {
            $this->logException($e);
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function executeUpdate($query, array $params = array(), array $types = array())
    {
        try {
            return parent::executeUpdate($query, $params, $types);
        } catch (DBALException $e) {
            $this->logException($e);
            throw $e;
        }
    }

    /**
     * Logs DBAL exception
     *
     * @param DBALException $e Exception
     */
    protected function logException(DBALException $e)
    {
        $GLOBALS['log']->fatal($e->getMessage());
    }
}
