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
$dictionary['audit_events'] = array(
    'table' => 'audit_events',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'type' => array(
              'name' => 'type',
              'type' => 'char',
              'len' => 10,
              'required' => true,
        ),
        'parent_id' => array(
              'name' => 'parent_id',
              'type' => 'id',
              'required' => true,
        ),
        'module_name' => array(
            'name' => 'module_name',
            'type' => 'varchar',
            'len' => 100,
            'required' => true,
        ),
        'source' => array(
            'name' => 'source',
            'type' => 'json',
            'dbType' => 'text',
            'required' => false,
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'json',
            'dbType' => 'text',
            'required' => false,
        ),
        'date_created' => array(
            'name' => 'date_created',
            'type' => 'datetime',
            'required' => true,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_aud_eve_id',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_aud_eve_ptd',
            'type' => 'index',
            'fields' => array(
                'parent_id',
                'type',
                'date_created',
            ),
        ),
    ),
);
