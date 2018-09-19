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
 
// $Id: popupdefs.php 54464 2010-02-11 15:55:50Z jmertic $

$popupMeta = array (
	'moduleMain' => 'Quote',
	'varName' => 'QUOTE',
	'orderBy' => 'name',
	'whereClauses' => array (
		'name' => 'quotes.name',
		'account_name' => 'accounts.name', 
		'date_quote_expected_closed' => 'quotes.date_quote_expected_closed',
	),
	'searchInputs' => array('name', 'account_name'),
	'listviewdefs' => array(
											'QUOTE_NUM' => array(
												'width' => '10',  
												'label' => 'LBL_LIST_QUOTE_NUM', 
												'link' => false,
										        'default' => true),
											'NAME' => array(
												'width' => '25', 
												'label' => 'LBL_LIST_QUOTE_NAME',
												'link' => true, 
										        'default' => true),
										    'BILLING_ACCOUNT_NAME' => array(
												'width' => '20',  
												'label' => 'LBL_LIST_ACCOUNT_NAME',
										        'id' => 'ACCOUNT_ID',
										        'module'  => 'Accounts',  
												'link' => true,      
										        'default' => true), 
											'QUOTE_STAGE' => array(
												'width' => '10', 
												'label' => 'LBL_LIST_QUOTE_STAGE', 
										        'link' => false,
										        'default' => true        
											),
											'PURCHASE_ORDER_NUM' => array(
												'width' => '25', 
												'label' => 'LBL_PURCHASE_ORDER_NUM', 
										        'default' => true),
											'ASSIGNED_USER_NAME' => array(
												'width' => '10', 
												'label' => 'LBL_LIST_ASSIGNED_USER',
										        'link' => false,
										        'default' => true        
										        ),
											),
						'searchdefs'   => array(
										 	'quote_num', 
											'name', 
											array('name' => 'billing_account_name', 'displayParams' => array('hideButtons'=>'true', 'size'=>30, 'class'=>'sqsEnabled sqsNoAutofill')),
											'quote_stage',
											'purchase_order_num',
											array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
										  )
);

?>