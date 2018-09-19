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

/**
 * Class KBSDuplicateCheckApi
 * Check duplicates for KBContents.
 */
class KBSDuplicateCheckApi extends SugarListApi
{
    public function registerApiRest()
    {
        return array(
            'duplicateCheck' => array(
                'reqType' => 'POST',
                'path' => array('KBContents','duplicateCheck'),
                'pathVars' => array('module',''),
                'method' => 'checkForDuplicates',
                'shortHelp' => 'Check for duplicate records within a module',
                'longHelp' => 'include/api/help/module_duplicatecheck_post_help.html',
            ),
        );
    }

    //FIXME It's better to use DuplicateCheckStrategy, but impossible due architectural limitations.
    /**
     * Using the appropriate duplicate check service, search for duplicates in the system
     * @see KBContentsApi::relatedDocuments
     * @param ServiceBase $api
     * @param array $args
     * @throws SugarApiExceptionInvalidParameter
     * @returns array
     */
    public function checkForDuplicates(ServiceBase $api, array $args)
    {
        $bean = BeanFactory::newBean($args['module']);
        if (!$bean->ACLAccess('view') || !$bean->ACLAccess('read')) {
            return;
        }
        $options = $this->parseArguments($api, $args);
        $errors = ApiHelper::getHelper($api, $bean)->populateFromApi($bean, $args, $options);
        if ($errors !== true) {
            $displayErrors = var_export($errors, true);
            throw new SugarApiExceptionInvalidParameter(
                "Unable to run duplicate check. There were validation errors on the submitted data: $displayErrors"
            );
        }

        $engineContainer = SearchEngine::getInstance()->getEngine()->getContainer();
        $builder = new QueryBuilder($engineContainer);
        $builder
            ->setUser($GLOBALS['current_user'])
            ->setModules(array($args['module']))
            ->setOffset($options['offset'])
            ->setLimit($options['limit']);
        $ftsFields = ApiHelper::getHelper($api, $bean)->getElasticSearchFields(array('name', 'kbdocument_body'));

        //set the query using more_like_this query
        $query = new KBQuery();
        $query->setBean($bean);
        $query->setFields($ftsFields);
        $builder->setQuery($query);

        //set the filter
        $filter = $query->createFilter(true);
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
}
