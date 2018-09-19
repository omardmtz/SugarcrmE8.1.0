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

//////////////////////////////////////////////////////////////////////////////////////////
//// This is a stand alone file that can be run from the command prompt for upgrading a
//// Sugar Instance. Three parameters are required to be defined in order to execute this file.
//// php.exe -f silentUpgrade.php {Path to Upgrade Package zip} {Path to Log file} {Path to Instance} {Admin User}
//// argv[1] = ZIP file
//// argv[2] = Log file
//// argv[3] = Instance dir
//// argv[4] = Admin user
//// See below the Usage for more details.
//// UPGRADE STEP 1:
//// - Check args
//// - Check preflight settings
//// - Check upgrade validity
//// - Unzip upgrade package
//// - Backup files
//// - Run pre-install
//// - Run 3-way merges
/////////////////////////////////////////////////////////////////////////////////////////
ini_set('memory_limit',-1);
ini_set('error_reporting', E_ALL & ~E_STRICT & ~E_DEPRECATED);

///////////////////////////////////////////////////////////////////////////////

function verifyArguments($argv,$usage_regular)
{
    if(count($argv) < 5) {
                echo "*******************************************************************************\n";
                echo "*** ERROR: Missing required parameters.  Received ".(count($argv)-1)." argument(s), require 4.\n";
                echo $usage_regular;
                echo "FAILURE\n";
                exit(1);
    }
    $upgradeType = '';
	$cwd = getcwd(); // default to current, assumed to be in a valid SugarCRM root dir.
	if(isset($argv[3]) && is_dir($argv[3])) {
			$cwd = $argv[3];
			chdir($cwd);
	} else {
			echo "*******************************************************************************\n";
			echo "*** ERROR: 3rd parameter must be a valid directory. \n";
			exit(1);
	}

	if(is_file("{$cwd}/include/entryPoint.php")) {
    		//this should be a regular sugar install
    		$upgradeType = constant('SUGARCRM_INSTALL');
    		//check if this is a valid zip file
	    	if(!is_file($argv[1])) { // valid zip?
		        echo "*******************************************************************************\n";
                echo "*** ERROR: First argument must be a full path to the patch file. Got [ {$argv[1]} ].\n";
                echo $usage_regular;
                echo "FAILURE\n";
                exit(1);
		    }
     } else {
            //this should be a regular sugar install
            echo "*******************************************************************************\n";
            echo "*** ERROR: Tried to execute in a non-SugarCRM root directory.\n";
            exit(1);
    }

    return $upgradeType;
}

function prepSystemForUpgradeSilent()
{
	global $subdirs;
	global $cwd;
	global $sugar_config;

	// make sure dirs exist
	foreach($subdirs as $subdir) {
		if(!is_dir($sugar_config['upload_dir']."/upgrades/{$subdir}")) {
			mkdir_recursive($sugar_config['upload_dir']."/upgrades/{$subdir}");
		}
	}
	$base_tmp_upgrade_dir = sugar_cached("upgrades/temp");

	if(file_exists($base_tmp_upgrade_dir.'/upgrade_progress.php')) {
	    unlink($base_tmp_upgrade_dir.'/upgrade_progress.php');
	}
}



//Bug 52872. Dies if the request does not come from CLI.
$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    die("This is command-line only script");
}
//End of #52872

// only run from command line
if(isset($_SERVER['HTTP_USER_AGENT'])) {
	fwrite(STDERR,'This utility may only be run from the command line or command prompt.');
	exit(1);
}
//Clean_string cleans out any file  passed in as a parameter
$_SERVER['PHP_SELF'] = 'silentUpgrade.php';

$usage_regular =<<<eoq2
Usage: php.exe -f silentUpgrade.php [upgradeZipFile] [logFile] [pathToSugarInstance] [admin-user]

On Command Prompt Change directory to where silentUpgrade.php resides. Then type path to
php.exe followed by -f silentUpgrade.php and the arguments.

Example:
    [path-to-PHP/]php.exe -f silentUpgrade.php [path-to-upgrade-package/]SugarEnt-Upgrade-5.2.0-to-5.5.0.zip [path-to-log-file/]silentupgrade.log  [path-to-sugar-instance/] admin

Arguments:
    upgradeZipFile                       : Upgrade package file.
    logFile                              : Silent Upgarde log file.
    pathToSugarInstance                  : Sugar Instance instance being upgraded.
    admin-user                           : admin user performing the upgrade
eoq2;
////	END USAGE
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	STANDARD REQUIRED SUGAR INCLUDES AND PRESETS
if(!defined('sugarEntry')) define('sugarEntry', true);

$_SESSION = array();
$_SESSION['schema_change'] = 'sugar'; // we force-run all SQL
$_SESSION['silent_upgrade'] = true;
$_SESSION['step'] = 'silent'; // flag to NOT try redirect to 4.5.x upgrade wizard

$_REQUEST = array();

define('SUGARCRM_INSTALL', 'SugarCRM_Install');
define('SUGARCRM_PRE_INSTALL_FILE', 'scripts/pre_install.php');

global $cwd;
$cwd = getcwd(); // default to current, assumed to be in a valid SugarCRM root dir.
touch($argv[2]);
$path			= realpath($argv[2]); // custom log file, if blank will use ./upgradeWizard.log

$upgradeType = verifyArguments($argv,$usage_regular);

///////////////////////////////////////////////////////////////////////////////
//////  Verify that all the arguments are appropriately placed////////////////
global $sugar_config;
$errors = array();
define('ENTRY_POINT_TYPE', 'api');
require_once('include/entryPoint.php');
require_once('include/utils/zip_utils.php');
$cwd = $argv[3];

$GLOBALS['log']	= LoggerManager::getLogger('SugarCRM');
$patchName		= basename($argv[1]);
$zip_from_dir	= substr($patchName, 0, strlen($patchName) - 4); // patch folder name (minus ".zip")

$db				= DBManagerFactory::getInstance();
$UWstrings		= return_module_language('en_us', 'UpgradeWizard');
$adminStrings	= return_module_language('en_us', 'Administration');
$app_list_strings = return_app_list_strings_language('en_us');
$mod_strings	= array_merge($adminStrings, $UWstrings);
$subdirs		= array('full', 'langpack', 'module', 'patch', 'theme', 'temp');

global $unzip_dir;

//////////////////////////////////////////////////////////////////////////////
//Adding admin user to the silent upgrade

$current_user = new User();
if(isset($argv[4])) {
    //if being used for internal upgrades avoid admin user verification
	$user_name = $argv[4];
	$q = "select id from users where user_name = '" . $user_name . "' and is_admin=1";
	$result = $db->query($q, false);
	$logged_user = $db->fetchByAssoc($result);
/////retrieve admin user
	if(empty($logged_user['id']) && $logged_user['id'] != null){
	   	echo "FAILURE: Not an admin user in users table. Please provide an admin user\n";
		exit(1);
	}
} else {
		echo "*******************************************************************************\n";
		echo "*** ERROR: 4th parameter must be a valid admin user.\n";
		echo $usage;
		echo "FAILURE\n";
		exit(1);
}


global $sugar_config;
$configOptions = $sugar_config['dbconfig'];

echo "\n";
echo "********************************************************************\n";
echo "************ This Upgrade process may take some time ***************\n";
echo "********************************************************************\n";
echo "\n";

///////////////////////////////////////////////////////////////////////////////
////	UPGRADE PREP
prepSystemForUpgradeSilent();

$unzip_dir = sugar_cached("upgrades/temp");
$install_file = $sugar_config['upload_dir']."/upgrades/patch/".basename($argv[1]);

$_SESSION['unzip_dir'] = $unzip_dir;
$_SESSION['install_file'] = $install_file;
$_SESSION['zip_from_dir'] = $zip_from_dir;
if(is_dir($unzip_dir.'/scripts'))
{
	rmdir_recursive($unzip_dir.'/scripts');
}
if(is_file($unzip_dir.'/manifest.php'))
{
	rmdir_recursive($unzip_dir.'/manifest.php');
}
mkdir_recursive($unzip_dir);
if(!is_dir($unzip_dir)) {
	echo "\n{$unzip_dir} is not an available directory\nFAILURE\n";
	exit(1);
}
unzip($argv[1], $unzip_dir);
// check that data was unpacked
$zipBasePath = "$unzip_dir/{$zip_from_dir}";
if(!is_file("$zipBasePath/sugar_version.php")) {
    echo "\n$cwd/{$zipBasePath}/sugar_version.php was not extracted\nFAILURE\n";
    exit(1);
}
// mimic standard UW by copy patch zip to appropriate dir
copy($argv[1], $install_file);
////	END UPGRADE PREP
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	MAKE SURE PATCH IS COMPATIBLE
if(is_file("$unzip_dir/manifest.php")) {

    //Check if uw_utils.php exists in zip package, fall back to existing file if not found (needed for flavor conversions)
    if(file_exists("{$zipBasePath}/modules/UpgradeWizard/uw_utils.php")) {
        require_once("{$zipBasePath}/modules/UpgradeWizard/uw_utils.php");
    } else {
        require_once("modules/UpgradeWizard/uw_utils.php");
    }

    // provides $manifest array
	include("$unzip_dir/manifest.php");
	if(!isset($manifest)) {
		echo "\nThe patch did not contain a proper manifest.php file.  Cannot continue.\nFAILURE\n";
		exit(1);
	} else {
		copy("$unzip_dir/manifest.php", $sugar_config['upload_dir']."/upgrades/patch/{$zip_from_dir}-manifest.php");
		$error = validate_manifest($manifest);
		if(!empty($error)) {
			$error = strip_tags(br2nl($error));
			echo "\n{$error}\n\nFAILURE\n";
			exit(1);
		}
	}
} else {
	echo "\nThe patch did not contain a proper manifest.php file.  Cannot continue.\nFAILURE\n";
	exit(1);
}

logThis("**** Upgrade checks passed", $path);
///// DONE WITH CHECKS
///////////////////////////////////////////////////////////////////////////////
////  BACKUP FILES
$rest_dir = remove_file_extension($install_file) . "-restore";
$errors = commitMakeBackupFiles($rest_dir, $install_file, $unzip_dir, $zip_from_dir, array(), $path);
logThis("**** Backup complete", $path);

///////////////////////////////////////////////////////////////////////////////
////	HANDLE PREINSTALL SCRIPTS
if(empty($errors)) {
    logThis("**** Pre-install scripts ", $path);
    $file = "{$unzip_dir}/".constant('SUGARCRM_PRE_INSTALL_FILE');

	if(is_file($file)) {
		require($file);
		pre_install();
	}
    logThis("**** Pre-install complete", $path);
}
