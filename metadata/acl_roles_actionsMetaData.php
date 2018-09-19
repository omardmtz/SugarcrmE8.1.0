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

$dictionary['acl_roles_actions'] = array(
    'table' => 'acl_roles_actions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'role_id' => array(
            'name' => 'role_id',
            'type' => 'id',
        ),
        'action_id' => array(
            'name' => 'action_id',
            'type' => 'id',
        ),
        'access_override' => array(
            'name' => 'access_override',
            'type' => 'int',
            'len' => '3',
            'required' => false,
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
        ),
    ),
    'indices' => array(
        array(
            'name' => 'acl_roles_actionspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_acl_role_id',
            'type' => 'index',
            'fields' => array(
                'role_id',
            ),
        ),
        array(
            'name' => 'idx_acl_action_id',
            'type' => 'index',
            'fields' => array(
                'action_id',
            ),
        ),
        array(
            'name' => 'idx_del_override',
            'type' => 'index',
            'fields' => array(
                'role_id',
                'deleted',
                'action_id',
                'access_override',
            ),
        ),
        array(
            'name' => 'idx_aclrole_action',
            'type' => 'alternate_key',
            'fields' => array(
                'role_id',
                'action_id',
            ),
        ),
    ),
    'relationships' => array(
        'acl_roles_actions' => array(
            'lhs_module' => 'ACLRoles',
            'lhs_table' => 'acl_roles',
            'lhs_key' => 'id',
            'rhs_module' => 'ACLActions',
            'rhs_table' => 'acl_actions',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'acl_roles_actions',
            'join_key_lhs' => 'role_id',
            'join_key_rhs' => 'action_id',
        ),
    ),
);
