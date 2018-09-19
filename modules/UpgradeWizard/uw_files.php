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



global $sugar_version;

///////////////////////////////////////////////////////////////////////////////
////	DYNAMICALLY GENERATE UPGRADEWIZARD MODULE FILE LIST
$uwFilesCurrent = findAllFiles('modules/UpgradeWizard/', array());

// handle 4.x to 4.5.x+ (no UpgradeWizard module)
if(count($uwFilesCurrent) < 5) {
	$uwFiles = array(
		'modules/UpgradeWizard/language/en_us.lang.php',
		'modules/UpgradeWizard/cancel.php',
		'modules/UpgradeWizard/commit.php',
		'modules/UpgradeWizard/commitJson.php',
		'modules/UpgradeWizard/end.php',
		'modules/UpgradeWizard/Forms.php',
		'modules/UpgradeWizard/index.php',
		'modules/UpgradeWizard/Menu.php',
		'modules/UpgradeWizard/preflight.php',
		'modules/UpgradeWizard/preflightJson.php',
		'modules/UpgradeWizard/start.php',
		'modules/UpgradeWizard/su_utils.php',
		'modules/UpgradeWizard/su.php',
		'modules/UpgradeWizard/systemCheck.php',
		'modules/UpgradeWizard/systemCheckJson.php',
		'modules/UpgradeWizard/upgradeWizard.js',
		'modules/UpgradeWizard/upload.php',
		'modules/UpgradeWizard/uw_ajax.php',
		'modules/UpgradeWizard/uw_files.php',
		'modules/UpgradeWizard/uw_main.tpl',
		'modules/UpgradeWizard/uw_utils.php',
	);
} else {
	$uwFilesCurrent = findAllFiles('ModuleInstall', $uwFilesCurrent);
	$uwFilesCurrent = findAllFiles('include/javascript/yui', $uwFilesCurrent);
	$uwFilesCurrent[] = 'HandleAjaxCall.php';

	$uwFiles = array();
	foreach($uwFilesCurrent as $file) {
		$uwFiles[] = str_replace("./", "", clean_path($file));
	}
}
////	END DYNAMICALLY GENERATE UPGRADEWIZARD MODULE FILE LIST
///////////////////////////////////////////////////////////////////////////////

$uw_files = array(
    // standard files we steamroll with no warning
    'log4php.properties',
    'include/utils/encryption_utils.php',
    'include/utils.php',
    'include/language/en_us.lang.php',
    'include/modules.php',
    'include/Localization/Localization.php',
    'install/language/en_us.lang.php',
    'vendor/XTemplate/xtpl.php',
    'include/database/DBHelper.php',
    'include/database/DBManager.php',
    'include/database/DBManagerFactory.php',
    'include/database/MssqlHelper.php',
    'include/database/MssqlManager.php',
    'include/database/MysqlHelper.php',
    'include/database/MysqlManager.php',
    'include/database/DBManagerFactory.php',
    'include/database/OracleHelper.php',
    'include/database/OracleManager.php',
);

$uw_files = array_merge($uw_files, $uwFiles);

