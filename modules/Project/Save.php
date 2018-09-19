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
 * Save functionality for Project
 */

require_once('include/formbase.php');

global $current_user;

$sugarbean = BeanFactory::newBean('Project');
$sugarbean = populateFromPost('', $sugarbean);

$projectTasks = array();
if (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true"){
    $base_project_id = $_REQUEST['relate_id'];
}
else{
    $base_project_id = $sugarbean->id;
}
if(isset($_REQUEST['save_type']) || isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true") {
    $query = 'SELECT id FROM project_task WHERE project_id = ' . $sugarbean->db->quoted($base_project_id)
        . ' AND deleted = 0';
    $result = $sugarbean->db->query($query,true,"Error retrieving project tasks");
    $row = $sugarbean->db->fetchByAssoc($result);

    while ($row != null){
        $projectTaskBean = BeanFactory::newBean('ProjectTask');
        $projectTaskBean->id = $row['id'];
        $projectTaskBean->retrieve();
        $projectTaskBean->date_entered = '';
        $projectTaskBean->date_modified = '';
        array_push($projectTasks, $projectTaskBean);
        $row = $sugarbean->db->fetchByAssoc($result);
    }
}
if (isset($_REQUEST['save_type'])){
    $sugarbean->id = '';
    $sugarbean->assigned_user_id = $current_user->id;

    if ($_REQUEST['save_type'] == 'TemplateToProject'){
        $sugarbean->name = $_REQUEST['project_name'];
        $sugarbean->is_template = 0;
    }
    else if ($_REQUEST['save_type'] == 'ProjectToTemplate'){
        $sugarbean->name = $_REQUEST['template_name'];
        $sugarbean->is_template = true;
    }
}
else{
    if (isset($_REQUEST['is_template']) && $_REQUEST['is_template'] == '1'){
        $sugarbean->is_template = true;
    }
    else{
        $sugarbean->is_template = 0;
    }
}

if(isset($_REQUEST['email_id'])) $sugarbean->email_id = $_REQUEST['email_id'];

if(!$sugarbean->ACLAccess('Save')){
        ACLController::displayNoAccess(true);
        sugar_cleanup(true);
}

if (isset($GLOBALS['check_notify'])) {
    $check_notify = $GLOBALS['check_notify'];
}
else {
    $check_notify = FALSE;
}
$sugarbean->save($check_notify);
$return_id = $sugarbean->id;

if(isset($_REQUEST['save_type']) || isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true") {
    for ($i = 0; $i < count($projectTasks); $i++){
        if (isset($_REQUEST['save_type']) || (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true")){
            $projectTasks[$i]->id = '';
            $projectTasks[$i]->project_id = $sugarbean->id;
        }
        if ($sugarbean->is_template){
            $projectTasks[$i]->assigned_user_id = '';
        }
        $projectTasks[$i]->team_id = $sugarbean->team_id;
        if(empty( $projectTasks[$i]->duration_unit)) $projectTasks[$i]->duration_unit = " "; //Since duration_unit cannot be null.
        $projectTasks[$i]->save(false);
    }
}

if ($sugarbean->is_template){
    header("Location: index.php?action=ProjectTemplatesDetailView&module=Project&record=$return_id&return_module=Project&return_action=ProjectTemplatesEditView");
}
else{
    handleRedirect($return_id,'Project');
}
?>
