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

 
// ENT/ULT only fields
$fields = array(
    'NAME' => array (
        'width' => '40',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true
    ),
    'ACCOUNT_NAME' => array (
        'width' => '20',
        'label' => 'LBL_LIST_ACCOUNT_NAME',
        'id' => 'ACCOUNT_ID',
        'module' => 'Accounts',
        'link' => true,
        'default' => true,
        'ACLTag' => 'ACCOUNT',
        'related_fields' => array (
            'account_id'
        ),
        'sortable' => true
    ),
    'OPPORTUNITY_NAME' => array (
        'width' => '20',
        'label' => 'LBL_LIST_OPPORTUNITY_NAME',
        'id' => 'OPPORTUNITY_ID',
        'module' => 'Opportunities',
        'link' => true,
        'default' => true,
        'ACLTag' => 'OPPORTUNITY',
        'related_fields' => array (
            'opportunity_id'
        ),
        'sortable' => true
    ),
    'SALES_STAGE' => array (
        'width' => '10',
        'label' => 'LBL_LIST_SALES_STAGE',
        'link' => false,
        'default' => true
    ),
    'PROBABILITY' => array (
        'width' => '10',
        'label' => 'LBL_LIST_PROBABILITY',
        'link' => false,
        'default' => true
    ),
    'COMMIT_STAGE' => array (
        'width' => '10',
        'label' => 'LBL_LIST_COMMIT_STAGE',
        'link' => false,
        'default' => true
    ),
    'PRODUCT_TEMPLATE_NAME' => array (
        'type' => 'relate',
        'link' => 'revenuelineitems_templates_link',
        'label' => 'LBL_LIST_PRODUCT_TEMPLATE',
        'width' => '10',
        'default' => false
    ),
    'CATEGORY_NAME' => array (
        'type' => 'relate',
        'link' => 'revenuelineitems_categories_link',
        'label' => 'LBL_CATEGORY_NAME',
        'width' => '10',
        'default' => false
    ),
    'QUANTITY' => array (
        'width' => '10',
        'label' => 'LBL_LIST_QUANTITY',
        'link' => false,
        'default' => true
    ),
    'LIKELY_CASE' =>  array(
        'width' => '10',
        'label' => 'LBL_LIKELY',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right',
    ),
    'BEST_CASE' =>  array(
        'width' => '10',
        'label' => 'LBL_BEST',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right',
    ),
    'WORST_CASE' =>  array(
        'width' => '10',
        'label' => 'LBL_WORST',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right',
    ),
    'QUOTE_NAME' => array (
        'type' => 'relate',
        'link' => 'quotes',
        'label' => 'LBL_QUOTE_NAME',
        'width' => '10',
        'default' => false
    ),
    'ASSIGNED_USER_NAME' => array (
        'width' => '8',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true
    ),
);

$listViewDefs['RevenueLineItems'] = $fields;
