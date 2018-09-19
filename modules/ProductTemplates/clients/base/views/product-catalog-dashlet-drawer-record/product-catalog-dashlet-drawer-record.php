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

$viewdefs['ProductTemplates']['base']['view']['product-catalog-dashlet-drawer-record'] = array(
    'buttons' => array(
        array(
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'view',
            'events' => array(
                'click' => 'button:cancel_button:click',
            ),
        ),
        array(
            'type' => 'button',
            'event' => 'button:add_to_quote_button:click',
            'name' => 'add_to_quote_button',
            'label' => 'LBL_ADD_TO_QUOTE_BUTTON',
            'css_class' => 'btn btn-primary',
            'showOn' => 'view',
            'showOnModules' => array(
                'Quotes' => array(
                    'create',
                    'record',
                ),
                'Opportunities' => array(
                    'create',
                    'record',
                ),
            ),
            'events' => array(
                'click' => 'button:add_to_quote_button:click',
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_header',
            'header' => true,
            'fields' => array(
                array(
                    'name'          => 'picture',
                    'type'          => 'avatar',
                    'size'          => 'large',
                    'dismiss_label' => true,
                    'readonly'      => true,
                ),
                array(
                    'name' => 'name',
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labels' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                'status',
                array(
                    'name' => 'website',
                    'type' => 'url'),
                'date_available',
                'tax_class',
                'qty_in_stock',
                'category_name',
                'manufacturer_name',
                'mft_part_num',
                'vendor_part_num',
                'weight',
                'type_name',
                array(
                    'name' => 'cost_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'cost_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true,
                ),
                'cost_usdollar',
                'date_cost_price',
                array(
                    'name' => 'discount_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'discount_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true,
                ),
                'discount_usdollar',
                array(
                    'name' => 'list_price',
                    'type' => 'currency',
                    'related_fields' => array(
                        'list_usdollar',
                        'currency_id',
                        'base_rate',
                    ),
                    'currency_field' => 'currency_id',
                    'base_rate_field' => 'base_rate',
                    'enabled' => true,
                    'default' => true,
                ),
                'list_usdollar',
                array(
                    'name' => 'pricing_formula',
                    'related_fields' => array(
                        'pricing_factor',
                    ),
                ),
                array(
                    'name' => 'description',
                    'span' => 12,
                ),
                'support_name',
                'support_description',
                'support_contact',
                'support_term',
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
            ),
        ),
    ),
);
