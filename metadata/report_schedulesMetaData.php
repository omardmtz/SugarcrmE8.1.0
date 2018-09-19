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

$dictionary['report_schedules'] = array(
    'table' => 'report_schedules',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'dbType' => 'id',
            'len' => '36',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'varchar',
            'dbType' => 'id',
            'len' => '36',
            'required' => true,
        ),
        'report_id' => array(
            'name' => 'report_id',
            'type' => 'varchar',
            'dbType' => 'id',
            'len' => '36',
            'required' => true,
        ),
        'date_start' => array(
            'name' => 'date_start',
            'type' => 'datetime',
        ),
        'next_run' => array(
            'name' => 'next_run',
            'type' => 'datetime',
            'len' => '',
            'required' => true,
        ),
        'active' => array(
            'name' => 'active',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true,
        ),
        'time_interval' => array(
            'name' => 'time_interval',
            'type' => 'int',
            'len' => '11',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'schedule_type' => array(
            'name' => 'schedule_type',
            'type' => 'varchar',
            'len' => '3',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'report_schedulespk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
    ),
    array(
        'name' => 'idx_report_schedule_user_id',
        'type' => 'index',
        'fields' => array(
            'user_id',
        ),
    ),
);
