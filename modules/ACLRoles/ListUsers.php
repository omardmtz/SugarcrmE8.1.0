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
if(!$GLOBALS['current_user']->isAdminForModule('Users')){
	sugar_die('No Access');
}
$record = '';
if(isset($_REQUEST['record'])) $record = $_REQUEST['record'];
?>
<form action="index.php" method="post" name="DetailView" id="form">

			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="user_id" value="">
			<input type="hidden" name="record" value="<?php echo $record; ?>">
			<input type="hidden" name="isDuplicate" value=''>
			
			
			<input type="hidden" name="action">
</form>

<?php

$users = get_user_array(true, "Active", $record);
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'],array($mod_strings['LBL_MODULE_NAME']), true);
echo "<form name='Users'>
<input type='hidden' name='action' value='ListRoles'>
<input type='hidden' name='module' value='Users'>
<select name='record' onchange='document.Users.submit();'>";
echo get_select_options_with_id($users, $record);
echo "</select></form>";
if(!empty($record)){
    $hideTeams = true; // to not show the teams subpanel in the following file
	require_once('modules/ACLRoles/DetailUserRole.php');
}


?>