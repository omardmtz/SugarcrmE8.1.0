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

$dictionary['Account'] = array(
    'table' => 'accounts',
    'audited' => true,
    'activity_enabled' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'unified_search_default_enabled' => true,
    'duplicate_merge' => true,
    'comment' => 'Accounts are organizations or entities that are the target of selling, support, and marketing activities, or have already purchased products or services',
    'fields' => array(
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ACCOUNT_ID',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'audited' => true,
            'comment' => 'Account ID of the parent of this account',
        ),
        'sic_code' => array(
            'name' => 'sic_code',
            'vname' => 'LBL_SIC_CODE',
            'type' => 'varchar',
            'len' => 10,
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => true,
                'boost' => 1.21,
                'type' => 'exact',
            ),
            'comment' => 'SIC code of the account',
            'merge_filter' => 'enabled',
        ),
         'duns_num' =>
          array (
            'name' => 'duns_num',
            'vname' => 'LBL_DUNS_NUM',
            'type' => 'varchar',
            'len' => 15,
             'full_text_search' => array(
                 'enabled' => true,
                 'searchable' => true,
                 'boost' => 1.23,
                 'type' => 'exact',
             ),
            'comment' => 'DUNS number of the account',
          ),
        'parent_name' => array(
            'name' => 'parent_name',
            'rname' => 'name',
            'id_name' => 'parent_id',
            'vname' => 'LBL_MEMBER_OF',
            'type' => 'relate',
            'isnull' => 'true',
            'module' => 'Accounts',
            'table' => 'accounts',
            'massupdate' => false,
            'source' => 'non-db',
            'len' => 36,
            'link' => 'member_of',
            'unified_search' => true,
            'importable' => 'true',
        ),
        'members' => array(
            'name' => 'members',
            'type' => 'link',
            'relationship' => 'member_accounts',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'source' => 'non-db',
            'vname' => 'LBL_MEMBERS',
        ),
        'member_of' => array(
            'name' => 'member_of',
            'type' => 'link',
            'relationship' => 'member_accounts',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'link_type' => 'one',
            'source' => 'non-db',
            'vname' => 'LBL_MEMBER_OF',
            'side' => 'right',
        ),
        'cases' => array(
            'name' => 'cases',
            'type' => 'link',
            'relationship' => 'account_cases',
            'module' => 'Cases',
            'bean_name' => 'aCase',
            'source' => 'non-db',
            'vname' => 'LBL_CASES',
        ),
        'tasks' => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'account_tasks',
            'module' => 'Tasks',
            'bean_name' => 'Task',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'notes' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'account_notes',
            'module' => 'Notes',
            'bean_name' => 'Note',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'account_meetings',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'account_calls',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'emails' => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_accounts_rel', /* reldef in emails */
            'module' => 'Emails',
            'bean_name' => 'Email',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
            'studio' => array("formula" => false),
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
            'relationship' => 'documents_accounts',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        ),
        'bugs' => array(
            'name' => 'bugs',
            'type' => 'link',
            'relationship' => 'accounts_bugs',
            'module' => 'Bugs',
            'bean_name' => 'Bug',
            'source' => 'non-db',
            'vname' => 'LBL_BUGS',
        ),
        'contacts' => array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'accounts_contacts',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
        ),
        'opportunities' => array(
            'name' => 'opportunities',
            'type' => 'link',
            'relationship' => 'accounts_opportunities',
            'module' => 'Opportunities',
            'bean_name' => 'Opportunity',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITY',
        ),
        'quotes' => array(
            'name' => 'quotes',
            'type' => 'link',
            'relationship' => 'quotes_billto_accounts',
            'source' => 'non-db',
            'module' => 'Quotes',
            'bean_name' => 'Quote',
            'ignore_role' => true,
            'vname' => 'LBL_QUOTES',
        ),
        'quotes_shipto' => array(
            'name' => 'quotes_shipto',
            'type' => 'link',
            'relationship' => 'quotes_shipto_accounts',
            'module' => 'Quotes',
            'bean_name' => 'Quote',
            'source' => 'non-db',
            'vname' => 'LBL_QUOTES_SHIP_TO',
        ),
        'project' => array (
             'name' => 'project',
             'type' => 'link',
             'relationship' => 'projects_accounts',
             'module'=>'Project',
             'bean_name'=>'Project',
             'source'=>'non-db',
             'vname'=>'LBL_PROJECTS',
         ),
        'leads' => array(
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'account_leads',
            'module' => 'Leads',
            'bean_name' => 'Lead',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
            'populate_list' => array(
                'name' => 'account_name',
                'phone_office' => 'phone_work',
            )
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'account_campaign_log',
            'module' => 'CampaignLog',
            'bean_name' => 'CampaignLog',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGNLOG',
            'studio' => array("formula" => false),
        ),
        'campaign_accounts' => array(
            'name' => 'campaign_accounts',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGNS',
            'relationship' => 'campaign_accounts',
            'source' => 'non-db',
        ),
        'revenuelineitems' =>  array(
            'name' => 'revenuelineitems',
            'type' => 'link',
            'relationship' => 'revenuelineitems_accounts',
            'vname' => 'LBL_REVENUELINEITEMS',
            'module' => 'RevenueLineItems',
            'bean_name' => 'RevenueLineItem',
            'source' => 'non-db',
            'workflow' => false
        ),
        'forecastworksheets' =>  array(
            'name' => 'forecastworksheets',
            'type' => 'link',
            'relationship' => 'forecastworksheets_accounts',
            'vname' => 'LBL_FORECAST_WORKSHEET',
            'module' => 'ForecastWorksheets',
            'bean_name' => 'ForecastWorksheet',
            'source' => 'non-db',
        ),
        'products' => array(
            'name' => 'products',
            'type' => 'link',
            'link_file' => 'modules/Products/AccountLink.php',
            'link_class' => 'AccountLink',
            'relationship' => 'products_accounts',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCTS',
        ),
        'contracts' => array(
            'name' => 'contracts',
            'type' => 'link',
            'relationship' => 'account_contracts',
            'source' => 'non-db',
            'vname' => 'LBL_CONTRACTS',
        ),
        'dataprivacy' => array(
            'name' => 'dataprivacy',
            'type' => 'link',
            'relationship' => 'accounts_dataprivacy',
            'source' => 'non-db',
            'vname' => 'LBL_DATAPRIVACY',
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'comment' => 'Campaign that generated Account',
            'vname' => 'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'id_name' => 'campaign_id',
            'type' => 'id',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
        ),
        'campaign_name' => array(
            'name' => 'campaign_name',
            'rname' => 'name',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'non-db',
            'table' => 'campaigns',
            'id_name' => 'campaign_id',
            'link' => 'campaign_accounts',
            'module' => 'Campaigns',
            'duplicate_merge' => 'disabled',
            'comment' => 'The first campaign name for Account (Meta-data only)',
        ),
        'prospect_lists' => array(
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_accounts',
            'module' => 'ProspectLists',
            'source' => 'non-db',
            'vname' => 'LBL_PROSPECT_LIST',
        ),
    ),
    'indices' => array(
        array('name' => 'idx_accnt_parent_id', 'type' => 'index', 'fields' => array('parent_id')),
        array('name' => 'idx_account_billing_address_city', 'type' => 'index', 'fields' => array('billing_address_city')),
        array('name' => 'idx_account_billing_address_country', 'type' => 'index', 'fields' => array('billing_address_country')),
    ),
    'relationships' => array(
        'member_accounts' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many'
        ),
        'account_cases' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'account_id',
            'relationship_type' => 'one-to-many'
        ),
        'account_tasks' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Accounts'
        ),
        'account_notes' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Accounts'
        ),
        'account_meetings' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Accounts'
        ),
        'account_calls' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Accounts'
        ),
        'account_emails' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Accounts'
        ),
        'account_leads' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'account_id',
            'relationship_type' => 'one-to-many',
        ),
        'accounts_assigned_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'accounts_modified_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'accounts_created_by' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'
        ),
        'account_campaign_log' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog',
            'rhs_table' => 'campaign_log',
            'rhs_key' => 'target_id',
            'relationship_type' => 'one-to-many'
        ),

    ),
    'duplicate_check' => array(
        'enabled' => true,
        'FilterDuplicateCheck' => array(
            'filter_template' => array(
                array(
                    '$or' => array(
                        array('name' => array('$equals' => '$name')),
                        array('duns_num' => array('$equals' => '$duns_num')),
                        array(
                            '$and' => array(
                                array('name' => array('$starts' => '$name')),
                                array(
                                    '$or' => array(
                                        array('billing_address_city' => array('$starts' => '$billing_address_city')),
                                        array('shipping_address_city' => array('$starts' => '$shipping_address_city')),
                                    )
                                ),
                            )
                        ),
                    )
                ),
            ),
            'ranking_fields' => array(
                array('in_field_name' => 'name', 'dupe_field_name' => 'name'),
                array('in_field_name' => 'billing_address_city', 'dupe_field_name' => 'billing_address_city'),
                array('in_field_name' => 'shipping_address_city', 'dupe_field_name' => 'shipping_address_city'),
            )
        )
    ),
    //This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,
    'uses' => array(
        'default',
        'assignable',
        'team_security',
        'company',
    ),
);

VardefManager::createVardef(
    'Accounts',
    'Account'
);

//jc - adding for refactor for import to not use the required_fields array
//defined in the field_arrays.php file
$dictionary['Account']['fields']['name']['importable'] = 'required';

//boost value for full text search
$dictionary['Account']['fields']['name']['full_text_search']['boost'] = 1.91;
$dictionary['Account']['fields']['email']['full_text_search']['boost'] = 1.89;
$dictionary['Account']['fields']['phone_office']['full_text_search']['boost'] = 1.05;
$dictionary['Account']['fields']['phone_fax']['full_text_search']['boost'] = 1.04;
$dictionary['Account']['fields']['phone_alternate']['full_text_search']['boost'] = 1.03;
$dictionary['Account']['fields']['description']['full_text_search']['boost'] = 0.72;
$dictionary['Account']['fields']['billing_address_street']['full_text_search']['boost'] = 0.35;
$dictionary['Account']['fields']['shipping_address_street']['full_text_search']['boost'] = 0.34;

