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

global $mod_strings;
global $app_strings;
global $current_user;

if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die("Unauthorized access to administration.");

$error_message = '';

if(isset($_REQUEST['team_id']) && isset($_REQUEST['teams'])) {
    /** @var Team $new_team */
	$new_team = BeanFactory::getBean('Teams', $_REQUEST['team_id']);
	
	//Grab the list of teams to reassign
	$old_teams = explode(",", $_REQUEST['teams']);
	
	if(!in_array($new_team->id, $old_teams)) {
		unset($_SESSION['REASSIGN_TEAMS']);
		
		//Call method to reassign the teams
		$new_team->reassign_team_records($old_teams);
		
		//Redirect to listview
		header("Location: index.php?module=Teams&action=index");
		return;
	}
	$error_message = string_format($mod_strings['ERR_INVALID_TEAM_REASSIGNMENT'], array(Team::getDisplayName($new_team->name, $new_team->name_2, false)));
}
	
$teams = array();
$focus = BeanFactory::newBean('Teams');

if (isset($_SESSION['REASSIGN_TEAMS'])) {
    foreach ($_SESSION['REASSIGN_TEAMS'] as $team_id) {
        $focus->retrieve($team_id);
        if ($team_id == $focus->global_team) {
            unset($_SESSION['REASSIGN_TEAMS']);
            $error_message = $app_strings['LBL_MASSUPDATE_DELETE_GLOBAL_TEAM'];
            SugarApplication::appendErrorMessage($error_message);
            header('Location: index.php?module=Teams&action=index');
            return;
        } else {
            $teams[$team_id] = $focus->name;
        }
    }
} else {
    if (isset($_REQUEST['record'])) {
        $focus->retrieve($_REQUEST['record']);
        if ($focus->id == $focus->global_team) {
            unset($_SESSION['REASSIGN_TEAMS']);
            $error_message = $app_strings['LBL_MASSUPDATE_DELETE_GLOBAL_TEAM'];
            SugarApplication::appendErrorMessage($error_message);
            header('Location: index.php?module=Teams&action=index');
            return;
        } else {
            $teams[$focus->id] = $focus->name;
        }
    }
}

if(empty($teams) && !isset($error_message)) {
  $GLOBALS['log']->fatal("No teams to reassign for operation");
  header("Location: index.php?module=Teams&action=index");
} else {	
  $ss = new Sugar_Smarty();
  $team_list = '('. implode("), (", $teams) . ')';
  $ss->assign("TITLE", string_format($mod_strings['LBL_REASSIGN_TEAM_TITLE'], array($team_list)));
  $ss->assign("ERROR_MESSAGE", $error_message);
  $ss->assign("TEAMS", implode(",", array_keys($teams)));
  $ss->assign("MOD_STRINGS", $mod_strings);
  $ss->assign("APP_STRINGS", $app_strings);
  $ss->display("modules/Teams/tpls/ReassignTeam.tpl");
}
