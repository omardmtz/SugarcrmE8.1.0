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

// $Id: Save.php 49350 2009-07-08 16:37:37Z eddy $


$project = BeanFactory::newBean('ProjectTask');
if(!empty($_POST['record']))
{
	$project->retrieve($_POST['record']);
}
////
//// save the fields to the ProjectTask object
////

if(isset($_REQUEST['email_id'])) $project->email_id = $_REQUEST['email_id'];

require_once('include/formbase.php');
$project = populateFromPost('', $project);
if(!isset($_REQUEST['milestone_flag']))
{
	$project->milestone_flag = '0';
}


$GLOBALS['check_notify'] = false;
if (!empty($_POST['assigned_user_id']) && ($project->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$GLOBALS['check_notify'] = true;
}

	if(!$project->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}

if( empty($project->project_id) ) $project->project_id = $_POST['relate_id']; //quick for 5.1 till projects are revamped for 5.5 nsingh- 7/3/08
$project->save($GLOBALS['check_notify']);

if(isset($_REQUEST['form']))
{
	// we are doing the save from a popup window
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}
else
{
	// need to refresh the page properly

	$return_module = empty($_REQUEST['return_module']) ? 'ProjectTask'
		: $_REQUEST['return_module'];

	$return_action = empty($_REQUEST['return_action']) ? 'index'
		: $_REQUEST['return_action'];

	$return_id = empty($_REQUEST['return_id']) ? $project->id
		: $_REQUEST['return_id'];
		
	//if this navigation is going to list view, do not show the bean id, it will populate the mass update.
	if($return_action == 'index') {
		$return_id ='';
	}		
header("Location: index.php?module=$return_module&action=$return_action&record=$return_id");

}
?>
