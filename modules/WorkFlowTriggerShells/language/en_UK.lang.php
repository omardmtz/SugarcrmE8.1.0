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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Conditions',
  'LBL_MODULE_NAME_SINGULAR' => 'Condition',
  'LBL_MODULE_TITLE' => 'Workflow Triggers: Home',
  'LBL_MODULE_SECTION_TITLE' => 'When these conditions are met',
  'LBL_SEARCH_FORM_TITLE' => 'Workflow Trigger Search',
  'LBL_LIST_FORM_TITLE' => 'Trigger List',
  'LBL_NEW_FORM_TITLE' => 'Create Trigger',
  'LBL_LIST_NAME' => 'Description:',
  'LBL_LIST_VALUE' => 'Value:',
  'LBL_LIST_TYPE' => 'Type:',
  'LBL_LIST_EVAL' => 'Eval:',
  'LBL_LIST_FIELD' => 'Field:',
  'LBL_NAME' => 'Trigger Name:',
  'LBL_TYPE' => 'Type:',
  'LBL_EVAL' => 'Trigger Evaluation:',
  'LBL_SHOW_PAST' => 'Modify Past Value:',
  'LBL_SHOW' => 'Show',

  'LNK_NEW_TRIGGER' => 'Create Trigger',
  'LNK_TRIGGER' => 'Workflow Triggers',


  'LBL_LIST_STATEMEMT' => 'Trigger an event based on the following:',
  'LBL_FILTER_LIST_STATEMEMT' => 'Filter objects based on the following:',

  'NTC_REMOVE_TRIGGER' => 'Are you sure you want to remove this trigger?',
  'NTC_REMOVE_TRIGGER_PRIMARY' => 'Removing a primary trigger will remove all triggers.',


    'LNK_NEW_WORKFLOW' => 'Create Workflow',
  'LNK_WORKFLOW' => 'Workflow Objects',
  'LBL_ALERT_TEMPLATES' => 'Alert Templates',


//General Labels
  'LBL_TRIGGER' => 'When',
  'LBL_FIELD' => 'field',
  'LBL_VALUE' => 'value',
  'LBL_RECORD' => 'module&#39;s',
//Specific Lables

	'LBL_COMPARE_SPECIFIC_TITLE' => 'When a field in the target module changes to or from a specified value',
	'LBL_COMPARE_SPECIFIC_PART' => 'changes to or from specified value',
	'LBL_COMPARE_SPECIFIC_PART_TIME' => ' ',


	'LBL_COMPARE_CHANGE_TITLE' => 'When a field on the target module changes',
	'LBL_COMPARE_CHANGE_PART' => 'changes',


	'LBL_COMPARE_COUNT_TITLE' => 'Trigger on specific count',

	'LBL_COMPARE_ANY_TIME_TITLE' => 'Field does not change for a specified amount of time',
	'LBL_COMPARE_ANY_TIME_PART2' => 'does not change for',
	'LBL_COMPARE_ANY_TIME_PART3' => 'specified amount of time',

	'LBL_FILTER_FIELD_TITLE' => 'When a field in the target module contains a specified value',
	'LBL_FILTER_FIELD_PART1' => 'Filter by',
	'LBL_FILTER_REL_FIELD_TITLE' => 'When the target module changes and a field in a related module contains a specified value',
	'LBL_FILTER_REL_FIELD_PART1' => 'Specify related',

	'LBL_TRIGGER_RECORD_CHANGE_TITLE' => 'When the target module changes',
	//'LBL_TRIGGER_RECORD_CHANGE_LIST_TITLE' => 'When record changes',

  'LBL_FUTURE_TRIGGER' => 'Specify new',



  'LBL_PAST_TRIGGER' => 'Specify old',


 	////////////////Count Language
 	'LBL_COUNT_TRIGGER1' => 'Total',
 	'LBL_COUNT_TRIGGER1_2' => 'compares to this amount',
 	'LBL_MODULE' => 'module',
  	'LBL_COUNT_TRIGGER2' => 'filter by related',
 	'LBL_COUNT_TRIGGER2_2' => 'only',
 	'LBL_COUNT_TRIGGER3' => 'filter specifically by',
 	'LBL_COUNT_TRIGGER4' => 'filter by a second',


 	'LBL_NEW_FILTER_BUTTON_KEY' => 'F',
 	'LBL_NEW_FILTER_BUTTON_TITLE' => 'Create Filter [Alt+F]',
 	'LBL_NEW_FILTER_BUTTON_LABEL' => 'Create Filter [Alt+F]',

  	'LBL_NEW_TRIGGER_BUTTON_KEY' => 'T',
 	'LBL_NEW_TRIGGER_BUTTON_TITLE' => 'Create Trigger',
 	'LBL_NEW_TRIGGER_BUTTON_LABEL' => 'Create Trigger',

 	'LBL_LIST_FRAME_SEC' => 'Filter:',
 	'LBL_LIST_FRAME_PRI' => 'Trigger:',


 	'LBL_TRIGGER_FILTER_TITLE' => 'Trigger Filters',
 	'LBL_TRIGGER_FORM_TITLE' => 'Define Condition for Workflow Execution',
 	'LBL_FILTER_FORM_TITLE' => 'Define a Workflow Condition',

 	'LBL_SPECIFIC_FIELD'=>"'s specific field",
 	'LBL_APOSTROPHE_S'=>"'s ",
	'LBL_WHEN_VALUE1'=>"When the value of the field is",
	'LBL_WHEN_VALUE2'=>"When the value of",

 	'LBL_SELECT_OPTION' => 'Please select an option.',
	'LBL_SELECT_TARGET_FIELD' => 'Please select a target field.',
	'LBL_SELECT_TARGET_MOD' => 'Please select a target related module.',
	'LBL_SPECIFIC_FIELD_LNK' => 'specific field',
	'LBL_MUST_SELECT_VALUE' => 'You must select a value for this field',
	
	'LBL_SELECT_AMOUNT' => 'You must select the amount',
	'LBL_SELECT_1ST_FILTER' => 'You must select a valid 1st filter field',
	'LBL_SELECT_2ND_FILTER' => 'You must select a valid 2nd filter field',

	'ERR_DELETE_RECORD' => 'A record number must be specified to delete the record.',
	'ERR_DELETE_EMPTY' => 'The record is already deleted or non-existent.',

);

$mod_process_order_strings = array(

	'compare_specific' => array('1','2','3'),
	'compare_change' => array('1','2','3'),
	'compare_count' => array('1','2','3')

);




?>
