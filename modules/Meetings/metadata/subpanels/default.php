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

// $Id: default.php 13968 2006-06-12 22:00:08Z jacob $
$subpanel_layout = array(
	'top_buttons' => array(
        	array('widget_class'=>'SubPanelTopCreateButton'),
			array('widget_class'=>'SubPanelTopSelectButton', 'popup_module' => 'Meetings'),
		),

	'where' => '',


	'list_fields' => array(
        'name'=>array(
		    'name' => 'name',
		 	'vname' => 'LBL_LIST_SUBJECT',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '50%',
		),
		'date_start'=>array(
		    'name' => 'date_start',
		 	'vname' => 'LBL_LIST_DATE',
			'width' => '25%',
		),
		'date_end'=>array(
		    'name' => 'date_end',
		    'vname' => 'LBL_DATE_END',
		    'width' => '25%',
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
		'recurring_source'=>array(
			'usage'=>'query_only',	
		),
	),
);
?>
