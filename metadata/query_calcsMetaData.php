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
$dictionary['QueryCalc'] = array('table' => 'query_calcs'
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
    'vname' => 'LBL_CALC_NAME',
    'type' => 'varchar',
    'len' => '50',
  ),
  'calc_field' => 
  array (
    'name' => 'column_module',
    'vname' => 'LBL_CALC_FIELD',
    'type' => 'varchar',
    'len' => '50',
  ),
    'calc_module' => 
  array (
    'name' => 'calc_module',
    'vname' => 'LBL_CALC_MODULE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'type' => 
  array (
    'name' => 'type',
    'vname' => 'LBL_CALC_TYPE',
    'type' => 'enum',
    'required' => true,
    'options' => 'query_calc_type_dom',
    'len'=>25,
  ),
    'calc_type' => 
  array (
    'name' => 'calc_type',
    'vname' => 'LBL_CALC_CALC_TYPE',
    'type' => 'enum',
    'required' => true,
    'options' => 'query_calc_calc_type_dom',
    'len'=>25,
  ),
    'calc_query_type' => 
  array (
    'name' => 'calc_query_type',
    'vname' => 'LBL_CALC_CALC_QUERY_TYPE',
    'type' => 'enum',
    'required' => true,
    'options' => 'query_calc_calc_query_type_dom',
    'len'=>25,
  ),
      'calc_group_condition' => 
  array (
    'name' => 'calc_group_condition',
    'vname' => 'LBL_CALC_GROUP_CONDITION',
    'type' => 'enum',
    'required' => true,
    'options' => 'query_calc_group_cond_dom',
    'len'=>25,
  ),
      'filter_group_condition' => 
  array (
    'name' => 'filter_group_condition',
    'vname' => 'LBL_FILTER_GROUP_CONDITION',
    'type' => 'enum',
    'required' => true,
    'options' => 'query_filter_group_cond_dom',
    'len'=>25,
  ),
    'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
  ),
    'calc_order' => 
  array (
    'name' => 'calc_order',
    'vname' => 'LBL_CALC_ORDER',
    'type' => 'int',
    'len' => '4',
  ),
      'filter_group' => 
  array (
    'name' => 'filter_group',
    'vname' => 'LBL_FILTER_GROUP',
    'type' => 'int',
    'len' => '4',
  ),
  
)
                                                      , 'indices' => array (
       array('name' =>'querycalc_k', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_querycalc', 'type'=>'index', 'fields'=>array('name','deleted')),
                                                      )
                            );
?>
