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

global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $hilite_bg;
global $sugar_version, $sugar_config;

global $theme;

$GLOBALS['log']->info("Project Dashboard view");

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_MY_PROJECTS_DASHBOARD']), true);

$sugar_smarty = new Sugar_Smarty();
///
/// Assign the template variables
///
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);

// MY PROJECTS DASHBOARD ////////////////////////////////////////
$projectBean = BeanFactory::newBean('Project');
$projects = array();

$today = $timedate->nowDbDate();
$nextWeek = $timedate->asDbDate( $timedate->getNow()->get('+1 week'));

$query = "SELECT * FROM project WHERE project.assigned_user_id='".$current_user->id."' AND project.estimated_end_date >= '".
        $today."' AND project.is_template=0 AND project.deleted=0 order by project.estimated_end_date ASC";
$result = $projectBean->db->query($query, true, "");
while (($row = $projectBean->db->fetchByAssoc($result)) != null) {
    $project = BeanFactory::retrieveBean('Project', $row['id']);
    if(empty($project)) continue;
    array_push($projects, $project);
}

$overdueTasks = array();
$upcomingTasks = array();
$openCases = array();
$resources = array();
$overdueTaskCount = array();
$upcomingTaskCount = array();

foreach ($projects as $project) {
    // Find all overdue tasks/////////////////
    $projectTaskBean = BeanFactory::newBean('ProjectTask');
    $query = "SELECT * FROM project_task WHERE project_task.project_id='" .$project->id."' AND project_task.date_finish < '". $today . "' AND project_task.percent_complete < 100 AND project_task.deleted=0 order by project_task.date_finish ASC";
    $result = $projectTaskBean->db->query($query, true, "");
    $count = 0;
    while (($row = $projectTaskBean->db->fetchByAssoc($result)) != null) {
        if ($count == 10) {
            $count++;
            break;
        }

        $projectTask = BeanFactory::retrieveBean('ProjectTask', $row['id']);
        if(empty($projectTask)) continue;
        if (isset($overdueTasks[$project->id])) {
            array_push($overdueTasks[$project->id], $projectTask);
        }
        else {
            $overdueTasks[$project->id] = array();
            array_push($overdueTasks[$project->id], $projectTask);
        }
        $count++;
    }
    $overdueTaskCount[$project->id] = $count;
    /////////////////////////////////////////

    // Find all upcoming tasks/////////////////
    $projectTaskBean = BeanFactory::newBean('ProjectTask');
    $query = "SELECT * FROM project_task WHERE project_task.project_id='" .$project->id."' AND " .
            "(project_task.date_start BETWEEN '" . $today . "' AND '". $nextWeek . "' OR ".
            "project_task.date_finish BETWEEN '". $today . "' AND '". $nextWeek . "') AND project_task.deleted=0 order by project_task.date_finish ASC";

    $result = $projectTaskBean->db->query($query, true, "");
    $count = 0;
    while (($row = $projectTaskBean->db->fetchByAssoc($result)) != null) {
        if ($count == 10) {
            $count++;
            break;
        }
        $projectTask = BeanFactory::retrieveBean('ProjectTask', $row['id']);
        if(empty($projectTask)) continue;
        if (isset($upcomingTasks[$project->id]))
            array_push($upcomingTasks[$project->id], $projectTask);
        else {
            $upcomingTasks[$project->id] = array();
            array_push($upcomingTasks[$project->id], $projectTask);
        }
        $count++;
    }
    $upcomingTaskCount[$project->id] = $count;

    /////////////////////////////////////////

    // Find all related Cases /////////////////
    $caseBean = BeanFactory::newBean('Cases');
    $query = "SELECT * from cases where id in ".
            "(SELECT case_id from projects_cases where project_id='". $project->id. "' and deleted = 0) and status != '".
            $app_list_strings['case_status_dom']['Closed'] . "'";

    $result = $caseBean->db->query($query, true, "");
    $count = 0;
    while (($row = $caseBean->db->fetchByAssoc($result)) != null) {
        if ($count == 10) {
            $count++;
            break;
        }
        $case = BeanFactory::retrieveBean('Cases', $row['id']);
        if(empty($case)) continue;

        if (isset($openCases[$project->id]))
            array_push($openCases[$project->id], $case);
        else {
            $openCases[$project->id] = array();
            array_push($openCases[$project->id], $case);
        }
        $count++;
    }
    $caseCount[$project->id] = $count;
    /////////////////////////////////////////
    // Find all resources/////////////////

    $userBean = BeanFactory::newBean('Users');
    $project->load_relationship("user_resources");
    $users = $project->user_resources->getBeans($userBean);
    $contactBean = BeanFactory::newBean('Contacts');
    $project->load_relationship("contact_resources");
    $contacts = $project->contact_resources->getBeans($contactBean);

    if (is_array($users)) {
        foreach ($users as $item) {
            if (!isset($resources[$item->id])) {
                $resources[$item->id] = $item->full_name;
            }
        }
    }

    if (is_array($contacts)) {
        foreach ($contacts as $item) {
            if (!isset($resources[$item->id])) {
                $resources[$item->id] = $item->full_name;
            }
        }
    }
    /////////////////////////////////////////
}

$sugar_smarty->assign("RESOURCES", $resources);
$sugar_smarty->assign("PROJECTS", $projects);
$sugar_smarty->assign("UPCOMING_TASKS", $upcomingTasks);
$sugar_smarty->assign("OVERDUE_TASKS", $overdueTasks);
$sugar_smarty->assign("OPEN_CASES", $openCases);
$sugar_smarty->assign("BG_COLOR", $hilite_bg);
$sugar_smarty->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
//todo: also add the owner's managers

$sugar_smarty->assign("DATE_FORMAT", $current_user->getPreference('datef'));
$sugar_smarty->assign("CURRENT_USER", $current_user->id);

// MY PROJECT TASKS DASHBOARD ////////////////////////////////////////
$myOverdueTasks = array();
$myUpcomingTasks = array();

// Find all my overdue tasks/////////////////
$projectTaskBean = BeanFactory::newBean('ProjectTask');
$query = "SELECT * FROM project_task WHERE project_task.resource_id like '".$current_user->id."' AND project_task.date_finish < '". $today . "' AND project_task.percent_complete < 100 AND project_task.deleted=0 order by project_task.date_finish ASC";
$result = $projectTaskBean->db->query($query, true, "");
$myOverDueTasksCount = 0;
while (($row = $projectTaskBean->db->fetchByAssoc($result)) != null) {
    $projectTask = BeanFactory::retrieveBean('ProjectTask', $row['id']);
    if(empty($projectTask)) continue;
    array_push($myOverdueTasks, $projectTask);
    $myOverDueTasksCount++;
}
/////////////////////////////////////////
// Find all upcoming tasks/////////////////
$projectTaskBean = BeanFactory::newBean('ProjectTask');
$query = "SELECT * FROM project_task WHERE project_task.resource_id like '" .$current_user->id."' AND " .
        "(project_task.date_start BETWEEN '" . $today . "' AND '". $nextWeek . "' OR ".
        "project_task.date_finish BETWEEN '". $today . "' AND '". $nextWeek . "') AND project_task.deleted=0 order by project_task.date_finish ASC";

$result = $projectTaskBean->db->query($query, true, "");
$myUpcomingTasksCount = 0;
while (($row = $projectTaskBean->db->fetchByAssoc($result)) != null) {
    $projectTask = BeanFactory::retrieveBean('ProjectTask', $row['id']);
    if(empty($projectTask)) continue;
    array_push($myUpcomingTasks, $projectTask);
    $myUpcomingTasksCount++;
}
/////////////////////////////////////////
$sugar_smarty->assign("MY_UPCOMING_TASKS", $myUpcomingTasks);
$sugar_smarty->assign("MY_OVERDUE_TASKS", $myOverdueTasks);

$sugar_smarty->assign("OVERDUE_TASKS_COUNT", $overdueTaskCount);
$sugar_smarty->assign("UPCOMING_TASKS_COUNT", $upcomingTaskCount);

echo $sugar_smarty->fetch('modules/Project/Dashboard.tpl');
