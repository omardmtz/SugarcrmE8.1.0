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

$dictionary['accounts_bugs'] = array(
    'table' => 'accounts_bugs',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'id',
        ),
        'bug_id' => array(
            'name' => 'bug_id',
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
            'required' => false,
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'accounts_bugspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_acc_bug_acc',
            'type' => 'index',
            'fields' => array(
                'account_id',
            ),
        ),
        array(
            'name' => 'idx_acc_bug_bug',
            'type' => 'index',
            'fields' => array(
                'bug_id',
            ),
        ),
        array(
            'name' => 'idx_account_bug',
            'type' => 'alternate_key',
            'fields' => array(
                'account_id',
                'bug_id',
            ),
        ),
    ),
    'relationships' => array(
        'accounts_bugs' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Bugs',
            'rhs_table' => 'bugs',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'accounts_bugs',
            'join_key_lhs' => 'account_id',
            'join_key_rhs' => 'bug_id',
        ),
    ),
);
