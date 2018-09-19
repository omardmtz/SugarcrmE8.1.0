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



class ForecastsProgressApi extends ModuleApi
{

    /**
     * uuid for the selected user
     *
     * @var string
     */
	protected $user_id;
    /**
     * uuid for the current/selected timeperiod
     *
     * @var string
     */
	protected $timeperiod_id;
    /**
     * Opportunity Bean used to create the opportunity queries
     *
     * @var Opportunity
     */
	protected $opportunity;
    /**
     * array of sales stages to denote as closed('lost')
     *
     * @var array
     */
    protected $sales_stage_lost = Array();
    /**
     * array of sales stages to denote as closed('won')
     *
     * @var array
     */
    protected $sales_stage_won = Array();

    /**
     * Rest Api Registration Method
     *
     * @return array
     */
    public function registerApiRest()
    {
        return array(
            'progressRep' => array(
                'reqType'   => 'GET',
                'path'      => array('Forecasts', '?', 'progressRep', '?'),
                'pathVars'  => array('', 'timeperiod_id', '', 'user_id'),
                'method'    => 'progressRep',
                'shortHelp' => 'Projected Rep data',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastProgressRepApi.html',
            ),
            'progressManager' => array(
                'reqType'   => 'GET',
                'path'      => array('Forecasts', '?', 'progressManager', '?'),
                'pathVars'  => array('', 'timeperiod_id', '', 'user_id'),
                'method'    => 'progressManager',
                'shortHelp' => 'Progress Manager data',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastProgressManagerApi.html',
            )
        );
    }

    /**
     * loads data and passes back an array to communicate data that may be missing.  The array is the same
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function progressRep(ServiceBase $api, array $args)
    {
        $args['user_id'] = clean_string($args["user_id"]);
        $args['timeperiod_id'] = clean_string($args["timeperiod_id"]);

        // base file and class name
        $file = 'include/SugarForecasting/Progress/Individual.php';
        $klass = 'SugarForecasting_Progress_Individual';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        return $obj->process();
	}

    /**
     * loads data and passes back an array to communicate data that may be missing.  The array is the same
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function progressManager(ServiceBase $api, array $args)
    {
        $args['user_id'] = clean_string($args["user_id"]);
        $args['timeperiod_id'] = clean_string($args["timeperiod_id"]);

        // base file and class name
        $file = 'include/SugarForecasting/Progress/Manager.php';
        $klass = 'SugarForecasting_Progress_Manager';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        return $obj->process();
	}
}
