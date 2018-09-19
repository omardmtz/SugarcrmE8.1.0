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
			array('widget_class' => 'SubPanelTopCreateButton'),
	),

	'where' => '',

	'list_fields' => array(
        'holiday_date'=>array(
		 	'vname' => 'LBL_HOLIDAY_DATE',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '21%',
		),
		'description'=>array(
		 	'vname' => 'LBL_DESCRIPTION',
			'width' => '75%',
			'sortable'=>false,				
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),


	),
);

if ( isset($_REQUEST['record']) ) {
//remove the administrator edit button holiday for the user admin only
        global $current_user;
        $result = $GLOBALS['db']->query("SELECT is_admin FROM users WHERE id='".$GLOBALS['db']->quote($_REQUEST['record'])."'");
        $row = $GLOBALS['db']->fetchByAssoc($result);
        if(!is_admin($current_user)&& $current_user->isAdminForModule('Users')&& $row['is_admin']==1){
            unset($subpanel_layout['list_fields']['edit_button']);
        }
}
?>