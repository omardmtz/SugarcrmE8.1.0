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
$viewdefs['Products']['base']['view']['quote-data-group-list'] = array(
    'panels' => array(
        array(
            'name' => 'products_quote_data_group_list',
            'label' => 'LBL_PRODUCTS_QUOTE_DATA_LIST',
            'fields' => array(
                array(
                    'name' => 'line_num',
                    'label' => null,
                    'widthClass' => 'cell-xsmall',
                    'css_class' => 'line_num tcenter',
                    'type' => 'line-num',
                    'readonly' => true,
                ),
                array(
                    'name' => 'quantity',
                    'label' => 'LBL_QUANTITY',
                    'widthClass' => 'cell-small',
                    'css_class' => 'quantity',
                    'type' => 'float',
                ),
                array(
                    'name' => 'product_template_name',
                    'label' => 'LBL_ITEM_NAME',
                    'widthClass' => 'cell-large',
                    'type' => 'quote-data-relate',
                    'required' => true,
                ),
                array(
                    'name' => 'mft_part_num',
                    'label' => 'LBL_MFT_PART_NUM',
                    'type' => 'base',
                ),
                array(
                    'name' => 'discount_price',
                    'label' => 'LBL_DISCOUNT_PRICE',
                    'type' => 'currency',
                    'convertToBase' => true,
                    'showTransactionalAmount' => true,
                    'related_fields' => array(
                        'discount_price',
                        'currency_id',
                        'base_rate',
                    ),
                ),
                array(
                    'name' => 'discount',
                    'type' => 'fieldset',
                    'css_class' => 'quote-discount-percent',
                    'label' => 'LBL_DISCOUNT_AMOUNT',
                    'fields' => array(
                        array(
                            'name' => 'discount_amount',
                            'label' => 'LBL_DISCOUNT_AMOUNT',
                            'type' => 'discount',
                            'convertToBase' => true,
                            'showTransactionalAmount' => true,
                        ),
                        array(
                            'type' => 'discount-select',
                            'name' => 'discount_select',
                            'no_default_action' => true,
                            'buttons' => array(
                                array(
                                    'type' => 'rowaction',
                                    'name' => 'select_discount_amount_button',
                                    'label' => 'LBL_DISCOUNT_AMOUNT',
                                    'event' => 'button:discount_select_change:click',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'name' => 'select_discount_percent_button',
                                    'label' => 'LBL_DISCOUNT_PERCENT',
                                    'event' => 'button:discount_select_change:click',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'name' => 'total_amount',
                    'label' => 'LBL_LINE_ITEM_TOTAL',
                    'type' => 'currency',
                    'widthClass' => 'cell-medium',
                    'showTransactionalAmount' => true,
                    'related_fields' => array(
                        'total_amount',
                        'currency_id',
                        'base_rate',
                    ),
                ),
            ),
        ),
    ),
);
