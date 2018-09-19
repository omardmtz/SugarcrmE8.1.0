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
$dictionary['Scheduler'] = array('table' => 'schedulers',
	'fields' => array (
		'job' => array (
			'name' => 'job',
			'vname' => 'LBL_JOB',
			'type' => 'varchar',
			'len' => '255',
			'required' => true,
			'reportable' => false,
		),
		'job_url' => array (
			'name' => 'job_url',
			'vname' => 'LBL_JOB_URL',
			'type' => 'varchar',
			'len' => '255',
            'required' => true,
			'reportable' => false,
			'source' => 'non-db',
			'dependency' => 'equal($job_function, "url::")'
		),
		'job_function' => array (
			'name' => 'job_function',
			'vname' => 'LBL_JOB',
			'type' => 'enum',
			'function' => array('name' => array('Scheduler', 'getJobsList'), 'params' => array()),
			'len' => '255',
			'required' => false,
			'reportable' => false,
            'massupdate' => false,
			'source' => 'non-db',
		),
		'date_time_start' => array (
			'name' => 'date_time_start',
			'vname' => 'LBL_DATE_TIME_START',
			'type' => 'datetimecombo',
			'required' => true,
			'reportable' => false,
			//Previously Editview on scheduler assigned default value as $timedate->fromString('2005-01-01')
            //the bottom value follows previous default value.
            'display_default' => '2005/01/01'
		),
		'date_time_end' => array (
			'name' => 'date_time_end',
			'vname' => 'LBL_DATE_TIME_END',
			'type' => 'datetimecombo',
			'reportable' => false,
		),
		'job_interval' => array (
			'name' => 'job_interval',
			'vname' => 'LBL_INTERVAL',
			'type' => 'varchar',
			'len' => '100',
			'required' => true,
			'reportable' => false,
		),
		'adv_interval' => array (
			'name' => 'adv_interval',
			'vname' => 'LBL_ADV_OPTIONS',
			'type' => 'bool',
			'required' => false,
			'reportable' => false,
			'source' => 'non-db',
		),

		'time_from' => array (
			'name' => 'time_from',
			'vname' => 'LBL_TIME_FROM',
			'type' => 'time',
			'required' => false,
			'reportable' => false,
		),
		'time_to' => array (
			'name' => 'time_to',
			'vname' => 'LBL_TIME_TO',
			'type' => 'time',
			'required' => false,
			'reportable' => false,
		),
		'last_run' => array (
			'name' => 'last_run',
			'vname' => 'LBL_LAST_RUN',
			'type' => 'datetime',
			'required' => false,
			'reportable' => false,
            'massupdate' => false,
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'enum',
			'options' => 'scheduler_status_dom',
			'len' => 100,
			'required' => false,
			'reportable' => false,
			'importable' => 'required',
		),
		'catch_up' => array (
			'name' => 'catch_up',
			'vname' => 'LBL_CATCH_UP',
			'type' => 'bool',
			'len' => 1,
			'required' => false,
			'default' => '1',
			'reportable' => false,
		),
		'schedulers_times' => array (
			'name'			=> 'schedulers_times',
			'vname'			=> 'LBL_SCHEDULER_TIMES',
			'type'			=> 'link',
			'relationship'	=> 'schedulers_jobs_rel',
			'module'		=> 'SchedulersJobs',
			'bean_name'		=> 'Scheduler',
			'source'		=> 'non-db',
		),
	),
    'acls' => array('SugarACLAdminOnly' => true),
	'indices' => array (
		array(
		'name' =>'idx_schedule',
		'type'=>'index',
		'fields' => array(
			'date_time_start',
			'deleted'
			)
		),
        array('name' => 'idx_scheduler_job_interval', 'type' => 'index', 'fields' => array('job_interval')),
        array('name' => 'idx_scheduler_status', 'type' => 'index', 'fields' => array('status')),
    ),
	'relationships' => array (
		'schedulers_created_by_rel' => array (
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Schedulers',
			'rhs_table'			=> 'schedulers',
			'rhs_key'			=> 'created_by',
			'relationship_type'	=> 'one-to-one'
		),
		'schedulers_modified_user_id_rel' => array (
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Schedulers',
			'rhs_table'			=> 'schedulers',
			'rhs_key'			=> 'modified_user_id',
			'relationship_type'	=> 'one-to-many'
		),
		'schedulers_jobs_rel' => array(
			'lhs_module'					=> 'Schedulers',
			'lhs_table'						=> 'schedulers',
			'lhs_key' 						=> 'id',
			'rhs_module'					=> 'SchedulersJobs',
			'rhs_table'						=> 'job_queue',
			'rhs_key' 						=> 'scheduler_id',
			'relationship_type' 			=> 'one-to-many',
		),
	)
);

VardefManager::createVardef('Schedulers','Scheduler', array('default'));
