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
use Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch\GlobalSearchCapable;
use Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch\ResultSetInterface;
use Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch\ResultInterface;
use Sugarcrm\Sugarcrm\SearchEngine\Capability\Aggregation\AggregationCapable;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Result;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\TagsHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;

/**
 *
 * GlobalSearch API
 *
 * (Note: the usage of /search will be deprecated in favor of /globalsearch)
 *
 *  The /globalsearch entry point is able to accept arguments  in the request
 *  body using JSON format. In case of duplicate settings, the URL parameters
 *  take precedence over the settings in the request body. Its encouraged to
 *  pass the arguments directly in the request body to prevent too long URLs.
 *
 *  Its prefered to use the GET method to consume the /globalsearch entry
 *  point. However for REST clients which do not support GET requests with
 *  request bodies, the POST method is also supported.
 *
 */
class GlobalSearchApi extends SugarApi
{
    /**
     * @var integer Offset
     */
    protected $offset = 0;

    /**
     * @var integer Limit
     */
    protected $limit = 20;

    /**
     * @var boolean Collect highlights
     */
    protected $highlights = true;

    /**
     * @var boolean Apply field boosts
     */
    protected $fieldBoost = true;

    /**
     * @var array List of modules to query
     */
    protected $moduleList = array();

    /**
     * @var array List of aggregation filters to query
     */
    protected $aggFilters = array();

    /**
     * @var boolean a flag to get tags in the response;
     */
    protected $getTags = false;

    /**
     * @var array List of tag ids for filtering;
     */
    protected $tagFilters = array();

    /**
     * @var array the list of filters to be added to globalsearch;
     */
    protected $filters = array();

    /**
     * @var integer the maximum number of tags returned in the response
     */
    protected $tagLimit = 5;

    /**
     * @var string Search term
     */
    protected $term = '';

    /**
     * @var array Sort fields
     */
    protected $sort = array();

    /**
     * Get cross module aggregation results
     * @var boolean
     */
    protected $crossModuleAggs = false;

    /**
     * List of modules for which to collect aggregations results
     * @var array
     */
    protected $moduleAggs = array();

    /**
     * Register endpoints
     * @return array
     */
    public function registerApiRest()
    {
        return array(

            // /globalsearch
            'globalSearch' => array(
                'reqType' => array('GET', 'POST'),
                'path' => array('globalsearch'),
                'pathVars' => array(''),
                'method' => 'globalSearch',
                'shortHelp' => 'Global search',
                'longHelp' => 'include/api/help/globalsearch_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                    'SugarApiExceptionSearchRuntime',
                ),
            ),

            // /<module>/globalsearch
            'modulesGlobalSearch' => array(
                'reqType' => array('GET', 'POST'),
                'path' => array('<module>', 'globalsearch'),
                'pathVars' => array('module', ''),
                'method' => 'globalSearch',
                'shortHelp' => 'Global search',
                'longHelp' => 'include/api/help/globalsearch_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionSearchUnavailable',
                    'SugarApiExceptionSearchRuntime',
                ),
            ),
        );
    }

    /**
     * GlobalSearch endpoint
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function globalSearch(ServiceBase $api, array $args)
    {
        $api->action = 'list';

        // Set properties from arguments
        $this->parseArguments($args);

        // Load global search engine
        $globalSearch = $this->getSearchEngine()->getEngine();

        // Get search results
        try {
            $resultSet = $this->executeGlobalSearch($globalSearch);
        } catch (\Exception $e) {
            $GLOBALS['log']->error("A search engine runtime error occurred:\n" . $e->getMessage());
            throw new SugarApiExceptionSearchRuntime();
        }

        // Handle the regular result set
        $response = array(
            'next_offset' => $this->getNextOffset($resultSet->getTotalHits(), $this->limit, $this->offset),
            'total' => $resultSet->getTotalHits(),
            'query_time' => $resultSet->getQueryTime(),
            'records' => $this->formatResults($api, $args, $resultSet),
        );

        // cross module aggregation results
        if ($this->crossModuleAggs) {
            $response['xmod_aggs'] = $resultSet->getAggregations();
        }

        // per module aggregation results
        if ($this->moduleAggs) {
            $response['mod_aggs'] = array();
        }

        // Search the tag module
        if ($this->getTags == true && !empty($this->term)) {
            $resultSet = $globalSearch->searchTags();
            $response['tags'] = $this->formatTagResults($resultSet);
        }
        return $response;
    }

    /**
     * Parse arguments
     * @param array $args
     */
    protected function parseArguments(array $args)
    {
        // Modules can be a comma separated list
        if (!empty($args['module_list']) && !is_array($args['module_list'])) {
            $this->moduleList = explode(',', $args['module_list']);
        }

        // If specific module is selected, this overrules the list
        if (!empty($args['module'])) {
            $this->moduleList = array($args['module']);
        }

        // Set search term
        if (!empty($args['q'])) {
            $this->term = $args['q'];
        }

        // Set limit
        if (isset($args['max_num'])) {
            $this->limit = (int) $args['max_num'];
        }

        // Set offset
        if (isset($args['offset'])) {
            $this->offset = (int) $args['offset'];
        }

        // Enable/disable highlights
        if (isset($args['highlights'])) {
            $this->highlights = (bool) $args['highlights'];
        }

        // Set sorting
        if (isset($args['sort']) && is_array($args['sort'])) {
            $this->sort = $args['sort'];
        }

        // Set cross module aggregations
        if (!empty($args['xmod_aggs'])) {
            $this->crossModuleAggs = true;
        }

        // Set module aggregations
        if (isset($args['mod_aggs'])) {
            $this->moduleAggs = explode(',', $args['mod_aggs']);
        }

        // Set aggregation filters
        if (!empty($args['agg_filters'])) {
            $this->aggFilters = $this->parseAggFilters($args['agg_filters']);
        }

        // Get tags related parameters
        if (!empty($args['tags'])) {
            $this->getTags = (bool) $args['tags'];
        }

        // Tag filters
        if (!empty($args['tag_filters'])) {
            $this->tagFilters = $this->parseTagFilters($args['tag_filters']);
        }
    }

    /**
     * Parse the list of aggregation filters from the arguments
     * @param string $aggFilterArgs
     * @return array
     */
    protected function parseAggFilters($aggFilterArgs)
    {
        if (!is_array($aggFilterArgs)) {
            return array();
        }

        $parsed = array();
        foreach ($aggFilterArgs as $id => $filter) {
            /*
             * We accept either an array of selected items or just a boolean.
             * Further down the road the aggregation handler will further
             * validate.
             */
            if (is_array($filter) || is_bool($filter)) {
                $parsed[$id] = $filter;
            }

        }
        return $parsed;
    }

    /**
     * Parse the list of tag filters from the arguments
     * @param string $tagFilterArgs
     * @return array
     */
    protected function parseTagFilters($tagFilterArgs)
    {
        if (!is_array($tagFilterArgs)) {
            return array();
        }
        return $tagFilterArgs;

    }

    /**
     * Compose a list of filters to be used in global search
     */
    protected function composeFilters()
    {
        // Compose the term filter for the tags
        if (!empty($this->tagFilters)) {
            $this->filters[] = new \Elastica\Query\Terms(
                Mapping::PREFIX_COMMON .TagsHandler::TAGS_FIELD .'.tags',
                $this->tagFilters
            );
        }

        // Compose the bool and term filter to exclude the tag module
        $tagFilter = new \Elastica\Query\Terms("_type", ["Tags"]);
        $boolFilter = new \Elastica\Query\BoolQuery();
        $boolFilter->addMustNot($tagFilter);
        $this->filters[] = $boolFilter;
    }

    /**
     * Execute search
     * @param GlobalSearchInterface $engine
     * @return \Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch\ResultSetInterface
     */
    protected function executeGlobalSearch(GlobalSearchCapable $engine)
    {
        $this->composeFilters();

        $engine
            ->from($this->moduleList)
            ->getTags($this->getTags)
            ->setTagLimit($this->tagLimit)
            ->setFilters($this->filters)
            ->term($this->term)
            ->limit($this->limit)
            ->offset($this->offset)
            ->fieldBoost($this->fieldBoost)
            ->highlighter($this->highlights)
            ->sort($this->sort)
        ;

        // pass aggregation query settings
        if ($engine instanceof AggregationCapable) {
            $engine
                ->queryCrossModuleAggs($this->crossModuleAggs)
                ->queryModuleAggs($this->moduleAggs)
                ->aggFilters($this->aggFilters)
            ;
        }

        return $engine->search();
    }

    /**
     * Get global search provider
     * @throws \SugarApiExceptionSearchRuntime
     * @throws \SugarApiExceptionSearchUnavailable
     * @return \Sugarcrm\Sugarcrm\SearchEngine\SearchEngine
     */
    protected function getSearchEngine()
    {
        // Instantiate search engine with GlobalSearch capability
        try {
            $engine = SearchEngine::getInstance('GlobalSearch');
        } catch (\Exception $e) {
            $GLOBALS['log']->error("A search engine runtime error occurred:\n" . $e->getMessage());
            throw new SugarApiExceptionSearchRuntime();
        }

        // Make sure engine is available
        if (!$engine->isAvailable()) {
            throw new SugarApiExceptionSearchUnavailable();
        }

        return $engine;
    }

    /**
     * Calculate next offset
     * @param integer $total
     * @param integer $limit
     * @param integer $offset
     * @return integer
     */
    protected function getNextOffset($total, $limit, $offset)
    {
        if ($total > ($limit + $offset)) {
            $nextOffset = $limit + $offset;
        } else {
            $nextOffset = -1;
        }
        return $nextOffset;
    }

    /**
     * Format result set
     *
     * @param ServiceBase $api
     * @param array $args
     * @param ResultSetInterface $results
     * @return array
     */
    protected function formatResults(ServiceBase $api, array $args, ResultSetInterface $results)
    {
        $formatted = array();

        /* @var $result ResultInterface */
        foreach ($results as $result) {

            // get bean data based on available fields in the result
            $data = $this->formatBeanFromResult($api, $args, $result);

            // set score
            if ($score = $result->getScore()) {
                $data['_score'] = $score;
            }

            // add highlights if available
            if ($highlights = $result->getHighlights()) {

                // Filter out fields from highlights which are not present
                // on our bean. This is to ensure we never return any fields
                // which are not avialable due to ACL's.
                foreach ($highlights as $field => $highlight) {
                    if (!isset($data[$field])) {
                        unset($highlights[$field]);
                    }
                }
                $data['_highlights'] = $highlights;
            }

            $formatted[] = $data;
        }

        return $formatted;
    }

    /**
     * Format the result set for tags
     * @param ResultSetInterface $results
     * @return array
     */
    protected function formatTagResults(ResultSetInterface $results)
    {
        $formatted = array();

        /* @var $result ResultInterface */
        foreach ($results as $result) {
            $data = array();

            // Retrieve tags' id & name
            $fields = $result->getData();
            $data['id'] = $result->getId();
            $data['name'] = $fields['name'];

            $formatted[] = $data;
        }
        return $formatted;
    }

    /**
     * Wrapper around formatBean based on Result
     * @param ServiceBase $api
     * @param array $args
     * @param Result $result
     * @return array
     */
    protected function formatBeanFromResult(ServiceBase $api, array $args, Result $result)
    {
        // pass in field list from available data fields on result
        $args = array(
            'fields' => $result->getDataFields(),
            'erased_fields' => $args['erased_fields']?? null,
        );
        $bean = $result->getBean();

        // Load email information directly from search backend if available
        // to avoid additional database retrievals.
        if (!empty($bean->emailAddress) && isset($bean->email)) {
            $bean->emailAddress->addresses = $bean->email;
            $bean->emailAddress->hasFetched = true;
        }

        return $this->formatBean($api, $args, $bean);
    }
}
