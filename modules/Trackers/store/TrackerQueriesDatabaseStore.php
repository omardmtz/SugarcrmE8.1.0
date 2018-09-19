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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class TrackerQueriesDatabaseStore implements Store {

    public function flush($monitor)
    {
        $db = DBManagerFactory::getInstance();
        if($monitor->run_count > 1) {
            $query = "UPDATE $monitor->table_name set run_count={$monitor->run_count}, sec_avg={$monitor->sec_avg}, sec_total={$monitor->sec_total}, date_modified='{$monitor->date_modified}' where query_hash = '{$monitor->query_hash}'";
            $db->query($query);
            return;
        }

       $metrics = $monitor->getMetrics();
       $values = array();
       foreach($metrics as $name=>$metric) {
       	  if(!empty($monitor->$name)) {
       	  	 $columns[] = $name;
       	  	 $fields[$name] = array('name' => $name, 'type' => $metrics[$name]->_type);
       	  	 $values[$name] = $monitor->$name;
           }
       } //foreach

       if(empty($values)) {
       	  return;
       }

       $fields['id'] = array('auto_increment' => true, "name" => "id", "type" => "int");
       $db->insertParams($monitor->table_name, $fields, $values);
    }
}
