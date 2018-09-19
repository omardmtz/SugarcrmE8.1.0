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

$viewdefs['base']['view']['forecastdetails'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_FORECAST_NAME',
            'description' => 'LBL_DASHLET_FORECASTS_DETAILS_DESC',
            'config' => array(
                'module' => 'Forecasts'
            ),
            'preview' => array(
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                    'Forecasts',
                ),
                'view' => array(
                    'record',
                    'records'
                )
            )
        ),
    ),
    'timeperiod' => array(
        array(
            'module' => 'Forecasts',
            'name' => 'selectedTimePeriod',
            'label' => 'TimePeriod',
            'type' => 'timeperiod',
            'default' => true,
            'enabled' => true,
            'view' => 'edit',
        ),
    ),
);
