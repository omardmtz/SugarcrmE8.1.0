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
$dictionary['Document'] = array('table' => 'documents',
    'unified_search' => true,
    'full_text_search' => true,
    'unified_search_default_enabled' => true,
    'fields' => array(

        'document_name' =>
        array(
            'name' => 'document_name',
            'vname' => 'LBL_DOCUMENT_NAME',
            'type' => 'varchar',
            'len' => '255',
            'required' => true,
            'importable' => 'required',
            'unified_search' => true,
            'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.82),
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'source' => 'non-db',
            'type' => 'varchar',
            'fields' => array('document_name'),
            'sort_on' => 'name',
        ),
        'doc_id' =>
        array(
            'name' => 'doc_id',
            'vname' => 'LBL_DOC_ID',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Document ID from documents web server provider',
            'importable' => false,
            'studio' => 'false',
        ),
        'doc_type' =>
        array(
            'name' => 'doc_type',
            'vname' => 'LBL_DOC_TYPE',
            'type' => 'enum',
            'function' => 'getDocumentsExternalApiDropDown',
            'len' => '100',
            'comment' => 'Document type (ex: Google, box.net, IBM SmartCloud)',
            'popupHelp' => 'LBL_DOC_TYPE_POPUP',
            'massupdate' => false,
            'options' => 'eapm_list',
            'default' => 'Sugar',
            'studio' => array('wirelesseditview' => false, 'wirelessdetailview' => false, 'wirelesslistview' => false, 'wireless_basic_search' => false),
        ),
        'doc_url' =>
        array(
            'name' => 'doc_url',
            'vname' => 'LBL_DOC_URL',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Document URL from documents web server provider',
            'importable' => false,
            'massupdate' => false,
            'studio' => 'false',
        ),
        'filename' =>
        array(
            'name' => 'filename',
            'vname' => 'LBL_FILENAME',
            'type' => 'file',
            'source' => 'non-db',
            'comment' => 'The filename of the document attachment',
            'required' => true,
            'noChange' => true,
            'allowEapm' => true,
            'fileId' => 'document_revision_id',
            'docType' => 'doc_type',
            'docUrl' => 'doc_url',
            'docId' => 'doc_id',
            'sort_on' => array('document_name'),
        ),

        'active_date' =>
        array(
            'name' => 'active_date',
            'vname' => 'LBL_DOC_ACTIVE_DATE',
            'type' => 'date',
            'importable' => 'required',
            'required' => true,
            'display_default' => 'now',
        ),

        'exp_date' =>
        array(
            'name' => 'exp_date',
            'vname' => 'LBL_DOC_EXP_DATE',
            'type' => 'date',
        ),

        'category_id' =>
        array(
            'name' => 'category_id',
            'vname' => 'LBL_SF_CATEGORY',
            'type' => 'enum',
            'len' => 100,
            'options' => 'document_category_dom',
            'reportable' => true,
        ),

        'subcategory_id' =>
        array(
            'name' => 'subcategory_id',
            'vname' => 'LBL_SF_SUBCATEGORY',
            'type' => 'enum',
            'len' => 100,
            'options' => 'document_subcategory_dom',
            'reportable' => true,
        ),

        'status_id' =>
        array(
            'name' => 'status_id',
            'vname' => 'LBL_DOC_STATUS',
            'type' => 'enum',
            'len' => 100,
            'options' => 'document_status_dom',
            'reportable' => false,
        ),

        'status' =>
        array(
            'name' => 'status',
            'vname' => 'LBL_DOC_STATUS',
            'type' => 'varchar',
            'source' => 'non-db',
            'comment' => 'Document status for Meta-Data framework',
            'studio' => 'false',
        ),

        'document_revision_id' =>
        array(
            'name' => 'document_revision_id',
            'vname' => 'LBL_DOCUMENT_REVISION_ID',
            'type' => 'id',
            'len' => '36',
            'reportable' => false,
        ),

        'revisions' =>
        array(
            'name' => 'revisions',
            'type' => 'link',
            'relationship' => 'document_revisions',
            'source' => 'non-db',
            'vname' => 'LBL_REVISIONS',
        ),

        'latest_document_revision_link' =>
        array(
            'name' => 'latest_document_revision_link',
            'type' => 'link',
            'relationship' => 'latest_document_revision',
            'source' => 'non-db',
            'vname' => 'LBL_LATEST_REVISION',
        ),

        'revision' =>
        array(
            'name' => 'revision',
            'vname' => 'LBL_DOC_VERSION',
            'type' => 'varchar',
            'reportable' => false,
            'required' => true,
            'source' => 'non-db',
            'importable' => 'required',
            'required' => true,
            'default' => '1',
        ),

        'last_rev_created_name' =>
        array(
            'name' => 'last_rev_created_name',
            'vname' => 'LBL_LAST_REV_CREATOR',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'last_rev_mime_type' =>
        array(
            'name' => 'last_rev_mime_type',
            'vname' => 'LBL_LAST_REV_MIME_TYPE',
            'type' => 'varchar',
            'reportable' => false,
            'studio' => 'false',
            'source' => 'non-db'
        ),
        'latest_revision' =>
        array(
            'name' => 'latest_revision',
            'vname' => 'LBL_LATEST_REVISION',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'latest_revision_file_mime_type' => array(
            'name' => 'latest_revision_file_mime_type',
            'type' => 'relate',
            'link' => 'latest_document_revision_link',
            'id_name' => 'document_revision_id',
            'module' => 'DocumentRevisions',
            'rname' => 'file_mime_type',
            'source' => 'non-db',
            'vname' => 'LBL_MIME',
        ),
        'latest_revision_file_size' => array(
            'name' => 'latest_revision_file_size',
            'type' => 'relate',
            'link' => 'latest_document_revision_link',
            'id_name' => 'document_revision_id',
            'module' => 'DocumentRevisions',
            'rname' => 'file_size',
            'source' => 'non-db',
            'vname' => 'LBL_FILE_SIZE',
        ),
        'latest_revision_file_ext' => array(
            'name' => 'latest_revision_file_ext',
            'type' => 'relate',
            'link' => 'latest_document_revision_link',
            'id_name' => 'document_revision_id',
            'module' => 'DocumentRevisions',
            'rname' => 'file_ext',
            'source' => 'non-db',
            'vname' => 'LBL_FILE_EXTENSION',
        ),
        'last_rev_create_date' =>
        array(
            'name' => 'last_rev_create_date',
            'type' => 'relate',
            'table' => 'document_revisions',
            'link' => 'latest_document_revision_link',
            'id_name' => 'document_revision_id',
            'join_name' => 'document_revisions',
            'vname' => 'LBL_LAST_REV_CREATE_DATE',
            'rname' => 'date_entered',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'contracts' => array(
            'name' => 'contracts',
            'type' => 'link',
            'relationship' => 'contracts_documents',
            'source' => 'non-db',
            'vname' => 'LBL_CONTRACTS',
        ),
        'contracttypes' => array(
            'name' => 'contracttypes',
            'type' => 'link',
            'relationship' => 'contracttype_documents',
            'source' => 'non-db',
            'vname' => 'LBL_CONTRACTTYPES',
        ),
        // Links around the world
        'accounts' =>
        array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'documents_accounts',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
        ),
        'contacts' =>
        array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'documents_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS_SUBPANEL_TITLE',
        ),
        'opportunities' =>
        array(
            'name' => 'opportunities',
            'type' => 'link',
            'relationship' => 'documents_opportunities',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
        ),
        'cases' =>
        array(
            'name' => 'cases',
            'type' => 'link',
            'relationship' => 'documents_cases',
            'source' => 'non-db',
            'vname' => 'LBL_CASES_SUBPANEL_TITLE',
        ),
        'bugs' =>
        array(
            'name' => 'bugs',
            'type' => 'link',
            'relationship' => 'documents_bugs',
            'source' => 'non-db',
            'vname' => 'LBL_BUGS_SUBPANEL_TITLE',
        ),
        'quotes' =>
        array(
            'name' => 'quotes',
            'type' => 'link',
            'relationship' => 'documents_quotes',
            'source' => 'non-db',
            'vname' => 'LBL_QUOTES_SUBPANEL_TITLE',
        ),
        'products' =>
        array(
            'name' => 'products',
            'type' => 'link',
            'relationship' => 'documents_products',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
        ),
        'revenuelineitems' =>
        array(
            'name' => 'revenuelineitems',
            'type' => 'link',
            'relationship' => 'documents_revenuelineitems',
            'source' => 'non-db',
            'vname' => 'LBL_RLI_SUBPANEL_TITLE',
            'workflow' => false
        ),
        'related_doc_id' =>
        array(
            'name' => 'related_doc_id',
            'vname' => 'LBL_RELATED_DOCUMENT_ID',
            'reportable' => false,
            'dbType' => 'id',
            'type' => 'id',
            'len' => '36',
        ),

        'related_doc_name' =>
        array(
            'name' => 'related_doc_name',
            'vname' => 'LBL_DET_RELATED_DOCUMENT',
            'type' => 'relate',
            'table' => 'documents',
            'link' => 'related_docs',
            'id_name' => 'related_doc_id',
            'rname' => 'document_name',
            'module' => 'Documents',
            'source' => 'non-db',
            'comment' => 'The related document name for Meta-Data framework',
        ),

        'related_doc_rev_id' =>
        array(
            'name' => 'related_doc_rev_id',
            'type' => 'relate',
            'link' => 'related_docs',
            'rname' => 'document_revision_id',
            'id_name' => 'related_doc_id',
            'vname' => 'LBL_RELATED_DOCUMENT_REVISION_ID',
            'reportable' => false,
            'dbType' => 'id',
            'type' => 'id',
            'len' => '36',
        ),

        'related_doc_rev_number' =>
        array(
            'name' => 'related_doc_rev_number',
            'type' => 'relate',
            'link' => 'revisions',
            'rname' => 'revision',
            'id_name' => 'related_doc_rev_id',
            'table' => 'document_revisions',
            'join_name' => 'document_revisions',
            'vname' => 'LBL_DET_RELATED_DOCUMENT_VERSION',
            'source' => 'non-db',
            'comment' => 'The related document version number for Meta-Data framework',
            'module' => 'DocumentRevisions', // If the module is not set, sidecar should look at the link to determine the module. This is just temp solution.
        ),

        'is_template' =>
        array(
            'name' => 'is_template',
            'vname' => 'LBL_IS_TEMPLATE',
            'type' => 'bool',
            'default' => 0,
            'reportable' => false,
        ),
        'template_type' =>
        array(
            'name' => 'template_type',
            'vname' => 'LBL_TEMPLATE_TYPE',
            'type' => 'enum',
            'len' => 100,
            'options' => 'document_template_type_dom',
            'reportable' => false,
        ),
//BEGIN field used for contract document subpanel.
        'latest_revision_name' =>
        array(
            'name' => 'latest_revision_name',
            'vname' => 'LBL_LASTEST_REVISION_NAME',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),

        'selected_revision_name' =>
        array(
            'name' => 'selected_revision_name',
            'vname' => 'LBL_SELECTED_REVISION_NAME',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'contract_status' =>
        array(
            'name' => 'contract_status',
            'vname' => 'LBL_CONTRACT_STATUS',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'contract_name' =>
        array(
            'name' => 'contract_name',
            'vname' => 'LBL_CONTRACT_NAME',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'linked_id' =>
        array(
            'name' => 'linked_id',
            'vname' => 'LBL_LINKED_ID',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'selected_revision_id' =>
        array(
            'name' => 'selected_revision_id',
            'vname' => 'LBL_SELECTED_REVISION_ID',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'latest_revision_id' =>
        array(
            'name' => 'latest_revision_id',
            'vname' => 'LBL_LATEST_REVISION_ID',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'selected_revision_filename' =>
        array(
            'name' => 'selected_revision_filename',
            'vname' => 'LBL_SELECTED_REVISION_FILENAME',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'non-db'
        ),
        'related_docs' => array(
            'name' => 'related_docs',
            'type' => 'link',
            'relationship' => 'related_documents',
            'source' => 'non-db',
            'vname' => 'LBL_DET_RELATED_DOCUMENT',
        ),
//END fields used for contract documents subpanel.

    ),
    'indices' => array(
        array('name' => 'idx_doc_cat', 'type' => 'index', 'fields' => array('category_id', 'subcategory_id')),
        array('name' => 'idx_document_doc_type', 'type' => 'index', 'fields' => array('doc_type')),
        array('name' => 'idx_document_exp_date', 'type' => 'index', 'fields' => array('exp_date')),
        array('name' => 'idx_documents_related_doc_id_deleted', 'type' => 'index', 'fields' => array(
            'related_doc_id',
            'deleted',
        )),
    ),
    'relationships' => array(
        'related_documents' => array('lhs_module' => 'Documents', 'lhs_table' => 'documents', 'lhs_key' => 'id',
            'rhs_module' => 'Documents', 'rhs_table' => 'documents', 'rhs_key' => 'related_doc_id',
            'relationship_type' => 'one-to-many')
    ,  'document_revisions' => array('lhs_module' => 'Documents', 'lhs_table' => 'documents', 'lhs_key' => 'id',
            'rhs_module' => 'DocumentRevisions', 'rhs_table' => 'document_revisions', 'rhs_key' => 'document_id',
            'relationship_type' => 'one-to-many')
    ,  'latest_document_revision' => array(
            'lhs_module' => 'Documents', 'lhs_table' => 'documents', 'lhs_key' => 'document_revision_id',
            'rhs_module' => 'DocumentRevisions', 'rhs_table' => 'document_revisions', 'rhs_key' => 'id',
            'relationship_type' => 'one-to-one'
        )

    , 'documents_modified_user' =>
        array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Documents', 'rhs_table' => 'documents', 'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many')

    , 'documents_created_by' =>
        array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Documents', 'rhs_table' => 'documents', 'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many')
    ),

);
VardefManager::createVardef('Documents', 'Document', array('default', 'assignable',
    'team_security',
));

//boost value for full text search
$dictionary['Document']['fields']['description']['full_text_search']['boost'] = 0.61;

