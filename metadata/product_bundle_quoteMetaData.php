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

$dictionary['product_bundle_quote'] = array(
    'table' => 'product_bundle_quote',
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
        'quote_id' => array(
            'name' => 'quote_id',
            'type' => 'id',
        ),
        'bundle_index' => array(
            'name' => 'bundle_index',
            'type' => 'int',
            'len' => '11',
            'default' => 0,
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prod_bundl_quotepk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_pbq_bundle',
            'type' => 'index',
            'fields' => array(
                'bundle_id',
            ),
        ),
        array(
            'name' => 'idx_pbq_quote',
            'type' => 'index',
            'fields' => array(
                'quote_id',
            ),
        ),
        array(
            'name' => 'idx_pbq_bq',
            'type' => 'alternate_key',
            'fields' => array(
                'quote_id',
                'bundle_id',
            ),
        ),
        array(
            'name' => 'bundle_index_idx',
            'type' => 'index',
            'fields' => array(
                'bundle_index',
            ),
        ),
    ),
    'relationships' => array(
        'product_bundle_quote' => array(
            'lhs_module' => 'Quotes',
            'lhs_table' => 'quotes',
            'lhs_key' => 'id',
            'rhs_module' => 'ProductBundles',
            'rhs_table' => 'product_bundles',
            'rhs_key' => 'id',
            'relationship_type' => 'one-to-many',
            'join_table' => 'product_bundle_quote',
            'join_key_lhs' => 'quote_id',
            'join_key_rhs' => 'bundle_id',
            'true_relationship_type' => 'one-to-many',
        ),
    ),
);
