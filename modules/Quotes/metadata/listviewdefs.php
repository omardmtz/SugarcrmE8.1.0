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

 // $Id: listviewdefs.php 56851 2010-06-07 22:17:02Z jenny $

$listViewDefs['Quotes'] = array(
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
    'TOTAL' => array(
        'width' => '10',
        'label' => 'LBL_LIST_AMOUNT',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right',
        'sortable' => false,
    ),
	'TOTAL_USDOLLAR' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_AMOUNT_USDOLLAR',
        'link' => false,
        'default' => true,
        'currency_format' => true,
        'align' => 'right'
    ),
	'DATE_QUOTE_EXPECTED_CLOSED' => array(
		'width' => '15', 
		'label' => 'LBL_LIST_DATE_QUOTE_EXPECTED_CLOSED',
        'link' => false,
        'default' => true        
        ),
	'TEAM_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_TEAM',
        'link' => false,
        'default' => false,
        'related_fields' => array('team_id'),        
        ),
	'ASSIGNED_USER_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_ASSIGNED_USER',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true        
        ),
	'DATE_ENTERED' => array (
	    'width' => '10',
	    'label' => 'LBL_DATE_ENTERED',
	    'default' => true,
	  )        
);

?>
