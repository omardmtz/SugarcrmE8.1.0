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

// $Id: listviewdefs.php 56123 2010-04-26 21:48:19Z asandberg $


$listViewDefs['Cases'] = array(
	'CASE_NUMBER' => array(
		'width' => '5', 
		'label' => 'LBL_LIST_NUMBER',
        'default' => true), 
	'NAME' => array(
		'width' => '25', 
		'label' => 'LBL_LIST_SUBJECT', 
		'link' => true,
        'default' => true), 
	'ACCOUNT_NAME' => array(
		'width' => '20', 
		'label' => 'LBL_LIST_ACCOUNT_NAME', 
		'module' => 'Accounts',
		'id' => 'ACCOUNT_ID',
		'link' => true,
        'default' => true,
        'ACLTag' => 'ACCOUNT',
        'related_fields' => array('account_id')),
	'PRIORITY' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_PRIORITY',
        'default' => true),  
	'STATUS' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_STATUS',
        'default' => true),
	'TEAM_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_TEAM',
        'default' => false),
	'ASSIGNED_USER_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_ASSIGNED_TO_NAME',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
	'DATE_ENTERED' => array (
	    'width' => '10',
	    'label' => 'LBL_DATE_ENTERED',
	    'default' => true),
);
?>
