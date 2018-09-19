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


//Create User Teams
$globalteam = BeanFactory::getBean('Teams', '1');
if(isset($globalteam->name)){
    echo 'Global '.$mod_strings['LBL_UPGRADE_TEAM_EXISTS'].'<br>';
    if($globalteam->deleted) {
        $globalteam->mark_undeleted($globalteam->id);
    }
} else {
    $globalteam->create_team("Global", $mod_strings['LBL_GLOBAL_TEAM_DESC'], $globalteam->global_team);
}

$results = $GLOBALS['db']->query("SELECT id, user_name FROM users WHERE default_team != '' AND default_team IS NOT NULL
    AND user_name NOT IN (" . $GLOBALS['db']->quoted(SugarSNIP::SNIP_USER) . ", 'SugarCustomerSupportPortalUser')");

$team = BeanFactory::newBean('Teams');
$user = BeanFactory::newBean('Users');
while($row = $GLOBALS['db']->fetchByAssoc($results)) {
	$results2 = $GLOBALS['db']->query("SELECT id, name FROM teams WHERE associated_user_id = '" . $row['id'] . "'");
	$row2 = $GLOBALS['db']->fetchByAssoc($results2);
	if(empty($row2['id'])) {
		$user->retrieve($row['id']);
		$team->new_user_created($user);
		// BUG 10339: do not display messages for upgrade wizard
		if(!isset($_REQUEST['upgradeWizard'])){
			echo $mod_strings['LBL_UPGRADE_TEAM_CREATE'].' '. $row['user_name']. '<br>';
		}
	}else{
		echo $row2['name'] .' '.$mod_strings['LBL_UPGRADE_TEAM_EXISTS'].'<br>';
	}

	$globalteam->add_user_to_team($row['id']);
}

echo '<br>' . $mod_strings['LBL_DONE'];
