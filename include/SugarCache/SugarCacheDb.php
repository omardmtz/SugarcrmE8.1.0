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


use Psr\Log\LoggerInterface;
use Sugarcrm\Sugarcrm\Logger\LoggerTransition;
use Psr\Log\LogLevel;

/**
 * SQL SugarCache backend - currently only MySQL backends are supported
 * awaiting full optimized upsert capability on the other DB backends.
 */
class SugarCacheDb extends SugarCacheAbstract
{
    /**
     * @var DBManager
     */
    protected $db;

    /**
     * @var TimeDate
     */
    protected $timeDate;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SugarConfig
     */
    protected $sugarConfig;

    /**
     * {@inheritdoc}
     */
    protected $_priority = 995;

    /**
     * Garbage collection probability to remove expired keys
     * @var float
     */
    protected $gcProbability = 0.0001;

    /**
     * Threshold in milliseconds for garbage collection when
     * triggering a slow gc notice
     * @var integer
     */
    protected $gcThreshold = 200;

    /**
     * {@inheritdoc}
     */
    public function useBackend()
    {
        // parent check
        if (!parent::useBackend()) {
            return false;
        }

        // explicit disable check
        if ($this->sugarConfig->get('external_cache_disabled_db', false)) {
            return false;
        }

        if (!$this->db->tableExists('key_value_cache')) {
            return false;
        }

        // limit usage to mysql based databases only
        if ($this->db->dbType != 'mysql') {
            return false;
        }

        // garbage collect expired entries
        $this->gc();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->_keyPrefix = '';

        $this->db = DBManagerFactory::getInstance();
        $this->timeDate = TimeDate::getInstance();
        $this->logger = new LoggerTransition(LoggerManager::getLogger());
        $this->sugarConfig = SugarConfig::getInstance();

        // garbage collection settings
        $this->gcProbability = $this->sugarConfig->get('external_cache_db_gc_probability', $this->gcProbability);
        $this->gcThreshold = $this->sugarConfig->get('external_cache_db_gc_threshold', $this->gcThreshold);
    }

    /**
     * {@inheritdoc}
     */
    protected function _setExternal($key, $value)
    {
        $hashedKey = $this->hashKeyName($key);
        $this->logger->debug("SugarCacheDb writing key '$key' using hash '$hashedKey'");

        $value = $this->encode($value);

        if ($this->_expireTimeout > 0) {
            $expires = $this->getDateTime('+' . $this->_expireTimeout . ' seconds');
        } else {
            $expires = null;
        }

        $placeholder = $this->db->convert('?', 'datetime');
        $sql = 'INSERT INTO key_value_cache (id, value, date_expires) VALUES (?, ?, ' . $placeholder . ') '
            . 'ON DUPLICATE KEY UPDATE value = ?, date_expires = ' . $placeholder;
        $this->query($sql, array(
            $hashedKey,
            $value,
            $expires,
            $value,
            $expires
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function _getExternal($key)
    {
        $sql = 'SELECT value FROM key_value_cache WHERE id = ? AND (date_expires IS NULL'
            . ' OR date_expires > ' . $this->db->convert('?', 'datetime') . ')';
        $stmt = $this->query($sql, array(
            $this->hashKeyName($key),
            $this->getDateTime(),
        ));

        $value = $stmt->fetchColumn();
        if ($value) {
            return $this->decode($value, $key);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function _clearExternal($key)
    {
        $sql = 'DELETE FROM key_value_cache WHERE id = ?';
        $this->query($sql, array(
            $this->hashKeyName($key),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function _resetExternal()
    {
        $this->query($this->db->truncateTableSQL('key_value_cache'));
    }

    /**
     * Garbage collect expired entries
     */
    protected function gc()
    {
        if (!$this->gcNeeded()) {
            return;
        }

        $start = microtime(true);
        $this->query("DELETE FROM key_value_cache WHERE date_expires <= " . $this->db->convert('?', 'datetime'), array(
            $this->getDateTime(),
        ));
        $runtime = round((microtime(true) - $start) * 1000);

        $msg = "SugarCacheDb running garbage collection - took $runtime msecs";

        if ($runtime >= $this->gcThreshold) {
            $msg .= " [SLOW]";
            $level = LogLevel::ALERT;
        } else {
            $level = LogLevel::INFO;
        }

        $this->logger->log($level, $msg);
    }

    /**
     * Determine if we need to garbage collect expired cache entries
     * @return boolean
     */
    protected function gcNeeded()
    {
        return (mt_rand(1, ceil(1 / $this->gcProbability)) === 1);
    }

    /**
     * Create encode string
     *
     * @param string $value
     * @return string
     */
    protected function encode($value)
    {
        return base64_encode(serialize($value));
    }

    /**
     * Decode stored cache value
     *
     * @param mixed $data
     * @param string $key
     * @return mixed|null
     */
    protected function decode($data, $key)
    {
        $decodeData = @base64_decode($data);
        if ($decodeData === false) {
            $this->logger->error("SugarCacheDb base64 decode failure for key '$key'");
            return null;
        }

        $value = @unserialize($decodeData);
        if ($value === false) {
            $this->logger->error("SugarCacheDb unserialize failure for key '$key'");
            return null;
        }

        return $value;
    }

    /**
     * Create md5 hash
     *
     * @param  string $key_name
     * @return string
     */
    protected function hashKeyName($key_name)
    {
        return md5($key_name);
    }

    /**
     * Returns datetime with the given offset in database format
     *
     * @param string|null $offset
     * @return string
     */
    protected function getDateTime($offset = null)
    {
        $dateTime = $this->timeDate->getNow();
        if ($offset) {
            $dateTime->modify($offset);
        }

        return $dateTime->asDb();
    }

    /**
     * Calls the normal DB query but first sets the DB to ignore the next query.
     * @param string $sql
     * @param array $params
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    protected function query($sql, array $params = array())
    {
        $this->db->increaseQueryLimit();

        // TODO: make sure Doctrine connection respects query limit
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
