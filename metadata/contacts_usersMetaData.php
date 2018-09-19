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

$dictionary['contacts_users'] = array(
    'table' => 'contacts_users',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'id',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
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
            'name' => 'contacts_userspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_con_users_con',
            'type' => 'index',
            'fields' => array(
                'contact_id',
            ),
        ),
        array(
            'name' => 'idx_con_users_user',
            'type' => 'index',
            'fields' => array(
                'user_id',
            ),
        ),
        array(
            'name' => 'idx_contacts_users',
            'type' => 'alternate_key',
            'fields' => array(
                'contact_id',
                'user_id',
            ),
        ),
    ),
    'relationships' => array(
        'contacts_users' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'user-based',
            'join_table' => 'contacts_users',
            'join_key_lhs' => 'user_id',
            'join_key_rhs' => 'contact_id',
            'user_field' => 'user_id',
        ),
    ),
);
