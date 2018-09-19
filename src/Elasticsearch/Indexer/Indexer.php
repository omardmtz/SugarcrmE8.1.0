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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Indexer;

use Sugarcrm\Sugarcrm\Elasticsearch\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\ProviderCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;

/**
 *
 * The Indexer is responsible to handle all data synchronisation into the
 * Elasticsearch backend. It will take care of populating the data properly
 * and using bulk indexers to send data.
 *
 */
class Indexer
{
    /**
     * @var \Sugarcrm\Sugarcrm\Elasticsearch\Container
     */
    protected $container;

    /**
     * @var \Sugarcrm\Sugarcrm\Elasticsearch\Indexer\BulkHandler
     */
    protected $bulkHandler;

    /**
     * Asynchronous index mode, can be configured using
     * `$sugar_config['search_engine']['force_async_index']`.
     * @var boolean
     */
    protected $async = false;

    /**
     * Disable indexer. Note that the status of this flag only affects the
     * current process. Disabling the indexer this way should only happen
     * for specific use cases like the installer/upgrader.
     * @var boolean
     */
    protected $disabled = false;

    /**
     * Maximum documents being sent in one bulk request as defined in
     * `$sugar_config['search_engine']['max_bulk_threshold']`.
     * @var integer
     */
    protected $maxBulkThreshold = 100;

    /**
     * @var \DBManager
     */
    protected $db;

    /**
     * Ctor
     * @param array $config
     * @param Container $container
     * @param \DBManager $db
     */
    public function __construct(array $config, Container $container, \DBManager $db)
    {
        if (!empty($config['force_async_index'])) {
            $this->async = (bool) $config['force_async_index'];
        }
        if (!empty($config['max_bulk_threshold'])) {
            $this->maxBulkThreshold = (int) $config['max_bulk_threshold'];
        }

        $this->container = $container;
        $this->db = $db;
    }

    /**
     * Index SugarBean into Elastichsearch. By default we send all beans
     * through the bulk (batch) handler to minimize the amount of updates
     * to the Elasticsearch backend. On the end of the page load the in
     * memory queue will be flushed. When calling this method from the queue
     * directly, make sure to set `$fromQueue` to avoid loops.
     *
     * @param \SugarBean $bean
     * @param boolean $batch
     * @param boolean $fromQueue
     * @return boolean
     */
    public function indexBean(\SugarBean $bean, $batch = true, $fromQueue = false)
    {
        // Skip indexing if we are disabled
        if ($this->disabled) {
            return false;
        }

        // In Elasticsearch code we rely on $bean->module_name so it is impossible of handling a SugarBean
        // which has module_name overwritten/redefined. Add the conversion of object name to module name,
        // instead of using $bean->module_name directly.
        // Note that $bean->module_name is overridden/redefined for the class 'Filters' in its vardef file, which
        // causes the SguarBeans of Filters may also be indexed. This conversion is added to handle such cases.

        // Also a unit test data\DrPhilTest.php::testOverriddenSugarBeanFields() is added to verify if there're any
        // overridden/redefined fields for SugarBean modules.
        $moduleName = \BeanFactory::getModuleName($bean);
        if ($moduleName === false) {
            return false;
        }

        // Skip bean if module not enabled
        if (!$this->container->metaDataHelper->isModuleEnabled($moduleName)) {
            return true;
        }

        // Send to database queue when Elastic is unavailable or in async mode
        if (!$fromQueue && $this->useQueue($bean)) {
            $this->container->queueManager->queueBean($bean);
            return true;
        }

        // Convert bean into an Elastica Document
        $document = $this->getDocumentFromBean($bean);

        // Process the document before indexing. We also pass the bean as a
        // reference in case the logic needs this.
        $this->processDocumentPreIndex($document, $bean);

        return $this->indexDocument($document, $batch);
    }

    /**
     * Verify whether or not to use the queue
     * @param \SugarBean $bean
     * @return boolean
     */
    protected function useQueue(\SugarBean $bean)
    {
        // connection should be available
        if (!$this->container->client->isAvailable()) {
            return true;
        }

        // in async mode use queue only if not coming from the queue already
        if ($this->async || $this->isModuleAsync($bean->module_name)) {
            return true;
        }

        return false;
    }

    /**
     * Check if given module is configured for async indexing only
     * @param string $module
     */
    protected function isModuleAsync($module)
    {
        return in_array($module, $this->container->metaDataHelper->getAsyncModules());
    }

    /**
     * Pass document to all the providers before indexing.
     *
     * @param Document $document Document being indexed
     * @param \SugarBean $bean Bean reference
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        foreach ($this->getRegisteredProviders() as $provider) {
            $provider->processDocumentPreIndex($document, $bean);
        }
    }

    /**
     * Get list of all registered provider names
     * @return array
     */
    protected function getRegisteredProviders()
    {
        return  new ProviderCollection($this->container, $this->container->getRegisteredProviders());
    }


    /**
     * Index Elastica Document into Elasticsearch. By default we send all
     * documents through the bulk (batch) handler to minimize the amount of
     * updates to the Elasticsearch backend. On the end of the page load the
     * in memory queue will be flushed.
     *
     * @param \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document $document
     * @param string $batch
     * @return boolean
     */
    protected function indexDocument(Document $document, $batch = true)
    {
        // Safeguard avoid sending documents without data, exception for deletes
        if (!$document->hasData() && $document->getOpType() !== \Elastica\Bulk\Action::OP_TYPE_DELETE) {
            return false;
        }

        if ($batch) {
            // Use in memory queue
            $this->getBulkHandler()->batchDocument($document);
        } else {
            // Send it out immediately
            $bulk = $this->newBulkHandler();
            $bulk->batchDocument($document);
            $bulk->finishBatch();
        }
        return true;
    }

    /**
     * Enable/disable asynchronous indexing
     * @param boolean $toggle
     */
    public function setForceAsyncIndex($toggle)
    {
        $this->async = $toggle;
    }

    /**
     * Enable/disable indexing
     * @param boolean $toggle
     */
    public function setDisable($toggle)
    {
        $this->disabled = $toggle;
    }

    /**
     * Lazy load local bulk handler
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Indexer\BulkHandler
     */
    protected function getBulkHandler()
    {
        if (empty($this->bulkHandler)) {
            $this->bulkHandler = $this->newBulkHandler();
        }
        return $this->bulkHandler;
    }

    /**
     * Create new bulk handler object
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Indexer\BulkHandler
     */
    protected function newBulkHandler()
    {
        $bulk = new BulkHandler($this->container);
        $bulk->setMaxBulkThreshold($this->maxBulkThreshold);
        return $bulk;
    }

    /**
     * Get index object for given bean.
     * @param \SugarBean $bean
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Index
     */
    protected function getWriteIndex(\SugarBean $bean)
    {
        $context = array('bean' => $bean);
        return $this->container->indexPool->getWriteIndex($bean->module_name, $context);
    }

    /**
     * Get field list to be indexed for given module.
     * @param string $module
     * @param boolean $fromQueue
     * @return array
     */
    public function getBeanIndexFields($module, $fromQueue = false)
    {
        $fields = array();

        foreach ($this->getRegisteredProviders() as $provider) {
            /* @var $provider ProviderInterace */
            $fields = array_merge(
                $fields,
                $provider->getBeanIndexFields($module, $fromQueue)
            );
        }
        return $fields;
    }

    /**
     * Get document for given bean. The returned document will have the
     * the operation action (create/update/delete) and target index set.
     * @param \SugarBean $bean
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document
     */
    protected function getDocumentFromBean(\SugarBean $bean)
    {
        $module = $bean->module_name;
        $index = $this->getWriteIndex($bean);
        $document = new Document($bean->id, array(), $bean->module_name, $index);

        // We dont need to send the whole data when deleting a record
        if ($bean->deleted) {
            $document->setOpType(\Elastica\Bulk\Action::OP_TYPE_DELETE);
            return $document;
        }

        // Ensure we have an explicit action set for graceful handling further down the line
        $document->setOpType(\Elastica\Bulk\Action::OP_TYPE_INDEX);

        // Get list of fields which are expected to be populated populated
        $fields = $this->getBeanIndexFields($module);

        // Populate field data from bean for bean index fields
        $data = array();
        foreach (array_keys($fields) as $field) {
            if (isset($bean->$field)) {
                $data[$field] = $this->decodeBeanField($bean->$field);
            }
        }

        $document->setId($bean->id);
        $document->setData($data);
        return $document;
    }

    /**
     * Decode the bean field if it's encoded before (e.g. from demo data or BWC mode, see \BeanFactory::getBean()).
     * @param object $fieldValue the field in SugarBean
     * @return mixed
     */
    protected function decodeBeanField($fieldValue)
    {
        if ($this->isFromApi()) {
            //In this case the call is made from our API, the field has not been encoded --> do nothing
            return $fieldValue;
        } else {
            return $this->db->decodeHTML($fieldValue);
        }
    }

    /**
     * Check if we are being called from API
     * @return boolean
     */
    protected function isFromApi()
    {
        return (defined('ENTRY_POINT_TYPE') && constant('ENTRY_POINT_TYPE') === 'api');
    }
}
