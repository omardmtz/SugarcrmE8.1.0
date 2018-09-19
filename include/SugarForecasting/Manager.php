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

// This class is used for the Manager Views
class SugarForecasting_Manager extends SugarForecasting_AbstractForecast implements SugarForecasting_ForecastSaveInterface
{

    /**
     * Class Constructor
     *
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        // set the isManager Flag just incase we need it
        $this->isManager = true;

        parent::__construct($args);

        // set the default data timeperiod to the set timeperiod
        $this->defaultData['timeperiod_id'] = $this->getArg('timeperiod_id');
    }

    /**
     * Run all the tasks we need to process get the data back
     *
     * @deprecated @see ForecastManagerWorksheetsFilterApi
     * @return array
     */
    public function process()
    {
        return array();
    }

    /**
     * Save the Manager Worksheet, This method is deprecated and should be done though use of
     * the ForecastManagerWorksheet bean
     *
     * @deprecated
     * @return string
     */
    public function save()
    {
        return '';
    }
}
