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

$GLOBALS['dictionary']['UserPreference'] = array('table' => 'user_preferences',
'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'category' => 
  array (
    'name' => 'category',
    'type' => 'varchar',
    'len' => 50,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'type' => 'bool',
    'default' => '0',
    'required'=>false,
  ),
  'date_entered' => 
  array (
    'name' => 'date_entered',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'type' => 'datetime',
    'required' => true,
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'id',
    'table' => 'users',
    'required' => true,
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
    'type' => 'longtext',
    'vname' => 'LBL_DESCRIPTION',
    'isnull' => true,
  ),
),
 

'indices' => array (
       array('name' =>'userpreferencespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_userprefnamecat', 'type'=>'index', 'fields'=>array('assigned_user_id','category')),
      )
);

// cn: bug 12036 - $dictionary['x'] for SugarBean::createRelationshipMeta() from upgrades
$dictionary['UserPreference'] = $GLOBALS['dictionary']['UserPreference'];
