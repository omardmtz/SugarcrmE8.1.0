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

$dictionary['tracker_tracker_queries'] = array ( 
'table' => 'tracker_tracker_queries', 
'fields' => array (
      'id'=>array('name' => 'id', 'vname' => 'LBL_ID', 'type' => 'int', 'len' => '11', 'isnull' => 'false', 'auto_increment' => true, 'reportable'=>false),      
      'monitor_id'=>array('name' =>'monitor_id', 'type' =>'id', 'len'=>'36'),
      'query_id'=>array('name' =>'query_id', 'type' =>'id', 'len'=>'36'),
      'date_modified'=>array ('name' => 'date_modified','type' => 'datetime')
),
'indices' => array (
      array('name' =>'tracker_tracker_queriespk', 'type' =>'primary', 'fields'=>array('id')),
      array('name' =>'idx_tracker_tq_monitor', 'type' =>'index', 'fields'=>array('monitor_id')),
      array('name' =>'idx_tracker_tq_query', 'type' =>'index', 'fields'=>array('query_id')),
), 
'relationships' => array (
	'tracker_tracker_queries' => array(
	    'lhs_module'=> 'Trackers', 'lhs_table'=> 'tracker', 'lhs_key' => 'monitor_id',
		'rhs_module'=> 'TrackerQueries', 'rhs_table'=> 'tracker_queries', 'rhs_key' => 'query_id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'tracker_tracker_queries', 'join_key_lhs'=>'monitor_id', 'join_key_rhs'=>'query_id'
	 )
)
);                              

?>
