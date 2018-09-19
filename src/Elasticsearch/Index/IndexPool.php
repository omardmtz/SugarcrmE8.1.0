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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Index;

use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\MappingCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Index;
use Sugarcrm\Sugarcrm\Elasticsearch\Exception\IndexPoolStrategyException;
use Sugarcrm\Sugarcrm\Elasticsearch\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Client;

/**
 *
 * Wrapper class to manage different indices. Every module can have one or
 * more indices assigned. The logic resides in the strategy classes. This
 * class manages the link between modules and indices. All index objects
 * needed have to be requested through this class.
 *
 */
class IndexPool
{
    const DEFAULT_STRATEGY = 'static';

    /**
     * @var string Prefix for every index
     */
    protected $prefix;

    /**
     * @var array Configuration parameters
     */
    protected $config;

    /**
     * @var \Sugarcrm\Sugarcrm\Elasticsearch\Container
     */
    protected $container;

    /**
     * Loaded strategies
     * @var \Sugarcrm\Sugarcrm\Elasticsearch\Index\Strategy\StrategyInterface[]
     */
    protected $loaded = array();

    /**
     * Registered strategies
     * @var array
     */
    protected $strategies = array();

    /**
     * @param string $prefix Index prefix
     * @param array $config
     */
    public function __construct($prefix, array $config, Container $container)
    {
        $this->prefix = $prefix;
        $this->config = $config;
        $this->container = $container;
        $this->registerStrategies();
    }

    /**
     * Build index collection for given mapping
     * @param MappingCollection $mappings
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Index\IndexCollection
     */
    public function buildIndexCollection(MappingCollection $mappings)
    {
        $collection = new IndexCollection($this->container);

        foreach ($mappings as $mapping) {
            /* @var Mapping $mapping */
            $module = $mapping->getModule();
            $indices = $this->getStrategy($module)->getManagedIndices($module);
            $collection->addType($indices, $module);
        }

        return $collection;
    }

    /**
     * Normalize index name and add prefix. The normalized named is only
     * referenced in the underlaying \Elastica\Index objects. Index name
     * access should always be resolved against the non-prefixed format.
     *
     * @param string $name Index name
     * @return string
     */
    public function normalizeIndexName($name)
    {
        if (!empty($this->prefix)) {
            $name = $this->prefix . '_' . $name;
        }

        // only lowercase index names are allowed
        return strtolower($name);
    }

    /**
     * Get strategy object for given module
     * @param string $module Module name
     * @throws \Sugarcrm\Sugarcrm\Elasticsearch\Exception\IndexPoolStrategyException
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Index\Strategy\StrategyInterface
     */
    public function getStrategy($module)
    {
        // take strategy identifier from config or use default if not available
        if (!empty($this->config[$module]) && !empty($this->config[$module]['strategy'])) {
            $id = $this->config[$module]['strategy'];
        } else {
            $id = self::DEFAULT_STRATEGY;
        }

        if (!isset($this->loaded[$id])) {

            if (!isset($this->strategies[$id])) {
                throw new IndexPoolStrategyException("Unknown strategy identifier '$id'");
            }

            $className = $this->strategies[$id];
            if (!class_exists($className)) {
                throw new IndexPoolStrategyException("Invalid strategy '$id' for module '$module'");
            }

            // create strategy object and pass index_strategy config
            $this->loaded[$id] = $strategy = new $className();
            $strategy->setConfig($this->config);
            $strategy->setIdentifier($id);
        }

        return $this->loaded[$id];
    }

    /**
     * Get list of available read indices for given modules
     * @param array $modules
     * @param array $context
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Index\IndexCollection
     */
    public function getReadIndices(array $modules, array $context = array())
    {
        $collection = new IndexCollection($this->container);
        foreach ($modules as $module) {
            $indices = $this->getStrategy($module)->getReadIndices($module, $context);
            $collection->addIndices($indices);
        }
        return $collection;
    }

    /**
     * Get list of managed indices for given modules
     * @param array $modules
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Index\IndexCollection
     */
    public function getManagedIndices(array $modules)
    {
        $collection = new IndexCollection($this->container);
        foreach ($modules as $module) {
            $indices = $this->getStrategy($module)->getManagedIndices($module);
            $collection->addIndices($indices);
        }
        return $collection;
    }

    /**
     * Get write index for given module. There can only be one write index at
     * any given time for a module.
     * @param string $module
     * @param array $context
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Index
     */
    public function getWriteIndex($module, array $context = array())
    {
        $index = $this->getStrategy($module)->getWriteIndex($module, $context);
        $normalized = $this->normalizeIndexName($index);
        return $this->newIndexObject($normalized);
    }

    /**
     * Add strategy to registry
     * @param string $identifier
     * @param string $className Class implementing StrategyInterface
     */
    public function addStrategy($identifier, $className)
    {
        $this->strategies[$identifier] = $className;
    }

    /**
     * Register available strategies
     */
    protected function registerStrategies()
    {
        $nsPrefix = '\Sugarcrm\Sugarcrm\Elasticsearch\Index\Strategy';
        $this->addStrategy('static', $nsPrefix . '\StaticStrategy');
    }

    /**
     * Get index object
     * @param string $indexName Index name
     * @param Client $client Optional client
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Index
     */
    protected function newIndexObject($name, Client $client = null)
    {
        $client = $client ?: $this->container->client;
        return new Index($client, $name);
    }
}
