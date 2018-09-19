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

global $mod_strings;

$popupMeta = array(
	'moduleMain' => 'Contact',
	'varName' => 'CONTACT',
	'orderBy' => 'contacts.first_name, contacts.last_name',
	'whereClauses' => 
		array('first_name' => 'contacts.first_name', 
				'last_name' => 'contacts.last_name',
				'account_name' => 'accounts.name',
				'account_id' => 'accounts.id'),
	'searchInputs' =>
		array('first_name', 'last_name', 'account_name', 'email'),
	'create' =>
		array('formBase' => 'ContactFormBase.php',
				'formBaseClass' => 'ContactFormBase',
				'getFormBodyParams' => array('','','ContactSave'),
				'createButton' => 'LNK_NEW_CONTACT'
			  ),
	'listviewdefs' => array(
		'NAME' => array(
			'width' => '20%', 
			'label' => 'LBL_LIST_NAME',
  			'link' => true,
	        'default' => true,
  			'related_fields' => array('first_name', 'last_name', 'salutation', 'account_name', 'account_id')), 
		'ACCOUNT_NAME' => array(
			'width' => '25', 
			'label' => 'LBL_LIST_ACCOUNT_NAME', 
			'module' => 'Accounts',
			'id' => 'ACCOUNT_ID',
  			'default' => true,
	        'sortable'=> true,
	        'ACLTag' => 'ACCOUNT',
	        'related_fields' => array('account_id')),
  		'TITLE' => array(
			'width' => '15%', 
			'label' => 'LBL_LIST_TITLE',
	        'default' => true), 
  		'LEAD_SOURCE' => array(
			'width' => '15%', 
			'label' => 'LBL_LEAD_SOURCE',
	        'default' => true), 
		),
	'searchdefs'   => array(
	 	'first_name', 
		'last_name', 
		array('name' => 'account_name', 'type' => 'varchar',),
		'title',
		'lead_source',
		'email',
		array('name' => 'campaign_name', 'displayParams' => array('hideButtons'=>'true', 'size'=>30, 'class'=>'sqsEnabled sqsNoAutofill')),
		array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
	  )
	);
?>
