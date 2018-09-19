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

// $Id: default.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
$subpanel_layout = array(
	'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton', 'title' => 'LBL_SELECT_USER_RESOURCE', 'popup_module' => 'Users', ),
	),

	'where' => '',

	'list_fields' => array(
		'object_image'=>array(
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
		),
		'name'=>array(
			'name' => 'name',
		 	'vname' => 'LBL_RESOURCE_NAME',
			'width' => '93%',
		),		
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Users',
			'width' => '5%',
		),
		'first_name' => array(
		    'usage'=>'query_only',
		),
		'last_name' => array(
		    'usage'=>'query_only',
		)		
	),
);
?>