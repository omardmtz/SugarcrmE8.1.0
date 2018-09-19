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

// $Id: listviewdefs.php 56344 2010-05-10 21:18:57Z jenny $

$listViewDefs ['Accounts'] = 
array (
  'NAME' => 
  array (
    'width' => '20',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'link' => true,
    'default' => true,
  ),
  'TEAM_NAME' => 
  array(
        'width' => '10', 
        'label' => 'LBL_LIST_TEAM',
        'default' => false
  ),
  'BILLING_ADDRESS_CITY' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_CITY',
    'default' => true,
  ),
  'BILLING_ADDRESS_COUNTRY' => 
  array (
    'width' => '10',
    'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
    'default' => true,
  ),
  'PHONE_OFFICE' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_PHONE',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'ACCOUNT_TYPE' => 
  array (
    'width' => '10',
    'label' => 'LBL_TYPE',
    'default' => false,
  ),
  'INDUSTRY' => 
  array (
    'width' => '10',
    'label' => 'LBL_INDUSTRY',
    'default' => false,
  ),
  'ANNUAL_REVENUE' => 
  array (
    'width' => '10',
    'label' => 'LBL_ANNUAL_REVENUE',
    'default' => false,
  ),
  'PHONE_FAX' => 
  array (
    'width' => '10',
    'label' => 'LBL_PHONE_FAX',
    'default' => false,
  ),
  'BILLING_ADDRESS_STREET' => 
  array (
    'width' => '15',
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'default' => false,
  ),
  'BILLING_ADDRESS_STATE' => 
  array (
    'width' => '7',
    'label' => 'LBL_BILLING_ADDRESS_STATE',
    'default' => false,
  ),
  'BILLING_ADDRESS_POSTALCODE' => 
  array (
    'width' => '10',
    'label' => 'LBL_BILLING_ADDRESS_POSTALCODE',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_STREET' => 
  array (
    'width' => '15',
    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_CITY' => 
  array (
    'width' => '10',
    'label' => 'LBL_SHIPPING_ADDRESS_CITY',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_STATE' => 
  array (
    'width' => '7',
    'label' => 'LBL_SHIPPING_ADDRESS_STATE',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_POSTALCODE' => 
  array (
    'width' => '10',
    'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_COUNTRY' => 
  array (
    'width' => '10',
    'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
    'default' => false,
  ),
  'RATING' => 
  array (
    'width' => '10',
    'label' => 'LBL_RATING',
    'default' => false,
  ),
  'PHONE_ALTERNATE' => 
  array (
    'width' => '10',
    'label' => 'LBL_OTHER_PHONE',
    'default' => false,
  ),
  'WEBSITE' => 
  array (
    'width' => '10',
    'label' => 'LBL_WEBSITE',
    'default' => false,
  ),
  'OWNERSHIP' => 
  array (
    'width' => '10',
    'label' => 'LBL_OWNERSHIP',
    'default' => false,
  ),
  'EMPLOYEES' => 
  array (
    'width' => '10',
    'label' => 'LBL_EMPLOYEES',
    'default' => false,
  ),
  'SIC_CODE' => 
  array (
    'width' => '10',
    'label' => 'LBL_SIC_CODE',
    'default' => false,
  ),
  'TICKER_SYMBOL' => 
  array (
    'width' => '10',
    'label' => 'LBL_TICKER_SYMBOL',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'width' => '5',
    'label' => 'LBL_DATE_MODIFIED',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10',
    'label' => 'LBL_CREATED',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '10',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
  'EMAIL' => 
  array(
    'width' => '15',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => true,
    'customCode' => '{$EMAIL_LINK}{$EMAIL}</a>',
    'default' => true
  ),  
  'DATE_ENTERED' => 
  array (
    'width' => '5',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),  
);
