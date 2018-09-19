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

// $Id: default.php 15992 2006-08-16 01:51:22Z majed $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Bugs'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'bug_number'=>array(
	 		'vname' => 'LBL_LIST_NUMBER',
	 		'width' => '5%',
		),
		
		'name'=>array(
	 		'vname' => 'LBL_LIST_SUBJECT',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'width' => '50%',
		),
		'status'=>array(
	 		'vname' => 'LBL_LIST_STATUS',
	 		'width' => '15%',
		),
		'type'=>array(
	 		'vname' => 'LBL_LIST_TYPE',
	 		'width' => '15%',
		),
		'priority'=>array(
	 		'vname' => 'LBL_LIST_PRIORITY',
	 		'width' => '11%',
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
		 	'module' => 'Bugs',
	 		'width' => '4%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Bugs',
			'width' => '5%',
		),
	),
);

?>