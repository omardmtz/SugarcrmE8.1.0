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
use \Sugarcrm\Sugarcrm\Elasticsearch\Query\KBQuery;


class KBContentsApi extends SugarListApi
{
    public function registerApiRest()
    {
        return array(
            'related_documents' => array(
                'reqType' => 'GET',
                'path' => array('KBContents', '?', 'related_documents'),
                'pathVars' => array('module', 'record'),
                'method' => 'relatedDocuments',
                'shortHelp' => 'Get related documents for current record.',
                'longHelp' => '',
            ),
            //disable KBDocuments, KBArticles through API
            'disableKBDocuments1' => array(
                'reqType' => '?',
                'path' => array('KBDocuments'),
                'pathVars' => array('module'),
                'method' => 'disableApi',
                'extraScore' => 1,
                'shortHelp' => 'Disable KBDocuments',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                ),
            ),
            'disableKBDocuments2' => array(
                'reqType' => '?',
                'path' => array('KBDocuments', '?'),
                'pathVars' => array('module', ''),
                'extraScore' => 1,
                'method' => 'disableApi',
                'shortHelp' => 'Disable KBDocuments',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                ),
            ),
            'disableKBArticles1' => array(
                'reqType' => '?',
                'path' => array('KBArticles'),
                'pathVars' => array('module'),
                'method' => 'disableApi',
                'extraScore' => 1,
                'shortHelp' => 'Disable KBArticles',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                ),
            ),
            'disableKBArticles2' => array(
                'reqType' => '?',
                'path' => array('KBArticles', '?'),
                'pathVars' => array('module', ''),
                'extraScore' => 1,
                'method' => 'disableApi',
                'shortHelp' => 'Disable KBArticles',
                'exceptions' => array(
                    'SugarApiExceptionNotFound',
                ),
            ),
        );
    }

    /**
     * Return related document using "more like this" query.
     *
     * @param ServiceBase $api The API class of the request.
     * @param array $args The arguments array passed in from the API.
     * @return array 'records' the list of returned records formatted through FormatBean, and 'next_offset'
     * which will indicate to the user if there are additional records to be returned.
     */
    public function relatedDocuments(ServiceBase $api, array $args)
    {
        $targetBean = BeanFactory::getBean($args['module'], $args['record']);
        if (!$targetBean->ACLAccess('view')) {
            return;
        }
        $options = $this->parseArguments($api, $args);

        $builder = $this->getElasticQueryBuilder($args, $options);
        $ftsFields = ApiHelper::getHelper($api, $targetBean)->getElasticSearchFields(array('name', 'kbdocument_body'));

        //set the query using more_like_this query
        $query = new KBQuery();
        $query->setBean($targetBean);
        $query->setFields($ftsFields);
        $builder->setQuery($query);

        //set the filter
        $filter = $query->createFilter(false);
        $builder->addFilter($filter);

        $resultSet = $builder->executeSearch();

        $returnedRecords = array();

        foreach ($resultSet as $result) {
            $record = BeanFactory::retrieveBean($result->getType(), $result->getId());
            if (!$record) {
                continue;
            }
            $formattedRecord = $this->formatBean($api, $args, $record);
            $formattedRecord['_module'] = $result->getType();
            $returnedRecords[] = $formattedRecord;
        }

        if ($resultSet->getTotalHits() > ($options['limit'] + $options['offset'])) {
            $nextOffset = $options['offset'] + $options['limit'];
        } else {
            $nextOffset = -1;
        }

        return array('next_offset' => $nextOffset, 'records' => $returnedRecords);
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

    /**
     * Disable modules through RestService.
     * @param ServiceBase $api Service to work with.
     * @param mixed $args Parameters came from Service.
     * @throws SugarApiExceptionNotFound Thrown always.
     */
    public function disableApi(ServiceBase $api, array $args)
    {
        throw new SugarApiExceptionNotFound("The requested module is disabled in API.");
    }
}
