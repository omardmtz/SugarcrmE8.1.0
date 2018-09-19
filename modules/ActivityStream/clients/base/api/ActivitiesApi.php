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


class ActivitiesApi extends FilterApi
{
    protected static $beanList = array();
    protected static $previewCheckResults = array();

    public function registerApiRest()
    {
        return array(
            // TODO: Look into removing this method. We shouldn't need this, but
            // it's here to prevent breaking stuff before SugarCon 2013.
            'record_activities' => array(
                'reqType' => 'GET',
                'path' => array('<module>','?', 'link', 'activities'),
                'pathVars' => array('module','record', ''),
                'method' => 'getRecordActivities',
                'shortHelp' => 'This method retrieves a record\'s activities',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/recordActivities.html',
            ),
            'module_activities' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'Activities'),
                'pathVars' => array('module', ''),
                'method' => 'getModuleActivities',
                'shortHelp' => 'This method retrieves a module\'s activities',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/moduleActivities.html',
            ),
            'home_activities' => array(
                'reqType' => 'GET',
                'path' => array('Activities'),
                'pathVars' => array(''),
                'method' => 'getHomeActivities',
                'shortHelp' => 'This method gets homepage activities for a user',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/homeActivities.html',
            ),
            'record_activities_filter' => array(
                'reqType' => 'GET',
                'path' => array('<module>','?', 'link', 'activities', 'filter'),
                'pathVars' => array('module','record', ''),
                'method' => 'getRecordActivities',
                'shortHelp' => 'This method retrieves a filtered list of a record\'s activities',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/recordActivities.html',
            ),
            'module_activities_filter' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'Activities', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'getModuleActivities',
                'shortHelp' => 'This method retrieves a filtered list of a module\'s activities',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/moduleActivities.html',
            ),
            'home_activities_filter' => array(
                'reqType' => 'GET',
                'path' => array('Activities', 'filter'),
                'pathVars' => array(''),
                'method' => 'getHomeActivities',
                'shortHelp' => 'This method gets a filtered list of homepage activities for a user',
                'longHelp' => 'modules/ActivityStream/clients/base/api/help/homeActivities.html',
            ),
        );
    }

    public function getRecordActivities(ServiceBase $api, array $args)
    {
        $this->requireActivityStreams($args['module']);

        $params = $this->parseArguments($api, $args);
        $record = BeanFactory::retrieveBean($args['module'], $args['record']);

        if (empty($record)) {
            throw new SugarApiExceptionNotFound('Could not find parent record '.$args['record'].' in module '.$args['module']);
        }
        if (!$record->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }

        $query = self::getQueryObject($record, $params, $api);
        return $this->formatResult($api, $args, $query, $record);
    }

    public function getModuleActivities(ServiceBase $api, array $args)
    {
        $this->requireActivityStreams($args['module']);

        $params = $this->parseArguments($api, $args);
        $record = BeanFactory::newBean($args['module']);
        if (!$record->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }

        $query = self::getQueryObject($record, $params, $api);
        return $this->formatResult($api, $args, $query, $record);
    }

    public function getHomeActivities(ServiceBase $api, array $args)
    {
        $this->requireActivityStreams('Home');

        $params = $this->parseArguments($api, $args);
        $query = self::getQueryObject(new EmptyBean(), $params, $api, true);
        return $this->formatResult($api, $args, $query);
    }

    public function parseArguments(ServiceBase $api, array $args, SugarBean $seed = null)
    {
        $params = parent::parseArguments($api, $args, $seed);
        if (isset($args['filter'])) {
            $params['filter'] = $args['filter'];
        }
        return $params;
    }

    protected function formatResult(ServiceBase $api, array $args, SugarQuery $query, SugarBean $bean = null)
    {
        global $locale;

        $response = array();
        $data = $query->execute('array', false);

        $seed = BeanFactory::newBean('Activities');

        // We add one to it when setting it, so we subtract one now for the true
        // limit.
        $limit = $query->limit - 1;
        $count = count($data);
        if ($count > $limit) {
            $nextOffset = $query->offset + $limit;
            array_pop($data);
        } else {
            $nextOffset = -1;
        }

        $options = array(
            'requestBean' => $bean,
        );

        foreach ($data as $row) {
            $seed->populateFromRow($row, true);
            $record = $this->formatBean($api, $args, $seed, $options);

            if (isset($record['activity_type']) && $record['activity_type'] === 'update') {
                if (is_null($bean) || empty($bean->id)) {
                    $fields = json_decode($row['fields'], true);
                    $changedData = array();
                    if (!empty($fields)) {
                        $aclBean = null;
                        if (!is_null($bean)) {
                            $aclBean = $bean;
                        } elseif (!empty($record['data']['object']['module'])) {
                            $aclModule = $record['data']['object']['module'];
                            $aclBean = $this->getEmptyBean($aclModule);
                        }
                        if (!is_null($aclBean)) {
                            $context = array('user' => $api->user);
                            $aclBean->ACLFilterFieldList($record['data']['changes'], $context);
                        }
                        foreach ($record['data']['changes'] as &$change) {
                            if (in_array($change['field_name'], $fields)) {
                                $changedData[$change['field_name']] = $record['data']['changes'][$change['field_name']];
                            }
                        }
                    }
                    $record['data']['changes'] = $changedData;
                } else {
                    $context = array('user' => $api->user);
                    $bean->ACLFilterFieldList($record['data']['changes'], $context);
                }
            }

            //check if parent record preview should be enabled
            if (!empty($record['parent_type']) && !empty($record['parent_id'])) {
                $previewCheckResult = $this->checkParentPreviewEnabled($api->user, $record['display_parent_type'], $record['display_parent_id']);
                $record['preview_enabled'] = $previewCheckResult['preview_enabled'];
                $record['preview_disabled_reason'] = $previewCheckResult['preview_disabled_reason'];
            }

            $record['created_by_name'] = $locale->formatName('Users', $row);

            if (!isset($record['created_by_name']) && isset($record['data']['created_by_name'])) {
                $record['created_by_name'] = $record['data']['created_by_name'];
            }

            $response['records'][] = $record;
        }
        $response['next_offset'] = $nextOffset;
        $response['args'] = $args;
        return $response;
    }

    protected function checkParentPreviewEnabled(User $user, $module, $id)
    {
        $previewCheckKey = $module . '.' . $id;
        $previewCheckResult = array();
        if (array_key_exists($previewCheckKey, self::$previewCheckResults)) {
            $previewCheckResult = self::$previewCheckResults[$previewCheckKey];
        } else {
            $previewCheckBean = $this->getEmptyBean($module);
            if (!empty($previewCheckBean)) {
                $previewCheckBean->id = $id;
                //check if user has access - also checks if record is deleted
                $previewCheckResult['preview_enabled'] = $previewCheckBean->checkUserAccess($user);
            }
            //currently only one error reason, but may be others in the future
            $previewCheckResult['preview_disabled_reason'] = $previewCheckResult['preview_enabled'] ? '' : 'LBL_PREVIEW_DISABLED_DELETED_OR_NO_ACCESS';
        }
        self::$previewCheckResults[$previewCheckKey] = $previewCheckResult;
        return $previewCheckResult;
    }

    protected function getEmptyBean($module)
    {
        if (isset(self::$beanList[$module])) {
            $bean = self::$beanList[$module];
        } else {
            $bean = BeanFactory::newBean($module);
            if (!is_null($bean)) {
                self::$beanList[$module] = $bean;
            }
        }
        return $bean;
    }

    protected static function getQueryObject(
        SugarBean $record,
        array $options,
        ServiceBase $api = null,
        $homeActivities = false
    ) {
        $seed = BeanFactory::newBean('Activities');
        $query = new SugarQuery();
        $query->from($seed);

        // Always order the activity stream by date modified DESC.
        $query->orderBy('date_modified', 'DESC');

        // +1 used to determine if we have more records to show.
        $query->limit($options['limit'] + 1)->offset($options['offset']);

        $columns = array('activities.*', 'users.first_name', 'users.last_name', 'users.picture');


        // Join with user names.
        $query->joinTable('users', array('joinType' => 'INNER'))
            ->on()->equalsField('activities.created_by', 'users.id');

        $join = $query->joinTable('activities_users', array('joinType' => 'INNER', 'linkName' => 'activities_users', "linkingTable" => true))
            ->on()->equalsField("activities_users.activity_id", 'activities.id')
            ->equals("activities_users.deleted", 0);

        if ($homeActivities || !$record->id) {
            // Join with cached list of activities to show.
            $columns[] = 'activities_users.fields';
            $join = $join->queryOr();
            // TODO: Change this to include all teams a user is a member of
            // for more granular activity stream control.
            $join->queryAnd()->equals('activities_users.parent_type', 'Teams')
                ->equals('activities_users.parent_id', 1);
            $join->queryAnd()->equals('activities_users.parent_type', 'Users')
                ->equals('activities_users.parent_id', $api->user->id);
            if ($homeActivities) {
                $homeActivityFilter = $query->where()->queryOr();
                $homeActivityFilter->isNull('activities.parent_type');
                $homeActivityFilter->equals('activities.parent_type', 'Activities');
                $homeActivityFilter->equals('activities.parent_type', 'Home');
                $homeActivityFilter->equals('activities_users.parent_type', 'Users');
            } else {
                $query->where()->equals('activities.parent_type', $record->module_name);
            }
        } else {
            // If we have a relevant bean, we add our where condition.
            $query->where()->equals('activities_users.parent_type', $record->module_name);
            if ($record->id) {
                $query->where()->equals('activities_users.parent_id', $record->id);
            }
        }

        // We only support filtering on activity_type.
        if (!empty($options['filter'])) {
            self::addFilters($options['filter'], $query->where(), $query);
        }

        $query->where()->equals('deleted', 0);
        $query->select($columns);

        return $query;
    }

    /**
     * Checks to see if Activity Streams is disabled
     *
     * @param string $moduleName
     * @throws SugarApiExceptionNotAuthorized
     *
     */
    private function requireActivityStreams($moduleName)
    {
        if (!Activity::isEnabled()) {
            throw new SugarApiExceptionNotAuthorized(translate('EXCEPTION_ACTIVITY_STREAM_DISABLED', $moduleName));
        }
    }
}
