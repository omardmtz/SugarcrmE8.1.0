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

// $Id: time_utils.php 51719 2009-10-22 17:18:00Z mitani $

include_once('include/workflow/workflow_utils.php');
include_once('modules/WorkFlow/WorkFlowSchedule.php');

function get_time_contents($workflow_id){
	
	$contents = "";
	
	//check to see if this item already is in the schedule table
	$contents .= "\t\t check_for_schedule(\$focus, \$workflow_id, \$time_array); \n\n ";

	
	return $contents;
	
//end function get_time_contents	
}	


function check_for_schedule(& $focus, $workflow_id, $time_array){

    // Check to see if it exists
	$wflow_schedule = new WorkFlowSchedule();
	$is_update = $wflow_schedule->check_existing_trigger($focus->id, $workflow_id);

	if(isset($time_array['parameters'])){
		$wflow_schedule->parameters = $time_array['parameters'];
	}

    // If new record, set the data
    if (!$is_update) {
		$wflow_schedule->bean_id = $focus->id;
		$wflow_schedule->workflow_id = $workflow_id;
		$wflow_schedule->target_module = $focus->module_dir;
	}

    $wflow_schedule->set_time_interval($focus, $time_array, $is_update);

    if (!empty($wflow_schedule->date_expired)) {
        $wflow_schedule->save();
    }

}
