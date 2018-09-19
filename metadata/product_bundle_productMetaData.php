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
$dictionary['product_bundle_product'] = array(
    'table' => 'product_bundle_product',
    'fields' => array(
        'id' => array(
            'name' => 'id',
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
        'bundle_id' => array(
            'name' => 'bundle_id',
            'type' => 'id',
        ),
        'product_id' => array(
            'name' => 'product_id',
            'type' => 'id',
        ),
        'product_index' => array(
            'name' => 'product_index',
            'type' => 'int',
            'len' => '11',
            'default' => '0',
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prod_bundl_prodpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_pbp_bundle',
            'type' => 'index',
            'fields' => array(
                'bundle_id',
            ),
        ),
        array(
            'name' => 'idx_pbp_quote',
            'type' => 'index',
            'fields' => array(
                'product_id',
            ),
        ),
        array(
            'name' => 'idx_pbp_bq',
            'type' => 'alternate_key',
            'fields' => array(
                'product_id',
                'bundle_id',
            ),
        ),
    ),
    'relationships' => array(
        'product_bundle_product' => array(
            'lhs_module' => 'ProductBundles',
            'lhs_table' => 'product_bundles',
            'lhs_key' => 'id',
            'rhs_module' => 'Products',
            'rhs_table' => 'products',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'product_bundle_product',
            'join_key_lhs' => 'bundle_id',
            'join_key_rhs' => 'product_id',
            'true_relationship_type' => 'one-to-many',
        ),
    ),
);
