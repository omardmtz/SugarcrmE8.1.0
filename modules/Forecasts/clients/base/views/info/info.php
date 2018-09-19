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

$viewdefs['Forecasts']['base']['view']['info'] = array(
    'timeperiod' => array(
        array(
            'name' => 'selectedTimePeriod',
            'label' => 'LBL_TIMEPERIOD_NAME',
            'type' => 'timeperiod',
            'css_class' => 'forecastsTimeperiod',
            'dropdown_class' => 'topline-timeperiod-dropdown',
            'dropdown_width' => 'auto',
            'view' => 'edit',
            // options are set dynamically in the view
            'default' => true,
            'enabled' => true,
        ),
    ),
    'last_commit' => array(
        array(
            'name' => 'lastCommitDate',
            'type' => 'lastcommit',
            'datapoints' => array(
                'worst_case',
                'likely_case',
                'best_case'
            )
        )
    ),
    'commitlog' => array(
        array(
            'name' => 'commitLog',
            'type' => 'commitlog',
        )
    ),
    'datapoints' => array(
        array(
            'name' => 'quota',
            'label' => 'LBL_QUOTA',
            'type' => 'quotapoint'
        ),
        array(
            'name' => 'worst_case',
            'label' => 'LBL_WORST',
            'type' => 'datapoint'
        ),
        array(
            'name' => 'likely_case',
            'label' => 'LBL_LIKELY',
            'type' => 'datapoint'
        ),
        array(
            'name' => 'best_case',
            'label' => 'LBL_BEST',
            'type' => 'datapoint'
        )
    ),
);
