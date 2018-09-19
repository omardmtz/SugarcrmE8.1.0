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

$dictionary['users_holidays'] = array(
    'table' => 'users_holidays',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
        ),
        'holiday_id' => array(
            'name' => 'holiday_id',
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
            'name' => 'users_holidays_pk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_user_holi_user',
            'type' => 'index',
            'fields' => array(
                'user_id',
            ),
        ),
        array(
            'name' => 'idx_user_holi_holi',
            'type' => 'index',
            'fields' => array(
                'holiday_id',
            ),
        ),
        array(
            'name' => 'users_quotes_alt',
            'type' => 'alternate_key',
            'fields' => array(
                'user_id',
                'holiday_id',
            ),
        ),
    ),
    'relationships' => array(
        'users_holidays' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Holidays',
            'rhs_table' => 'holidays',
            'rhs_key' => 'person_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'related_module',
            'relationship_role_column_value' => null,
        ),
    ),
);
