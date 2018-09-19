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
$dictionary['Note'] = array(
    'studio_enabled' => array(
        'portal' => false,
    ),
    'favorites'=>true,
    'table' => 'notes',
    'activity_enabled' => true,
	'unified_search' => true, 'full_text_search' => true, 'unified_search_default_enabled' => true,

	'comment' => 'Notes & Attachments'
                               ,'fields' => array (
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NOTE_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '255',
	'unified_search' => true,
    'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.83),
    'comment' => 'Name of the note',
    'importable' => 'required',
    'required' => true,
  ),
  'file_mime_type' =>
  array (
    'name' => 'file_mime_type',
    'vname' => 'LBL_FILE_MIME_TYPE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Attachment MIME type',
    'importable' => false,
            'duplicate_on_record_copy' => 'always',
  ),
        'file_ext' => array(
            'name' => 'file_ext',
            'vname' => 'LBL_FILE_EXTENSION',
            'type' => 'varchar',
            'len' => 100,
            'duplicate_on_record_copy' => 'always',
            'massupdate' => false,
        ),
        'file_source' => array(
            'name' => 'file_source',
            'vname' => 'LBL_FILE_SOURCE',
            'type' => 'varchar',
            'len' => '32',
            'comment' => 'The name of the module where the attachment originated',
            'importable' => false,
            'duplicate_on_record_copy' => 'always',
            'massupdate' => false,
        ),
        'file_size' => array(
            'name' => 'file_size',
            'vname' => 'LBL_FILE_SIZE',
            'type' => 'int',
            'comment' => 'Attachment File Size',
            'importable' => false,
            'default' => 0,
            'duplicate_on_record_copy' => 'always',
            'massupdate' => false,
        ),
  'file_url'=>
  array(
  	'name'=>'file_url',
    'vname' => 'LBL_FILE_URL',
    'type'=>'varchar',
   	'source'=>'non-db',
  	'reportable'=>false,
  	'comment' => 'Path to file (can be URL)',
    'importable' => false,
  	),
  'filename' =>
  array (
    'name' => 'filename',
    'vname' => 'LBL_FILENAME',
    'type' => 'file',
    'dbType' => 'varchar',
    'len' => '255',
    'reportable'=>true,
    'comment' => 'File name associated with the note (attachment)',
    'importable' => false,
            'duplicate_on_record_copy' => 'always',
  ),
    'upload_id' => array(
        'name' => 'upload_id',
        'type' => 'id',
        'required' => false,
        'reportable' => false,
        'comment' => 'File id under uploads/ dir. Set only for email attachments',
            'duplicate_on_record_copy' => 'always',
    ),
        'email_type' => array(
            'comment' => "The module of the record to which this note's file is attached (Emails or EmailTemplates)",
            'name' => 'email_type',
            'reportable' => false,
            'required' => false,
            'studio' => false,
            'massupdate' => false,
            'duplicate_on_record_copy' => 'always',
            'type' => 'varchar',
            'vname' => 'LBL_EMAIL_TYPE',
        ),
        'email_id' => array(
            'comment' => 'Email or EmailTemplate ID to which this note is attached',
            'name' => 'email_id',
            'reportable' => false,
            'required' => false,
            'studio' => false,
            'massupdate' => false,
            'duplicate_on_record_copy' => 'always',
            'type' => 'id',
            'vname' => 'LBL_EMAIL_ID',
        ),
  'parent_type'=>
  array(
  	'name'=>'parent_type',
  	'vname'=>'LBL_PARENT_TYPE',
  	'type' =>'parent_type',
    'dbType' => 'varchar',
    'group'=>'parent_name',
    'options'=> 'parent_type_display',
  	'len'=> '255',
    'studio' => array('wirelesslistview'=>false),
  	'comment' => 'Sugar module the Note is associated with'
  ),
  'parent_id'=>
  array(
  	'name'=>'parent_id',
  	'vname'=>'LBL_PARENT_ID',
  	'type'=>'id',
  	'required'=>false,
  	'reportable'=>true,
  	'comment' => 'The ID of the Sugar item specified in parent_type'
  ),
  'contact_id'=>
  array(
  	'name'=>'contact_id',
  	'vname'=>'LBL_CONTACT_ID',
  	'type'=>'id',
  	'required'=>false,
  	'reportable'=>false,
  	'comment' => 'Contact ID note is associated with'
  ),
  'portal_flag' =>
  array (
    'name' => 'portal_flag',
    'vname' => 'LBL_PORTAL_FLAG',
    'type' => 'bool',
    'default' => '0',
	'comment' => 'Portal flag indicator determines if note created via portal'
  ),
  'embed_flag' =>
  array (
    'name' => 'embed_flag',
    'vname' => 'LBL_EMBED_FLAG',
    'type' => 'bool',
    'default' => 0,
	'comment' => 'Embed flag indicator determines if note embedded in email'
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_NOTE_STATUS',
    'type' => 'text',
    'comment' => 'Full text of the note',
    'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.44),
  ),

 'parent_name'=>
 	array(
		'name'=> 'parent_name',
		'parent_type'=>'record_type_display' ,
		'type_name'=>'parent_type',
		'id_name'=>'parent_id', 'vname'=>'LBL_RELATED_TO',
		'type'=>'parent',
		'source'=>'non-db',
		'options'=> 'record_type_display_notes',
    'studio' => true,
		),

 'contact_name'=>
 	array(
		'name'=>'contact_name',
		'rname'=>'name',
		'id_name'=>'contact_id',
		'vname'=>'LBL_CONTACT_NAME',
        'table'=>'contacts',
		'type'=>'relate',
		'link'=>'contact',
		'join_name'=>'contacts',
        'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
		'isnull'=>'true',
		'module'=>'Contacts',
		'source'=>'non-db',
		),
    'contact_phone' => array(
        'name'=>'contact_phone',
        'vname' => 'LBL_PHONE',
        'type' => 'relate',
        'source'=>'non-db',
        'link' => 'contact',
        'module' => 'Contacts',
        'table' => 'contacts',
        'id_name' => 'contact_id',
        'rname' => 'phone_work',
    ),
 'contact_email'=>
    array(
        'name'=>'contact_email',
        'type'=>'varchar',
		'vname' => 'LBL_EMAIL_ADDRESS',
		'source' => 'non-db',
        'studio' => false
    ),

  'account_id' =>
  array (
    'name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'opportunity_id' =>
  array (
    'name' => 'opportunity_id',
    'vname' => 'LBL_OPPORTUNITY_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'acase_id' =>
  array (
    'name' => 'acase_id',
    'vname' => 'LBL_CASE_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'lead_id' =>
  array (
    'name' => 'lead_id',
    'vname' => 'LBL_LEAD_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'product_id' =>
  array (
    'name' => 'product_id',
    'vname' => 'LBL_PRODUCT_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'quote_id' =>
  array (
    'name' => 'quote_id',
    'vname' => 'LBL_QUOTE_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),

  'contact' =>
  array (
    'name' => 'contact',
    'type' => 'link',
    'relationship' => 'contact_notes',
    'vname' => 'LBL_LIST_CONTACT_NAME',
    'source'=>'non-db',
  ),
  'cases' =>
  array (
    'name' => 'cases',
    'type' => 'link',
    'relationship' => 'case_notes',
    'vname' => 'LBL_CASES',
    'source'=>'non-db',
  ),
  'accounts' =>
  array (
    'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'account_notes',
    'source'=>'non-db',
    'vname'=>'LBL_ACCOUNTS',
  ),
  'opportunities' =>
  array (
    'name' => 'opportunities',
    'type' => 'link',
    'relationship' => 'opportunity_notes',
    'source'=>'non-db',
    'vname'=>'LBL_OPPORTUNITIES',
  ),
  'leads' =>
  array (
    'name' => 'leads',
    'type' => 'link',
    'relationship' => 'lead_notes',
    'source'=>'non-db',
    'vname'=>'LBL_LEADS',
  ),
  'products' =>
  array (
    'name' => 'products',
    'type' => 'link',
    'relationship' => 'product_notes',
    'source'=>'non-db',
    'vname'=>'LBL_PRODUCTS',
  ),
  'revenuelineitems' =>
  array (
    'name' => 'revenuelineitems',
    'type' => 'link',
    'relationship' => 'revenuelineitem_notes',
    'source'=>'non-db',
    'vname'=>'LBL_REVENUELINEITEMS',
    'workflow' => false
  ),
  'quotes' =>
  array (
    'name' => 'quotes',
    'type' => 'link',
    'relationship' => 'quote_notes',
    'vname' => 'LBL_QUOTES',
    'source'=>'non-db',
  ),
  'contracts' =>
  array (
    'name' => 'contracts',
    'type' => 'link',
    'relationship' => 'contract_notes',
    'source' => 'non-db',
    'vname' => 'LBL_CONTRACTS',
  ),
    'prospects' => array (
      'name' => 'prospects',
      'type' => 'link',
      'relationship' => 'prospect_notes',
      'source' => 'non-db',
      'vname' => 'LBL_PROSPECTS',
    ),
  'bugs' =>
  array (
    'name' => 'bugs',
    'type' => 'link',
    'relationship' => 'bug_notes',
    'source'=>'non-db',
    'vname'=>'LBL_BUGS',
  ),
  'kbcontents' =>
    array (
      'name' => 'kbcontents',
      'type' => 'link',
      'relationship' => 'kbcontent_notes',
      'source'=>'non-db',
      'vname'=>'LBL_KBDOCUMENTS',
    ),
  'emails' =>
  array(
    'name'=> 'emails',
    'vname'=> 'LBL_EMAILS',
    'type'=> 'link',
    'relationship'=> 'emails_notes_rel',
    'source'=> 'non-db',
  ),
        'email_attachment_for' => array(
            'bean_name' => 'Email',
            'module' => 'Emails',
            'name' => 'email_attachment_for',
            'relationship' => 'emails_attachments',
            'source' => 'non-db',
            'type' => 'link',
            'vname' => 'LBL_EMAIL_ATTACHMENT_FOR',
        ),
  'projects' =>
  array (
    'name' => 'projects',
    'type' => 'link',
    'relationship' => 'projects_notes',
    'source'=>'non-db',
    'vname'=>'LBL_PROJECTS',
  ),
  'project_tasks' =>
  array (
    'name' => 'project_tasks',
    'type' => 'link',
    'relationship' => 'project_tasks_notes',
    'source'=>'non-db',
    'vname'=>'LBL_PROJECT_TASKS',
  ),
  'meetings' =>
  array (
    'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'meetings_notes',
    'source'=>'non-db',
    'vname'=>'LBL_MEETINGS',
  ),
  'calls' =>
  array (
    'name' => 'calls',
    'type' => 'link',
    'relationship' => 'calls_notes',
    'source'=>'non-db',
    'vname'=>'LBL_CALLS',
  ),
  'tasks' =>
  array (
    'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'tasks_notes',
    'source'=>'non-db',
    'vname'=>'LBL_TASKS',
  ),
  'schedulersjobs' => array(
      'name' => 'schedulersjobs',
      'type' => 'link',
      'relationship' => 'schedulersjob_notes',
      'source' => 'non-db',
      'vname' => 'LBL_SCHEDULERS_JOBS',
  ),
  'description' =>
      array (
        'name' => 'description',
        'vname' => 'LBL_DESCRIPTION',
        'type' => 'text',
        'comment' => 'Full text of the note',
        'rows' => 30,
        'cols' => 90,
        'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.44),
      ),
    'contact_parent' => array(
        'name' => 'contact_parent',
        'type' => 'link',
        'relationship' => 'contact_notes_parent',
        'source' => 'non-db',
        'reportable' => false
    ),
    ),
    'relationships'=>array(
    )
    , 'indices' => array (
       array('name' =>'idx_note_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_notes_parent', 'type'=>'index', 'fields'=>array('parent_id', 'parent_type')),
       array('name' =>'idx_note_contact', 'type'=>'index', 'fields'=>array('contact_id')),
        array(
            'name' =>'idx_note_email_id',
            'type'=>'index',
            'fields' => array('email_id'),
        ),
        array(
            'name' => 'idx_note_email_type',
            'type' => 'index',
            'fields' => array('email_type'),
        ),
        array(
            'name' => 'idx_note_email',
            'type' => 'index',
            'fields' => array(
                'email_id',
                'email_type',
            ),
        ),
    )

    //This enables optimistic locking for Saves From EditView
    ,'optimistic_locking'=>true

    ,'duplicate_check' => array(
        'enabled' => false
    ),
);

VardefManager::createVardef('Notes','Note', array('default', 'assignable',
'team_security',
));

