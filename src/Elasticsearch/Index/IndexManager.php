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

use Sugarcrm\Sugarcrm\Elasticsearch\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\MappingHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\ProviderCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Index;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\MappingCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Exception\IndexManagerException;
use Elastica\Response;

/**
 *
 * Index manager is responsible for the index settings and mappings. The
 * information for both is registered through the MappingManager and
 * AnalyzerManager for every enabled Provider. The index manager uses
 * the Index Pool to push changes to the Elasticsearch backend.
 *
 */
class IndexManager
{
    /**
     * Key for `$this->config` containing the default configuration settings
     * which apply to all indices.
     */
    const DEFAULT_INDEX_SETTINGS_KEY = 'default';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array Configuration index_settings
     */
    protected $config = array();

    /**
     * @var array Default index settings
     */
    protected $defaultSettings = array(
        // Ignore malformed fields when index a document
        'index.mapping.ignore_malformed' => true,
        // Coerce numeric values
        'index.mapping.coerce' => true,
        // Refresh interval
        'index.refresh_interval' => '1s',
        // Number of replicas
        'index.number_of_replicas' => '1',
    );

    /**
     * @var array Default type mapping
     */
    protected $defaultMapping = array(
        // Avoid wasting space on _all field which we don't need
        '_all' => array('enabled' => false),
        // Ignore new fields for which no mapping exists
        'dynamic' => false,
    );

    /**
     * Value used as refresh_interval when performing a reindex. When no more
     * records are in the queue, the refresh_interval will be restored as per
     * the index settings.
     *
     * This can be configured by using the following parameter:
     * `$sugar_config['full_text_engine']['Elastic']['reindex_refresh_interval']`
     *
     * @var int|string
     */
    protected $reindexRefreshInterval = '1s';

    /**
     * During indexing documents are sent to each replica node on which the
     * indexing process is repeated. When this option is enabled, the
     * replicas for the involved indices will be set to zero. When all
     * records are processed from fts_queue, the replicas are enabled again
     * and the recovery process will start syncing the data which is much
     * more performant than having multiple replicas active.
     *
     * Use `$sugar_config['full_text_engine']['Elastic']['reindex_zero_replica'] = true`
     *
     * When using this functionality it is highly encourage to configure
     * the replica settings using index_settings as we cannot fall back
     * the any node configuration for this. If no index_setting config is
     * supplied every index will get configured using one replica by default.
     *
     * Also note that when using this functionality (which is disabled by
     * default) the cluster state can be affected by it.
     *
     * @var boolean
     */
    protected $reindexZeroReplica = false;

    /**
     * Ctor
     */
    public function __construct(array $config, Container $container)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Set reindex refresh interval
     * @param int|string $interval
     */
    public function setReindexRefreshInterval($interval)
    {
        if (!is_numeric($interval) && !is_string($interval)) {
            throw new IndexManagerException("Index refresh_interval needs to be an int or string");
        }
        $this->reindexRefreshInterval = $interval;
    }

    /**
     * Set zero replica reindex
     * @param boolean $flag
     */
    public function setReindexZeroReplica($flag)
    {
        $this->reindexZeroReplica = (bool) $flag;
    }

    /**
     * Verify if the system is ready to create/change indices.
     * @return boolean
     */
    protected function readyForIndexChanges()
    {
        // force connectivity check
        if (!$this->container->client->isAvailable(true)) {
            return false;
        }
        return $this->container->queueManager->pauseQueue();
    }

    /**
     * Schedule reindex:
     * 1. Create indices (drop existing one's on $recreateIndices)
     * 2. Queue all records for given modules
     * 3. Ensure scheduler job exists to process the queue
     *
     * @param array $modules List of modules
     * @param boolean $recreateIndices
     * @return boolean False on failure, true on success
     */
    public function scheduleIndexing(array $modules = array(), $recreateIndices = false)
    {
        if (!$this->readyForIndexChanges()) {
            $this->container->logger->critical('IndexManager: System not ready for full reindex, cancelling');
            return false;
        }

        $allModules = $this->getAllEnabledModules();
        if (empty($modules)) {
            $modules = $allModules;
        } else {
            $modules = array_intersect($modules, $allModules);
        }

        if ($recreateIndices) {
            $this->syncIndices($modules, $recreateIndices);
        }

        $this->disableRefresh($modules);
        $this->disableReplicas($modules);
        $this->container->queueManager->reindexModules($modules);

        return true;
    }

    /**
     * Create the mappings for given module without re-creating the index.
     * @param array $modules
     * @return bool
     */
    public function addMappings(array $modules = array())
    {
        if (!$this->readyForIndexChanges()) {
            $this->container->logger->critical('IndexManager: System not ready for full reindex, cancelling');
            return false;
        }

        //add the mapping without re-creating the index
        $this->syncIndices($modules, false);

        return true;
    }


    /**
     * *** Zero downtime reindexing with analyzer/mapping changes ***
     *
     * Smart reindex generates the full mapping and compares the difference
     * with the already present settings and mapping for every active index.
     * Based on this difference the Index Nanager will determine what needs
     * to happen to get everything back in sync.
     *
     * The following preliminary approaches can be used:
     *
     * Analyzer differences (same for ALL indices):
     * 1. New analyzer
     *      - Close index, add analyzer, open index
     * 2. Removed analyzer
     *      - Can be ignored, no further action
     * 3. Update existing analyzer
     *      - Not possible, highly discouraged
     *      - Needs full reindex
     *
     * Field mapping changes:
     * 1. New field and new sugarbean field
     *      - Can be added dynamically
     *      - No further action as need as no data exists yet for this field
     * 2. New field for existing sugarbean field
     *      - Can be added dynamically
     *      - Reindex of the module is required, needs database as source
     * 3. New mapping for existing field
     *      - Can be added dynamically, no need to remove the old one (*)
     *      - Reindex of the module required, can use elastic as source
     *
     * Different sync strategies may be implemented when an index is in need
     * of a "full" reindex.
     *
     * OPTION 1 when NO new data needs to be pulled from the database:
     * 1. Create new index with correct settings and mappings
     * 2. Redirect updates to the new index
     * 3. Scrolled search to funnel docs from the old into the new index
     * 4. When done delete the old index
     *
     * OPTION 2 when NEW data needs to be pulled from the database:
     * 1. Use procedure from above
     * 2. But before sending the docs back to ES pull specific field(s) in
     *    a cheap/fast way from the database
     *
     * TBD: The Index Pool/Strategy can be extended to support aliases. This
     * might come in hand but is not absolutely required as sugar should
     * already know when to use which indices. Nevertheless by adding smart
     * reindex support Index Pool does need to know about index versions
     * regardless if aliases are implemnted or not.
     *
     */

    /**
     * Sync new index settings to Elasticsearch backend
     * @param array $modules List of modules to sync
     * @param boolean $dropExist
     */
    protected function syncIndices(array $modules, $dropExist = false)
    {
        // Get registered providers
        $providerCollection = new ProviderCollection($this->container, $this->getRegisteredProviders());

        // build analysis settings
        $analysisBuilder = new AnalysisBuilder();
        $this->buildAnalysis($analysisBuilder, $providerCollection);

        // build mapping
        $mappingCollection = $this->buildMapping($providerCollection, $modules);

        // build index list
        $indexCollection = $this->getIndexCollection($mappingCollection);

        /*
         * Currently we only support full rebuilds of the indices. This will
         * change in the future by adding logic in IndexManager to diff the
         * new analyzer and mapping settings against the already deployed
         * indices if any. This will allow for an incremental update when
         * possible.
         */
        $this->createIndices($indexCollection, $analysisBuilder, $mappingCollection, $dropExist);
    }

    /**
     * Build analysis settings
     * @param AnalysisBuilder $analysisBuilder
     * @param ProviderCollection $providers
     */
    protected function buildAnalysis(AnalysisBuilder $analysisBuilder, ProviderCollection $providerCollection)
    {
        foreach ($providerCollection as $provider) {
            $provider->buildAnalysis($analysisBuilder);
        }
    }

    /**
     * Build mapping for available providers
     * @param ProviderCollection $providerCollection
     * @param array $modules the list of enabled modules
     * @return MappingCollection
     */
    protected function buildMapping(ProviderCollection $providerCollection, array $modules = array())
    {
        return $this->container->mappingManager->buildMapping($providerCollection, $modules);
    }

    /**
     * Build index settings for given index
     * @param Index $index
     * @param AnalysisBuilder $analysisBuilder
     * @return array
     */
    protected function buildIndexSettings(Index $index, AnalysisBuilder $analysisBuilder)
    {
        $config = $this->getIndexSettingsFromConfig($index);
        return array_merge($config, $analysisBuilder->compile());
    }

    /**
     * Get index settings from $sugar_config
     * @param Index $index
     * @return array
     */
    protected function getIndexSettingsFromConfig(Index $index)
    {
        $indexName = $index->getBaseName();
        $settings = array();

        // explicit index configuration
        if (isset($this->config[$indexName])) {
            $settings = $this->config[$indexName];
        }

        // default settings from config
        if (isset($this->config[self::DEFAULT_INDEX_SETTINGS_KEY])) {
            $settings = array_merge($this->config[self::DEFAULT_INDEX_SETTINGS_KEY], $settings);
        }

        // derfault core settings
        $settings = array_merge($this->defaultSettings, $settings);

        // We do NOT accept analysis settings anymore in `$sugar_config`
        if (isset($settings[AnalysisBuilder::ANALYSIS])) {
            unset($settings[AnalysisBuilder::ANALYSIS]);
        }

        return $settings;
    }

    /**
     * Create list of indices
     * @param IndexCollection $indexCollection
     * @param AnalysisBuilder $analysisBuilder
     * @param MappingCollection $mappingCollection
     * @param boolean $dropExist Drop indices if already they already exist
     */
    public function createIndices(
        IndexCollection $indexCollection,
        AnalysisBuilder $analysisBuilder,
        MappingCollection $mappingCollection,
        $dropExist = false
    ) {
        foreach ($indexCollection as $index) {

            if ($dropExist === true) {
                $this->createIndex($index, $analysisBuilder, $dropExist);
            }

            // Set mapping for all available types on this index
            $types = $index->getTypes();
            foreach ($types as $module => $type) {

                /* @var $fieldMappings Mapping */
                $fieldMappings = $mappingCollection->$module;
                $properties = $fieldMappings->compile();

                // Prepare mapping object
                $mapping = new \Elastica\Type\Mapping();
                $mapping->setType($type);
                $mapping->setProperties($properties);

                // Configure _source
                $mapping->setParam('_source', $this->getSourceSettings($fieldMappings));

                // Send mapping
                $this->sendMapping($mapping);
            }
        }
    }

    /**
     * Update Mappings of list of indices, it doesn't require re-index
     *
     * @param array $moduels, module names
     * @param MappingHandlerInterface[] $handlers
     *
     */
    public function updateIndexMappings(array $modules, MappingHandlerInterface ...$handlers)
    {
        if (empty($modules)) {
            // get all enabled modules
            $modules = $this->getAllEnabledModules();
        }

        // Create mapping iterator for requested modules
        $mappingCollection = new MappingCollection($modules);
        foreach ($mappingCollection as $mapping) {
            // build mapping
            foreach ($handlers as $handler) {
                foreach ($this->container->metaDataHelper->getFtsFields($mapping->getModule()) as $field => $defs) {
                    $handler->buildMapping($mapping, $field, $defs);
                };
            }
        }

        $indexCollection = $this->getIndexCollection($mappingCollection);
        foreach ($indexCollection as $index) {
            // Set mapping for all available types on this index
            $types = $index->getTypes();
            foreach ($types as $module => $type) {
                /* @var $fieldMappings Mapping */
                $fieldMappings = $mappingCollection->$module;
                $properties = $fieldMappings->compile();

                // Prepare mapping object
                $mapping = new \Elastica\Type\Mapping();
                $mapping->setType($type);
                $mapping->setProperties($properties);

                // send out without any default properties
                $mapping->send();
            }
        }
    }

    /**
     * Get _source field settings
     * @param Mapping $mapping
     * @return array
     */
    protected function getSourceSettings(Mapping $mapping)
    {
        // base settings
        $source = array(
            'enabled' => true,
        );

        // add excludes
        if ($excludes = $mapping->getSourceExcludes()) {
            $source['excludes'] = $excludes;
        }

        return $source;
    }

    /**
     * Create index
     * @param Index $index
     * @param AnalysisBuilder $analysisBuilder
     * @param boolean $dropExist Drop index if already exists
     * @return Index
     */
    public function createIndex(Index $index, AnalysisBuilder $analysisBuilder, $dropExist = false)
    {
        // TODO: add error handling
        $settings = $this->buildIndexSettings($index, $analysisBuilder);

        $result = $index->create($settings, $dropExist);
        return $index;
    }

    /**
     * Send type mapping to backend applying default mapping
     * @param \Elastica\Type\Mapping $mapping
     */
    protected function sendMapping(\Elastica\Type\Mapping $mapping)
    {
        // Apply default mapping settings
        foreach ($this->defaultMapping as $key => $value) {
            $mapping->setParam($key, $value);
        }
        $mapping->send();
    }

    /**
     * Get list of all enabled modules
     * @return array
     */
    protected function getAllEnabledModules()
    {
        return $this->container->metaDataHelper->getAllEnabledModules();
    }

    /**
     * Wrapper listing all registered providers
     * @return array
     */
    protected function getRegisteredProviders()
    {
        return $this->container->getRegisteredProviders();
    }

    /**
     * Get index collection for given mapping
     * @param MappingCollection $mappingCollection
     * @return IndexCollection
     */
    protected function getIndexCollection(MappingCollection $mappingCollection)
    {
        return $this->container->indexPool->buildIndexCollection($mappingCollection);
    }

    /**
     * Get list of managed indices for given modules
     * @param array $modules
     * @return IndexCollection
     */
    protected function getManagedIndices(array $modules)
    {
        return $this->container->indexPool->getManagedIndices($modules);
    }

    /**
     * Ensure the refresh intervals are properly configured for all indices.
     * During bulk imports the refresh interval can be disabled to speed up
     * bulk imports. This method is primarily called from the scheduler which
     * kicks in from cron. When no more records are present in fts_queue the
     * scheduler will re-enable the refresh intervals as per index config.
     *
     * This implies when the index.refresh_interval is set using $sugar_config,
     * this will be picked up automatically given cron is enabled on the
     * system.
     *
     * @return array List of affected indices and status code
     */
    public function enableRefresh()
    {
        $modules = $this->getAllEnabledModules();
        $status = array();
        foreach ($this->getManagedIndices($modules) as $index) {
            $status[$index->getName()] = $this->enableIndexRefresh($index)->getStatus();
        }
        return $status;
    }

    /**
     * Disable refresh interval on indices for given modules. This should only
     * be called from the queue manager when a reindex is scheduled. The
     * scheduler which will ensure the refresh interval is re-enabled when no
     * more records are in need of processing from the fts_queue table.
     *
     * @param array $modules List of modules
     * @return array List of affected indices and status code
     */
    public function disableRefresh(array $modules)
    {
        $status = array();
        foreach ($this->getManagedIndices($modules) as $index) {
            $status[$index->getName()] = $this->setIndexRefresh($index, $this->reindexRefreshInterval)->getStatus();
        }
        return $status;
    }

    /**
     * Enable replicas for all indices. This method is primarily called by the
     * scheduler when no more records are in the fts_queue table. This implies
     * that sugar controls the replica settings for its indices and no other
     * cluster overrides should be set to avoid flapping index recovery.
     *
     * @return array List of affected indices and status code
     */
    public function enableReplicas()
    {
        if (!$this->reindexZeroReplica) {
            return array();
        }

        $modules = $this->getAllEnabledModules();
        $status = array();
        foreach ($this->getManagedIndices($modules)->getIterator() as $index) {
            $status[$index->getName()] = $this->enableIndexReplicas($index)->getStatus();
        }
        return $status;
    }

    /**
     * Disable replicas on indices for given modules. This method is primarily
     * called by queue manager to disable replicas during reindexing process.
     * Cron scheduler will ensure to re-enable the replica settings once
     * fts_queue table is empty.
     *
     * @param array $modules
     * @return array List of affected indices and status code
     */
    public function disableReplicas(array $modules)
    {
        if (!$this->reindexZeroReplica) {
            return array();
        }

        $status = array();
        foreach ($this->getManagedIndices($modules)->getIterator() as $index) {
            $status[$index->getName()] = $this->setNumberOfReplicas($index, 0)->getStatus();
        }
        return $status;
    }

    /**
     * Set index interval
     * @param Index $index
     * @param int|string $interval
     * @return Response
     */
    protected function setIndexRefresh(Index $index, $interval)
    {
        $this->container->logger->info(sprintf(
            "IndexManager: Set refresh interval %s for %s",
            $interval,
            $index->getName()
        ));
        return $index->getSettings()->setRefreshInterval($interval);
    }

    /**
     * Set proper refresh interval for given index from configuration
     * @param Index $index
     * @return Response
     */
    protected function enableIndexRefresh(Index $index)
    {
        $config = $this->getIndexSettingsFromConfig($index);
        if (!isset($config['index.refresh_interval'])) {
            throw new IndexManagerException("No refresh_interval config setting available");
        }
        return $this->setIndexRefresh($index, $config['index.refresh_interval']);
    }

    /**
     * Set replicas on given index
     * @param Index $index
     * @param int $replicas
     * @return Response
     */
    protected function setNumberOfReplicas(Index $index, $replicas)
    {
        $this->container->logger->info(sprintf(
            "IndexManager: Set replicas to %s for %s",
            $replicas,
            $index->getName()
        ));
        return $index->getSettings()->setNumberOfReplicas($replicas);
    }

    /**
     * Enable replica settings for given index from configuration
     * @param Index $index
     * @return Response
     */
    protected function enableIndexReplicas(Index $index)
    {
        $config = $this->getIndexSettingsFromConfig($index);
        if (!isset($config['index.number_of_replicas'])) {
            throw new IndexManagerException("No number_of_replicas config setting available");
        }
        return $this->setNumberOfReplicas($index, $config['index.number_of_replicas']);
    }
}
