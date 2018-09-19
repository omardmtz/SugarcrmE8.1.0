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

$viewdefs['base']['view']['bubblechart'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME',
            'description' => 'LBL_TOP10_OPPORTUNITIES_CHART_DESC',
            'config' => array(
                'filter_duration' => 'current',
            ),
            'preview' => array(),
            'filter' => array(
                'module' => array(
                    'Home',
                    'Accounts',
                    'Contacts',
                    'Leads',
                    'Opportunities',
                    'RevenueLineItems',
                ),
                'view' => array(
                    'record',
                    'records',
                ),
            ),
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
                    'name' => 'filter_duration',
                    'label' => 'LBL_TOP10_OPPORTUNITIES_FILTER_DURATIONS',
                    'type' => 'timeperiod',
                    'options' => 'generic_timeperiod_options',
                    'use_generic_timeperiods' => true,
                    'enum_width' => 'auto',
                ),
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_TOP10_OPPORTUNITIES_DEFAULT_DATASET',
                    'type' => 'enum',
                    'options' => 'top10_opportunities_visibility_options',
                    'enum_width' => 'auto',
                ),
            ),
        ),
    ),
    'filter_duration' => array(
        array(
            'name' => 'filter_duration',
            'label' => 'LBL_TOP10_OPPORTUNITIES_FILTER_DURATIONS',
            'type' => 'timeperiod',
            'options' => 'generic_timeperiod_options',
            'use_generic_timeperiods' => true,
        ),
    ),
);
