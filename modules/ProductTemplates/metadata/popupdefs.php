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

$popupMeta = array (
    'moduleMain' => 'ProductTemplates',
    'varName' => 'ProductTemplate',
    'orderBy' => 'producttemplates.name',
    'whereClauses' => array (
        'name' => 'producttemplates.name',
        'category_name' => 'producttemplates.category_name',
    ),
    'searchInputs' => array (
        'name',
        'category_name',
    ),
    'searchdefs' => array (
        'name',
        'category_name'
    ),
    'listviewdefs' => array (
        'NAME' =>
        array (
            'width' => '30',
            'label' => 'LBL_LIST_NAME',
            'link' => true,
            'default' => true,
            'name' => 'name',
        ),
        'TYPE_NAME' =>
        array (
            'width' => '10',
            'label' => 'LBL_LIST_TYPE',
            'sortable' => true,
            'default' => true,
            'name' => 'type_name',
        ),
        'CATEGORY_NAME' =>
        array (
            'width' => '10',
            'label' => 'LBL_LIST_CATEGORY',
            'sortable' => true,
            'default' => true,
            'name' => 'category_name',
        ),
        'STATUS' =>
        array (
            'width' => '10',
            'label' => 'LBL_LIST_STATUS',
            'default' => true,
            'name' => 'status',
        ),
        'QTY_IN_STOCK' =>
        array (
            'width' => '10',
            'label' => 'LBL_LIST_QTY_IN_STOCK',
            'default' => true,
            'name' => 'qty_in_stock',
        ),
        'COST_PRICE' =>
        array (
            'type' => 'currency',
            'label' => 'LBL_COST_PRICE',
            'currency_format' => true,
            'width' => '10',
            'default' => true,
            'name' => 'cost_price',
        ),
        'LIST_PRICE' =>
        array (
            'type' => 'currency',
            'label' => 'LBL_LIST_PRICE',
            'currency_format' => true,
            'width' => '10',
            'default' => true,
            'name' => 'list_price',
        ),
        'DISCOUNT_PRICE' =>
        array (
            'type' => 'currency',
            'label' => 'LBL_DISCOUNT_PRICE',
            'currency_format' => true,
            'width' => '10',
            'default' => true,
        ),
    ),
);