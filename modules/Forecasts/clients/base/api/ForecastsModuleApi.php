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

class ForecastsModuleApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array('Forecasts'),
                'pathVars' => array('module'),
                'method' => 'createRecord',
                'shortHelp' => 'This method creates a new record of the specified type',
                'longHelp' => 'include/api/help/module_new_help.html',
            ),
        );
    }

    public function createRecord(ServiceBase $api, array $args)
    {
        if (!SugarACL::checkAccess('Forecasts', 'edit')) {
            throw new SugarApiExceptionNotAuthorized('No access to edit records for module: Forecasts');
        }

        $obj = $this->getClass($args);
        return $obj->save();
    }

    /**
     * Get the Committed Class
     *
     * @param array $args
     * @return SugarForecasting_Committed
     */
    protected function getClass(array $args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Committed.php';
        $klass = 'SugarForecasting_Committed';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);

        return $obj;
    }
}
