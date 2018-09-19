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

 
$dictionary['ACLRole'] = array('table' => 'acl_roles', 'comment' => 'ACL Role definition'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'required'=>true,
    'type' => 'id',
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record created'
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record last modified'
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'modified_user_id_users',
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=> false,
    'len' => 36,
    'reportable'=>true,
    'comment' => 'User who last modified record'
  ),
    'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'created_by_users',
    'isnull' => 'false',
    'dbType' => 'id',
    'len' => 36,
    'comment' => 'User who created record'
  ),
   'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 150,
    'comment' => 'The role name'
  ),
   'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'The role description'
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
  'users' => 
  array (
  	'name' => 'users',
    'type' => 'link',
    'relationship' => 'acl_roles_users',
      'link_file' => 'modules/ACLRoles/UserLink.php',
      'link_class' => 'UserLink',
    'source'=>'non-db',
	'vname'=>'LBL_USERS',
  ),
    'actions' => 
  array (
  	'name' => 'actions',
    'type' => 'link',
    'relationship' => 'acl_roles_actions',
    'source'=>'non-db',
	'vname'=>'LBL_USERS',
  ),
        'acl_role_sets' => array(
            'name' => 'acl_role_sets',
            'type' => 'link',
            'source' => 'non-db',
            'relationship' => 'acl_role_sets_acl_roles',
        ),
),
'acls' => array('SugarACLDeveloperOrAdmin' => array('aclModule' => 'Users')),
'indices' => array (
       array('name' =>'aclrolespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_aclrole_id_del', 'type' =>'index', 'fields'=>array('id', 'deleted')),
       array('name' => 'idx_aclrole_name', 'type' => 'index', 'fields' => array('name'))

                                                   )

                            );

$dictionary['ACLRoleSet'] = array(
    'table' => 'acl_role_sets',
    'fields' => array(
        'hash' => array(
            'name' => 'hash',
            'type' => 'varchar',
            'len' => 32,
            'isnull' => false,
        ),
        'acl_roles' => array(
            'name' => 'acl_roles',
            'type' => 'link',
            'source' => 'non-db',
            'relationship' => 'acl_role_sets_acl_roles',
            'duplicate_merge' => 'disabled',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_acl_role_sets_hash',
            'type' => 'unique',
            'fields' => array('hash'),
        ),
    ),
);

VardefManager::createVardef('ACLRoleSets', 'ACLRoleSet');
