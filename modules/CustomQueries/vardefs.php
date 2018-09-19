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
$dictionary['CustomQuery'] = array('table' => 'custom_queries',
	'comment' => 'Stores the query used in Advanced Reports'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
    'comment' => 'Unique identifer'
  ),
   'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
    'comment' => 'Date record created'
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
    'comment' => 'Date record last modified'
  ),
  'modified_user_id' =>
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
	'comment' => 'User who last modified record'
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User ID who created record'
  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Name of the custom query',
    'importable' => 'required',
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full description of the custom query'
  ),
    'custom_query' =>
  array (
    'name' => 'custom_query',
    'vname' => 'LBL_CUSTOMQUERY',
    'type' => 'text',
    'comment' => 'The SQL statement'
  ),
    'query_type' =>
  array (
    'name' => 'query_type',
    'vname' => 'LBL_QUERY_TYPE',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'The type of query (unused)'
  ),
    'list_order' =>
  array (
    'name' => 'list_order',
    'vname' => 'LBL_LIST_ORDER',
    'type' => 'int',
    'len' => '4',
    'comment' => 'The relative order of this query (unused)'
  ),
     'query_locked' =>
  array (
    'name' => 'query_locked',
    'vname' => 'LBL_QUERY_LOCKED',
    'type' => 'bool',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => '0',
    'comment' => 'Indicates whether query body (the SQL statement) can be changed'
  ),
),
'acls' => array('SugarACLAdminOnly' => array('allowUserRead' => true)),
'indices' => array (
       array('name' =>'custom_queriespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_customqueries', 'type'=>'index', 'fields'=>array('name','deleted')),
                                                      ),
                            );

VardefManager::createVardef('CustomQueries','CustomQuery', array(
'team_security',
));
?>
