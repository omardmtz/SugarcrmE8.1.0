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


abstract class SugarApi
{
    /**
     * @var ServiceBase
     */
    public $api;

    /**
     * @var string
     */
    public $action;

    /**
     * Handles validation of required arguments for a request
     *
     * @param array $args
     * @param array $requiredFields
     * @throws SugarApiExceptionMissingParameter
     */
    public function requireArgs(array $args, $requiredFields = array())
    {
        foreach ( $requiredFields as $fieldName ) {
            if ( !array_key_exists($fieldName, $args) ) {
                throw new SugarApiExceptionMissingParameter('Missing parameter: '.$fieldName);
            }
        }
    }

    /**
     * Fetches data from the $args array and formats the bean with those parameters
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the formatted data is returned
     * @param array $args The arguments array passed in from the API, will check this for the 'fields' argument to only return the requested fields
     * @param $bean SugarBean The fully loaded bean to format
     * @param $options array Formatting options
     * @return array An array version of the SugarBean with only the requested fields (also filtered by ACL)
     */
    protected function formatBean(ServiceBase $api, array $args, SugarBean $bean, array $options = array())
    {
        if ((empty($args['fields']) && !empty($args['view'])) ||
            (!empty($args['fields']) && !is_array($args['fields']))
        ) {
            $args['fields'] = $this->getFieldsFromArgs($api, $args, $bean, 'view', $options['display_params']);
        }

        if (!empty($args['fields'])) {
            $fieldList = $args['fields'];

            if ( ! in_array('date_modified',$fieldList ) ) {
                $fieldList[] = 'date_modified';
            }
            if ( ! in_array('id',$fieldList ) ) {
                $fieldList[] = 'id';
            }

            if (!in_array('locked_fields', $fieldList)) {
                $fieldList[] = 'locked_fields';
            }
        } else {
            $fieldList = array();
        }

        $options = array_merge(array(
            'action' => $api->action,
            'args' => $args,
        ), $options);

        $data = ApiHelper::getHelper($api,$bean)->formatForApi($bean,$fieldList, $options);

        // Should we log this as a recently viewed item?
        if ( !empty($data) && isset($args['viewed']) && $args['viewed'] == true ) {
            if ( !isset($this->action) ) {
                $this->action = 'view';
            }
            if ( !isset($this->api) ) {
                $this->api = $api;
            }
            $this->trackAction($bean);
        }

        if (!empty($bean->module_name)) {
            $data['_module'] = $bean->module_name;
        }

        return $data;
    }

    protected function formatBeans(ServiceBase $api, array $args, $beans, array $options = array())
    {
        if (!empty($args['fields']) && !is_array($args['fields'])) {
            $args['fields'] = explode(',',$args['fields']);
        }

        $ret = array();

        foreach ($beans as $bean) {
            if (!is_subclass_of($bean, 'SugarBean')) {
                continue;
            }
            $ret[] = $this->formatBean($api, $args, $bean, $options);
        }

        return $ret;
    }
    /**
     * Recursively runs html entity decode for the reply
     * @param $data array The bean the API is returning
     */
    protected function htmlDecodeReturn(&$data) {
        foreach($data AS $key => $value) {
            if((is_object($value) || is_array($value)) && !empty($value)) {
                if (is_array($data)) {
                    $this->htmlDecodeReturn($data[$key]);
                } else {
                    $this->htmlDecodeReturn($data->$key);
                }
            }
            // htmldecode screws up bools..returns '1' for true
            elseif (is_string($value) && !empty($data) && !empty($value)) {
                // USE ENT_QUOTES TO REMOVE BOTH SINGLE AND DOUBLE QUOTES, WITHOUT THIS IT WILL NOT CONVERT THEM
                $data[$key] = html_entity_decode($value, ENT_COMPAT|ENT_QUOTES, 'UTF-8');
            }
            else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Fetches data from the $args array and updates the bean with that data
     * @param ServiceBase $api The API class of the request
     * @param array $args The arguments array passed in from the API
     * @param $aclToCheck string What kind of ACL to verify when loading a bean. Supports: view,edit,create,import,export
     * @param $options array Options to pass to the retrieveBean method
     * @return SugarBean The loaded bean
     */
    protected function loadBean(ServiceBase $api, array $args, $aclToCheck = 'view', array $options = array())
    {
        $this->requireArgs($args, array('module','record'));

        if (!empty($args['erased_fields'])) {
            $options['erased_fields'] = true;
        }

        $bean = BeanFactory::retrieveBean($args['module'],$args['record'], $options);

        if ($api->action == 'save' && ($bean == false || $bean->deleted == 1)) {
            throw new SugarApiExceptionNotAuthorized('SUGAR_API_EXCEPTION_RECORD_NOT_AUTHORIZED', array('save'));
        }

        if ( $bean == FALSE || $bean->deleted == 1) {
            // Couldn't load the bean
            throw new SugarApiExceptionNotFound('Could not find record: '.$args['record'].' in module: '.$args['module']);
        }

        if (SugarACLStatic::fixUpActionName($aclToCheck) != 'view' && !$bean->ACLAccess(SugarACLStatic::fixUpActionName($aclToCheck), $options)) {
            throw new SugarApiExceptionNotAuthorized('SUGAR_API_EXCEPTION_RECORD_NOT_AUTHORIZED',array($aclToCheck));
        }

        return $bean;
    }

    /**
     * Fetches data from the $args array and updates the bean with that data
     * @param $bean SugarBean The bean to be updated
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return id Bean id
     */
    protected function updateBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        $this->populateBean($bean, $api, $args);
        $this->saveBean($bean, $api, $args);

        return $bean->id;
    }

    /**
     * Populates the given bean with the values from API arguments
     *
     * @param SugarBean $bean The bean to be populated
     * @param ServiceBase $api
     * @param array $args API arguments
     * @throws SugarApiExceptionEditConflict
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function populateBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        $helper = ApiHelper::getHelper($api,$bean);
        $options = array();
        if(!empty($args['_headers']['X_TIMESTAMP'])) {
            $options['optimistic_lock'] = $args['_headers']['X_TIMESTAMP'];
        }
        try {
            $errors = $helper->populateFromApi($bean,$args, $options);
        } catch(SugarApiExceptionEditConflict $conflict) {
            $api->action = 'view';
            $data = $this->formatBean($api, $args, $bean);
            // put current state of the record on the exception
            $conflict->setExtraData("record", $data);
            throw $conflict;
        }

        if ( $errors !== true ) {
            // There were validation errors.
            throw new SugarApiExceptionInvalidParameter('There were validation errors on the submitted data. Record was not saved.');
        }
    }

    /**
     * Saves the given bean
     *
     * @param SugarBean $bean The bean to be saved
     * @param ServiceBase $api
     * @param array $args API arguments
     */
    protected function saveBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        $helper = ApiHelper::getHelper($api, $bean);
        $check_notify = $helper->checkNotify($bean);
        $bean->save($check_notify);

        BeanFactory::unregisterBean($bean->module_name, $bean->id);

        if(isset($args['my_favorite'])) {
            $this->toggleFavorites($bean, $args['my_favorite']);
        }
    }



    /**
     * Toggle Favorites
     * @param SugarBean $module
     * @param type $favorite
     * @return bool
     */

    protected function toggleFavorites(SugarBean $bean, $favorite)
    {

        $reindexBean = false;

        $favorite = (bool) $favorite;

        $module = $bean->module_dir;
        $record = $bean->id;

        $fav_id = SugarFavorites::generateGUID($module,$record);

        // get it even if its deleted
        $fav = BeanFactory::getBean('SugarFavorites', $fav_id, array("deleted" => false));

        // already exists
        if(!empty($fav->id)) {
            $deleted = ($favorite) ? 0 : 1;
            $fav->toggleExistingFavorite($fav_id, $deleted);
            $reindexBean = true;
        }

        elseif($favorite && empty($fav->id)) {
            $fav = BeanFactory::newBean('SugarFavorites');
            $fav->id = $fav_id;
            $fav->new_with_id = true;
            $fav->module = $module;
            $fav->record_id = $record;
            $fav->created_by = $GLOBALS['current_user']->id;
            $fav->assigned_user_id = $GLOBALS['current_user']->id;
            $fav->deleted = 0;
            $fav->save();
        }

        $bean->my_favorite = $favorite;

        // Bug59888 - If a Favorite is toggled, we need to reindex the bean for FTS engines so that the document will be updated with this change
        if($reindexBean === true) {
            $searchEngine = SugarSearchEngineFactory::getInstance(SugarSearchEngineFactory::getFTSEngineNameFromConfig());

            if($searchEngine instanceof SugarSearchEngineAbstractBase) {
                $searchEngine->indexBean($bean, false);
            }
        }

        return true;

    }


    /**
     * Verifies field level access for a bean and field for the logged in user
     *
     * @param SugarBean $bean The bean to check on
     * @param string $field The field to check on
     * @param string $action The action to check permission on
     * @param array $context ACL context
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function verifyFieldAccess(SugarBean $bean, $field, $action = 'access', $context = array()) {
        if (!$bean->ACLFieldAccess($field, $action, $context)) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionNotAuthorized('Not allowed to ' . $action . ' ' . $field . ' field in ' . $bean->object_name . ' module.');
        }
    }

    /**
     * Adds an entry in the tracker table noting that this record was touched
     *
     * @param SugarBean $bean The bean to record in the tracker table
     */
    public function trackAction(SugarBean $bean)
    {
        $manager = $this->getTrackerManager();
        $monitor = $manager->getMonitor('tracker');

        if ( ! $monitor ) {
            // This tracker is disabled.
            return;
        }
        if ( empty($bean->id) || (isset($bean->new_with_id) && $bean->new_with_id) ) {
            // It's a new bean, don't record it.
            // Tracking bean saves/creates happens in the SugarBean so it is always recorded
            return;
        }

        $monitor->setValue('team_id', $this->api->user->getPrivateTeamID());
        $monitor->setValue('action', $this->action);
        $monitor->setValue('user_id', $this->api->user->id);
        $monitor->setValue('module_name', $bean->module_dir);
        $monitor->setValue('date_modified', TimeDate::getInstance()->nowDb());

        // Visibility is important... only mark it visible if the bean says to
        $monitor->setValue('visible', $bean->tracker_visibility);
        $monitor->setValue('item_id', $bean->id);
        $monitor->setValue('item_summary', $bean->get_summary_text());

        $manager->saveMonitor($monitor, true, true);
    }

    /**
     * Helper until we have dependency injection to grab a tracker manager
     * @return TrackerManager An instance of the tracker manager
     */
    public function getTrackerManager()
    {
        return TrackerManager::getInstance();
    }

    /**
     *
     * Determine field list from arguments base both "fields" and "view" parameter.
     * The final result is a merger of both.
     *
     * @param ServiceBase $api           The API request object
     * @param array       $args          The arguments passed in from the API
     * @param SugarBean   $bean          Bean context
     * @param string      $viewName      The argument used to determine the view name, defaults to view
     * @param array       $displayParams Display parameters for some fields
     * @return array
     */
    protected function getFieldsFromArgs(
        ServiceBase $api,
        array $args,
        SugarBean $bean = null,
        $viewName = 'view',
        &$displayParams = array()
    ) {
        // Try to get the fields list if explicitly defined.
        if (!empty($args['fields'])) {
            $fields = $this->normalizeFields($args['fields'], $displayParams);
        } else {
            $fields = array();
        }

        // When a view name is specified and a seed is available, also include those fields
        if (!empty($viewName) && !empty($args[$viewName]) && !empty($bean)) {
            $fields = array_unique(
                array_merge(
                    $fields,
                    $this->getMetaDataManager($api->platform)
                         ->getModuleViewFields($bean->module_name, $args[$viewName], $displayParams)
                )
            );

            // add dependant field for relates
            $fieldDefs = $bean->field_defs;
            foreach ($fields as $field) {
                if (!empty($fieldDefs[$field]) && isset($fieldDefs[$field]['type'])) {
                    $type = $fieldDefs[$field]['type'];
                    if (in_array($type, $bean::$relateFieldTypes)) {
                        $type = 'relate';
                    }
                    switch ($type) {
                        case 'relate':
                            if (!empty($fieldDefs[$field]['id_name'])) {
                                $fields[] = $fieldDefs[$field]['id_name'];
                            }
                            break;
                        case 'parent':
                            if (!empty($fieldDefs[$field]['id_name'])) {
                                $fields[] = $fieldDefs[$field]['id_name'];
                            }
                            if (!empty($fieldDefs[$field]['type_name'])) {
                                $fields[] = $fieldDefs[$field]['type_name'];
                            }
                            break;
                        case 'url':
                            if (!empty($fieldDefs[$field]['default'])) {
                                preg_match_all('/{([^{}]+)}/', $fieldDefs[$field]['default'], $matches);
                                foreach ($matches[1] as $match) {
                                    if (!empty($match) && !empty($fieldDefs[$match])) {
                                        $fields[] = $match;
                                    }
                                }
                            }
                            break;
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Normalizes the value of fields argument. Returns plain array of field names and associative array
     * of display parameters (if specified) by reference
     *
     * @param string|array $fields Original value from API arguments
     * @param array $displayParams Display parameters
     *
     * @return array
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function normalizeFields($fields, &$displayParams)
    {
        $displayParams = array();
        if (is_string($fields)) {
            $fields = $this->parseFields($fields);
        }

        if (!is_array($fields)) {
            throw new SugarApiExceptionInvalidParameter(
                sprintf('Fields must be string or array, %s is given', gettype($fields))
            );
        }

        $normalized = array();
        foreach ($fields as $field) {
            if (is_string($field)) {
                $name = $field;
            } else {
                if (!isset($field['name'])) {
                    throw new SugarApiExceptionInvalidParameter(
                        sprintf('Fields must be specified in array notation')
                    );
                }

                $name = $field['name'];
                unset($field['name']);

                if ($field) {
                    $displayParams[$name] = $field;
                }
            }

            $normalized[] = $name;
        }

        return $normalized;
    }

    /**
     * Parses mixed comma-separated-JSON format of fields argument
     *
     * Example input:
     * <code>$fields = 'name,{"name":"opportunities","fields":["id","name","sales_status"]}';</code>
     *
     * Resulting output:
     * <code>
     * array(
     *     'name',
     *     array(
     *         'name' => 'opportunities',
     *         'fields' => array('id', 'name', 'sales_status'),
     *     ),
     * );
     * </code>
     *
     * @param $fields string Original value from API arguments
     *
     * @return array
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function parseFields($fields)
    {
        $chunks = explode(',', $fields);
        $formatted = array();
        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if (strpos($chunk, '"') === false) {
                $formatted[] = '"' . $chunk . '"';
            } else {
                $formatted[] = $chunk;
            }
        }

        $json = '[' . implode(',', $formatted) . ']';
        $decoded = json_decode($json, true);

        if ($decoded === null) {
            throw new SugarApiExceptionInvalidParameter(
                'Unable to parse fields'
            );
        }

        return $decoded;
    }

    /**
     * Creates internal representation of ORDER BY expression from API arguments
     *
     * @param array $args API arguments
     * @param SugarBean $seed The bean to validate the value against.
     *                        If omitted, no validation is performed
     *
     * @return array Associative array where key is field name, boolean value is direction
     *               (TRUE stands for ASC, FALSE stands for DESC)
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function getOrderByFromArgs(array $args, SugarBean $seed = null)
    {
        $orderBy = array();
        if (!isset($args['order_by']) || !is_string($args['order_by'])) {
            return $orderBy;
        }

        $columns = explode(',', $args['order_by']);
        $parsed = array();
        foreach ($columns as $column) {
            $column = explode(':', $column, 2);
            $field = array_shift($column);

            if ($seed) {
                if (!isset($seed->field_defs[$field])) {
                    throw new SugarApiExceptionInvalidParameter(
                        sprintf('Non existing field: %s in module: %s', $field, $seed->module_name)
                    );
                }

                if (!$seed->ACLFieldAccess($field, 'list')) {
                    throw new SugarApiExceptionNotAuthorized(
                        sprintf('No access to view field: %s in module: %s', $field, $seed->module_name)
                    );
                }
            }

            // do not override previous value if it exists since it should have higher precedence
            if (!isset($parsed[$field])) {
                $direction = array_shift($column);
                $parsed[$field] = strtolower($direction) !== 'desc';
            }
        }

        return $parsed;
    }

    /**
     * Gets a MetaDataManager object
     * @param string $platform The platform to get the manager for
     * @param boolean $public Flag to describe visibility for metadata
     * @return MetaDataManager
     */
    protected function getMetaDataManager($platform = '', $public = false)
    {
        return MetaDataManager::getManager($platform, $public);
    }

    /**
     * Checks if POST request body was successfully delivered to the application.
     * Throws exception, if it was not.
     *
     * @throws SugarApiExceptionRequestTooLarge
     * @throws SugarApiExceptionMissingParameter
     */
    protected function checkPostRequestBody()
    {
        if (empty($_FILES)) {
            $contentLength = $this->getContentLength();
            $postMaxSize = $this->getPostMaxSize();
            if ($contentLength && $postMaxSize && $contentLength > $postMaxSize) {
                // @TODO Localize this exception message
                throw new SugarApiExceptionRequestTooLarge('Attachment is too large');
            }

            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Attachment is missing');
        }
    }

    /**
     * Returns max size of post data allowed defined by PHP configuration in bytes, or NULL if unable to determine.
     *
     * @return int|null
     */
    protected function getPostMaxSize()
    {
        $iniValue = ini_get('post_max_size');
        $postMaxSize = parseShorthandBytes($iniValue);

        return $postMaxSize;
    }

    /**
     * Checks if PUT request body was successfully delivered to the application.
     * Throws exception, if it was not.
     *
     * We have to require callers to provide the amount of data read from input stream, because otherwise we
     * would have to read the data ourselves, however the stream cannot be rewound. It seems there's no way
     * to get actual input size without reading it (e.g. by inspecting stream metadata).
     *
     * @param int $length The amount of data read from input stream
     *
     * @throws SugarApiExceptionRequestTooLarge
     * @throws SugarApiExceptionMissingParameter
     */
    protected function checkPutRequestBody($length)
    {
        $contentLength = $this->getContentLength();
        if ($contentLength && $length < $contentLength) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionRequestTooLarge('File is too large');
        } elseif (!$length) {
            throw new SugarApiExceptionMissingParameter('File is missing or no file data was received.');
        }
    }

    /**
     * Returns request body length, or NULL if unable to determine.
     *
     * @return int|null
     */
    protected function getContentLength()
    {
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            return (int) $_SERVER['CONTENT_LENGTH'];
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            $headers = array_change_key_case($headers, CASE_LOWER);
            if (isset($headers['content-length'])) {
                return (int) $headers['content-length'];
            }
        }

        return null;
    }

    /**
     * Check if list limit passed to API less or greater than allowed predefined value.
     * If max limit is not defined it returns passed value without changes.
     *
     * @param int $limit List limit passed to API
     * @return int
     */
    public function checkMaxListLimit($limit)
    {
        $maxListLimit = SugarConfig::getInstance()->get('max_list_limit');

        if ($maxListLimit && ($limit < 1 || $limit > $maxListLimit)) {
            return $maxListLimit;
        }

        return $limit;
    }
}
