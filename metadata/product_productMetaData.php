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

$dictionary['product_product'] = array(
    'table' => 'product_product',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'type' => 'id',
        ),
        'child_id' => array(
            'name' => 'child_id',
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
            'name' => 'prod_prodpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_pp_parent',
            'type' => 'index',
            'fields' => array(
                'parent_id',
            ),
        ),
        array(
            'name' => 'idx_pp_child',
            'type' => 'index',
            'fields' => array(
                'child_id',
            ),
        ),
    ),
    'relationships' => array(
        'product_product' => array(
            'lhs_module' => 'Products',
            'lhs_table' => 'products',
            'lhs_key' => 'id',
            'rhs_module' => 'Products',
            'rhs_table' => 'products',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'product_product',
            'join_key_lhs' => 'parent_id',
            'join_key_rhs' => 'child_id',
            'reverse' => '1',
        ),
    ),
);
