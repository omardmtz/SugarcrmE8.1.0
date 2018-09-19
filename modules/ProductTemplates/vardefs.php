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
$dictionary['ProductTemplate'] = array(
    'table' => 'product_templates',
    'comment' => 'The Admin view of a Product in Product Catalog; used as template for a product instance',
    'audited' => true,
    'favorites' => false,
    'full_text_search' => true,
    'fields' => array(
        'type_id' => array(
            'name' => 'type_id',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'vname' => 'LBL_TYPE_ID',
            'importable' => 'true',
            'comment' => 'Product type (ex: hardware, software)',
        ),
        'manufacturer_id' => array(
            'name' => 'manufacturer_id',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'vname' =>'LBL_LIST_MANUFACTURER_ID',
            'importable' => 'true',
            'comment' => 'Manufacturer of the product',
        ),
        'manufacturer_name' => array(
            'name' => 'manufacturer_name',
            'rname' => 'name',
            'id_name'=> 'manufacturer_id',
            'type' => 'relate',
            'vname' =>'LBL_MANUFACTURER_NAME',
            'join_name' => 'manufacturers',
            'link' => 'manufacturer_link',
            'table' => 'manufacturers',
            'isnull' => 'true',
            'source' => 'non-db',
            'module' => 'Manufacturers',
            'dbType' => 'varchar',
            'len' => '255',
            'studio' => 'false'
        ),
        'category_id' => array(
            'name' => 'category_id',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'vname' => 'LBL_LIST_CATEGORY_ID',
            'importable' => 'true',
            'comment' => 'Category of the product'
        ),
        'category_name' => array(
            'name' => 'category_name',
            'rname' => 'name',
            'id_name' => 'category_id',
            'vname' => 'LBL_CATEGORY_NAME',
            'join_name'=>'product_categories',
            'type' => 'relate',
            'link' => 'category_link',
            'table' => 'product_categories',
            'isnull' => 'true',
            'module' => 'ProductCategories',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
        ),
        'type_name' => array(
            'name' => 'type_name',
            'rname' => 'name',
            'id_name' => 'type_id',
            'vname' => 'LBL_TYPE',
            'join_name'=>'product_types',
            'type' => 'relate',
            'link' => 'type_link',
            'table' => 'product_types',
            'isnull' => 'true',
            'module' => 'ProductTypes',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            'importable' => 'true',
        ),
        'mft_part_num' => array(
            'name' => 'mft_part_num',
            'vname' => 'LBL_MFT_PART_NUM',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'Manufacturer part number'
        ),
        'vendor_part_num' => array(
            'name' => 'vendor_part_num',
            'vname' => 'LBL_VENDOR_PART_NUM',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'Vendor part number'
        ),
        'date_cost_price' => array(
            'name' => 'date_cost_price',
            'vname' => 'LBL_DATE_COST_PRICE',
            'type' => 'date',
            'massupdate' => false,
            'comment' => 'Starting date cost price is valid'
        ),
        'cost_price' => array(
            'name' => 'cost_price',
            'vname' => 'LBL_COST_PRICE',
            'type' => 'currency',
            'required' => true,
            'len' => '26,6',
            'comment' => 'Product cost ("Cost" in Quote)',
            'importable' => 'required',
            'required' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'discount_price' => array(
            'name' => 'discount_price',
            'vname' => 'LBL_DISCOUNT_PRICE',
            'required' => true,
            'type' => 'currency',
            'len' => '26,6',
            'comment' => 'Discounted price ("Unit Price" in Quote)',
            'importable' => 'required',
            'required' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'list_price' => array(
            'name' => 'list_price',
            'vname' => 'LBL_LIST_PRICE',
            'required' => true,
            'type' => 'currency',
            'len' => '26,6',
            'importable' => 'required',
            'required' => true,
            'comment' => 'List price of product ("List" in Quote)',
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'cost_usdollar' => array(
            'name' => 'cost_usdollar',
            'vname' => 'LBL_COST_USDOLLAR',
            'type' => 'currency',
            'currency_id'=> '-99',
            'len' => '26,6',
            'comment' => 'Cost expressed in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($cost_price), currencyDivide($cost_price, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'discount_usdollar' => array(
            'name' => 'discount_usdollar',
            'vname' => 'LBL_DISCOUNT_USDOLLAR',
            'type' => 'currency',
            'currency_id'=> '-99',
            'len' => '26,6',
            'comment' => 'Discount price expressed in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($discount_price), currencyDivide($discount_price, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'list_usdollar' => array(
            'name' => 'list_usdollar',
            'vname' => 'LBL_LIST_USDOLLAR',
            'type' => 'currency',
            'currency_id'=> '-99',
            'len' => '26,6',
            'comment' => 'List price expressed in USD',
            'studio' => array(
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($list_price), currencyDivide($list_price, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'product_template_status_dom',
            'len' => 100,
            'comment' => 'Product status (not used in product Catalog)',
        ),
        'tax_class' => array(
            'name' => 'tax_class',
            'vname' => 'LBL_TAX_CLASS',
            'type' => 'enum',
            'options' => 'tax_class_dom',
            'len' => 100,
            'comment' => 'Tax classification (ex: Taxable, Non-taxable)',
        ),
        'date_available' => array(
            'name' => 'date_available',
            'vname' => 'LBL_DATE_AVAILABLE',
            'type' => 'date',
            'comment' => 'Availability date',
        ),
        'website' => array(
            'name' => 'website',
            'vname' => 'LBL_URL',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Product URL',
        ),
        'weight' => array(
            'name' => 'weight',
            'vname' => 'LBL_WEIGHT',
            'type' => 'decimal',
            'len' => '12',
            'precision' => '2',
            'comment' => 'Weight of the product',
        ),
        'qty_in_stock' => array(
            'name' => 'qty_in_stock',
            'vname' => 'LBL_QUANTITY',
            'type' => 'int',
            'len' => '5',
            'comment' => 'Quantity on hand',
        ),
        'support_name' => array(
            'name' => 'support_name',
            'vname' => 'LBL_SUPPORT_NAME',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'Name of product for support purposes',
        ),
        'support_description' => array(
            'name' => 'support_description',
            'vname' => 'LBL_SUPPORT_DESCRIPTION',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Description of product for support purposes',
        ),
        'support_contact' => array(
            'name' => 'support_contact',
            'vname' => 'LBL_SUPPORT_CONTACT',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'Contact for support purposes',
        ),
        'support_term' => array(
            'name' => 'support_term',
            'vname' => 'LBL_SUPPORT_TERM',
            'type' => 'enum',
            'options' => 'support_term_dom',
            'len' => 100,
            'comment' => 'Term (length) of support contract',
        ),
        'pricing_formula' => array(
            'name' => 'pricing_formula',
            'vname' => 'LBL_PRICING_FORMULA',
            'type' => 'pricing-formula',
            'dbType' => 'enum',
            'options' => 'pricing_formula_dom',
            'len' => 100,
            'comment' => 'Pricing formula (ex: Fixed, Markup over Cost)',
            'studio' => array(
                'field' => array(
                    'options' => false,
                ),
            ),
            'related_fields' => array(
                'pricing_factor',
            ),
        ),
        'pricing_factor' => array(
            'name' => 'pricing_factor',
            'vname' => 'LBL_PRICING_FACTOR',
            'type' => 'decimal',
            'len' => '8',
            'precision' => '2',
            'comment' => 'Variable pricing factor depending on pricing_formula',
            'related_fields' => array(
                'pricing_formula',
            ),
        ),
        'category_link' => array(
            'name' => 'category_link',
            'type' => 'link',
            'relationship' => 'product_templates_product_categories',
            'vname' => 'LBL_PRODUCT_CATEGORIES',
            'link_type' => 'one',
            'module' => 'ProductCategories',
            'bean_name' => 'ProductCategory',
            'source' => 'non-db',
        ),
        'type_link' => array(
            'name' => 'type_link',
            'type' => 'link',
            'relationship' => 'product_templates_product_types',
            'vname' => 'LBL_PRODUCT_TYPES',
            'link_type' => 'one',
            'module' => 'ProductTypes',
            'bean_name' => 'ProductType',
            'source' => 'non-db',
        ),
        'manufacturer_link' => array(
            'name' => 'manufacturer_link',
            'type' => 'link',
            'relationship' => 'product_templates_manufacturers',
            'vname' => 'LBL_MANUFACTURERS',
            'link_type' => 'one',
            'module' => 'Manufacturers',
            'bean_name' => 'Manufacturer',
            'source' => 'non-db',
        ),
        'forecastworksheet' => array(
            'name' => 'forecastworksheet',
            'type' => 'link',
            'relationship' => 'forecastworksheets_templates',
            'source' => 'non-db',
            'vname' => 'LBL_FORECAST_WORKSHEET',
        ),
    ),
    'relationships' => array(
        'product_templates_product_categories' => array(
            'lhs_module' => 'ProductCategories',
            'lhs_table' => 'product_categories',
            'lhs_key' => 'id',
            'rhs_module' => 'ProductTemplates',
            'rhs_table' => 'product_templates',
            'rhs_key' => 'category_id',
            'relationship_type' => 'one-to-many'
        ),
        'product_templates_product_types' => array(
            'lhs_module'=> 'ProductTypes',
            'lhs_table'=> 'product_types',
            'lhs_key' => 'id',
            'rhs_module'=> 'ProductTemplates',
            'rhs_table'=> 'product_templates',
            'rhs_key' => 'type_id',
            'relationship_type'=>'one-to-many'
        ),
        'product_templates_manufacturers' => array(
            'lhs_module' => 'Manufacturers',
            'lhs_table'=> 'manufacturers',
            'lhs_key' => 'id',
            'rhs_module' => 'ProductTemplates',
            'rhs_table' => 'product_templates',
            'rhs_key' => 'manufacturer_id',
            'relationship_type' => 'one-to-many'
        ),
    ),
    'acls' => array('SugarACLDeveloperOrAdmin' => array('aclModule' => 'Products', 'allowUserRead' => true)),
    'indices' => array (
        array('name' => 'idx_producttemplate_status', 'type' => 'index', 'fields' => array('status')),
        array('name' => 'idx_producttemplate_qty_in_stock', 'type' => 'index', 'fields' => array('qty_in_stock')),
        array('name' => 'idx_producttemplate_category', 'type' => 'index', 'fields' => array('category_id')),
    ),
);

VardefManager::createVardef(
    'ProductTemplates',
    'ProductTemplate',
    array(
        'default',
        'assignable',
        'currency'
    )
);
