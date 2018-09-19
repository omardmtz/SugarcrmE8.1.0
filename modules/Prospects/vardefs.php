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

$dictionary['Prospect'] = array(
    'table' => 'prospects',
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'fields' => array(
        'tracker_key' => array(
            'name' => 'tracker_key',
            'vname' => 'LBL_TRACKER_KEY',
            'type' => 'int',
            'len' => '11',
            'required' => true,
            'auto_increment' => true,
            'readonly' => true,
            'importable' => 'false',
            'studio' => array('editview' => false),
        ),
        'birthdate' => array(
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'audited' => true,
            'pii' => true,
        ),
        'do_not_call' => array(
            'name' => 'do_not_call',
            'vname' => 'LBL_DO_NOT_CALL',
            'type' => 'bool',
            'default' => '0',
        ),
        'lead_id' => array(
            'name' => 'lead_id',
            'type' => 'id',
            'reportable' => false,
            'vname' => 'LBL_LEAD_ID',
        ),
        'account_name' => array(
            'name' => 'account_name',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'varchar',
            'len' => '150',
        ),
        'campaign_id' => array(
        'name' => 'campaign_id',
            'comment' => 'Campaign that generated lead',
            'vname' => 'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'id_name' => 'campaign_id',
            'type' => 'id',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            //'dbType' => 'char',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
        ),
        'email_addresses' => array(
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => 'prospects_email_addresses',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable' => false,
            'rel_fields' => array('primary_address' => array('type' => 'bool')),
        ),
        'email_addresses_primary' => array(
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => 'prospects_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge' => 'disabled',
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'prospect_campaign_log',
            'module' => 'CampaignLog',
            'bean_name' => 'CampaignLog',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGNLOG',
        ),
        'prospect_lists' => array(
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_prospects',
            'module' => 'ProspectLists',
            'source' => 'non-db',
            'vname' => 'LBL_PROSPECT_LIST',
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'prospect_calls',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
            'module' => 'Calls',
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'prospect_meetings',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
            'module' => 'Meetings',
        ),
        'notes' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'prospect_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'dataprivacy' => array(
            'name' => 'dataprivacy',
            'type' => 'link',
            'relationship' => 'prospects_dataprivacy',
            'source' => 'non-db',
            'vname' => 'LBL_DATAPRIVACY',
        ),
        'dp_business_purpose' => array (
            'name' => 'dp_business_purpose',
            'vname' => 'LBL_DATAPRIVACY_BUSINESS_PURPOSE',
            'type' => 'multienum',
            'isMultiSelect' => true,
            'audited' => true,
            'options' => 'dataprivacy_business_purpose_dom',
            'default' => '',
            'len' => 255,
            'comment' => 'Business purposes consented for',
        ),
        'dp_consent_last_updated' => array(
            'name' => 'dp_consent_last_updated',
            'vname' => 'LBL_DATAPRIVACY_CONSENT_LAST_UPDATED',
            'type' => 'date',
            'display_default' => 'now',
            'audited' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'comment' => 'Date consent last updated',
        ),
        //d&b principal id, a unique id assigned to a contact by D&B API
        //this contact is used for dupe check
        'dnb_principal_id' => array (
            'name' => 'dnb_principal_id',
            'vname' => 'LBL_DNB_PRINCIPAL_ID',
            'type' => 'varchar',
            'len' => 30,
            'comment' => 'Unique Id For D&B Contact',
        ),
        'tasks' => array (
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'prospect_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'emails' => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_prospects_rel',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'archived_emails' => array(
            'name' => 'archived_emails',
            'type' => 'link',
            'link_file' => 'modules/Emails/ArchivedEmailsLink.php',
            'link_class' => 'ArchivedEmailsLink',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
            'module' => 'Emails',
            'link_type' => 'many',
            'relationship' => '',
            'hideacl' => true,
            'readonly' => true,
        ),
        'lead' => array(
            'name' => 'lead',
            'type' => 'link',
            'relationship' => 'lead_prospect',
            'module' => 'Leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEAD',
        ),
        'assistant' => array(
            'name' => 'assistant',
            'type' => 'varchar',
            'len' => 75,
            'vname' => 'LBL_ASSISTANT',
        ),
        'assistant_phone' => array(
            'name' => 'assistant_phone',
            'type' => 'varchar',
            'len' => 100,
            'vname' => 'LBL_ASSISTANT_PHONE',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prospect_auto_tracker_key',
            'type' => 'index',
            'fields' => array('tracker_key')
        ),
        array(
            'name' => 'idx_prospecs_del_last',
            'type' => 'index',
            'fields' => array(
                'last_name',
                'deleted'
            )
        ),
        array('name' => 'idx_prospects_assigned', 'type' => 'index', 'fields' => array('assigned_user_id')),
        array('name' => 'idx_prospect_title', 'type' => 'index', 'fields' => array('title')),
    ),
    'relationships' => array(
        'prospect_tasks' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'
        ),
        'prospect_notes' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'
        ),
        'prospect_meetings' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'
        ),
        'prospect_calls' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'
        ),
        'prospect_emails' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'
        ),
        'prospect_campaign_log' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
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
                        array(
                            '$and' => array(
                                array('first_name' => array('$starts' => '$first_name')),
                                array('last_name' => array('$starts' => '$last_name')),
                                array('account_name' => array('$starts' => '$account_name')),
                                array('dnb_principal_id' => array('$equals' => '$dnb_principal_id')),
                            )
                        ),
                        array('phone_work' => array('$equals' => '$phone_work'))
                    )
                )
            ),
            'ranking_fields' => array(
                array('in_field_name' => 'phone_work', 'dupe_field_name' => 'phone_work'),
                array('in_field_name' => 'account_name', 'dupe_field_name' => 'account_name'),
                array('in_field_name' => 'last_name', 'dupe_field_name' => 'last_name'),
                array('in_field_name' => 'first_name', 'dupe_field_name' => 'first_name'),
            )
        )
    ),
);
VardefManager::createVardef(
    'Prospects',
    'Prospect',
    array(
        'default',
        'assignable',
        'team_security',
        'person'
    )
);

//boost value for full text search
$dictionary['Prospect']['fields']['first_name']['full_text_search']['boost'] = 1.37;
$dictionary['Prospect']['fields']['last_name']['full_text_search']['boost'] = 1.36;
$dictionary['Prospect']['fields']['email']['full_text_search']['boost'] = 1.35;
$dictionary['Prospect']['fields']['phone_home']['full_text_search']['boost'] = 0.89;
$dictionary['Prospect']['fields']['phone_mobile']['full_text_search']['boost'] = 0.88;
$dictionary['Prospect']['fields']['phone_work']['full_text_search']['boost'] = 0.87;
$dictionary['Prospect']['fields']['phone_other']['full_text_search']['boost'] = 0.86;
$dictionary['Prospect']['fields']['phone_fax']['full_text_search']['boost'] = 0.85;
$dictionary['Prospect']['fields']['description']['full_text_search']['boost'] = 0.43;
$dictionary['Prospect']['fields']['primary_address_street']['full_text_search']['boost'] = 0.22;
$dictionary['Prospect']['fields']['alt_address_street']['full_text_search']['boost'] = 0.21;


