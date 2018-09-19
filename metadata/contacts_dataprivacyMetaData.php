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
$dictionary['contacts_dataprivacy'] = array(
    'table' => 'contacts_dataprivacy',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'contact_id' => array(
            'name' => 'contact_id',
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
            'name' => 'contacts_dataprivacypk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_con_dataprivacy_con',
            'type' => 'index',
            'fields' => array(
                'contact_id',
            ),
        ),
        array(
            'name' => 'idx_con_dataprivacy_dataprivacy',
            'type' => 'index',
            'fields' => array(
                'dataprivacy_id',
            ),
        ),
        array(
            'name' => 'idx_contacts_dataprivacy',
            'type' => 'alternate_key',
            'fields' => array(
                'contact_id',
                'dataprivacy_id',
            ),
        ),
    ),
    'relationships' => array(
        'contacts_dataprivacy' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'DataPrivacy',
            'rhs_table' => 'data_privacy',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'contacts_dataprivacy',
            'join_key_lhs' => 'contact_id',
            'join_key_rhs' => 'dataprivacy_id',
        ),
    ),
);
