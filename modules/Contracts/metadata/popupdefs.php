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

// $Id: popupdefs.php 55931 2010-04-09 18:25:11Z jmertic $

$popupMeta = array (
	'moduleMain' => 'Contract',
	'varName' => 'CONTRACT',
	'className' => 'Contract',
	'orderBy' => 'contracts.name',
	'whereClauses' => array (
		'name' => 'contracts.name', 
		'reference_code' => 'contracts.reference_code',
		'status' => 'contracts.status',
		'account_id' => 'contracts.account_id',
	),
	'searchInputs' => array('account_id','account_name','name','reference_code','status'),
	'selectDoms' => array('STATUS_OPTIONS' => 
								array('dom' => 'contract_status_dom', 'searchInput' => 'status'),
	),
	'listviewdefs' => array(
		'NAME' => array(
	        'width' => '40', 
	        'label' => 'LBL_LIST_CONTRACT_NAME', 
	        'link' => true,
	        'default' => true),
	    'REFERENCE_CODE' => array(
	        'width' => '10', 
	        'label' => 'LBL_REFERENCE_CODE', 
	        'link' => false,
	        'default' => true),
	    'STATUS' => array(
	        'width' => '10', 
	        'label' => 'LBL_STATUS', 
	        'link' => false,
	        'default' => true),
	    'START_DATE' => array(
	        'width' => '15', 
	        'label' => 'LBL_LIST_START_DATE', 
	        'link' => false,
	        'default' => true),
	    'END_DATE' => array(
	        'width' => '15', 
	        'label' => 'LBL_LIST_END_DATE', 
	        'link' => false,
	        'default' => true),
		'ASSIGNED_USER_NAME' => array(
			'width' => '10', 
			'label' => 'LBL_LIST_ASSIGNED_USER',
	        'link' => false,
	        'default' => true        
	        ),
		),
	'searchdefs'   => array(
	 	'name', 
		'reference_code', 
		'status',
		'start_date',
		'end_date',
		array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
	  )
);
?>
