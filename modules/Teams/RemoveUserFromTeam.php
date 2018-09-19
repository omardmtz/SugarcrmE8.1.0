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




global $mod_strings;
global $current_user;


if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die("Unauthorized access to administration.");

$focus = BeanFactory::newBean('Teams');

if(!isset($_REQUEST['team_record']) || !isset($_REQUEST['record'])) {
	sugar_die($mod_strings['ERR_DELETE_RECORD']);
}
else {
	$focus->retrieve($_REQUEST['team_record']);
}

$focus->remove_user_from_team($_REQUEST['record']);

header("Location: index.php?module={$_REQUEST['return_module']}&action={$_REQUEST['return_action']}&record={$_REQUEST['return_id']}");
?>
