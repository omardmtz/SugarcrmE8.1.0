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

// $Id: ForQueues.php 13782 2006-06-06 17:58:55Z majed $

//$layout_defs['ForQueues'] = array(
//	'top_buttons' => array(
//			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Queues'),
//		),
//);


$subpanel_layout = array(
	'top_buttons' => array(
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Queues'),
	),
	'where' => "",

	'fill_in_additional_fields'=>true,
	'list_fields' => array(
/*		'mass_update' => array (
			
		),
*/		'object_image'=>array(
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
		),
		'name'=>array(
			 'vname' => 'LBL_LIST_SUBJECT',
			 'widget_class' => 'SubPanelDetailViewLink',
			 'width' => '68%',
		),
		'case_name'=>array(
			 'widget_class' => 'SubPanelDetailViewLink',
			 'target_record_key' => 'case_id',
			 'target_module' => 'Cases',
			 'module' => 'Cases',
			 'vname' => 'LBL_LIST_CASE',
			 'width' => '20%',
			 'force_exists'=>true,
			 'sortable'=>false,
		),
		'contact_id'=>array(
			'usage'=>'query_only',
			'force_exists'=>true,
		)	,
/*		'parent_name'=>array(
			 'vname' => 'LBL_LIST_RELATED_TO',		
			 'width' => '22%',
			 'target_record_key' => 'parent_id',
			 'target_module_key'=>'parent_type',
			 'widget_class' => 'SubPanelDetailViewLink',
			  'sortable'=>false,	
		),*/
		'date_modified'=>array(
			'vname' => 'LBL_DATE_MODIFIED',
			 'width' => '10%',
		),
/*		'edit_button'=>array(
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),
		'remove_button'=>array(
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
		'parent_id'=>array(
			'usage'=>'query_only',
		),
		'parent_type'=>array(
			'usage'=>'query_only',
		),
		'filename'=>array(
			'usage'=>'query_only',
			'force_exists'=>true
			),		
*/		
	),
);		

?>