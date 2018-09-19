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

$vardefs = array(
    'fields' => array(
        'email'=> array(
            'name' => 'email',
            'type' => 'email',
            'query_type' => 'default',
            'source' => 'non-db',
            'operator' => 'subquery',
            'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
            'db_field' => array(
                'id',
            ),
            'vname' =>'LBL_EMAIL_ADDRESS',
            'studio' => array(
                'visible' => true,
                'searchview' => true,
                'editview' => true,
                'editField' => true,
            ),
            'duplicate_on_record_copy' => 'always',
            'len' => 100,
            'link' => 'email_addresses_primary',
            'rname' => 'email_address',
            'module' => 'EmailAddresses',
            'full_text_search' => array(
                'enabled' => true,
                'searchable' => true,
                'boost' => 1.50,
            ),
            'audited' => true,
            'pii' => true,
        ),
        'email1' => array(
			'name'		=> 'email1',
			'vname'		=> 'LBL_EMAIL_ADDRESS',
			'type'		=> 'varchar',
			'function'	=> array(
				'name'		=> 'getEmailAddressWidget',
				'returns'	=> 'html',
            ),
			'source'	=> 'non-db',
			'link' => 'email_addresses_primary',
			'rname' => 'email_address',
			'group'=>'email1',
            'merge_filter' => 'enabled',
            'module' => 'EmailAddresses',
		    'studio' => false,
            'duplicate_on_record_copy' => 'always',
            'importable' => false,
        ),
        'email2' => array(
			'name'		=> 'email2',
			'vname'		=> 'LBL_OTHER_EMAIL_ADDRESS',
			'type'		=> 'varchar',
			'function'	=> array(
				'name'		=> 'getEmailAddressWidget',
				'returns'	=> 'html',
            ),
			'source'	=> 'non-db',
			'group'=>'email2',
            'merge_filter' => 'enabled',
		    'studio' => 'false',
            'duplicate_on_record_copy' => 'always',
            'importable' => false,
            'workflow' => false,
		),
        'invalid_email' => array(
			'name'		=> 'invalid_email',
			'vname'     => 'LBL_INVALID_EMAIL',
			'source'	=> 'non-db',
			'type'		=> 'bool',
			'link'      => 'email_addresses_primary',
			'rname'     => 'invalid_email',
		    'massupdate' => false,
		    'studio' => 'false',
            'duplicate_on_record_copy' => 'always',
		),
        'email_opt_out' => array(
			'name'		=> 'email_opt_out',
			'vname'     => 'LBL_EMAIL_OPT_OUT',
			'source'	=> 'non-db',
			'type'		=> 'bool',
			'link'      => 'email_addresses_primary',
			'rname'     => 'opt_out',
		    'massupdate' => false,
			'studio'=>'false',
            'duplicate_on_record_copy' => 'always',
		),
        'email_addresses_primary' => array (
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => strtolower($module).'_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge' => 'disabled',
            'primary_only' => true,
        ),
        'email_addresses' => array (
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => strtolower($module).'_email_addresses',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable'=>false,
            'unified_search' => true,
            'rel_fields' => array('primary_address' => array('type'=>'bool')),
        ),
        // Used for non-primary mail import
        'email_addresses_non_primary'=> array(
            'name' => 'email_addresses_non_primary',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' =>'LBL_EMAIL_NON_PRIMARY',
            'studio' => false,
            'reportable' => false,
            'massupdate' => false,
        ),
    ),
    'relationships' => array(
        strtolower($module).'_email_addresses' => array(
            'lhs_module'=> $module,
            'lhs_table'=> strtolower($module),
            'lhs_key' => 'id',
            'rhs_module'=> 'EmailAddresses',
            'rhs_table'=> 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'email_addr_bean_rel',
            'join_key_lhs'=>'bean_id',
            'join_key_rhs'=>'email_address_id',
            'relationship_role_column'=>'bean_module',
            'relationship_role_column_value'=>$module,
        ),
        strtolower($module).'_email_addresses_primary' => array(
            'lhs_module'=> $module,
            'lhs_table'=> strtolower($module),
            'lhs_key' => 'id',
            'rhs_module'=> 'EmailAddresses',
            'rhs_table'=> 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'email_addr_bean_rel',
            'join_key_lhs'=>'bean_id',
            'join_key_rhs'=>'email_address_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => $module,
            'primary_flag_column' => 'primary_address',
        ),
    ),
    'indices' => array(
    ),
    'acls'=>array(
        'SugarACLEmailAddress'=>true
    ),
);

