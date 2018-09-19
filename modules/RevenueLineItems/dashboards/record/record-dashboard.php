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

return array(
    'metadata' =>
    array(
        'components' =>
        array(
            array(
                'rows' =>
                array(
                    array(
                        array(
                            'view' =>
                            array(
                                'type' => 'forecastdetails-record',
                                'label' => 'LBL_DASHLET_FORECAST_NAME',
                            ),
                            'context' =>
                            array(
                                'module' => 'Forecasts',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' =>
                            array(
                                'type' => 'forecast-pareto',
                                'label' => 'LBL_DASHLET_FORECASTS_CHART_NAME',
                            ),
                            'context' =>
                            array(
                                'module' => 'Forecasts',
                            ),
                            'width' => 12,
                        ),
                    ),
                    array(
                        array(
                            'view' =>
                                array(
                                    'name' => 'active-tasks',
                                    'label' => 'LBL_ACTIVE_TASKS_DASHLET',
                                ),
                            'width' => 12,
                        ),
                    ),
                ),
                'width' => 12,
            ),
        ),
    ),
    'name' => 'LBL_REVENUE_LINE_ITEMS_RECORD_DASHBOARD',
);
