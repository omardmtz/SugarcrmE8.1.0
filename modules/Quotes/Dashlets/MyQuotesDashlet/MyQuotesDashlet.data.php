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

// $Id: MyCasesDashlet.data.php 56357 2010-05-10 22:48:37Z jenny $

global $current_user;

$dashletData['MyQuotesDashlet']['searchFields'] = array('quote_stage'             => array('default' => ''),
                                                       'name'             => array('default' => ''),
												       'date_quote_expected_closed'             => array('default' => ''),
                                                       //'date_modified'    => array('default' => ''),
                                                       'team_id'          => array('default' => '', 'label' => 'LBL_TEAMS'),
                                                       'assigned_user_id' => array('type'    => 'assigned_user_name',
																				   'label'   => 'LBL_ASSIGNED_TO',
                                                                                   'default' => $current_user->name));
$dashletData['MyQuotesDashlet']['columns'] = array('quote_num' => array(
		'width' => '10',  
		'label' => 'LBL_LIST_QUOTE_NUM', 
		'link' => false,
        'default' => true),
	'name' => array(
		'width' => '25', 
		'label' => 'LBL_LIST_QUOTE_NAME', 
		'link' => true,
        'default' => true),
	'billing_account_name' => array(
		'width' => '20',  
		'label' => 'LBL_LIST_ACCOUNT_NAME',
        'id' => 'ACCOUNT_ID',
        'module'  => 'Accounts',        
        'link' => true,
        'default' => true), 
	'quote_stage' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_QUOTE_STAGE', 
        'link' => false,
        'default' => true        
	),
	'total_usdollar' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_AMOUNT_USDOLLAR',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right'
    ),
	'date_quote_expected_closed' => array(
		'width' => '15', 
		'label' => 'LBL_LIST_DATE_QUOTE_EXPECTED_CLOSED',
        'link' => false,
        'default' => true        
        ),
    'quote_type' => array(
		'width' => '15', 
		'label' => 'LBL_QUOTE_TYPE',
        'link' => false,      
        ),
     'order_stage' => array(
		'width' => '15', 
		'label' => 'LBL_ORDER_STAGE',
        'link' => false,       
        ),
  'billing_address_street' =>
  array (
    'width' => '20', 
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'link' => false, 
  ),
  'billing_address_city' =>
  array (
     'width' => '20',
    'label' => 'LBL_BILLING_ADDRESS_CITY',
   'link' => false, 
  ),
  'billing_address_state' =>
  array (
     'width' => '20',
    'label' => 'LBL_BILLING_ADDRESS_STATE',
    'link' => false, 
  ),
  'billing_address_postalcode' =>
  array (
     'width' => '20',
    'label' => 'LBL_BILLING_ADDRESS_POSTAL_CODE',
    'link' => false, 
  ),
  'billing_address_country' =>
  array (
     'width' => '20',
    'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
    'link' => false, 
  ),
  'shipping_address_street' =>
  array (
    'width' => '20',
    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
    'link' => false, 
  ),
  'shipping_address_city' =>
  array (
     'width' => '20',
    'label' => 'LBL_SHIPPING_ADDRESS_CITY',
    'link' => false, 
  ),
  'shipping_address_state' =>
  array (
    'width' => '20',
    'label' => 'LBL_SHIPPING_ADDRESS_STATE',
    'link' => false, 
  ),
  'shipping_address_postalcode' =>
  array (
    'width' => '20',
    'label' => 'LBL_SHIPPING_ADDRESS_POSTAL_CODE',
   'link' => false, 
  ),
  'shipping_address_country' =>
  array (
     'width' => '20',
    'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
   'link' => false, 
  ),
);
?>
