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

$dictionary['projects_revenuelineitems'] = array(
    'table' => 'projects_revenue_line_items',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'rli_id' => array(
            'name' => 'rli_id',
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
            'name' => 'projects_rli_pk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_proj_rli_project',
            'type' => 'index',
            'fields' => array(
                'project_id',
            ),
        ),
        array(
            'name' => 'idx_proj_rli_product',
            'type' => 'index',
            'fields' => array(
                'rli_id',
            ),
        ),
        array(
            'name' => 'projects_rli_alt',
            'type' => 'alternate_key',
            'fields' => array(
                'project_id',
                'rli_id',
            ),
        ),
    ),
    'relationships' => array(
        'projects_revenuelineitems' => array(
            'lhs_module' => 'Project',
            'lhs_table' => 'project',
            'lhs_key' => 'id',
            'rhs_module' => 'RevenueLineItems',
            'rhs_table' => 'revenue_line_items',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'projects_products',
            'join_key_lhs' => 'project_id',
            'join_key_rhs' => 'rli_id',
        ),
    ),
);
