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

$dictionary['calls_leads'] = array(
    'table' => 'calls_leads',
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
        'lead_id' => array(
            'name' => 'lead_id',
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
            'name' => 'calls_leadspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_lead_call_call',
            'type' => 'index',
            'fields' => array(
                'call_id',
            ),
        ),
        array(
            'name' => 'idx_lead_call_lead',
            'type' => 'index',
            'fields' => array(
                'lead_id',
            ),
        ),
        array(
            'name' => 'idx_call_lead',
            'type' => 'alternate_key',
            'fields' => array(
                'call_id',
                'lead_id',
            ),
        ),
    ),
    'relationships' => array(
        'calls_leads' => array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'calls_leads',
            'join_key_lhs' => 'call_id',
            'join_key_rhs' => 'lead_id',
        ),
    ),
);
