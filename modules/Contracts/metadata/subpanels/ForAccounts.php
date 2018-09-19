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

// $Id: ForAccounts.php 13782 2006-06-06 17:58:55Z majed $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contracts','mode'=>'MultiSelect'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'name'=>array(
			'name'=>'name',		
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'module' => 'Contacts',
			'width' => '33%',
		),
		'start_date'=>array(
			'name'=>'start_date',		
			'vname' => 'LBL_LIST_START_DATE',
			'width' => '10%',
		),
		'end_date'=>array(
			'name'=>'end_date',		
			'vname' => 'LBL_LIST_END_DATE',
			'width' => '10%',
		),
		'status'=>array(
			'name'=>'status',		
			'vname' => 'LBL_LIST_STATUS',
			'width' => '10%',
		),
		'total_contract_value'=>array (
			'name'=>'total_contract_value',		
			'vname' => 'LBL_LIST_CONTRACT_VALUE',
			'width' => '15%',
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Contracts',
			'width' => '5%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Contracts',
			'width' => '5%',
		),
	),
);		
?>
