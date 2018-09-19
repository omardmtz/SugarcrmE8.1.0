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

$subpanel_layout = array(
	'top_buttons' => array(),
	'where'				=> "",
	'fill_in_additional_fields'	=> true,
	'list_fields' => array(
		'object_image'=>array(
			'widget_class'			=> 'SubPanelIcon',
 		 	'width'					=> '2%',
		),
		'name' => array(
			 'vname'				=> 'LBL_LIST_SUBJECT',
			 'widget_class'			=> 'SubPanelDetailViewLink',
			 'width'				=> '30%',
             'parent_info'          => true
		),
		'status' => array(
			 'vname'				=> 'LBL_LIST_STATUS',
			 'width'				=> '15%',
		),
		'reply_to_status' => array(
			 'usage'				=> 'query_only',
             'force_exists'			=> true,
		),
		'date_entered' => array(
			'width'					=> '10%',
		    'vname'					=> 'LBL_DATE_CREATED',
		),
		'date_modified' => array(
			'width'					=> '10%',
		    'vname'					=> 'LBL_DATE_MODIFIED',
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'target_record_key' => 'assigned_user_id',
			'target_module' => 'Employees',
		),
		'edit_button' => array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class'			=> 'SubPanelEditButton',
			 'width'				=> '2%',
		),
		'filename' => array(
			'usage'					=> 'query_only',
			'force_exists'			=> true
		),
	), // end list_fields
);
?>
