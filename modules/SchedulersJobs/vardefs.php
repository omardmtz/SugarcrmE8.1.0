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

$dictionary['SchedulersJob'] = array(
    'table' => 'job_queue',
    'favorites' => true,
    'audited' => true,
    'activity_enabled' => true,
    'comment' => 'Job queue keeps the list of the jobs executed by this instance',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_NAME',
            'type' => 'id',
            'len' => '36',
            'required' => true,
            'reportable' => false,
        ),
       'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => 255,
            'required' => true,
        ),
        'deleted' => array (
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => true,
            'default' => '0',
            'reportable' => false,
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
        ),
        'scheduler_id' => array(
            'name' => 'scheduler_id',
            'vname' => 'LBL_SCHEDULER',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
        ),
        'execute_time' => array(
            'name' => 'execute_time',
            'vname' => 'LBL_EXECUTE_TIME',
            'type' => 'datetime',
            'required' => false,
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'schedulers_status_dom',
            'len' => 20,
            'required' => true,
            'reportable' => true,
            'readonly' => true,
        ),
        'resolution' => array(
            'name' => 'resolution',
            'vname' => 'LBL_RESOLUTION',
            'type' => 'enum',
            'options' => 'schedulers_resolution_dom',
            'len' => 20,
            'required' => true,
            'reportable' => true,
            'readonly' => true,
        ),
        'message' => array(
            'name' => 'message',
            'vname' => 'LBL_MESSAGE',
            'type' => 'text',
            'required' => false,
            'reportable' => false,
        ),
        'target' => array(
            'name' => 'target',
            'vname' => 'LBL_TARGET_ACTION',
            'type' => 'varchar',
            'len' => 255,
            'required' => true,
            'reportable' => true,
        ),
        'data' => array(
            'name' => 'data',
            'vname' => 'LBL_DATA',
            'type' => 'longtext',
            'required' => false,
            'reportable' => true,
        ),
        'requeue' => array(
            'name' => 'requeue',
            'vname' => 'LBL_REQUEUE',
            'type' => 'bool',
            'default' => 0,
            'required' => false,
            'reportable' => true,
        ),
        'retry_count' => array(
            'name' => 'retry_count',
            'vname' => 'LBL_RETRY_COUNT',
            'type' => 'tinyint',
            'required' => false,
            'reportable' => true,
            'readonly' => true,
        ),
        'failure_count' => array(
            'name' => 'failure_count',
            'vname' => 'LBL_FAIL_COUNT',
            'type' => 'tinyint',
            'required' => false,
            'reportable' => true,
            'readonly' => true,
        ),
        'job_delay' => array(
            'name' => 'job_delay',
            'vname' => 'LBL_INTERVAL',
            'type' => 'int',
            'required' => false,
            'reportable' => false,
        ),
        'client' => array(
            'name' => 'client',
            'vname' => 'LBL_CLIENT',
            'type' => 'varchar',
            'len' => 255,
            'required' => true,
            'reportable' => true,
        ),
        'percent_complete' => array(
            'name' => 'percent_complete',
            'vname' => 'LBL_PERCENT',
            'type' => 'int',
            'required' => false,
        ),
        'job_group' => array(
            'name' => 'job_group',
            'vname' => 'LBL_JOB_GROUP',
            'type' => 'id',
            'dbType' => 'varchar',
            'len' => 255,
            'required' => false,
            'reportable' => true,
        ),
        'schedulers' => array(
            'name' => 'schedulers',
            'vname' => 'LBL_SCHEDULER_ID',
            'type' => 'link',
            'relationship' => 'schedulers_jobs_rel',
            'source' => 'non-db',
            'link_type' => 'one',
        ),
        'module' => array(
            'name' => 'module',
            'vname' => 'LBL_MODULE',
            'type' => 'varchar',
            'len' => 255,
            'required' => false,
            'reportable' => true,
        ),
        'fallible' => array(
            'name' => 'fallible',
            'vname' => 'LBL_FALLIBLE',
            'type' => 'bool',
            'default' => '0',
            'audited' => true,
            'comment' => 'An indicator of whether parents failure depends on subtask.'
        ),
        'rerun' => array(
            'name' => 'rerun',
            'vname' => 'LBL_RERUN',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'If a job can be rerun.'
        ),
        'interface' => array(
            'name' => 'interface',
            'vname' => 'LBL_INTERFACE',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Mark the task as interface for a job in job server.'
        ),
        'notes' => array(
            'name' => 'notes',
            'vname' => 'LBL_NOTES',
            'type' => 'link',
            'relationship' => 'schedulersjob_notes',
            'module' => 'Notes',
            'bean_name' => 'Note',
            'source' => 'non-db',
        ),
    ),
    'relationships' => array(
        'schedulersjob_notes' => array(
            'lhs_module' => 'SchedulersJobs',
            'lhs_table' => 'job_queue',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'job_queuepk',
            'type' => 'primary',
            'fields' => array(
                'id'
            )
        ),
        array(
            'name' => 'idx_status_scheduler',
            'type' => 'index',
            'fields' => array(
                'status',
                'scheduler_id',
            )
        ),
        array(
            'name' => 'idx_status_time',
            'type' => 'index',
            'fields' => array(
                'status',
                'execute_time',
                'date_entered',
            )
        ),
        array(
            'name' => 'idx_status_entered',
            'type' => 'index',
            'fields' => array(
                'status',
                'date_entered',
            )
        ),
        array(
            'name' => 'idx_status_modified',
            'type' => 'index',
            'fields' => array(
                'status',
                'date_modified',
            )
        ),
        array(
            'name' => 'idx_group_status',
            'type' => 'index',
            'fields' => array(
                'job_group',
                'status',
            )
        ),
    ),
    'acls' => array(
        'SugarACLAdminOnly' => true,
    ),
    'uses' => array(
        'following',
        'favorite',
    ),
);

VardefManager::createVardef('SchedulersJobs', 'SchedulersJob', array('assignable'));
