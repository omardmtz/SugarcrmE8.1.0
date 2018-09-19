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

class PipelineChartApi extends SugarApi
{
    /**
     * What modules allow pipeline data
     *
     * @var array
     */
    protected $allowedModules = array(
        'RevenueLineItems',
        'Opportunities',
        'Products'
    );

    /**
     * Map of what the amount field is for a given module
     * @var array
     */
    protected $moduleAmountField = array(
        'RevenueLineItems' => 'likely_case',
        'Opportunities' => 'amount',
        'Products' => 'likely_case'
    );

    /**
     * Endpoints to register
     *
     * @return array
     */
    public function registerApiRest()
    {
        return array(
            'pipeline' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'chart', 'pipeline'),
                'pathVars' => array('module', '', ''),
                'method' => 'pipeline',
                'shortHelp' => 'Get the current opportunity pipeline data for the current timeperiod',
                'longHelp' => 'modules/Opportunities/clients/base/api/help/OpportunitiesPipelineChartApi.html',
            ),
            'pipelineWithTimeperiod' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'chart', 'pipeline', '?'),
                'pathVars' => array('module', '', '', 'timeperiod_id'),
                'method' => 'pipeline',
                'shortHelp' => 'Get the current opportunity pipeline data for a specific timeperiod',
                'longHelp' => 'modules/Opportunities/clients/base/api/help/OpportunitiesPipelineChartApi.html',
            ),
            'pipelineWithTimeperiodAndTeam' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'chart', 'pipeline', '?', '?'),
                'pathVars' => array('module', '', '', 'timeperiod_id', 'type'),
                'method' => 'pipeline',
                'shortHelp' => 'Get the current opportunity pipeline data for the current timeperiod',
                'longHelp' => 'modules/Opportunities/clients/base/api/help/OpportunitiesPipelineChartApi.html',
            ),
        );
    }

    /**
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionNotFound
     */
    public function pipeline(ServiceBase $api, array $args)
    {

        // if not in the allowed module list, then we throw a 404 not found
        if (!in_array($args['module'], $this->allowedModules)) {
            throw new SugarApiExceptionNotFound();
        }

        // make sure we can view the module first
        // since we don't have a proper record just make an empty one
        $args['record'] = '';
        /* @var $seed Opportunity|Product|RevenueLineItem */
        $seed = $this->loadBean($api, $args, 'view');
        if (!$seed->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized();
        }

        $tp = $this->getTimeperiod($args['timeperiod_id']);

        // check the type param
        if (!isset($args['type']) || ($args['type'] != 'user' && $args['type'] != 'group')) {
            $args['type'] = 'user';
        }

        $settings = $this->getForecastSettings();
        // get sales_stages to ignore
        $ignore_stages = array_merge($settings['sales_stage_won'], $settings['sales_stage_lost']);

        // get the amount field here
        $amount_field = $this->moduleAmountField[$seed->module_name];

        $sq = $this->buildQuery($api, $seed, $tp, $amount_field, $args['type']);

        // run the query
        $rows = $sq->execute();
        // data storage
        $data = array();
        // keep track of the total for later user
        $total = SugarMath::init('0');

        foreach ($rows as $row) {
            // if the sales stage is one we need to ignore, the just continue to the next record
            if (in_array($row['sales_stage'], $ignore_stages)) {
                continue;
            }

            // if we have not seen this sales stage before, set the value to zero (0)
            if (!isset($data[$row['sales_stage']])) {
                $data[$row['sales_stage']] = array('count' => 0, 'total' => '0');
            }

            // if customers have made amount not required, it saves to the DB as NULL
            // make sure we set it to 0 for the math ahead
            if (empty($row['amount'])) {
                $row['amount'] = 0;
            }
            // convert to the base currency
            $base_amount = SugarCurrency::convertWithRate($row[$amount_field], $row['base_rate']);

            // add the new value into what was already there
            $data[$row['sales_stage']]['total'] = SugarMath::init($data[$row['sales_stage']]['total'])->add(
                $base_amount
            )->result();
            $data[$row['sales_stage']]['count']++;

            // add to the total
            $total->add($base_amount);
        }

        // get the default currency for the user
        /* @var $currency Currency */
        $currency = Currency::getUserCurrency();

        // setup for return format
        $return_data = array();
        $series = 0;
        $previous_value = SugarMath::init('0');
        foreach ($data as $key => $item) {
            $value = SugarCurrency::convertAmountFromBase($item['total'], $currency->id);
            // set up each return key
            $return_data[] = array(
                'key' => $key,          // the label/sales stage
                'count' => $item['count'],
                'values' => array(      // the values used in the grid
                    array(
                        'series' => $series++,
                        'label' => SugarCurrency::formatAmount($value, $currency->id, 0),
                        // sending value by itself as 'y' gets manipulated by scale on the frontend
                        // this way we maintain the actual value's integrity
                        'value' => floatval($value),
                        'x' => 0,
                        'y' => intval($value),                  // this needs to be an integer
                        'y0' => intval($previous_value->result())         // this needs to be an integer
                    )
                )
            );
            // save the previous value for use in the next item in the series
            $previous_value->add($value);
        }

        // actually return the formatted data
        $mod_strings = return_module_language($GLOBALS['current_language'], $seed->module_name);
        //return the total from the SugarMath Object.
        $total = SugarCurrency::convertAmountFromBase($total->result(), $currency->id);
        return array(
            'properties' => array(
                'title' => $mod_strings['LBL_PIPELINE_TOTAL_IS'] . SugarCurrency::formatAmount($total, $currency->id),
                'total' => $total,
                'scale' => 1000,
                'units' => $currency->symbol
            ),
            'data' => $return_data
        );
    }

    /**
     * @param ServiceBase $api
     * @param SugarBean $seed
     * @param $tp
     * @param $amount_field
     * @param string $type
     * @return SugarQuery
     * @throws SugarQueryException
     */
    protected function buildQuery(ServiceBase $api, SugarBean $seed, $tp, $amount_field, $type = 'user')
    {
        // build out the query
        $sq = new SugarQuery();
        $sq->select(array('id', 'sales_stage', $amount_field, 'base_rate'));
        $sq->from($seed)
            ->where()
            ->gte('date_closed_timestamp', $tp->start_date_timestamp)
            ->lte('date_closed_timestamp', $tp->end_date_timestamp);

        $sq->orderBy('probability', 'DESC');

        // determine the type we need to fetch
        if ($type == 'user') {
            // we are only looking at our pipeline
            $sq->where()->equals('assigned_user_id', $api->user->id);
        } else {
            // we need to fetch ours + everyone under us (the whole tree)
            // get the reporting users
            $users = $this->getReportingUsers($api->user->id);
            // add current_user to the users_list
            array_unshift($users, $api->user->id);
            $sq->where()->in('assigned_user_id', array_values($users));
        }

        return $sq;
    }

    /**
     * @param String|Number $tp_id
     * @return TimePeriod
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function getTimeperiod($tp_id = '')
    {
        $forecast_settings = $this->getForecastSettings();
        if (SugarACL::checkAccess('Forecasts', 'access') && $forecast_settings['is_setup'] == 1) {
            // we have no timeperiod defined, so lets just pull the current one
            if (empty($tp_id)) {
                $tp_id = TimePeriod::getCurrentId();
            }

            /* @var $tp TimePeriod */
            // we use retrieveBean so it will return NULL and not an empty bean if the $args['timeperiod_id'] is invalid
            $tp = BeanFactory::retrieveBean('TimePeriods', $tp_id);
        } else {
            /* @var $tp TimePeriod */
            $tp = BeanFactory::newBean('TimePeriods');
            // generate the generic timeperiod based off the integer that was passed in.
            $data = $tp->getGenericStartEndByDuration($tp_id);
            // set the values
            $tp->id = 'fake_timeperiod';
            foreach ($data as $key => $value) {
                $tp->$key = $value;
            }
        }

        // if $tp is null or the id is empty, throw an exception
        if (is_null($tp) || empty($tp->id)) {
            throw new SugarApiExceptionInvalidParameter('Provided TimePeriod is invalid');
        }

        return $tp;
    }



    /**
     * Recursive Method to Retrieve the full tree of reportees for your team.
     *
     * @param string $user_id User to check for reportees on
     * @return array
     */
    protected function getReportingUsers($user_id)
    {
        $final_users = array();
        $reporting_users = User::getReporteesWithLeafCount($user_id);

        foreach ($reporting_users as $user => $reportees) {
            $final_users[] = $user;
            // if the user comes back with zero (0) for the count, don't try as they don't have any reportees
            if ($reportees > 0) {
                $final_users = array_merge($final_users, $this->getReportingUsers($user));
            }
        }

        return $final_users;
    }

    /**
     * Utility Method to get the Settings for Forecasting
     *
     * @codeCoverageIgnore
     * @return array
     */
    protected function getForecastSettings()
    {
        return Forecast::getSettings();
    }
}
