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



$process_dictionary['TriggersCreateStep1'] = Array('action' => 'CreateStep1',
'elements' => array(

'compare_specific' => 
	Array(
		'trigger_type' => 'all',
		'filter_type' => Array('Normal'=>'Normal', 'Time' => 'Time'),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'compare_specific',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_COMPARE_SPECIFIC_TITLE', 'mutual_exclusion' => array('trigger_record_change')),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'compare_specific',
			'options' => Array(
				'1' => Array('vname' => 'LBL_TRIGGER', 'text_type' => 'static', 'display_function' => 'get_list_display_text_compare_specific'),
				'2' => Array(
						'vname' => 'LBL_FIELD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'href',
						'value' => 'field',
						'value_type' => 'normal_field',
						'jscript_function' => 'get_single_selector',
						'jscript_content' => array('self', 'field'),
						),
				'3' => Array('vname' => 'LBL_COMPARE_SPECIFIC_PART', 'text_type' => 'static', 'vname_time' => 'LBL_COMPARE_SPECIFIC_PART_TIME')		
			//end bottom options
			),
		//end bottom
		),
//end compare_specific array	
),	
'trigger_record_change' => 
	Array(
		'trigger_type' => 'non_time',	
		'filter_type' => Array('Normal'=>'Normal'),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'trigger_record_change',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_TRIGGER_RECORD_CHANGE_TITLE', 'display_function' => 'get_trigger_list_display_text_generic', 'mutual_exclusion' => array('compare_specific')),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'trigger_record_change',
			'options' => Array(
				'1' => Array('vname' => 'LBL_TRIGGER_RECORD_CHANGE_TITLE', 'text_type' => 'static'),	
			//end bottom options
			),
		//end bottom
		),
//end trigger_record_change array	
),
'compare_change' => 
	Array(
		'trigger_type' => 'non_time',
		'filter_type' => Array('Normal'=>'Normal'),
		'trigger_type_override' => 'normal_date_trigger',
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'compare_change',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_COMPARE_CHANGE_TITLE'),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'compare_change',
			'options' => Array(
				'1' => Array('vname' => 'LBL_TRIGGER', 'text_type' => 'static'),
				'2' => Array(
						'vname' => 'LBL_FIELD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'href',
						'value' => 'field',
						'value_type' => 'normal_field',
						'jscript_function' => 'get_single_selector',
						'jscript_content' => array('self', 'field')
						),
				'3' => Array('vname' => 'LBL_COMPARE_CHANGE_PART', 'text_type' => 'static')		
			//end bottom options
			),
		//end bottom
		),
//end compare_specific array	
),
/*'compare_count' => 
	Array(
		'trigger_type' => 'non_time',
		'filter_type' => Array(),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'compare_count',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_COMPARE_COUNT_TITLE'),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'compare_count',
			'options' => Array(
				'1' => Array('vname' => 'LBL_COMPARE_COUNT_TITLE', 'text_type' => 'static'),	
			//end bottom options
			),
		//end bottom
		),
//end compare_count array	
),*/
'compare_any_time' => 
	Array(
		'trigger_type' => 'time_only',
		'filter_type' => Array('Time' => 'Time'),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'compare_any_time',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_COMPARE_ANY_TIME_TITLE'),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'compare_any_time',
			'options' => Array(
				'1' => Array(
						'vname' => 'LBL_FIELD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'href',
						'value' => 'field',
						'value_type' => 'normal_field',
						'jscript_function' => 'get_single_selector',
						'jscript_content' => array('self', 'field')
						),
				'2' => Array('vname' => 'LBL_COMPARE_ANY_TIME_PART2', 'text_type' => 'static'),
				'3' => Array(
						'vname' => 'LBL_COMPARE_ANY_TIME_PART3', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'dropdown',
						'value' => 'parameters',
						'value_type' => 'special_exp',
						'value_exp_type' => 'dom_array',
						'dom_name' => 'tselect_type_dom',
						),
			//end bottom options
			),
		//end bottom
		),
//end compare_any_time array	
),
'filter_field' => 
	Array(
		'trigger_type' => 'non_time',	
		'filter_type' => Array('Normal'=>'Normal', 'Time' => 'Time'),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'filter_field',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_FILTER_FIELD_TITLE'),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'filter_field',
			'options' => Array(
				'1' => Array('vname' => 'LBL_FILTER_FIELD_TITLE', 'text_type' => 'static'),	
			//end bottom options
			),
		//end bottom
		),
//end filter_field array	
),
'filter_rel_field' => 
	Array(
		'trigger_type' => 'non_time',
		'filter_type' => Array('Normal'=>'Normal', 'Time' => 'Time'),
		'top' => Array(
			'type' => 'radio',
			'name' => 'type',
			'value' => 'filter_rel_field',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_FILTER_REL_FIELD_TITLE'),
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'filter_rel_field',
			'related' => Array('count' => '1', 'rel1_field' => 'rel_module'),
			'options' => Array(
				'1' => Array('vname' => 'LBL_FILTER_REL_FIELD_PART1', 'text_type' => 'static'),
				'2' => Array(
						'vname' => 'LBL_RECORD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'href',
						'value' => 'rel_module',
						'value_type' => 'relrel_module',
						'possess_next' => 'Yes',
						'jscript_function' => 'get_single_selector2',
						'jscript_content' => array('self', 'rel_module', 'field' ,'trigger_rel_filter')
						),	
				'3' => Array('vname' => 'LBL_FIELD', 'text_type' => 'static'),
			//end bottom options
			),
		//end bottom
		),
//end filter_rel_field array	
),
	
),

'hide_others' => array(
					'target_field' => 'type',
					'target_element' => array(
						'compare_change' => array('compare_change'),
						'compare_specific' => array('compare_specific'),
						'compare_count' => array('compare_count'),
						'compare_any_time' => array('compare_any_time'),
						'trigger_record_change' => array('trigger_record_change'),
					),	
					
				),
//End process dictionary CreateStep1
);







$process_dictionary['CreateStepSpecific'] = Array('action' => 'CreateStepSpecific',
'elements' => array(

'future_trigger' => 
	Array(
		'trigger_type' => 'all',
		'top' => Array(
			'type' => 'checkbox',
			'present' => 'always',
			'name' => '',
			'value' => 'compare_specific',	
			'options' => Array(
				'1' => Array('vname' => 'LBL_FUTURE_TRIGGER', 'text_type' => 'static'),
				'2' => Array(
						'vname' => 'LBL_FIELD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'text',
						'value' => 'field',
						'value_type' => 'normal_field',
						),
				'3' => Array('vname' => 'LBL_VALUE', 'text_type' => 'static')	
			//end top options
			),
		//end top
		),
		'bottom' => Array(
			'type' => 'text',
			'value' => 'compare_specific',
			'options' => Array(
				'1' => Array('vname' => 'LBL_FUTURE_TRIGGER', 'text_type' => 'static'),
				'2' => Array(
						'vname' => 'LBL_FIELD', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'text',
						'value' => 'field',
						'value_type' => 'normal_field',
						),
				'3' => Array(
						'vname' => 'LBL_VALUE', 
						'default' => 'on',
						'text_type' => 'dynamic',
						'type'=> 'href',
						'value' => 'field',
						'value_type' => 'normal_field',
						),				
			//end bottom options
			),
		//end bottom
		),
//end future_trigger array	
),	

),

'hide_others' => array(
					'target_field' => 'type',
					'target_element' => array(
						'compare_change' => array('compare_change'),
						'compare_specific' => array('compare_specific'),
						'compare_count' => array('compare_count'),
					),	
					
				),
//End process dictionary CreateStepSpecific
);











?>