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

// $Id: default.php 15479 2006-08-03 22:12:05Z jenny $
$subpanel_layout = array(
	'top_buttons' => array(
		//array('widget_class' => 'SubPanelTopCreateButton'),
		//array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'first_name'=>array(
		 	'usage' => 'query_only',
		),
		'last_name'=>array(
		 	'usage' => 'query_only',
		),
		'salutation'=>array(
			'name'=>'salutation',
		 	'usage' => 'query_only',
		),
		'name'=>array(
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
            'sort_order' => 'asc',
            'sort_by' => 'last_name',
		 	'module' => 'Leads',
			'width' => '20%',
		),
		'refered_by'=>array(
	 		'vname' => 'LBL_LIST_REFERED_BY',
			'width' => '13%',
		),
		'lead_source'=>array(
	 		'vname' => 'LBL_LIST_LEAD_SOURCE',
			'width' => '13%',
		),
		'phone_work'=>array(
	 		'vname' => 'LBL_LIST_PHONE',
			'width' => '10%',
		),
		'email'=>array(
	 		'vname' => 'LBL_LIST_EMAIL_ADDRESS',
			'width' => '10%',
			'widget_class' => 'SubPanelEmailLink',
			'sortable'=>false,
		),
		'lead_source_description'=>array(
	 		'name' => 'lead_source_description',
	 		'vname' => 'LBL_LIST_LEAD_SOURCE_DESCRIPTION',
			'width' => '26%',
			'sortable'=>false,
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'target_record_key' => 'assigned_user_id',
			'target_module' => 'Employees',
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Leads',
			'width' => '4%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Leads',
	 		'width' => '4%',
		),
	),
);

?>