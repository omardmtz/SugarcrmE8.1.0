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
 * Abstract cache class
 * @api
 */
abstract class SugarCacheAbstract
{
    /**
     * @var set to false if you don't want to use the local store, true by default.
     */
    public $useLocalStore = true;

    /**
     * @var int timeout in seconds used for cache item expiration
     */
    protected $_expireTimeout = 300;

    /**
     * @var prefix to use for all cache key entries
     */
    protected $_keyPrefix = 'sugarcrm_';

    /**
     * @var stores locally any cached items so we don't have to hit the external cache as much
     */
    protected $_localStore = array();

    /**
     * @var records the number of get requests made against the cache
     */
    protected $_cacheRequests = 0;

    /**
     * @var records the number of hits made against the cache that have been resolved without hitting the
     * external cache
     */
    protected $_cacheLocalHits = 0;

    /**
     * @var records the number of hits made against the cache that are resolved using the external cache
     */
    protected $_cacheExternalHits = 0;

    /**
     * @var records the number of get requests that aren't in the cache
     */
    protected $_cacheMisses = 0;

    /**
     * @var indicates the priority level for using this cache; the lower number indicates the highest
     * priority ( 1 would be the highest priority, but we should never ship a backend with this number
     * so we don't bump out custom backends. ) Shipping backends use priorities in the range of 900-999.
     */
    protected $_priority = 899;

    /**
     * Constructor
     */
    public function __construct()
    {
        if ( isset($GLOBALS['sugar_config']['cache_expire_timeout']) )
            $this->_expireTimeout = $GLOBALS['sugar_config']['cache_expire_timeout'];
        if ( isset($GLOBALS['sugar_config']['unique_key']) )
            $this->_keyPrefix = $GLOBALS['sugar_config']['unique_key'];
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
    }

    /**
     * PHP's magic __get() method, used here for getting the current value from the cache.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get a value for a key from the cache. Returns NULL in case if the entry is not found
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $this->_cacheRequests++;
        if ( !SugarCache::$isCacheReset && (!$this->useLocalStore || !isset($this->_localStore[$key]))) {
            $this->_localStore[$key] = $this->_getExternal($this->_keyPrefix.$key);
            if ( isset($this->_localStore[$key]) ) {
                $this->_cacheExternalHits++;
            }
            else {
                $this->_cacheMisses++;
            }
        }
        elseif ( isset($this->_localStore[$key]) ) {
            $this->_cacheLocalHits++;
        }

        if ( isset($this->_localStore[$key]) ) {
            return $this->_localStore[$key];
        }

        return null;
    }

    /**
     * PHP's magic __set() method, used here for setting a value for a key in the cache.
     *
     * @param  string $key
     * @return mixed
     */
    public function __set( $key, $value)
    {
        $this->set($key, $value);

    }

    /**
     *  Set a value for a key in the cache, optionally specify a ttl. A ttl value of zero
     * will indicate that a value should never expire.
     *
     * @param $key
     * @param $value
     * @param $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        if ( is_null($value) )
        {
            $value = SugarCache::EXTERNAL_CACHE_NULL_VALUE;
        }


        if ( $this->useLocalStore )
        {
            $this->_localStore[$key] = $value;
        }

        if( $ttl === NULL )
        {
            $this->_setExternal($this->_keyPrefix.$key,$value);
        } else {
            //For BC reasons the setExternal signature will remain the same.
            $previousExpireTimeout = $this->_expireTimeout;
            $this->_expireTimeout = $ttl;
            $this->_setExternal($this->_keyPrefix.$key,$value);
            $this->_expireTimeout = $previousExpireTimeout;
        }
    }
    /**
     * PHP's magic __isset() method, used here for checking for a key in the cache.
     *
     * @param  string $key
     * @return mixed
     */
    public function __isset($key)
    {
        return !is_null($this->__get($key));
    }

    /**
     * PHP's magic __unset() method, used here for clearing a key in the cache.
     *
     * @param  string $key
     * @return mixed
     */
    public function __unset($key)
    {
        unset($this->_localStore[$key]);
        $this->_clearExternal($this->_keyPrefix.$key);
    }

    /**
     * Reset the cache for this request
     */
    public function reset()
    {
        $this->_localStore = array();
        SugarCache::$isCacheReset = true;
    }

    /**
     * Reset the cache fully
     */
    public function resetFull()
    {
        $this->reset();
        $this->_resetExternal();
    }

    /**
     * Flush the contents of the cache
     */
    public function flush()
    {
        $this->_localStore = array();
        $this->_resetExternal();
    }

    /**
     * Returns the number of cache hits made
     *
     * @return array assocative array with each key have the value
     */
    public function getCacheStats()
    {
        return array(
            'requests'     => $this->_cacheRequests,
            'externalHits' => $this->_cacheExternalHits,
            'localHits'    => $this->_cacheLocalHits,
            'misses'       => $this->_cacheMisses,
            );
    }

    /**
     * Returns what backend is used for caching, uses normalized class name for lookup
     *
     * @return string
     */
    public function __toString()
    {
        return strtolower(str_replace('SugarCache','',get_class($this)));
    }

    /**
     * Hook for the child implementations of the individual backends to provide thier own logic for
     * setting a value from cache
     *
     * @param string $key
     * @param mixed  $value
     */
    abstract protected function _setExternal($key,$value);

    /**
     * Hook for the child implementations of the individual backends to provide thier own logic for
     * getting a value from cache
     *
     * @param  string $key
     * @return mixed  $value, returns null if the key is not in the cache
     */
    abstract protected function _getExternal($key);

    /**
     * Hook for the child implementations of the individual backends to provide thier own logic for
     * clearing a value out of thier cache
     *
     * @param string $key
     */
    abstract protected function _clearExternal($key);

    /**
     * Hook for the child implementations of the individual backends to provide thier own logic for
     * clearing thier cache out fully
     */
    abstract protected function _resetExternal();

    /**
     * Hook for testing if the backend should be used or not. Typically we'll extend this for backend specific
     * checks as well.
     *
     * @return boolean true if we can use the backend, false if not
     */
    public function useBackend()
    {
        if ( !empty($GLOBALS['sugar_config']['external_cache_disabled'])
                && $GLOBALS['sugar_config']['external_cache_disabled'] == true ) {
            return false;
        }

        if (defined('SUGARCRM_IS_INSTALLING')) {
            return false;
        }

        if ( isset($GLOBALS['sugar_config']['external_cache_force_backend'])
                && ( $GLOBALS['sugar_config']['external_cache_force_backend'] != (string) $this ) ) {
            return false;
        }

        return true;
    }

    /**
     * Returns the priority level for this backend
     *
     * @see self::$_priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }
}
