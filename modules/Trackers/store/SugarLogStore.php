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


class SugarLogStore implements Store {
    
    public function flush($monitor) {
       $metrics = $monitor->getMetrics();
       $values = array();
       foreach($metrics as $name=>$metric) {
       	  if(!empty($monitor->$name)) {
       	  	 $values[$name] = $monitor->$name;
       	  }
       } //foreach
       
       if(empty($values)) {
       	  return;
       }
       
       $GLOBALS['log']->info("---- metrics for $monitor->name ----");
       $GLOBALS['log']->info(var_export($values, true));
    }
}
?>
