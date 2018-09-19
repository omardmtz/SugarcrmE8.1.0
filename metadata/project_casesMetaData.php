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

$dictionary['projects_cases'] = array(
    'table' => 'projects_cases',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'case_id' => array(
            'name' => 'case_id',
            'type' => 'id',
        ),
        'project_id' => array(
            'name' => 'project_id',
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
            'name' => 'projects_cases_pk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_proj_case_proj',
            'type' => 'index',
            'fields' => array(
                'project_id',
            ),
        ),
        array(
            'name' => 'idx_proj_case_case',
            'type' => 'index',
            'fields' => array(
                'case_id',
            ),
        ),
        array(
            'name' => 'projects_cases_alt',
            'type' => 'alternate_key',
            'fields' => array(
                'project_id',
                'case_id',
            ),
        ),
    ),
    'relationships' => array(
        'projects_cases' => array(
            'lhs_module' => 'Project',
            'lhs_table' => 'project',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'projects_cases',
            'join_key_lhs' => 'project_id',
            'join_key_rhs' => 'case_id',
        ),
    ),
);
