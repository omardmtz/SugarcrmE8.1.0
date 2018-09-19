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


class ForecastsChartApi extends SugarApi
{
    /**
     * Rest Api Registration Method
     *
     * @return array
     */
    public function registerApiRest()
    {
        $parentApi = array(
            'forecasts_chart' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', '?', '?', 'chart', '?'),
                'pathVars' => array('', 'timeperiod_id', 'user_id', '', 'display_manager'),
                'method' => 'chart',
                'shortHelp' => 'Retrieve the Chart data for the given data in the Forecast Module',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastChartApi.html',
            ),
        );
        return $parentApi;
    }

    /**
     * Build out the chart for the sales rep view in the forecast module
     *
     * @param ServiceBase $api      The Api Class
     * @param array $args           Service Call Arguments
     * @return mixed
     */
    public function chart(ServiceBase $api, array $args)
    {
        $args['timeperiod_id'] = clean_string($args['timeperiod_id']);
        $args['user_id'] = clean_string($args['user_id']);
        $args['group_by'] = !isset($args['group_by']) ? "forecast" : $args['group_by'];

        // default to the Individual Code
        $file = 'include/SugarForecasting/Chart/Individual.php';
        $klass = 'SugarForecasting_Chart_Individual';

        // test to see if we need to display the manager
        if((bool)$args['display_manager'] && User::isManager($api->user->id)) {
            // we have a manager view, pull in the manager classes
            $file = 'include/SugarForecasting/Chart/Manager.php';
            $klass = 'SugarForecasting_Chart_Manager';
        }

        $obj = $this->getClass($file, $klass, $args);
        return $obj->process();
    }

    /**
     * Utility method to get the class
     *
     * @param string $file
     * @param string $klass
     * @param array $args
     * @return SugarForecasting_Chart_AbstractChart
     */
    protected function getClass($file, $klass, array $args)
    {
        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_Chart_AbstractChart */
        return new $klass($args);
    }
}
