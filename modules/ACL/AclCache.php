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
 * ACL data cache
 */
class AclCache
{
    const HASH_KEY = 'ACL';

    /** @var SugarCacheAbstract */
    protected $cache;

    /**
     * Local copy of cached value hashes
     *
     * @var array|null
     */
    protected $hashes;

    /**
     * Local copy of cached value hashes
     *
     * @var static
     */
    protected static $instance;

    /**
     * Constructor.
     *
     * @param SugarCacheAbstract $cache
     */
    protected function __construct(SugarCacheAbstract $cache = null)
    {
        if (!$cache) {
            $cache = SugarCache::instance();
        }

        $this->cache = $cache;
    }

    /**
     * Returns single instance of the class
     *
     * @return static
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Resets single instance of the class
     *
     * @return static
     */
    public static function resetInstance()
    {
        self::$instance = null;
    }

    /**
     * Retrieve a value for a key from the cache. Returns NULL in case if the entry is not found.
     *
     * @param string $userId User ID
     * @param string $key Entry key
     *
     * @return mixed
     */
    public function retrieve($userId, $key)
    {
        if (!$userId) {
            return null;
        }

        if ($this->hashes === null) {
            $this->hashes = $this->cache->get(self::HASH_KEY);
        }

        if (isset($this->hashes[$userId][$key])) {
            $hash = $this->hashes[$userId][$key];
            $value = $this->cache->get($hash);
            return $value;
        }

        return null;
    }

    /**
     * Set a value for a key in the cache.
     *
     * @param string $userId User ID
     * @param string $key Entry key
     * @param mixed $value Value
     */
    public function store($userId, $key, $value)
    {
        if (!$userId) {
            return;
        }

        $hash = md5(serialize($value));
        if (!isset($this->hashes[$userId][$key]) || $this->hashes[$userId][$key] !== $hash) {
            $this->hashes[$userId][$key] = $hash;
            $this->cache->set(self::HASH_KEY, $this->hashes, 0);
        }

        $this->cache->set($hash, $value, session_cache_expire() * 60);
    }

    /**
     * Clear cache.
     * @param string $userId
     * @param string $key
     */
    public function clear($userId = null, $key = null)
    {
        // clear cache for a single user
        if ($userId) {
            if ($this->hashes === null) {
                $this->hashes = $this->cache->get(self::HASH_KEY);
            }
            if (isset($this->hashes[$userId])) {
                if ($key) {
                    if (isset($this->hashes[$userId][$key])) {
                        unset($this->hashes[$userId][$key]);
                        $this->cache->set(self::HASH_KEY, $this->hashes, 0);
                    }
                    return;
                }
                unset($this->hashes[$userId]);
                $this->cache->set(self::HASH_KEY, $this->hashes, 0);
            }
            return;
        }
        // clear cache for all users
        $this->hashes = null;
        unset($this->cache->{self::HASH_KEY});
    }
}
