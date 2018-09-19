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

$viewdefs['RevenueLineItems']['base']['view']['subpanel-for-opportunities'] = array(
    'type' => 'subpanel-list',
    'favorite' => true,
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
                    'default' => true,
                    'related_fields' => array(
                        'mft_part_num',
                    ),
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
                array(
                    'name' => 'account_name',
                    'readonly' => true,
                    'enabled' => true,
                    'default' => true
                ),
                'sales_stage',
                'probability',
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
                    'name' => 'quote_name',
                    'label' => 'LBL_ASSOCIATED_QUOTE',
                    'related_fields' => array('quote_id'),
                    // this is a hack to get the quote_id field loaded
                    'readonly' => true,
                    'bwcLink' => true,
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'assigned_user_name',
                    'enabled' => true,
                    'default' => true
                )
            )
        ),
    ),
    'selection' => array (
        'type' => 'multi',
        'actions' => array (
            array (
                'name' => 'quote_button',
                'type' => 'button',
                'label' => 'LBL_GENERATE_QUOTE',
                'primary' => true,
                'events' => array (
                    'click' => 'list:massquote:fire',
                ),
                'acl_module' => 'Quotes',
                'acl_action' => 'create',
                'related_fields' => array(
                    'account_id',
                    'account_name',
                    'assigned_user_id',
                    'assigned_user_name',
                    'base_rate',
                    'best_case',
                    'book_value',
                    'category_id',
                    'category_name',
                    'commit_stage',
                    'cost_price',
                    'currency_id',
                    'date_closed',
                    'deal_calc',
                    'likely_case',
                    'list_price',
                    'mft_part_num',
                    'my_favorite',
                    'name',
                    'probability',
                    'product_template_id',
                    'product_template_name',
                    'quote_id',
                    'quote_name',
                    'worst_case',
                ),
            ),
            array(
                'name' => 'massdelete_button',
                'type' => 'button',
                'label' => 'LBL_DELETE',
                'acl_action' => 'delete',
                'primary' => true,
                'events' => array(
                    'click' => 'list:massdelete:fire',
                ),
                'related_fields' => array('sales_stage')
            ),
        ),
    ),
    'rowactions' => array(
        'css_class' => 'pull-right',
        'actions' => array(
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
            ),
            array(
                'type' => 'rowaction',
                'name' => 'edit_button',
                'icon' => 'fa-pencil',
                'label' => 'LBL_EDIT_BUTTON',
                'event' => 'list:editrow:fire',
                'acl_action' => 'edit',
            ),
        ),
    ),
);
