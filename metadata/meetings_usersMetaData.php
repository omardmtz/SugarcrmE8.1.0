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

$dictionary['meetings_users'] = array(
    'table' => 'meetings_users',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => '36',
        ),
        'meeting_id' => array(
            'name' => 'meeting_id',
            'type' => 'id',
            'len' => '36',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
            'len' => '36',
        ),
        'required' => array(
            'name' => 'required',
            'type' => 'varchar',
            'len' => '1',
            'default' => '1',
        ),
        'accept_status' => array(
            'name' => 'accept_status',
            'type' => 'varchar',
            'len' => '25',
            'default' => 'none',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
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
            'name' => 'meetings_userspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_usr_mtg_mtg',
            'type' => 'index',
            'fields' => array(
                'meeting_id',
            ),
        ),
        array(
            'name' => 'idx_usr_mtg_usr',
            'type' => 'index',
            'fields' => array(
                'user_id',
            ),
        ),
        array(
            'name' => 'idx_meeting_users',
            'type' => 'alternate_key',
            'fields' => array(
                'meeting_id',
                'user_id',
            ),
        ),
        array(
            'name' => 'idx_meeting_users_del',
            'type' => 'alternate_key',
            'fields' => array(
                'meeting_id',
                'user_id',
                'deleted',
            ),
        ),
    ),
    'relationships' => array(
        'meetings_users' => array(
            'lhs_module' => 'Meetings',
            'lhs_table' => 'meetings',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'meetings_users',
            'join_key_lhs' => 'meeting_id',
            'join_key_rhs' => 'user_id',
        ),
    ),
);
