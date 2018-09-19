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
/**
 * Save functionality for ProjectTask
 */

global $timedate;

$newIds = array();

for ($i = 1; $i <= $_REQUEST['numRowsToSave']; $i++) {
    // don't save any blank rows
    if (isset($_REQUEST["duration_" . $i]) && ($_REQUEST["duration_" . $i] != "")) {
        $projectTask = BeanFactory::newBean('ProjectTask');
        $projectTask->skipParentUpdate();
        if (isset($_REQUEST["obj_id_" . $i])) {
            //$projectTask->id = $_REQUEST["obj_id_" . $i];
            $projectTask->retrieve($_REQUEST["obj_id_" . $i]);
            if (!$projectTask->ACLAccess('edit')) {
                continue;
            }
        }
        $projectTask->project_task_id = $_REQUEST["mapped_row_" . $i];
        $projectTask->percent_complete = $_REQUEST["percent_complete_" . $i];
        $projectTask->name = $_REQUEST["description_" . $i];
        $projectTask->duration = $_REQUEST["duration_" . $i];

        if (isset($_REQUEST["duration_unit_" . $i]))
            $projectTask->duration_unit = $_REQUEST["duration_unit_" . $i];

        $projectTask->date_start = $_REQUEST["date_start_" . $i];
        $projectTask->date_finish = $_REQUEST["date_finish_" . $i];
        $projectTask->milestone_flag = $_REQUEST["is_milestone_" . $i];
        $projectTask->time_start = $_REQUEST["time_start_" . $i];
        $projectTask->time_finish = $_REQUEST["time_finish_" . $i];

        //$projectTask->parent_task_id = $_REQUEST["parent_" . $i];
        $parentId = $_REQUEST["parent_" . $i];
        if ($parentId != "")
            $projectTask->parent_task_id = $_REQUEST["mapped_row_".$parentId];
        else
            $projectTask->parent_task_id = "";
        $projectTask->project_id = $_REQUEST["project_id"];
        $projectTask->predecessors = $_REQUEST["predecessors_" . $i];

        if ($_REQUEST["is_template"]){
        	$projectTask->assigned_user_id = NULL;
        }
        else if (isset($_REQUEST["resource_" . $i])) {
            $projectTask->resource_id = $_REQUEST["resource_" . $i];
            if ($_REQUEST["resource_type_" . $i] == "User")
                $projectTask->assigned_user_id = $_REQUEST["resource_" . $i];
        }

        $projectTask->team_id = $_REQUEST["team_id"];
        $projectTask->actual_duration = $_REQUEST["actual_duration_" . $i];

        //todo check_notify
        $id = $projectTask->save(false);
        //$projectTask->save($GLOBALS['check_notify']);

        // Keep track of the newly generated Id to pass back to the grid so that we avoid
        // saving the row multiple times.
        if (empty($_REQUEST["obj_id_" . $i])) {
           $newIds[$i] = $id;
        }
    }
}
// get random ProjectTask from current project
$ind = rand(1, $_REQUEST['numRowsToSave']);
$projectTask = BeanFactory::newBean('ProjectTask');
$projectTask->skipParentUpdate();
if(isset($_REQUEST["obj_id_" . $ind]) && !empty($_REQUEST["obj_id_" . $ind]))
{
    $projectTask->retrieve($_REQUEST["obj_id_" . $ind]);
}
else
{
    $projectTask->retrieve($newIds[$ind]);
}
//updating percentage complete for tasks with child tasks in current project
$projectTask->updateStatistic();
// Handle deleted rows.
$deletedRows = $_REQUEST['deletedRows'];
if ($deletedRows != "") {
    $deletedRowsArray = explode(",", $deletedRows);
    foreach ($deletedRowsArray as $rowid) {
        $projectTask = BeanFactory::getBean('ProjectTask', $rowid);
        if ($projectTask->ACLAccess('delete')) {
            $projectTask->mark_deleted($projectTask->id);
        }
    }
}

$json = getJSONobj();
header("Content-Type: application/json");
echo $json->encode($newIds);

