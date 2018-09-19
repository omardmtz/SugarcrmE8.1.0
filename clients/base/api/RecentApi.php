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


class RecentApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'getRecentlyViewed' => array(
                'reqType' => 'GET',
                'path' => array('recent'),
                'pathVars' => array('',''),
                'method' => 'getRecentlyViewed',
                'shortHelp' => 'This method retrieves recently viewed records for the user.',
                'longHelp' => 'include/api/help/me_recently_viewed_help.html',
            ),
        );
    }

    /**
     * Gets the user bean for the user of the api
     *
     * @return User
     */
    protected function getUserBean()
    {
        global $current_user;
        return $current_user;
    }

    /**
     * Set up options from args and default values.
     *
     * @param arrat $args Arguments from request.
     * @return array options after setup.
     */
    protected function parseArguments(array $args)
    {
        $options = array();
        $options['limit'] = !empty($args['limit']) ? (int) $args['limit'] : 20;
        if (!empty($args['max_num'])) {
            $options['limit'] = (int) $args['max_num'];
        }

        $options['limit'] = $this->checkMaxListLimit($options['limit']);
        $options['offset'] = 0;

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset'] = (int) $args['offset'];
            }
        }

        $options['select'] = !empty($args['fields']) ? explode(",", $args['fields']) : null;
        $options['module'] = !empty($args['module']) ? $args['module'] : null;
        $options['date'] = !empty($args['date']) ? $args['date'] : null;

        $options['moduleList'] = array();
        if (!empty($args['module_list'])) {
            $options['moduleList'] = array_filter(explode(',', $args['module_list']));
        }

        return $options;
    }

    /**
     * Filters the list of modules to the ones that the user has access to and
     * that exist on the moduleList.
     *
     * @param array $modules Modules list.
     * @param string $acl (optional) ACL action to check, default is `list`.
     * @return array Filtered modules list.
     */
    private function filterModules(array $modules, $acl = 'list')
    {
        return array_filter($modules, function ($module) use ($acl) {
            if (in_array($module, $GLOBALS['moduleList']) || $module === 'Employees') {
                $seed = BeanFactory::newBean($module);
                return $seed && $seed->ACLAccess($acl);
            }
            return false;
        });
    }

    /**
     * Gets recently viewed records.
     *
     * @param ServiceBase $api Current api.
     * @param array $args Arguments from request.
     * @param string $acl (optional) ACL action to check, default is `list`.
     * @return array List of recently viewed records.
     */
    public function getRecentlyViewed(ServiceBase $api, array $args, $acl = 'list')
    {
        $this->requireArgs($args, array('module_list'));

        $options = $this->parseArguments($args);

        $moduleList = $this->filterModules($options['moduleList'], $acl);
        if (empty($moduleList)) {
            return array('next_offset' => -1 , 'records' => array());
        }

        if (count($moduleList) === 1) {
            $moduleName = $moduleList[0];
            $seed = BeanFactory::newBean($moduleName);
            $mainQuery = $this->getRecentlyViewedQueryObject($seed, $options);
            $mainQuery->orderByRaw('MAX(tracker.date_modified)', 'DESC');

        } else {
            $mainQuery = new SugarQuery();
            foreach ($moduleList as $moduleName) {
                $seed = BeanFactory::newBean($moduleName);
                $mainQuery->union($this->getRecentlyViewedQueryObject($seed, $options), true);
            }
            $mainQuery->orderByRaw('last_viewed_date', 'DESC');
        }

        // Add an extra record to the limit so we can detect if there are more records to be found.
        $mainQuery->limit($options['limit'] + 1);
        $mainQuery->offset($options['offset']);

        $data = $beans = array();
        $data['next_offset'] = -1;

        // 'Cause last_viewed_date is an alias (not a real field), we need to
        // temporarily store its values and append it later to each recently
        // viewed record
        $lastViewedDates = array();

        $results = $mainQuery->execute();
        $db = DBManagerFactory::getInstance();
        foreach ($results as $idx => $recent) {
            if ($idx == $options['limit']) {
                $data['next_offset'] = (int) ($options['limit'] + $options['offset']);
                break;
            }

            $seed = BeanFactory::getBean($recent['module_name'], $recent['id'], array(
                'erased_fields' => !empty($args['erased_fields']),
            ));
            $lastViewedDates[$seed->id] = $db->fromConvert($recent['last_viewed_date'], 'datetime');
            $beans[$seed->id] = $seed;
        }

        $data['records'] = $this->formatBeans($api, $args, $beans);

        global $timedate;

        // Append last_viewed_date to each recently viewed record
        foreach($data['records'] as &$record) {
            $record['_last_viewed_date'] = $timedate->asIso($timedate->fromDb($lastViewedDates[$record['id']]));
        }

        return $data;
    }

    /**
     * Returns query object to retrieve list of recently viewed records by
     * module.
     *
     * @param SugarBean $seed Instance of current bean.
     * @param array $options Prepared options.
     * @return SugarQuery query to execute.
     */
    protected function getRecentlyViewedQueryObject(SugarBean $seed, array $options)
    {
        $currentUser = $this->getUserBean();

        $query = new SugarQuery();
        $query->from($seed);

        // FIXME: FRM-226, logic for these needs to be moved to SugarQuery

        // Since tracker relationships don't actually exist, we're gonna have to add a direct join
        $join = $query->joinTable('tracker');
        $join->on()->equalsField('tracker.item_id', $query->from->getTableName() . '.id')
            ->equals('tracker.module_name', $query->from->module_name)
            ->equals('tracker.user_id', $currentUser->id);

        $query->select(array('id', array('tracker.module_name', 'module_name')));

        if (!empty($options['date'])) {
            $td = new SugarDateTime();
            $td->modify($options['date']);
            $query->where()->queryAnd()->gte('tracker.date_modified', $td->asDb());
        }

        // We should only show recent items that are visible
        $query->where()->queryAnd()->equals('tracker.visible', 1);

        foreach ($query->select()->select as $v) {
            $query->groupBy($v->table . '.' . $v->field);
        }

        $query->select()->fieldRaw('MAX(tracker.date_modified)', 'last_viewed_date');

        return $query;
    }
}
