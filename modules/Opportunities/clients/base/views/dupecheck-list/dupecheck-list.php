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
$viewdefs['Opportunities']['base']['view']['dupecheck-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
                    'enabled' => true,
                    'default' => true,
                    'related_fields' => array(
                        'total_revenue_line_items',
                        'closed_revenue_line_items',
                    ),
                ),
                array(
                    'name' => 'account_name',
                    'link' => true,
                    'label' => 'LBL_LIST_ACCOUNT_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'sales_stage',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'probability',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'amount',
                    'type' => 'currency',
                    'label' => 'LBL_LIKELY',
                    'related_fields' => array(
                        'amount',
                        'currency_id',
                        'base_rate',
                    ),
                    'readonly' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'opportunity_type',
                    'label' => 'LBL_TYPE',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'lead_source',
                    'label' => 'LBL_LEAD_SOURCE',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'next_step',
                    'label' => 'LBL_NEXT_STEP',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_DATE_CLOSED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                    'related_fields' => array(
                        'date_closed_timestamp'
                    )
                ),
                array(
                    'name' => 'created_by_name',
                    'label' => 'LBL_CREATED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'team_name',
                    'type' => 'teamset',
                    'label' => 'LBL_LIST_TEAM',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'id' => 'ASSIGNED_USER_ID',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'modified_by_name',
                    'label' => 'LBL_MODIFIED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
