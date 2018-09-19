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

// $Id: ForMeetings.php 19886 2007-02-06 23:22:59Z chris $
$subpanel_layout = array(
	'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Users'),
	),

	'where' => '',
	
	

    'list_fields'=> array(
		'accept_status_name' => array(
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
			'width' => '11%',
			'sortable' => false,
		),
		'm_accept_status_fields'=>array(
			'usage' => 'query_only',
		),
		'accept_status_id'=>array(
			'usage' => 'query_only',
		),				
        'first_name'=>array(
		 	'usage' => 'query_only',
		),
		'last_name'=>array(
		 	'usage' => 'query_only',
		),
		'name'=>array(
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'target_module' => 'Employees',
		 	'module' => 'Users',
	 		'width' => '25%',
		),
		'user_name'=>array(
			'vname' => 'LBL_LIST_USER_NAME',
			'width' => '25%',
		),
		'email'=>array(
			'vname' => 'LBL_LIST_EMAIL',
			'width' => '25%',
			'widget_class' => 'SubPanelEmailLink',
            'sortable' => false,
		),
		'phone_work'=>array (
			'vname' => 'LBL_LIST_PHONE',
			'width' => '21%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButtonMeetings',
		 	'module' => 'Users',
			'width' => '4%',
			'linked_field' => 'users',
		),
	),
);
?>
