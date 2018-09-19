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
$viewdefs['RevenueLineItems']['base']['view']['subpanel-for-opportunities-create'] = array(
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'css_class' => 'btn deleteBtn',
                'icon' => 'fa-minus',
                'event' => 'list:deleterow:fire',
            ),
            array(
                'type' => 'rowaction',
                'css_class' => 'btn addBtn',
                'icon' => 'fa-plus',
                'event' => 'list:addrow:fire',
            ),
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true
                ),
                'date_closed',
                array(
                    'name' => 'worst_case',
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                        'total_amount',
                        'quantity',
                        'discount_amount',
                        'discount_price'
                    ),
                    'showTransactionalAmount' => true,
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'likely_case',
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                        'total_amount',
                        'quantity',
                        'discount_amount',
                        'discount_price'
                    ),
                    'showTransactionalAmount' => true,
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'best_case',
                    'type' => 'currency',
                    'related_fields' => array(
                        'currency_id',
                        'base_rate',
                        'total_amount',
                        'quantity',
                        'discount_amount',
                        'discount_price'
                    ),
                    'showTransactionalAmount' => true,
                    'convertToBase' => true,
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true
                ),
                'sales_stage',
                array(
                    'name' => 'probability',
                    'readonly' => true
                ),
                'commit_stage',
                array(
                    'name' => 'product_template_name',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'category_name',
                    'enabled' => true,
                    'default' => true
                ),
                'quantity',
                array(
                    'name' => 'assigned_user_name',
                    'enabled' => true,
                    'default' => true
                )
            )
        ),
    ),
);
