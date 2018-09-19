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

// $Id: default.php 16627 2006-09-06 02:28:58Z eddy $

$subpanel_layout = array(
	'top_buttons' => array(
      		array('widget_class'=>'SubPanelTopCreateButton'),
			array('widget_class'=>'SubPanelTopSelectButton'),
		),

	'where' => '',


    'list_fields'=> array(
    	'first_name' => array(
		 	'usage' => 'query_only',
    	),
    	'last_name' => array(
		 	'usage' => 'query_only',
    	),
        'full_name'=>array(
		 	'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '40%',
            'sort_by' => 'last_name',
		),
		'title'=>array(
		 	'vname' => 'LBL_LIST_TITLE',
			'width' => '25%',
		),
		'email'=>array(
		 	'vname' => 'LBL_LIST_EMAIL_ADDRESS',
			'width' => '15%',
			'widget_class' => 'SubPanelEmailLink',
			'sortable' => false,
		),
		'phone_work'=>array(
		 	'vname' => 'LBL_LIST_PHONE',
			'width' => '10%',
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Contacts',
			'width' => '5%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Contacts',
			'width' => '5%',
		),		
	),
);

?>