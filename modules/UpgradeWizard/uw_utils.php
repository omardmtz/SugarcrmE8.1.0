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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

/**
 * Implodes some parts of version with specified delimiter, beta & rc parts are removed all time
 *
 * @example ('6.5.6') returns 656
 * @example ('6.5.6beta2') returns 656
 * @example ('6.5.6rc3') returns 656
 * @example ('6.6.0.1') returns 6601
 * @example ('6.5.6', 3, 'x') returns 65x
 * @example ('6', 3, '', '.') returns 6.0.0
 *
 * @param string $version like 6, 6.2, 6.5.0beta1, 6.6.0rc1, 6.5.7 (separated by dot)
 * @param int $size number of the first parts of version which are requested
 * @param string $lastSymbol replace last part of version by some string
 * @param string $delimiter delimiter for result
 * @return string
 */
function implodeVersion($version, $size = 0, $lastSymbol = '', $delimiter = '')
{
    preg_match('/^\d+(\.\d+)*/', $version, $parsedVersion);
    if (empty($parsedVersion)) {
        return '';
    }

    $parsedVersion = $parsedVersion[0];
    $parsedVersion = explode('.', $parsedVersion);

    if ($size == 0) {
        $size = count($parsedVersion);
    }

    $parsedVersion = array_pad($parsedVersion, $size, 0);
    $parsedVersion = array_slice($parsedVersion, 0, $size);
    if ($lastSymbol !== '') {
        array_pop($parsedVersion);
        array_push($parsedVersion, $lastSymbol);
    }

    return implode($delimiter, $parsedVersion);
}

/**
 * Helper function for upgrade - get path from upload:// name
 * @param string $path
 * return string
 */
function getUploadRelativeName($path)
{
    if(class_exists('UploadFile')) {
        return UploadFile::realpath($path);
    }
    if(substr($path, 0, 9) == "upload://") {
    	$path = rtrim($GLOBALS['sugar_config']['upload_dir'], "/\\")."/".substr($path, 9);
    }
    return $path;
}

/**
 * Backs-up files that are targeted for patch/upgrade to a restore directory
 * @param string rest_dir Full path to the directory containing the original, replaced files.
 * @param string install_file Full path to the uploaded patch/upgrade zip file
 * @param string unzip_dir Full path to the unzipped files in a temporary directory
 * @param string zip_from_dir Name of directory that the unzipped files containing the actuall replacement files
 * @param array errors Collection of errors to be displayed at end of process
 * @param string path Optional full path to the log file.
 * @return array errors
 */
function commitMakeBackupFiles($rest_dir, $install_file, $unzip_dir, $zip_from_dir, $errors, $path='') {
	global $mod_strings;
	// create restore file directory
	sugar_mkdir($rest_dir, 0775, true);

    if(file_exists($rest_dir) && is_dir($rest_dir)){
		logThis('backing up files to be overwritten...', $path);
		$newFiles = findAllFiles(clean_path($unzip_dir . '/' . $zip_from_dir), array());

		// keep this around for canceling
		$_SESSION['uw_restore_dir'] = getUploadRelativeName($rest_dir);

		foreach ($newFiles as $file) {
			if (strpos($file, 'md5'))
				continue;

			// get name of current file to place in restore directory
			$cleanFile = str_replace(clean_path($unzip_dir . '/' . $zip_from_dir), '', $file);

			// make sure the directory exists
			$cleanDir = $rest_dir . '/' . dirname($cleanFile);
			sugar_mkdir($cleanDir, 0775, true);
			$oldFile = clean_path(getcwd() . '/' . $cleanFile);

			// only copy restore files for replacements - ignore new files from patch
			if (is_file($oldFile)) {
				if (is_writable($rest_dir)) {
					logThis('Backing up file: ' . $oldFile, $path);
					if (!copy($oldFile, $rest_dir . '/' . $cleanFile)) {
						logThis('*** ERROR: could not backup file: ' . $oldFile, $path);
						$errors[] = "{$mod_strings['LBL_UW_BACKUP']}::{$mod_strings['ERR_UW_FILE_NOT_COPIED']}: {$oldFile}";
					} else {
						$backupFilesExist = true;
					}

				} else {
					logThis('*** ERROR: directory not writable: ' . $rest_dir, $path);
					$errors[] = "{$mod_strings['LBL_UW_BACKUP']}::{$mod_strings['ERR_UW_DIR_NOT_WRITABLE']}: {$oldFile}";
				}
			}
		}
    }
	logThis('file backup done.', $path);
	return $errors;
}

/**
 * Copies files from the unzipped patch to the destination.
 * @param string unzip_dir Full path to the temporary directory created during unzip operation.
 * @param string zip_from_dir Name of folder containing the unzipped files; usually the name of the Patch without the
 * extension.
 * @param string path Optional full path to alternate upgradeWizard log file.
 * @return array Two element array containing to $copiedFiles and $skippedFiles.
 */



function commitCopyNewFiles($unzip_dir, $zip_from_dir, $path='') {
	logThis('Starting file copy process...', $path);
	global $sugar_version;
	$backwardModules='';

    	$modules = getAllModules();
			$backwardModules = array();
			foreach($modules as $mod){
				if(is_dir(clean_path(getcwd().'/modules/'.$mod.'/.500'))){
					$files = array();
			    	$files= findAllFiles(clean_path(getcwd().'/modules/'.$mod.'/.500'),$files);
			    	if(sizeof($files) >0){
			    		//backward compatibility is on
						$backwardModules[] = $mod;
			    	}
			   }
			}

	$newFiles = findAllFiles(clean_path($unzip_dir . '/' . $zip_from_dir), array());
	$zipPath = clean_path($unzip_dir . '/' . $zip_from_dir);

	// handle special do-not-overwrite conditions
	$doNotOverwrite = array();
	$doNotOverwrite[] = '__stub';
	if(isset($_REQUEST['overwrite_files_serial'])) {
		$doNotOverwrite = explode('::', $_REQUEST['overwrite_files_serial']);
	}

	$copiedFiles = array();
	$skippedFiles = array();

	foreach($newFiles as $file) {
		$cleanFile = str_replace($zipPath, '', $file);
		$srcFile = $zipPath . $cleanFile;
		$targetFile = clean_path(getcwd() . '/' . $cleanFile);
		if($backwardModules != null && sizeof($backwardModules) >0){
			foreach($backwardModules as $mod){
				$splitPath = explode('/',trim($cleanFile));
				if('modules' == trim($splitPath[1]) && $mod == trim($splitPath[2])){
					$cleanFile = str_replace('/modules/'.$mod, '/modules/'.$mod.'/.500', $cleanFile);
					$targetFile = clean_path(getcwd() . '/' . $cleanFile);
				}
			}
		}
		if(!is_dir(dirname($targetFile))) {
			mkdir_recursive(dirname($targetFile)); // make sure the directory exists
		}

		if((!file_exists($targetFile)) || /* brand new file */
			(!in_array($targetFile, $doNotOverwrite)) /* manual diff file */
			) {
			// handle sugar_version.php
			if(strpos($targetFile, 'sugar_version.php') !== false && !preg_match('/\/portal\/sugar_version\.php$/i', $targetFile)) {
				logThis('Skipping "sugar_version.php" - file copy will occur at end of successful upgrade', $path);
				$_SESSION['sugar_version_file'] = $srcFile;
				continue;
			}


			if(!copy($srcFile, $targetFile)) {
				logThis('*** ERROR: could not copy file: ' . $targetFile, $path);
			} else {
				$copiedFiles[] = $targetFile;
			}
		} else {
			$skippedFiles[] = $targetFile;
		}
	}
	logThis('File copy done.', $path);

	$ret = array();
	$ret['copiedFiles'] = $copiedFiles;
	$ret['skippedFiles'] = $skippedFiles;

	return $ret;
}


//On cancel put back the copied files from 500 to 451 state
function copyFilesOnCancel($step){
//place hoder for cancel action

}


function removeFileFromPath($file,$path, $deleteNot=array()){
		$removed = 0;
		$cur = $path . '/' . $file;
		if(file_exists($cur)){
			$del = true;
			foreach($deleteNot as $dn){
				if($cur == $dn){
					$del = false;
				}
			}
			if($del){
				unlink($cur);
				$removed++;
			}
		}
		if(!file_exists($path))return $removed;
		$d = dir($path);
		while(false !== ($e = $d->read())){  // Fixed bug. !== is required to literally match the type and value of false, so that a filename that could evaluate and cast to false, ie "false" or "0", still allows the while loop to continue.  From example at http://www.php.net/manual/en/function.dir.php
			$next = $path . '/'. $e;
			if(substr($e, 0, 1) != '.' && is_dir($next)){
				$removed += removeFileFromPath($file, $next, $deleteNot);
			}
		}
		$d->close();  // from example at http://www.php.net/manual/en/function.dir.php
		return $removed;
	}

/**
 * This function copies/overwrites between directories
 *
 * @param string the directory name to remove
 * @param boolean whether to just empty the given directory, without deleting the given directory.
 * @return boolean True/False whether the directory was deleted.
 */

function copyRecursiveBetweenDirectories($from,$to){
	if(file_exists($from)){
		$modifiedFiles = array();
		$modifiedFiles = findAllFiles(clean_path($from), $modifiedFiles);
	 	$cwd = clean_path(getcwd());
		foreach($modifiedFiles as $file) {
			$srcFile = clean_path($file);
            if (strpos($srcFile,".svn") === false) {
	    		$targetFile = str_replace($from, $to, $srcFile);

				if(!is_dir(dirname($targetFile))) {
					mkdir_recursive(dirname($targetFile)); // make sure the directory exists
				}

					// handle sugar_version.php
					if(strpos($targetFile, 'sugar_version.php') !== false && !preg_match('/\/portal\/sugar_version\.php$/i', $targetFile)) {
						logThis('Skipping "sugar_version.php" - file copy will occur at end of successful upgrade', $targetFile);
						$_SESSION['sugar_version_file'] = $srcFile;
						continue;
					}

					if(!copy($srcFile, $targetFile)) {
						logThis("*** ERROR: could not copy file $srcFile to $targetFile");
					}
            	}
		 }
	}
}

function deleteDirectory($dirname,$only_empty=false) {
    if (!is_dir($dirname))
        return false;
    $dscan = array(realpath($dirname));
    $darr = array();
    while (!empty($dscan)) {
        $dcur = array_pop($dscan);
        $darr[] = $dcur;
        if ($d=opendir($dcur)) {
            while ($f=readdir($d)) {
                if ($f=='.' || $f=='..')
                    continue;
                $f=$dcur.'/'.$f;
                if (is_dir($f))
                    $dscan[] = $f;
                else
                    unlink($f);
            }
            closedir($d);
        }
    }
    $i_until = ($only_empty)? 1 : 0;
    for ($i=count($darr)-1; $i>=$i_until; $i--) {
        if (rmdir($darr[$i]))
            logThis('Success :Copying file to destination: ' . $darr[$i]);
        else
            logThis('Copy problem:Copying file to destination: ' . $darr[$i]);
    }
    return (($only_empty)? (count(scandir)<=2) : (!is_dir($dirname)));
}
/**
 * Get all the customized modules. Compare the file md5s with the base md5s
 * If a file has been modified then put the module in the list of customized
 * modules. Show the list in the preflight check UI.
 */

function deleteAndOverWriteSelectedFiles($unzip_dir, $zip_from_dir,$delete_dirs){
	if($delete_dirs != null){
		foreach($delete_dirs as $del_dir){
			deleteDirectory($del_dir);
			$newFiles = findAllFiles(clean_path($unzip_dir . '/' . $zip_from_dir.'/'.$del_dir), array());
			$zipPath = clean_path($unzip_dir . '/' . $zip_from_dir.'/'.$del_dir);
			$copiedFiles = array();
			$skippedFiles = array();

			foreach($newFiles as $file) {
				$cleanFile = str_replace($zipPath, '', $file);
				$srcFile = $zipPath . $cleanFile;
				$targetFile = clean_path(getcwd() . '/' . $cleanFile);

				if(!is_dir(dirname($targetFile))) {
					mkdir_recursive(dirname($targetFile)); // make sure the directory exists
				}

				if(!file_exists($targetFile)){
					// handle sugar_version.php
					if(strpos($targetFile, 'sugar_version.php') !== false) {
						logThis('Skipping sugar_version.php - file copy will occur at end of successful upgrade');
						$_SESSION['sugar_version_file'] = $srcFile;
						continue;
					}

					if(!copy($srcFile, $targetFile)) {
						logThis('*** ERROR: could not copy file: ' . $targetFile);
					} else {
						$copiedFiles[] = $targetFile;
					}
				} else {
					$skippedFiles[] = $targetFile;
				}
			  }
		}
	}
	$ret = array();
	$ret['copiedFiles'] = $copiedFiles;
	$ret['skippedFiles'] = $skippedFiles;

	return $ret;
}

//Default is empty the directory. For removing set it to false
// to use this function to totally remove a directory, write:
// recursive_remove_directory('path/to/directory/to/delete',FALSE);

// to use this function to empty a directory, write:
// recursive_remove_directory('path/to/full_directory');

function recursive_empty_or_remove_directory($directory, $exclude_dirs=null,$exclude_files=null,$empty=true)
{
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
		// ... we return false and exit the function
		return false;

	// ... if the path is not readable
	}elseif(!is_readable($directory))
	{
		// ... we return false and exit the function
		return false;

	// ... else if the path is readable
	}else{

		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while (false !== ($item = readdir($handle)))
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if($item != '.' && $item != '..')
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;

				// if the new path is a directory
				//add another check if the dir is in the list to exclude delete
				if(is_dir($path) && $exclude_dirs != null && in_array($path,$exclude_dirs)){
				    //do nothing
				}
				else if(is_dir($path))
				{
					// we call this function with the new path
					recursive_empty_or_remove_directory($path);
				}
				// if the new path is a file
				else{
					// we remove the file
					if($exclude_files != null && in_array($path,$exclude_files)){
                           //do nothing
					}
					else{
						unlink($path);
				    }
				}
			}
		}
		// close the directory
		closedir($handle);

		// if the option to empty is not set to true
		if($empty == false)
		{
			// try to delete the now empty directory
			if(!rmdir($directory))
			{
				// return false if not possible
				return false;
			}
		}
		// return success
		return true;
	}
}
// ------------------------------------------------------------




function getAllCustomizedModules() {

		require_once('files.md5');

	    $return_array = array();
	    $modules = getAllModules();
	    foreach($modules as $mod) {
	    	   //find all files in each module if the files have been modified
	    	   //as compared to the base version then add the module to the
	    	   //customized modules array
	    	   $modFiles = findAllFiles(clean_path(getcwd())."/modules/$mod", array());
               foreach($modFiles as $file){
             	  $fileContents = file_get_contents($file);
             	   $file = str_replace(clean_path(getcwd()),'',$file);
               	  if($md5_string['./' . $file]){
	               	  if(md5($fileContents) != $md5_string['./' . $file]) {
	               	     //A file has been customized in the module. Put the module into the
	               	     // customized modules array.
	               	     echo 'Changed File'.$file;
	               	  	  $return_array[$mod];
	               	  	  break;
	               	  }
               	  }
               	  else{
               	  	// This is a new file in user's version and indicates that module has been
               	  	//customized. Put the module in the customized array.
                       echo 'New File'.$file;
                       $return_array[$mod];
                       break;
               	  }
               }
	    } //foreach

		return $return_array;
	}

    /**
     * Array of all Modules in the version bein upgraded
     * This method returns an Array of all modules
     * @return $modules Array of modules.
     */
	function getAllModules() {
		$modules = array();
		$d = dir('modules');
		while($e = $d->read()){
			if(substr($e, 0, 1) == '.' || !is_dir('modules/' . $e))continue;
			$modules[] = $e;
		}
		return $modules;
	}

//Remove files with the smae md5

function removeMd5MatchingFiles($deleteNot=array()){

	$md5_string = array();
	if(file_exists(clean_path(getcwd().'/files.md5'))){
		require(clean_path(getcwd().'/files.md5'));
	}
    $modulesAll = getAllModules();
     foreach($modulesAll as $mod){
	      $allModFiles = array();
	      if(is_dir('modules/'.$mod)){
	      $allModFiles = findAllFiles('modules/'.$mod,$allModFiles);
	       foreach($allModFiles as $file){
	           	if(file_exists($file) && !in_array(basename($file),$deleteNot)){
		       		 if(isset($md5_string['./'.$file])) {
		                  $fileContents = file_get_contents($file);
		               	  if(md5($fileContents) == $md5_string['./'.$file]) {
		               	  	unlink($file);
		               	  }
		              }
	            }
           }
	   }
   }
}


function deleteCache(){
	//Clean modules from cache
	$cachedir = sugar_cached('modules');
	if(is_dir($cachedir)){
		$allModFiles = array();
		$allModFiles = findAllFiles($cachedir,$allModFiles, true);
		foreach($allModFiles as $file) {
	       	if(file_exists($file)) {
	       		if(is_dir($file)) {
				  rmdir_recursive($file);
	       		} else {
	       		  unlink($file);
               }
	       	}
	    }

	}

	//Clean jsLanguage from cache
	$cachedir = sugar_cached('jsLanguage');
	if(is_dir($cachedir)){
		$allModFiles = array();
		$allModFiles = findAllFiles($cachedir,$allModFiles);
	   foreach($allModFiles as $file){
		   	if(file_exists($file)){
				unlink($file);
		   	}
		}
	}
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
	//Rebuild dashlets cache
	require_once('include/Dashlets/DashletCacheBuilder.php');
	$dc = new DashletCacheBuilder();
    $dc->buildCache();
}

function deleteChance(){
	//Clean folder from cache
	if(is_dir('include/SugarObjects/templates/chance')){
		rmdir_recursive('include/SugarObjects/templates/chance');
	 }
	if(is_dir('include/SugarObjects/templates/chance')){
		if(!isset($_SESSION['chance'])){
			$_SESSION['chance'] = '';
		}
		$_SESSION['chance'] = 'include/SugarObjects/templates/chance';
	}
}



/**
 * upgradeUWFiles
 * This function copies upgrade wizard files from new patch if that dir exists
 *
 * @param $file String path to uploaded zip file
 */
function upgradeUWFiles($file) {
    $cacheUploadUpgradesTemp = mk_temp_dir(sugar_cached("upgrades/temp"));

	unzip($file, $cacheUploadUpgradesTemp);

	if(!file_exists("$cacheUploadUpgradesTemp/manifest.php")) {
		logThis("*** ERROR: no manifest file detected while bootstraping upgrade wizard files!");
		return;
	} else {
		include("$cacheUploadUpgradesTemp/manifest.php");
	}

	$allFiles = array();
	$from_dir = "{$cacheUploadUpgradesTemp}/{$manifest['copy_files']['from_dir']}";

    // Localization
    if(file_exists("$from_dir/include/Localization/Localization.php")) {
        $allFiles[] = "$from_dir/include/Localization/Localization.php";
    }
    // upgradeWizard
    if(file_exists("$from_dir/modules/UpgradeWizard")) {
        $allFiles[] = findAllFiles("$from_dir/modules/UpgradeWizard", array());
    }
    // moduleInstaller
    if(file_exists("$from_dir/ModuleInstall")) {
        $allFiles[] = findAllFiles("$from_dir/ModuleInstall", array());
    }
    if(file_exists("$from_dir/include/javascript/yui")) {
        $allFiles[] = findAllFiles("$from_dir/include/javascript/yui", array());
    }
    if(file_exists("$from_dir/HandleAjaxCall.php")) {
        $allFiles[] = "$from_dir/HandleAjaxCall.php";
    }
    if(file_exists("$from_dir/include/SugarTheme")) {
        $allFiles[] = findAllFiles("$from_dir/include/SugarTheme", array());
    }
    if(file_exists("$from_dir/include/SugarCache")) {
        $allFiles[] = findAllFiles("$from_dir/include/SugarCache", array());
    }
    if(file_exists("$from_dir/include/utils/external_cache.php")) {
        $allFiles[] = "$from_dir/include/utils/external_cache.php";
    }
    if(file_exists("$from_dir/include/upload_file.php")) {
        $allFiles[] = "$from_dir/include/upload_file.php";
    }
    if(file_exists("$from_dir/include/file_utils.php")) {
        $allFiles[] = "$from_dir/include/file_utils.php";
    }
    if(file_exists("$from_dir/include/utils/sugar_file_utils.php")) {
        $allFiles[] = "$from_dir/include/utils/sugar_file_utils.php";
    }

    // users
    if(file_exists("$from_dir/modules/Users")) {
        $allFiles[] = findAllFiles("$from_dir/modules/Users", array());
    }
    if(file_exists("$from_dir/include/utils/autoloader.php")) {
    	$allFiles[] = "$from_dir/include/utils/autoloader.php";
    }

    upgradeUWFilesCopy($allFiles, $from_dir);
}

/**
 * upgradeUWFilesCopy
 *
 * This function recursively copies files from the upgradeUWFiles Array
 * @see upgradeUWFiles
 *
 * @param array $allFiles Array of files to copy over after zip file has been uploaded
 * @param string $from_dir Source directory
 */
function upgradeUWFilesCopy($allFiles, $from_dir)
{
   foreach($allFiles as $file)
   {
       if(is_array($file))
       {
           upgradeUWFilesCopy($file, $from_dir);
       } else {
           $destFile = str_replace($from_dir."/", "", $file);
           if(!is_dir(dirname($destFile))) {
              mkdir_recursive(dirname($destFile)); // make sure the directory exists
           }

           if(stristr($file,'uw_main.tpl'))
              logThis('Skipping "'.$file.'" - file copy will during commit step.');
           else {
              logThis('updating UpgradeWizard code: '.$destFile);
              copy_recursive($file, $destFile);
           }
       }
   }
}



/**
 * gets valid patch file names that exist in upload/upgrade/patch/
 */
function getValidPatchName($returnFull = true) {
	global $base_upgrade_dir;
	global $mod_strings;
	global $uh;
	global $sugar_version;
    global $sugar_config;
    $uh = new UpgradeHistory();
    list($base_upgrade_dir, $base_tmp_upgrade_dir) = getUWDirs();
    $return = array();

	// scan for new files (that are not installed)
	logThis('finding new files for upgrade');
	$upgrade_content = '';
	$upgrade_contents = findAllFiles($base_upgrade_dir, array(), false, 'zip');
	//other variations of zip file i.e. ZIP, zIp,zIP,Zip,ZIp,ZiP
	$ready = "<ul>\n";
	$ready .= "
		<table>
			<tr>
				<td></td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_NAME']}</b>
				</td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_TYPE']}</b>
				</td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_VERSION']}</b>
				</td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_PUBLISHED']}</b>
				</td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_UNINSTALLABLE']}</b>
				</td>
				<td align=left>
					<b>{$mod_strings['LBL_ML_DESCRIPTION']}</b>
				</td>
			</tr>";
	$disabled = '';

	// assume old patches are there.
	$upgradeToVersion = array(); // fill with valid patches - we will only use the latest qualified found patch

	// cn: bug 10609 - notices for uninitialized variables
	$icon = '';
	$name = '';
	$type = '';
	$version = '';
	$published_date = '';
	$uninstallable = '';
	$description = '';
	$disabled = '';

	foreach($upgrade_contents as $upgrade_content) {
		if(!preg_match("#.*\.zip\$#i", $upgrade_content)) {
			continue;
		}

		$the_base = basename($upgrade_content);
		$the_md5 = md5_file($upgrade_content);

		$md5_matches = $uh->findByMd5($the_md5);

		/* If a patch is in the /patch dir AND has no record in the upgrade_history table we assume that it's the one we want.
		 * Edge-case: manual upgrade with a FTP of a patch; UH table has no entry for it.  Assume nothing. :( */
		if(0 == sizeof($md5_matches)) {
			$target_manifest = remove_file_extension( $upgrade_content ) . '-manifest.php';
			require_once($target_manifest);

			if(empty($manifest['version'])) {
				logThis("*** Potential error: patch found with no version [ {$upgrade_content} ]");
				continue;
			}
			if(!isset($manifest['type']) || $manifest['type'] != 'patch') {
				logThis("*** Potential error: patch found with either no 'type' or non-patch type [ {$upgrade_content} ]");
				continue;
			}

			$upgradeToVersion[$manifest['version']] = urlencode($upgrade_content);

			$name = empty($manifest['name']) ? $upgrade_content : $manifest['name'];
			$version = empty($manifest['version']) ? '' : $manifest['version'];
			$published_date = empty($manifest['published_date']) ? '' : $manifest['published_date'];
			$icon = '';
			$description = empty($manifest['description']) ? 'None' : $manifest['description'];
			$uninstallable = empty($manifest['is_uninstallable']) ? 'No' : 'Yes';
			$type = getUITextForType( $manifest['type'] );
			$manifest_type = $manifest['type'];

			if(empty($manifest['icon'])) {
				$icon = getImageForType( $manifest['type'] );
			} else {
				$path_parts = pathinfo( $manifest['icon'] );
				$icon = "<!--not_in_theme!--><img src=\"" . remove_file_extension( $upgrade_content ) . "-icon." . $path_parts['extension'] . "\">";
			}
	    }
	}

	// cn: bug 10488 use the NEWEST upgrade/patch available when running upgrade wizard.
	ksort($upgradeToVersion);
	$upgradeToVersion = array_values($upgradeToVersion);
	$newest = array_pop($upgradeToVersion);
	$_SESSION['install_file'] = urldecode($newest); // in-case it was there from a prior.
	logThis("*** UW using [ {$_SESSION['install_file']} ] as source for patch files.");

	$cleanUpgradeContent = urlencode($_SESSION['install_file']);

	// cn: 10606 - cannot upload a patch file since this returned always.
	if(!empty($cleanUpgradeContent)) {
		$ready .= "<tr><td>$icon</td><td>$name</td><td>$type</td><td>$version</td><td>$published_date</td><td>$uninstallable</td><td>$description</td>\n";
		$ready .=<<<eoq
	        <td>
				<form action="index.php" method="post">
					<input type="hidden" name="module" value="UpgradeWizard">
					<input type="hidden" name="action" value="index">
					<input type="hidden" name="step" value="{$_REQUEST['step']}">
					<input type="hidden" name="run" value="delete">
	        		<input type=hidden name="install_file" value="{$cleanUpgradeContent}" />
	        		<input type=submit value="{$mod_strings['LBL_BUTTON_DELETE']}" />
				</form>
			</td></table>
eoq;
		$disabled = "DISABLED";
	}



	if(empty($cleanUpgradeContent)){
	    $ready .= "<tr><td colspan='7'><i>None</i></td>\n";
		$ready .= "</table>\n";
	}
	$ready .= "<br></ul>\n";

	$return['ready'] = $ready;
	$return['disabled'] = $disabled;

	if($returnFull) {
		return $return;
	}
}


/**
 * finalizes upgrade by setting upgrade versions in DB (config table) and sugar_version.php
 * @return bool true on success
 */
function updateVersions($version) {
	global $db;
	global $path;

	logThis('At updateVersions()... updating config table and sugar_version.php.', $path);

	// handle file copy
	if(isset($_SESSION['sugar_version_file']) && !empty($_SESSION['sugar_version_file'])) {
		if(!copy($_SESSION['sugar_version_file'], clean_path(getcwd().'/sugar_version.php'))) {
			logThis('*** ERROR: sugar_version.php could not be copied to destination! Cannot complete upgrade', $path);
			return false;
		} else {
			logThis('sugar_version.php successfully updated!', $path);
		}
	} else {
		logThis('*** ERROR: no sugar_version.php file location found! - cannot complete upgrade...', $path);
		return false;
	}

	$q1 = "DELETE FROM config WHERE category = 'info' AND name = 'sugar_version'";
	$q2 = "INSERT INTO config (category, name, value) VALUES ('info', 'sugar_version', '{$version}')";

	logThis('Deleting old DB version info from config table.', $path);
	$db->query($q1);

	logThis('Inserting updated version info into config table.', $path);
	$db->query($q2);

	logThis('updateVersions() complete.', $path);
	return true;
}



/**
 * gets a module's lang pack - does not need to be a SugarModule
 * @param lang string Language
 * @param module string Path to language folder
 * @return array mod_strings
 */
function getModuleLanguagePack($lang, $module) {
	$mod_strings = array();

	if(!empty($lang) && !empty($module)) {
		$langPack = clean_path(getcwd().'/'.$module.'/language/'.$lang.'.lang.php');
		$langPackEn = clean_path(getcwd().'/'.$module.'/language/en_us.lang.php');

        if (file_exists($langPack))
        {
            include FileLoader::validateFilePath($langPack);
        }
        elseif (file_exists($langPackEn))
        {
            include($langPackEn);
        }
	}

	return $mod_strings;
}
/**
 * checks system compliance for 4.5+ codebase
 * @return array Mixed values
 */
function checkSystemCompliance() {
	global $sugar_config;
	global $current_language;
	global $db;
	global $mod_strings;
    global $app_strings;

	if(!defined('SUGARCRM_MIN_MEM')) {
		define('SUGARCRM_MIN_MEM', 40);
	}

	$installer_mod_strings = getModuleLanguagePack($current_language, './install');
	$ret = array();
	$ret['error_found'] = false;

    require_once 'include/utils.php';
	// PHP version
	$php_version = constant('PHP_VERSION');
    $check_php_version_result = check_php_version();

	switch($check_php_version_result) {
		case -1:
			$ret['phpVersion'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_PHP_INVALID_VER']} {$php_version} )</span></b>";
			$ret['error_found'] = true;
			break;
		case 1:
			$ret['phpVersion'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_PHP_OK']} {$php_version} )</span></b>";
			break;
	}

	// database and connect
    $canInstall = $db->canInstall();
    if ($canInstall !== true)
    {
        $ret['error_found'] = true;
        if (count($canInstall) == 1)
        {
            $ret['dbVersion'] = "<b><span class=stop>" . $installer_mod_strings[$canInstall[0]] . "</span></b>";
        }
        else
        {
            $ret['dbVersion'] = "<b><span class=stop>" . sprintf($installer_mod_strings[$canInstall[0]], $canInstall[1]) . "</span></b>";
        }
    }

	// XML Parsing
	if(function_exists('xml_parser_create')) {
		$ret['xmlStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['xmlStatus'] = "<b><span class=stop>{$installer_mod_strings['LBL_CHECKSYS_NOT_AVAILABLE']}</span></b>";
		$ret['error_found'] = true;
	}

	// cURL
	if(function_exists('curl_init')) {
		$ret['curlStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['curlStatus'] = "<b><span class=go>{$installer_mod_strings['ERR_CHECKSYS_CURL']}</span></b>";
		$ret['error_found'] = false;
	}

	// mbstrings
	if(function_exists('mb_strlen')) {
		$ret['mbstringStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['mbstringStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_MBSTRING']}</span></b>";
		$ret['error_found'] = true;
	}

	// imap
	if(function_exists('imap_open')) {
		$ret['imapStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['imapStatus'] = "<b><span class=go>{$installer_mod_strings['ERR_CHECKSYS_IMAP']}</span></b>";
		$ret['error_found'] = false;
	}


	// safe mode
	if('1' == ini_get('safe_mode')) {
		$ret['safeModeStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_SAFE_MODE']}</span></b>";
		$ret['error_found'] = true;
	} else {
		$ret['safeModeStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	}


	// call time pass by ref
    if('1' == ini_get('allow_call_time_pass_reference')) {
		$ret['callTimeStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_CALL_TIME']}</span></b>";
		//continue upgrading
	} else {
		$ret['callTimeStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	}

	// memory limit
	$ret['memory_msg']     = "";
    $memory_limit = '-1';
	$sugarMinMem = constant('SUGARCRM_MIN_MEM');
	// logic based on: http://us2.php.net/manual/en/ini.core.php#ini.memory-limit
	if( $memory_limit == "" ){          // memory_limit disabled at compile time, no memory limit
	    $ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_MEM_OK']}</span></b>";
	} elseif( $memory_limit == "-1" ){   // memory_limit enabled, but set to unlimited
	    $ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_MEM_UNLIMITED']}</span></b>";
	} else {
	    rtrim($memory_limit, 'M');
	    $memory_limit_int = (int) $memory_limit;
	    if( $memory_limit_int < constant('SUGARCRM_MIN_MEM') ){
	        $ret['memory_msg'] = "<b><span class=\"stop\">{$installer_mod_strings['ERR_CHECKSYS_MEM_LIMIT_1']}" . constant('SUGARCRM_MIN_MEM') . "{$installer_mod_strings['ERR_CHECKSYS_MEM_LIMIT_2']}</span></b>";
			$ret['error_found'] = true;
	    } else {
			$ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_OK']} ({$memory_limit})</span></b>";
	    }
	}
        // zip support
    if (!class_exists("ZipArchive"))
    {
        $ret['ZipStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_ZIP']}</span></b>";
        $ret['error_found'] = true;
    } else {
        $ret['ZipStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
    }



    // Suhosin allow to use upload://
    $ret['stream_msg'] = '';
    if (UploadStream::getSuhosinStatus() == true)
    {
        $ret['stream_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
    }
    else
    {
        $ret['stream_msg'] = "<b><span class=\"stop\">{$app_strings['ERR_SUHOSIN']}</span></b>";
        $ret['error_found'] = true;
    }

	// mbstring.func_overload
	$ret['mbstring.func_overload'] = '';
	$mb = ini_get('mbstring.func_overload');

	if($mb > 1) {
		$ret['mbstring.func_overload'] = "<b><span class=\"stop\">{$mod_strings['ERR_UW_MBSTRING_FUNC_OVERLOAD']}</b>";
		$ret['error_found'] = true;
	}
	return $ret;
}


/**
 * is a file that we blow away automagically
 */
function isAutoOverwriteFile($file) {
	$overwriteDirs = array(
		'./sugar_version.php',
		'./modules/UpgradeWizard/uw_main.tpl',
	);
	$file = trim('.'.str_replace(clean_path(getcwd()), '', $file));

	if(in_array($file, $overwriteDirs)) {
		return true;
	}

	$fileExtension = substr(strrchr($file, "."), 1);
	if($fileExtension == 'tpl' || $fileExtension == 'html') {
		return false;
	}

	return true;
}

/**
 * flatfile logger
 */
function logThis($entry, $path='') {
	global $mod_strings;
	if(file_exists('include/utils/sugar_file_utils.php')){
		require_once('include/utils/sugar_file_utils.php');
	}
		$log = empty($path) ? 'upgradeWizard.log' : $path;

		// create if not exists
		$fp = @fopen($log, 'a+');
		if(!is_resource($fp)) {
				$GLOBALS['log']->fatal('UpgradeWizard could not open/lock upgradeWizard.log file');
				die($mod_strings['ERR_UW_LOG_FILE_UNWRITABLE']);
		}

		$line = date('r').' [UpgradeWizard] - '.$entry."\n";

		if(@fwrite($fp, $line) === false) {
			$GLOBALS['log']->fatal('UpgradeWizard could not write to upgradeWizard.log: '.$entry);
			die($mod_strings['ERR_UW_LOG_FILE_UNWRITABLE']);
		}

		if(is_resource($fp)) {
			fclose($fp);
		}
}


/**
 * test perms for CREATE queries
 */
function testPermsCreate($db, $out) {
	logThis('Checking CREATE TABLE permissions...');
	global $mod_strings;

	if(!$db->checkPrivilege("CREATE TABLE")) {
        logThis('cannot CREATE TABLE!');
		$out['db']['dbNoCreate'] = true;
		$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_CREATE']}</span></td></tr>";
	}
    return $out;
}

/**
 * test perms for INSERT
 */
function testPermsInsert($db, $out, $skip=false) {
	logThis('Checking INSERT INTO permissions...');
	global $mod_strings;

	if(!$db->checkPrivilege("INSERT")) {
		logThis('cannot INSERT INTO!');
		$out['db']['dbNoInsert'] = true;
		$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_INSERT']}</span></td></tr>";
    }
    return $out;
}


/**
 * test perms for UPDATE TABLE
 */
function testPermsUpdate($db, $out, $skip=false) {
	logThis('Checking UPDATE TABLE permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("UPDATE")) {
					logThis('cannot UPDATE TABLE!');
					$out['db']['dbNoUpdate'] = true;
					$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_UPDATE']}</span></td></tr>";
    }
    return $out;
}


/**
 * test perms for SELECT
 */
function testPermsSelect($db, $out, $skip=false) {
	logThis('Checking SELECT permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("SELECT")) {
				logThis('cannot SELECT!');
				$out['db']['dbNoSelect'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_SELECT']}</span></td></tr>";
    }
    return $out;
}

/**
 * test perms for DELETE
 */
function testPermsDelete($db, $out, $skip=false) {
	logThis('Checking DELETE FROM permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("DELETE")) {
				logThis('cannot DELETE FROM!');
				$out['db']['dbNoDelete'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DELETE']}</span></td></tr>";
    }
    return $out;
}


/**
 * test perms for ALTER TABLE ADD COLUMN
 */
function testPermsAlterTableAdd($db, $out, $skip=false) {
	logThis('Checking ALTER TABLE ADD COLUMN permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("ADD COLUMN")) {
				logThis('cannot ADD COLUMN!');
				$out['db']['dbNoAddColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_ADD_COLUMN']}</span></td></tr>";
    }
    return $out;
}

/**
 * test perms for ALTER TABLE ADD COLUMN
 */
function testPermsAlterTableChange($db, $out, $skip=false) {
	logThis('Checking ALTER TABLE CHANGE COLUMN permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("CHANGE COLUMN")) {
				logThis('cannot CHANGE COLUMN!');
				$out['db']['dbNoChangeColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_CHANGE_COLUMN']}</span></td></tr>";
    }
    return $out;
}

/**
 * test perms for ALTER TABLE DROP COLUMN
 */
function testPermsAlterTableDrop($db, $out, $skip=false) {
	logThis('Checking ALTER TABLE DROP COLUMN permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("DROP COLUMN")) {
				logThis('cannot DROP COLUMN!');
				$out['db']['dbNoDropColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DROP_COLUMN']}</span></td></tr>";
    }
    return $out;
}


/**
 * test perms for DROP TABLE
 */
function testPermsDropTable($db, $out, $skip=false) {
	logThis('Checking DROP TABLE permissions...');
	global $mod_strings;
	if(!$db->checkPrivilege("DROP TABLE")) {
				logThis('cannot DROP TABLE!');
				$out['db']['dbNoDropTable'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DROP_TABLE']}</span></td></tr>";
    }
    return $out;
}

function getFormattedError($error, $query) {
	$error = "<div><b>".$error;
	$error .= "</b>::{$query}</div>";

	return $error;
}

/**
 * parses a query finding the table name
 * @param string query The query
 * @return string table The table
 */
function getTableFromQuery($query) {
	$standardQueries = array('ALTER TABLE', 'DROP TABLE', 'CREATE TABLE', 'INSERT INTO', 'UPDATE', 'DELETE FROM');
	$query = preg_replace("/[^A-Za-z0-9\_\s]/", "", $query);
	$query = trim(str_replace($standardQueries, '', $query));

	$firstSpc = strpos($query, " ");
	$end = ($firstSpc > 0) ? $firstSpc : strlen($query);
	$table = substr($query, 0, $end);

	return $table;
}

//prelicense check

function preLicenseCheck() {
	require_once('modules/UpgradeWizard/uw_files.php');

	global $sugar_config;
	global $mod_strings;
	global $sugar_version;

    if (empty($sugar_version))
    {
        require('sugar_version.php');
    }

if(!isset($_SESSION['unzip_dir']) || empty($_SESSION['unzip_dir'])) {
		logThis('unzipping files in upgrade archive...');
		$errors					= array();
		list($base_upgrade_dir, $base_tmp_upgrade_dir) = getUWDirs();
		$unzip_dir = '';
		//also come up with mechanism to read from upgrade-progress file
		if(!isset($_SESSION['install_file']) || empty($_SESSION['install_file']) || !is_file($_SESSION['install_file'])) {
			if (file_exists(clean_path($base_tmp_upgrade_dir)) && $handle = opendir(clean_path($base_tmp_upgrade_dir))) {
		    		while (false !== ($file = readdir($handle))) {
		    		if($file !="." && $file !="..")	{
					 if(is_file($base_tmp_upgrade_dir."/".$file."/manifest.php")){
					 	require_once($base_tmp_upgrade_dir."/".$file."/manifest.php");
					 	$package_name= $manifest['copy_files']['from_dir'];
					 	if(file_exists($base_tmp_upgrade_dir."/".$file."/".$package_name) && file_exists($base_tmp_upgrade_dir."/".$file."/scripts") && file_exists($base_tmp_upgrade_dir."/".$file."/manifest.php")){
					 		$unzip_dir = $base_tmp_upgrade_dir."/".$file;
					 		if(file_exists("$base_upgrade_dir/patch/".$package_name.'.zip')){
					 			$_SESSION['install_file'] = $package_name.".zip";
					 			break;
					 		}
						}
					  }
		    		}
		    	}
			}
		}
        if(empty($_SESSION['install_file'])){
        	unlinkUWTempFiles();
        	resetUwSession();
        	echo 'Upload File not found so redirecting to Upgrade Start ';
        	$redirect_new_wizard = $sugar_config['site_url' ].'/index.php?module=UpgradeWizard&action=index';
        	echo '<form name="redirect" action="' .$redirect_new_wizard. '"  method="POST">';
$upgrade_directories_not_found =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>'Upload file missing or has been deleted. Refresh the page to go back to UpgradeWizard start'</b></span>
			</th>
		</tr>
	</table>
eoq;
$uwMain = $upgrade_directories_not_found;
				return '';
        }
		$install_file			= "$base_upgrade_dir/patch/".basename(urldecode( $_SESSION['install_file'] ));
		$show_files				= true;
		if(empty($unzip_dir)){
			$unzip_dir				= mk_temp_dir( $base_tmp_upgrade_dir );
		}
		$zip_from_dir			= ".";
		$zip_to_dir				= ".";
		$zip_force_copy			= array();

		if(!$unzip_dir){
			logThis('Could not create a temporary directory using mk_temp_dir( $base_tmp_upgrade_dir )');
			die($mod_strings['ERR_UW_NO_CREATE_TMP_DIR']);
		}

		//double check whether unzipped .
		if(file_exists($unzip_dir ."/scripts") && file_exists($unzip_dir."/manifest.php")){
        	//already unzipped
		}
		else{
			unzip( $install_file, $unzip_dir );
		}

		// assumption -- already validated manifest.php at time of upload
		require_once( "$unzip_dir/manifest.php" );

		if( isset( $manifest['copy_files']['from_dir'] ) && $manifest['copy_files']['from_dir'] != "" ){
		    $zip_from_dir   = $manifest['copy_files']['from_dir'];
		}
		if( isset( $manifest['copy_files']['to_dir'] ) && $manifest['copy_files']['to_dir'] != "" ){
		    $zip_to_dir     = $manifest['copy_files']['to_dir'];
		}
		if( isset( $manifest['copy_files']['force_copy'] ) && $manifest['copy_files']['force_copy'] != "" ){
		    $zip_force_copy     = $manifest['copy_files']['force_copy'];
		}
		if( isset( $manifest['version'] ) ){
		    $version    = $manifest['version'];
		}
		if( !is_writable( "config.php" ) ){
			return $mod_strings['ERR_UW_CONFIG'];
		}

		$_SESSION['unzip_dir'] = clean_path($unzip_dir);
		$_SESSION['zip_from_dir'] = clean_path($zip_from_dir);
		logThis('unzip done.');
	} else {
		$unzip_dir = $_SESSION['unzip_dir'];
		$zip_from_dir = $_SESSION['zip_from_dir'];
	}

    //check if $_SESSION['unzip_dir'] and $_SESSION['zip_from_dir'] exist
	if(!isset($_SESSION['unzip_dir']) || !file_exists($_SESSION['unzip_dir'])
		|| !isset($_SESSION['install_file']) || empty($_SESSION['install_file']) || !file_exists($_SESSION['install_file'])){
		    //redirect to start
	    unlinkUWTempFiles();
		resetUwSession();
		echo 'Upload File not found so redirecting to Upgrade Start ';
		$redirect_new_wizard = $sugar_config['site_url' ].'/index.php?module=UpgradeWizard&action=index';
		echo '<form name="redirect" action="' .$redirect_new_wizard. '"  method="POST">';
$upgrade_directories_not_found =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>'Upload file missing or has been deleted. Refresh the page to go back to UpgradeWizard start'</b></span>
			</th>
		</tr>
	</table>
eoq;
$uwMain = $upgrade_directories_not_found;
				return '';
	}

    logThis ('is SugarConfig there '.file_exists(clean_path($unzip_dir.'/'.$zip_from_dir."/include/SugarObjects/SugarConfig.php")));
	if(file_exists(clean_path($unzip_dir.'/'.$zip_from_dir."/include/SugarObjects/SugarConfig.php"))) {
		$file = clean_path($unzip_dir.'/'.$zip_from_dir."/include/SugarObjects/SugarConfig.php");
		$destFile = str_replace(clean_path($unzip_dir.'/'.$zip_from_dir), $cwd, $file);
		if(!is_dir(dirname($destFile))) {
			mkdir_recursive(dirname($destFile)); // make sure the directory exists
		}
        copy($file,$destFile);
        //also copy include utils array utils
        $file = clean_path($unzip_dir.'/'.$zip_from_dir."/include/utils/array_utils.php");
		$destFile = str_replace(clean_path($unzip_dir.'/'.$zip_from_dir), $cwd, $file);
		if(!is_dir(dirname($destFile))) {
			mkdir_recursive(dirname($destFile)); // make sure the directory exists
		}
        copy($file,$destFile);
	}
}


function preflightCheck() {
	require_once('modules/UpgradeWizard/uw_files.php');

	global $sugar_config;
	global $mod_strings;
	global $sugar_version;

    if (empty($sugar_version))
    {
        require('sugar_version.php');
    }

	// don't bother if are rechecking
	$manualDiff			= array();
	if(!isset($_SESSION['unzip_dir']) || empty($_SESSION['unzip_dir'])) {
		logThis('unzipping files in upgrade archive...');
		$errors					= array();
		list($base_upgrade_dir, $base_tmp_upgrade_dir) = getUWDirs();
		$unzip_dir = '';
		//Following is if User logged out unexpectedly and then logged into UpgradeWizard again.
		//also come up with mechanism to read from upgrade-progress file.
		if(!isset($_SESSION['install_file']) || empty($_SESSION['install_file']) || !is_file($_SESSION['install_file'])) {
			if (file_exists($base_tmp_upgrade_dir) && $handle = opendir($base_tmp_upgrade_dir)) {
		    	while (false !== ($file = readdir($handle))) {
		    		if($file !="." && $file !="..")	{
					 if(is_file($base_tmp_upgrade_dir."/".$file."/manifest.php")){
					 	require_once($base_tmp_upgrade_dir."/".$file."/manifest.php");
					 	$package_name= $manifest['copy_files']['from_dir'];
					 	if(file_exists($base_tmp_upgrade_dir."/".$file."/".$package_name) && file_exists($base_tmp_upgrade_dir."/".$file."/scripts") && file_exists($base_tmp_upgrade_dir."/".$file."/manifest.php")){
					 		$unzip_dir = $base_tmp_upgrade_dir."/".$file;
					 		if(file_exists("$base_upgrade_dir/patch/".$package_name.'.zip')){
					 			$_SESSION['install_file'] = $package_name.".zip";
					 			break;
					 		}
						}
					  }
		    		}
		    	}
			}
		}
        if(empty($_SESSION['install_file'])){
        	unlinkUWTempFiles();
        	resetUwSession();
        	echo 'Upload File not found so redirecting to Upgrade Start ';
        	$redirect_new_wizard = $sugar_config['site_url' ].'/index.php?module=UpgradeWizard&action=index';
        	echo '<form name="redirect" action="' .$redirect_new_wizard. '"  method="POST">';
$upgrade_directories_not_found =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>'Upload file missing or has been deleted. Refresh the page to go back to UpgradeWizard start'</b></span>
			</th>
		</tr>
	</table>
eoq;
$uwMain = $upgrade_directories_not_found;
				return '';

        }
		$install_file			= "$base_upgrade_dir/patch/".basename(urldecode( $_SESSION['install_file'] ));
		$show_files				= true;
		if(empty($unzip_dir)){
			$unzip_dir				= mk_temp_dir( $base_tmp_upgrade_dir );
		}
		$zip_from_dir			= ".";
		$zip_to_dir				= ".";
		$zip_force_copy			= array();

		if(!$unzip_dir){
			logThis('Could not create a temporary directory using mk_temp_dir( $base_tmp_upgrade_dir )');
			die($mod_strings['ERR_UW_NO_CREATE_TMP_DIR']);
		}

		//double check whether unzipped .
		if(file_exists($unzip_dir ."/scripts") && file_exists($unzip_dir."/manifest.php")){
        	//already unzipped
		}
		else{
			unzip( $install_file, $unzip_dir );
		}

		// assumption -- already validated manifest.php at time of upload
		require_once( "$unzip_dir/manifest.php" );

		if( isset( $manifest['copy_files']['from_dir'] ) && $manifest['copy_files']['from_dir'] != "" ){
		    $zip_from_dir   = $manifest['copy_files']['from_dir'];
		}
		if( isset( $manifest['copy_files']['to_dir'] ) && $manifest['copy_files']['to_dir'] != "" ){
		    $zip_to_dir     = $manifest['copy_files']['to_dir'];
		}
		if( isset( $manifest['copy_files']['force_copy'] ) && $manifest['copy_files']['force_copy'] != "" ){
		    $zip_force_copy     = $manifest['copy_files']['force_copy'];
		}
		if( isset( $manifest['version'] ) ){
		    $version    = $manifest['version'];
		}
		if( !is_writable( "config.php" ) ){
			return $mod_strings['ERR_UW_CONFIG'];
		}

		$_SESSION['unzip_dir'] = clean_path($unzip_dir);
		$_SESSION['zip_from_dir'] = clean_path($zip_from_dir);

	} else {
		$unzip_dir = $_SESSION['unzip_dir'];
		$zip_from_dir = $_SESSION['zip_from_dir'];
	}
	//check if $_SESSION['unzip_dir'] and $_SESSION['zip_from_dir'] exist
	if(!isset($_SESSION['unzip_dir']) || !file_exists($_SESSION['unzip_dir'])
		|| !isset($_SESSION['install_file']) || empty($_SESSION['install_file']) || !file_exists($_SESSION['install_file'])){
		    //redirect to start
	    unlinkUWTempFiles();
		resetUwSession();
		echo 'Upload File not found so redirecting to Upgrade Start ';
		$redirect_new_wizard = $sugar_config['site_url' ].'/index.php?module=UpgradeWizard&action=index';
		echo '<form name="redirect" action="' .$redirect_new_wizard. '"  method="POST">';
$upgrade_directories_not_found =<<<eoq
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<th colspan="2" align="left">
				<span class='error'><b>'Upload file missing or has been deleted. Refresh the page to go back to UpgradeWizard start'</b></span>
			</th>
		</tr>
	</table>
eoq;
$uwMain = $upgrade_directories_not_found;
				return '';
	}
	//copy minimum required files
	fileCopy('include/utils/sugar_file_utils.php');

	$upgradeFiles = findAllFiles(clean_path("$unzip_dir/$zip_from_dir"), array());
	$cache_html_files= array();

	// get md5 sums
	$md5_string = array();
	if(file_exists(clean_path(getcwd().'/files.md5'))){
		require(clean_path(getcwd().'/files.md5'));
	}

	// file preflight checks
	logThis('verifying md5 checksums for files...');
	foreach($upgradeFiles as $file) {
		if(in_array(str_replace(clean_path("$unzip_dir/$zip_from_dir") . "/", '', $file), $uw_files))
			continue; // skip already loaded files

		if(strpos($file, '.md5'))
			continue; // skip md5 file

		// normalize file paths
		$file = clean_path($file);

		// check that we can move/delete the upgraded file
		if(!is_writable($file)) {
			$errors[] = $mod_strings['ERR_UW_FILE_NOT_WRITABLE'].": ".$file;
		}
		// check that destination files are writable
		$destFile = getcwd().str_replace(clean_path($unzip_dir.'/'.$zip_from_dir), '', $file);

		if(is_file($destFile)) { // of course it needs to exist first...
			if(!is_writable($destFile)) {
				$errors[] = $mod_strings['ERR_UW_FILE_NOT_WRITABLE'].": ".$destFile;
			}
		}

		///////////////////////////////////////////////////////////////////////
		////	DIFFS
		// compare md5s and build up a manual merge list
		$targetFile = clean_path(".".str_replace(getcwd(),'',$destFile));
		$targetMd5 = '0';
		if(is_file($destFile)) {
			if(strpos($targetFile, '.php')) {
				// handle PHP files that were hit with the security regex
				$fp = '';
				if(function_exists('sugar_fopen')){
					$fp = sugar_fopen($destFile, 'r');
				}
				else{
					$fp = fopen($destFile, 'r');
				}
				$filesize = filesize($destFile);
				if($filesize > 0) {
					$fileContents = stream_get_contents($fp);
					$targetMd5 = md5($fileContents);
				}
			} else {
				$targetMd5 = md5_file($destFile);
			}
		}

		if(isset($md5_string[$targetFile]) && $md5_string[$targetFile] != $targetMd5) {
			logThis('found a file with a differing md5: ['.$targetFile.']');
			$manualDiff[] = $destFile;
		}
		////	END DIFFS
		///////////////////////////////////////////////////////////////////////
	}
	logThis('md5 verification done.');
	$errors['manual'] = $manualDiff;

	return $errors;
}

function fileCopy($file_path){
	if(file_exists(clean_path($_SESSION['unzip_dir'].'/'.$_SESSION['zip_from_dir'].'/'.$file_path))) {
		$file = clean_path($_SESSION['unzip_dir'].'/'.$_SESSION['zip_from_dir'].'/'.$file_path);
		$destFile = str_replace(clean_path($_SESSION['unzip_dir'].'/'.$_SESSION['zip_from_dir']),  clean_path(getcwd()), $file);
	if(!is_dir(dirname($destFile))) {
		mkdir_recursive(dirname($destFile)); // make sure the directory exists
		}
		copy_recursive($file,$destFile);
	}
}
function getChecklist($steps, $step) {
	global $mod_strings;

	$skip = array('start', 'cancel', 'uninstall','end');
	$j=0;
	$i=1;
	$ret  = '<table cellpadding="3" cellspacing="4" border="0">';
	$ret .= '<tr><th colspan="3" align="left">'.$mod_strings['LBL_UW_CHECKLIST'].':</th></tr>';
	foreach($steps['desc'] as $k => $desc) {
		if(in_array($steps['files'][$j], $skip)) {
			$j++;
			continue;
		}

		$desc_mod_pre = '';
		$desc_mod_post = '';

		if($k == $_REQUEST['step']) {
			$desc_mod_pre = "<font color=blue><i>";
			$desc_mod_post = "</i></font>";
		}

		$ret .= "<tr><td>&nbsp;</td><td><b>{$i}: {$desc_mod_pre}{$desc}{$desc_mod_post}</b></td>";
		$ret .= "<td id={$steps['files'][$j]}><i></i></td></tr>";
		$i++;
		$j++;
	}
	$ret .= "</table>";
	return $ret;
}

function prepSystemForUpgrade() {
	global $sugar_config;
	global $sugar_flavor;
	global $mod_strings;
    global $current_language;
	global $subdirs;
	global $base_upgrade_dir;
	global $base_tmp_upgrade_dir;
    list($p_base_upgrade_dir, $p_base_tmp_upgrade_dir) = getUWDirs();
	///////////////////////////////////////////////////////////////////////////////
	////	Make sure variables exist
	if(empty($base_upgrade_dir)){
		$base_upgrade_dir       = $p_base_upgrade_dir;
	}
	if(empty($base_tmp_upgrade_dir)){
		$base_tmp_upgrade_dir   = $p_base_tmp_upgrade_dir;
	}
	sugar_mkdir($base_tmp_upgrade_dir, 0775, true);
	if(!isset($subdirs) || empty($subdirs)){
		$subdirs = array('full', 'langpack', 'module', 'patch', 'theme');
	}

    $upgrade_progress_dir = $base_tmp_upgrade_dir;
    $upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
    if(file_exists($upgrade_progress_file)){
    	if(function_exists('get_upgrade_progress') && function_exists('didThisStepRunBefore')){
    		if(didThisStepRunBefore('end')){
    			include($upgrade_progress_file);
    			unset($upgrade_config);
    			unlink($upgrade_progress_file);
    		}
    	}
    }

    // increase the cuttoff time to 1 hour
	ini_set("max_execution_time", "3600");

    // make sure dirs exist
	if($subdirs != null){
		foreach($subdirs as $subdir) {
		    sugar_mkdir("$base_upgrade_dir/$subdir", 0775, true);
		}
	}
	// array of special scripts that are executed during (un)installation-- key is type of script, value is filename
	if(!defined('SUGARCRM_PRE_INSTALL_FILE')) {
		define('SUGARCRM_PRE_INSTALL_FILE', 'scripts/pre_install.php');
		define('SUGARCRM_POST_INSTALL_FILE', 'scripts/post_install.php');
		define('SUGARCRM_PRE_UNINSTALL_FILE', 'scripts/pre_uninstall.php');
		define('SUGARCRM_POST_UNINSTALL_FILE', 'scripts/post_uninstall.php');
	}

	$script_files = array(
		"pre-install" => constant('SUGARCRM_PRE_INSTALL_FILE'),
		"post-install" => constant('SUGARCRM_POST_INSTALL_FILE'),
		"pre-uninstall" => constant('SUGARCRM_PRE_UNINSTALL_FILE'),
		"post-uninstall" => constant('SUGARCRM_POST_UNINSTALL_FILE'),
	);

	// check that the upload limit is set to 6M or greater
	define('SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES', 6 * 1024 * 1024);  // 6 Megabytes
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_bytes = return_bytes($upload_max_filesize);

	if($upload_max_filesize_bytes < constant('SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES')) {
		$GLOBALS['log']->debug("detected upload_max_filesize: $upload_max_filesize");
        $admin_strings = return_module_language($current_language, 'Administration');
		echo '<p class="error">'.$admin_strings['MSG_INCREASE_UPLOAD_MAX_FILESIZE'].' '.get_cfg_var('cfg_file_path')."</p>\n";
	}
}

if ( !function_exists('extractFile') ) {
function extractFile($zip_file, $file_in_zip) {
    global $base_tmp_upgrade_dir;

	// strip cwd
	$absolute_base_tmp_upgrade_dir = clean_path($base_tmp_upgrade_dir);
	$relative_base_tmp_upgrade_dir = clean_path(str_replace(clean_path(getcwd()), '', $absolute_base_tmp_upgrade_dir));

    // mk_temp_dir expects relative pathing
    $my_zip_dir = mk_temp_dir($relative_base_tmp_upgrade_dir);

    unzip_file($zip_file, $file_in_zip, $my_zip_dir);

    return("$my_zip_dir/$file_in_zip");
}
}

if ( !function_exists('extractManifest') ) {
function extractManifest($zip_file) {
	logThis('extracting manifest.');
    return(extractFile($zip_file, "manifest.php"));
}
}

if ( !function_exists('getInstallType') ) {
function getInstallType($type_string) {
    // detect file type
    global $subdirs;
	$subdirs = array('full', 'langpack', 'module', 'patch', 'theme', 'temp');
    foreach($subdirs as $subdir) {
        if(preg_match("#/$subdir/#", $type_string)) {
            return($subdir);
        }
    }
    // return empty if no match
    return("");
}
}

function getImageForType($type) {
    global $image_path;
    global $mod_strings;

    $icon = "";
    switch($type) {
        case "full":
            $icon = SugarThemeRegistry::current()->getImage("Upgrade", "",null,null,'.gif',$mod_strings['LBL_UPGRADE']);
            break;
        case "langpack":
            $icon = SugarThemeRegistry::current()->getImage("LanguagePacks", "",null,null,'.gif',$mod_strings['LBL_LANGPACKS']);
            break;
        case "module":
            $icon = SugarThemeRegistry::current()->getImage("ModuleLoader", "",null,null,'.gif',$mod_strings['LBL_MODULELOADER']);
            break;
        case "patch":
            $icon = SugarThemeRegistry::current()->getImage("PatchUpgrades", "",null,null,'.gif',$mod_strings['LBL_PATCHUPGRADES']);
            break;
        case "theme":
            $icon = SugarThemeRegistry::current()->getImage("Themes", "",null,null,'.gif',$mod_strings['LBL_THEMES']);
            break;
        default:
            break;
    }
    return($icon);
}

if ( !function_exists('getLanguagePackName') ) {
function getLanguagePackName($the_file) {
    $app_list_strings = FileLoader::varFromInclude($the_file, 'app_list_strings');
    if(isset($app_list_strings["language_pack_name"])) {
        return($app_list_strings["language_pack_name"]);
    }
    return("");
}
}

function getUITextForType($type) {
    if($type == "full") {
        return("Full Upgrade");
    }
    if($type == "langpack") {
        return("Language Pack");
    }
    if($type == "module") {
        return("Module");
    }
    if($type == "patch") {
        return("Patch");
    }
    if($type == "theme") {
        return("Theme");
    }
}

if ( !function_exists('validate_manifest') ) {
/**
 * Verifies a manifest from a patch or module to be compatible with the current Sugar version and flavor
 * @param array manifest Standard manifest array
 * @return string Error message, blank on success
 */
function validate_manifest($manifest) {
	logThis('validating manifest.php file');
    // takes a manifest.php manifest array and validates contents
    global $subdirs;
    global $sugar_version;
    global $sugar_flavor;
	global $mod_strings;

    if(!isset($manifest['type'])) {
        return $mod_strings['ERROR_MANIFEST_TYPE'];
    }

    $type = $manifest['type'];

    if(getInstallType("/$type/") == "") {
		return $mod_strings['ERROR_PACKAGE_TYPE']. ": '" . $type . "'.";
    }

    if(isset($manifest['acceptable_sugar_versions'])) {
        $version_ok = false;
        $matches_empty = true;
        if(isset($manifest['acceptable_sugar_versions']['exact_matches'])) {
            $matches_empty = false;
            foreach($manifest['acceptable_sugar_versions']['exact_matches'] as $match) {
                if($match == $sugar_version) {
                    $version_ok = true;
                }
            }
        }
        if(!$version_ok && isset($manifest['acceptable_sugar_versions']['regex_matches'])) {
            $matches_empty = false;
            foreach($manifest['acceptable_sugar_versions']['regex_matches'] as $match) {
                if(!empty($match) && preg_match("/$match/", $sugar_version)) {
                    $version_ok = true;
                }
            }
        }

        if(!$matches_empty && !$version_ok) {
            return $mod_strings['ERROR_VERSION_INCOMPATIBLE']."<br />".
            $mod_strings['ERR_UW_VERSION'].$sugar_version;
        }
    }

    if(isset($manifest['acceptable_sugar_flavors']) && sizeof($manifest['acceptable_sugar_flavors']) > 0) {
        $flavor_ok = false;
        foreach($manifest['acceptable_sugar_flavors'] as $match) {
            if($match == $sugar_flavor) {
                $flavor_ok = true;
            }
        }
        if(!$flavor_ok) {
            return $mod_strings['ERROR_FLAVOR_INCOMPATIBLE']."<br />".
            $mod_strings['ERR_UW_FLAVOR'].$sugar_flavor."<br />".
            $mod_strings['ERR_UW_FLAVOR_2'].$manifest['acceptable_sugar_flavors'][0];
        }
    }

    return '';
}
}

/**
 * deletes files created by unzipping a package
 */
function unlinkUWTempFiles() {
	global $sugar_config;
	global $path;

	logThis('at unlinkUWTempFiles()');
	$tempDir='';
	list($upgDir, $tempDir) = getUWDirs();

    if(file_exists($tempDir) && is_dir($tempDir)){
		$files = findAllFiles($tempDir, array(), false);
		rsort($files);
		foreach($files as $file) {
			if(!is_dir($file)) {
				@unlink($file);
			}
		}
		// now do dirs
		$files = findAllFiles($tempDir, array(), true);
		foreach($files as $dir) {
			if(is_dir($dir)) {
				@rmdir($dir);
			}
		}
		$cacheFile = sugar_cached("modules/UpgradeWizard/_persistence.php");
		if(is_file($cacheFile)) {
			logThis("Unlinking Upgrade cache file: '_persistence.php'", $path);
			@unlink($cacheFile);
		}
	}
	logThis("finished!");
}

/**
 * finds all files in the passed path, but skips select directories
 * @param string dir Relative path
 * @param array the_array Collections of found files/dirs
 * @param bool include_dir True if we want to include directories in the
 * returned collection
 */
function uwFindAllFiles($dir, $theArray, $includeDirs=false, $skipDirs=array(), $echo=false) {
	// check skips
    if (whetherNeedToSkipDir($dir, $skipDirs))
	{
	    return $theArray;
	}

    if (!is_dir($dir)) { return $theArray; }   // Bug # 46035, just checking for valid dir
	$d = dir($dir);
    if ($d === false)  { return $theArray; }   // Bug # 46035, more checking

	while($f = $d->read()) {
	                                // bug 40793 Skip Directories array in upgradeWizard does not function correctly
	    if($f == "." || $f == ".." || whetherNeedToSkipDir("$dir/$f", $skipDirs)) { // skip *nix self/parent
	        continue;
	    }

		// for AJAX length count
    	if($echo) {
	    	echo '.';
	    	ob_flush();
    	}

	    if(is_dir("$dir/$f")) {
			if($includeDirs) { // add the directory if flagged
				$theArray[] = clean_path("$dir/$f");
			}

			// recurse in
	        $theArray = uwFindAllFiles("$dir/$f/", $theArray, $includeDirs, $skipDirs, $echo);
	    } else {
	        $theArray[] = clean_path("$dir/$f");
	    }


	}
	rsort($theArray);
	$d->close();
	return $theArray;
}



/**
 * unset's UW's Session Vars
 */
function resetUwSession() {
	logThis('resetting $_SESSION');

	if(isset($_SESSION['committed']))
		unset($_SESSION['committed']);
	if(isset($_SESSION['sugar_version_file']))
		unset($_SESSION['sugar_version_file']);
	if(isset($_SESSION['upgrade_complete']))
		unset($_SESSION['upgrade_complete']);
	if(isset($_SESSION['allTables']))
		unset($_SESSION['allTables']);
	if(isset($_SESSION['alterCustomTableQueries']))
		unset($_SESSION['alterCustomTableQueries']);
	if(isset($_SESSION['skip_zip_upload']))
		unset($_SESSION['skip_zip_upload']);
	if(isset($_SESSION['install_file']))
		unset($_SESSION['install_file']);
	if(isset($_SESSION['unzip_dir']))
		unset($_SESSION['unzip_dir']);
	if(isset($_SESSION['zip_from_dir']))
		unset($_SESSION['zip_from_dir']);
	if(isset($_SESSION['overwrite_files']))
		unset($_SESSION['overwrite_files']);
	if(isset($_SESSION['schema_change']))
		unset($_SESSION['schema_change']);
	if(isset($_SESSION['uw_restore_dir']))
		unset($_SESSION['uw_restore_dir']);
	if(isset($_SESSION['step']))
		unset($_SESSION['step']);
	if(isset($_SESSION['files']))
		unset($_SESSION['files']);
	if(isset($_SESSION['Upgraded451Wizard'])){
		unset($_SESSION['Upgraded451Wizard']);
	}
	if(isset($_SESSION['Initial_451to500_Step'])){
		unset($_SESSION['Initial_451to500_Step']);
	}
	if(isset($_SESSION['license_shown']))
		unset($_SESSION['license_shown']);
    if(isset($_SESSION['sugarMergeRunResults']))
		unset($_SESSION['sugarMergeRunResults']);
}

/**
 * runs rebuild scripts
 */
function UWrebuild() {
}

function getCustomTables() {
	global $db;

    return $db->tablesLike('%_cstm');
}

function alterCustomTables($customTables)
{
	return array();
}

function getAllTables() {
	global $db;
    return $db->getTablesArray();
}

function printAlterTableSql($tables)
{
	$alterTableSql = '';

	foreach($tables as $table)
		$alterTableSql .= "ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;" . "\n";

	return $alterTableSql;
}

function executeConvertTablesSql($tables)
{
	global $db;

	foreach($tables as $table){
		$query = "ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
		if(!empty($table)){
			logThis("Sending query: ".$query);
            $db->query($query);//, true, "An error has occured while performing db query.  See log file for details.<br>");
         }
	}
	return true;
}

///////////////////////////////////////////////////////////////////////////////
////	SYSTEM CHECK FUNCTIONS
/**
 * generates an array with all files in the SugarCRM root directory, skipping
 * cache/
 * @return array files Array of files with absolute paths
 */
function getFilesForPermsCheck() {
	global $sugar_config;

	// add directories here that should be skipped when doing file permissions checks (cache/upload is the nasty one)
	$skipDirs = array(
		$sugar_config['upload_dir'],
	);
	$files = uwFindAllFiles(".", array(), true, $skipDirs, true);
	return $files;
}

/**
 * checks files for permissions
 * @param array files Array of files with absolute paths
 * @return string result of check
 */
function checkFiles($files, $echo=false) {
	global $mod_strings;
	$filesNotWritable = array();
	$i=0;
	$filesOut = "
		<a href='javascript:void(0); toggleNwFiles(\"filesNw\");'>{$mod_strings['LBL_UW_SHOW_NW_FILES']}</a>
		<div id='filesNw' style='display:none;'>
		<table cellpadding='3' cellspacing='0' border='0'>
		<tr>
			<th align='left'>{$mod_strings['LBL_UW_FILE']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_PERMS']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_OWNER']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_GROUP']}</th>
		</tr>";

	$isWindows = is_windows();
	foreach($files as $file) {

		if($isWindows) {
			if(!is_writable_windows($file)) {
				logThis('WINDOWS: File ['.$file.'] not readable - saving for display');
			}
		} else {
			if(!is_writable($file)) {
				logThis('File ['.$file.'] not writable - saving for display');
				// don't warn yet - we're going to use this to check against replacement files
				$filesNotWritable[$i] = $file;
				$filesNWPerms[$i] = substr(sprintf('%o',fileperms($file)), -4);
				$owner = posix_getpwuid(fileowner($file));
				$group = posix_getgrgid(filegroup($file));
				$filesOut .= "<tr>".
								"<td><span class='error'>{$file}</span></td>".
								"<td>{$filesNWPerms[$i]}</td>".
								"<td>".$owner['name']."</td>".
								"<td>".$group['name']."</td>".
							  "</tr>";
			}
		}
		$i++;
	}

	$filesOut .= '</table></div>';
	// not a stop error
	$errors['files']['filesNotWritable'] = (count($filesNotWritable) > 0) ? true : false;
	if(count($filesNotWritable) < 1) {
		$filesOut = "{$mod_strings['LBL_UW_FILE_NO_ERRORS']}";
	}

	return $filesOut;
}

function deletePackageOnCancel(){
	global $mod_strings;
	global $sugar_config;
	list($base_upgrade_dir, $base_tmp_upgrade_dir) = getUWDirs();
	logThis('running delete');
    if(!isset($_SESSION['install_file']) || ($_SESSION['install_file'] == "")) {
    	logThis('ERROR: trying to delete non-existent file: ['.$_REQUEST['install_file'].']');
        $error = $mod_strings['ERR_UW_NO_FILE_UPLOADED'];
    }
    // delete file in upgrades/patch
    $delete_me = "$base_upgrade_dir/patch/".basename(urldecode( $_REQUEST['install_file'] ));
    if(@unlink($delete_me)) {
        $out = basename($delete_me).$mod_strings['LBL_UW_FILE_DELETED'];
    } else {
    	logThis('ERROR: could not delete ['.$delete_me.']');
		$error = $mod_strings['ERR_UW_FILE_NOT_DELETED'].$delete_me;
    }

    if(!empty($error)) {
		$out = "<b><span class='error'>{$error}</span></b><br />";
    }
}

function handleExecuteSqlKeys($db, $tableName, $disable)
{
    if(empty($tableName)) return true;
    if(is_callable(array($db, "supports"))) {
        // new API
        return $disable?$db->disableKeys($tableName):$db->enableKeys($tableName);
    } else {
        // old api
        $op = $disable?"DISABLE":"ENABLE";
        return $db->query("ALTER TABLE $tableName $op KEYS");
    }
}

function parseAndExecuteSqlFile($sqlScript,$forStepQuery='',$resumeFromQuery='')
{
	global $sugar_config;
	$alterTableSchema = '';
	$sqlErrors = array();
	if(!isset($_SESSION['sqlSkippedQueries'])){
		$_SESSION['sqlSkippedQueries'] = array();
	}
	$db = DBManagerFactory::getInstance();
	$disable_keys = ($db->dbType == "mysql"); // have to use old way for now for upgrades
	if(strpos($resumeFromQuery,",") != false){
		$resumeFromQuery = explode(",",$resumeFromQuery);
	}
	if(file_exists($sqlScript)) {
		$fp = fopen($sqlScript, 'r');
		$contents = stream_get_contents($fp);
		$anyScriptChanges =$contents;
		$resumeAfterFound = false;
		if(rewind($fp)) {
			$completeLine = '';
			$count = 0;
			while($line = fgets($fp)) {
				if(strpos($line, '--') === false) {
					$completeLine .= " ".trim($line);
					if(strpos($line, ';') !== false) {
						$query = '';
						$query = str_replace(';','',$completeLine);
						//if resume from query is not null then find out from where
						//it should start executing the query.

						if($query != null && $resumeFromQuery != null){
							if(!$resumeAfterFound){
								if(strpos($query,",") != false){
									$queArray = explode(",",$query);
									for($i=0;$i<sizeof($resumeFromQuery);$i++){
										if(strcasecmp(trim($resumeFromQuery[$i]),trim($queArray[$i]))==0){
											$resumeAfterFound = true;
										} else {
											$resumeAfterFound = false;
											break;
										}
									}//for

								}
								elseif(strcasecmp(trim($resumeFromQuery),trim($query))==0){
									$resumeAfterFound = true;
								}
							}
							if($resumeAfterFound){
								$count++;
							}
							// if $count=1 means it is just found so skip the query. Run the next one
							if($query != null && $resumeAfterFound && $count >1){
    							$tableName = getAlterTable($query);
								if($disable_keys)
								{
									handleExecuteSqlKeys($db, $tableName, true);
								}
								$db->query($query);
								if($db->checkError()){
									//put in the array to use later on
									$_SESSION['sqlSkippedQueries'][] = $query;
								}
								if($disable_keys)
								{
									handleExecuteSqlKeys($db, $tableName, false);
								}
								$progQuery[$forStepQuery]=$query;
								post_install_progress($progQuery,$action='set');
							}//if
						}
						elseif($query != null){
							$tableName = getAlterTable($query);
							if($disable_keys)
							{
								handleExecuteSqlKeys($db, $tableName, true);
							}
							$db->query($query);
							if($disable_keys)
							{
								handleExecuteSqlKeys($db, $tableName, false);
							}
							$progQuery[$forStepQuery]=$query;
							post_install_progress($progQuery,$action='set');
							if($db->checkError()){
								//put in the array to use later on
								$_SESSION['sqlSkippedQueries'][] = $query;
							}
						}
						$completeLine = '';
					}
				}
			}//while
		}
	}
}


function getAlterTable($query){
	$query = strtolower($query);
	if (preg_match('/^\s*alter\s+table\s+/', $query)) {
		$sqlArray = explode(" ", $query);
		$key = array_search('table', $sqlArray);
		return $sqlArray[($key+1)];
	}else {
		return '';
	}
}

function set_upgrade_vars(){
	logThis('setting session variables...');
	$upgrade_progress_dir = sugar_cached('upgrades/temp');
	if(!is_dir($upgrade_progress_dir)){
		mkdir_recursive($upgrade_progress_dir);
	}
	$upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
	if(file_exists($upgrade_progress_file)){
		include($upgrade_progress_file);
	}
	else{
		fopen($upgrade_progress_file, 'w+');
	}
	if(!isset($upgrade_config) || $upgrade_config == null){
		$upgrade_config = array();
		$upgrade_config[1]['upgrade_vars']=array();
	}
    if(isset($upgrade_config[1]) && isset($upgrade_config[1]['upgrade_vars']) && !is_array($upgrade_config[1]['upgrade_vars'])){
    	$upgrade_config[1]['upgrade_vars'] = array();
    }

	if(!isset($upgrade_vars) || $upgrade_vars == null){
		$upgrade_vars = array();
	}
	if(isset($_SESSION['unzip_dir']) && !empty($_SESSION['unzip_dir']) && file_exists($_SESSION['unzip_dir'])){
		$upgrade_vars['unzip_dir']=$_SESSION['unzip_dir'];
	}
	if(isset($_SESSION['install_file']) && !empty($_SESSION['install_file']) && file_exists($_SESSION['install_file'])){
		$upgrade_vars['install_file']=$_SESSION['install_file'];
	}
	if(isset($_SESSION['Upgraded451Wizard']) && !empty($_SESSION['Upgraded451Wizard'])){
		$upgrade_vars['Upgraded451Wizard']=$_SESSION['Upgraded451Wizard'];
	}
	if(isset($_SESSION['license_shown']) && !empty($_SESSION['license_shown'])){
		$upgrade_vars['license_shown']=$_SESSION['license_shown'];
	}
	if(isset($_SESSION['Initial_451to500_Step']) && !empty($_SESSION['Initial_451to500_Step'])){
		$upgrade_vars['Initial_451to500_Step']=$_SESSION['Initial_451to500_Step'];
	}
	if(isset($_SESSION['zip_from_dir']) && !empty($_SESSION['zip_from_dir'])){
		$upgrade_vars['zip_from_dir']=$_SESSION['zip_from_dir'];
	}
	//place into the upgrade_config array and rewrite config array only if new values are being inserted
	if(isset($upgrade_vars) && $upgrade_vars != null && sizeof($upgrade_vars) > 0){
		foreach($upgrade_vars as $key=>$val){
			if($key != null && $val != null){
				$upgrade_config[1]['upgrade_vars'][$key]=$upgrade_vars[$key];
			}
		}
		ksort($upgrade_config);
		if(is_writable($upgrade_progress_file) && write_array_to_file( "upgrade_config", $upgrade_config,
			$upgrade_progress_file)) {
		       //writing to the file
		}
    }
}

function initialize_session_vars(){
  $upgrade_progress_dir = sugar_cached('upgrades/temp');
  $upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
  if(file_exists($upgrade_progress_file)){
  	include($upgrade_progress_file);
  	if(isset($upgrade_config) && $upgrade_config != null && is_array($upgrade_config) && sizeof($upgrade_config) >0){
	  	$currVarsArray=$upgrade_config[1]['upgrade_vars'];
	  	if(isset($currVarsArray) && $currVarsArray != null && is_array($currVarsArray) && sizeof($currVarsArray)>0){
	  		foreach($currVarsArray as $key=>$val){
	  			if($key != null && $val !=null){
		  			//set session variables
		  			$_SESSION[$key]=$val;
		  			//set varibales
					'$'.$key=$val;
	  			}
	  		}
	  	}
  	}
  }
}
//track the upgrade progress on each step
//track the upgrade progress on each step
function set_upgrade_progress($currStep,$currState,$currStepSub='',$currStepSubState=''){

	$upgrade_progress_dir = sugar_cached('upgrades/temp');
	if(!is_dir($upgrade_progress_dir)){
		mkdir_recursive($upgrade_progress_dir);
	}
	$upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
	if(file_exists($upgrade_progress_file)){
		include($upgrade_progress_file);
	}
	else{
		if(function_exists('sugar_fopen')){
			sugar_fopen($upgrade_progress_file, 'w+');
		}
		else{
			fopen($upgrade_progress_file, 'w+');
		}
	}
	if(!isset($upgrade_config) || $upgrade_config == null){
		$upgrade_config = array();
		$upgrade_config[1]['upgrade_vars']=array();
	}
    if(!is_array($upgrade_config[1]['upgrade_vars'])){
    	$upgrade_config[1]['upgrade_vars'] = array();
    }
   	if($currStep != null && $currState != null){
		if(sizeof($upgrade_config) > 0){
			if($currStepSub != null && $currStepSubState !=null){
				//check if new status to be set or update
				//get the latest in array. since it has sub components prepare an array
				if(!empty($upgrade_config[sizeof($upgrade_config)][$currStep]) && is_array($upgrade_config[sizeof($upgrade_config)][$currStep])){
					$latestStepSub = currSubStep($upgrade_config[sizeof($upgrade_config)][$currStep]);
					if($latestStepSub == $currStepSub){
						$upgrade_config[sizeof($upgrade_config)][$currStep][$latestStepSub]=$currStepSubState;
						$upgrade_config[sizeof($upgrade_config)][$currStep][$currStep] = $currState;
					}
					else{
						$upgrade_config[sizeof($upgrade_config)][$currStep][$currStepSub]=$currStepSubState;
						$upgrade_config[sizeof($upgrade_config)][$currStep][$currStep] = $currState;
					}
				}
				else{
					$currArray = array();
					$currArray[$currStep] = $currState;
					$currArray[$currStepSub] = $currStepSubState;
					$upgrade_config[sizeof($upgrade_config)+1][$currStep] = $currArray;
				}
			}
          else{
				//get the current upgrade progress
				$latestStep = get_upgrade_progress();
				//set the upgrade progress
				if($latestStep == $currStep){
					//update the current step with new progress status
					$upgrade_config[sizeof($upgrade_config)][$latestStep]=$currState;
				}
				else{
					//it's a new step
					$upgrade_config[sizeof($upgrade_config)+1][$currStep]=$currState;
				}
	            // now check if there elements within array substeps
          }
		}
		else{
			//set the upgrade progress  (just starting)
			$upgrade_config[sizeof($upgrade_config)+1][$currStep]= $currState;
		}

		if(is_writable($upgrade_progress_file) && write_array_to_file( "upgrade_config", $upgrade_config,
		$upgrade_progress_file)) {
	       //writing to the file
		}

   	}
}

function get_upgrade_progress(){
	$upgrade_progress_dir = sugar_cached('upgrades/temp');
	$upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
	$currState = '';

	if(file_exists($upgrade_progress_file)){
		include($upgrade_progress_file);
		if(!isset($upgrade_config) || $upgrade_config == null){
			$upgrade_config = array();
		}
		if($upgrade_config != null && sizeof($upgrade_config) >1){
			$currArr = $upgrade_config[sizeof($upgrade_config)];
			if(is_array($currArr)){
			   foreach($currArr as $key=>$val){
					$currState = $key;
				}
			}
		}
	}
	return $currState;
}
function currSubStep($currStep){
	$currSubStep = '';
	if(is_array($currStep)){
       foreach($currStep as $key=>$val){
		    if($key != null){
			$currState = $key;
		  	}
	   }
	}
	return $currState;
}
function currUpgradeState($currState){
	$currState = '';
	if(is_array($currState)){
       foreach($currState as $key=>$val){
			if(is_array($val)){
			  	foreach($val as $k=>$v){
			  		if($k != null){
						$currState = $k;
			  		}
			  	}
			}
			else{
				$currState = $key;
			}
		}
	}
	return $currState;
}

function didThisStepRunBefore($step,$SubStep=''){
	if($step == null) return;
	$upgrade_progress_dir = sugar_cached('upgrades/temp');
	$upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
	$currState = '';
	$stepRan = false;
	if(file_exists($upgrade_progress_file)){
		include($upgrade_progress_file);
		if(isset($upgrade_config) && $upgrade_config != null && is_array($upgrade_config) && sizeof($upgrade_config) >0){
			for($i=1;$i<=sizeof($upgrade_config);$i++){
			  if(is_array($upgrade_config[$i])){
					foreach($upgrade_config[$i] as $key=>$val){
						if($key==$step){
							if(is_array($upgrade_config[$i][$step])){
								//now process
								foreach ($upgrade_config[$i][$step] as $k=>$v){
									if(is_array($v)){
										foreach($v as $k1=>$v1){
											if($SubStep != null){
												if($SubStep ==$k1 && $v1=='done'){
													$stepRan = true;
													break;
												}
											}
										}//foreach
									}
									elseif($SubStep !=null){
										if($SubStep==$k && $v=='done'){
											$stepRan = true;
											break;
										}
									}
									elseif($step==$k && $v=='done'){
										$stepRan = true;
										break;
									}
								}//foreach
							}
							elseif($val=='done'){
								$stepRan = true;
							}
						}
					}//foreach
				}
		 	}//for
	   	}
	}
	return $stepRan;
}



//get and set post install status
function post_install_progress($progArray='',$action=''){
	$upgrade_progress_dir = sugar_cached('upgrades/temp');
	$upgrade_progress_file = $upgrade_progress_dir.'/upgrade_progress.php';
    if($action=='' || $action=='get'){
		//get the state of post install
        $currProg = array();
		if(file_exists($upgrade_progress_file)){
			include($upgrade_progress_file);
			if(is_array($upgrade_config[sizeof($upgrade_config)]['commit']['post_install']) && sizeof($upgrade_config[sizeof($upgrade_config)]['commit']['post_install'])>0){
				foreach($upgrade_config[sizeof($upgrade_config)]['commit']['post_install'] as $k=>$v){
					$currProg[$k]=$v;
				}
			}
		}
		return $currProg;
	}
	elseif($action=='set'){
		if(!is_dir($upgrade_progress_dir)){
			mkdir($upgrade_progress_dir);
		}
		if(file_exists($upgrade_progress_file)){
			include($upgrade_progress_file);
		}
		else{
			fopen($upgrade_progress_file, 'w+');
		}
		if(empty($upgrade_config[sizeof($upgrade_config)]['commit']['post_install']) || !is_array($upgrade_config[sizeof($upgrade_config)]['commit']['post_install'])){
			$upgrade_config[sizeof($upgrade_config)]['commit']['post_install']=array();
			$upgrade_config[sizeof($upgrade_config)]['commit']['post_install']['post_install'] = 'in_progress';
		}
		if($progArray != null && is_array($progArray)){
			foreach($progArray as $key=>$val){
				$upgrade_config[sizeof($upgrade_config)]['commit']['post_install'][$key]=$val;
			}
		}
		if(is_writable($upgrade_progress_file) && write_array_to_file( "upgrade_config", $upgrade_config,
		$upgrade_progress_file)) {
	       //writing to the file
		}
	}
}

function repairDBForUpgrade($execute=false,$path=''){

	global $current_user, $beanFiles;
	global $dictionary;
	set_time_limit(3600);

    $db = DBManagerFactory::getInstance();
	$sql = '';
	VardefManager::clearVardef();
	require_once('include/ListView/ListView.php');
	foreach ($beanFiles as $bean => $file) {
		require_once ($file);
		$focus = new $bean ();
		$sql .= $db->repairTable($focus, $execute);

	}
	$olddictionary = $dictionary;
	unset ($dictionary);
	include ('modules/TableDictionary.php');
	foreach ($dictionary as $meta) {
		$tablename = $meta['table'];
		$fielddefs = $meta['fields'];
        $indices = isset($meta['indices']) ? $meta['indices'] : [];
		$sql .= $db->repairTableParams($tablename, $fielddefs, $indices, $execute);
	}
	 $qry_str = "";
	  foreach (explode("\n", $sql) as $line) {
		  if (!empty ($line) && substr($line, -2) != "*/") {
		  	$line .= ";";
		  }
	  	  $qry_str .= $line . "\n";
	   }
	  $sql = str_replace(
	  array(
	  	"\n",
		'&#039;',
	   ),
	  array(
	  	'',
		"'",
	  ),
	  preg_replace('#(/\*.+?\*/\n*)#', '', $qry_str)
	  );
	 logThis("*******START EXECUTING DB UPGRADE QUERIES***************",$path);
	 	logThis($sql,$path);
	 logThis("*******END EXECUTING DB UPGRADE QUERIES****************",$path);
	 if(!$execute){
	 	return $sql;
	 }
}

/**
 * upgradeDashletsForSalesAndMarketing
 *
 */
function upgradeDashletsForSalesAndMarketing() {
	if(file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
   	   require($cachedfile);
   	}

   	if(file_exists('modules/Home/dashlets.php')) {
   	   require('modules/Home/dashlets.php');
   	}



    require_once('include/MySugar/MySugar.php');

    $prefstomove = array(
        'mypbss_date_start' => 'MyPipelineBySalesStageDashlet',
        'mypbss_date_end' => 'MyPipelineBySalesStageDashlet',
        'mypbss_sales_stages' => 'MyPipelineBySalesStageDashlet',
        'mypbss_chart_type' => 'MyPipelineBySalesStageDashlet',
        'lsbo_lead_sources' => 'OpportunitiesByLeadSourceByOutcomeDashlet',
        'lsbo_ids' => 'OpportunitiesByLeadSourceByOutcomeDashlet',
        'pbls_lead_sources' => 'OpportunitiesByLeadSourceDashlet',
        'pbls_ids' => 'OpportunitiesByLeadSourceDashlet',
        'pbss_date_start' => 'PipelineBySalesStageDashlet',
        'pbss_date_end' => 'PipelineBySalesStageDashlet',
        'pbss_sales_stages' => 'PipelineBySalesStageDashlet',
        'pbss_chart_type' => 'PipelineBySalesStageDashlet',
        'obm_date_start' => 'OutcomeByMonthDashlet',
        'obm_date_end' => 'OutcomeByMonthDashlet',
        'obm_ids' => 'OutcomeByMonthDashlet');

	$GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Home');
    $db = DBManagerFactory::getInstance();
    $result = $db->query("SELECT id FROM users where deleted = '0'");

   	while($row = $db->fetchByAssoc($result)) {
	      $current_user = new User();
	      $current_user->retrieve($row['id']);

	      //Set the user theme to be 'Sugar' theme since this is run for CE flavor conversions
	      $current_user->setPreference('user_theme', 'Sugar', 0, 'global');

		  $pages = $current_user->getPreference('pages', 'Home');

		  if(empty($pages)) {
             continue;
		  }

		  $empty_dashlets = array();
		  $dashlets = $current_user->getPreference('dashlets', 'Home');
		  $dashlets = !empty($dashlets) ? $dashlets : $empty_dashlets;
   		  $existingDashlets = array();
   		  foreach($dashlets as $id=>$dashlet) {
   		  	      if(!empty($dashlet['className']) && !is_array($dashlet['className'])) {
		  	         $existingDashlets[$dashlet['className']] = $dashlet['className'];
   		  	      }
		  } //foreach


		    // BEGIN 'Sales Page'
		    $salesDashlets = array();
		    foreach($defaultSalesDashlets as $salesDashletName=>$module){
				// clint - fixes bug #20398
				// only display dashlets that are from visibile modules and that the user has permission to list
				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();
		    	if (isset($dashletsFiles[$salesDashletName]) && $displayDashlet){
		            $options = array();
		            $prefsforthisdashlet = array_keys($prefstomove,$salesDashletName);
		            foreach ( $prefsforthisdashlet as $pref ) {
		               $options[$pref] = $current_user->getPreference($pref);
		            }

		            $salesDashlets[create_guid()] = array('className' => $salesDashletName,
												 'module'=>$module,
			                                         'fileLocation' => $dashletsFiles[$salesDashletName]['file'],
		                                             'options' => $options);
		    	}
		    }

		    foreach ($defaultSalesChartDashlets as $salesChartDashlet=>$module) {
				$savedReport = new SavedReport();
				$reportId = $savedReport->retrieveReportIdByName($salesChartDashlet);
				// clint - fixes bug #20398
				// only display dashlets that are from visibile modules and that the user has permission to list
				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

				if(isset($reportId) && $displayDashlet) {
		    		$salesDashlets[create_guid()] = array('className' => 'ChartsDashlet',
													 	  'module'=>$module,
		    											  'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
		    											  'reportId' => $reportId);
		    	}
		    }

		    $count = 0;
		    $salesColumns = array();
		    $salesColumns[0] = array();
		    $salesColumns[0]['width'] = '60%';
		    $salesColumns[0]['dashlets'] = array();
		    $salesColumns[1] = array();
		    $salesColumns[1]['width'] = '40%';
		    $salesColumns[1]['dashlets'] = array();

		    foreach($salesDashlets as $guid=>$dashlet){
		        if($count % 2 == 0) array_push($salesColumns[0]['dashlets'], $guid);
		        else array_push($salesColumns[1]['dashlets'], $guid);
		        $count++;
		    }
		    // END 'Sales Page'

			// BEGIN 'Marketing Page'
			$marketingDashlets = array();
		    foreach ($defaultMarketingChartDashlets as $marketingChartDashlet=>$module){
				$savedReport = new SavedReport();
				$reportId = $savedReport->retrieveReportIdByName($marketingChartDashlet);
				// clint - fixes bug #20398
				// only display dashlets that are from visibile modules and that the user has permission to list
				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

				if(isset($reportId) && $displayDashlet) {
		    		$marketingDashlets[create_guid()] = array('className' => 'ChartsDashlet',
													 		'module'=>$module,
		    												'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
		    												'reportId' => $reportId, );
			    }
		    }

		    foreach($defaultMarketingDashlets as $marketingDashletName=>$module){
				// clint - fixes bug #20398
				// only display dashlets that are from visibile modules and that the user has permission to list
				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

		    	if (isset($dashletsFiles[$marketingDashletName]) && $displayDashlet){
			        $options = array();
	            $prefsforthisdashlet = array_keys($prefstomove,$marketingDashletName);
	            foreach ( $prefsforthisdashlet as $pref ) {
	               $options[$pref] = $current_user->getPreference($pref);
	            } //foreach
	            $marketingDashlets[create_guid()] = array('className' => $marketingDashletName,
										 		 'module'=>$module,
		                                         'fileLocation' => $dashletsFiles[$marketingDashletName]['file'],
	                                             'options' => $options);
	    		}
		    }

		    $count = 0;
		    $marketingColumns = array();
		    $marketingColumns[0] = array();
		    $marketingColumns[0]['width'] = '30%';
		    $marketingColumns[0]['dashlets'] = array();
		    $marketingColumns[1] = array();
		    $marketingColumns[1]['width'] = '30%';
		    $marketingColumns[1]['dashlets'] = array();
		    $marketingColumns[2] = array();
		    $marketingColumns[2]['width'] = '40%';
		    $marketingColumns[2]['dashlets'] = array();

		    foreach($marketingDashlets as $guid=>$dashlet){
		        if($count % 3 == 0) array_push($marketingColumns[0]['dashlets'], $guid);
		        else if($count % 3 == 1) array_push($marketingColumns[1]['dashlets'], $guid);
		        else array_push($marketingColumns[2]['dashlets'], $guid);
		        $count++;
		    }
			// END 'Marketing Page'

		    // BEGIN 'Support Page'- bug46195
			$supportDashlets = array();
		    foreach ($defaultSupportChartDashlets as $supportChartDashlet=>$module){
				$savedReport = new SavedReport();
				$reportId = $savedReport->retrieveReportIdByName($supportChartDashlet);
				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

				if(isset($reportId) && $displayDashlet) {
		    		$supportDashlets[create_guid()] = array('className' => 'ChartsDashlet',
													 		'module'=>$module,
		    												'fileLocation' => $dashletsFiles['ChartsDashlet']['file'],
		    												'reportId' => $reportId, );
			    }
		    }

		    foreach($defaultSupportDashlets as $supportDashletName=>$module){

				$myDashlet = new MySugar($module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

		    	if (isset($dashletsFiles[$supportDashletName]) && $displayDashlet){
			        $options = array();
	            $prefsforthisdashlet = array_keys($prefstomove,$supportDashletName);
	            foreach ( $prefsforthisdashlet as $pref ) {
	               $options[$pref] = $current_user->getPreference($pref);
	            } //foreach
	            $supportDashlets[create_guid()] = array('className' => $supportDashletName,
										 		 'module'=>$module,
		                                         'fileLocation' => $dashletsFiles[$supportDashletName]['file'],
	                                             'options' => $options);
	    		}
		    }

		    $count = 0;
		    $supportColumns = array();
		    $supportColumns[0] = array();
		    $supportColumns[0]['width'] = '30%';
		    $supportColumns[0]['dashlets'] = array();
		    $supportColumns[1] = array();
		    $supportColumns[1]['width'] = '30%';
		    $supportColumns[1]['dashlets'] = array();
		    $supportColumns[2] = array();
		    $supportColumns[2]['width'] = '40%';
		    $supportColumns[2]['dashlets'] = array();

		    foreach($supportDashlets as $guid=>$dashlet){
		        if($count % 3 == 0) array_push($supportColumns[0]['dashlets'], $guid);
		        else if($count % 3 == 1) array_push($supportColumns[1]['dashlets'], $guid);
		        else array_push($supportColumns[2]['dashlets'], $guid);
		        $count++;
		    }
			// END ' Support Page' - bug 46195


		   	//Set the dashlets pages to user preferences table
		   	$pageIndex = count($pages);
			$pages[$pageIndex]['columns'] = $salesColumns;
			$pages[$pageIndex]['numColumns'] = '2';
			$pages[$pageIndex]['pageTitle'] = $GLOBALS['mod_strings']['LBL_HOME_PAGE_2_NAME'];	// "Sales Page"
			$pageIndex++;

			$pages[$pageIndex]['columns'] = $marketingColumns;
			$pages[$pageIndex]['numColumns'] = '3';
			$pages[$pageIndex]['pageTitle'] = $GLOBALS['mod_strings']['LBL_HOME_PAGE_6_NAME'];	// "Marketing Page"
			$pageIndex++;

			$pages[$pageIndex]['columns'] = $supportColumns;
			$pages[$pageIndex]['numColumns'] = '4';
			$pages[$pageIndex]['pageTitle'] = $GLOBALS['mod_strings']['LBL_HOME_PAGE_3_NAME'];	// " Support Page" - bug 46195

		    $dashlets = array_merge($dashlets, $salesDashlets, $marketingDashlets, $supportDashlets);
		    $current_user->setPreference('dashlets', $dashlets, 0, 'Home');
		    $current_user->setPreference('pages', $pages, 0, 'Home');
		} //while

} //upgradeDashletsForSalesAndMarketing


/**
 * upgradeUserPreferences
 * This method updates the user_preferences table and sets the pages/dashlets for users
 * which have ACL access to Trackers so that the Tracker dashlets are set in their user perferences
 *
 */
function upgradeUserPreferences() {
    global $sugar_config, $sugar_version;
    $uw_strings = return_module_language($GLOBALS['current_language'], 'UpgradeWizard');

    $localization = Localization::getObject();
    $localeCoreDefaults = $localization->getLocaleConfigDefaults();

    // check the current system wide default_locale_name_format and add it to the list if it's not there
    if(empty($sugar_config['name_formats'])) {
        $sugar_config['name_formats'] = $localeCoreDefaults['name_formats'];
        if(!rebuildConfigFile($sugar_config, $sugar_version)) {
            $errors[] = $uw_strings['ERR_UW_CONFIG_WRITE'];
        }
    }

    $currentDefaultLocaleNameFormat = $sugar_config['default_locale_name_format'];

    if ($localization->isAllowedNameFormat($currentDefaultLocaleNameFormat)) {
        upgradeLocaleNameFormat($currentDefaultLocaleNameFormat);
    } else {
        $sugar_config['default_locale_name_format'] = $localeCoreDefaults['default_locale_name_format'];
        if(!rebuildConfigFile($sugar_config, $sugar_version)) {
            $errors[] = $uw_strings['ERR_UW_CONFIG_WRITE'];
        }
        $localization->createInvalidLocaleNameFormatUpgradeNotice();
    }

	if(file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
   	   require($cachedfile);
   	} else if(file_exists('modules/Dashboard/dashlets.php')) {
   	   require('modules/Dashboard/dashlets.php');
   	}

	$upgradeTrackingDashlets = array('TrackerDashlet'=>array(
									    'file' => 'modules/Trackers/Dashlets/TrackerDashlet/TrackerDashlet.php',
									    'class' => 'TrackerDashlet',
									    'meta' => 'modules/Trackers/Dashlets/TrackerDashlet/TrackerDashlet.meta.php',
									    'module' => 'Trackers',
									 ),
									 'MyModulesUsedChartDashlet'=>array(
									    'file' => 'modules/Charts/Dashlets/MyModulesUsedChartDashlet/MyModulesUsedChartDashlet.php',
									    'class' => 'MyModulesUsedChartDashlet',
									    'meta' => 'modules/Charts/Dashlets/MyModulesUsedChartDashlet/MyModulesUsedChartDashlet.meta.php',
									    'module' => 'Trackers',
									 ),
									 'MyTeamModulesUsedChartDashlet'=>array(
									    'file' => 'modules/Charts/Dashlets/MyTeamModulesUsedChartDashlet/MyTeamModulesUsedChartDashlet.php',
									    'class' => 'MyTeamModulesUsedChartDashlet',
									    'meta' => 'modules/Charts/Dashlets/MyTeamModulesUsedChartDashlet/MyTeamModulesUsedChartDashlet.meta.php',
									    'module' => 'Trackers',
									 )
							   );

    $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Home');

    $ce_to_pro_or_ent = (isset($_SESSION['upgrade_from_flavor']) && preg_match('/^SugarCE.*?(Pro|Ent|Corp|Ult)$/', $_SESSION['upgrade_from_flavor']));

    $db = DBManagerFactory::getInstance();
    $result = $db->query("SELECT id FROM users WHERE " . User::getLicensedUsersWhere());
   	while($row = $db->fetchByAssoc($result))
    {
        $current_user = new User();
        $current_user->retrieve($row['id']);

        // get the user's name locale format, check if it's in our list, add it if it's not, keep it as user's default
        $changed = false;
        $currentUserNameFormat = $current_user->getPreference('default_locale_name_format');
        if ($localization->isAllowedNameFormat($currentUserNameFormat)) {
            upgradeLocaleNameFormat($currentUserNameFormat);
        } else {
            $current_user->setPreference('default_locale_name_format', 's f l', 0, 'global');
            $changed = true;
        }

        if(!$current_user->getPreference('calendar_publish_key')) {
        	// set publish key if not set already
        	$current_user->setPreference('calendar_publish_key', create_guid());
        	$changed = true;
        }

	      //Set the user theme to be 'Sugar' theme since this is run for CE flavor conversions
	      $userTheme = $current_user->getPreference('user_theme', 'global');

          //If theme is empty or if theme was set to Classic (Sugar5) or if this is a ce to pro/ent flavor upgrade change to RacerX theme
	      if(empty($userTheme) || $userTheme == 'Sugar5' || $ce_to_pro_or_ent)
	      {
            $changed = true;
	      	$current_user->setPreference('user_theme', 'RacerX', 0, 'global');
	      }

	      //Set the number of tabs by default to 7
	      $maxTabs = $current_user->getPreference('max_tabs', 'global');
	      if(empty($maxTabs))
	      {
            $changed = true;
	      	$current_user->setPreference('max_tabs', '7', 0, 'global');
	      }

          //If preferences have changed, save before proceeding
          if($changed)
          {
             $current_user->savePreferencesToDB();
          }

		  $pages = $current_user->getPreference('pages', 'Home');

		  if(empty($pages))
          {
             continue;
		  }

          $changed = false;
		  $empty_dashlets = array();
		  $dashlets = $current_user->getPreference('dashlets', 'Home');
		  $dashlets = !empty($dashlets) ? $dashlets : $empty_dashlets;
   		  $existingDashlets = array();
   		  foreach($dashlets as $id=>$dashlet) {
   		  	      if(!empty($dashlet['className']) && !is_array($dashlet['className'])) {
		  	         $existingDashlets[$dashlet['className']] = $dashlet['className'];
   		  	      }
		  }

		  if(ACLController::checkAccess('Trackers', 'view', false, 'Tracker')) {
				$trackingDashlets = array();
			    foreach($upgradeTrackingDashlets as $trackingDashletName=>$entry){
			    	if (empty($existingDashlets[$trackingDashletName])) {
			            $trackingDashlets[create_guid()] = array('className' => $trackingDashletName,
				                                                 'fileLocation' => $entry['file'],
			                                                     'options' => array());
			    	}
			    }

			    if(empty($trackingDashlets)) {
			       continue;
			    }

			    $trackingColumns = array();
			    $trackingColumns[0] = array();
			    $trackingColumns[0]['width'] = '50%';
			    $trackingColumns[0]['dashlets'] = array();

			    foreach($trackingDashlets as $guid=>$dashlet){
			            array_push($trackingColumns[0]['dashlets'], $guid);
			    }

			    //Set the tracker dashlets to user preferences table
		 		$dashlets = array_merge($dashlets, $trackingDashlets);
		 		$current_user->setPreference('dashlets', $dashlets, 0, 'Home');

		    	//Set the dashlets pages to user preferences table
		    	$pageIndex = count($pages);
				$pages[$pageIndex]['columns'] = $trackingColumns;
				$pages[$pageIndex]['numColumns'] = '1';
				$pages[$pageIndex]['pageTitle'] = $GLOBALS['mod_strings']['LBL_HOME_PAGE_4_NAME'];
				$current_user->setPreference('pages', $pages, 0, 'Home');
                $changed = true;
		  } //if

        // we need to force save the changes to disk, otherwise we lose them.
        if($changed)
        {
            $current_user->savePreferencesToDB();
        }

	} //while

    /*
	 * This section checks to see if the Tracker settings for the corresponding versions have been
	 * disabled and the regular tracker (for breadcrumbs) enabled.  If so, then it will also disable
	 * the tracking for the regular tracker.  Disabling the tracker (for breadcrumbs) will no longer prevent
	 * breadcrumb tracking.  It will instead only track visible entries (see trackView() method in SugarView.php).
	 * This has the benefit of reducing the tracking overhead and limiting it to only visible items.
	 * For the CE version, we are checking to see that there are no entries enabled for PRO/ENT versions
	 * we are checking for Tracker sessions, performance and queries.
	 */
	if($ce_to_pro_or_ent) {
		//Set tracker settings. Disable tracker session, performance and queries
		$category = 'tracker';
		$value = 1;
		$key = array('Tracker', 'tracker_sessions','tracker_perf','tracker_queries');
		$admin = new Administration();
		foreach($key as $k){
			$admin->saveSetting($category, $k, $value);
		}
	} else {
	   $query = "select count(name) as total from config where category = 'tracker' and name = 'Tracker'";
	   $results = $db->query($query);
	   if(!empty($results)) {
	       $row = $db->fetchByAssoc($results);
	       $total = $row['total'];
	       if($GLOBALS['sugar_flavor'] == 'PRO' || $GLOBALS['sugar_flavor'] == 'ENT')  {
	       	   //Fix problem with having multiple tracker entries in config table
	       	   if($total > 1) {
	       	   	  $db->query("DELETE FROM config where category = 'tracker' and name = 'Tracker'");
	       	   	  $db->query("INSERT INTO config (category, name, value) VALUES ('tracker', 'Tracker', '1')");
	       	   }
	       } else {
		       //We are assuming if the 'Tracker' setting is not disabled then we will just disable it
		       if($total == 0) {
		       	  $db->query("INSERT INTO config (category, name, value) VALUES ('tracker', 'Tracker', '1')");
		       }
	       }
	   }
	}

	//Write the entries to cache/dashlets/dashlets.php
	if(file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
	   require($cachedfile);
	   foreach($upgradeTrackingDashlets as $id=>$entry) {
	   	   if(!isset($dashletsFiles[$id])) {
	   	   	  $dashletsFiles[$id] = $entry;
	   	   }
	   }
	   write_array_to_file("dashletsFiles", $dashletsFiles, $cachedfile);
	} //if
}


/**
 * Checks if a locale name format is part of the default list, if not adds it to the config
 * @param $name_format string a local name format string such as 's f l'
 * @return bool true on successful write to config file, false on failure;
 */
function upgradeLocaleNameFormat($name_format) {
    global $sugar_config, $sugar_version;

    $localization = Localization::getObject();
    $localeConfigDefaults = $localization->getLocaleConfigDefaults();

    $uw_strings = return_module_language($GLOBALS['current_language'], 'UpgradeWizard');
    if(empty($sugar_config['name_formats'])) {
        $sugar_config['name_formats'] = $localeConfigDefaults['name_formats'];
        if(!rebuildConfigFile($sugar_config, $sugar_version)) {
            $errors[] = $uw_strings['ERR_UW_CONFIG_WRITE'];
        }
    }
    if (!in_array($name_format, $sugar_config['name_formats'])) {
        $new_config = sugarArrayMerge($sugar_config['name_formats'], array($name_format=>$name_format));
        $sugar_config['name_formats'] = $new_config;
        if(!rebuildConfigFile($sugar_config, $sugar_version)) {
            $errors[] = $uw_strings['ERR_UW_CONFIG_WRITE'];
            return false;
        }
    }
    return true;
}


function migrate_sugar_favorite_reports(){
    require_once('modules/SugarFavorites/SugarFavorites.php');

    // Need to repair the RC1 instances that have incorrect GUIDS
    $deleteRows = array();
    $res = $GLOBALS['db']->query("select * from sugarfavorites where module='Reports'");
    while($row = $GLOBALS['db']->fetchByAssoc($res)){
        $expectedId = SugarFavorites::generateGUID('Reports', $row['record_id'], $row['assigned_user_id']);
        if ($row['id'] != $expectedId) {
            $deleteRows[] = $row['id'];
        }
    }
    $GLOBALS['db']->query("delete from sugarfavorites where id in ('" . implode("','",$deleteRows) . "')");
    // End Repair


    $active_users = array();
    $res = $GLOBALS['db']->query("select id, user_name, deleted, status from users where is_group = 0 and portal_only = 0 and status = 'Active' and deleted = 0");
    while($row = $GLOBALS['db']->fetchByAssoc($res)){
        $active_users[] = $row['id'];
    }

    foreach($active_users as $user_id){
        $user = new User();
        $user->retrieve($user_id);

        $user_favorites = $user->getPreference('favorites', 'Reports');
        if(!is_array($user_favorites)) $user_favorites = array();

        if(!empty($user_favorites)){
            foreach($user_favorites as $report_id => $bool){
                $fav = new SugarFavorites();
                $record = SugarFavorites::generateGUID('Reports', $report_id, $user_id);
                if(!$fav->retrieve($record, true, false)){
                        $fav->new_with_id = true;
                }
                $fav->id = $record;
                $fav->module = 'Reports';
                $fav->record_id = $report_id;
                $fav->assigned_user_id = $user->id;
                $fav->created_by = $user->id;
                $fav->modified_user_id = $user->id;

                $fav->deleted = 0;
                $fav->save();
            }
        }
    }
}

function add_custom_modules_favorites_search(){
    $module_directories = scandir('modules');

	foreach($module_directories as $module_dir){
		if($module_dir == '.' || $module_dir == '..' || !is_dir("modules/{$module_dir}")){
			continue;
		}

		$matches = array();
		preg_match('/^[a-z0-9]{1,5}_[a-z0-9_]+$/i' , $module_dir, $matches);

		// Make sure the module was created by module builder
		if(empty($matches)){
			continue;
		}

		$full_module_dir = "modules/{$module_dir}/";
		$read_searchdefs_from = "{$full_module_dir}/metadata/searchdefs.php";
		$read_SearchFields_from = "{$full_module_dir}/metadata/SearchFields.php";
		$read_custom_SearchFields_from = "custom/{$full_module_dir}/metadata/SearchFields.php";

		// Studio can possibly override this file, so we check for a custom version of it
		if(file_exists("custom/{$full_module_dir}/metadata/searchdefs.php")){
			$read_searchdefs_from = "custom/{$full_module_dir}/metadata/searchdefs.php";
		}

		if(file_exists($read_searchdefs_from) && file_exists($read_SearchFields_from)){
			$found_sf1 = false;
			$found_sf2 = false;
			require($read_searchdefs_from);
			foreach($searchdefs[$module_dir]['layout']['basic_search'] as $sf_array){
				if(isset($sf_array['name']) && $sf_array['name'] == 'favorites_only'){
					$found_sf1 = true;
				}
			}

			require($read_SearchFields_from);
			if(isset($searchFields[$module_dir]['favorites_only'])){
				$found_sf2 = true;
			}

			if(!$found_sf1 && !$found_sf2){
				$searchdefs[$module_dir]['layout']['basic_search']['favorites_only'] = array('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',);
				$searchdefs[$module_dir]['layout']['advanced_search']['favorites_only'] = array('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',);
				$searchFields[$module_dir]['favorites_only'] = array(
					'query_type'=>'format',
					'operator' => 'subquery',
					'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites
								WHERE sugarfavorites.deleted=0
									and sugarfavorites.module = \''.$module_dir.'\'
									and sugarfavorites.assigned_user_id = \'{0}\'',
					'db_field'=>array('id')
				);

				if(!is_dir("custom/{$full_module_dir}/metadata")){
					mkdir_recursive("custom/{$full_module_dir}/metadata");
				}
				$success_sf1 = write_array_to_file('searchdefs', $searchdefs, "custom/{$full_module_dir}/metadata/searchdefs.php");
				$success_sf2 = write_array_to_file('searchFields', $searchFields, "{$full_module_dir}/metadata/SearchFields.php");

				if(!$success_sf1){
					logThis("add_custom_modules_favorites_search failed for searchdefs.php for {$module_dir}");
				}
				if(!$success_sf2){
					logThis("add_custom_modules_favorites_search failed for SearchFields.php for {$module_dir}");
				}
				if($success_sf1 && $success_sf2){
					logThis("add_custom_modules_favorites_search successfully updated searchdefs and searchFields for {$module_dir}");
				}
			}
		}
	}
}


/**
 * upgradeModulesForTeamsets
 *
 * This method adds the team_set_id values to the module tables that have the new team_set_id column
 * added through the SugarCRM 5.5.x upgrade process.  It also adds the values into the team_sets and
 * team_sets_teams tables.
 *
 * @param filter Array of modules to process; empty by default
 */
function upgradeModulesForTeamsets($filter=array()) {
    require('include/modules.php');
	foreach($beanList as $moduleName=>$beanName) {
		    if(!empty($filter) && array_search($moduleName, $filter) === false) {
		       continue;
		    }
	        if($moduleName == 'TeamMemberships' || $moduleName == 'ForecastOpportunities'){
                continue;
            }
			$bean = loadBean($moduleName);
			if(empty($bean) ||
			   empty($bean->table_name)) {
			   continue;
			}

			$FieldArray = $GLOBALS['db']->helper->get_columns($bean->table_name);
			if(!isset($FieldArray['team_id'])) {
			   continue;
			}

			upgradeTeamColumn($bean, 'team_id');

	} //foreach

    //Upgrade users table
	$bean = loadBean('Users');
   	upgradeTeamColumn($bean, 'default_team');
	$result = $GLOBALS['db']->query("SELECT id FROM teams where deleted=0");
	while($row = $GLOBALS['db']->fetchByAssoc($result)) {
	      $teamset = new TeamSet();
	      $teamset->addTeams($row['id']);
	}
}


/**
 * upgradeTeamColumn
 * Helper function to create a team_set_id column and also set team_set_id column
 * to have the value of the $column_name parameter
 *
 * @param $bean SugarBean which we are adding team_set_id column to
 * @param $column_name The name of the column containing the default team_set_id value
 */
function upgradeTeamColumn($bean, $column_name) {
	//first let's check to ensure that the team_set_id field is defined, if not it could be the case that this is an older
	//module that does not use the SugarObjects
	if(empty($bean->field_defs['team_set_id']) && $bean->module_dir != 'Trackers'){

		//at this point we could assume that since we have a team_id defined and not a team_set_id that we need to
		//add that field and the corresponding relationships
		$object = $bean->object_name;
		$module = $bean->module_dir;
		$object_name = $object;
		$_object_name = strtolower($object_name);

		if(!empty($GLOBALS['dictionary'][$object]['table'])){
			$table_name = $GLOBALS['dictionary'][$object]['table'];
		}else{
			$table_name = strtolower($module);
		}

		$path = 'include/SugarObjects/implements/team_security/vardefs.php';
		require($path);
		//go through each entry in the vardefs from team_security and unset anything that is already set in the core module
		//this will ensure we have the proper ordering.
		$fieldDiff = array_diff_assoc($vardefs['fields'], $GLOBALS['dictionary'][$bean->object_name]['fields']);

		$file = 'custom/Extension/modules/' . $bean->module_dir. '/Ext/Vardefs/teams.php';
		$contents = "<?php\n";
		if(!empty($fieldDiff)){
			foreach($fieldDiff as $key => $val){
				$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['fields']['". $key . "']=" . var_export_helper($val) . ";";
			}
		}
		$relationshipDiff = array_diff_assoc($vardefs['relationships'], $GLOBALS['dictionary'][$bean->object_name]['relationships']);
		if(!empty($relationshipDiff)){
			foreach($relationshipDiff as $key => $val){
				$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['relationships']['". $key . "']=" . var_export_helper($val) . ";";
			}
		}
		$indexDiff = array_diff_assoc($vardefs['indices'], $GLOBALS['dictionary'][$bean->object_name]['indices']);
		if(!empty($indexDiff)){
			foreach($indexDiff as $key => $val){
					$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['indices']['". $key . "']=" . var_export_helper($val) . ";";
			}
		}
		if( $fh = @sugar_fopen( $file, 'wt' ) )
	    {
	        fputs( $fh, $contents);
	        fclose( $fh );
	    }


		//we have written out the teams.php into custom/Extension/modules/{$module_dir}/Ext/Vardefs/teams.php'
		//now let's merge back into vardefs.ext.php
        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();
		$mi->merge_files('Ext/Vardefs/', 'vardefs.ext.php');
        VardefManager::loadVardef($bean->getModuleName(), $bean->object_name, true);
		$bean->field_defs = $GLOBALS['dictionary'][$bean->object_name]['fields'];
	}

	if(isset($bean->field_defs['team_set_id'])) {
		//Create the team_set_id column
		$FieldArray = $GLOBALS['db']->helper->get_columns($bean->table_name);
		if(!isset($FieldArray['team_set_id'])) {
			$GLOBALS['db']->addColumn($bean->table_name, $bean->field_defs['team_set_id']);
		}
		$indexArray =  $GLOBALS['db']->helper->get_indices($bean->table_name);

        $indexName = getValidDBName('idx_'.strtolower($bean->table_name).'_tmst_id', true, 34);
        $indexDef = array(
					 array(
						'name' => $indexName,
						'type' => 'index',
						'fields' => array('team_set_id')
					 )
				   );
		if(!isset($indexArray[$indexName])) {
			$GLOBALS['db']->addIndexes($bean->table_name, $indexDef);
		}

		//Update the table's team_set_id column to have the same values as team_id
	    $GLOBALS['db']->query("UPDATE {$bean->table_name} SET team_set_id = {$column_name}");
	}
}

/**
 *  Update the folder subscription table which confirms to the team security mechanism but
 *  the class SugarFolders does not extend SugarBean and is therefore never picked up by the
 *  upgradeModulesForTeamsets function.
 */
function upgradeFolderSubscriptionsTeamSetId()
{
    logThis("In upgradeFolderSubscriptionsTeamSetId()");
    $query = "UPDATE folders SET team_set_id = team_id";
    $result = $GLOBALS['db']->query($query);
    logThis("Finished upgradeFolderSubscriptionsTeamSetId()");
}

/**
 * upgradeModulesForTeam
 *
 * This method update the associated_user_id, name, name_2 to the private team records on teams table
 * This function is used for upgrade process from 5.1.x and 5.2.x.
 *
 */
function upgradeModulesForTeam() {
    logThis("In upgradeModulesForTeam()");
    $result = $GLOBALS['db']->query("SELECT id, user_name, first_name, last_name FROM users where deleted=0");

    while($row = $GLOBALS['db']->fetchByAssoc($result)) {
    	$results2 = $GLOBALS['db']->query("SELECT id FROM teams WHERE name = '({$row['user_name']})'");
    	$assoc = '';
  		if(!$assoc = $GLOBALS['db']->fetchByAssoc($results2)) {
  			//if team does not exist, then lets create the team for this user
  			$team = new Team();
			$user = new User();
  			$user->retrieve($row['id']);
			$team->new_user_created($user);
			$team_id = $team->id;
  		}else{
  			$team_id =$assoc['id'];
  		}

  			//upgrade the team
  			$name = is_null($row['first_name'])?'':$row['first_name'];
			$name_2 = is_null($row['last_name'])?'':$row['last_name'];
			$associated_user_id = $row['id'];

			//Bug 32914
			//Ensure team->name is not empty by using team->name_2 if available
			if(empty($name) && !empty($name_2)) {
			   $name = $name_2;
			   $name_2 = '';
			}

			$query = "UPDATE teams SET name = '{$name}', name_2 = '{$name_2}', associated_user_id = '{$associated_user_id}' WHERE id = '{$team_id}'";
			$GLOBALS['db']->query($query);
    } //while

    //Update the team_set_id and default_team columns
    $ce_to_pro_or_ent = isset($_SESSION['upgrade_from_flavor']) && preg_match('/^SugarCE.*?(Pro|Ent|Corp|Ult)$/', $_SESSION['upgrade_from_flavor']);

    //Update team_set_id
	if($ce_to_pro_or_ent) {
	   $GLOBALS['db']->query("update users set team_set_id = (select teams.id from teams where teams.associated_user_id = users.id)");
	   $GLOBALS['db']->query("update users set default_team = (select teams.id from teams where teams.associated_user_id = users.id)");
	}

}


    function addNewSystemTabsFromUpgrade($from_dir){
        global $path;
        if(isset($_SESSION['upgrade_from_flavor'])){

            //check to see if there are any new files that need to be added to systems tab
            //retrieve old modules list
            logThis('check to see if new modules exist',$path);
            $oldModuleList = array();
            $newModuleList = array();
            include($from_dir.'/include/modules.php');
            $oldModuleList = $moduleList;
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
            //new modules list now has left over modules which are new to this install, so lets add them to the system tabs
            logThis('new modules to add are '.var_export($newModuleList,true),$path);

            if(!empty($newModuleList))
            {
	            //grab the existing system tabs
	            $tabs = $newTB->get_system_tabs();

	            //add the new tabs to the array
	            foreach($newModuleList as $nm ){
	              $tabs[$nm] = $nm;
	            }

	            $newTB->set_system_tabs($tabs);
            }
            logThis('module tabs updated',$path);
        }
    }


	function update_iframe_dashlets(){
		require_once(sugar_cached('dashlets/dashlets.php'));

		$db = DBManagerFactory::getInstance();
		$query = "SELECT id, contents, assigned_user_id FROM user_preferences WHERE deleted = 0 AND category = 'Home'";
		$result = $db->query($query, true, "Unable to update new default dashlets! ");
		while ($row = $db->fetchByAssoc($result)) {
			$content = unserialize(base64_decode($row['contents']));
			$assigned_user_id = $row['assigned_user_id'];
			$record_id = $row['id'];

			$current_user = new User();
			$current_user->retrieve($row['assigned_user_id']);

			if(!empty($content['dashlets']) && !empty($content['pages'])){
				$originalDashlets = $content['dashlets'];
				foreach($originalDashlets as $key => $ds){
				    if(!empty($ds['options']['url']) && stristr($ds['options']['url'],'http://www.sugarcrm.com/crm/product/gopro')){
						unset($originalDashlets[$key]);
					}
				}
				$current_user->setPreference('dashlets', $originalDashlets, 0, 'Home');
			}
		}
	}

	function check_FTS(){
		//check to see if FTS is installed
		global $sugar_config;
		if(is_callable(array($GLOBALS['db'], "supports")) && $GLOBALS['db']->supports('fulltext') && $GLOBALS['db']->full_text_indexing_installed()) {
            return true;
		}
		return false;
	}

    /**
     * convertImageToText
     * @deprecated
     * This method attempts to convert date type image to text on Microsoft SQL Server.
     * This method could NOT be used in any other type of datebases.
     */
	function convertImageToText($table_name,$column_name){
		$set_lang = "SET LANGUAGE us_english";
		$GLOBALS['db']->query($set_lang);
	    if($GLOBALS['db']->lastError()){
            logThis('An error occurred when performing this query-->'.$set_lang);
        }
       $q="SELECT data_type
        FROM INFORMATION_SCHEMA.Tables T JOIN INFORMATION_SCHEMA.Columns C
        ON T.TABLE_NAME = C.TABLE_NAME where T.TABLE_NAME = '$table_name' and C.COLUMN_NAME = '$column_name'";
       $res= $GLOBALS['db']->query($q);
       if($GLOBALS['db']->lastError()){
            logThis('An error occurred when performing this query-->'.$q);
        }
       $row= $GLOBALS['db']->fetchByAssoc($res);

     if(trim(strtolower($row['data_type'])) == 'image'){
        $addContent_temp = "alter table {$table_name} add {$column_name}_temp text null";
        $GLOBALS['db']->query($addContent_temp);
        if($GLOBALS['db']->lastError()){
            logThis('An error occurred when performing this query-->'.$addContent_temp);
        }
        $qN = "select count=datalength({$column_name}), id, {$column_name} from {$table_name}";
        $result = $GLOBALS['db']->query($qN);
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
           if($row['count'] >8000){
                $contentLength = $row['count'];
                $start = 1;
                $next=8000;
                $convertedContent = '';
                while($contentLength >0){
                    $stepsQuery = "select cont=convert(varchar(max), convert(varbinary(8000), substring({$column_name},{$start},{$next}))) from {$table_name} where id= '{$row['id']}'";
                    $steContQ = $GLOBALS['db']->query($stepsQuery);
                    if($GLOBALS['db']->lastError()){
                        logThis('An error occurred when performing this query-->'.$stepsQuery);
                    }
                    $stepCont = $GLOBALS['db']->fetchByAssoc($steContQ);
                    if(isset($stepCont['cont'])){
                        $convertedContent = $convertedContent.$stepCont['cont'];
                    }
                    $start = $start+$next;
                    $contentLength = $contentLength - $next;
                }
                $addContentDataText="update {$table_name} set {$column_name}_temp = '{$convertedContent}' where id= '{$row['id']}'";
                $GLOBALS['db']->query($addContentDataText);
                if($GLOBALS['db']->lastError()){
                    logThis('An error occurred when performing this query-->'.$addContentDataText);
                }
           }
           else{
                $addContentDataText="update {$table_name} set {$column_name}_temp =
                convert(varchar(max), convert(varbinary(8000), {$column_name})) where id= '{$row['id']}'";
                $GLOBALS['db']->query($addContentDataText);
                if($GLOBALS['db']->lastError()){
                    logThis('An error occurred when performing this query-->'.$addContentDataText);
                }
           }
        }
        //drop the contents now and change contents_temp to contents
        $dropColumn = "alter table {$table_name} drop column {$column_name}";
        $GLOBALS['db']->query($dropColumn);
        if($GLOBALS['db']->lastError()){
            logThis('An error occurred when performing this query-->'.$dropColumn);
        }
        $changeColumnName = "EXEC sp_rename '{$table_name}.[{$column_name}_temp]','{$column_name}','COLUMN'";
        $GLOBALS['db']->query($changeColumnName);
        if($GLOBALS['db']->lastError()){
            logThis('An error occurred when performing this query-->'.$changeColumnName);
        }
     }
    }

	 /**
     * clearHelpFiles
     * This method attempts to delete all English inline help files.
     * This method was introduced by 5.5.0RC2.
     */
    function clearHelpFiles(){
		$modulePath = clean_path(getcwd() . '/modules');
		$allHelpFiles = array();
		getFiles($allHelpFiles, $modulePath, "/en_us.help.*/");

		foreach( $allHelpFiles as $the_file ){
	        if( is_file( $the_file ) ){
	            unlink( $the_file );
	            logThis("Deleted file: $the_file");
	        }
	    }
	}


	/**
	 * fix_assigned_user_link_reports
	 *
	 * This method goes through the existing reports and fixes errors with the reports definition
	 * where the assigned_user_link may have been pointing to the wrong relationship name
	 * ("teams" instead of "team_memberships").  Also, fix errors where the team_memberships relationship
	 * may have been incorrectly pointing to the wrong name definition ("teams" instead of "team_memberships").
	 * This will fix existing reports created before 5.2.0d that used the Assigned User Name's
	 * teams as a filter.  The fix will allow the Teams folder for the Assigned To User field to
	 * be displayed.  Also, the fix to the team memberships relationship name will correctly fix the
	 * query to join against the team_memberships table rather than the teams table.
	 *
	 * @param $path String variable for the log path
	 *
	 */
	function fix_report_relationships($path='') {
		if(!empty($path)) {
		   logThis('Begin fix_report_relationships', $path);
		}

		$query = "SELECT id, content FROM saved_reports WHERE deleted = 0";
        $result = $GLOBALS['db']->query($query);

        while($row = $GLOBALS['db']->fetchByAssoc($result)) {
        	  $content = $row['content'];
        	  $content = str_replace('&quot;', '"', $content);
			  $content2 = str_replace(':assigned_user_link:teams', ':assigned_user_link:team_memberships', $content);
              $content3 = str_replace('{"name":"teams","relationship_name":"team_memberships"', '{"name":"team_memberships","relationship_name":"team_memberships"', $content2);

              //If the contents have been altered, update the saved_report definition
              if($content != $content3) {
              	 $update_query = 'UPDATE saved_reports SET content = \'' . $GLOBALS['db']->quote($content3) . '\' WHERE id = \'' . $row['id'] . '\'';
              	 if(!empty($path)) {
              	 	logThis('Running SQL:' . $update_query, $path);
              	 }
              	 $GLOBALS['db']->query($update_query);
              }
        }

        if(!empty($path)) {
           logThis('End fix_report_relationships', $path);
        }
	}

	/**
	 * upgradeDocumentTypeFields
	 *
	 */
	function upgradeDocumentTypeFields($path){
		//bug: 39757
		global $db;

		$documentsSql = "UPDATE documents SET doc_type = 'Sugar' WHERE doc_type IS NULL";
		$meetingsSql = "UPDATE meetings SET type = 'Sugar' WHERE type IS NULL";

		logThis('upgradeDocumentTypeFields Documents SQL:' . $documentsSql, $path);
		$db->query($documentsSql);
		logThis('upgradeDocumentTypeFields Meetings SQL:' . $meetingsSql, $path);
		$db->query($meetingsSql);
	}


/**
 * merge_config_si_settings
 * This method checks for the presence of a config_si.php file and, if found, merges the configuration
 * settings from the config_si.php file into config.php.  If a config_si_location parameter value is not
 * supplied it will attempt to discover the config_si.php file location from where the executing script
 * was invoked.
 *
 * @param write_to_upgrade_log boolean optional value to write to the upgradeWizard.log file
 * @param config_location String optional value to config.php file location
 * @param config_si_location String optional value to config_si.php file location
 * @param path String file of the location of log file to write to
 * @return boolean value indicating whether or not a merge was attempted with config_si.php file
 */
function merge_config_si_settings($write_to_upgrade_log=false, $config_location='', $config_si_location='', $path='')
{
	if(!empty($config_location) && !file_exists($config_location))
	{
		if($write_to_upgrade_log)
		{
	       logThis('config.php file specified in ' . $config_si_location . ' could not be found.  Skip merging', $path);
		}
	    return false;
	} else if(empty($config_location)) {
		global $argv;
		//We are assuming this is from the silentUpgrade scripts so argv[3] will point to SugarCRM install location
		if(isset($argv[3]) && is_dir($argv[3]))
		{
			$config_location = $argv[3] . DIRECTORY_SEPARATOR . 'config.php';
		}
	}

	//If config_location is still empty or if the file cannot be found, skip merging
	if(empty($config_location) || !file_exists($config_location))
	{
	   if($write_to_upgrade_log)
	   {
	   	  logThis('config.php file at (' . $config_location . ') could not be found.  Skip merging.', $path);
	   }
	   return false;
	} else {
	   if($write_to_upgrade_log)
	   {
	      logThis('Loading config.php file at (' . $config_location . ') for merging.', $path);
	   }

	   include($config_location);
	   if(empty($sugar_config))
	   {
	   	  if($write_to_upgrade_log)
		  {
	   	     logThis('config.php contents are empty.  Skip merging.', $path);
		  }
	   	  return false;
	   }
	}

	if(!empty($config_si_location) && !file_exists($config_si_location))
	{
		if($write_to_upgrade_log)
		{
	       logThis('config_si.php file specified in ' . $config_si_location . ' could not be found.  Skip merging', $path);
		}
	    return false;
	} else if(empty($config_si_location)) {
		if(isset($argv[0]) && is_file($argv[0]))
		{
			$php_file = $argv[0];
			$p_info = pathinfo($php_file);
			$php_dir = (isset($p_info['dirname']) && $p_info['dirname'] != '.') ?  $p_info['dirname'] . DIRECTORY_SEPARATOR : '';
			$config_si_location = $php_dir . 'config_si.php';
		}
	}

	//If config_si_location is still empty or if the file cannot be found, skip merging
	if(empty($config_si_location) || !file_exists($config_si_location))
	{
	   if($write_to_upgrade_log)
	   {
	      logThis('config_si.php file at (' . $config_si_location . ') could not be found.  Skip merging.', $path);
	   }
	   return false;
	} else {
	   if($write_to_upgrade_log)
	   {
	      logThis('Loading config_si.php file at (' . $config_si_location . ') for merging.', $path);
	   }

	   include($config_si_location);
	   if(empty($sugar_config_si))
	   {
	      if($write_to_upgrade_log)
		  {
	   	     logThis('config_si.php contents are empty.  Skip merging.', $path);
		  }
	   	  return false;
	   }
	}

	//Now perform the merge operation
	$modified = false;
	foreach($sugar_config_si as $key=>$value)
	{
        if (!preg_match('/^setup_/', $key)) {
		   if($write_to_upgrade_log)
		   {
		      logThis('Merge key (' . $key . ') with value (' . $value . ')', $path);
		   }
		   // Add config_si values to the global sugar_config array so that they
		   // are available on install, since merging SI happens after capturing
		   // sugar_config and only saves to file, not to memory
		   $GLOBALS['sugar_config'][$key] = $sugar_config[$key] = $value;
		   $modified = true;
		}
	}

	if($modified)
	{
		if($write_to_upgrade_log)
		{
	       logThis('Update config.php file with new values', $path);
		}

	    if(!write_array_to_file("sugar_config", $sugar_config, $config_location)) {
	       if($write_to_upgrade_log)
		   {
	    	  logThis('*** ERROR: could not write to config.php', $path);
		   }
		   return false;
		}
	} else {
	   if($write_to_upgrade_log)
	   {
	      logThis('config.php values are in sync with config_si.php values.  Skipped merging.', $path);
	   }
	   return false;
	}

	if($write_to_upgrade_log)
	{
	   logThis('End merge_config_si_settings', $path);
	}
	return true;
}


/**
 * upgrade_connectors
 *
 * This function handles support for upgrading connectors it is invoked from both end.php and silentUpgrade_step2.php
 *
 */
function upgrade_connectors() {
    require_once('include/connectors/utils/ConnectorUtils.php');
    if(!ConnectorUtils::updateMetaDataFiles()) {
       $GLOBALS['log']->fatal('Cannot update metadata files for connectors');
    }

    //Delete the custom connectors.php file if it exists so that it may be properly rebuilt
    if(file_exists('custom/modules/Connectors/metadata/connectors.php'))
    {
        unlink('custom/modules/Connectors/metadata/connectors.php');
    }
}

/**
 * Enable the InsideView connector for the four default modules.
 */
function upgradeEnableInsideViewConnector($path='')
{
    logThis('Begin upgradeEnableInsideViewConnector', $path);

    // Load up the existing mapping and hand it to the InsideView connector to have it setup the correct logic hooks
    $mapFile = 'modules/Connectors/connectors/sources/ext/rest/insideview/mapping.php';
    if ( file_exists('custom/'.$mapFile) ) {
        logThis('Found CUSTOM mappings', $path);
        require('custom/'.$mapFile);
    } else {
        logThis('Used default mapping', $path);
        require($mapFile);
    }

    require_once('include/connectors/sources/SourceFactory.php');
    $source = SourceFactory::getSource('ext_rest_insideview');

    // $mapping is brought in from the mapping.php file above
    $source->saveMappingHook($mapping);

    require_once('include/connectors/utils/ConnectorUtils.php');
    ConnectorUtils::installSource('ext_rest_insideview');

    // Now time to set the various modules to active, because this part ignores the default config
    require(CONNECTOR_DISPLAY_CONFIG_FILE);
    // $modules_sources come from that config file
    foreach ( $source->allowedModuleList as $module ) {
        $modules_sources[$module]['ext_rest_insideview'] = 'ext_rest_insideview';
    }
    if(!write_array_to_file('modules_sources', $modules_sources, CONNECTOR_DISPLAY_CONFIG_FILE)) {
        //Log error and return empty array
        logThis("Cannot write \$modules_sources to " . CONNECTOR_DISPLAY_CONFIG_FILE,$path);
    }

    logThis('End upgradeEnableInsideViewConnector', $path);

}

function repair_long_relationship_names($path='')
{
    logThis("Begin repair_long_relationship_names", $path);
    require_once 'modules/ModuleBuilder/parsers/relationships/DeployedRelationships.php' ;
    $GLOBALS['mi_remove_tables'] = false;
    $touched = array();
    foreach($GLOBALS['moduleList'] as $module)
    {
        $relationships = new DeployedRelationships ($module) ;
        foreach($relationships->getRelationshipList() as $rel_name)
        {
            if (strlen($rel_name) > 27 && empty($touched[$rel_name]))
            {
                logThis("Rebuilding relationship fields for $rel_name", $path);
                $touched[$rel_name] = true;
                $rel_obj = $relationships->get($rel_name);
                $rel_obj->setReadonly(false);
                $relationships->delete($rel_name);
                $relationships->save();
                $relationships->add($rel_obj);
                $relationships->save();
                $relationships->build () ;
            }
        }
    }
    logThis("End repair_long_relationship_names", $path);
}

function removeSilentUpgradeVarsCache(){
    global $silent_upgrade_vars_loaded;

    $cacheFileDir = "{$GLOBALS['sugar_config']['cache_dir']}/silentUpgrader";
    $cacheFile = "{$cacheFileDir}/silentUpgradeCache.php";

    if(file_exists($cacheFile)){
        unlink($cacheFile);
    }

    $silent_upgrade_vars_loaded = array(); // Set to empty to reset it

    return true;
}

function loadSilentUpgradeVars(){
    global $silent_upgrade_vars_loaded;

    if(empty($silent_upgrade_vars_loaded)){
        $cacheFile = sugar_cached("silentUpgrader/silentUpgradeCache.php");
        // We have no pre existing vars
        if(!file_exists($cacheFile)){
            // Set the vars array so it's loaded
            $silent_upgrade_vars_loaded = array('vars' => array());
        }
        else{
            require_once($cacheFile);
            $silent_upgrade_vars_loaded = $silent_upgrade_vars_cache;
        }
    }

    return true;
}

function writeSilentUpgradeVars(){
    global $silent_upgrade_vars_loaded;

    if(empty($silent_upgrade_vars_loaded)){
        return false; // You should have set some values before trying to write the silent upgrade vars
    }

    $cacheFileDir = sugar_cached("silentUpgrader");
    $cacheFile = "{$cacheFileDir}/silentUpgradeCache.php";

    require_once('include/dir_inc.php');
    if(!mkdir_recursive($cacheFileDir)){
        return false;
    }
    require_once('include/utils/file_utils.php');
    if(!write_array_to_file('silent_upgrade_vars_cache', $silent_upgrade_vars_loaded, $cacheFile, 'w')){
        global $path;
        logThis("WARNING: writeSilentUpgradeVars could not write to {$cacheFile}", $path);
        return false;
    }

    return true;
}

function setSilentUpgradeVar($var, $value){
    if(!loadSilentUpgradeVars()){
        return false;
    }

    global $silent_upgrade_vars_loaded;

    $silent_upgrade_vars_loaded['vars'][$var] = $value;

    return true;
}

function getSilentUpgradeVar($var){
    if(!loadSilentUpgradeVars()){
        return false;
    }

    global $silent_upgrade_vars_loaded;

    if(!isset($silent_upgrade_vars_loaded['vars'][$var])){
        return null;
    }else{
        return $silent_upgrade_vars_loaded['vars'][$var];
    }
}


/**
 * add_unified_search_to_custom_modules_vardefs
 *
 * This method calls the repair code to remove the unified_search_modules.php fiel
 *
 */
function add_unified_search_to_custom_modules_vardefs()
{
	if(file_exists($cachefile = sugar_cached('modules/unified_search_modules.php')))
	{
	   unlink($cachefile);
	}

}

/**
 * unlinkUpgradeFiles
 * This is a helper function to clean up
 *
 * @param $version String value of current system version (pre upgrade)
 * @param $path String value to the path of the log file; defaults to empty
 */
function unlinkUpgradeFiles($version, $path='')
{
	if(!isset($version))
	{
	   return;
	}

    //First check if we even have the scripts_for_patch/files_to_remove directory
    require_once('modules/UpgradeWizard/UpgradeRemoval.php');

    if(isset($_SESSION['unzip_dir']) && file_exists($_SESSION['unzip_dir'].'/scripts/files_to_remove'))
    {
       $files_to_remove = glob($_SESSION['unzip_dir'].'/scripts/files_to_remove/*.php');

       foreach($files_to_remove as $script)
       {
       		if(preg_match('/UpgradeRemoval(\d+)x\.php/', $script, $matches))
       		{
       	   	   $upgradeClass = 'UpgradeRemoval' . $matches[1] . 'x';
       	   	   require_once($_SESSION['unzip_dir'].'/scripts/files_to_remove/' . $upgradeClass . '.php');
               if (class_exists($upgradeClass) == false)
               {
                   continue;
               }

                //Check to make sure we should load and run this UpgradeRemoval instance
                $upgradeInstance = new $upgradeClass();
                if ($upgradeInstance instanceof UpgradeRemoval && version_compare($upgradeInstance->version, $version, '>='))
                {
                    logThis('Running UpgradeRemoval instance ' . $upgradeClass, $path);
                    logThis('Files will be backed up to custom/backup', $path);
                    $files = $upgradeInstance->getFilesToRemove($version);
                    foreach($files as $file)
                    {
                       logThis($file, $path);
                    }
                    $upgradeInstance->processFilesToRemove($files);
                }
       	    }
       }
    }

    //Check if we have a custom directory
    if(file_exists('custom/scripts/files_to_remove'))
    {
       //Now find
       $files_to_remove = glob('custom/scripts/files_to_remove/*.php');

       foreach($files_to_remove as $script)
       {
       	   if(preg_match('/\/files_to_remove\/(.*?)\.php$/', $script, $matches))
       	   {
       	   	   require_once($script);
       	   	   $upgradeClass  = $matches[1];

       	   	   if(!class_exists($upgradeClass))
       	   	   {
       	   	   	  continue;
       	   	   }

       	   	   $upgradeInstance = new $upgradeClass();
       	   	   if($upgradeInstance instanceof UpgradeRemoval)
       	   	   {
       	   	   	  	  logThis('Running Custom UpgradeRemoval instance ' . $upgradeClass);
	       	   	   	  $files = $upgradeInstance->getFilesToRemove($version);
	       	   	   	  foreach($files as $file)
	       	   	   	  {
	       	   	   	  	 logThis($file, $path);
	       	   	   	  }
	       	   	   	  $upgradeInstance->processFilesToRemove($files);
       	   	   }
       	   }
       }
    }

}

if (!function_exists("getValidDBName"))
{
    /*
     * Return a version of $proposed that can be used as a column name in any of our supported databases
     * Practically this means no longer than 25 characters as the smallest identifier length for our supported DBs is 30 chars for Oracle plus we add on at least four characters in some places (for indicies for example)
     * @param string $name Proposed name for the column
     * @param string $ensureUnique
     * @return string Valid column name trimmed to right length and with invalid characters removed
     */
     function getValidDBName ($name, $ensureUnique = false, $maxLen = 30)
    {
        // first strip any invalid characters - all but alphanumerics and -
        $name = preg_replace ( '/[^\w-]+/i', '', $name ) ;
        $len = strlen ( $name ) ;
        $result = $name;
        if ($ensureUnique)
        {
            $md5str = md5($name);
            $tail = substr ( $name, -11) ;
            $temp = substr($md5str , strlen($md5str)-4 );
            $result = substr ( $name, 0, 10) . $temp . $tail ;
        }else if ($len > ($maxLen - 5))
        {
            $result = substr ( $name, 0, 11) . substr ( $name, 11 - $maxLen + 5);
        }
        return strtolower ( $result ) ;
    }


}

/**
 * Get UW directories
 * Provides compatibility with both 6.3 and pre-6.3 setup
 */
function getUWDirs()
{
    if(!class_exists('UploadStream')) {
        // we're still running the old code
        global $sugar_config;
        return array($sugar_config['upload_dir'] . "/upgrades", $sugar_config['cache_dir'] . "upload/upgrades/temp");
    } else {
        if(!in_array("upload", stream_get_wrappers())) {
            UploadStream::register(); // just in case file was copied, but not run
        }
        return array("upload://upgrades", sugar_cached("upgrades/temp"));
    }
}

/**
 * Whether directory exists within list of directories to skip
 * @param string $dir dir to be checked
 * @param array $skipDirs list with skipped dirs
 * @return boolean
 */
function whetherNeedToSkipDir($dir, $skipDirs)
{
    foreach($skipDirs as $skipMe) {
		if(strpos( clean_path($dir), $skipMe ) !== false) {
			return true;
		}
	}
    return false;
}


/*
 * rebuildSprites
 * @param silentUpgrade boolean flag indicating whether or not we should treat running the SugarSpriteBuilder as an upgrade operation
 *
 */
function rebuildSprites($fromUpgrade=true)
{
    require_once('modules/Administration/SugarSpriteBuilder.php');
    $sb = new SugarSpriteBuilder();
    $sb->cssMinify = true;
    $sb->fromSilentUpgrade = $fromUpgrade;
    $sb->silentRun = $fromUpgrade;

    // add common image directories
    $sb->addDirectory('default', 'include/images');
    $sb->addDirectory('default', 'themes/default/images');
    $sb->addDirectory('default', 'themes/default/images/SugarLogic');

    // add all theme image directories
    if($dh = opendir('themes'))
    {
        while (($dir = readdir($dh)) !== false)
        {
            if ($dir != "." && $dir != ".." && $dir != 'default' && is_dir('themes/'.$dir)) {
                $sb->addDirectory($dir, "themes/{$dir}/images");
            }
        }
        closedir($dh);
    }

     // add all theme custom image directories
    $custom_themes_dir = "custom/themes";
    if (is_dir($custom_themes_dir)) {
         if($dh = opendir($custom_themes_dir))
         {
             while (($dir = readdir($dh)) !== false)
             {
                 //Since the custom theme directories don't require an images directory
                 // we check for it implicitly
                 if ($dir != "." && $dir != ".." && is_dir('custom/themes/'.$dir."/images")) {
                     $sb->addDirectory($dir, "custom/themes/{$dir}/images");
                 }
             }
             closedir($dh);
         }
    }

    // generate the sprite goodies
    // everything is saved into cache/sprites
    $sb->createSprites();
}


/**
 * repairSearchFields
 *
 * This method goes through the list of SearchFields files based and calls TemplateRange::repairCustomSearchFields
 * method on the files in an attempt to ensure the range search attributes are properly set in SearchFields.php.
 *
 * @param $globString String value used for glob search defaults to searching for all SearchFields.php files in modules directory
 * @param $path String value used to point to log file should logging be required.  Defaults to empty.
 *
 */
function repairSearchFields($globString='modules/*/metadata/SearchFields.php', $path='')
{
	if(!empty($path))
	{
		logThis('Begin repairSearchFields', $path);
	}

	require_once('include/dir_inc.php');
	require_once('modules/DynamicFields/templates/Fields/TemplateRange.php');
	require('include/modules.php');

	global $beanList;
	$searchFieldsFiles = glob($globString);

	foreach($searchFieldsFiles as $file)
	{
		if(preg_match('/modules\/(.*?)\/metadata\/SearchFields\.php/', $file, $matches) && isset($beanList[$matches[1]]))
		{
			$module = $matches[1];
			$beanName = $beanList[$module];
			VardefManager::loadVardef($module, $beanName);
			if(isset($GLOBALS['dictionary'][$beanName]['fields']))
			{
				if(!empty($path))
				{
					logThis('Calling TemplateRange::repairCustomSearchFields for module ' . $module, $path);
				}
				TemplateRange::repairCustomSearchFields($GLOBALS['dictionary'][$beanName]['fields'], $module);
			}
		}
	}

	if(!empty($path))
	{
		logThis('End repairSearchFields', $path);
	}
}

/**
 * repairUpgradeHistoryTable
 *
 * This is a helper function used in the upgrade process to fix upgrade_history entries so that the filename column points
 * to the new upload directory location introduced in 6.4 versions
 */
function repairUpgradeHistoryTable()
{
    require_once('modules/Configurator/Configurator.php');
    new Configurator();
    global $sugar_config;

    //Now upgrade the upgrade_history table entries
    $results = $GLOBALS['db']->query('SELECT id, filename FROM upgrade_history');
    $upload_dir = $sugar_config['cache_dir'].'upload/';

    //Create regular expression string to
    $match = '/^' . str_replace('/', '\/', $upload_dir) . '(.*?)$/';

    while(($row = $GLOBALS['db']->fetchByAssoc($results)))
    {
        $file = str_replace('//', '/', $row['filename']); //Strip out double-paths that may exist

        if(!empty($file) && preg_match($match, $file, $matches))
        {
            //Update new file location to use the new $sugar_config['upload_dir'] value
            $new_file_location = $sugar_config['upload_dir'] . $matches[1];
            $GLOBALS['db']->query("UPDATE upgrade_history SET filename = '{$new_file_location}' WHERE id = '{$row['id']}'");
        }
    }

}


/**
 * Patch for bug57431
 * Compares current moduleList to base moduleList to detect if some modules have been renamed
 * Run changeModuleModStrings to create new labels based on customizations.
 */
function updateRenamedModulesLabels()
{
    require_once('modules/Studio/wizards/RenameModules.php');
    require_once('include/utils.php');

    $klass = new RenameModules();
    $languages = get_languages();

    foreach ($languages as $langKey => $langName) {
        //get list strings for this language
        $strings = return_app_list_strings_language($langKey);

        //get base list strings for this language
        if (file_exists("include/language/$langKey.lang.php")) {
            include FileLoader::validateFilePath("include/language/$langKey.lang.php");

            //Keep only renamed modules
            $renamedModules = array_diff($strings['moduleList'], $app_list_strings['moduleList']);

            foreach ($renamedModules as $moduleId => $moduleName) {
                if(isset($app_list_strings['moduleListSingular'][$moduleId])) {
                    $klass->selectedLanguage = $langKey;

                    $replacementLabels = array(
                        'singular' => $strings['moduleListSingular'][$moduleId],
                        'plural' => $strings['moduleList'][$moduleId],
                        'prev_singular' => $app_list_strings['moduleListSingular'][$moduleId],
                        'prev_plural' => $app_list_strings['moduleList'][$moduleId],
                        'key_plural' => $moduleId,
                        'key_singular' => $klass->getModuleSingularKey($moduleId)
                    );
                    $klass->changeModuleModStrings($moduleId, $replacementLabels);
                }
            }
        }
    }
}


/**
 * addPdfManagerTemplate
 *
 * This method adds default PDF Template in PDF Manager
 */
function addPdfManagerTemplate() {
    logThis('Begin addPdfManagerTemplate');

    include 'install/seed_data/PdfManager_SeedData.php';

    logThis('End addPdfManagerTemplate');
}


/**
 * This is left here for legacy, just calling the new methods
 * @deprecated
 * @param int $perJob The Number of records to put in a job
 * @return array|string An array of jobs ids that were created, unless
 *      there is one, the it's just that single job id
 */
function updateOpportunitiesForForecasting($perJob = 100)
{
    SugarAutoLoader::load('include/SugarQueue/jobs/SugarJobUpdateOpportunities.php');
    return SugarJobUpdateOpportunities::updateOpportunitiesForForecasting($perJob);
}

/**
 * Add the platform to the portal config settings
 */
function updatePortalConfigToContainPlatform()
{
    $db = DBManagerFactory::getInstance();
    $sql = "UPDATE config SET platform = 'support' where category = 'portal'";
    $db->query($sql);
}
