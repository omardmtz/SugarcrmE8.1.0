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

$popupMeta = array(
	'moduleMain' => 'Case',
	'varName' => 'CASE',
	'className' => 'aCase',
	'orderBy' => 'name',
	'whereClauses' => 
		array('name' => 'cases.name', 
				'case_number' => 'cases.case_number',
				'account_name' => 'accounts.name'),
	'listviewdefs' => array(
		'CASE_NUMBER' => array(
			'width' => '5', 
			'label' => 'LBL_LIST_NUMBER',
	        'default' => true), 
		'NAME' => array(
			'width' => '35', 
			'label' => 'LBL_LIST_SUBJECT', 
			'link' => true,
	        'default' => true), 
		'ACCOUNT_NAME' => array(
			'width' => '25', 
			'label' => 'LBL_LIST_ACCOUNT_NAME', 
			'module' => 'Accounts',
			'id' => 'ACCOUNT_ID',
			'link' => true,
	        'default' => true,
	        'ACLTag' => 'ACCOUNT',
	        'related_fields' => array('account_id')),
		'PRIORITY' => array(
			'width' => '8', 
			'label' => 'LBL_LIST_PRIORITY',
	        'default' => true),  
		'STATUS' => array(
			'width' => '8', 
			'label' => 'LBL_LIST_STATUS',
	        'default' => true),
	    'ASSIGNED_USER_NAME' => array(
	        'width' => '2', 
	        'label' => 'LBL_LIST_ASSIGNED_USER',
	        'default' => true,
	       ),
		),
	'searchdefs'   => array(
	 	'case_number', 
		'name',
		array('name' => 'account_name', 'displayParams' => array('hideButtons'=>'true', 'size'=>30, 'class'=>'sqsEnabled sqsNoAutofill')),
		'priority',
		'status',
		array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
	  )
);
