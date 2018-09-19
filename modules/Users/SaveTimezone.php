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
global $current_user;
global $sugar_config;

if(isset($_POST['timezone']) || isset($_GET['timezone'])) {
    if(isset($_POST['timezone'])) { 
    	$timezone = $_POST['timezone'];
    } else {
    	$timezone = $_GET['timezone'];
    }

	$current_user->setPreference('timezone', $timezone);
	$current_user->setPreference('ut', 1);
	$current_user->savePreferencesToDB();
	session_write_close();
	require_once('modules/Users/password_utils.php');
	if((($GLOBALS['sugar_config']['passwordsetting']['userexpiration'] > 0) &&
        	$_SESSION['hasExpiredPassword'] == '1'))
        header('Location: index.php?module=Users&action=ChangePassword');
    else
	   header('Location: index.php?action=index&module=Home');
   exit();
}
?>
