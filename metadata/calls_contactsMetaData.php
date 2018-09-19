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

$dictionary['calls_contacts'] = array(
    'table' => 'calls_contacts',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => '36',
        ),
        'call_id' => array(
            'name' => 'call_id',
            'type' => 'id',
            'len' => '36',
        ),
        'contact_id' => array(
            'name' => 'contact_id',
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
            'name' => 'calls_contactspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_con_call_call',
            'type' => 'index',
            'fields' => array(
                'call_id',
            ),
        ),
        array(
            'name' => 'idx_con_call_con',
            'type' => 'index',
            'fields' => array(
                'contact_id',
            ),
        ),
        array(
            'name' => 'idx_call_contact',
            'type' => 'alternate_key',
            'fields' => array(
                'call_id',
                'contact_id',
            ),
        ),
    ),
    'relationships' => array(
        'calls_contacts' => array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'calls_contacts',
            'join_key_lhs' => 'call_id',
            'join_key_rhs' => 'contact_id',
        ),
    ),
);
