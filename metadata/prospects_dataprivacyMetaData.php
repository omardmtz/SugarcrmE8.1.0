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
$dictionary['prospects_dataprivacy'] = array(
    'table' => 'prospects_dataprivacy',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'prospect_id' => array(
            'name' => 'prospect_id',
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
            'name' => 'prospects_dataprivacypk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_prospect_dataprivacy_prospect',
            'type' => 'index',
            'fields' => array(
                'prospect_id',
            ),
        ),
        array(
            'name' => 'idx_prospect_dataprivacy_dataprivacy',
            'type' => 'index',
            'fields' => array(
                'dataprivacy_id',
            ),
        ),
        array(
            'name' => 'idx_prospects_dataprivacy',
            'type' => 'alternate_key',
            'fields' => array(
                'prospect_id',
                'dataprivacy_id',
            ),
        ),
    ),
    'relationships' => array(
        'prospects_dataprivacy' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'DataPrivacy',
            'rhs_table' => 'data_privacy',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'prospects_dataprivacy',
            'join_key_lhs' => 'prospect_id',
            'join_key_rhs' => 'dataprivacy_id',
        ),
    ),
);
