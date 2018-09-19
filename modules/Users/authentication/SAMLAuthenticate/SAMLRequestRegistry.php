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

/**
 * SAML authentication tracker
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMSAMLAuthenticate
 */
class SAMLRequestRegistry
{
    /**
     * Persistent data storage
     *
     * @var SugarCacheAbstract
     */
    protected $storage;

    /**
     * @var int Request TTL
     */
    protected $ttl = 300;

    /**
     * Constructor
     *
     * @param SugarCacheAbstract $storage Persistant storage for registered requests
     * @param int $ttl Request TTL
     */
    public function __construct(SugarCacheAbstract $storage = null, $ttl = null)
    {
        $this->storage = $storage ?: SugarCache::instance();
        $this->ttl = $ttl ?: SugarConfig::getInstance()->get('saml.request_ttl', $this->ttl);
    }

    /**
     * Registers request
     *
     * @param string $id
     */
    public function registerRequest($id)
    {
        $this->storage->set($id, true, $this->ttl);
    }

    /**
     * Checks whether request is registered
     *
     * @param string $id
     * @return bool
     */
    public function isRequestRegistered($id)
    {
        return isset($this->storage->$id);
    }

    /**
     * Unregisters request
     *
     * @param string $id
     */
    public function unregisterRequest($id)
    {
        unset($this->storage->$id);
    }
}
