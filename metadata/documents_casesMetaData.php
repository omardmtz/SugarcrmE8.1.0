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

$dictionary['documents_cases'] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' => array(
        'documents_cases' => array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_cases',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'case_id',
        ),
    ),
    'table' => 'documents_cases',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => 36,
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
            'required' => true,
        ),
        'document_id' => array(
            'name' => 'document_id',
            'type' => 'id',
            'len' => 36,
        ),
        'case_id' => array(
            'name' => 'case_id',
            'type' => 'id',
            'len' => 36,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'documents_casesspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'documents_cases_case_id',
            'type' => 'alternate_key',
            'fields' => array(
                'case_id',
                'document_id',
            ),
        ),
        array(
            'name' => 'documents_cases_document_id',
            'type' => 'alternate_key',
            'fields' => array(
                'document_id',
                'case_id',
            ),
        ),
    ),
);
