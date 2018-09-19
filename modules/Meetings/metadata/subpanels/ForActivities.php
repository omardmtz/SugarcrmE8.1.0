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

// $Id: ForActivities.php 13782 2006-06-06 17:58:55Z majed $
$subpanel_layout = array(
	//Removed button because this layout def is a component of
	//the activities sub-panel.

	'where' => "(meetings.status !='Held' AND meetings.status !='Not Held')",
	
	
				
	'list_fields' => array(
		'object_image'=>array(
			'vname' => 'LBL_OBJECT_IMAGE',
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
			'image2'=>'__VARIABLE',
 		 	'image2_ext_url_field'=>'displayed_url',
		),
		'name'=>array(
			 'vname' => 'LBL_LIST_SUBJECT',
			 'widget_class' => 'SubPanelDetailViewLink',
			 'width' => '42%',
		),
		'status'=>array(
			 'widget_class' => 'SubPanelActivitiesStatusField',
			 'vname' => 'LBL_LIST_STATUS',
			 'width' => '15%',
		),
		'contact_name'=>array(
			 'widget_class' => 'SubPanelDetailViewLink',
			 'target_record_key' => 'contact_id',
			 'target_module' => 'Contacts',
			 'module' => 'Contacts',
			 'vname' => 'LBL_LIST_CONTACT',
			 'width' => '11%',
			 'sortable'=>false,
		),
		'contact_id'=>array(
			'usage'=>'query_only',
	
		),
		'contact_name_owner'=>array(
			'usage'=>'query_only',
			'force_exists'=>true
		),	
		'contact_name_mod'=>array(
			'usage'=>'query_only',
			'force_exists'=>true
		),		
		'date_start'=>array(
			 'vname' => 'LBL_LIST_DUE_DATE',
			 'width' => '10%',
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'target_record_key' => 'assigned_user_id',
			'target_module' => 'Employees',
			'width' => '10%',		
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),
		'close_button'=>array(
			'widget_class' => 'SubPanelCloseButton',
			'vname' => 'LBL_LIST_CLOSE',
			'sortable'=>false,
			'width' => '6%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
		'time_start'=>array(
			'usage'=>'query_only',
	
		),	
		'recurring_source'=>array(
			'usage'=>'query_only',	
		),
		'join_url'=>array(
			'usage'=>'query_only'
		),	
		'host_url'=>array(
			'usage'=>'query_only'
		),			
	),
);		
?>
