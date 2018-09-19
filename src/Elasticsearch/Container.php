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

namespace Sugarcrm\Sugarcrm\Elasticsearch;

use Sugarcrm\Sugarcrm\SearchEngine\MetaDataHelper;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Client;
use Sugarcrm\Sugarcrm\Elasticsearch\Index\IndexPool;
use Sugarcrm\Sugarcrm\Elasticsearch\Index\IndexManager;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\MappingManager;
use Sugarcrm\Sugarcrm\Elasticsearch\Indexer\Indexer;
use Sugarcrm\Sugarcrm\Elasticsearch\Queue\QueueManager;
use Sugarcrm\Sugarcrm\Elasticsearch\Exception\ContainerException;
use SugarAutoLoader;
use SugarConfig;
use SugarArray;
use DBManagerFactory;

/**
 *
 * Elasticsearch service container
 *
 * List of properties exposed through `$this->__get()`
 *
 * @property-read Logger logger
 * @property-read MetaDataHelper metaDataHelper
 * @property-read QueueManager queueManager
 * @property-read Client client
 * @property-read IndexPool indexPool
 * @property-read IndexManager indexManager
 * @property-read MappingManager mappingManager
 * @property-read Indexer indexer
 *
 */
class Container
{
    /**
     * @var Container
     */
    protected static $instance;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var MetaDataHelper
     */
    protected $metaDataHelper;

    /**
     * @var QueueManager
     */
    protected $queueManager;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IndexPool
     */
    protected $indexPool;

    /**
     * @var IndexManager
     */
    protected $indexManager;

    /**
     * @var MappingManager
     */
    protected $mappingManager;

    /**
     * @var Indexer
     */
    protected $indexer;

    /**
     * Registered providers
     * @var array
     */
    protected $providers = array();

    /**
     * Configuration parameters
     * @var array
     */
    protected $config = array(
        'engine' => array(),
        'global' => array(),
    );

    /**
     * Provider initialization callbacks
     * @var array
     */
    protected $providerInit = array();

    /**
     * Resource initialization callbacks
     * @var array
     */
    protected $resourceInit = array();

    /**
     * To instantiate this container self::create() should be used instead
     * of using this ctor directly unless you know what your are doing. This
     * ctor is not made private for testing purposes and edge cases where
     * its desirable to be able to instantiate this container directly.
     */
    public function __construct()
    {
        $this->registerProviders();
    }

    /**
     * Create new container object. Use self::getInstance unless you know
     * what you are doing.
     *
     * @return Container
     */
    public static function create()
    {
        /*
         * Until system wide bundle support is possible in the framework we
         * rely on the ability of using the /custom framework to customize
         * this service container. See `self::getInstance`.
         */
        $class = SugarAutoLoader::customClass('Sugarcrm\\Sugarcrm\\Elasticsearch\\Container');
        return new $class();
    }

    /**
     * Factory getting the service container instance.
     *
     * Until we have bundle support in the framework, this service container
     * can be customized by deploying the following class:
     *
     *      \Sugarcrm\Sugarcrm\custom\src\Elasticsearch\Container
     *
     * The main purpose of extending this service container is to be able to
     * register additional custom providers on the stack. Overriding the
     * initialization methods is also possible but care should be taken to
     * understand the implication when doing so as those methods can change.
     *
     * @return Container
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = self::create();
        }
        return self::$instance;
    }

    /**
     * Overload for container resources
     * @param string $resource
     */
    public function __get($resource)
    {
        // Return the resource if already initialized
        if (!empty($this->$resource)) {
            return $this->$resource;
        }

        // Lazy load resources when accessed
        $init = 'init' . ucfirst($resource);
        if (property_exists($this, $resource) && method_exists($this, $init)) {

            // instantiate resource
            $this->$init();

            // apply resource initialization callbacks
            if (isset($this->resourceInit[$resource])) {
                foreach ($this->resourceInit[$resource] as $callback) {
                    $callback($this->$resource);
                }
            }

            // return resource
            return $this->$resource;
        }
    }

    /**
     * Add provider initialization callback
     * @param string $identifier Provider identifier
     * @param callable $callback
     */
    public function addProviderInit($identifier, $callback)
    {
        $this->providerInit[$identifier][] = $callback;
    }

    /**
     * Add resource initialization callback
     * @param string $resource
     * @param callable $callback
     */
    public function addResourceInit($resource, $callback)
    {
        $this->resourceInit[$resource][] = $callback;
    }

    /**
     * Initialize Logger
     */
    protected function initLogger()
    {
        $this->logger = new Logger(\LoggerManager::getLogger());
        $xhprof = SugarConfig::getInstance()->get('xhprof_config');
        if (!empty($xhprof)) {
            $this->logger->setXhProf(\SugarXHprof::getInstance());
        }
    }

    /**
     * Initialize MetaDataHelper
     */
    protected function initMetaDataHelper()
    {
        $this->initLogger();
        $this->metaDataHelper = new MetaDataHelper($this->logger);
    }

    /**
     * Initialize QueueManager
     */
    protected function initQueueManager()
    {
        $this->queueManager = new QueueManager($this->getConfig('global'), $this);
    }

    /**
     * Initialize Client
     */
    protected function initClient()
    {
        $this->initLogger();
        $this->client = new Client($this->getConfig('engine'), $this->logger);
    }

    /**
     * Initialize IndexPool
     */
    protected function initIndexPool()
    {
        $prefix = SugarConfig::getInstance()->get('unique_key', 'sugarcrm');
        $config = SugarArray::staticGet($this->getConfig('engine'), 'index_strategy', array());
        $this->indexPool = new IndexPool($prefix, $config, $this);
    }

    /**
     * Initialize IndexManager
     */
    protected function initIndexManager()
    {
        $config = SugarArray::staticGet($this->getConfig('engine'), 'index_settings', array());
        $this->indexManager = new IndexManager($config, $this);

        // reindex refresh interval from config
        $interval = SugarArray::staticGet($this->getConfig('engine'), 'reindex_refresh_interval');
        if (null !== $interval) {
            $this->indexManager->setReindexRefreshInterval($interval);
        }

        // reindex zero replica usage from config
        $zeroReplica = SugarArray::staticGet($this->getConfig('engine'), 'reindex_zero_replica');
        if ($zeroReplica) {
            $this->indexManager->setReindexZeroReplica($zeroReplica);
        }
    }

    /**
     * Initialize MappingManager
     */
    protected function initMappingManager()
    {
        $this->mappingManager = new MappingManager();
    }

    /**
     * Initialize Indexer
     */
    protected function initIndexer()
    {
        $this->indexer = new Indexer($this->getConfig('global'), $this, DBManagerFactory::getInstance());
    }

    /**
     * Set configuration parameters
     * @param array $config
     */
    public function setConfig($key, array $config)
    {
        $this->config[$key] = $config;
    }

    /**
     * Get configuration parameters
     * @param string $key Configuration key
     * @param array $default Default array if not found
     * @return array
     */
    public function getConfig($key, array $default = array())
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return $default;
    }

    /**
     * Stock providers, can be overriden in custom class implementation.
     */
    public function registerProviders()
    {
        $this->registerProvider(
            'GlobalSearch',
            'Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\GlobalSearch'
        );

        $this->registerProvider(
            'Visibility',
            'Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Visibility'
        );
    }

    /**
     * Register a new provider on the stack
     * @param string $identifier Provider identifier
     * @param string $class Classname
     */
    public function registerProvider($identifier, $class)
    {
        $this->providers[$identifier] = $class;
    }

    /**
     * Unregister a provider
     * @param string $identifier Provider identifier
     */
    public function unregisterProvider($identifier)
    {
        if (isset($this->providers[$identifier])) {
            unset($this->providers[$identifier]);
        }
    }

    /**
     * Return list of registered providers
     * @return array
     */
    public function getRegisteredProviders()
    {
        return array_keys($this->providers);
    }

    /**
     * Check if given provider is available
     * @param string $identifier Provider identifier
     * @return boolean
     */
    public function isProviderAvailable($identifier)
    {
        return isset($this->providers[$identifier]);
    }

    /**
     * Lazy load provider object
     * @param string $identifier Provider identifier
     * @throws ContainerException
     * @return ProviderInterface
     */
    public function getProvider($identifier)
    {
        if (!isset($this->providers[$identifier])) {
            throw new ContainerException("Unknown Elasticsearch provider '{$identifier}'");
        }

        if (!is_object($this->providers[$identifier])) {
            $className = $this->providers[$identifier];
            $this->providers[$identifier] = $provider = new $className();

            $provider->setIdentifier($identifier);
            $provider->setUser($this->getCurrentUser());

            if ($provider instanceof ContainerAwareInterface) {
                $provider->setContainer($this);
            }

            // execute initialization callbacks
            if (isset($this->providerInit[$identifier])) {
                foreach ($this->providerInit[$identifier] as $callback) {
                    $callback($provider);
                }
            }
        }

        return $this->providers[$identifier];
    }

    /**
     * Get current user
     * @throws ContainerException
     * @return \User
     */
    protected function getCurrentUser()
    {
        if (empty($GLOBALS['current_user'])) {
            throw new ContainerException('Current user not available');
        }
        return $GLOBALS['current_user'];
    }
}
