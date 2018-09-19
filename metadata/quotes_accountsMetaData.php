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

$dictionary['quotes_accounts'] = array(
    'table' => 'quotes_accounts',
    'true_relationship_type' => 'one-to-many',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'quote_id' => array(
            'name' => 'quote_id',
            'type' => 'id',
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'id',
        ),
        'account_role' => array(
            'name' => 'account_role',
            'type' => 'varchar',
            'len' => '20',
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
            'name' => 'quotes_accountspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_acc_qte_acc',
            'type' => 'index',
            'fields' => array(
                'account_id',
            ),
        ),
        array(
            'name' => 'idx_acc_qte_opp',
            'type' => 'index',
            'fields' => array(
                'quote_id',
            ),
        ),
        array(
            'name' => 'idx_quote_account_role',
            'type' => 'alternate_key',
            'fields' => array(
                'quote_id',
                'account_role',
            ),
        ),
    ),
    'relationships' => array(
        'quotes_billto_accounts' => array(
            'rhs_module' => 'Quotes',
            'rhs_table' => 'quotes',
            'rhs_key' => 'id',
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'true_relationship_type' => 'one-to-many',
            'join_table' => 'quotes_accounts',
            'join_key_rhs' => 'quote_id',
            'join_key_lhs' => 'account_id',
            'relationship_role_column' => 'account_role',
            'relationship_role_column_value' => 'Bill To',
        ),
        'quotes_shipto_accounts' => array(
            'rhs_module' => 'Quotes',
            'rhs_table' => 'quotes',
            'rhs_key' => 'id',
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'true_relationship_type' => 'one-to-many',
            'join_table' => 'quotes_accounts',
            'join_key_rhs' => 'quote_id',
            'join_key_lhs' => 'account_id',
            'relationship_role_column' => 'account_role',
            'relationship_role_column_value' => 'Ship To',
        ),
    ),
);
