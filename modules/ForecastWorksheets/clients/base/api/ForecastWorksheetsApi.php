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


class ForecastWorksheetsApi extends SugarApi
{

    public function registerApiRest()
    {
        return array(
            'forecastWorksheetSave' => array(
                'reqType' => 'PUT',
                'path' => array('ForecastWorksheets', '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'forecastWorksheetSave',
                'shortHelp' => 'Updates a ForecastWorksheet model',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastWorksheetPut.html',
            ),
        );
    }

    /**
     * This method handles saving data for the /ForecastsWorksheet REST endpoint
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array Worksheet data entries
     * @throws SugarApiExceptionNotAuthorized
     */
    public function forecastWorksheetSave(ServiceBase $api, array $args)
    {
        $obj = $this->getClass($args);
        $bean = $obj->save();

        return $this->formatBean($api, $args, $bean);
    }


    /**
     * @param array $args
     * @return SugarForecasting_Individual
     */
    protected function getClass(array $args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Individual.php';
        $klass = 'SugarForecasting_Individual';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);

        return $obj;
    }
}
