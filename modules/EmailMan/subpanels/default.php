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
// $Id: default.php 53349 2009-12-23 16:46:03Z jmertic $
$subpanel_layout = array(
	'top_buttons' => array(
	),

	'where' => '',


	'list_fields' => array(
		'recipient_name'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_NAME',
			'width' => '10%',
			'sortable'=>false,			
		),
		'recipient_email'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_EMAIL',
			'width' => '10%',
			'sortable'=>false,			
		),		
		'message_name' => array(
			'vname' => 'LBL_MARKETING_ID',
			'width' => '10%',
			'sortable'=>false,
		),
		'send_date_time' => array(
			'vname' => 'LBL_LIST_SEND_DATE_TIME',
			'width' => '10%',
			'sortable'=>false,			
		),
		'related_id'=>array(
			'usage'=>'query_only',
		),
		'related_type'=>array(
			'usage'=>'query_only',			
		),
		'marketing_id' => array(
			'usage'=>'query_only',			
		),
	),
);		
?>