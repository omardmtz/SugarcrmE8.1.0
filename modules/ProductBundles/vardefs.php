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
$dictionary['ProductBundle'] = array(
    'table' => 'product_bundles',
    'comment' => 'Quote groups',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_NAME',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => false,
            'default' => '0',
            'reportable' => false,
            'comment' => 'Record deletion indicator'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record created'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record last modified'
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'reportable' => true,
            'comment' => 'User who last modified record'
        ),
        'created_by' => array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User who created record'
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'dbType' => 'varchar',
            'type' => 'name',
            'len' => '255',
            'comment' => 'Name of the group'
        ),
        'bundle_stage' => array(
            'name' => 'bundle_stage',
            'vname' => 'LBL_BUNDLE_STAGE',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Processing stage of the group (ex: Draft)'
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Group description'
        ),
        'taxrate_id' => array(
            'name' => 'taxrate_id',
            'vname' => 'LBL_TAXRATE_ID',
            'type' => 'id',
        ),
        'tax' => array(
            'name' => 'tax',
            'vname' => 'LBL_TAX',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Tax rate applied to items in the group',
            'related_fields' => array(
                'currency_id',
                'base_rate',
                'taxrate_id',
                'new_sub',
            ),
        ),
        'tax_usdollar' => array(
            'name' => 'tax_usdollar',
            'vname' => 'LBL_TAX_USDOLLAR',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Total tax for all items in group in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(and(isNumeric($tax), not(equal($tax, 0))), currencyDivide($tax, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'total' => array(
            'name' => 'total',
            'vname' => 'LBL_TOTAL',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Total amount for all items in the group',
            'related_fields' => array(
                'currency_id',
                'base_rate',
                'new_sub',
                'tax',
                'shipping',
            ),
            //left currencyAdd here as a placeholder for when we do per group tax/shipping.
            'formula' => 'currencyAdd(
                $new_sub,
                "0"
            )',
            'calculated' => true,
            'enforced' => true,
        ),
        'total_usdollar' => array(
            'name' => 'total_usdollar',
            'vname' => 'LBL_TOTAL_USDOLLAR',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Total amount for all items in the group in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($total), currencyDivide($total, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'subtotal_usdollar' => array(
            'name' => 'subtotal_usdollar',
            'vname' => 'LBL_SUBTOTAL_USDOLLAR',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Group total minus tax and shipping in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($subtotal), currencyDivide($subtotal, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'shipping_usdollar' => array(
            'name' => 'shipping_usdollar',
            'vname' => 'LBL_SHIPPING',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Shipping charge for group in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($shipping), currencyDivide($shipping, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'deal_tot' => array(
            'name' => 'deal_tot',
            'vname' => 'LBL_DEAL_TOT',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'discount amount',
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'rollupCurrencySum($products, "deal_calc")',
            'calculated' => true,
            'enforced' => true,
        ),
        'deal_tot_usdollar' => array(
            'name' => 'deal_tot_usdollar',
            'vname' => 'LBL_DEAL_TOT',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'discount amount',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($deal_tot), currencyDivide($deal_tot, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'new_sub' => array(
            'name' => 'new_sub',
            'vname' => 'LBL_NEW_SUB',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Group total minus discount and tax and shipping',
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'currencySubtract($subtotal, $deal_tot)',
            'enforced' => true,
            'calculated' => true,
        ),
        'new_sub_usdollar' => array(
            'name' => 'new_sub_usdollar',
            'vname' => 'LBL_NEW_SUB',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Group total minus discount and tax and shipping',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($new_sub), currencyDivide($new_sub, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,

        ),
        'subtotal' => array(
            'name' => 'subtotal',
            'vname' => 'LBL_SUBTOTAL',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Group total minus tax and shipping',
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'rollupCurrencySum($products, "subtotal")',
            'calculated' => true,
            'enforced' => true,
        ),
        'taxable_subtotal' => array(
            'name' => 'taxable_subtotal',
            'vname' => 'LBL_TAXABLE_SUBTOTAL',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Rollup of all products marked as Taxable',
            'formula' => 'rollupConditionalSum($products, "total_amount", "tax_class", "Taxable")',
            'calculated' => true,
            'enforced' => true,
        ),
        'shipping' => array(
            'name' => 'shipping',
            'vname' => 'LBL_SHIPPING',
            'type' => 'currency',
            'len' => '26,6',
            'disable_num_format' => true,
            'comment' => 'Shipping charge for group',
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
        ),
        'taxrate' => array(
            'name' => 'taxrate',
            'type' => 'link',
            'relationship' => 'product_bundle_taxrate',
            'module' => 'TaxRates',
            'bean_name' => 'TaxRate',
            'source' => 'non-db'
        ),
        'products' => array(
            'name' => 'products',
            'type' => 'link',
            'relationship' => 'product_bundle_product',
            'module' => 'Products',
            'bean_name' => 'Product',
            'source' => 'non-db',
            'rel_fields' => array('product_index' => array('type' => 'integer')),
            'vname' => 'LBL_PRODUCTS',
        ),
        'quotes' => array(
            'name' => 'quotes',
            'type' => 'link',
            'relationship' => 'product_bundle_quote',
            'module' => 'Quotes',
            'bean_name' => 'Quote',
            'source' => 'non-db',
            'rel_fields' => array('bundle_index' => array('type' => 'integer')),
            'relationship_fields' => array('bundle_index' => 'bundle_index'),
            'vname' => 'LBL_QUOTES',
        ),
        'product_bundle_notes' => array(
            'name' => 'product_bundle_notes',
            'type' => 'link',
            'relationship' => 'product_bundle_note',
            'module' => 'ProductBundleNotes',
            'bean_name' => 'ProductBundleNote',
            'source' => 'non-db',
            'rel_fields' => array('note_index' => array('type' => 'integer')),
            'vname' => 'LBL_NOTES',
        ),
        'product_bundle_items' => array(
            'name' => 'product_bundle_items',
            'type' => 'collection',
            'vname' => 'LBL_PRODUCT_BUNDLES',
            'links' => array('products','product_bundle_notes'),
            'source' => 'non-db',
            'order_by' => 'position:asc',
        ),
        'position' => array(
            'massupdate' => false,
            'name' => 'position',
            'type' => 'integer',
            'studio' => false,
            'vname' => 'LBL_QUOTE_BUNDLE_POSITION',
            'importable' => false,
            'source' => 'non-db',
            'link' => 'quotes',
            'rname_link' => 'bundle_index',
        ),
        'default_group' => array(
            'name' => 'default_group',
            'type' => 'bool',
            'studio' => false,
            'vname' => 'LBL_QUOTE_BUNDLE_DEFAULT_GROUP',
            'importable' => false,
            'default' => false,
        ),
    ),
    'indices' => array(
        array('name' => 'procuct_bundlespk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_products_bundles', 'type' => 'index', 'fields' => array('name', 'deleted')),
    ),
    'relationships' => array(
        'product_bundle_taxrate' => array(
            'rhs_module' => 'ProductBundles',
            'rhs_table' => 'product_bundles',
            'rhs_key' => 'taxrate_id',
            'lhs_module' => 'TaxRates',
            'lhs_table' => 'taxrates',
            'lhs_key' => 'id',
            'relationship_type' => 'one-to-many',
        ),
    )
);

VardefManager::createVardef(
    'ProductBundles',
    'ProductBundle',
    array(
        'team_security',
        'currency'
    )
);
