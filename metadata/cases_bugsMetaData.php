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

$dictionary['cases_bugs'] = array(
    'table' => 'cases_bugs',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'case_id' => array(
            'name' => 'case_id',
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
            'default' => '0',
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'cases_bugspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_cas_bug_cas',
            'type' => 'index',
            'fields' => array(
                'case_id',
            ),
        ),
        array(
            'name' => 'idx_cas_bug_bug',
            'type' => 'index',
            'fields' => array(
                'bug_id',
            ),
        ),
        array(
            'name' => 'idx_case_bug',
            'type' => 'alternate_key',
            'fields' => array(
                'case_id',
                'bug_id',
            ),
        ),
    ),
    'relationships' => array(
        'cases_bugs' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Bugs',
            'rhs_table' => 'bugs',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'cases_bugs',
            'join_key_lhs' => 'case_id',
            'join_key_rhs' => 'bug_id',
        ),
    ),
);
