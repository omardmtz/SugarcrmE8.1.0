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

$dictionary['teams'] = array ( 'table' => 'teams'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36',)
      , array('name' =>'name', 'type' =>'varchar', 'len'=>'128', )
      , array('name' =>'date_entered', 'type' =>'datetime', 'len'=>'', )
      , array('name' =>'date_modified', 'type' =>'datetime', 'len'=>'', )
      , array('name' =>'modified_user_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'created_by', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'private', 'type' =>'bool', 'len'=>'1', 'default'=>'0')
      , array('name' =>'description', 'type' =>'text', 'len'=>'',)
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'teamspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_team_del', 'type' =>'index', 'fields'=>array('name'))
                                                      )
                                  )
?>
