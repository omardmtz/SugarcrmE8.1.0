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
 
// $Id: layout_defs.php 13782 2006-06-06 17:58:55Z majed $

$layout_defs['ACL'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(
        'users' => array(
			'top_buttons' => array(	array('widget_class' => 'SubPanelTopSubModuleSelectButton', 'popup_module' => 'Users'),),
			'order' => 20,
			'module' => 'Users',
			'subpanel_name' => 'ForSubModules',
			'get_subpanel_data' => 'users',
			'add_subpanel_data' => 'user_id',
			'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
		),
	),
);
$layout_defs['UserRoles'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(
        'acl' => array(
			'top_buttons' => array(array('widget_class' => 'SubPanelTopSubModuleSelectButton', 'popup_module' => 'ACL'),),
			'order' => 20,
			'module' => 'ACL',
			'subpanel_def_path'=>'modules/ACL/Roles/subpanels/default.php',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'roles',
			'add_subpanel_data' => 'role_id',
			'title_key' => 'LBL_ROLES_SUBPANEL_TITLE',
		),
	),
	
);


?>