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


class ForecastManagerWorksheetsApi extends SugarApi
{
    public function registerApiRest()
    {
        //Extend with test method
        return array(
            'forecastManagerWorksheetAssignQuota' => array(
                'reqType' => 'POST',
                'path' => array('ForecastManagerWorksheets', 'assignQuota'),
                'pathVars' => array('module', 'action'),
                'method' => 'assignQuota',
                'shortHelp' => 'Assign the Quota for Users with out actually committing',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastWorksheetManagerAssignQuota.html',
            )
        );
    }

    /**
     * Run the assign Quota Code.
     *
     * @param ServiceBase $api          API Service
     * @param array $args               Args from the XHR Call
     * @return array
     */
    public function assignQuota(ServiceBase $api, array $args = array())
    {
        /* @var $mgr_worksheet ForecastManagerWorksheet */
        $mgr_worksheet = $this->getBean($args['module']);
        $ret = $mgr_worksheet->assignQuota($args['user_id'], $args['timeperiod_id']);
        return array('success' => $ret);
    }

    /**
     * Utility method to get a bean
     *
     * @param string $module
     * @return SugarBean
     */
    protected function getBean($module)
    {
        return BeanFactory::newBean($module);
    }
}
