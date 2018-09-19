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

// $Id: default.php 19273 2007-01-12 17:59:46Z liliya $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelAddToProspectListButton','create'=>'true'),			
	),

	'where' => '',


	'list_fields' => array(
		'recipient_name'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_NAME',
			'width' => '14%',
			'sortable'=>false,
		),
		'recipient_email'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_EMAIL',
			'width' => '14%',
			'sortable'=>false,
		),		
		'marketing_name'=>array(			
			'vname' => 'LBL_LIST_MARKETING_NAME',
			'width' => '14%',
			'sortable'=>false,		
		),
		'activity_type' => array(
			'vname' => 'LBL_ACTIVITY_TYPE',
			'width' => '14%',
		),
		'activity_date' => array(
			'vname' => 'LBL_ACTIVITY_DATE',
			'width' => '14%',
		),
		'related_name' => array(
			'widget_class' => 'SubPanelDetailViewLink',
			'target_record_key' => 'related_id',
			'target_module_key' => 'related_type',		
            'parent_id' =>'target_id',
            'parent_module'=>'target_type',         
			'vname' => 'LBL_RELATED',
			'width' => '20%',
			'sortable'=>false,
		),
		'hits' => array(
			'vname' => 'LBL_HITS',
			'width' => '5%',
		),		
		'target_id'=>array(
			'usage' =>'query_only',
		),
		'target_type'=>array(
			'usage' =>'query_only',
		),
		'related_id'=>array(
			'usage' =>'query_only',
		),
		'related_type'=>array(
			'usage' =>'query_only',
		),
	),
);		
?>
