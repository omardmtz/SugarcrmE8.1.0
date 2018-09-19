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


class ForecastsApi extends ModuleApi
{
    public function registerApiRest()
    {
        $parentApi = array(
            'init' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts','init'),
                'pathVars' => array(),
                'method' => 'forecastsInitialization',
                'shortHelp' => 'Returns forecasts initialization data and additional user data',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsApiInitGet.html',
            ),
            'selecteUserObject' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'user', '?'),
                'pathVars' => array('', '', 'user_id'),
                'method' => 'retrieveSelectedUser',
                'shortHelp' => 'Returns selectedUser object for given user',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsApiUserGet.html',
            ),
            'timeperiod' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'enum', 'selectedTimePeriod'),
                'pathVars' => array('', '', ''),
                'method' => 'timeperiod',
                'shortHelp' => 'forecast timeperiod',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastApiTimePeriodGet.html',
            ),
            'reportees' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'reportees', '?'),
                'pathVars' => array('', '', 'user_id'),
                'method' => 'getReportees',
                'shortHelp' => 'Gets reportees to a user by id',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastApiReporteesGet.html',
            ),
            'orgtree' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'orgtree', '?'),
                'pathVars' => array('', '', 'user_id'),
                'method' => 'getOrgTree',
                'shortHelp' => 'Gets managers and reportees of user',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastApiOrgetreeGet.html',
            ),
            'list' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts',),
                'pathVars' => array('module'),
                'method' => 'returnEmptySet',
                'shortHelp' => 'Forecast list endpoint returns an empty set',
                'longHelp' => 'include/api/help/module_record_favorite_put_help.html',
            ),
            'getQuotaRollup' => array(
                'reqType' => 'GET',
                'path'      => array('Forecasts', '?', 'quotas', 'rollup', '?'),
                'pathVars'  => array('', 'timeperiod_id', '', 'quota_type', 'user_id'),
                'method' => 'getQuota',
                'shortHelp' => 'Returns the rollup quota for the user by timeperiod',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsQuotasApiGet.html',
            ),
            'getQuotaDirect' => array(
                'reqType' => 'GET',
                'path'      => array('Forecasts', '?', 'quotas', 'direct', '?'),
                'pathVars'  => array('', 'timeperiod_id', '', 'quota_type', 'user_id'),
                'method' => 'getQuota',
                'shortHelp' => 'Returns the direct quota for the user by timeperiod',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsQuotasApiGet.html',
            ),
        );
        return $parentApi;
    }

    /**
     * Returns an empty set for favorites and filter because those operations on forecasts are impossible
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function returnEmptySet(ServiceBase $api, array $args)
    {
        return array('next_offset' => -1, 'records' => array());
    }
    /**
     * Returns the initialization data for the module including currently logged-in user data,
     * timeperiods, and admin config settings
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     */
    public function forecastsInitialization(ServiceBase $api, array $args)
    {
        global $current_user;

        if(!SugarACL::checkAccess('Forecasts', 'access')) {
            throw new SugarApiExceptionNotAuthorized();
        }

        $returnInitData = array();
        $defaultSelections = array();

        // Add Forecasts-specific items to returned data
        $returnInitData["initData"]["userData"]['showOpps'] = false;
        $returnInitData["initData"]["userData"]['first_name'] = $current_user->first_name;
        $returnInitData["initData"]["userData"]['last_name'] = $current_user->last_name;

        // INVESTIGATE: these need to be more dynamic and deal with potential customizations based on how filters are built in admin and/or studio
        /* @var $admin Administration */
        $admin = $this->getBean('Administration');
        $forecastsSettings = $admin->getConfigForModule('Forecasts', 'base', true);
        // we need to make sure all the default setting are there, if they are not
        // it should set them to the default value + clear the metadata and kick out a 412 error to force
        // the metadata to reload
        $this->compareSettingsToDefaults($admin, $forecastsSettings, $api);

        // TODO: These should probably get moved in with the config/admin settings, or by themselves since this file will probably going away.
        $tp = TimePeriod::getCurrentTimePeriod($forecastsSettings['timeperiod_leaf_interval']);
        if (!empty($tp->id)) {
            $defaultSelections["timeperiod_id"] = array(
                'id' => $tp->id,
                'label' => $tp->name,
                'start' => $tp->start_date,
                'end' => $tp->end_date
            );
        } else {
            $defaultSelections["timeperiod_id"]["id"] = '';
            $defaultSelections["timeperiod_id"]["label"] = '';
            $defaultSelections["timeperiod_id"]["start"] = '';
            $defaultSelections["timeperiod_id"]["end"] = '';
        }

        $returnInitData["initData"]['forecasts_setup'] = (isset($forecastsSettings['is_setup'])) ? $forecastsSettings['is_setup'] : 0;

        $defaultSelections["ranges"] = $forecastsSettings['commit_stages_included'];
        $defaultSelections["group_by"] = 'forecast';
        $defaultSelections["dataset"] = 'likely';

        // push in defaultSelections
        $returnInitData["defaultSelections"] = $defaultSelections;

        return $returnInitData;
    }

    /**
     * Retrieves user data for a given user id
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function retrieveSelectedUser(ServiceBase $api, array $args)
    {
        global $locale;
        $uid = $args['user_id'];
        /* @var $user User */
        $user = $this->getBean('Users', $uid);
        $data = array();
        $data['id'] = $user->id;
        $data['user_name'] = $user->user_name;
        $data['full_name'] = $locale->formatName($user);
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['reports_to_id'] = $user->reports_to_id;
        $data['reports_to_name'] = $user->reports_to_name;
        $data['is_manager'] = User::isManager($user->id);
        $data['is_top_level_manager'] = User::isTopLevelManager($user->id);
        return $data;
    }

    /**
     * Return the dom of the current timeperiods.
     *
     * //TODO, move this logic to store the values in a custom language file that contains the timeperiods for the Forecast module
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array of timeperiods
     */
    public function timeperiod(ServiceBase $api, array $args)
    {
        $obj = $this->getTimeperiodFilterClass($args);
        return $obj->process();
    }

    /**
     * Utility method to get the timeperiod filter class
     *
     * @param array $args The arguments array passed in from the API
     * @return SugarForecasting_AbstractForecast
     */
    protected function getTimeperiodFilterClass(array $args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Filter/TimePeriodFilter.php';
        $klass = 'SugarForecasting_Filter_TimePeriodFilter';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class
        return new $klass($args);
    }

    /**
     * Retrieve an array of Users and their tree state that report to the user that was passed in
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array|string of users that reported to specified/current user
     */
    public function getReportees(ServiceBase $api, array $args)
    {
        $args['user_id'] = isset($args["user_id"]) ? $args["user_id"] : $GLOBALS["current_user"]->id;
        $args['level'] = isset($args['level']) ? (int) $args['level'] : 1;

        // base file and class name
        $file = 'include/SugarForecasting/ReportingUsers.php';
        $klass = 'SugarForecasting_ReportingUsers';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        $reportees = $obj->process();

        if (($args['level'] < 0 || $args['level'] > 1)) {
            // may contain parent
            $children = isset($reportees['children']) ? $reportees['children'] : $reportees[1]['children'];

            foreach ($children as &$child) {
                if ($child['metadata']['id'] != $args['user_id']) {
                    $childArgs = $args;
                    $childArgs['user_id'] = $child['metadata']['id'];
                    $childArgs['level'] = $args['level'] - 1;
                    $childReportees = $this->getReportees($api, $childArgs);
                    $child['children'] = isset($childReportees['children']) ? $childReportees['children'] : $childReportees[1]['children'];
                }
            }

            isset($reportees['children']) ? $reportees['children'] = $children : $reportees[1]['children'] = $children;
        }

        return $reportees;
    }

    /**
     * Retrieve an array of Users and their tree state that report to the user that was passed in
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array|string of users that reported to specified/current user
     */
    public function getOrgTree(ServiceBase $api, array $args)
    {
        $args['user_id'] = isset($args["user_id"]) ? $args["user_id"] : $GLOBALS["current_user"]->id;
        $args['level'] = isset($args['level']) ? (int) $args['level'] : 1;

        // base file and class name
        $file = 'include/SugarForecasting/ReportingUsers.php';
        $klass = 'SugarForecasting_ReportingUsers';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        $reportees = $obj->process();

        $isManager = false;
        $isSalesRep = false;

        if (isset($reportees['metadata'])) {
            //associative array
            $isManager = true;
        } else if (is_array($reportees) && count($reportees) === 2) {
            //array of associative arrays
            $isSalesRep = true;
        }

        if ($isManager) {
            $rootId = $reportees['metadata']['id'];
            if (isset($reportees['children'])) {
                $children = $reportees['children'];
            }
        } else if ($isSalesRep) {
            $rootId = $reportees[1]['metadata']['id'];
            if (isset($reportees[1]['children'])) {
                $children = $reportees[1]['children'];
            }
        }

        if (isset($children)) {

            foreach ($children as $childKey => &$child) {

                if ($rootId === $args['user_id'] && $child['metadata']['reports_to_id'] !== $args['user_id']) {
                    //get rid of my_opportunity elemetns
                    unset($children[$childKey]);
                } else if ($rootId !== $args['user_id'] && $child['metadata']['id'] !== $args['user_id']) {
                    //get rid of sibling elements if a sales rep
                    unset($children[$childKey]);
                } else if ($rootId === $args['user_id'] && $child['metadata']['is_manager'] === true && $args['level'] > 1) {
                    $childArgs = $args;
                    $childArgs['user_id'] = $child['metadata']['id'];
                    $childArgs['level'] = $args['level'] - 1;

                    $childReportees = $this->getOrgTree($api, $childArgs);

                    $child['children'] = isset($childReportees['children']) ? $childReportees['children'] : $childReportees[1]['children'];
                }
            }

            if ($isManager) {
                $reportees['children'] = $children;
            } else if ($isSalesRep) {
                $reportees[1]['children'] = $children;
            }
        }

        return $reportees;
    }

    /**
     * @param Administration $admin
     * @param array $forecastsSettings
     * @param ServiceBase $api
     * @throws SugarApiExceptionInvalidHash
     */
    protected function compareSettingsToDefaults(Administration $admin, $forecastsSettings, ServiceBase $api)
    {
        $defaultConfig = ForecastsDefaults::getDefaults();
        $missing_config = array_diff(array_keys($defaultConfig), array_keys($forecastsSettings));
        if (!empty($missing_config)) {
            foreach ($missing_config as $config) {
                $val = $defaultConfig[$config];
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                $admin->saveSetting('Forecasts', $config, $val, $api->platform);
            }
            MetaDataManager::refreshModulesCache(array("Forecasts"));
            MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_CONFIG));
            throw new SugarApiExceptionInvalidHash();
        }
    }

    /**
     * Returns the Quota for a given timeperiod_id, user_id, and quota_type
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     */
    public function getQuota(ServiceBase $api, array $args)
    {
        if(!SugarACL::checkAccess('Quotas', 'access')) {
            throw new SugarApiExceptionNotAuthorized();
        }

        /* @var $quotaBean Quota */
        $quotaBean = $this->getBean('Quotas');

        $isRollup = ($args['quota_type'] == 'rollup');

        // add the manager's rollup quota to the data returned
        $data = $quotaBean->getRollupQuota($args['timeperiod_id'], $args['user_id'], $isRollup);

        // add if the manager is a top-level manager or not
        $data['is_top_level_manager'] = User::isTopLevelManager($args['user_id']);

        return $data;
    }

    /**
     * Utility method to make unit testing easier
     *
     * @param string $module The module to load
     * @param string $id The record id to load
     * @return SugarBean
     */
    protected function getBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }
}
