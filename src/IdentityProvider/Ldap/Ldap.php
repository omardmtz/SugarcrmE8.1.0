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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Ldap;

use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;

use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Exception\DriverNotFoundException;
use Symfony\Component\Ldap\LdapInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Extended copy of Symfony\Component\Ldap\Ldap with logging capabilities
 */
class Ldap implements LdapInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $adapter;

    private static $adapterMap = array(
        'ext_ldap' => 'Symfony\Component\Ldap\Adapter\ExtLdap\Adapter',
    );

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->setLogger(LoggerFactory::getLogger('authentication'));
    }

    /**
     * {@inheritdoc}
     */
    public function bind($dn = null, $password = null)
    {
        $this->logger->debug(sprintf('LDAP: binding with DN=%s', $dn));

        $this->adapter->getConnection()->bind($dn, $password);

        $this->logger->debug('LDAP: bound successfully');
    }

    /**
     * {@inheritdoc}
     */
    public function query($dn, $query, array $options = array())
    {
        $this->logger->debug(sprintf('LDAP: querying with DN=%s and query=%s', $dn, $query));

        return $this->adapter->createQuery($dn, $query, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager()
    {
        return $this->adapter->getEntryManager();
    }

    /**
     * {@inheritdoc}
     */
    public function escape($subject, $ignore = '', $flags = 0)
    {
        return $this->adapter->escape($subject, $ignore, $flags);
    }

    /**
     * Creates a new Ldap instance.
     *
     * @param string $adapter The adapter name
     * @param array  $config  The adapter's configuration
     *
     * @return static
     */
    public static function create($adapter, array $config = array())
    {
        if (!isset(self::$adapterMap[$adapter])) {
            throw new DriverNotFoundException(sprintf(
                'Adapter "%s" not found. You should use one of: %s',
                $adapter,
                implode(', ', self::$adapterMap)
            ));
        }

        $class = self::$adapterMap[$adapter];

        return new self(new $class($config));
    }
}
