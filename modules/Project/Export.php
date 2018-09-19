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

// $Id: EditView.php 16705 2006-09-12 23:59:52 +0000 (Tue, 12 Sep 2006) jenny $

global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $sugar_version, $sugar_config;
global $locale;
$focus = BeanFactory::newBean('Project');

if(!empty($_REQUEST['record']))
{
    $focus->retrieve($_REQUEST['record']);
}

// MPX = Microsoft Project eXchange file
// Building contents of this file here.

// File Creation (required record that identifies the file format
$mpx = "MPX,SugarCRM,4.5.1,ANSI\n";

// (10) Currency Settings 
$mpx .= "10,$,1,2,\",\",.\n";

// (11) Default Settings 
$mpx .= "11,2,0,1,8.00,40.00,$10.00/h,$15.00/h,1,0\n";

// (12) Date Time Settings
$mpx .= "12,2,0,480,-,:,am,pm,20,20\n";

// (30) Project Header Information
$mpx .= "30," . $focus->name . ",,,Standard," . $timedate->to_db_date($focus->estimated_start_date, false) .",,0," . $timedate->nowDbDate() .",,$0.00,$0.00,$0.00,0h,0h,0h,0%,0d,0d,0d,0%,,,,,0d,0d\n";
 
// (20) Calendar Definition
$mpx .= "20,Standard,0,1,1,1,1,1,0\n";

// (25) Calendar Hours Definition
$mpx .= "25,1\n";
$mpx .= "25,2,09:00 AM,12:00 PM,12:00 PM,05:00 PM\n";
$mpx .= "25,3,09:00 AM,12:00 PM,12:00 PM,05:00 PM\n";
$mpx .= "25,4,09:00 AM,12:00 PM,12:00 PM,05:00 PM\n";
$mpx .= "25,5,09:00 AM,12:00 PM,12:00 PM,05:00 PM\n";
$mpx .= "25,6,09:00 AM,12:00 PM,12:00 PM,05:00 PM\n";
$mpx .= "25,7\n"; 
 
// (40) Resource Table Definition
$mpx .= "40,Unique ID,ID,Name\n";
 
// (50) Resource Information
$resources = array();
$userBean = BeanFactory::newBean('Users');
$focus->load_relationship("user_resources");
$users = $focus->user_resources->getBeans($userBean);
$contactBean = BeanFactory::newBean('Contacts');
$focus->load_relationship("contact_resources");
$contacts = $focus->contact_resources->getBeans($contactBean);

for ($i = 1; $i <= count($users); $i++) {
	$mpx .= "50," . $i . "," . $i . "," . $locale->translateCharset($users[$i-1]->full_name, 'UTF-8', $locale->getExportCharset()) . "\n";
	$resources[$users[$i-1]->id] = $i;
}
$j = $i-1;
for ($i = 1; $i <= count($contacts); $i++) {
	$offset = $i+$j;
    $mpx .= "50," . $offset . "," . $offset . "," . $locale->translateCharset($contacts[$i-1]->full_name, 'UTF-8', $locale->getExportCharset()) . "\n";
    $resources[$contacts[$i-1]->id] = $offset;
}
 
// (60) Task Table Definition
$mpx .= "60,Outline Level,Unique ID,ID,Name,Milestone,Start,Finish,Constraint Date,Constraint Type,Duration,% Complete,Actual Start,Predecessors\n";

// (61) Numeric Task Table Definition
$mpx .= "61,3,98,90,1,81,50,51,68,91,40,44,58,70\n";


// (70) Task Information
$projectTasks = $focus->getAllProjectTasks();

$outlineLevel = 1;
$indentLevel = array();
for ($i = 0; $i < count($projectTasks); $i++){
	$projTaskId = $i+1;
	
	$mpx .= "70,";
	
	// outline level
	if (!empty($projectTasks[$i]->parent_task_id)){
		if ($outlineLevel == 1){
			$outlineLevel = $outlineLevel + 1;
			$indentLevel[$projectTasks[$i]->parent_task_id] = $outlineLevel;
		}
		else{
			if (!isset($indentLevel[$projectTasks[$i]->parent_task_id])){
				$outlineLevel = $outlineLevel + 1;
				$indentLevel[$projectTasks[$i]->parent_task_id] = $outlineLevel;
			}	
			else{
				$outlineLevel = $indentLevel[$projectTasks[$i]->parent_task_id];
			}
		}
	}
	else{
		$outlineLevel = 1;
	}
		
	$mpx .= $outlineLevel . ",";
	
	// unique id / id
	$mpx .= $projTaskId . "," .$projTaskId . ",";
	
	// name
    $mpx_name = htmlspecialchars_decode( $locale->translateCharset($projectTasks[$i]->name, 'UTF-8', $locale->getExportCharset()) );
    $mpx_name = str_replace('"', '""', $mpx_name);
    
	$mpx .= "\"" . $mpx_name . "\",";
	
	// milestone
	$mpx .= ($projectTasks[$i]->milestone_flag == 1) ? "Yes," :  "No,";
	
	// start
	$mpx .= $timedate->to_db_date($projectTasks[$i]->date_start, false) . ",";

    // finish
    $mpx .= $timedate->to_db_date($projectTasks[$i]->date_finish, false) . ",";
	
	// constraint date
	$mpx .= $timedate->to_db_date($projectTasks[$i]->date_start, false) . ",";
	
	// constraint type
	$mpx .= "Must Start On,";
	
	// duration
	$duration_unit = ($projectTasks[$i]->duration_unit == "Hours") ? "h" : "d";
	$mpx .= $projectTasks[$i]->duration . $duration_unit . ",";
	
	// $ complete
	$mpx .= $projectTasks[$i]->percent_complete ."%,";
	
	// actual start
	$mpx .= $timedate->to_db_date($projectTasks[$i]->date_start, false) . ',';;
	
	// predecessors
	if ($projectTasks[$i]->predecessors != ''){
		$mpx .= "\"" . $projectTasks[$i]->predecessors . "\"";
	}
	
	$mpx .= "\n";

	// (75) Task Resource Definition
	
	if ($projectTasks[$i]->resource_id != '' || $projectTasks[$i]->resource_id != NULL){
		print('resource_id = ' . $projectTasks[$i]->resource_id . '<br>');
		$progress = ($projectTasks[$i]->duration * $projectTasks[$i]->percent_complete) / 100;
		$mpx .= "75," . $resources[$projectTasks[$i]->resource_id] . ",1," . $projectTasks[$i]->duration . $duration_unit . ",," . $progress . $duration_unit . ",,,,,,,," . $resources[$projectTasks[$i]->resource_id] . "\n";
	}
}

$name = $locale->translateCharset($focus->name, 'UTF-8', $locale->getExportCharset());

ob_end_clean();

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-type: application/force-download");
header("Content-Length: " . strlen($mpx));
header("Content-disposition: attachment; filename=\"".$name.".mpx\";");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(0);

ob_start();

print $mpx;

@ob_end_flush();
sugar_cleanup();
exit;	
?>
