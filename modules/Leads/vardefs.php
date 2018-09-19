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

$dictionary['Lead'] = array(
    'table' => 'leads',
    'audited' => true,
    'activity_enabled' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'unified_search_default_enabled' => true,
    'duplicate_merge' => true,
    'comment' => 'Leads are persons of interest early in a sales cycle',
    'fields' => array(


        'converted' => array(
            'name' => 'converted',
            'vname' => 'LBL_CONVERTED',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Has Lead been converted to a Contact (and other Sugar objects)',
            'massupdate' => false,
            'studio' => false,
        ),
        'refered_by' => array(
            'name' => 'refered_by',
            'vname' => 'LBL_REFERED_BY',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Identifies who refered the lead',
            'merge_filter' => 'enabled',
        ),
        'lead_source' => array(
            'name' => 'lead_source',
            'vname' => 'LBL_LEAD_SOURCE',
            'type' => 'enum',
            'options' => 'lead_source_dom',
            'len' => '100',
            'audited' => true,
            'comment' => 'Lead source (ex: Web, print)',
            'merge_filter' => 'enabled',
        ),
        'lead_source_description' => array(
            'name' => 'lead_source_description',
            'vname' => 'LBL_LEAD_SOURCE_DESCRIPTION',
            'type' => 'text',
            'group' => 'lead_source',
            'comment' => 'Description of the lead source'
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => '100',
            'options' => 'lead_status_dom',
            'default' => 'New',
            'audited' => true,
            'comment' => 'Status of the lead',
            'merge_filter' => 'enabled',
            //Fixme PAT-2241 will remove this when SugarLogic is supported in preview
            'previewEdit' => false,
        ),
        'status_description' => array(
            'name' => 'status_description',
            'vname' => 'LBL_STATUS_DESCRIPTION',
            'type' => 'text',
            'group' => 'status',
            'comment' => 'Description of the status of the lead'
        ),
        'department' => array(
            'name' => 'department',
            'vname' => 'LBL_DEPARTMENT',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Department the lead belongs to',
            'merge_filter' => 'enabled',
        ),
        'reports_to_id' => array(
            'name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO_ID',
            'type' => 'id',
            'reportable' => false,
            'comment' => 'ID of Contact the Lead reports to'
        ),
        'report_to_name' => array(
            'name' => 'report_to_name',
            'rname' => 'name',
            'id_name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO',
            'type' => 'relate',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => 'id',
            'source' => 'non-db',
            'reportable' => false,
            'massupdate' => false,
        ),
        'reports_to_link' => array(
            'name' => 'reports_to_link',
            'type' => 'link',
            'relationship' => 'lead_direct_reports',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_REPORTS_TO',
            'reportable' => false
        ),
        'reportees' => array(
            'name' => 'reportees',
            'type' => 'link',
            'relationship' => 'lead_direct_reports',
            'link_type' => 'many',
            'side' => 'left',
            'source' => 'non-db',
            'vname' => 'LBL_REPORTS_TO',
            'reportable' => false
        ),
        'contacts' => array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'contact_leads',
            'module' => "Contacts",
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
            'reportable' => false
        ),
        'dataprivacy' => array(
            'name' => 'dataprivacy',
            'type' => 'link',
            'relationship' => 'leads_dataprivacy',
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
        'account_name' => array(
            'name' => 'account_name',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'varchar',
            'len' => '255',
            'unified_search' => true,
            'comment' => 'Account name for lead',
        ),
        'account_to_lead' => array(
            'name' => 'account_to_lead',
            'rname' => 'name',
            'id_name' => 'account_id',
            'type' => 'relate',
            'link' => 'accounts',
            'isnull' => 'true',
            'module' => 'Accounts',
            'source' => 'non-db',
            'unified_search' => false,
            'studio' => false,
            'massupdate' => false,
            'populate_list' => array(
                'billing_address_street' => 'primary_address_street',
                'billing_address_city' => 'primary_address_city',
                'billing_address_state' => 'primary_address_state',
                'billing_address_postalcode' => 'primary_address_postalcode',
                'billing_address_country' => 'primary_address_country',
                'phone_office' => 'phone_work',
            ),
            'importable' => 'false',
        ),
        'accounts' => array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'account_leads',
            'link_type' => 'one',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
            'duplicate_merge' => 'disabled',
        ),
        'account_description' => array(
            'name' => 'account_description',
            'vname' => 'LBL_ACCOUNT_DESCRIPTION',
            'type' => 'text',
            'group' => 'account_name',
            'unified_search' => true,
            'comment' => 'Description of lead account'
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'id',
            'reportable' => false,
            'vname' => 'LBL_CONTACT_ID',
            'comment' => 'If converted, Contact ID resulting from the conversion'
        ),
        'contact' => array(
            'name' => 'contact',
            'type' => 'link',
            'link_type' => 'one',
            'relationship' => 'contact_leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
            'reportable' => false,
            'side' => 'right',
        ),
        'contact_name' => array(
            'name' => 'contact_name',
            'rname' => 'name',
            'id_name' => 'contact_id',
            'vname' => 'LBL_CONTACT_NAME',
            'join_name' => 'contacts',
            'type' => 'relate',
            'link' => 'contacts',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            'unified_search' => false,
            'massupdate' => false,
        ),
        'account_id' => array(
            'name' => 'account_id',
            'type' => 'id',
            'reportable' => false,
            'vname' => 'LBL_ACCOUNT_ID',
            'comment' => 'If converted, Account ID resulting from the conversion'
        ),
        'opportunity_id' => array(
            'name' => 'opportunity_id',
            'type' => 'id',
            'reportable' => false,
            'vname' => 'LBL_OPPORTUNITY_ID',
            'comment' => 'If converted, Opportunity ID resulting from the conversion'
        ),
        'opportunity' => array(
            'name' => 'opportunity',
            'type' => 'link',
            'link_type' => 'one',
            'relationship' => 'opportunity_leads',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITIES',
        ),
        'opportunity_name' => array(
            'name' => 'opportunity_name',
            'vname' => 'LBL_OPPORTUNITY_NAME',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Opportunity name associated with lead'
        ),
        'opportunity_amount' => array(
            'name' => 'opportunity_amount',
            'vname' => 'LBL_OPPORTUNITY_AMOUNT',
            'type' => 'varchar',
            'group' => 'opportunity_name',
            'len' => '50',
            'comment' => 'Amount of the opportunity'
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'type' => 'id',
            'reportable' => false,
            'vname' => 'LBL_CAMPAIGN_ID',
            'comment' => 'Campaign that generated lead'
        ),
        'campaign_name' => array(
            'name' => 'campaign_name',
            'rname' => 'name',
            'id_name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_leads',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'source' => 'non-db',
            'additionalFields' => array('id' => 'campaign_id'),
            'massupdate' => false,
        ),
        'campaign_leads' => array(
            'name' => 'campaign_leads',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGN_LEAD',
            'relationship' => 'campaign_leads',
            'source' => 'non-db',
        ),
        //Deprecated: Use rname_link instead
        'c_accept_status_fields' => array(
            'name' => 'c_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'calls',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        //Deprecated: Use rname_link instead
        'm_accept_status_fields' => array(
            'name' => 'm_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'meetings',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'hideacl' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        //Deprecated: Use rname_link instead
        'accept_status_id' => array(
            'name' => 'accept_status_id',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'studio' => array('listview' => false),
        ),
        //Deprecated: Use rname_link instead
        'accept_status_name' => array(
            'massupdate' => false,
            'name' => 'accept_status_name',
            'type' => 'enum',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
        ),
        'accept_status_calls' => array(
            'massupdate' => false,
            'name' => 'accept_status_calls',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
            'link' => 'calls',
            'rname_link' => 'accept_status',
        ),
        'accept_status_meetings' => array(
            'massupdate' => false,
            'name' => 'accept_status_meetings',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
            'link' => 'meetings',
            'rname_link' => 'accept_status',
        ),
        'webtolead_email1' => array(
            'name' => 'webtolead_email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'email',
            'len' => '100',
            'source' => 'non-db',
            'comment' => 'Main email address of lead',
            'importable' => 'false',
            'studio' => 'false',
        ),
        'webtolead_email2' => array(
            'name' => 'webtolead_email2',
            'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
            'type' => 'email',
            'len' => '100',
            'source' => 'non-db',
            'comment' => 'Secondary email address of lead',
            'importable' => 'false',
            'studio' => 'false',
        ),
        'webtolead_email_opt_out' => array(
            'name' => 'webtolead_email_opt_out',
            'vname' => 'LBL_EMAIL_OPT_OUT',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Indicator signaling if lead elects to opt out of email campaigns',
            'importable' => 'false',
            'massupdate' => false,
            'studio' => 'false',
        ),
        'webtolead_invalid_email' => array(
            'name' => 'webtolead_invalid_email',
            'vname' => 'LBL_INVALID_EMAIL',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Indicator that email address for lead is invalid',
            'importable' => 'false',
            'massupdate' => false,
            'studio' => 'false',
        ),
        'birthdate' => array(
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'comment' => 'The birthdate of the contact',
            'audited' => true,
            'pii' => true,
        ),
        'portal_name' => array(
            'name' => 'portal_name',
            'vname' => 'LBL_PORTAL_NAME',
            'type' => 'varchar',
            'len' => '255',
            'group' => 'portal',
            'group_label' => 'LBL_PORTAL',
            'comment' => 'Portal user name when lead created via lead portal',
            'studio' => true,
        ),
        'portal_app' => array(
            'name' => 'portal_app',
            'vname' => 'LBL_PORTAL_APP',
            'type' => 'varchar',
            'group' => 'portal',
            'len' => '255',
            'comment' => 'Portal application that resulted in created of lead',
            'studio' => true,
        ),
        'website' => array(
            'name' => 'website',
            'vname' => 'LBL_WEBSITE',
            'type' => 'url',
            'dbType' => 'varchar',
            'len' => 255,
            'link_target' => '_blank',
            'comment' => 'URL of website for the company',
        ),
        'tasks' => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'lead_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'notes' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'lead_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'meetings' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'meetings_leads',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'meetings_parent' => array(
            'name' => 'meetings_parent',
            'type' => 'link',
            'relationship' => 'lead_meetings',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
            'reportable' => false,
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'calls_leads',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'calls_parent' => array(
            'name' => 'calls_parent',
            'type' => 'link',
            'relationship' => 'lead_calls',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
            'reportable' => false,
        ),
        'emails' => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_leads_rel',
            'source' => 'non-db',
            'unified_search' => true,
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
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'lead_campaign_log',
            'module' => 'CampaignLog',
            'bean_name' => 'CampaignLog',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGNLOG',
        ),
        'prospect_lists' => array(
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_leads',
            'module' => 'ProspectLists',
            'source' => 'non-db',
            'vname' => 'LBL_PROSPECT_LIST',
        ),
        'prospect' => array(
            'name' => 'prospect',
            'type' => 'link',
            'relationship' => 'lead_prospect',
            'module' => 'Prospects',
            'source' => 'non-db',
            'vname' => 'LBL_PROSPECT',
        ),
        'preferred_language' => array(
            'name' => 'preferred_language',
            'type' => 'enum',
            'vname' => 'LBL_PREFERRED_LANGUAGE',
            'options' => 'available_language_dom',
        ),
        // Marketo Fields
        'mkto_sync' => array(
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
        'mkto_id' => array(
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
        'mkto_lead_score' => array(
            'name' => 'mkto_lead_score',
            'vname' => 'LBL_MKTO_LEAD_SCORE',
            'comment' => null,
            'type' => 'int',
            'default_value' => null,
            'audited' => true,
            'mass_update' => false,
            'duplicate_merge' => true,
            'reportable' => true,
            'importable' => 'true',
        ),
    ),
    'indices' => array(
        array('name' => 'idx_lead_acct_name_first', 'type' => 'index', 'fields' => array('account_name', 'deleted')),
        array(
            'name' => 'idx_lead_del_stat',
            'type' => 'index',
            'fields' => array('last_name', 'status', 'deleted', 'first_name')
        ),
        array('name' => 'idx_lead_opp_del', 'type' => 'index', 'fields' => array('opportunity_id', 'deleted',)),
        array('name' => 'idx_leads_acct_del', 'type' => 'index', 'fields' => array('account_id', 'deleted',)),
        array('name' => 'idx_lead_assigned', 'type' => 'index', 'fields' => array('assigned_user_id')),
        array('name' => 'idx_lead_contact', 'type' => 'index', 'fields' => array('contact_id')),
        array('name' => 'idx_reports_to', 'type' => 'index', 'fields' => array('reports_to_id')),
        array('name' => 'idx_lead_phone_work', 'type' => 'index', 'fields' => array('phone_work')),
        array('name' => 'idx_lead_mkto_id', 'type' => 'index', 'fields' => array('mkto_id')),
    ),
    'relationships' => array(
        'lead_prospect' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Prospects',
            'rhs_table' => 'prospects',
            'rhs_key' => 'lead_id',
            'relationship_type' => 'one-to-one'
        ),
        'lead_direct_reports' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'reports_to_id',
            'relationship_type' => 'one-to-many'
        ),
        'lead_tasks' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Leads'
        ),
        'lead_notes' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Leads'
        ),
        'lead_meetings' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Leads'
        ),
        'lead_calls' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Leads'
        ),
        'lead_emails' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Leads'
        ),
        'lead_campaign_log' => array(
            'lhs_module' => 'Leads',
            'lhs_table' => 'leads',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog',
            'rhs_table' => 'campaign_log',
            'rhs_key' => 'target_id',
            'relationship_type' => 'one-to-many'
        )

    ),
    'duplicate_check' => array(
        'enabled' => true,
        'FilterDuplicateCheck' => array(
            'filter_template' => array(
                array(
                    '$and' => array(
                        array(
                            '$or' => array(
                                array('status' => array('$not_equals' => 'Converted')),
                                array('status' => array('$is_null' => '')),
                            )
                        ),
                        array(
                            '$or' => array(
                                array(
                                    '$and' => array(
                                        array('account_name' => array('$starts' => '$account_name')),
                                        array('first_name' => array('$starts' => '$first_name')),
                                        array('last_name' => array('$starts' => '$last_name')),
                                        array('dnb_principal_id' => array('$equals' => '$dnb_principal_id')),
                                    )
                                ),
                                array('phone_work' => array('$equals' => '$phone_work')),
                            )
                        ),
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
    //This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,
);

VardefManager::createVardef(
    'Leads',
    'Lead',
    array(
        'default',
        'assignable',
        'team_security',
        'person'
    )
);

//boost value for full text search
$dictionary['Lead']['fields']['first_name']['full_text_search']['boost'] = 1.87;
$dictionary['Lead']['fields']['last_name']['full_text_search']['boost'] = 1.85;
$dictionary['Lead']['fields']['email']['full_text_search']['boost'] = 1.83;
$dictionary['Lead']['fields']['phone_home']['full_text_search']['boost'] = 1.02;
$dictionary['Lead']['fields']['phone_mobile']['full_text_search']['boost'] = 1.01;
$dictionary['Lead']['fields']['phone_work']['full_text_search']['boost'] = 1.00;
$dictionary['Lead']['fields']['phone_other']['full_text_search']['boost'] = 0.99;
$dictionary['Lead']['fields']['phone_fax']['full_text_search']['boost'] = 0.98;
$dictionary['Lead']['fields']['description']['full_text_search']['boost'] = 0.70;
$dictionary['Lead']['fields']['primary_address_street']['full_text_search']['boost'] = 0.31;
$dictionary['Lead']['fields']['alt_address_street']['full_text_search']['boost'] = 0.30;
