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


class FilterApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'filterModuleGet' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Lists filtered records.',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterList and filterListSetup
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup, getPredefinedFilterById, and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'filterModuleAll' => array(
                'reqType' => 'GET',
                'path' => array('<module>'),
                'pathVars' => array('module'),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'List of all records in this module',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterList and filterListSetup
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup, getPredefinedFilterById, and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'filterModuleAllCount' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'count'),
                'pathVars' => array('module', ''),
                'jsonParams' => array('filter'),
                'method' => 'getFilterListCount',
                'shortHelp' => 'List of all records in this module',
                'longHelp' => 'include/api/help/module_filter_get_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterListSetup and getPredefinedFilterById
                    'SugarApiExceptionNotAuthorized',
                    // Thrown in filterListSetup
                    'SugarApiExceptionInvalidParameter',
                ),
            ),
            'filterModulePost' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'filterList',
                'shortHelp' => 'Lists filtered records.',
                'longHelp' => 'include/api/help/module_filter_post_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterList and filterListSetup
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup, getPredefinedFilterById, and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
            'filterModulePostCount' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'filter', 'count'),
                'pathVars' => array('module', '', ''),
                'method' => 'filterListCount',
                'shortHelp' => 'Lists filtered records.',
                'longHelp' => 'include/api/help/module_filter_post_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterListSetup and getPredefinedFilterById
                    'SugarApiExceptionNotAuthorized',
                    // Thrown in filterListSetup
                    'SugarApiExceptionInvalidParameter'
                ),
            ),
            'filterModuleCount' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'filter', 'count'),
                'pathVars' => array('module', '', ''),
                'method' => 'getFilterListCount',
                'shortHelp' => 'Lists filtered records.',
                'longHelp' => 'include/api/help/module_filter_post_help.html',
                'exceptions' => array(
                    // Thrown in getPredefinedFilterById
                    'SugarApiExceptionNotFound',
                    'SugarApiExceptionError',
                    // Thrown in filterListSetup
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionInvalidParameter'
                ),
            ),
        );
    }

    protected static $isFavorite = false;

    protected $defaultLimit = 20; // How many records should we show if they don't pass up a limit
    protected $defaultOrderBy = array(
        array('date_modified', 'DESC'),
    );

    protected static $current_user;

    /**
     * List of fields that are mandatory for all filters
     * @var array
     */
    protected static $mandatory_fields = array(
        // id and date_modified should always be in the response
        'id',
        'date_modified',
        // user fields are needed for ACLs since they check owners
        'assigned_user_id',
        'created_by',
        // Locked fields are necessary to enforce data integrity for processes on
        // on the client
        'locked_fields',
    );

    public function __construct()
    {
        global $current_user;
        self::$current_user = $current_user;
    }

    protected function parseArguments(ServiceBase $api, array $args, SugarBean $seed = null)
    {
        $options = array();

        // Set up the defaults
        $options['limit'] = $this->defaultLimit;
        $options['offset'] = 0;
        $options['add_deleted'] = true;

        if (!empty($args['max_num'])) {
            $options['limit'] = (int) $args['max_num'];
        }

        $options['limit'] = $this->checkMaxListLimit($options['limit']);

        if (!empty($args['deleted'])) {
            $options['add_deleted'] = false;
        }

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset'] = (int) $args['offset'];
            }
        }

        $orderBy = $this->getOrderByFromArgs($args, $seed);
        if ($orderBy) {
            $options['order_by'] = $orderBy;
        }

        // Set $options['module'] so that runQuery can create beans of the right
        // type.
        if (!empty($seed)) {
            $options['module'] = $seed->module_name;
        }

        // Set the list of fields to be used in the select.
        $options['select'] = $this->getFieldsFromArgs($api, $args, $seed, 'view', $options['display_params']);

        $options['select'] = array_unique(
            array_merge($options['select'], self::$mandatory_fields)
        );

        // Some modules have fields that are composed of or require other fields
        // Add those in now so that they can be selected and set onto the bean
        // so formatting can use them. This is necessary for file type fields.
        if (!empty($seed)) {
            foreach ($seed->field_defs as $field => $def) {
                if (isset($def['name']) && in_array($def['name'], $options['select']) && isset($def['fields'])) {
                    foreach ($def['fields'] as $field) {
                        if (!in_array($field, $options['select'])) {
                            $options['select'][] = $field;
                        }
                    }
                }
            }

            foreach ($options['select'] as $field) {
                if (isset($seed->field_defs[$field]) && !empty($seed->field_defs[$field]['relate_collection'])) {
                    $options['relate_collections'][$field] = $seed->field_defs[$field];
                }
            }
        }

        $options['action'] = $api->action;

        if (!empty($args['erased_fields'])) {
            $options['erased_fields'] = true;
        }

        return $options;
    }

    /**
     * Creates internal representation of ORDER BY expression from API arguments
     *
     * Overrides parent implementation in order to convert the value to the structure
     * which is currently used in Filter API
     *
     * {@inheritDoc}
     */
    protected function getOrderByFromArgs(array $args, SugarBean $seed = null)
    {
        $orderBy = parent::getOrderByFromArgs($args, $seed);
        $converted = array();
        foreach ($orderBy as $field => $direction) {
            $converted[] = array($field, $direction ? 'ASC' : 'DESC');
        }

        return $converted;
    }

    /**
     * Retrieve a predefined filter by the given ID
     *
     * @param ServiceBase $api The API class of the request.
     * @param string $filterId ID of the filter to retrieve.
     * @return array The (possibly empty) filter as an associative array.
     * @throws SugarApiExceptionError If JSON decoding failed or json_decode
     *   returned something other than an array.
     * @throws SugarApiExceptionNotFound If we cannot find the filter.
     * @throws SugarApiExceptionNotAuthorized If we do not have permission to
     *   load the filter.
     */
    private function getPredefinedFilterById(ServiceBase $api, $filterId)
    {
        $predefinedFilter = $this->loadBean(
            $api,
            array(
                'module' => 'Filters',
                'record' => $filterId,
            )
        );

        if (empty($predefinedFilter->filter_definition)) {
            LoggerManager::getLogger()->warn('Filter ' . $predefinedFilter->id . ' has no definition');
            return array();
        }

        // Try to decode the filter. Note that the expectation is that
        // json_decode returns an array based on the assumption that filters are
        // encoded in the database as objects.
        $decodedFilter = json_decode($predefinedFilter->filter_definition, true);
        if (is_array($decodedFilter)) {
            return $decodedFilter;
        }
        $jsonErrorMessage = 'Decoding definition for filter ' . $predefinedFilter->id . ' failed';
        LoggerManager::getLogger()->error($jsonErrorMessage);
        throw new SugarApiExceptionError($jsonErrorMessage);
    }

    /**
     * Preprocess the args array to set filter options.
     *
     * @param ServiceBase $api The REST API object.
     * @param array $args REST API arguments.
     * @param string $acl Which type of ACL to check.
     * @return array An array containing the modified args array, a query object
     *   with all the filters applied, the modified options array, and a
     *   SugarBean for the chosen module.
     * @throws SugarApiExceptionError If retrieving a predefined filter failed.
     * @throws SugarApiExceptionInvalidParameter If any arguments are invalid.
     * @throws SugarApiExceptionNotAuthorized If we lack ACL access.
     */
    public function filterListSetup(ServiceBase $api, array $args, $acl = 'list')
    {
        $seed = BeanFactory::newBean($args['module']);

        if (!$seed->ACLAccess($acl, array('source' => 'filter_api'))) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: ' . $args['module']);
        }

        $options = $this->parseArguments($api, $args, $seed);

        // In case the view parameter is set, reflect those fields in the
        // fields argument as well so formatBean only takes those fields
        // into account instead of every bean property.
        if (!empty($args['view'])) {
            $args['fields'] = $options['select'];
        }

        $q = self::getQueryObject($seed, $options);

        // Relate collections should not be in the select clause of the query
        // since we have trouble doing group bys on certain databases. We should
        // be getting the relate collection values later anyways.
        if (isset($options['relate_collections'])) {
            $options = $this->removeRelateCollectionsFromSelect($options);
        }

        if (empty($args['filter_id'])) {
            $predefinedFilter = array();
        } else {
            $predefinedFilter = $this->getPredefinedFilterById($api, $args['filter_id']);
            unset($args['filter_id']);
        }

        // FIXME TY-1821: Empty filter definitions are currently supported to
        // maintain backward compatibility on v10. This behaviour will change on
        // one of the upcoming API versions.
        if (isset($args['filter']) && $args['filter'] == '') {
            // Remove filter if it is an empty string.
            unset($args['filter']);
        }

        // filter must be an array
        if (isset($args['filter']) && !is_array($args['filter'])) {
            throw new SugarApiExceptionInvalidParameter('Unexpected filter type ' . gettype($args['filter']) . '.');
        }

        if (isset($args['filter'])) {
            $filterDefinition = $args['filter'];
            $args['filter'] = array_merge($predefinedFilter, $filterDefinition);
        } else {
            $args['filter'] = $predefinedFilter;
        }

        static::addFilters($args['filter'], $q->where(), $q);

        if (!empty($args['my_items'])) {
            static::addOwnerFilter($q, $q->where(), '_this');
        }

        if (!empty($args['favorites'])) {
            self::$isFavorite = true;
            static::addFavoriteFilter($q, $q->where(), '_this', 'INNER');
        }

        if (!sizeof($q->order_by)) {
            self::addOrderBy($q, $this->defaultOrderBy);
        }
        return array($args, $q, $options, $seed);
    }

    /**
     * Returns the records for the module and filter provided.
     *
     * @param ServiceBase $api The REST API object.
     * @param array $args REST API arguments.
     * @param string $acl Which type of ACL to check.
     * @return array The REST response as a PHP array.
     * @throws SugarApiExceptionError If retrieving a predefined filter failed.
     * @throws SugarApiExceptionInvalidParameter If any arguments are invalid.
     * @throws SugarApiExceptionNotAuthorized If we lack ACL access.
     */
    public function filterList(ServiceBase $api, array $args, $acl = 'list')
    {
        if (!empty($args['q'])) {
            if (!empty($args['filter']) || !empty($args['filter_id']) || !empty($args['deleted'])) {
                // These flags can be used with the filter API, but not with the search API
                throw new SugarApiExceptionInvalidParameter();
            }
            // We need to use unified search for this for compatibilty with Nomad
            $search = new UnifiedSearchApi();
            $args['module_list'] = $args['module'];
            return $search->globalSearch($api, $args);
        }

        $api->action = 'list';
        list($args, $q, $options, $seed) = $this->filterListSetup($api, $args, $acl);

        return $this->runQuery($api, $args, $q, $options, $seed);
    }

    /**
     * Returns the number of records for the module and filter provided:
     *
     * Example:
     *     {"record_count": 50}
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array The number of filtered/unfiltered records for the module provided.
     * @throws SugarApiExceptionError If retrieving a predefined filter failed.
     * @throws SugarApiExceptionInvalidParameter if any of the parameters are
     *  invalid.
     * @throws SugarApiExceptionNotAuthorized if we lack ACL access.
     */
    public function getFilterListCount(ServiceBase $api, array $args)
    {
        $api->action = 'list';

        /** @var SugarQuery $q */
        list(, $q) = $this->filterListSetup($api, $args);

        $q->select->selectReset()->setCountQuery();
        $q->orderByReset();
        $q->limit = null;

        $stmt = $q->compile()->execute();
        $count = (int) $stmt->fetchColumn();

        return array(
            'record_count' => $count,
        );
    }

    /**
     * Returns the number of records for the module and filter provided:
     *
     * Example:
     *     {'record_count': '50'}
     *
     * This method is now deprecated. Use getFilterListCount instead.
     *
     * @deprecated Since 7.7.0. Will be removed in 7.9.0.
     * @param ServiceBase $api
     * @param array $args
     * @return Object The number of filtered/unfiltered records for the module
     *   provided.
     * @throws SugarApiExceptionError If retrieving a predefined filter failed.
     * @throws SugarApiExceptionInvalidParameter if any of the parameters are
     *  invalid.
     * @throws SugarApiExceptionNotAuthorized if we lack ACL access.
     */
    public function filterListCount(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->fatal('POST <module>/filter/count has been deprecated as of 7.7.0. ' .
            'Please use the equivalent GET endpoint instead.');

        return $this->getFilterListCount($api, $args);
    }

    protected static function getQueryObject(SugarBean $seed, array $options)
    {
        if (empty($options['select'])) {
            $options['select'] = self::$mandatory_fields;
        }

        $queryOptions = array(
            'add_deleted' => !isset($options['add_deleted']) || $options['add_deleted'],
        );

        if ($queryOptions['add_deleted'] == false) {
            $options['select'][] = 'deleted';
        }

        if (!empty($options['erased_fields'])) {
            $queryOptions['erased_fields'] = true;
        }

        $q = static::newSugarQuery(DBManagerFactory::getInstance('listviews'));
        $q->from($seed, $queryOptions);
        $q->distinct(false);
        $fields = array();
        foreach ($options['select'] as $field) {
            // Skip the related bean options since related collections
            // are expected to be handled later, e.g. FilterApi for Tags
            if (isset($seed->field_defs[$field]['relate_collection']) &&
                $seed->field_defs[$field]['relate_collection']) {
                continue;
            }

            // FIXME: convert this to vardefs too?
            if ($field == 'my_favorite') {
                if (self::$isFavorite) {
                    $joinType = 'INNER';
                } else {
                    $joinType = 'LEFT';
                }
                $fjoin = $q->join('favorites', array('joinType' => $joinType));
                $fields[] = array($fjoin->joinName() . '.id', 'my_favorite');
                continue;
            }

            // fields that aren't in field defs are removed, since we don't know
            // what to do with them
            if (isset($seed->field_defs[$field]['type'])) {
                $sf = SugarFieldHandler::getSugarField($seed->field_defs[$field]['type']);
                $sf->addFieldToQuery($field, $fields);
            }
        }

        $q->select($fields);

        if (!empty($options['order_by'])) {
            self::addOrderBy($q, $options['order_by']);
        }

        // nagative limit means no limit
        if ($options['limit'] >= 0) {
            // Add an extra record to the limit so we can detect if there are more records to be found
            $q->limit($options['limit'] + 1);
        }
        $q->offset($options['offset']);

        return $q;
    }

    /**
     * Adds order by to query
     * @param SugarQuery $q
     * @param $orderByOption
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionNotAuthorized
     */
    protected static function addOrderBy(SugarQuery $q, array $orderByOption)
    {
        foreach ($orderByOption as $orderBy) {
            // ID and date_modified are used to give some order to the system
            if ($orderBy[0] != 'date_modified' && $orderBy[0] != 'id') {
                self::verifyField($q, $orderBy[0]);
            }
            $q->orderBy($orderBy[0], $orderBy[1]);
        }
    }

    /**
     * Populate related beans from data array.
     *
     * @param SugarBean $bean
     * @param array $data
     */
    protected function populateRelatedFields(SugarBean $bean, $data)
    {
        $relates = array();
        // fill in related rows data by field
        foreach ($data as $key => $value) {
            if (($split = strpos($key, '__')) > 0) {
                $relates[substr($key, 0, $split)][] = substr($key, $split + 2);
            }
        }

        foreach ($bean->field_defs as $field => $fieldDef) {
            if (in_array($fieldDef['type'], $bean::$relateFieldTypes)
                && (!empty($fieldDef['link']) || !empty($fieldDef['module']))) {
                if (empty($data[$field]) && empty($relates[$field])) {
                    continue;
                }

                if (!empty($fieldDef['link'])) {
                    $rbean = $bean->getRelatedBean($fieldDef['link']);
                } else {
                    $rbean = BeanFactory::newBean($fieldDef['module']);
                }

                if (empty($rbean)) {
                    continue;
                }

                if (!empty($data[$field])) {
                    if (empty($fieldDef['rname'])) {
                        LoggerManager::getLogger()->fatal("Field $field has invalid metadata, " .
                            'has source of relate but is missing rname');
                        continue;
                    }
                    // we have direct data - populate it
                    $rbean->populateFromRow(
                        array($fieldDef['rname'] => $data[$field]),
                        true
                    );
                } else {
                    if (empty($relates[$field])) {
                        continue;
                    }

                    $reldata = array();
                    foreach ($relates[$field] as $relfield) {
                        $reldata[$relfield] = $data["{$field}__{$relfield}"];
                    }
                    if (!empty($reldata)) {
                        $rbean->populateFromRow($reldata, true);
                    }
                    if (empty($fieldDef['link'])) {
                        $bean->related_beans[$fieldDef['name']] = $rbean;
                    }
                }

                if (empty($rbean->id) && !empty($fieldDef['id_name']) && !empty($data[$fieldDef['id_name']])) {
                    $rbean->id = $data[$fieldDef['id_name']];
                }
            }
        }
        // Call some data fillings for the bean
        foreach ($bean->related_beans as $rbean) {
            if (empty($rbean->id)) {
                continue;
            }

            $rbean->check_date_relationships_load();
            // $rbean->fill_in_additional_list_fields();
            if ($rbean->hasCustomFields()) {
                $rbean->custom_fields->fill_relationships();
            }
            $rbean->call_custom_logic('process_record');
        }
    }

    protected function runQuery(ServiceBase $api, array $args, SugarQuery $q, array $options, SugarBean $seed = null)
    {
        $seed->call_custom_logic('before_filter', array($q, $options));

        if (empty($args['fields'])) {
            $fields = array();
        } else {
            $fields = $options['select'];
        }

        $queryOptions = array(
            'returnRawRows' => true,
            'compensateDistinct' => true,
        );
        $fetched = $seed->fetchFromQuery($q, $fields, $queryOptions);

        list($beans, $rows, $distinctCompensation) = $this->parseQueryResults($fetched);

        $data = array();
        $data['next_offset'] = -1;

        // Get the related bean options to be able to handle related collections, like
        // in tags. Do this early, before beans in the collection are mutated
        $rcOptions = $this->getRelatedCollectionOptions($beans, $fields);
        $rcBeans = $this->runRelateCollectionQuery($beans, $rcOptions);

        // 'Cause last_viewed_date is an alias (not a real field), we need to
        // temporarily store its values and append it later to each recently
        // viewed record
        $lastViewedDates = array();
        $db = DBManagerFactory::getInstance();

        $i = $distinctCompensation;
        foreach ($beans as $bean_id => $bean) {
            if ($i == $options['limit']) {
                if (count($beans) > $options['limit']) {
                    unset($beans[$bean_id]);
                }
                $data['next_offset'] = (int) ($options['limit'] + $options['offset']);
                continue;
            }
            $i++;

            if (isset($rows[$bean_id]['last_viewed_date'])) {
                $lastViewedDates[$bean_id] = $db->fromConvert($rows[$bean_id]['last_viewed_date'], 'datetime');
            }

            $this->populateRelatedFields($bean, $rows[$bean_id]);
        }

        if (!empty($options['relate_collections'])) {
            // If there is no module set in the options array set the options
            // module to the args module
            if (!isset($options['module'])) {
                $options['module'] = $args['module'];
            }

            // Put all relate collection beans together so that parent beans and
            // relate beans all have a chance to load their relate collections
            // from memory
            $options['rc_beans'] = array_merge($this->runRelateCollectionQuery($beans, $options), $rcBeans);
        }

        $data['records'] = $this->formatBeans($api, $args, $beans, $options);

        if (!empty($lastViewedDates) && !empty($data['records'])) {
            global $timedate;

            // Append _last_viewed_date to each recently viewed record
            foreach ($data['records'] as &$record) {
                if (isset($lastViewedDates[$record['id']])) {
                    $record['_last_viewed_date'] = $timedate->asIso($timedate->fromDb($lastViewedDates[$record['id']]));
                }
            }
        }

        return $data;
    }

    /**
     * Run additional queries to find all the related records pointed to by relate_collection fields.
     *
     * @param array $beans
     * @param array $options
     * @return array
     */
    protected function runRelateCollectionQuery(array $beans, array $options)
    {
        $rc_beans = array();

        // Sanity check, just to make sure things are kosher
        if (empty($beans) || empty($options['relate_collections'])) {
            return $rc_beans;
        }

        // Grab the string of bean_ids for use in the IN clause, making sure to
        // quote them according their own DB
        $bean_ids = array_keys($beans);
        array_walk($bean_ids, function (&$val, $key, $db) {
            $val = $db->quoted($val);
        }, DBManagerFactory::getInstance());
        $bean_ids = implode(",", $bean_ids);

        foreach ($options['relate_collections'] as $name => $def) {
            // Parent bean
            $bean = BeanFactory::newBean($options['module']);

            // Related bean
            $relate_bean = BeanFactory::newBean($def['module']);

            // If the related bean has the necessary method to get related records
            // then call it
            if (is_callable(array($relate_bean, 'getRelatedModuleRecords'))) {
                $rc_beans[$name] = $relate_bean->getRelatedModuleRecords($bean, $bean_ids);
            } else {
                LoggerManager::getLogger()->fatal('Field is a relate collection, ' .
                    'but associated module does not have function getRelatedModuleRecords');
            }
        }

        return $rc_beans;
    }

    /**
     * Parse fetched result set as returned by SugarBean::fetchFromQuery.
     * Besides an array of beans some additional parameters are passed
     * which we want to abstract from it and cleanup the actually array.
     *
     * @param array $fetched Result set from SugarBean::fetchFromQuery
     * @return array
     */
    protected function parseQueryResults(array $fetched)
    {
        $rows = array();
        $distinctCompensation = 0;

        if (isset($fetched['_rows'])) {
            $rows = $fetched['_rows'];
            unset($fetched['_rows']);
        }

        if (isset($fetched['_distinctCompensation'])) {
            $distinctCompensation = $fetched['_distinctCompensation'];
            unset($fetched['_distinctCompensation']);
        }

        return array($fetched, $rows, $distinctCompensation);
    }

    /**
     * Verify that the passed field is correct
     *
     * @param SugarQuery $q
     * @param string $field
     * @return bool
     * @throws SugarApiExceptionInvalidParameter
     */
    protected static function verifyField(SugarQuery $q, $field)
    {
        $ret = array();
        if (strpos($field, '.')) {
            // It looks like it's a related field that it's searching by
            list($linkName, $field) = explode('.', $field);

            $q->from->load_relationship($linkName);
            if (empty($q->from->$linkName)) {
                throw new SugarApiExceptionInvalidParameter("Invalid link $linkName for field $field");
            }

            if ($q->from->$linkName->getType() == 'many') {
                // FIXME TY-1192: we have a problem here: we should allow 'many' links for related to match against
                // parent object but allowing 'many' in other links may lead to duplicates. So for now we allow 'many'
                // but we should figure out how to find if 'many' is permittable or not.
                // throw new SugarApiExceptionInvalidParameter("Cannot use condition against multi-link $linkName");
            }

            $join = $q->join($linkName, array('joinType' => 'LEFT'));
            $table = $join->joinName();
            $ret['field'] = "$table.$field";

            $bean = $q->getTableBean($table);
            if (empty($bean)) {
                $bean = $q->getTableBean($linkName);
            }
            if (empty($bean) && $q->getFromBean() && $q->getFromBean()->$linkName) {
                $bean = BeanFactory::newBean($q->getFromBean()->$linkName->getRelatedModuleName());
            }
            if (empty($bean)) {
                throw new SugarApiExceptionInvalidParameter("Cannot use condition against $linkName - unknown module");
            }
        } else {
            $bean = $q->from;
        }
        $defs = $bean->field_defs;

        if (empty($defs[$field])) {
            throw new SugarApiExceptionInvalidParameter("Unknown field $field");
        }

        if (!$bean->ACLFieldAccess($field)) {
            throw new SugarApiExceptionNotAuthorized("Access for field $field is not allowed");
        }

        $field_def = $defs[$field];

        if (!empty($field_def['source']) && $field_def['source'] == 'relate') {
            if (empty($field_def['rname']) || empty($field_def['link'])) {
                throw new SugarApiExceptionInvalidParameter("Field $field has invalid metadata, has source of relate" .
                    ' but is missing rname or link');
            }
            $relfield = $field_def['rname'];
            $link = $field_def['link'];
            return self::verifyField($q, "$link.$relfield");
        }

        $ret['bean'] = $bean;
        $ret['def'] = $field_def;

        return $ret;
    }

    /**
     * Add filters to the query
     *
     * @param array $filterDefs
     * @param SugarQuery_Builder_Where $where
     * @param SugarQuery $q
     * @throws SugarApiExceptionInvalidParameter
     */
    protected static function addFilters(array $filterDefs, SugarQuery_Builder_Where $where, SugarQuery $q)
    {
        foreach ($filterDefs as $filterDef) {
            if (!is_array($filterDef)) {
                throw new SugarApiExceptionInvalidParameter(
                    sprintf(
                        'Did not recognize the definition: %s',
                        print_r($filterDef, true)
                    )
                );
            }
            foreach ($filterDef as $field => $filter) {
                static::addFilter($field, $filter, $where, $q);
            }
        }
    }

    /**
     * Add an individual filter part to the query
     *
     * @param string $field name of the field or shorcut to operate on. Ex. 'name' , '$owner'
     * @param array|string $filter filter definition. Ex. {'$equals':'foo'}
     * @param SugarQuery_Builder_Where $where
     * @param SugarQuery $q
     *
     * This function should be considered internal to sugar and not extended by external customizations.
     *
     * @throws SugarApiExceptionInvalidParameter
     */
    protected static function addFilter($field, $filter, SugarQuery_Builder_Where $where, SugarQuery $q)
    {
        if ($field == '$or') {
            static::addFilters($filter, $where->queryOr(), $q);
        } elseif ($field == '$and') {
            static::addFilters($filter, $where->queryAnd(), $q);
        } elseif ($field == '$favorite') {
            static::addFavoriteFilter($q, $where, $filter);
        } elseif ($field == '$owner') {
            static::addOwnerFilter($q, $where, $filter);
        } elseif ($field == '$creator') {
            static::addCreatorFilter($q, $where, $filter);
        } elseif ($field == '$tracker') {
            static::addTrackerFilter($q, $where, $filter);
        } elseif ($field == '$following') {
            static::addFollowFilter($q, $where, $filter);
        } else {
            static::addFieldFilter($q, $where, $filter, $field);
        }
    }

    /**
     * Processes filter parts that operate on standard (non-macro) fields
     *
     * @param SugarQuery_Builder_Where $where
     * @param SugarQuery $q
     * @param array $filter
     * @param string  $field
     *
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionNotAuthorized
     */
    private static function addFieldFilter(SugarQuery $q, SugarQuery_Builder_Where $where, $filter, $field)
    {
        static $sfh;
        if (!isset($sfh)) {
            $sfh = new SugarFieldHandler();
        }

        // Looks like just a normal field, parse its options
        $fieldInfo = self::verifyField($q, $field);

        // If the field was a related field and we added a join, we need to adjust the table name used
        // to get the right join table alias
        if (!empty($fieldInfo['field'])) {
            $field = $fieldInfo['field'];
        }
        $fieldType = !empty($fieldInfo['def']['custom_type']) ? $fieldInfo['def']['custom_type'] :
            $fieldInfo['def']['type'];
        $sugarField = $sfh->getSugarField($fieldType);
        if (!is_array($filter)) {
            $value = $filter;
            $filter = array();
            $filter['$equals'] = $value;
        }
        foreach ($filter as $op => $value) {
            /*
             * occasionally fields may need to be fixed up for the Filter, for instance if you are
             * doing an operation on a datetime field and only send in a date, we need to fix that field to
             * be a dateTime then unFormat it so that its in GMT ready for DB use
             */

            if (strpos($field, '.') === false) {
                if (isset($fieldInfo['def']['source']) && $fieldInfo['def']['source'] === 'custom_fields') {
                    $tableName = $fieldInfo['bean']->get_custom_table_name();
                } else {
                    $tableName = $fieldInfo['bean']->getTableName();
                }
                $columnName = $tableName . '.' . $field;
            } else {
                $columnName = $field;
            }

            if ($sugarField->fixForFilter($value, $columnName, $fieldInfo['bean'], $q, $where, $op) == false) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $i => $val) {
                    // FIXME: BR-4063 apiUnformat() is deprecated, this will change to apiUnformatField() in
                    // next API version
                    $value[$i] = $sugarField->apiUnformat($val);
                }
            } else {
                // FIXME: BR-4063 apiUnformat() is deprecated, this will change to apiUnformatField() in
                // next API version
                $value = $sugarField->apiUnformat($value);
            }

            switch ($op) {
                case '$equals':
                    $where->equals($field, $value);
                    break;
                case '$not_equals':
                    $where->notEquals($field, $value);
                    break;
                case '$starts':
                    $where->starts($field, $value);
                    break;
                case '$ends':
                    $where->ends($field, $value);
                    break;
                case '$contains':
                    $where->contains($field, $value);
                    break;
                case '$not_contains':
                    $where->notContains($field, $value);
                    break;
                case '$in':
                    if (!is_array($value)) {
                        throw new SugarApiExceptionInvalidParameter('$in requires an array');
                    }
                    $where->in($field, $value);
                    break;
                case '$not_in':
                    if (!is_array($value)) {
                        throw new SugarApiExceptionInvalidParameter('$not_in requires an array');
                    }
                    $where->notIn($field, $value);
                    break;
                case '$dateBetween':
                case '$between':
                    if (!is_array($value) || count($value) != 2) {
                        throw new SugarApiExceptionInvalidParameter(
                            '$between requires an array with two values.'
                        );
                    }
                    $where->between($field, $value[0], $value[1]);
                    break;
                case '$is_null':
                    $where->isNull($field);
                    break;
                case '$not_null':
                    $where->notNull($field);
                    break;
                case '$empty':
                    $where->isEmpty($field);
                    break;
                case '$not_empty':
                    $where->isNotEmpty($field);
                    break;
                case '$lt':
                    $where->lt($field, $value);
                    break;
                case '$lte':
                    $where->lte($field, $value);
                    break;
                case '$gt':
                    $where->gt($field, $value);
                    break;
                case '$gte':
                    $where->gte($field, $value);
                    break;
                case '$dateRange':
                    $where->dateRange($field, $value, $fieldInfo['bean']);
                    break;
                default:
                    throw new SugarApiExceptionInvalidParameter('Did not recognize the operand: ' . $op);
            }
        }
    }

    /**
     * This function adds an owner filter to the sugar query
     *
     * @param SugarQuery $q The whole SugarQuery object
     * @param SugarQuery_Builder_Where $where The Where part of the SugarQuery object
     * @param string $link Which module are you adding the owner filter to.
     */
    protected static function addOwnerFilter(SugarQuery $q, SugarQuery_Builder_Where $where, $link)
    {
        if ($link == '' || $link == '_this') {
            $linkPart = '';
        } else {
            $join = $q->join($link, array('joinType' => 'LEFT'));
            $linkPart = $join->joinName() . '.';
        }

        $where->equals($linkPart . 'assigned_user_id', self::$current_user->id);
    }

    /**
     * Add a Following Filter
     *
     * @param SugarQuery $q
     * @param SugarQuery_Builder_Where $where
     * @param $filter
     */
    protected static function addFollowFilter(
        SugarQuery $q,
        SugarQuery_Builder_Where $where,
        $filter,
        $joinType = 'LEFT'
    ) {
        $field = 'following';
        $q->select($field);
        if (isset($q->from->field_defs[$field]['link'])) {
            $link = $q->from->field_defs[$field]['link'];
            if (isset($q->joinLinkToKey[$link])) {
                $alias = $q->joinLinkToKey[$link];
                $where->addRaw("$alias.id IS NOT NULL");
            }
        }
    }

    /**
     * This function adds a creator filter to the sugar query
     *
     * @param SugarQuery $q The whole SugarQuery object
     * @param SugarQuery_Builder_Where $where The Where part of the SugarQuery object
     * @param string $link Which module are you adding the owner filter to.
     */
    protected static function addCreatorFilter(SugarQuery $q, SugarQuery_Builder_Where $where, $link)
    {
        if ($link == '' || $link == '_this') {
            $linkPart = '';
        } else {
            $q->join($link, array('joinType' => 'LEFT'));
            $linkPart = $link . '.';
        }

        $where->equals($linkPart . 'created_by', self::$current_user->id);
    }

    /**
     * This function adds a favorite filter to the sugar query
     *
     * @param SugarQuery $q The whole SugarQuery object
     * @param SugarQuery_Builder_Where $where The Where part of the SugarQuery object
     * @param string $link Which module are you adding the favorite filter to.
     */
    protected static function addFavoriteFilter(
        SugarQuery $q,
        SugarQuery_Builder_Where $where,
        $link,
        $joinType = 'LEFT'
    ) {
        $sfOptions = array('joinType' => $joinType, 'favorites' => true);
        if ($link == '' || $link == '_this') {
            $link_name = 'favorites';
        } else {
            $joinTo = $q->join($link, array('joinType' => 'LEFT'));
            $sfOptions['joinTo'] = $joinTo;
            $sfOptions['joinModule'] = $q->getFromBean()->module_name;
            $link_name = 'sf_' . $link;
        }

        $fjoin = $q->join($link_name, $sfOptions);

        $where->notNull($fjoin->joinName() . '.id');
    }

    protected static function addTrackerFilter(SugarQuery $q, SugarQuery_Builder_Where $where, $interval)
    {
        global $db;

        $td = new SugarDateTime();
        $td->modify($interval);
        $min_date = $td->asDb();

        // Have to do a subselect because MAX() and GROUP BY don't get along with
        // databases other than MySQL
        $join = $q->joinTable(
            '(SELECT t.item_id item_id, MAX(t.date_modified) track_max ' .
            ' FROM tracker t ' .
            ' WHERE t.module_name = ' . $db->quoted($q->from->module_name) . ' ' .
            ' AND t.user_id = ' . $db->quoted($GLOBALS['current_user']->id) . ' ' .
            ' AND t.date_modified >= ' . $db->convert("'$min_date'", 'datetime') . ' ' .
            ' AND t.deleted = 0 ' .
            ' GROUP BY t.item_id)',
            array('alias' => 'tracker')
        );
        $join->on()->equalsField('tracker.item_id', $q->from->getTableName() . '.id');

        if (empty($q->order_by)) {
            // Now, if they want tracker records without specific order, so let's order it by the tracker date_modified
            $q->order_by = array();
            $q->orderByRaw('tracker.track_max', 'DESC');
        }
        $q->distinct(false);
        $q->select()->fieldRaw('tracker.track_max', 'last_viewed_date');
    }

    /**
     * Returns default limit of returned records
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }

    /**
     * Returns default record order
     */
    public function getDefaultOrderBy()
    {
        return $this->defaultOrderBy;
    }

    /**
     * Gets relate collection information from a collection of beans
     *
     * @param array $beans Collection of beans to get relate collections from
     * @param array $fields List of fields to check for relate collection information
     * @return array
     */
    protected function getRelatedCollectionOptions(array $beans, array $fields)
    {
        $options = array();
        if (empty($beans) || !is_array($beans)) {
            return $options;
        }

        // Get the first member of the beans array since we only need to test
        // one bean for a relate_collection field
        reset($beans);
        $bean = $beans[key($beans)];

        // Do some sanity checking, since some tests might send this array as a
        // simple array of values
        if ($bean instanceof SugarBean) {
            foreach ($bean->field_defs as $def) {
                if ((count($fields) == 0 || in_array($def['name'], $fields)) && !empty($def['relate_collection'])) {
                    $options['relate_collections'][$def['name']] = $def;
                    if (!isset($options['module'])) {
                        $options['module'] = $bean->getModuleName();
                    }
                }
            }
        }

        return $options;
    }

    /**
     * Relate collections should be gathered separately from the main filter
     * query. There are multiple records for each row of data and cannot be
     * shown in the select as such.
     *
     * @param $options array of options to use
     * @return mixed the options without any relate collections
     */
    protected function removeRelateCollectionsFromSelect(array $options)
    {
        if (isset($options['select'])) {
            foreach ($options['select'] as $index => $field) {
                if (isset($options['relate_collections'][$field])) {
                    unset($options['select'][$index]);
                }
            }
        }
        return $options;
    }

    /**
     * Creat new SugarQuery object
     * @param DBManager $db
     * @return SugarQuery
     */
    protected static function newSugarQuery(DBManager $db)
    {
        return new SugarQuery($db);
    }
}
