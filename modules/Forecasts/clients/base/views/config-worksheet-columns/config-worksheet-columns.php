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

$viewdefs['Forecasts']['base']['view']['config-worksheet-columns'] = array(
    'label' => 'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS',
    'panels' => array(
        array(
            'fields' => array(
                // Default enabled columns
                // the order is split between this file and
                // the fields in the ForecastReset.php file.
                // its used when the list view is written out as currently
                // custom ordering is not allowed
                array(
                    'name' => 'commit_stage',
                    'label' => 'LBL_FORECAST',
                    'locked' => true,
                    'order' => 0
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_NAME',
                    'label_module' => 'Opportunities',
                    'locked' => true,
                    'order' => 1
                ),
                array(
                    'name' => 'account_name',
                    'label' => 'LBL_LIST_ACCOUNT_NAME',
                    'label_module' => 'RevenueLineItems',
                    'order' => 5
                ),
                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_DATE_CLOSED',
                    'label_module' => 'RevenueLineItems',
                    'order' => 6
                ),
                array(
                    'name' => 'sales_stage',
                    'label' => 'LBL_SALES_STAGE',
                    'label_module' => 'Products',
                    'order' => 10
                ),
                array(
                    'name' => 'probability',
                    'label' => 'LBL_OW_PROBABILITY',
                    'order' => 11
                ),
                array(
                    'name' => 'worst_case',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST',
                    'locked' => true,
                    'order' => 12
                ),
                array(
                    'name' => 'likely_case',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY',
                    'locked' => true,
                    'order' => 13
                ),
                array(
                    'name' => 'best_case',
                    'label' => 'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST',
                    'locked' => true,
                    'order' => 14
                ),
                // Non-default-enabled columns
                array(
                    'name' => 'product_type',
                    'label' => 'LBL_TYPE',
                    'label_module' => 'RevenueLineItems',
                    'order' => 25
                ),
                array(
                    'name' => 'lead_source',
                    'label' => 'LBL_LEAD_SOURCE',
                    'label_module' => 'Contacts',
                    'order' => 26
                ),
                array(
                    'name' => 'campaign_name',
                    'label' => 'LBL_CAMPAIGN',
                    'order' => 27
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO_NAME',
                    'order' => 28
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_TEAMS',
                    'order' => 29
                ),
                array(
                    'name' => 'next_step',
                    'label' => 'LBL_NEXT_STEP',
                    'label_module' => 'RevenueLineItems',
                    'order' => 30
                ),
                array(
                    'name' => 'description',
                    'label' => 'LBL_DESCRIPTION',
                    'label_module' => 'RevenueLineItems',
                    'order' => 31
                ),
            )
        )
    )
);
