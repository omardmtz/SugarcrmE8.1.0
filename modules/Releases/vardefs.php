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
$dictionary['Release'] = array(
  'table' => 'releases',
  'favorites' => false,
  'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>true,
  ),
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>false,
    'reportable'=>false,
  ),
  'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
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
    'required'=>false,
    'reportable'=>true,
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

  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '50',
    'required'=>true,
    'importable' => 'required',
  ),
 'list_order' =>
  array (
    'name' => 'list_order',
    'vname' => 'LBL_LIST_ORDER',
    'type' => 'int',
    'len' => '4',
    'importable' => 'required',
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'release_status_dom',
    'len' => 100,
    'importable' => 'required',
  ),
),
'acls' => array('SugarACLDeveloperOrAdmin' => array('aclModule' => 'Bugs', 'allowUserRead' => true)),
'indices' => array (
       array('name' =>'releasespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_releases', 'type'=>'index', 'fields'=>array('name','deleted')),
                                                      )
                            );
?>
