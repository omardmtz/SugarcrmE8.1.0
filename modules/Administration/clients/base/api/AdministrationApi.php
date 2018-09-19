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

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic;

/**
 *
 * Administration API
 *
 */
class AdministrationApi extends SugarApi
{
    /**
     * Register endpoints
     * @return array
     */
    public function registerApiRest()
    {
        return array(

            //// Search administration ////

            'searchReindex' => array(
                'reqType' => array('POST'),
                'path' => array('Administration', 'search', 'reindex'),
                'pathVars' => array(''),
                'method' => 'searchReindex',
                'shortHelp' => 'Perform a reindex',
                'longHelp' => 'include/api/help/administration_search_reindex_post_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'searchStatus' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'search', 'status'),
                'pathVars' => array(''),
                'method' => 'searchStatus',
                'shortHelp' => 'Search status',
                'longHelp' => 'include/api/help/administration_search_status_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'searchFields' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'search', 'fields'),
                'pathVars' => array(''),
                'method' => 'searchFields',
                'shortHelp' => 'List search field configuration',
                'longHelp' => 'include/api/help/administration_search_fields_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                ),
            ),

            //// Elasticsearch administration ////

            'elasticSearchQueue' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'queue'),
                'pathVars' => array(''),
                'method' => 'elasticSearchQueue',
                'shortHelp' => 'Elasticsearch queue statistics',
                'longHelp' => 'include/api/help/administration_elasticsearch_queue_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchRouting' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'routing'),
                'pathVars' => array(''),
                'method' => 'elasticSearchRouting',
                'shortHelp' => 'Elasticsearch index routing',
                'longHelp' => 'include/api/help/administration_elasticsearch_routing_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchIndices' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'indices'),
                'pathVars' => array(''),
                'method' => 'elasticSearchIndices',
                'shortHelp' => 'Elasticsearch index statistics',
                'longHelp' => 'include/api/help/administration_elasticsearch_indices_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchMapping' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'mapping'),
                'pathVars' => array(''),
                'method' => 'elasticSearchMapping',
                'shortHelp' => 'Elasticsearch index mappings',
                'longHelp' => 'include/api/help/administration_elasticsearch_mapping_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),

            // Refresh API's
            'elasticSearchRefreshStatus' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'refresh', 'status'),
                'pathVars' => array(''),
                'method' => 'elasticSearchRefreshStatus',
                'shortHelp' => 'Elasticsearch index refresh status',
                'longHelp' => 'include/api/help/administration_elasticsearch_refresh_status_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchRefreshTrigger' => array(
                'reqType' => array('POST'),
                'path' => array('Administration', 'elasticsearch', 'refresh', 'trigger'),
                'pathVars' => array(''),
                'method' => 'elasticSearchRefreshTrigger',
                'shortHelp' => 'Elasticsearch trigger an index refresh',
                'longHelp' => 'include/api/help/administration_elasticsearch_refresh_trigger_post_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchRefreshEnable' => array(
                'reqType' => array('POST'),
                'path' => array('Administration', 'elasticsearch', 'refresh', 'enable'),
                'pathVars' => array(''),
                'method' => 'elasticSearchRefreshEnable',
                'shortHelp' => 'Elasticsearch enable index refresh',
                'longHelp' => 'include/api/help/administration_elasticsearch_refresh_enable_post_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),

            // Replica API's
            'elasticSearchReplicasStatus' => array(
                'reqType' => array('GET'),
                'path' => array('Administration', 'elasticsearch', 'replicas', 'status'),
                'pathVars' => array(''),
                'method' => 'elasticSearchReplicasStatus',
                'shortHelp' => 'Elasticsearch index replica status',
                'longHelp' => 'include/api/help/administration_elasticsearch_replicas_status_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
            'elasticSearchReplicasEnable' => array(
                'reqType' => array('POST'),
                'path' => array('Administration', 'elasticsearch', 'replicas', 'enable'),
                'pathVars' => array(''),
                'method' => 'elasticSearchReplicasEnable',
                'shortHelp' => 'Elasticsearch enable index replicas',
                'longHelp' => 'include/api/help/administration_elasticsearch_replicas_enable_post_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                ),
            ),
        );
    }

    /**
     * Search reindex
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function searchReindex(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $clearData = isset($args['clear_data']) ? (bool) $args['clear_data'] : false;
        $modules = empty($args['module_list']) ? array() : explode(',', $args['module_list']);
        $engine = $this->getSearchEngine();
        $status = $engine->scheduleIndexing($modules, $clearData);
        return array('success' => $status);
    }

    /**
     * Search status
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function searchStatus(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine();

        // Check if search backend is available
        if (!$engine->isAvailable(true)) {
            return array('available' => false);
        }

        $modules = $engine->getMetaDataHelper()->getAllEnabledModules();
        sort($modules);

        $status = array(
            'available' => true,
            'enabled_modules' => $modules,
        );

        return $status;
    }

    /**
     * Search field configuration
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function searchFields(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $modules = empty($args['module_list']) ? array() : explode(',', $args['module_list']);
        $list = $this->getSearchFields($modules);

        if (isset($args['order_by_boost'])) {
            $orderByBoost = true;
            $searchOnly = true;
        } else {
            $orderByBoost = false;
            $searchOnly = isset($args['search_only']);
        }

        // filter searchable fields only
        if ($searchOnly) {
            foreach ($list as $module => $fields) {
                $list[$module] = array_filter($fields, function ($value) {
                    return !empty($value['searchable']);
                });
            }
        }

        // order by boost returning a flat list
        if ($orderByBoost) {
            $flat = array();
            foreach ($list as $module => $fields) {
                foreach ($fields as $field => $defs) {
                    $key = $module . '.' . $field;
                    $flat[$key] = $defs['boost'];
                }
            }
            arsort($flat, SORT_NUMERIC);
            $list = $flat;
        }
        return $list;
    }

    /**
     * Get search fields for given modules
     * @param array $modules
     * @return array
     */
    protected function getSearchFields(array $modules)
    {
        $metaDataHelper = $this->getSearchEngine()->getMetaDataHelper();

        // use all modules if non given
        $modules = $modules ?: $metaDataHelper->getAllEnabledModules();

        $fields = array();
        foreach ($modules as $module) {

            $fields[$module] = array();
            foreach ($metaDataHelper->getFtsFields($module) as $defs) {

                $searchable = $metaDataHelper->isFieldSearchable($defs);

                $field = array(
                    'name' => $defs['name'],
                    'type' => $defs['type'],
                    'searchable' => $searchable,
                );

                // add boost value for searchable fields
                if ($searchable) {
                    if (isset($defs['full_text_search']['boost'])) {
                        $field['boost'] = $defs['full_text_search']['boost'];
                    } else {
                        $field['boost'] = 1;
                    }
                }

                $fields[$module][$defs['name']] = $field;
            }
        }
        return $fields;
    }

    /**
     * Elasticsearch queue
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchQueue(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $total = 0;
        $queued = array();

        // get statistics per module
        $queueManager = $this->getSearchEngine(true)->getContainer()->queueManager;
        foreach ($queueManager->getQueuedModules() as $module) {
            $queued[$module] = $queueManager->getQueueCountModule($module);
        }

        // total count
        if ($queued) {
            $total = array_reduce($queued, function ($carry, $value) {
                return $carry + $value;
            });
        }

        return array(
            'total' => $total,
            'queued' => $queued,
        );
    }

    /**
     * Elasticsearch index routing
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchRouting(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        $metaDataHelper = $engine->getMetaDataHelper();
        $indexPool = $engine->getContainer()->indexPool;

        $result = array();
        foreach ($metaDataHelper->getAllEnabledModules() as $module) {

            $read = array();
            foreach ($indexPool->getReadIndices(array($module))->getIterator() as $index) {
                $read[] = $index->getName();
            }

            $result[$module] = array(
                'strategy' => $indexPool->getStrategy($module)->getIdentifier(),
                'routing' => array(
                    'write_index' => $indexPool->getWriteIndex($module)->getName(),
                    'read_indices' => $read,
                ),
            );
        }
        return $result;
    }

    /**
     * Elasticsearch index statistics
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchIndices(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);

        $indices = array();
        foreach ($this->getIndices($engine) as $index) {
            $indices[$index->getName()] = $index->getStats()->getData();
        }
        return $indices;
    }

    /**
     * Elasticsearch mapping
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchMapping(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);

        $indices = array();
        foreach ($this->getIndices($engine) as $index) {
            $indices[$index->getName()] = $index->getMapping();
        }
        return $indices;
    }

    /**
     * Get managed indices
     * @param Elastic $engine
     * @return \Elastica\Index[]
     */
    protected function getIndices(Elastic $engine)
    {
        $indexPool = $engine->getContainer()->indexPool;
        $modules = $engine->getMetaDataHelper()->getAllEnabledModules();
        return $indexPool->getManagedIndices($modules)->getIterator();
    }

    /**
     * Get SearchEngine
     * @param boolean $checkElastic Check if backend is Elastic
     * @throws SugarApiExceptionSearchUnavailable
     * @return Elastic
     */
    protected function getSearchEngine($checkElastic = false)
    {
        $searchEngine = SearchEngine::getInstance()->getEngine();
        if ($checkElastic && !$searchEngine instanceof Elastic) {
            throw new SugarApiExceptionSearchUnavailable(
                'Administration not supported for non Elasticsearch backend'
            );
        }
        return $searchEngine;
    }

    /**
     * Ensure current user has admin permissions
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function ensureAdminUser()
    {
        if (empty($GLOBALS['current_user']) || !$GLOBALS['current_user']->isAdmin()) {
            throw new SugarApiExceptionNotAuthorized(
                $GLOBALS['app_strings']['EXCEPTION_NOT_AUTHORIZED']
            );
        }
    }

    /**
     * Get refresh status for all indices
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchRefreshStatus(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        $indices = array();

        foreach ($this->getIndices($engine) as $index) {
            $indices[$index->getName()] = $index->getSettings()->getRefreshInterval();
        }
        return $indices;
    }

    /**
     * Trigger a manual refresh on all indices
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchRefreshTrigger(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        $indices = array();

        foreach ($this->getIndices($engine) as $index) {
            $status = $index->refresh();
            $indices[$index->getName()] = $status->getStatus();
        }
        return $indices;
    }

    /**
     * Enable refresh on all indices
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchRefreshEnable(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        return $engine->getContainer()->indexManager->enableRefresh();
    }

    /**
     * Get replica status for all indices
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchReplicasStatus(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        $indices = array();

        foreach ($this->getIndices($engine) as $index) {
            $indices[$index->getName()] = $index->getSettings()->get('number_of_replicas');
        }
        return $indices;
    }

    /**
     * Enable replicas on all indices
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function elasticSearchReplicasEnable(ServiceBase $api, array $args)
    {
        $this->ensureAdminUser();

        $engine = $this->getSearchEngine(true);
        return $engine->getContainer()->indexManager->enableReplicas();
    }
}
