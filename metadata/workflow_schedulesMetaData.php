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

$dictionary['WorkFlowSchedule'] = array('table' => 'workflow_schedules'
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
    'required' => false,
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
  'date_expired' => 
  array (
    'name' => 'date_expired',
    'vname' => 'LBL_DATE_EXPIRED',
    'type' => 'datetime',
    'required' => true,
  ),
  'workflow_id' => 
  array (
    'name' => 'workflow_id',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
  ),  
    'target_module' => 
  array (
    'name' => 'target_module',
    'vname' => 'LBL_TARGET_MODULE',
    'type' => 'varchar',
    'len' => '50',
  ),
    'bean_id' => 
  array (
    'name' => 'bean_id',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
  ),  
      'parameters' => 
  array (
    'name' => 'parameters',
    'vname' => 'LBL_PARAMETERS',
    'type' => 'varchar',
    'len' => '255',
    'required' => false,
  ),       
)
                                                      , 'indices' => array (
       array('name' =>'schedule_k', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_wkfl_schedule', 'type'=>'index', 'fields'=>array('workflow_id','deleted')),
                                                      )
                            );
?>
