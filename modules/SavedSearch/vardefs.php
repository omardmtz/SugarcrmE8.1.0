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
$dictionary['SavedSearch'] = array('table' => 'saved_search',
'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 150,
  ),
  'search_module' => 
  array (
    'name' => 'search_module',
    'type' => 'varchar',
    'vname' => 'LBL_MODULE',
    'len' => 150,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required'=>true,
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
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'id',
    'isnull' => 'false',
    'reportable'=>true,
    'massupdate' => false,
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'type' => 'varchar',
    'reportable'=>false,
    'massupdate' => false,
    'source'=>'non-db',
    'table' => 'users',
  ),
  'contents' => 
  array (
    'name' => 'contents',
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'isnull' => true,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'isnull' => true,
  ),
  'assigned_user_link' =>
  array (
        'name' => 'assigned_user_link',
    'type' => 'link',
    'relationship' => 'saved_search_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
),
'relationships' => array (
  'saved_search_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'SavedSearch', 'rhs_table'=> 'saved_search', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')
), 

'indices' => array (
       array('name' =>'savedsearchpk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_desc', 'type'=>'index', 'fields'=>array('name','deleted')))
);

VardefManager::createVardef('SavedSearch','SavedSearch', array(
'team_security',
));
?>
