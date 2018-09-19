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

// $Id: ForAccounts.php 14827 2006-07-20 20:32:16Z awu $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',

	'fill_in_additional_fields'=>true,

	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '28%',
			'sort_by'=>'products.name'
		),					
		'status'=>array(
	 		'vname' => 'LBL_LIST_STATUS',
			'width' => '8%',
		),
		'contact_name'=>array(
	 		'vname' => 'LBL_LIST_CONTACT_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
 		 	'target_record_key' => 'contact_id',
 		 	'target_module' => 'Contacts',
	 		'module' => 'Contacts',
			'width' => '15%',
			 'sortable'=>false,	
		),
		'date_purchased'=>array(
	 		'vname' => 'LBL_LIST_DATE_PURCHASED',
			'width' => '10%',
		),
		'discount_price'=>array(
	 		'vname' => 'LBL_LIST_DISCOUNT_PRICE',
			'width' => '10%',
		),					
		'date_support_expires'=>array(
	 		'vname' => 'LBL_LIST_SUPPORT_EXPIRES',
			'width' => '10%',
		),					
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
			'module' => 'Products',
		 	'width' => '4%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Products',
	 		'width' => '4%',
		),
		'discount_usdollar'=>array(
	 		 		'usage' => 'query_only',
				),	
	),
);

?>