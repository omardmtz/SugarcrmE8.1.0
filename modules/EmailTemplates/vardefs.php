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
$dictionary['EmailTemplate'] = array(
    'table' => 'email_templates',

    'favorites' => false,
    'comment' => 'Templates used in email processing',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
			'comment' => 'Unique identifier'
		),
		'date_entered' => array(
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required'=>true,
			'comment' => 'Date record created'
		),
		'date_modified' => array(
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required'=>true,
			'comment' => 'Date record last modified'
		),
		'modified_user_id' => array(
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'reportable'=>true,
			'isnull' => 'false',
			'dbType' => 'id',
			'comment' => 'User who last modified record'
		),
        'modified_by_name' => array(
            'name' => 'modified_by_name',
            'vname' => 'LBL_MODIFIED',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'non-db',
            'rname' => 'full_name',
            'table' => 'users',
            'id_name' => 'modified_user_id',
            'module' => 'Users',
            'link' => 'modified_user_link',
            'duplicate_merge' => 'disabled',
            'massupdate' => false,
            'duplicate_on_record_copy' => 'no',
            'readonly' => true,
            'sort_on' => array('last_name'),
        ),
        'modified_user_link' => array(
            'name' => 'modified_user_link',
            'type' => 'link',
            'relationship' => 'emailtemplates_modified_user',
            'vname' => 'LBL_MODIFIED_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'created_by' => array(
			'name' => 'created_by',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'id',
			'len'=> '36',
			'comment' => 'User who created record'
		),
        'created_by_name' => array(
            'name' => 'created_by_name',
            'vname' => 'LBL_CREATED',
            'type' => 'relate',
            'reportable' => false,
            'link' => 'created_by_link',
            'rname' => 'full_name',
            'source' => 'non-db',
            'table' => 'users',
            'id_name' => 'created_by',
            'module' => 'Users',
            'duplicate_merge' => 'disabled',
            'importable' => false,
            'massupdate' => false,
            'duplicate_on_record_copy' => 'no',
            'readonly' => true,
            'sort_on' => array('last_name'),
        ),
        'created_by_link' => array(
            'name' => 'created_by_link',
            'type' => 'link',
            'relationship' => 'emailtemplates_created_by',
            'vname' => 'LBL_CREATED_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
		'published' => array(
			'name' => 'published',
			'vname' => 'LBL_PUBLISHED',
			'type' => 'varchar',
			'len' => '3',
			'comment' => ''
		),
		'name' => array(
			'name' => 'name',
			'vname' => 'LBL_NAME',
			'type' => 'varchar',
			'len' => '255',
			'comment' => 'Email template name',
			'importable' => 'required',
            'required' => true
		),
		'description' => array(
			'name' => 'description',
			'vname' => 'LBL_DESCRIPTION',
			'type' => 'text',
			'comment' => 'Email template description'
		),
		'subject' => array(
			'name' => 'subject',
			'vname' => 'LBL_SUBJECT',
			'type' => 'varchar',
			'len' => '255',
			'comment' => 'Email subject to be used in resulting email'
		),
		'body' => array(
			'name' => 'body',
			'vname' => 'LBL_BODY',
			'type' => 'text',
			'comment' => 'Plain text body to be used in resulting email'
		),
		'body_html' => array(
			'name' => 'body_html',
			'vname' => 'LBL_PLAIN_TEXT',
			'type' => 'html',
			'comment' => 'HTML formatted email body to be used in resulting email'
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => false,
			'reportable'=>false,
			'comment' => 'Record deletion indicator'
		),
		'base_module' => array(
			'name' => 'base_module',
			'vname' => 'LBL_BASE_MODULE',
			'type' => 'varchar',
			'len' => '50',
			'comment' => 'In Workflow alert templates, the module to which this template is associated'
		),
		'from_name' => array(
			'name' => 'from_name',
			'vname' => 'LBL_FROM_NAME',
			'type' => 'varchar',
			'len' => '255',
			'reportable'=>false,
		),
		'from_address' => array(
			'name' => 'from_address',
			'vname' => 'LBL_FROM_ADDRESS',
			'type' => 'varchar',
			'len' => '255',
			'reportable'=>false,
		),
        'text_only' => array(
            'name' => 'text_only',
            'vname' => 'LBL_TEXT_ONLY',
            'type' => 'bool',
            'required' => false,
            'reportable'=>false,
            'comment' => 'Should be checked if email template is to be sent in text only'
        ),
        'type' => array(
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'required' => false,
            'reportable'=> false,
            'default'=> 'email',
            'options' => 'emailTemplates_type_list',
            'comment' => 'Type of the email template'
       ),
        'attachments' => array(
            'bean_name' => 'Note',
            'module' => 'Notes',
            'name' => 'attachments',
            'relationship' => 'emailtemplates_attachments',
            'source' => 'non-db',
            'type' => 'link',
            'vname' => 'LBL_ATTACHMENTS',
        ),
        'has_variables' => array(
            'name' => 'has_variables',
            'vname' => 'LBL_TEMPLATE_HAS_VARIABLES',
            'type' => 'bool',
            'default' => '0',
            'readonly' => true,
            'duplicate_on_record_copy' => 'no',
            'massupdate' => false,
            'importable' => false,
        ),
	),
	'indices' => array(
		array(
			'name' => 'email_templatespk',
			'type' =>'primary',
			'fields'=>array('id')
		),
		array(
			'name' => 'idx_email_template_name',
			'type'=>'index',
			'fields'=>array('name')
		),
        array('name' => 'idx_emailtemplate_type', 'type' => 'index', 'fields' => array('type')),
        array('name' => 'idx_emailtemplate_date_modified', 'type' => 'index', 'fields' => array('date_modified')),
        array('name' => 'idx_emailtemplate_date_entered', 'type' => 'index', 'fields' => array('date_entered')),
	),
    'relationships' => array(
        'emailtemplates_assigned_user' => array(
            'lhs_module'=> 'Users',
            'lhs_table'=> 'users',
            'lhs_key' => 'id',
            'rhs_module'=> 'EmailTemplates',
            'rhs_table'=> 'email_templates',
            'rhs_key' => 'assigned_user_id',
            'relationship_type'=>'one-to-many'
        ),
        'emailtemplates_modified_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailTemplates',
            'rhs_table' => 'email_templates',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'emailtemplates_created_by' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailTemplates',
            'rhs_table' => 'email_templates',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'
        ),
        'emailtemplates_attachments' => array(
            'lhs_module' => 'EmailTemplates',
            'lhs_table' => 'email_templates',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'email_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'email_type',
            'relationship_role_column_value' => 'EmailTemplates',
        ),
    ),
    'uses' => array(
        'assignable',
        'team_security',
    ),
    'acls' => array('SugarACLStatic' => true),
);

VardefManager::createVardef('EmailTemplates', 'EmailTemplate', array());
