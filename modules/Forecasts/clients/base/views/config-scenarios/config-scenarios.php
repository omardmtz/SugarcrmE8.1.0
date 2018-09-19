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
$viewdefs['Forecasts']['base']['view']['config-scenarios'] = array(
    'label' => 'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS',
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'show_worksheet_likely',
                    'type' => 'bool',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY',
                    'default' => false,
                    'enabled' => true,
                    'view' => 'detail',
                ),
                array(
                    'name' => 'show_worksheet_best',
                    'type' => 'bool',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST',
                    'default' => false,
                    'enabled' => true,
                    'view' => 'forecastsWorksheet',
                ),
                array(
                    'name' => 'show_worksheet_worst',
                    'type' => 'bool',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST',
                    'default' => false,
                    'enabled' => true,
                    'view' => 'forecastsWorksheet',
                ),
            ),
        ),
        //TODO-sfa - this will be revisited in a future sprint and determined whether it should go in 6.7, 6.8 or later
    ),
);
