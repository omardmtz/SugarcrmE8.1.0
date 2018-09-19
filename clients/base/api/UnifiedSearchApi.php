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

/**
 *
 * UnifiedSearchApi for /search entry point
 *
 * The /search entry point uses a hybrid approach wrapping around full text
 * search (using Elasticearch) and spot search (using the database backend).
 *
 * A new entry point /globalsearch is available (see GlobalSearchApi) which
 * solely uses the Elasticsearch backend. It is encouraged to use this new
 * /globalsearch end point as the current /search will be deprecated in a
 * future release.
 *
 */
class UnifiedSearchApi extends SugarListApi {
    public function registerApiRest() {
        return array(
            'globalSearch' => array(
                'reqType' => 'GET',
                'path' => array('search'),
                'pathVars' => array(''),
                'method' => 'globalSearch',
                'jsonParams' => array('fields'),
                'shortHelp' => 'Globally search records',
                'longHelp' => 'include/api/help/module_get_help.html',
            )
        );
    }

    protected $defaultLimit = 20; // How many records should we show if they don't pass up a limit
    protected $defaultModuleLimit = 20; // How many records should we show if they don't pass up a limit

    /**
     * This function pulls all of the search-related options out of the $args array and returns a fully-populated array with either the defaults or the provided settings
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @return array Many elements containing each setting the search engine uses
     */
    public function parseSearchOptions(ServiceBase $api, array $args)
    {
        $options = array();

        if ( isset($args['module_list']) && count($args['module_list']) == 1 ) {
            // We can create a bean of this type
            $seed = BeanFactory::newBean($args['module_list']);
        } else {
            $seed = null;
        }
        $options = parent::parseArguments($api, $args, $seed);

        // We need to support 'deleted' same as ListApi
        $options['deleted'] = false;
        if (isset($args['deleted']) && (strtolower($args['deleted']) == 'true' || $args['deleted'] == '1' )) {
            $options['deleted'] = true;
        }

        $options['query'] = '';
        if ( isset($args['q']) ) {
            $options['query'] = trim($args['q']);
        }

        $options['limitPerModule'] = $this->defaultModuleLimit;
        if ( !empty($args['max_num_module']) ) {
            $options['limitPerModule'] = (int)$args['max_num_module'];
        }

        $options['searchFields'] = array();
        if (!empty($args['search_fields'])) {
            $options['searchFields'] = explode(',', $args['search_fields']);
        }

        $options['selectFields'] = array('id');
        if ( !empty($args['order_by']) ) {
            if ( strpos($args['order_by'],',') !== 0 ) {
                // There is a comma, we are ordering by more than one thing
                $orderBys = explode(',',$args['order_by']);
            } else {
                $orderBys = array($args['order_by']);
            }
            $orderByArray = array();
            foreach ( $orderBys as $order ) {
                if ( strpos($order,':') ) {
                    // It has a :, it's specifying ASC / DESC
                    list($column,$direction) = explode(':',$order);
                    if ( strtolower($direction) == 'desc' ) {
                        $direction = 'DESC';
                    } else {
                        $direction = 'ASC';
                    }
                } else {
                    // No direction specified, let's let it fly free
                    $column = $order;
                    $direction = 'ASC';
                }

                // If this field has already been added, don't do it again
                // Common cause of this was the id field, since we always add it
                // by default.
                if (in_array($column, $options['selectFields'])) {
                    // Before busting out of this, ensure we have what we need
                    if (empty($orderByData[$column])) {
                        $orderByData[$column] = ($direction=='ASC'?true:false);
                        if (!in_array("$column $direction", $orderByArray)) {
                            $orderByArray[] = $column.' '.$direction;
                        }
                    }

                    continue;
                }
                $options['selectFields'][] = $column;
                $orderByData[$column] = ($direction=='ASC'?true:false);
                $orderByArray[] = $column.' '.$direction;
            }
            $options['orderBySetByApi'] = true;
            $orderBy = implode(',',$orderByArray);
        } else {
            /*
             * Adding id to the default sort by.  When data has the same date_modified the sort could change with the
             * offset showing the same record on multiple pages
             */
            $orderBy = 'date_modified DESC, id DESC';
            $orderByData['date_modified'] = false;
            $orderByData['id'] = false;
            $options['orderBySetByApi'] = false;
            $options['selectFields'][] = 'date_modified';
        }
        $options['orderByArray'] = $orderByData;
        $options['orderBy'] = $orderBy;

        $options['moduleList'] = array();
        if ( !empty($args['module_list']) ) {
            $options['moduleList'] = explode(',',$args['module_list']);
            // remove any empty moduleList array entries..if someone were to do Contacts, it would not hit elastic because '' is not an elastic module.
            $options['moduleList'] = array_filter($options['moduleList']);
        }
        $options['primaryModule'] = 'Home';
        if ( !empty($args['primary_module']) ) {
            $options['primaryModule']=$args['primary_module'];
        } else if ( isset($options['moduleList'][0]) ) {
            $options['primaryModule'] = $options['moduleList'][0];
        }

        // we want favorites info with records, so that we can flag a favorite out of a recordset
        $options['favorites'] = false;
        if ( !empty($args['favorites']) && $args['favorites'] == true ) {
            // Setting favorites to 1 includes favorites information,
            // setting it to 2 searches for favorite records.
            $options['favorites'] = 2;
        }
        $options['my_items'] = false;
        if ( !empty($args['my_items']) ) {
            // TODO: When the real filters get in, change it so that this is just described as an additional filter.
            $options['my_items'] = $args['my_items'];
        }

        $fieldFilters = array();
        // Sort out the multi-module field filter
        if ( !empty($args['fields']) ) {
            if ( is_array($args['fields']) ) {
                // This one has multiple modules in it we need to split it up among all of the modules
                $fieldFilters = $args['fields'];
            } else  {
                // They want one filter across all modules
                $fieldFilters['_default'] = explode(',',$args['fields']);
            }
        } else {
            $fieldFilters['_default'] = '';
        }
        // Ensure date_modified and id are in the list of fields
        foreach ( $fieldFilters as $key => $fieldArray ) {
            if ( empty($fieldArray) ) {
                // Just allow the defaults to take over
                continue;
            }
            foreach ( array('id','date_modified') as $requiredField ) {
                if ( !in_array($requiredField,$fieldArray) ) {
                    $fieldFilters[$key][] = $requiredField;
                }
            }
        }

        $options['fieldFilters'] = $fieldFilters;


        return $options;
    }

    /**
     * This function is the global search
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @return array result set
     */
    public function globalSearch(ServiceBase $api, array $args) {
        $api->action = 'list';

        // This is required to keep the loadFromRow() function in the bean from making our day harder than it already is.
        $GLOBALS['disable_date_format'] = true;

        $options = $this->parseSearchOptions($api,$args);

        // determine the correct serach engine, don't pass any configs and fallback to the default search engine if the determiend one is down
        $searchEngine = SugarSearchEngineFactory::getInstance($this->determineSugarSearchEngine($api, $args, $options), array(), false);

        if ( $searchEngine instanceOf SugarSearchEngine) {
            $options['resortResults'] = true;
            $recordSet = $this->globalSearchSpot($api,$args,$searchEngine,$options);
            $sortByDateModified = true;
        } else {
            $recordSet = $this->globalSearchFullText($api,$args,$searchEngine,$options);
            $sortByDateModified = false;
        }


        return $recordSet;
    }

    /**
     * This function is used to determine the search engine to use
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @param $options array An array of options to pass through to the search engine, they get translated to the $searchOptions array so you can see exactly what gets passed through
     * @return string name of the Search Engine
     */
    protected function determineSugarSearchEngine(ServiceBase $api, array $args, array $options)
    {
        /*
            How to determine which Elastic Search
            1 - Not Portal
            2 - All Modules are full_text_search = true
            4 - not order by
        */

        $engine = SearchEngine::getInstance()->getEngine();
        $metaDataHelper = $engine->getMetaDataHelper();

        /*
         * If a module isn't FTS switch to spot search.  Global Search should be done with either the enabled modules
         * Using the new ServerInfo endpoint OR passing in a blank module list.
         */
        if(!empty($options['moduleList']))
        {
            foreach($options['moduleList'] AS $module)
            {
                //A module enabled in unified search but disabled in the new FTS (Elastic) search should also
                //use the local database search, i.e., return 'SugarSearchEngine' here.
                if (!$metaDataHelper->isModuleEnabled($module)) {
                    return 'SugarSearchEngine';
                }
            }
        }

        /*
         * Currently we cannot do an order by in FTS.  Thus any ordering must be done using the Spot Search
         */
        if(isset($options['orderBySetByApi']) && $options['orderBySetByApi'] == true) {
            return 'SugarSearchEngine';
        }

        // if the query is empty no reason to pass through FTS they want a normal list view.
        if(empty($args['q'])) {
            return 'SugarSearchEngine';
        }

        $fts = SugarSearchEngineFactory::getFTSEngineNameFromConfig();
        //everything is groovy for FTS, get the FTS Engine Name from the conig
        if(!empty($fts)) {
            return $fts;
        }
        return 'SugarSearchEngine';
    }

    /**
     * This function is used to hand off the global search to the FTS Search Emgine
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @param $searchEngine SugarSearchEngine The SugarSpot search engine created using the Factory in the caller
     * @param $options array An array of options to pass through to the search engine, they get translated to the $searchOptions array so you can see exactly what gets passed through
     * @return array Two elements, 'records' the list of returned records formatted through FormatBean, and 'next_offset' which will indicate to the user if there are additional records to be returned.
     */
    protected function globalSearchFullText(ServiceBase $api, array $args, SugarSearchEngineAbstractBase $searchEngine, array $options)
    {
        $api->action = 'list';
        $returnedRecords = array();

        // SugarSearchEngine uses moduleFilter instead of moduleList, pass it along
        $options['moduleFilter'] = empty($options['moduleList']) ? array() : $options['moduleList'];

        $results = $searchEngine->search($options['query'], $options['offset'], $options['limit'], $options);

        if (empty($results)) {
            return array('next_offset' => -1, 'records' => array());
        }

        // format results
        foreach ($results as $result) {

            $bean = $result->getBean();

            // if we can't get the bean skip it
            if (empty($bean)) {
                continue;
            }

            $module = $bean->module_dir;

            // Need to override the filter arg so that it looks like something formatBean expects
            if (!empty($options['fieldFilters'][$module])) {
                $moduleFields = $options['fieldFilters'][$module];
            } elseif (!empty($options['fieldFilters']['_default'])) {
                $moduleFields = $options['fieldFilters']['_default'];
            } else {
                $moduleFields = array();
            }

            if (!empty($moduleFields) && !in_array('id', $moduleFields)) {
                $moduleFields[] = 'id';
            }

            $moduleArgs['fields'] = implode(',', $moduleFields);
            $moduleArgs['erased_fields'] = $args['erased_fields']?? null;
            $formattedRecord = $this->formatBean($api, $moduleArgs, $bean);

            // add additional parameters expected to be returned
            $formattedRecord['_search']['score'] = $result->getScore();
            $formattedRecord['_search']['highlighted'] = $result->getHighlightedHitText();

            $returnedRecords[] = $formattedRecord;
        }

        // calculate next offset
        $total = $results->getTotalHits();
        if ($total > ($options['limit'] + $options['offset'])) {
            $nextOffset = $options['offset']+$options['limit'];
        } else {
            $nextOffset = -1;
        }

        return array('next_offset' => $nextOffset, 'records' => $returnedRecords);
    }

    /**
     * This function is used to hand off the global search to the built-in SugarSearchEngine (aka SugarSpot)
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @param $searchEngine SugarSearchEngine The SugarSpot search engine created using the Factory in the caller
     * @param $options array An array of options to pass through to the search engine, they get translated to the $searchOptions array so you can see exactly what gets passed through
     * @return array Two elements, 'records' the list of returned records formatted through FormatBean, and 'next_offset' which will indicate to the user if there are additional records to be returned.
     */
    public function globalSearchSpot(ServiceBase $api, array $args, $searchEngine, array $options)
    {


        $searchOptions = array(
            'modules'=>$options['moduleList'],
            'current_module'=>$options['primaryModule'],
            'return_beans'=>true,
            'my_items'=>$options['my_items'],
            'favorites'=>$options['favorites'],
            'orderBy'=>$options['orderBy'],
            'fields'=>$options['fieldFilters'],
            'selectFields'=>$options['selectFields'],
            'limitPerModule'=>$options['limitPerModule'],
            'allowEmptySearch'=>true,
            'distinct'=>'DISTINCT', // Needed until we get a query builder
            'return_beans'=>true,
            );

        if (isset($options['deleted'])) {
            $searchOptions['deleted'] = $options['deleted'];
        }

        $multiModule = false;
        if ( empty($options['moduleList']) || count($options['moduleList']) == 0 || count($options['moduleList']) > 1 ) {
            $multiModule = true;
        }

        if(empty($options['moduleList']))
        {
            $usa = new UnifiedSearchAdvanced();
            $moduleList = $usa->getUnifiedSearchModules();

            // get the module names [array keys]
            $moduleList = array_keys($moduleList);
            // filter based on User Access if Blank
            $ACL = new ACLController();
            // moduleList is passed by reference
            $ACL->filterModuleList($moduleList);
            $searchOptions['modules'] = $options['moduleList'] = $moduleList;
        }

        if (!empty($options['searchFields'])) {
            $customWhere = array();
            foreach ($options['moduleList'] as $module) {
                $seed = BeanFactory::newBean($module);
                $fields = array_keys($seed->field_defs);
                $existingfields = array_intersect($fields, $options['searchFields']);
                if (!empty($existingfields)) {
                    $customTable = $seed->get_custom_table_name();
                    $table = $seed->table_name;
                    foreach ($existingfields as $field) {
                        if (!isset($seed->field_defs[$field]['unified_search']) || $seed->field_defs[$field]['unified_search'] !== true) {
                            continue;
                        }
                        $prefix = $table;
                        if (isset($GLOBALS['dictionary'][$seed->object_name]['custom_fields'][$field])) {
                            $prefix = $customTable;
                        }
                        if (!isset($seed->field_defs[$field]['source']) || $seed->field_defs[$field]['source'] != 'non-db') {
                            $customWhere[$module][] = "{$prefix}.{$field} LIKE '". $seed->db->quote($options['query']) . "%'";
                        }
                    }
                    if (isset($customWhere[$module])) {
                        $searchOptions['custom_where_module'][$module] = '(' . implode(' OR ', $customWhere[$module]) . ')';
                    }
                }
            }
        }

        $offset = $options['offset'];
        // One for luck.
        // Well, actually it's so that we know that there are additional results
        $limit = $options['limit']+1;
        if ( $multiModule && $options['offset'] != 0 ) {
            // With more than one module, there is no way to do offsets for real, so we have to fake it.
            $limit = $limit+$offset;
            $offset = 0;
        }

        if ( !$multiModule ) {
            // It's not multi-module, the per-module limit should be the same as the master limit
            $searchOptions['limitPerModule'] = $limit;
        }

        if(isset($options['custom_select'])) {
            $searchOptions['custom_select'] = $options['custom_select'];
        }

        if(isset($options['custom_from'])) {
            $searchOptions['custom_from'] = $options['custom_from'];
        }

        if(isset($options['custom_where'])) {
            $searchOptions['custom_where'] = $options['custom_where'];
        }

        $searchOptions['erased_fields'] = $args['erased_fields']?? null;
        $results = $searchEngine->search($options['query'],$offset, $limit, $searchOptions);

        $returnedRecords = array();

        $api->action = 'list';

        foreach ( $results as $module => $moduleResults ) {
            if ( !is_array($moduleResults['data']) ) {
                continue;
            }
            $moduleArgs = $args;
            // Need to override the filter arg so that it looks like something formatBean expects
            if ( !empty($options['fieldFilters'][$module]) ) {
                $moduleFields = $options['fieldFilters'][$module];
            } else if ( !empty($options['fieldFilters']['_default']) ) {
                $moduleFields = $options['fieldFilters']['_default'];
            } else {
                $moduleFields = array();
            }
            $moduleArgs['fields'] = implode(',',$moduleFields);
            $moduleArgs['erased_fields'] = $args['erased_fields']?? null;
            foreach ( $moduleResults['data'] as $record ) {
                $formattedRecord = $this->formatBean($api,$moduleArgs,$record);
                $formattedRecord['_module'] = $module;
                // The SQL based search engine doesn't know how to score records, so set it to 1
                $formattedRecord['_search']['score'] = 1.0;
                $returnedRecords[] = $formattedRecord;
            }
        }

        if ( $multiModule ) {
            // Need to re-sort the results because the DB search engine clumps them together per-module
            $this->resultSetSortData = $options['orderByArray'];
            usort($returnedRecords,array($this,'resultSetSort'));
        }

        if ( $multiModule && $options['offset'] != 0 ) {
            // The merged module mess leaves us in a bit of a pickle with offsets and limits
            if ( count($returnedRecords) > ($options['offset']+$options['limit']) ) {
                $nextOffset = $options['offset']+$options['limit'];
            } else {
                $nextOffset = -1;
            }
            $returnedRecords = array_slice($returnedRecords,$options['offset'],$options['limit']);
        } else {
            // Otherwise, offsets and limits should work.
            if ( count($returnedRecords) > $options['limit'] ) {
                $nextOffset = $options['offset']+$options['limit'];
            } else {
                $nextOffset = -1;
            }
            $returnedRecords = array_slice($returnedRecords,0,$options['limit']);
        }

        if ( $options['offset'] === 'end' ) {
            $nextOffset = -1;
        }

        return array('next_offset'=>$nextOffset,'records'=>$returnedRecords);
    }

    protected $resultSetSortData;
    /**
     * This function is used to resort the results that come out of SpotSearch, they are clumped per module and we need them sorted by potentially multiple columns.
     * For reference on how this function reacts, look at the PHP manual for usort()
     */
    public function resultSetSort($left, $right) {
        $greaterThan = 0;
        foreach ( $this->resultSetSortData as $key => $isAscending ) {
            $greaterThan = 0;
            if ( isset($left[$key]) != isset($right[$key]) ) {
                // One of them is set, the other one isn't
                // If the left one is set, then it is greater than the right one
                $greaterThan = (isset($left[$key])?1:-1);
            } else if ( !isset($left[$key]) ) {
                // Since the isset matches, and the left one isn't set, neither of them are set
                $greaterThan = 0;
            } else if ( $left[$key] == $right[$key] ) {
                $greaterThan = 0;
            } else {
                $greaterThan = ($left[$key]>$right[$key]?1:-1);
            }

            // Figured out if the left is greater than the right, now time to act
            if ( $greaterThan != 0 ) {
                if ( $isAscending ) {
                    return $greaterThan;
                } else {
                    return -$greaterThan;
                }
            }
        }
    }
}
