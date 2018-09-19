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

class ForecastsFilterApi extends FilterApi
{

    public function registerApiRest()
    {
        return array(
            'filterModuleGet' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'filterList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Filter records from a single module',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsFilter.html',
                'exceptions' => array(
                    'SugarApiExceptionError',
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ),
            ),
            'filterModulePost' => array(
                'reqType' => 'POST',
                'path' => array('Forecasts', 'filter'),
                'pathVars' => array('module', ''),
                'method' => 'filterList',
                'shortHelp' => 'Filter records from a single module',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastsFilter.html',
                'exceptions' => array(
                    'SugarApiExceptionError',
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ),
            ),
        );
    }

    /**
     * forecastsCommitted -- only left in for testing purposes
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function forecastsCommitted(ServiceBase $api, array $args)
    {

        // if no timeperiod is set, just set it to false, and the current time period will be set
        if (!isset($args['timeperiod_id'])) {
            $args['timeperiod_id'] = false;
        }
        // if no user id is set, just set it to false so it will use the default user
        if (!isset($args['user_id'])) {
            $args['user_id'] = false;
        }
        // make sure the type arg is set to prevent notices
        if (!isset($args['forecast_type'])) {
            $args['forecast_type'] = false;
        }

        $args['filter'] = $this->createFilter($api, $args['user_id'], $args['timeperiod_id'], $args['forecast_type']);

        return parent::filterList($api, $args);
    }

    /**
     * Forecast Worksheet Filter API Handler
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     */
    public function filterList(ServiceBase $api, array $args, $acl = 'list')
    {
        if (!SugarACL::checkAccess('Forecasts', $acl)) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: Forecasts');
        }

        // some local variables
        $found_assigned_user = false;
        $found_timeperiod = false;
        $found_type = false;

        // if filter is not defined, define it
        if (!isset($args['filter']) || !is_array($args['filter'])) {
            $args['filter'] = array();
        }

        if (isset($args['filter'][0]['$tracker'])) {
            return array('next_offset' => -1, 'records' => array());
        }

        // if there are filters set, process through them
        if (!empty($args['filter'])) {
            // todo-sfa: clean this up as it currently doesn't handle much in the way of nested arrays
            foreach ($args['filter'] as $key => $filter) {
                reset($filter);
                $filter_key = key($filter);
                // if the key is assigned_user_id, take the value and save it for later
                if ($found_assigned_user == false && $filter_key == 'user_id') {
                    $found_assigned_user = array_pop($filter);
                }
                // if the key is timeperiod_id, take the value, save it for later, and remove the filter
                if ($found_timeperiod == false && $filter_key == 'timeperiod_id') {
                    $found_timeperiod = array_pop($filter);
                    // remove the timeperiod_id
                    unset($args['filter'][$key]);
                }
                if ($found_type == false && $filter_key == 'forecast_type') {
                    $found_type = array_pop($filter);
                    unset($args['filter'][$key]);
                }
            }
        }

        $args['filter'] = $this->createFilter($api, $found_assigned_user, $found_timeperiod, $found_type);

        return parent::filterList($api, $args, $acl);
    }

    /**
     * Utility Method to create the filter for the filer API to use
     *
     * @param ServiceBase $api                  Service Api Class
     * @param mixed $user_id                    Passed in User ID, if false, it will use the current use from $api->user
     * @param mixed $timeperiod_id              TimePeriod Id, if false, the current time period will be found an used
     * @param string $forecast_type             Type of forecast to return, direct or rollup
     * @return array                            The Filer array to be passed back into the filerList Api
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function createFilter(ServiceBase $api, $user_id, $timeperiod_id, $forecast_type)
    {
        $filter = array();

        // if we did not find a user in the filters array, set it to the current user's id
        if ($user_id == false) {
            // use the current user, since on one was passed in
            $user_id = $api->user->id;
        } else {
            // make sure that the passed in user is a valid user
            /* @var $user User */
            // we use retrieveBean so it will return NULL and not an empty bean if the $args['user_id'] is invalid
            $user = BeanFactory::retrieveBean('Users', $user_id);
            if (is_null($user) || is_null($user->id)) {
                throw new SugarApiExceptionInvalidParameter('Provided User is not valid');
            }

            # if they are not a manager, don't show them committed number for others
            global $mod_strings, $current_language;
            $mod_strings = return_module_language($current_language, 'Forecasts');

            if ($user_id != $api->user->id && !User::isManager($api->user->id)) {
                throw new SugarApiExceptionNotAuthorized(
                    string_format($mod_strings['LBL_ERROR_NOT_MANAGER'], array($api->user->id, $user_id))
                );
            }
        }

        // set the assigned_user_id
        array_push($filter, array('user_id' => $user_id));

        if ($forecast_type !== false) {
            // make sure $forecast_type is valid (e.g. Direct or Rollup)
            switch (strtolower($forecast_type)) {
                case 'direct':
                case 'rollup':
                    break;
                default:
                    throw new SugarApiExceptionInvalidParameter(
                        'Forecast Type of ' . $forecast_type . ' is not valid. Valid options Direct or Rollup.'
                    );
            }
            // set the forecast type, make sure it's always capitalized
            array_push($filter, array('forecast_type' => ucfirst($forecast_type)));
        }

        // if we didn't find a time period, set the time period to be the current time period
        if ($timeperiod_id == false) {
            $timeperiod_id = TimePeriod::getCurrentId();
        }

        // fix up the timeperiod filter
        /* @var $tp TimePeriod */
        // we use retrieveBean so it will return NULL and not an empty bean if the $args['timeperiod_id'] is invalid
        $tp = BeanFactory::retrieveBean('TimePeriods', $timeperiod_id);
        if (is_null($tp) || is_null($tp->id)) {
            throw new SugarApiExceptionInvalidParameter('Provided TimePeriod is not valid');
        }
        array_push($filter, array('timeperiod_id' => $tp->id));

        return $filter;
    }

}
