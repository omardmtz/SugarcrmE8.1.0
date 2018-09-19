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
$listViewDefs['ProductTemplates'] = array(
    'NAME' => array(
		'width' => '30', 
		'label' => 'LBL_LIST_NAME', 
		'link' => true,
        'default' => true),
 	'TYPE_NAME' => array (
        'width' => '10',
		'label' =>'LBL_LIST_TYPE',
        'link' => false,
        'sortable' => true,
        'default' => true),
	'CATEGORY_NAME' => array (
        'width' => '10',
		'label' =>'LBL_LIST_CATEGORY',
        'link' => false,
        'sortable' => true,
        'default' => true),
	'STATUS' => array (
        'width' => '10',
		'label' =>'LBL_LIST_STATUS',
        'link' => false,
        'default' => true),
    'QTY_IN_STOCK' => array (
        'width' => '10',
		'label' =>'LBL_LIST_QTY_IN_STOCK',
        'link' => false,
        'default' => true),
    'COST_USDOLLAR'  => array (
        'width' => '10',
		'label' =>'LBL_LIST_COST_PRICE',
        'link' => false,
        'default' => true,
        'align' => 'right',
        'related_fields' => array('currency_id'),
        'currency_format' => true),
    'LIST_USDOLLAR' => array (
        'width' => '10',
		'label' =>'LBL_LIST_LIST_PRICE',
        'link' => false,
        'default' => true,
        'align' => 'right',
        'related_fields' => array('currency_id'),
        'currency_format' => true),
    'DISCOUNT_USDOLLAR' => array (
        'width' => '10',
		'label' =>'LBL_LIST_DISCOUNT_PRICE',
        'link' => false,
        'default' => true,
        'align' => 'right',
        'related_fields' => array('currency_id'),
        'currency_format' => true),	    
        
);
?>
