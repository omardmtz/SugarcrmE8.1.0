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
$dictionary['accounts_dataprivacy'] = array(
    'table' => 'accounts_dataprivacy',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'id',
        ),
        'dataprivacy_id' => array(
            'name' => 'dataprivacy_id',
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
            'name' => 'accounts_dataprivacypk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_acc_dataprivacy_acc',
            'type' => 'index',
            'fields' => array(
                'account_id',
            ),
        ),
        array(
            'name' => 'idx_acc_dataprivacy_dataprivacy',
            'type' => 'index',
            'fields' => array(
                'dataprivacy_id',
            ),
        ),
        array(
            'name' => 'idx_accounts_dataprivacy',
            'type' => 'alternate_key',
            'fields' => array(
                'account_id',
                'dataprivacy_id',
            ),
        ),
    ),
    'relationships' => array(
        'accounts_dataprivacy' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'DataPrivacy',
            'rhs_table' => 'data_privacy',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'accounts_dataprivacy',
            'join_key_lhs' => 'account_id',
            'join_key_rhs' => 'dataprivacy_id',
        ),
    ),
);
