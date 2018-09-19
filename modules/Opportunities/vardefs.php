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

$dictionary['Opportunity'] = array(
    'table' => 'opportunities',
    'audited' => true,
    'activity_enabled' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'unified_search_default_enabled' => true,
    'duplicate_merge' => true,
    'comment' => 'An opportunity is the target of selling activities',
    'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_OPPORTUNITY_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '50',
            'unified_search' => true,
            'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 1.65),
            'comment' => 'Name of the opportunity',
            'merge_filter' => 'selected',
            'importable' => 'required',
            'required' => true,
        ),
        'opportunity_type' => array(
            'name' => 'opportunity_type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'options' => 'opportunity_type_dom',
            'len' => '255',
            'audited' => true,
            'comment' => 'Type of opportunity (ex: Existing, New)',
            'merge_filter' => 'enabled',
        ),
        'account_name' => array(
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'relate',
            'table' => 'accounts',
            'join_name' => 'accounts',
            'isnull' => true,
            'module' => 'Accounts',
            'dbType' => 'varchar',
            'link' => 'accounts',
            'len' => '255',
            'source' => 'non-db',
            'unified_search' => true,
            'required' => true,
            'importable' => 'required',
            'required' => true,
            'related_field' => array(
                'account_id'
            ),
            'exportable'=> true,
            'export_link_type' => 'one',//relationship type to be used during export
        ),
        'account_id' => array(
            'name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_ID',
            'id_name' => 'account_id',
            'type' => 'relate',
            'link' => 'accounts',
            'rname' => 'id',
            'source' => 'non-db',
            'audited' => true,
            'dbType' => 'id',
            'module' => 'Accounts',
            'massupdate' => false,
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'comment' => 'Campaign that generated lead',
            'vname' => 'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'type' => 'id',
            'dbType' => 'id',
            'table' => 'campaigns',
            'isnull' => true,
            'module' => 'Campaigns',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
        ),
        'campaign_name' => array(
            'name' => 'campaign_name',
            'rname' => 'name',
            'id_name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_opportunities',
            'isnull' => true,
            'table' => 'campaigns',
            'module' => 'Campaigns',
            'source' => 'non-db',
        ),
        'campaign_opportunities' => array(
            'name' => 'campaign_opportunities',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGN_OPPORTUNITY',
            'relationship' => 'campaign_opportunities',
            'source' => 'non-db',
        ),
        'lead_source' => array(
            'name' => 'lead_source',
            'vname' => 'LBL_LEAD_SOURCE',
            'type' => 'enum',
            'options' => 'lead_source_dom',
            'len' => '50',
            'comment' => 'Source of the opportunity',
            'merge_filter' => 'enabled',
        ),
        'amount' => array(
            'name' => 'amount',
            'vname' => 'LBL_LIKELY',
            'type' => 'currency',
            'dbType' => 'currency',
            'comment' => 'Unconverted amount of the opportunity',
            'importable' => 'required',
            'duplicate_merge' => '1',
            'required' => true,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => true,
            'audited' => true,
            'validation' => array('type' => 'range', 'min' => 0),
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'amount_usdollar' => array(
            'name' => 'amount_usdollar',
            'vname' => 'LBL_AMOUNT_USDOLLAR',
            'type' => 'currency',
            'group' => 'amount',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => '0',
            'comment' => 'Formatted amount of the opportunity',
            'studio' => array(
                'wirelesslistview' => false,
                'wirelesseditview' => false,
                'wirelessdetailview' => false,
                'wireless_basic_search' => false,
                'wireless_advanced_search' => false,
                'editview' => false,
                'detailview' => false,
                'quickcreate' => false,
                'mobile' => false,
            ),
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'formula' => 'ifElse(isNumeric($amount), currencyDivide($amount, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
        ),
        'date_closed' => array(
            'name' => 'date_closed',
            'vname' => 'LBL_DATE_CLOSED',
            'type' => 'date',
            'comment' => 'Expected or actual date the oppportunity will close',
            'audited' => true,
            'importable' => 'required',
            'required' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'related_fields' => array(
                'date_closed_timestamp'
            ),
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => false,
            ),
        ),
        'date_closed_timestamp' => array(
            'name' => 'date_closed_timestamp',
            'vname' => 'LBL_DATE_CLOSED_TIMESTAMP',
            'type' => 'ulong',
            'studio' => array(
                'formula' => true,
                'related' => true,
                'recordview' => false,
                'listview' => false,
                'detailview' => false,
                'searchview' => false,
                'createview' => false,
                'editField' => false
            ),
            'reportable' => false,
            'workflow' => false,
            'massupdate' => false,
            'enforced' => true,
            'calculated' => true,
            'formula' => 'timestamp($date_closed)',
        ),
        'next_step' => array(
            'name' => 'next_step',
            'vname' => 'LBL_NEXT_STEP',
            'type' => 'varchar',
            'len' => '100',
            'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.74),
            'comment' => 'The next step in the sales process',
            'merge_filter' => 'enabled',
            'massupdate' => true,
        ),
        'sales_stage' => array(
            'name' => 'sales_stage',
            'vname' => 'LBL_SALES_STAGE',
            'type' => 'enum',
            'options' => 'sales_stage_dom',
            'default' => 'Prospecting',
            'len' => '255',
            'comment' => 'Indication of progression towards closure',
            'merge_filter' => 'enabled',
            'importable' => 'required',
            'audited' => true,
            'required' => true,
        ),
        'sales_status' => array(
            'name' => 'sales_status',
            'vname' => 'LBL_SALES_STATUS',
            'type' => 'enum',
            'options' => 'sales_status_dom',
            'len' => '255',
            'readonly' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'reportable' => false,
            'massupdate' => false,
            'importable' => false,
            'default' => 'New'
        ),
        'probability' => array(
            'name' => 'probability',
            'vname' => 'LBL_PROBABILITY',
            'type' => 'int',
            'dbType' => 'double',
            'audited' => true,
            'formula' => 'getDropdownValue("sales_probability_dom",$sales_stage)',
            'calculated' => true,
            'enforced' => true,
            'workflow' => false,
            'comment' => 'The probability of closure',
            'validation' => array('type' => 'range', 'min' => 0, 'max' => 100),
            'merge_filter' => 'enabled',
        ),
        'best_case' => array(
            'name' => 'best_case',
            'vname' => 'LBL_BEST',
            'dbType' => 'currency',
            'type' => 'currency',
            'len' => '26,6',
            'validation' => array('type' => 'range', 'min' => 0),
            'audited' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'worst_case' => array(
            'name' => 'worst_case',
            'vname' => 'LBL_WORST',
            'dbType' => 'currency',
            'type' => 'currency',
            'len' => '26,6',
            'validation' => array('type' => 'range', 'min' => 0),
            'audited' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate'
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
        ),
        'commit_stage' => array(
            'name' => 'commit_stage',
            'vname' => 'LBL_COMMIT_STAGE_FORECAST',
            'type' => 'enum',
            'len' => '50',
            'comment' => 'Forecast commit ranges: Include, Likely, Omit etc.',
            'function' => 'getCommitStageDropdown',
            'function_bean' => 'Forecasts',
            'formula' => 'forecastCommitStage($probability)',
            'calculated' => true,
            'related_fields' => array(
                'probability'
            )
        ),
        'total_revenue_line_items' => array(
            'name' => 'total_revenue_line_items',
            'vname' => 'LBL_TOTAL_RLIS',
            'type' => 'int',
            'formula' => 'count($revenuelineitems)',
            'calculated' => true,
            'enforced' => true,
            'studio' => false,
            'workflow' => false,
            'reportable' => false,
            'importable' => false
        ),
        'closed_revenue_line_items' => array(
            'name' => 'closed_revenue_line_items',
            'vname' => 'LBL_CLOSED_RLIS',
            'type' => 'int',
            'formula' => 'countConditional($revenuelineitems,"sales_stage",createList("Closed Won","Closed Lost"))',
            'calculated' => true,
            'enforced' => true,
            'studio' => false,
            'workflow' => false,
            'reportable' => false,
            'importable' => false
        ),
        'included_revenue_line_items' => array(
            'name' => 'included_revenue_line_items',
            'vname' => 'LBL_INCLUDED_RLIS',
            'type' => 'int',
            'formula' => 'countConditional($revenuelineitems,"commit_stage", forecastIncludedCommitStages())',
            'calculated' => true,
            'enforced' => true,
            'studio' => false,
            'workflow' => false,
            'reportable' => false,
            'importable' => false
        ),
        'accounts' => array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'accounts_opportunities',
            'source' => 'non-db',
            'link_type' => 'one',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'vname' => 'LBL_ACCOUNTS',
        ),
        'contacts' => array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'opportunities_contacts',
            'source' => 'non-db',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'rel_fields' => array(
                'contact_role' => array(
                    'type' => 'enum',
                    'options' => 'opportunity_relationship_type_dom'
                )
            ),
            'vname' => 'LBL_CONTACTS',
            'populate_list' => array(
                'account_id' => 'account_id',
                'account_name' => 'account_name',
            )
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'massupdate' => false,
            'vname' => 'LBL_OPPORTUNITY_ROLE',
            'options' => 'opportunity_relationship_type_dom',
            'link' => 'contacts',
            'rname_link' => 'contact_role',
        ),
        'tasks' => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'opportunity_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'notes' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'opportunity_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'opportunity_meetings',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'opportunity_calls',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'emails' => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_opportunities_rel', /* reldef in emails */
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'archived_emails' => array(
            'name' => 'archived_emails',
            'type' => 'link',
            'link_file' => 'modules/Emails/ArchivedEmailsBeanLink.php',
            'link_class' => 'ArchivedEmailsBeanLink',
            'link' => 'contacts',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
            'module' => 'Emails',
            'link_type' => 'many',
            'relationship' => '',
            'hideacl' => true,
            'readonly' => true,
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_opportunities',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        ),
        'quotes' => array(
            'name' => 'quotes',
            'type' => 'link',
            'relationship' => 'quotes_opportunities',
            'source' => 'non-db',
            'vname' => 'LBL_QUOTES',
        ),

        'project' => array(
            'name' => 'project',
            'type' => 'link',
            'relationship' => 'projects_opportunities',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTS',
        ),
        'leads' => array(
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'opportunity_leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'campaignlog_created_opportunities',
            'module' => 'CampaignLog',
            'bean_name' => 'CampaignLog',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGNS',
            'reportable' => false
        ),
        'contracts' => array(
            'name' => 'contracts',
            'type' => 'link',
            'vname' => 'LBL_CONTRACTS',
            'relationship' => 'contracts_opportunities',
            //'link_type' => 'one', bug# 31652 relationship is one to many from opportunities to contracts
            'source' => 'non-db',
            'populate_list' => array(
                'account_id' => 'account_id',
                'account_name' => 'account_name',
            )
        ),
        'revenuelineitems' => array(
            'name' => 'revenuelineitems',
            'type' => 'link',
            'vname' => 'LBL_RLI',
            'relationship' => 'opportunities_revenuelineitems',
            'source' => 'non-db',
            'workflow' => false
        ),
        'forecastworksheets' =>  array(
            'name' => 'forecastworksheets',
            'type' => 'link',
            'relationship' => 'forecastworksheets_opportunities',
            'vname' => 'LBL_FORECAST_WORKSHEET',
            'module' => 'ForecastWorksheets',
            'bean_name' => 'ForecastWorksheet',
            'source' => 'non-db',
        ),
        'products' => array(
            'name' => 'products',
            'type' => 'link',
            'vname' => 'LBL_PRODUCTS',
            'relationship' => 'opportunities_products',
            'source' => 'non-db',
        ),

        // Marketo Fields
        'mkto_sync' =>
            array(
                'name' => 'mkto_sync',
                'vname' => 'LBL_MKTO_SYNC',
                'type' => 'bool',
                'default' => '0',
                'comment' => 'Should the Lead be synced to Marketo',
                'massupdate' => true,
                'audited' => true,
                'duplicate_merge' => true,
                'reportable' => true,
                'importable' => 'true',
            ),
        'mkto_id' =>
            array(
                'name' => 'mkto_id',
                'vname' => 'LBL_MKTO_ID',
                'comment' => 'Associated Marketo Lead ID',
                'type' => 'int',
                'default' => null,
                'audited' => true,
                'mass_update' => false,
                'duplicate_merge' => true,
                'reportable' => true,
                'importable' => 'false',
            ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_opp_name',
            'type' => 'index',
            'fields' => array('name'),
        ),
        array(
            'name' => 'idx_opp_assigned_timestamp',
            'type' => 'index',
            'fields' => array('assigned_user_id', 'date_closed_timestamp', 'deleted'),
        ),
        array('name' => 'idx_opportunity_sales_status', 'type' => 'index', 'fields' => array('sales_status')),
        array('name' => 'idx_opportunity_opportunity_type', 'type' => 'index', 'fields' => array('opportunity_type')),
        array('name' => 'idx_opportunity_lead_source', 'type' => 'index', 'fields' => array('lead_source')),
        array('name' => 'idx_opportunity_next_step', 'type' => 'index', 'fields' => array('next_step')),
        array(
            'name' => 'idx_opportunity_mkto_id',
            'type' => 'index',
            'fields' => array('mkto_id')
        ),
    ),
    'relationships' => array(
        'opportunity_calls' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Opportunities'
        ),
        'opportunity_meetings' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Opportunities'
        ),
        'opportunity_tasks' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Opportunities'
        ),
        'opportunity_notes' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Opportunities'
        ),
        'opportunity_emails' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Opportunities'
        ),
        'opportunity_leads' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'opportunity_id',
            'relationship_type' => 'one-to-many'
        ),
        'opportunities_assigned_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many',
        ),
        'opportunities_modified_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'opportunities_created_by' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'
        ),
        'opportunities_revenuelineitems' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'RevenueLineItems',
            'rhs_table' => 'revenue_line_items',
            'rhs_key' => 'opportunity_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'duplicate_check' => array(
        'enabled' => true,
        'FilterDuplicateCheck' => array(
            'filter_template' => array(
                array(
                    '$and' => array(
                        array('name' => array('$starts' => '$name')),
                        array('sales_stage' => array('$not_equals' => 'Closed Lost')),
                        array('sales_stage' => array('$not_equals' => 'Closed Won')),
                        array('accounts.id' => array('$equals' => '$account_id')),
                    )
                ),
            ),
            'ranking_fields' => array(
                array('in_field_name' => 'name', 'dupe_field_name' => 'name'),
            )
        )
    ),
//This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,
);
VardefManager::createVardef(
    'Opportunities',
    'Opportunity',
    array(
        'default',
        'assignable',
        'team_security',
        'currency'
    )
);

$dictionary['Opportunity']['fields']['base_rate']['readonly'] = true;

//boost value for full text search
$dictionary['Opportunity']['fields']['description']['full_text_search']['boost'] = 0.59;
