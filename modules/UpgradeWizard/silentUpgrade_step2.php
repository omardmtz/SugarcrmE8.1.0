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
//// UPGRADE STEP 2:
//// - Copy files
//// - Run pre-db upgrades, config upgrades, etc.
/////////////////////////////////////////////////////////////////////////////////////////
ini_set('memory_limit',-1);
ini_set('error_reporting', E_ALL & ~E_STRICT & ~E_DEPRECATED);

//Clean_string cleans out any file  passed in as a parameter
$_SERVER['PHP_SELF'] = 'silentUpgrade.php';

///////////////////////////////////////////////////////////////////////////////
////	STANDARD REQUIRED SUGAR INCLUDES AND PRESETS
if(!defined('sugarEntry')) define('sugarEntry', true);

$_SESSION = array();
$_SESSION['schema_change'] = 'sugar'; // we force-run all SQL
$_SESSION['silent_upgrade'] = true;
$_SESSION['step'] = 'silent'; // flag to NOT try redirect to 4.5.x upgrade wizard

$_REQUEST = array();
$_REQUEST['addTaskReminder'] = 'remind';

define('SUGARCRM_INSTALL', 'SugarCRM_Install');
$upgradeType = 'SugarCRM_Install';

global $cwd;
$cwd = getcwd(); // default to current, assumed to be in a valid SugarCRM root dir.

///////////////////////////////////////////////////////////////////////////////
//////  Verify that all the arguments are appropriately placed////////////////

define('SUGARCRM_PRE_INSTALL_FILE', 'scripts/pre_install.php');
define('SUGARCRM_POST_INSTALL_FILE', 'scripts/post_install.php');
define('SUGARCRM_PRE_UNINSTALL_FILE', 'scripts/pre_uninstall.php');
define('SUGARCRM_POST_UNINSTALL_FILE', 'scripts/post_uninstall.php');

set_time_limit(0);
ob_start();
$errors = array();
touch($argv[2]);
$path = realpath($argv[2]); // custom log file, if blank will use ./upgradeWizard.log

$cwd = $argv[3];
chdir($cwd);
global $sugar_config;
require('config.php');
require('sugar_version.php');
//// Minimal files for copy/upgrade
require_once("include/dir_inc.php");
require_once("include/utils/sugar_file_utils.php");
require_once("include/utils/file_utils.php");
require_once("include/utils.php");

$_SERVER['SERVER_SOFTWARE'] = '';

$unzip_dir = sugar_cached("upgrades/temp");
$install_file = $sugar_config['upload_dir']."/upgrades/patch/".basename($argv[1]);
$patchName		= basename($argv[1]);
$zip_from_dir	= substr($patchName, 0, strlen($patchName) - 4); // patch folder name (minus ".zip")

$_SESSION['unzip_dir'] = $unzip_dir;
$_SESSION['install_file'] = $install_file;
$_SESSION['zip_from_dir'] = $zip_from_dir;

//If this is a flavor conversion going from 6.7.0 => 6.7.0, we make sure to load the autoloader.php file
if(version_compare($sugar_version, '6.7.0', '=')) {
    require_once('include/utils/autoloader.php');
}

/// load old modules
$oldModuleList = array();
include('include/modules.php');
$oldModuleList = $moduleList;

$GLOBALS['log']	= new FakeLogger($path);

///////////////////////////////////////////////////////////////////////////////
////	UPGRADE UPGRADEWIZARD

$zipBasePath = "$unzip_dir/{$zip_from_dir}";
$uwFiles = findAllFiles("{$zipBasePath}/modules/UpgradeWizard", array());
$destFiles = array();
$newDirs = array();

foreach($uwFiles as $uwFile) {
	$destFile = str_replace($zipBasePath."/", '', $uwFile);

    $dir = dirname($destFile);

    //Ensure that the parent directory exists
    if(!isset($newDirs[$dir]) && !file_exists($dir)) {
       mkdir_recursive($dir);
       $newDirs[$dir] = true;
    }

	copy($uwFile, $destFile);
}

require_once('modules/UpgradeWizard/uw_utils.php'); // This is the NEW uw_utils.php file

removeSilentUpgradeVarsCache(); // Clear the silent upgrade vars - Note: Any calls to these functions within this file are removed here
logThis("*** SILENT UPGRADE INITIATED.", $path);
logThis("*** UpgradeWizard Upgraded  ", $path);
////	END UPGRADE UPGRADEWIZARD
///////////////////////////////////////////////////////////////////////////////
set_upgrade_vars();
initialize_session_vars();

// Load manifest
require("$unzip_dir/manifest.php");

$ce_to_pro_ent = isset($manifest['name']) && preg_match('/^SugarCE.*?(Pro|Ent|Corp|Ult)$/', $manifest['name']);
$_SESSION['upgrade_from_flavor'] = $manifest['name'];

global $sugar_config;
global $sugar_version;
global $sugar_flavor;

///////////////// START COPY PHASE OF UPGRADE

////////////////COMMIT PROCESS BEGINS///////////////////////////////////////////////////////////////

////	MAKE BACKUPS OF TARGET FILES

	///////////////////////////////////////////////////////////////////////////////
	////	COPY NEW FILES INTO TARGET INSTANCE
$split = commitCopyNewFiles($unzip_dir, $zip_from_dir, $path);
$copiedFiles = $split['copiedFiles'];
$skippedFiles = $split['skippedFiles'];
logThis("*** File copy completed  ", $path);

//Clean smarty from cache
$cachedir = sugar_cached('smarty');
if(is_dir($cachedir)){
	$allModFiles = array();
	$allModFiles = findAllFiles($cachedir,$allModFiles);
	foreach($allModFiles as $file){
		if(file_exists($file)){
			unlink($file);
		}
	}
}

///////// HERE ALL FILES HAVE BEEN COPIED
/// We can load some environment now
define('ENTRY_POINT_TYPE', 'api');
require_once('include/entryPoint.php');

$GLOBALS['log']	= LoggerManager::getLogger('SugarCRM');
$db				= DBManagerFactory::getInstance();
$UWstrings		= return_module_language('en_us', 'UpgradeWizard');
$adminStrings	= return_module_language('en_us', 'Administration');
$app_list_strings = return_app_list_strings_language('en_us');
$mod_strings	= array_merge($adminStrings, $UWstrings);
$subdirs		= array('full', 'langpack', 'module', 'patch', 'theme', 'temp');

/// Update upgrader vars
require_once($unzip_dir.'/scripts/upgrade_utils.php');
$new_sugar_version = getUpgradeVersion();
$siv_varset_1 = setSilentUpgradeVar('origVersion', $sugar_version);
$siv_varset_2 = setSilentUpgradeVar('destVersion', $new_sugar_version);
$siv_write    = writeSilentUpgradeVars();
if(!$siv_varset_1 || !$siv_varset_2 || !$siv_write){
	logThis("Error with silent upgrade variables: origVersion write success is ({$siv_varset_1}) ".
			"-- destVersion write success is ({$siv_varset_2}) -- ".
			"writeSilentUpgradeVars success is ({$siv_write}) -- ".
			"path to cache dir is ({$GLOBALS['sugar_config']['cache_dir']})", $path);
}
logThis("*** UpgradeWizard variables updated  ", $path);

//////////////////////////////////////////////////////////////////////////////
//Adding admin user to the silent upgrade

$current_user = new User();
$user_name = $argv[4];
$q = "select id from users where user_name = '" . $user_name . "' and is_admin=1";
$result = $db->query($q, false);
$logged_user = $db->fetchByAssoc($result);
$current_user->retrieve($logged_user['id']);
$configOptions = $sugar_config['dbconfig'];

//repair tabledictionary.ext.php file if needed
repairTableDictionaryExtFile();

if($configOptions['db_type'] == 'mysql'){
	//Change the db wait_timeout for this session
	$now_timeout = $db->getOne("select @@wait_timeout");
	logThis('Wait Timeout before change ***** '.$now_timeout , $path);
	$db->query("set wait_timeout=28800");
	$now_timeout = $db->getOne("select @@wait_timeout");
	logThis('Wait Timeout after change ***** '.$now_timeout , $path);
}
SugarAutoLoader::buildCache();

///////////////////////////////////////////////////////////////////////////////
////	RUN THE REST OF THE UPGRADE

require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
///////////////////////////////////////////////////////////////////////////////
///    RELOAD NEW DEFINITIONS
global $ACLActions, $beanList, $beanFiles;
include('modules/ACLActions/actiondefs.php');
include('include/modules.php');
/////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	HANDLE POSTINSTALL SCRIPTS
if(empty($errors)) {
		logThis('Starting post_install()...', $path);

		$trackerManager = TrackerManager::getInstance();
        $trackerManager->pause();
        $trackerManager->unsetMonitors();

		if(!didThisStepRunBefore('commit','post_install')){
			$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
			if(is_file($file)) {
				$progArray['post_install']='in_progress';
				post_install_progress($progArray,'set');
				    global $moduleList;
					include($file);
					post_install();
				// cn: only run conversion if admin selects "Sugar runs SQL"
				if(!empty($_SESSION['allTables']) && $_SESSION['schema_change'] == 'sugar') {
					executeConvertTablesSql($_SESSION['allTables']);
                }
				//set process to done
				$progArray['post_install']='done';
				post_install_progress($progArray,'set');
			}
		}
		//clean vardefs
		logThis('Performing UWrebuild()...', $path);
		ob_start();
		@UWrebuild();
		ob_end_clean();
		logThis('UWrebuild() done.', $path);

		logThis('begin check default permissions .', $path);
	    checkConfigForPermissions();
	    logThis('end check default permissions .', $path);

	    logThis('begin check logger settings .', $path);
	    checkLoggerSettings();
	    logThis('begin check logger settings .', $path);

        logThis('begin check lead conversion settings .', $path);
        checkLeadConversionSettings();
	    logThis('end check lead conversion settings .', $path);

	    logThis('begin check resource settings .', $path);
		checkResourceSettings();
		logThis('begin check resource settings .', $path);

		require("sugar_version.php");
		require('config.php');

		if($ce_to_pro_ent){
			if(isset($sugar_config['sugarbeet']))
			{
			    //$sugar_config['sugarbeet'] is only set in COMM
			    unset($sugar_config['sugarbeet']);
			}
		    if(isset($sugar_config['disable_team_access_check']))
			{
			    //$sugar_config['disable_team_access_check'] is a runtime configration,
			    //no need to write to config.php
			    unset($sugar_config['disable_team_access_check']);
			}
			logThis('Running merge_passwordsetting', $path);
			if(!merge_passwordsetting($sugar_config, $sugar_version)) {
				logThis('*** ERROR: could not write config.php! - upgrade will fail!', $path);
				$errors[] = 'Could not write config.php!';
			}
			logThis('Done merge_passwordsetting', $path);
		}

        if (version_compare($sugar_version, '6.7.0', '<') && isset($sugar_config['default_theme']) && $sugar_config['default_theme'] == 'Sugar5') {
            logThis('Set default_theme to RacerX', $path);
            require_once('modules/Configurator/Configurator.php');
            $configurator = new Configurator();
            $configurator->config['default_theme'] = 'RacerX';
            $configurator->handleOverride();
        }

		if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) ) {
            logThis('*** ERROR: could not write config.php! - upgrade will fail!', $path);
            $errors[] = 'Could not write config.php!';
        }

        logThis('Set default_max_tabs to 7', $path);
		$sugar_config['default_max_tabs'] = '7';

		logThis('Upgrade the sugar_version', $path);
		$sugar_config['sugar_version'] = $sugar_version;

		ksort($sugar_config);

		if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) ) {
            logThis('*** ERROR: could not write config.php! - upgrade will fail!', $path);
            $errors[] = 'Could not write config.php!';
        }

		logThis('post_install() done.', $path);
}

///////////////////////////////////////////////////////////////////////////////
////	REGISTER UPGRADE
if(empty($errors)) {
		logThis('Registering upgrade with UpgradeHistory', $path);
		if(!didThisStepRunBefore('commit','upgradeHistory')){
			$file_action = "copied";
			// if error was encountered, script should have died before now
			$new_upgrade = new UpgradeHistory();
			$new_upgrade->filename = $install_file;
			$new_upgrade->md5sum = md5_file($install_file);
			$new_upgrade->name = $zip_from_dir;
			$new_upgrade->description = $manifest['description'];
			$new_upgrade->type = 'patch';
			$new_upgrade->version = $sugar_version;
			$new_upgrade->status = "installed";
			$new_upgrade->manifest = (!empty($_SESSION['install_manifest']) ? $_SESSION['install_manifest'] : '');

			if($new_upgrade->description == null){
				$new_upgrade->description = "Silent Upgrade was used to upgrade the instance";
			}
			else{
				$new_upgrade->description = $new_upgrade->description." Silent Upgrade was used to upgrade the instance.";
			}
		   $new_upgrade->save();
		   set_upgrade_progress('commit','done','commit','done');
		}
}

//Clean modules from cache
	    $cachedir = sugar_cached('smarty');
		if(is_dir($cachedir)){
			$allModFiles = array();
			$allModFiles = findAllFiles($cachedir,$allModFiles);
		   foreach($allModFiles as $file){
		       	if(file_exists($file)){
					unlink($file);
		       	}
		   }
		}
//delete cache/modules before rebuilding the relations
//Clean modules from cache
   	    $cachedir = sugar_cached('modules');
		if(is_dir($cachedir)){
			$allModFiles = array();
			$allModFiles = findAllFiles($cachedir,$allModFiles);
		   foreach($allModFiles as $file){
		       	if(file_exists($file)){
					unlink($file);
		       	}
		   }
		}

//delete cache/themes
		$cachedir = sugar_cached('themes');
		if(is_dir($cachedir)){
			$allModFiles = array();
			$allModFiles = findAllFiles($cachedir,$allModFiles);
		   foreach($allModFiles as $file){
		       	if(file_exists($file)){
					unlink($file);
		       	}
		   }
		}
    ob_start();
	if(!isset($_REQUEST['silent'])){
		$_REQUEST['silent'] = true;
	}
	else if(isset($_REQUEST['silent']) && $_REQUEST['silent'] != true){
		$_REQUEST['silent'] = true;
	}

	@createMissingRels();
	ob_end_clean();


/////////////////////////Old Logger settings///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

@deleteCache();

///////////////////////////////////////////////////////////////////////////////
if($ce_to_pro_ent){
	if(function_exists('upgradeDashletsForSalesAndMarketing')){
		logThis('Upgrading tracker dashlets for sales and marketing start .', $path);
		upgradeDashletsForSalesAndMarketing();
		logThis('Upgrading tracker dashlets for sales and marketing start .', $path);
	}
}

fix_report_relationships($path);

if($ce_to_pro_ent)
{
        //check to see if there are any new files that need to be added to systems tab
        //retrieve old modules list
        logThis('check to see if new modules exist',$path);
        $newModuleList = array();
        include('include/modules.php');
        $newModuleList = $moduleList;

        //include tab controller
        require_once('modules/MySettings/TabController.php');
        $newTB = new TabController();

        //make sure new modules list has a key we can reference directly
        $newModuleList = $newTB->get_key_array($newModuleList);
        $oldModuleList = $newTB->get_key_array($oldModuleList);

        //iterate through list and remove commonalities to get new modules
        foreach ($newModuleList as $remove_mod){
            if(in_array($remove_mod, $oldModuleList)){
                unset($newModuleList[$remove_mod]);
            }
        }

        $must_have_modules= array(
			  'Activities'=>'Activities',
        	  'Calendar'=>'Calendar',
        	  'Reports' => 'Reports',
			  'Quotes' => 'Quotes',
			  'Products' => 'Products',
			  'Forecasts' => 'Forecasts',
			  'Contracts' => 'Contracts',
        );
        $newModuleList = array_merge($newModuleList,$must_have_modules);

        //new modules list now has left over modules which are new to this install, so lets add them to the system tabs
        logThis('new modules to add are '.var_export($newModuleList,true),$path);

        //grab the existing system tabs
        $tabs = $newTB->get_system_tabs();

        //add the new tabs to the array
        foreach($newModuleList as $nm ){
          $tabs[$nm] = $nm;
        }

        //now assign the modules to system tabs
        $newTB->set_system_tabs($tabs);
        logThis('module tabs updated',$path);
}

//Also set the tracker settings if  flavor conversion ce->pro or ce->ent
if(isset($_SESSION['current_db_version']) && isset($_SESSION['target_db_version'])){
    if (version_compare($_SESSION['current_db_version'], $_SESSION['target_db_version'], '='))
    {
	    $_REQUEST['upgradeWizard'] = true;
	    ob_start();
			include('vendor/Smarty/internals/core.write_file.php');
		ob_end_clean();
	 	$db = DBManagerFactory::getInstance();
		if($ce_to_pro_ent){
	        //Also set license information
	        $admin = new Administration();
			$category = 'license';
			$value = 0;
			$admin->saveSetting($category, 'users', $value);
            $key = array('key', 'expire_date');
			$value = '';
			foreach($key as $k){
				$admin->saveSetting($category, $k, $value);
			}
		}
	}
}

$phpErrors = ob_get_contents();
ob_end_clean();
logThis("**** Potential PHP generated error messages: {$phpErrors}", $path);

if(count($errors) > 0) {
	foreach($errors as $error) {
		logThis("****** SilentUpgrade ERROR: {$error}", $path);
	}
	echo "FAILED\n";
}

///////////////////////////////////////////////////////////////////////////////
////	UTILITIES THAT MUST BE LOCAL :(
//Bug 24890, 24892. default_permissions not written to config.php. Following function checks and if
//no found then adds default_permissions to the config file.
function checkConfigForPermissions(){
	if(file_exists(getcwd().'/config.php')){
		require(getcwd().'/config.php');
	}
	global $sugar_config;
	if(!isset($sugar_config['default_permissions'])){
		$sugar_config['default_permissions'] = array (
				'dir_mode' => 02770,
				'file_mode' => 0660,
				'user' => '',
				'group' => '',
		);
		ksort($sugar_config);
		if(is_writable('config.php') && write_array_to_file("sugar_config", $sugar_config,'config.php')) {
			//writing to the file
		}
	}
}
function checkLoggerSettings(){
	if(file_exists('config.php')){
		require('config.php');
	}
	global $sugar_config;
	if(!isset($sugar_config['logger'])){
		$sugar_config['logger'] =array (
				'level'=>'fatal',
				'file' =>
				array (
						'ext' => '.log',
						'name' => 'sugarcrm',
						'dateFormat' => '%c',
						'maxSize' => '10MB',
						'maxLogs' => 10,
						'suffix' => '', // bug51583, change default suffix to blank for backwards comptability
				),
		);
		ksort($sugar_config);
		if(is_writable('config.php') && write_array_to_file("sugar_config", $sugar_config,'config.php')) {
			//writing to the file
		}
	}
}

function checkLeadConversionSettings() {
	if (file_exists('config.php')) {
		require('config.php');
	}
	global $sugar_config;
	if (!isset($sugar_config['lead_conv_activity_opt'])) {
		$sugar_config['lead_conv_activity_opt'] = 'copy';
		ksort($sugar_config);
		if (is_writable('config.php') && write_array_to_file("sugar_config", $sugar_config,'config.php')) {
			//writing to the file
		}
	}
}

function checkResourceSettings(){
	if(file_exists(getcwd().'/config.php')){
		require(getcwd().'/config.php');
	}
	global $sugar_config;
	if(!isset($sugar_config['resource_management'])){
		$sugar_config['resource_management'] =
		array (
				'special_query_limit' => 50000,
				'special_query_modules' =>
				array (
						0 => 'Reports',
						1 => 'Export',
						2 => 'Import',
						3 => 'Administration',
						4 => 'Sync',
				),
				'default_limit' => 1000,
		);
		ksort($sugar_config);
		if(is_writable('config.php') && write_array_to_file("sugar_config", $sugar_config,'config.php')) {
			//writing to the file
		}
	}
}


function createMissingRels()
{
	$relForObjects = array('leads'=>'Leads','campaigns'=>'Campaigns','prospects'=>'Prospects');
	foreach($relForObjects as $relObjName=>$relModName){
		//assigned_user
		$guid = create_guid();
		$query = "SELECT id FROM relationships WHERE relationship_name = '{$relObjName}_assigned_user'";
		$result= $GLOBALS['db']->query($query, true);
		$a = $GLOBALS['db']->fetchByAssoc($result);
		if(!isset($a['id']) && empty($a['id']) ){
			$qRel = "INSERT INTO relationships (id,relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, relationship_role_column, relationship_role_column_value, reverse, deleted)
			VALUES ('{$guid}', '{$relObjName}_assigned_user','Users','users','id','{$relModName}','{$relObjName}','assigned_user_id',NULL,NULL,NULL,'one-to-many',NULL,NULL,'0','0')";
			$GLOBALS['db']->query($qRel);
		}
		//modified_user
		$guid = create_guid();
		$query = "SELECT id FROM relationships WHERE relationship_name = '{$relObjName}_modified_user'";
		$result= $GLOBALS['db']->query($query, true);
		$a = $GLOBALS['db']->fetchByAssoc($result);
		if(!isset($a['id']) && empty($a['id']) ){
    		$qRel = "INSERT INTO relationships (id,relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, relationship_role_column, relationship_role_column_value, reverse, deleted)
    		VALUES ('{$guid}', '{$relObjName}_modified_user','Users','users','id','{$relModName}','{$relObjName}','modified_user_id',NULL,NULL,NULL,'one-to-many',NULL,NULL,'0','0')";
    		$GLOBALS['db']->query($qRel);
		}
		//created_by
		$guid = create_guid();
		$query = "SELECT id FROM relationships WHERE relationship_name = '{$relObjName}_created_by'";
		$result= $GLOBALS['db']->query($query, true);
		$a = $GLOBALS['db']->fetchByAssoc($result);
		if(!isset($a['id']) && empty($a['id']) ){
    		$qRel = "INSERT INTO relationships (id,relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, relationship_role_column, relationship_role_column_value, reverse, deleted)
    		VALUES ('{$guid}', '{$relObjName}_created_by','Users','users','id','{$relModName}','{$relObjName}','created_by',NULL,NULL,NULL,'one-to-many',NULL,NULL,'0','0')";
    		$GLOBALS['db']->query($qRel);
		}
		$guid = create_guid();
		$query = "SELECT id FROM relationships WHERE relationship_name = '{$relObjName}_team'";
		$result= $GLOBALS['db']->query($query, true);
		$a = $GLOBALS['db']->fetchByAssoc($result);
		if(!isset($a['id']) && empty($a['id']) ){
    		$qRel = "INSERT INTO relationships (id,relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, relationship_role_column, relationship_role_column_value, reverse, deleted)
    		VALUES ('{$guid}', '{$relObjName}_team','Teams','teams','id','{$relModName}','{$relObjName}','team_id',NULL,NULL,NULL,'one-to-many',NULL,NULL,'0','0')";
    		$GLOBALS['db']->query($qRel);
		}
	}
	//Also add tracker perf relationship
	$guid = create_guid();
	$query = "SELECT id FROM relationships WHERE relationship_name = 'tracker_monitor_id'";
	$result= $GLOBALS['db']->query($query, true);
	$a = $GLOBALS['db']->fetchByAssoc($result);
	if(!isset($a['id']) && empty($a['id']) ){
			$qRel = "INSERT INTO relationships (id,relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, relationship_role_column, relationship_role_column_value, reverse, deleted)
			VALUES ('{$guid}', 'tracker_monitor_id','TrackerPerfs','tracker_perf','monitor_id','Trackers','tracker','monitor_id',NULL,NULL,NULL,'one-to-many',NULL,NULL,'0','0')";
			$GLOBALS['db']->query($qRel);
	}
}


/**
 * This function will merge password default settings into config file
 * @param   $sugar_config
 * @param   $sugar_version
 * @return  bool true if successful
 */
function merge_passwordsetting($sugar_config, $sugar_version)
{
    $passwordsetting_defaults = array(
        'passwordsetting' => array(
            'minpwdlength' => '',
            'maxpwdlength' => '',
            'oneupper' => '',
            'onelower' => '',
            'onenumber' => '',
            'onespecial' => '',
            'SystemGeneratedPasswordON' => '',
            'generatepasswordtmpl' => '',
            'lostpasswordtmpl' => '',
            'customregex' => '',
            'regexcomment' => '',
            'forgotpasswordON' => false,
            'linkexpiration' => '1',
            'linkexpirationtime' => '30',
            'linkexpirationtype' => '1',
            'userexpiration' => '0',
            'userexpirationtime' => '',
            'userexpirationtype' => '1',
            'userexpirationlogin' => '',
            'systexpiration' => '0',
            'systexpirationtime' => '',
            'systexpirationtype' => '0',
            'systexpirationlogin' => '',
            'lockoutexpiration' => '0',
            'lockoutexpirationtime' => '',
            'lockoutexpirationtype' => '1',
            'lockoutexpirationlogin' => ''
        ));

    $sugar_config = sugarArrayMerge($passwordsetting_defaults, $sugar_config);

    // need to override version with default no matter what
    $sugar_config['sugar_version'] = $sugar_version;

    ksort($sugar_config);

    return write_array_to_file("sugar_config", $sugar_config, "config.php");
}

/**
 * repairTableDictionaryExtFile
 *
 * There were some scenarios in 6.0.x whereby the files loaded in the extension tabledictionary.ext.php file
 * did not exist.  This would cause warnings to appear during the upgrade.  As a result, this
 * function scans the contents of tabledictionary.ext.php and then remove entries where the file does exist.
 */
function repairTableDictionaryExtFile()
{
	$tableDictionaryExtDirs = array('custom/Extension/application/Ext/TableDictionary', 'custom/application/Ext/TableDictionary');

	foreach($tableDictionaryExtDirs as $tableDictionaryExt)
	{

		if(is_dir($tableDictionaryExt) && is_writable($tableDictionaryExt)){
			$dir = dir($tableDictionaryExt);
			while(($entry = $dir->read()) !== false)
			{
				$entry = $tableDictionaryExt . '/' . $entry;
				if(is_file($entry) && preg_match('/\.php$/i', $entry) && is_writeable($entry))
				{

						if(function_exists('sugar_fopen'))
						{
							$fp = @sugar_fopen($entry, 'r');
						} else {
							$fp = fopen($entry, 'r');
						}


					    if($fp)
				        {
				             $altered = false;
				             $contents = '';

				             while($line = fgets($fp))
						     {
						    	if(preg_match('/\s*include\s*\(\s*[\'|\"](.*?)[\"|\']\s*\)\s*;/', $line, $match))
						    	{
						    	   if(!file_exists($match[1]))
						    	   {
						    	      $altered = true;
						    	   } else {
						    	   	  $contents .= $line;
						    	   }
						    	} else {
						    	   $contents .= $line;
						    	}
						     }

						     fclose($fp);
				        }


					    if($altered)
					    {
							if(function_exists('sugar_fopen'))
							{
								$fp = @sugar_fopen($entry, 'w');
							} else {
								$fp = fopen($entry, 'w');
							}

							if($fp && fwrite($fp, $contents))
							{
								fclose($fp);
							}
					    }
				} //if
			} //while
		} //if
	}
}

class FakeLogger
{
    public $path;
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function __call($name, $args)
    {
        logThis("[$name] {$args[0]}", $this->path);
    }
}

////	END UTILITIES THAT MUST BE LOCAL :(
///////////////////////////////////////////////////////////////////////////////
