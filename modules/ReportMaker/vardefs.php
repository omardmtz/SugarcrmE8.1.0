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
$dictionary['ReportMaker'] = array('table' => 'report_maker'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
  ),
   'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
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
    'dbType' => 'id'
  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '50',
    'importable' => 'required',
    'required' => true,
  ),
    'title' =>
  array (
    'name' => 'title',
    'vname' => 'LBL_TITLE',
    'type' => 'varchar',
    'len' => '50',
  ),
 'report_align' =>
  array (
    'name' => 'report_align',
    'vname' => 'LBL_REPORT_ALIGN',
    'type' => 'enum',
    'len' => '8',
    'options' => 'report_align_dom',
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
   'scheduled' =>
  array (
    'name' => 'scheduled',
    'vname' => 'LBL_SCHEDULED',
    'type' => 'bool',
    'default'=>0,
  ),

)
                                                      , 'indices' => array (
       array('name' =>'rmaker_k', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_rmaker', 'type'=>'index', 'fields'=>array('name','deleted')),
    ),
    'acls' => array(
        'SugarACLAdminOnly' => array(
            'allowUserRead' => true,
        ),
    ),
                            );

VardefManager::createVardef('ReportMaker','ReportMaker', array(
'team_security',
));
?>
