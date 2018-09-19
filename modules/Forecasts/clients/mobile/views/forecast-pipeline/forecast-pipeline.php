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

$viewdefs['Forecasts']['mobile']['view']['forecast-pipeline'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_PIPELINE_CHART_NAME',
            'description' => 'LBL_DASHLET_PIPELINE_CHART_DESC',
            'config' => array(
                'module' => 'Forecasts'
            ),
            'preview' => array(
                'module' => 'Forecasts'
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                    'Accounts',
                    'Opportunities',
                    'RevenueLineItems'
                ),
                'view' => array(
                    'record',
                    'records'
                )
            )
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_body',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'type' => 'enum',
                    'options' => 'forecast_pipeline_visibility_options',
                    'enum_width' => 'auto',
                ),
            ),
        ),
    ),
    'timeperiod' => array(
        array(
            'name' => 'selectedTimePeriod',
            'label' => 'TimePeriod',
            'type' => 'timeperiod',
        ),
    )
);
