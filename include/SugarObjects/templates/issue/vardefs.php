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
$vardefs = array (
	'fields' => array (
        $_object_name . '_number' => array (
			'name' => $_object_name . '_number',
			'vname' => 'LBL_NUMBER',
			'type' => 'int',
            'readonly' => true,
			'len' => 11,
			'required' => true,
			'auto_increment' => true,
			'unified_search' => true,
            'full_text_search' => array('enabled' => true, 'searchable' => true,  'boost' => 1.25),
			'comment' => 'Visual unique identifier',
			'duplicate_merge' => 'disabled',
			'disable_num_format' => true,
			'studio' => array('quickcreate' => false),
            'duplicate_on_record_copy' => 'no',
		),

        'name' => array (
			'name' => 'name',
			'vname' => 'LBL_SUBJECT',
			'type' => 'name',
			'dbType' => 'varchar',
			'len' => 255,
			'audited' => true,
			'unified_search' => true,
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => true,
                'boost' => 1.47,
            ),
			'comment' => 'The short description of the bug',
			'merge_filter' => 'selected',
			'required'=>true,
            'importable' => 'required',
            'duplicate_on_record_copy' => 'always',

		),
        'type' => array (
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'options' => strtolower($object_name) . '_type_dom',
            'len'=>255,
            'comment' => 'The type of issue (ex: issue, feature)',
            'merge_filter' => 'enabled',
            'sortable' => true,
            'duplicate_on_record_copy' => 'always',
        ),

		'status' => array (
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => strtolower($object_name) . '_status_dom',
            'len' => 100,
            'audited' => true,
            'comment' => 'The status of the issue',
            'merge_filter' => 'enabled',
            'sortable' => true,
            'duplicate_on_record_copy' => 'always',
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => false,
            ),
		),

        'priority' => array (
			'name' => 'priority',
			'vname' => 'LBL_PRIORITY',
			'type' => 'enum',
			'options' => strtolower($object_name) . '_priority_dom',
			'len' => 100,
			'audited' => true,
			'comment' => 'An indication of the priorty of the issue',
			'merge_filter' => 'enabled',
            'sortable' => true,
            'duplicate_on_record_copy' => 'always',
		),

        'resolution' => array (
			'name' => 'resolution',
			'vname' => 'LBL_RESOLUTION',
			'type' => 'enum',
			'options' => strtolower($object_name) . '_resolution_dom',
			'len' => 255,
			'audited' => true,
			'comment' => 'An indication of how the issue was resolved',
			'merge_filter' => 'enabled',
            'sortable' => true,
            'duplicate_on_record_copy' => 'always',

		),
			//not in cases.
	    'work_log' => array (
			'name' => 'work_log',
			'vname' => 'LBL_WORK_LOG',
			'type' => 'text',
            'full_text_search' => array('enabled' => true, 'searchable' => true,  'boost' => 0.51),
            'duplicate_on_record_copy' => 'always',
			'comment' => 'Free-form text used to denote activities of interest'
		),


	),
	'indices'=>array(
		 'number'=>array('name' =>strtolower($module).'numk', 'type' =>'unique', 'fields'=>array($_object_name . '_number'))
	),
        'uses' => array(
            'taggable',
        ),
    'duplicate_check' => array(
        'enabled' => true,
        'FilterDuplicateCheck' => array(
            'filter_template' => array(
                array('name' => array('$starts' => '$name')),
            ),
            'ranking_fields' => array(
                array('in_field_name' => 'name', 'dupe_field_name' => 'name'),
            )
        )
    ),
);
