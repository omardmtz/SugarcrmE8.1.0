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


use \Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use \Sugarcrm\Sugarcrm\Elasticsearch\Query\QueryBuilder;
use \Sugarcrm\Sugarcrm\Elasticsearch\Query\KBFilterQuery;

class KBContentsFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        return array(
            'filterModuleGet' => array(
                'reqType' => 'GET',
                'path' => array('KBContents', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Lists filtered records.',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'filterModuleAll' => array(
                'reqType' => 'GET',
                'path' => array('KBContents'),
                'pathVars' => array('module'),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'List of all records in this module',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     * Add filter to return only active revisions for filterApi.
     */
    public function filterListSetup(ServiceBase $api, array $args, $acl = 'list')
    {
        list($args, $q, $options, $seed) = parent::filterListSetup($api, $args, $acl);

        $q->where()->equals('active_rev', '1');

        if (!empty($args['mostUseful'])) {
            $q->select()->fieldRaw('(kbcontents.useful - kbcontents.notuseful)', 'mu');
            $orderBy = new SugarQuery_Builder_Orderby($q, 'DESC');
            $orderBy->addRaw('mu');
            array_unshift($q->order_by, $orderBy);
        }

        return array($args, $q, $options, $seed);
    }

    /**
     * {@inheritDoc}
     * If we want to filter by KB Article Body let's do it via ElasticSearch.
     */
    public function filterList(ServiceBase $api, array $args, $acl = 'list')
    {
        // If we have 'Body containing/excluding these words' filter, separate it from the rest filters, so that
        // it is searched via Elastic and the rest is searched via standard engine.
        $bodySearchFilter = array();
        $modifiedMainFilter = array();

        if (isset($args['filter'])) {
            foreach ($args['filter'] as $filter) {
                if (array_key_exists('kbdocument_body', $filter)) {
                    $bodySearchFilter[] = $filter['kbdocument_body'];
                } else {
                    $modifiedMainFilter[] = $filter;
                }
            }
            $args['filter'] = $modifiedMainFilter;
        }

        // We've got 3 cases:
        // 1. Composite filter with KBBody - retrieve all records found via Elastic & augment filter with their ids;
        // 2. Filter without KBBody - just use standard Sugar filtering mechanism.
        if ($bodySearchFilter) {
            $ids = $this->filterByContainingExcludingWords($api, $args, $bodySearchFilter);
            $ids = !empty($ids) ? $ids : array('');
            $args['filter'][] = array('id' => array('$in' => $ids));
        }
        return parent::filterList($api, $args, $acl);
    }

    /**
     * Search for 'containing/excluding these words' filter via Elastic.
     * @param ServiceBase $api
     * @param array $args
     * @param array $filterArgs filter args that contain only 'containing/excluding these words' filter
     * @return array
     */
    protected function filterByContainingExcludingWords(ServiceBase $api, array $args, $filterArgs)
    {
        $options = $this->parseArguments($api, $args);
        $bean = BeanFactory::newBean($args['module']);
        //We don't use ordering because it will be done with database search.
        $orderBy = array();

        $operators = array();
        foreach ($filterArgs as $filterDef) {
            $key = key($filterDef);
            $values = preg_split('/[\s[:punct:]]+/', $filterDef[$key]);
            $operators[$key] = !isset($operators[$key]) ? array() : $operators[$key];
            $operators[$key] = $operators[$key] + $values;
        }

        $builder = $this->getElasticQueryBuilder($args, $options);
        $ftsFields = ApiHelper::getHelper($api, $bean)->getElasticSearchFields(array('kbdocument_body'));

        $query = new KBFilterQuery();
        $query->setBean($bean);
        $query->setFields($ftsFields);
        $query->setTerm($operators);

        //set the filter
        $filterField = ApiHelper::getHelper($api, $bean)->getElasticSearchFields(array('active_rev'));
        $filter = $query->createFilter(array($filterField['active_rev'][0] => 1));
        $builder
            ->addFilter($filter);

        //set sort
        $builder
            ->setSort($orderBy);

        // set query
        $builder
            ->setQuery($query);

        $resultSet = $builder->executeSearch();

        // Return all ids for further filtering.
        $data = array();
        foreach ($resultSet as $result) {
            $data[] = $result->getId();
        }
        return $data;
    }

    /**
     * Get configured Elastic search builder.
     * @param array $args The arguments array passed in from the API.
     * @param $options array An array with the options limit, offset, fields and order_by set
     * @return QueryBuilder
     */
    protected function getElasticQueryBuilder(array $args, array $options)
    {
        global $current_user;

        $engineContainer = SearchEngine::getInstance()->getEngine()->getContainer();
        $builder = new QueryBuilder($engineContainer);
        $builder
            ->setUser($current_user)
            ->setModules(array($args['module']))
            ->setOffset($options['offset'])
            ->setLimit($options['limit']);

        return $builder;
    }
}
