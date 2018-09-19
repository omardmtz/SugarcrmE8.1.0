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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $current_user;

if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die("Unauthorized access to administration.");

global $mod_strings;
global $app_strings;


$focus = BeanFactory::getBean('Teams', $_REQUEST['record']);

// Fix for RS-1267 - We should not allow to remove Global team or reassign users in it
if ($focus->isGlobalTeam()) {
    $errorMessage = $app_strings['LBL_MASSUPDATE_DELETE_GLOBAL_TEAM'];
    $GLOBALS['log']->fatal($errorMessage);
    SugarApplication::appendErrorMessage($errorMessage);
    header("Location: index.php?module=Teams&action=index");
    die();
}

//Check if there are module records where this team is assigned to in a team_set_id
//if so, redirect to prompt the Administrator to select a new team
if($focus->has_records_in_modules()) {
   header("Location: index.php?module=Teams&action=ReassignTeams&record={$focus->id}");
} else {
	
	//Check if the associated user is deleted
	$user = BeanFactory::getBean('Users', $focus->associated_user_id);
	if($focus->private == 1 && (!empty($user->id) && $user->deleted != 1))
	{
		$msg = string_format($GLOBALS['app_strings']['LBL_MASSUPDATE_DELETE_USER_EXISTS'], array(Team::getDisplayName($focus->name, $focus->name_2), $user->full_name));
		$GLOBALS['log']->error($msg);
        SugarApplication::appendErrorMessage($msg);
		header('Location: index.php?module=Teams&action=DetailView&record='.$focus->id);
		return;
	}

	//Call mark_deleted function
    $focus->mark_deleted($focus->id);
	header("Location: index.php?module=Teams&action=index");	
}
