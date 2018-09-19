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

$dictionary['Case'] = array(
    'table' => 'cases',
    'audited' => true,
    'activity_enabled' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'unified_search_default_enabled' => true,
    'duplicate_merge' => true,
    'comment' => 'Cases are issues or problems that a customer asks a support representative to resolve',
    'fields' => array(
        'account_name' => array(
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'relate',
            'link' => 'accounts',
            'table' => 'accounts',
            'join_name' => 'accounts',
            'isnull' => 'true',
            'module' => 'Accounts',
            'dbType' => 'varchar',
            'len' => 100,
            'source' => 'non-db',
            'unified_search' => true,
            'comment' => 'The name of the account represented by the account_id field',
            'required' => true,
            'importable' => 'required',
            'exportable' => true,
            'studio' => array(
                'portalrecordview' => false,
                'portallistview' => false,
            ),
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'relate',
            'dbType' => 'id',
            'rname' => 'id',
            'module' => 'Accounts',
            'id_name' => 'account_id',
            'reportable' => false,
            'vname' => 'LBL_ACCOUNT_ID',
            'audited' => true,
            'massupdate' => false,
            'comment' => 'The account to which the case is associated',
        ),
        'source' => array(
            'name' => 'source',
            'vname' => 'LBL_SOURCE',
            'type' => 'enum',
            'options' => 'source_dom',
            'len' => 255,
            'comment' => 'An indicator of how the bug was entered (ex: via web, email, etc.)',
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'case_status_dom',
            'len' => 100,
            'audited' => true,
            'comment' => 'The status of the case',
            'merge_filter' => 'enabled',
            'sortable' => true,
        ),
        'priority' => array(
            'name' => 'priority',
            'vname' => 'LBL_PRIORITY',
            'type' => 'enum',
            'options' => 'case_priority_dom',
            'len' => 100,
            'audited' => true,
            'comment' => 'The priority of the case',
            'merge_filter' => 'enabled',
            'sortable' => true,
        ),
        'resolution' => array(
            'name' => 'resolution',
            'vname' => 'LBL_RESOLUTION',
            'type' => 'text',
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => true,
                'boost' => 0.65,
            ),
            'comment' => 'The resolution of the case',
        ),
        'portal_viewable' => array(
            'name' => 'portal_viewable',
            'vname' => 'LBL_SHOW_IN_PORTAL',
            'type' => 'bool',
            'default' => 0,
            'reportable' => false,
        ),
        'tasks' => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'case_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'notes' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'case_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'case_meetings',
            'bean_name' => 'Meeting',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'emails' => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_cases_rel',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'archived_emails' => array(
            'name' => 'archived_emails',
            'type' => 'link',
            'link_file' => 'modules/Cases/CaseEmailsLink.php',
            'link_class' => 'CaseEmailsLink',
            'link' => 'contacts',
            'module' => 'Emails',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
            'link_type' => 'many',
            'relationship' => '',
            'hideacl' => true,
            'readonly' => true,
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_cases',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'case_calls',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'bugs' => array(
            'name' => 'bugs',
            'type' => 'link',
            'relationship' => 'cases_bugs',
            'source' => 'non-db',
            'vname' => 'LBL_BUGS',
        ),
        'contacts' => array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'contacts_cases',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
        ),
        'accounts' => array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'account_cases',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
        ),
        'project' => array(
            'name' => 'project',
            'type' => 'link',
            'relationship' => 'projects_cases',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTS',
        ),
        'kbcontents' => array(
            'name' => 'kbcontents',
            'type' => 'link',
            'vname' => 'LBL_KBCONTENTS_SUBPANEL_TITLE',
            'relationship' => 'relcases_kbcontents',
            'source' => 'non-db',
            'link_type' => 'many',
            'side' => 'right',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_case_name',
            'type' => 'index',
            'fields' => array(
                'name',
            ),
        ),
        array(
            'name' => 'idx_account_id',
            'type' => 'index',
            'fields' => array(
                'account_id',
            ),
        ),
        array(
            'name' => 'idx_cases_stat_del',
            'type' => 'index',
            'fields' => array(
                'assigned_user_id',
                'status',
                'deleted',
            ),
        ),
    ),
    'relationships' => array(
        'case_calls' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Cases',
        ),
        'case_tasks' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Cases',
        ),
        'case_notes' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Cases',
        ),
        'case_meetings' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Cases',
        ),
        'case_emails' => array(
            'lhs_module' => 'Cases',
            'lhs_table' => 'cases',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Cases',
        ),
        'cases_assigned_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many',
        ),
        'cases_modified_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many',
        ),
        'cases_created_by' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Cases',
            'rhs_table' => 'cases',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'acls' => array(
        'SugarACLStatic' => true,
    ),
    'duplicate_check' => array(
        'enabled' => true,
        'FilterDuplicateCheck' => array(
            'filter_template' => array(
                array(
                    '$and' => array(
                        array(
                            'name' => array(
                                '$starts' => '$name',
                            ),
                        ),
                        array(
                            'status' => array(
                                '$not_equals' => 'Closed',
                            ),
                        ),
                        array(
                            'account_id' => array(
                                '$equals' => '$account_id',
                            ),
                        ),
                    ),
                ),
            ),
            'ranking_fields' => array(
                array(
                    'in_field_name' => 'name',
                    'dupe_field_name' => 'name',
                ),
                array(
                    'in_field_name' => 'account_id',
                    'dupe_field_name' => 'account_id',
                ),
            ),
        ),
    ),

    // This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,
);

VardefManager::createVardef('Cases', 'Case', array(
    'default',
    'assignable',
    'team_security',
    'issue',
), 'case');

//jc - adding for refactor for import to not use the required_fields array
//defined in the field_arrays.php file
$dictionary['Case']['fields']['name']['importable'] = 'required';

//boost value for full text search
$dictionary['Case']['fields']['name']['full_text_search']['boost'] = 1.53;
$dictionary['Case']['fields']['case_number']['full_text_search']['boost'] = 1.29;
$dictionary['Case']['fields']['description']['full_text_search']['boost'] = 0.66;
$dictionary['Case']['fields']['work_log']['full_text_search']['boost'] = 0.64;
