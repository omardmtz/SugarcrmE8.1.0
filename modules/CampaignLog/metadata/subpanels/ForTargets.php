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

// $Id: ForTargets.php 17937 2006-11-27 21:26:57Z ajay $
$subpanel_layout = array(
	'top_buttons' => array(
	),

	'where' => '',


	'list_fields' => array(
		'campaign_name1'=>array(
			'vname' => 'LBL_LIST_CAMPAIGN_NAME',
			'width' => '20%',
			'widget_class' => 'SubPanelDetailViewLink',
			'target_record_key' => 'campaign_id',
			'target_module' => 'Campaigns',			
		),
		'activity_type' => array(
			'vname' => 'LBL_ACTIVITY_TYPE',
			'width' => '10%',
		),
		'activity_date' => array(
			'vname' => 'LBL_ACTIVITY_DATE',
			'width' => '10%',
		),
		'related_name' => array(
			'widget_class' => 'SubPanelDetailViewLink',
			'target_record_key' => 'related_id',
			'target_module_key' => 'related_type',	
            'parent_id' =>'target_id',
            'parent_module'=>'target_type',         
			'vname' => 'LBL_RELATED',
			'width' => '60%',
			'sortable'=>false,			
		),
		'related_id'=>array(
			'usage' =>'query_only',
		),
		'related_type'=>array(
			'usage' =>'query_only',
		),
		'target_id'=>array(
			'usage' =>'query_only',
		),
		'target_type'=>array(
			'usage' =>'query_only',
		),		
	),
);			
?>