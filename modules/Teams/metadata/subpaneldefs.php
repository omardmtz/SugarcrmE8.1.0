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

$layout_defs['Teams'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
        'users' => array(
        'top_buttons' => array(
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Users', 'mode'=>'MultiSelect'),
		),
		'fill_in_additional_fields'=>true,
	
			'order' => 10,
			'module' => 'Users',
			'sort_by' => 'user_name',
			'sort_order' => 'asc',
			'subpanel_name' => 'ForTeams',
			'get_subpanel_data' => 'users',
			'add_subpanel_data' => 'user_id',
			'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
		),
	),
);
?>