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
 * $Header$
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$folder = new SugarFolder();
$_REQUEST['name'] = $_REQUEST['groupFolderAddName'];
$_REQUEST['parent_folder'] = $_REQUEST['groupFoldersAdd'];
$_REQUEST['group_id'] = $_REQUEST['groupFoldersUser'];
$_REQUEST['team_id'] = $_REQUEST['primaryTeamId'];
$teamSet = BeanFactory::newBean('TeamSets');
$teamIds = explode(",", $_REQUEST['teamIds']);
$team_set_id = $teamSet->addTeams($teamIds);
$_REQUEST['team_set_id'] = $team_set_id;
if (empty($_REQUEST['record'])) {
	$folder->setFolder($_REQUEST);
} else {
	$folder->updateFolder($_REQUEST);
}
$body1 = "
	<script type='text/javascript'>
		function refreshOpener() {
			window.opener.refresh_group_folder_list('$folder->id','$folder->name')
			window.close();
		} // fn
		refreshOpener();
	</script>";
echo  $body1;
?>
