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

// $Id: ForEmails.php 13782 2006-06-06 17:58:55Z majed $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',
	
	'fill_in_additional_fields'=>true,
	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '35%',
		),
		'assigned_user_name'=>array(
	 		'vname' => 'LBL_LIST_ASSIGNED_USER_ID',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'module' => 'Users',
	 		'target_record_key' => 'assigned_user_id',
 		 	'target_module' => 'Users',
			'width' => '15%',
			 'sortable'=>false,	
		),
		'estimated_start_date' => array(
			'vname' => 'LBL_DATE_START',
			'width' => '22%',
			'sortable' => true,
		),
		'estimated_end_date' => array(
			'vname' => 'LBL_DATE_END',
			'width' => '22%',
			'sortable' => true,
		),	
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
			'module' => 'Project',
		 	'width' => '3%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
 		 	'module' => 'Project',
	 		'width' => '3%',
		),
	),
);

?>