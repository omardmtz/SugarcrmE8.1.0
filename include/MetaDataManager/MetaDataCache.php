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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;

/**
 * Assists in modifying the Metadata in places that the core cannot handle at this time.
 *
 */
class MetaDataCache implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $db;

    /**
     * Name of the cache table used to store metadata cache data
     * @var string
     */
    protected static $cacheTable = "metadata_cache";

    protected static $isCacheEnabled = true;

    public function __construct(DBManager $db)
    {
        $this->db = $db;
    }

    public function get($key)
    {
        return $this->getFromCacheTable($key);
    }

    /**
     * Logs message with stack trace and additional information
     * such as user id, client type, request url.
     * This method should only be used when called in-frequently as it has heavy logging.
     *
     * @param LoggerInterface $logger
     * @param string $message
     */
    protected static function logDetails(LoggerInterface $logger, $message)
    {
        $logger->info($message);
    }

    public function set($key, $data)
    {
        if ($data == null) {
            static::logDetails($this->logger, "Removing key " . $key . " from cache table.. data is null.");
            $this->removeFromCacheTable($key);
        } else {
            $this->storeToCacheTable($key, $data);
        }
    }

    public function getKeys()
    {
        return $this->db
            ->getConnection()
            ->executeQuery('SELECT type FROM ' . static::$cacheTable)
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function reset()
    {
        $this->clearCacheTable();
    }

    /**
     * Checks if metadata cache is operable with current database schema
     *
     * This check should be made during upgrades when the source code has been upgraded by the moment,
     * but database schema hasn't yet.
     *
     * @return bool
     */
    public static function isCacheOperable()
    {
        return DBManagerFactory::getInstance()->tableExists(static::$cacheTable);
    }

    /**
     * Used to cache metadata responses in the database
     *
     * @param String $key key for data stored in the cache table
     *
     * @return mixed|null Value pulled from cache table blob if found.
     */
    protected function getFromCacheTable($key)
    {
        $result = null;
        //During install/setup, this function might get called before the DB is setup.
        if (!empty($this->db)) {
            $cacheResult = null;
            $row = $this->getLastModifiedByType($key);
            if (!empty($row['data'])) {
                $cacheResult = $row['data'];
            }

            if (!empty($cacheResult)) {
                try {
                    $result = unserialize(gzinflate(base64_decode($cacheResult)));
                } catch (Exception $e) {
                    $this->logger->error("Exception when decompressing metadata hash for $key:" . $e->getMessage());
                }
            }
        }

        return $result;
    }

    /**
     * Stores data in the cache table compressed and serialized. Any PHP data is valid.
     *
     * @param String $key key to store data with
     * @param mixed  $data Data to store in the cache table blob
     *
     * @return void
     */
    protected function storeToCacheTable($key, $data)
    {
        if (!empty($this->db)) {
            try {
                $encoded = base64_encode(gzdeflate(serialize($data)));
            } catch (Exception $e) {
                $this->logger->fatal("Exception when compressing metadata for $key:" . $e->getMessage());

                return;
            }

            $id = null;
            $row = $this->getLastModifiedByType($key);
            if (!empty($row['id'])) {
                $id = $row['id'];
            }

            $now = new DateTime('now', new DateTimeZone('UTC'));
            $values = array(
                'id' => $id,
                'type' => $key,
                'data' => $encoded,
                'date_modified' => $now->format(TimeDate::DB_DATETIME_FORMAT),
                'deleted' => 0,
            );

            $fields = array();
            foreach ($this->getFields() as $field) {
                $fields[$field['name']] = $field;
            }
            $this->db->commit();
            if (empty($values['id'])) {
                $values['id'] = create_guid();
                $this->db->insertParams(static::$cacheTable, $fields, $values);
            } else {
                $this->db->updateParams(static::$cacheTable, $fields, $values, array(
                    'id' => $values['id'],
                ));
            }
            $this->db->commit();
        }
    }

    /**
     * return last modified db row by type
     * @param $type
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getLastModifiedByType($type)
    {
        $result = null;
        if (empty($this->db)) {
            return $result;
        }
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery(
            sprintf('SELECT id, data FROM %s WHERE type = ? ORDER BY date_modified DESC', static::$cacheTable),
            array($type)
        );
        $result = $stmt->fetch();
        if ($result) {
            //If we have more than one entry for the same key, we need to remove the duplicate entries.
            while ($row = $stmt->fetch()) {
                $conn->delete(static::$cacheTable, array('id' => $row['id']));
            }
        }

        return $result;
    }

    /**
     * Remove an entry in the cache table.
     *
     * @param String $key
     *
     * @return void
     */
    protected function removeFromCacheTable($key)
    {
        if (self::$isCacheEnabled) {
            $this->db->getConnection()->delete(static::$cacheTable, array('type' => $key));
        }
    }

    /**
     * Clears all entries in the cache table.
     */
    protected static function clearCacheTable()
    {
        if (!self::$isCacheEnabled) {
            return true;
        }

        static::logDetails(LoggerFactory::getLogger('metadata'), "Clearing all entries from metadata cache table.");

        $db = DBManagerFactory::getInstance();
        $db->commit();
        $db->query($db->truncateTableSQL(static::$cacheTable));
        $db->commit();
    }

    public function clearKeysLike($key) {
        $qb = $this->db->getConnection()->createQueryBuilder();
        return $qb->delete(static::$cacheTable)
            ->where($qb->expr()->like('type', $qb->createPositionalParameter($key . '%')))
            ->execute();
    }


    /**
     * Returns array of fields of static::$cacheTable
     *
     * @return array
     */
    protected function getFields()
    {
        global $dictionary;
        include_once 'modules/TableDictionary.php';

        $fields = array();
        if (!empty($dictionary[static::$cacheTable]['fields'])) {
            $fields = $dictionary[static::$cacheTable]['fields'];
        }

        return $fields;
    }
}
