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
$dictionary['ForecastWorksheet'] = array(
    'table' => 'forecast_worksheets',
    'studio' => false,
    'acls' => array('SugarACLForecastWorksheets' => true),
    'fields' => array(
        'parent_id' =>
        array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ACCOUNT_ID',
            'type' => 'id',
            'group'=>'parent_name',
            'required' => false,
            'reportable' => false,
            'audited' => false,
            'comment' => 'Account ID of the parent of this account',
            'studio' => false
        ),
        'parent_type' =>
        array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'group' => 'parent_name',
            'options' => 'parent_type_display',
            'len' => '255',
            'comment' => 'Sugar module the Worksheet is associated with',
            'studio' => false
        ),
        'parent_name' =>
        array(
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_NAME',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'parent_type_display',
            'studio' => true,
            'sortable' => true,
            'related_fields' => array(
                'parent_id',
                'parent_type',
                'parent_deleted',
                'name',
            )
        ),
        'opportunity_id' =>
        array(
            'name' => 'opportunity_id',
            'vname' => 'LBL_OPPORTUNITY_ID',
            'type' => 'id',
            'audited' => false,
            'studio' => false,
            'link' => 'opportunity',
        ),
        'opportunity_name' =>
        array(
            'name' => 'opportunity_name',
            'id_name' => 'opportunity_id',
            'module' => 'Opportunities',
            'vname' => 'LBL_OPPORTUNITY_NAME',
            'type' => 'relate',
            'dbType' => 'varchar',
            'len' => '255',
            'studio' => false,
            'sortable' => true,
            'related_fields' => array(
                'opportunity_id',
            ),
            'rname' => 'name',
            'link' => 'opportunity',
            'formula' => 'related($opportunity, "name")',
            'enforced' => true,
            'calculated' => true,
        ),
        'account_name' =>
        array(
            'name' => 'account_name',
            'id_name' => 'account_id',
            'module' => 'Accounts',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'relate',
            'dbType' => 'varchar',
            'len' => '255',
            'studio' => false,
            'sortable' => true,
            'related_fields' => array(
                'account_id',
            ),
            'rname' => 'name',
            'link' => 'account',
            'formula' => 'related($account, "name")',
            'enforced' => true,
            'calculated' => true,
        ),
        'account_id' =>
        array(
            'name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_ID',
            'type' => 'id',
            'audited' => false,
            'studio' => false
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN_ID',
            'type' => 'id',
            'audited' => false,
            'studio' => false
        ),
        'campaign_name' => array(
            'name' => 'campaign_name',
            'id_name' => 'campaign_id',
            'rname' => 'name',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'dbType' => 'varchar',
            'len' => '255',
            'module' => 'Campaigns',
            'sortable' => true,
            'related_fields' => array(
                'campaign_id',
            ),
            'link' => 'campaign',
            'formula' => 'related($campaign, "name")',
            'enforced' => true,
            'calculated' => true,
        ),
        'product_template_id' => array(
            'name' => 'product_template_id',
            'vname' => 'LBL_PRODUCT_TEMPLATE_ID',
            'type' => 'id',
            'audited' => false,
            'studio' => false
        ),
        'product_template_name' => array(
            'name' => 'product_template_name',
            'id_name' => 'product_template_id',
            'rname' => 'name',
            'vname' => 'LBL_PRODUCT',
            'type' => 'relate',
            'dbType' => 'varchar',
            'len' => '255',
            'module' => 'ProductTemplates',
            'sortable' => true,
            'related_fields' => array(
                'product_template_id',
            ),
            'link' => 'template',
            'formula' => 'related($template, "name")',
            'enforced' => true,
            'calculated' => true,
        ),
        'category_id' =>  array(
            'name' => 'category_id',
            'vname' => 'LBL_CATEGORY',
            'type' => 'id',
            'required' => false,
            'reportable' => true,
        ),
        'category_name' =>  array(
            'name' => 'category_name',
            'id_name' => 'category_id',
            'rname' => 'name',
            'vname' => 'LBL_CATEGORY_NAME',
            'type' => 'relate',
            'module' => 'ProductCategories',
            'dbType' => 'varchar',
            'len' => '255',
            'sortable' => true,
            'related_fields' => array(
                'category_id'
            ),
            'link' => 'category',
            'formula' => 'related($category, "name")',
            'enforced' => true,
            'calculated' => true,
        ),
        'sales_status' => array(
            'name' => 'sales_status',
            'vname' => 'LBL_SALES_STATUS',
            'type' => 'enum',
            'options' => 'sales_status_dom',
            'len' => '255',
            'sortable' => true,
            'audited' => true,
        ),
        'likely_case' =>
        array(
            'name' => 'likely_case',
            'vname' => 'LBL_LIKELY',
            'dbType' => 'currency',
            'type' => 'currency',
            'len' => '26,6',
            'validation' => array('type' => 'range', 'min' => 0),
            'audited' => false,
            'studio' => false,
            'align' => 'right',
            'sortable' => true,
            'related_fields' => array(
                'base_rate',
                'currency_id',
                'best_case',
                'worst_case'
            ),
            'convertToBase' => true,
            'skip_preferred_conversion' => true
        ),
        'best_case' =>
        array(
            'name' => 'best_case',
            'vname' => 'LBL_BEST',
            'dbType' => 'currency',
            'type' => 'currency',
            'len' => '26,6',
            'validation' => array('type' => 'range', 'min' => 0),
            'audited' => false,
            'studio' => false,
            'align' => 'right',
            'sortable' => true,
            'related_fields' => array(
                'base_rate',
                'currency_id'
            ),
            'convertToBase' => true,
            'skip_preferred_conversion' => true
        ),
        'worst_case' =>
        array(
            'name' => 'worst_case',
            'vname' => 'LBL_WORST',
            'dbType' => 'currency',
            'type' => 'currency',
            'len' => '26,6',
            'validation' => array('type' => 'range', 'min' => 0),
            'audited' => false,
            'studio' => false,
            'align' => 'right',
            'sortable' => true,
            'related_fields' => array(
                'base_rate',
                'currency_id'
            ),
            'convertToBase' => true,
            'skip_preferred_conversion' => true
        ),
        'date_closed' =>
        array(
            'name' => 'date_closed',
            'vname' => 'LBL_DATE_CLOSED',
            'type' => 'date',
            'audited' => false,
            'comment' => 'Expected or actual date the oppportunity will close',
            'importable' => 'required',
            'required' => true,
            'enable_range_search' => true,
            'sortable' => true,
            'options' => 'date_range_search_dom',
            'studio' => false,
            'related_fields' => array(
                'date_closed_timestamp'
            )
        ),
        'date_closed_timestamp' =>
        array(
            'name' => 'date_closed_timestamp',
            'vname' => 'LBL_DATE_CLOSED_TIMESTAMP',
            'type' => 'ulong',
            'studio' => false
        ),
        'sales_stage' =>
        array(
            'name' => 'sales_stage',
            'vname' => 'LBL_SALES_STAGE',
            'type' => 'enum',
            'options' => 'sales_stage_dom',
            'len' => '255',
            'audited' => false,
            'comment' => 'Indication of progression towards closure',
            'merge_filter' => 'enabled',
            'importable' => 'required',
            'sortable' => true,
            'required' => true,
            'studio' => false,
            'related_fields' => array(
                'probability'
            )
        ),
        'probability' =>
        array(
            'name' => 'probability',
            'vname' => 'LBL_OW_PROBABILITY',
            'type' => 'int',
            'dbType' => 'double',
            'audited' => false,
            'comment' => 'The probability of closure',
            'validation' => array('type' => 'range', 'min' => 0, 'max' => 100),
            'merge_filter' => 'enabled',
            'sortable' => true,
            'studio' => false,
            'formula' => 'getDropdownValue("sales_probability_dom",$sales_stage)',
            'calculated' => true,
            'enforced' => true,
            'related_fields' => array(
                'sales_stage'
            )
        ),
        'commit_stage' =>
        array(
            'name' => 'commit_stage',
            'vname' => 'LBL_FORECAST',
            'type' => 'enum',
            'len' => '50',
            'comment' => 'Forecast commit ranges: Include, Likely, Omit etc.',
            'sortable' => true,
            'studio' => false,
            'function' => 'getCommitStageDropdown',
            'function_bean' => 'Forecasts',
            'formula' => 'forecastCommitStage($probability)',
            'calculated' => true,
            'related_fields' => array(
                'probability'
            )
        ),
        'draft' =>
        array(
            'name' => 'draft',
            'vname' => 'LBL_DRAFT',
            'default' => 0,
            'type' => 'int',
            'comment' => 'Is A Draft Version',
            'studio' => false
        ),
        'next_step' => array(
            'name' => 'next_step',
            'vname' => 'LBL_NEXT_STEP',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'The next step in the sales process',
            'merge_filter' => 'enabled',
        ),
        'lead_source' => array(
            'name' => 'lead_source',
            'vname' => 'LBL_LEAD_SOURCE',
            'type' => 'enum',
            'options' => 'lead_source_dom',
            'len' => '50',
            'comment' => 'Source of the product',
            'sortable' => true,
            'merge_filter' => 'enabled',
        ),
        'product_type' => array(
            'name' => 'product_type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'options' => 'opportunity_type_dom',
            'len' => '255',
            'audited' => true,
            'comment' => 'Type of product ( from opportunities opportunity_type ex: Existing, New)',
            'merge_filter' => 'enabled',
        ),
        'list_price' =>  array(
            'name' => 'list_price',
            'vname' => 'LBL_LIST_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'audited' => true,
            'sortable' => true,
            'align' => 'right',
            'comment' => 'List price of product ("List" in Quote)'
        ),
        'cost_price' =>  array(
            'name' => 'cost_price',
            'vname' => 'LBL_COST_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'audited' => true,
            'sortable' => true,
            'align' => 'right',
            'comment' => 'Product cost ("Cost" in Quote)'
        ),
        'discount_price' =>  array(
            'name' => 'discount_price',
            'vname' => 'LBL_DISCOUNT_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'audited' => true,
            'sortable' => true,
            'align' => 'right',
            'comment' => 'Discounted price ("Unit Price" in Quote)'
        ),
        'discount_amount' =>  array(
            'name' => 'discount_amount',
            'vname' => 'LBL_TOTAL_DISCOUNT_AMOUNT',
            'type' => 'currency',
            'options' => 'discount_amount_class_dom',
            'len' => '26,6',
            'precision' => 6,
            'sortable' => true,
            'align' => 'right',
            'comment' => 'Discounted amount'
        ),
        'quantity' =>  array(
            'name' => 'quantity',
            'vname' => 'LBL_QUANTITY',
            'type' => 'int',
            'len' => 5,
            'comment' => 'Quantity in use',
            'sortable' => true,
            'align' => 'right',
            'default' => 1
        ),
        'total_amount' => array(
            'name' => 'total_amount',
            'vname' => 'LBL_CALCULATED_LINE_ITEM_AMOUNT',
            'reportable' => false,
            'sortable' => true,
            'align' => 'right',
            'type' => 'currency'
        ),
        'parent_deleted' =>
        array(
            'name' => 'parent_deleted',
            'default' => 0,
            'type' => 'int',
            'comment' => 'Is Parent Deleted',
            'studio' => false,
            'source' => 'non-db'
        ),
        'opportunity' =>
        array(
            'name' => 'opportunity',
            'type' => 'link',
            'relationship' => 'forecastworksheets_opportunities',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITY',
        ),
        'account' =>
        array(
            'name' => 'account',
            'type' => 'link',
            'relationship' => 'forecastworksheets_accounts',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNTS',
        ),
        'campaign' =>
        array(
            'name' => 'campaign',
            'type' => 'link',
            'relationship' => 'forecastworksheets_campaigns',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGN',
        ),
        'template' =>
        array(
            'name' => 'template',
            'type' => 'link',
            'relationship' => 'forecastworksheets_templates',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCT',
        ),
        'category' =>
        array(
            'name' => 'category',
            'type' => 'link',
            'relationship' => 'forecastworksheets_categories',
            'source' => 'non-db',
            'vname' => 'LBL_CATEGORY',
        ),
    ),
    'indices' => array(
        array('name' => 'idx_worksheets_parent', 'type' => 'index', 'fields' => array('parent_id', 'parent_type')),
        array(
            'name' => 'idx_worksheets_assigned_del_time_draft_parent_type',
            'type' => 'index',
            'fields' => array('deleted','assigned_user_id', 'draft', 'date_closed_timestamp', 'parent_type')
        ),
        array('name' => 'idx_forecastworksheet_commit_stage', 'type' => 'index', 'fields' => array('commit_stage')),
        array('name' => 'idx_forecastworksheet_sales_stage', 'type' => 'index', 'fields' => array('sales_stage')),
    ),
    'relationships' => array(
        'forecastworksheets_accounts' =>  array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'ForecastWorksheets',
            'rhs_table' => 'forecast_worksheets',
            'rhs_key' => 'account_id',
            'relationship_type' => 'one-to-many'
        ),
        'forecastworksheets_opportunities' =>  array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'ForecastWorksheets',
            'rhs_table' => 'forecast_worksheets',
            'rhs_key' => 'opportunity_id',
            'relationship_type' => 'one-to-many'
        ),
        'forecastworksheets_campaigns' =>  array(
            'lhs_module' => 'Campaigns',
            'lhs_table' => 'campaigns',
            'lhs_key' => 'id',
            'rhs_module' => 'ForecastWorksheets',
            'rhs_table' => 'forecast_worksheets',
            'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'
        ),
        'forecastworksheets_templates' =>  array(
            'lhs_module' => 'ProductTemplates',
            'lhs_table' => 'product_templates',
            'lhs_key' => 'id',
            'rhs_module' => 'ForecastWorksheets',
            'rhs_table' => 'forecast_worksheets',
            'rhs_key' => 'product_template_id',
            'relationship_type' => 'one-to-many'
        ),
        'forecastworksheets_categories' =>  array(
            'lhs_module' => 'ProductCategories',
            'lhs_table' => 'product_categories',
            'lhs_key' => 'id',
            'rhs_module' => 'ForecastWorksheets',
            'rhs_table' => 'forecast_worksheets',
            'rhs_key' => 'category_id',
            'relationship_type' => 'one-to-many'
        )
    ),
    // @TODO Fix the Default and Basic SugarObject templates so that Basic
    // implements Default. This would allow the application of various
    // implementations on Basic without forcing Default to have those so that
    // situations like this - implementing taggable - doesn't have to apply to
    // EVERYTHING. Since there is no distinction between basic and default for
    // sugar objects templates yet, we need to forecefully remove the taggable
    // implementation fields. Once there is a separation of default and basic
    // templates we can safely remove these as this module will implement
    // default instead of basic.
    'ignore_templates' => array(
        'taggable',
    ),
);

VardefManager::createVardef(
    'ForecastWorksheets',
    'ForecastWorksheet',
    array(
        'default',
        'assignable',
        'team_security',
        'currency'
    )
);
