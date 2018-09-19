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

require_once 'modules/Administration/UpgradeWizardCommon.php';
require_once 'include/SugarSmarty/plugins/function.sugar_csrf_form_token.php';

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

function UWrebuild() {
	global $log;
	global $db;
	$log->info('Deleting Relationship Cache. Relationships will automatically refresh.');

	echo "
	<div id='rrresult'></div>
	<script>
		var xmlhttp=false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		// JScript gives us Conditional compilation, we can cope with old IE versions.
		// and security blocked creation of the objects.
		 try {
		  xmlhttp = new ActiveXObject(\"Msxml2.XMLHTTP\");
		 } catch (e) {
		  try {
		   xmlhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");
		  } catch (E) {
		   xmlhttp = false;
		  }
		 }
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			try {
				xmlhttp = new XMLHttpRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		if (!xmlhttp && window.createRequest) {
			try {
				xmlhttp = window.createRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		xmlhttp.onreadystatechange = function() {
		            if(xmlhttp.readyState == 4) {
		              document.getElementById('rrresult').innerHTML = xmlhttp.responseText;
		            }
		          }
		xmlhttp.open('GET', 'index.php?module=Administration&action=RebuildRelationship&to_pdf=true', true);
		xmlhttp.send(null);
		</script>";

	$log->info('Rebuilding everything.');
    SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
    $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
    $mi = new $moduleInstallerClass();
	$mi->rebuild_all();
}

/**
 * Returns manifest patch from user request
 *
 * @param array $request
 * @return array
 */
function UW_get_patch_from_request(array $request)
{
    if (isset($request['patch'])) {
        return $request['patch'];
    }

    return array();
}

/**
 * Returns manifest patch from upgrade history of the given install file
 *
 * @param string $install_file
 * @return array
 */
function UW_get_patch_for_file($install_file)
{
    $history = new UpgradeHistory();
    $md5 = md5_file($install_file);
    $matches = $history->findByMd5($md5);
    $history = array_shift($matches);

    if ($history && $history->patch) {
        return unserialize(base64_decode($history->patch));
    }

    return array();
}

global $log, $db;

$request = InputValidation::getService();
$mode = $request->getValidInputRequest('mode');
$version = $request->getValidInputRequest('version');
$copyCount = $request->getValidInputRequest('copy_count');
$unzipDir = $request->getValidInputRequest('unzip_dir');

// $_REQUEST['install_file'] is a hash as per fileToHash/hashToFile
$installFile = $request->getValidInputRequest('install_file');

// process commands
if ($mode === null) {
    die($mod_strings['ERR_UW_NO_MODE']);
}

if ($version === null) {
    die($mod_strings['ERR_UW_NO_MODE']);
}

if ($copyCount === null) {
    die($mod_strings['ERR_UW_NO_FILES']);
}

if ($unzipDir === null || $unzipDir == "." || $unzipDir == "..") {
    die($mod_strings['ERR_UW_NO_TEMP_DIR']);
}
$unzip_dir = $base_tmp_upgrade_dir. "/". basename($unzipDir);

if ($installFile === null) {
    die($mod_strings['ERR_UW_NO_INSTALL_FILE']);
}

$install_file   = hashToFile($installFile);
$install_type   = UpgradeWizardCommon::getInstallType( $install_file );

//from here on out, the install_file is used as the file path to copy or rename the physical file, so let's remove the stream wrapper if it's set
//and replace it with the proper upload location
if(strpos($install_file,'upload://') === 0){
    //get the upload location if it's set, or default to 'upload'
    $upload_dir = empty($GLOBALS['sugar_config']['upload_dir']) ? 'upload' : rtrim($GLOBALS['sugar_config']['upload_dir'], '/\\');

    //replace the wrapper in the file name with the directory
    $install_file = str_replace('upload:/',$upload_dir,$install_file);
    $_REQUEST['install_file'] = $install_file;
}

$id_name = $request->getValidInputRequest('id_name', null, '');
$s_manifest = $request->getValidInputRequest('s_manifest', null, '');
$s_patch = $request->getValidInputRequest('patch', 'Assert\ArrayRecursive');
if ($s_patch) {
    $s_patch = base64_encode(serialize($s_patch));
}
$previous_version = $request->getValidInputRequest('previous_version', null, '');
$previous_id = $request->getValidInputRequest('previous_id', null, '');

if ($install_type != "module") {
	$zip_from_dir = $request->getValidInputRequest('zip_from_dir', null, '.');
	$zip_to_dir = $request->getValidInputRequest('zip_to_dir', null, '.');
}
$remove_tables = $request->getValidInputRequest('remove_tables', null, 'true');

$overwrite = $request->getValidInputRequest('radio_overwrite');
$overwrite_files = $overwrite ? ($overwrite != 'do_not_overwrite') : true;

//rrs
$author = '';
$is_uninstallable = true;
$name = '';
$description = '';

if($install_type == 'module'){
	$is_uninstallable = $request->getValidInputRequest('is_uninstallable', null, true);
	$name = $request->getValidInputRequest('name', null, '');
	$description = $request->getValidInputRequest('description', null, '');
}


$file_action    = "";
$uh_status      = "";

$rest_dir = remove_file_extension($install_file)."-restore";

$files_to_handle  = array();
register_shutdown_function("rmdir_recursive", $unzip_dir);

if (((defined('MODULE_INSTALLER_PACKAGE_SCAN') && MODULE_INSTALLER_PACKAGE_SCAN)
    || !empty($GLOBALS['sugar_config']['moduleInstaller']['packageScan'])) && $install_type != 'patch') {
	require_once('ModuleInstall/ModuleScanner.php');
	$ms = new ModuleScanner();
	$ms->scanPackage($unzip_dir);
	if($ms->hasIssues()){
		$ms->displayIssues();
		sugar_cleanup(true);
	}
}

//
// execute the PRE scripts
//
if($install_type == 'patch' || $install_type == 'module')
{
	switch($mode)
 	{
 		case 'Install':
 			$file = "$unzip_dir/" . constant('SUGARCRM_PRE_INSTALL_FILE');
			if(is_file($file))
			{
				print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
				include FileLoader::validateFilePath($file);
				pre_install();
   		}
 			break;
 		case 'Uninstall':
 			$file = "$unzip_dir/" . constant('SUGARCRM_PRE_UNINSTALL_FILE');
			if(is_file($file))
			{
				print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
				include FileLoader::validateFilePath($file);
				pre_uninstall();
   		}
 			break;
 		default:
 			break;
 		}
}

//
// perform the action
//

for( $iii = 0; $iii < $copyCount; $iii++ ){
    if( isset($_REQUEST["copy_" . $iii]) && ($_REQUEST["copy_" . $iii] != "") ){
        $file_to_copy = InputValidation::getService()->getValidInputRequest('copy_' . $iii);
        $src_file   = clean_path( "$unzip_dir/$zip_from_dir/$file_to_copy" );

        $sugar_home_dir = getCwd();
        $dest_file  = clean_path( "$sugar_home_dir/$zip_to_dir/$file_to_copy" );
        if($zip_to_dir != '.')
        	$rest_file  = clean_path("$rest_dir/$zip_to_dir/$file_to_copy");
        else
        	$rest_file  = clean_path("$rest_dir/$file_to_copy");

        switch( $mode ){
            case "Install":
                mkdir_recursive( dirname( $dest_file ) );

                if($install_type=="patch" && is_file($dest_file))
                {
	                if(!is_dir(dirname( $rest_file )))
                		mkdir_recursive( dirname( $rest_file ) );

	                copy( $dest_file, $rest_file);
	                sugar_touch( $rest_file, filemtime($dest_file) );
                }

                if( !copy( $src_file, $dest_file ) ){
                    die( $mod_strings['ERR_UW_COPY_FAILED'].$src_file.$mod_strings['LBL_TO'].$dest_file);
                }
                $uh_status = "installed";
                break;
            case "Uninstall":
                if($install_type=="patch" && is_file($rest_file))
                {
	                copy( $rest_file, $dest_file);
	                sugar_touch( $dest_file, filemtime($rest_file) );
                }
                elseif(file_exists($dest_file) && !unlink($dest_file))
                {
                    die($mod_strings['ERR_UW_REMOVE_FAILED'].$dest_file);
                }
                $uh_status = "uninstalled";
                break;
            default:
                die("{$mod_strings['LBL_UW_OP_MODE']} '$mode' {$mod_strings['ERR_UW_NOT_RECOGNIZED']}." );
        }
        $files_to_handle[] = clean_path( "$zip_to_dir/$file_to_copy" );
    }
}

switch( $install_type ){
    case "langpack":
		$newLangName = $request->getValidInputRequest('new_lang_name', null, "");
		$newLangDesc = $request->getValidInputRequest('new_lang_desc', null, "");
        if ($newLangName == "") {
            die($mod_strings['ERR_UW_NO_LANG']);
        }
        if ($newLangDesc == "") {
            die($mod_strings['ERR_UW_NO_LANG_DESC']);
        }

        if( $mode == "Install" || $mode=="Enable" ){
            $sugar_config['languages'] = $sugar_config['languages'] + array( $newLangName => $newLangDesc );
        }
        else if( $mode == "Uninstall" || $mode=="Disable" ){
            $new_langs = array();
            $old_langs = $sugar_config['languages'];
            foreach( $old_langs as $key => $value ){
                if( $key != $newLangName ){
                    $new_langs += array( $key => $value );
                }
            }
			$sugar_config['languages'] = $new_langs;

	        $default_sugar_instance_lang = 'en_us';
	        if($current_language == $newLangName){
	        	$_SESSION['authenticated_user_language'] =$default_sugar_instance_lang;
	        	$lang_changed_string = $mod_strings['LBL_CURRENT_LANGUAGE_CHANGE'].$sugar_config['languages'][$default_sugar_instance_lang].'<br/>';
	        }

	        if($sugar_config['default_language'] == $newLangName){
	        	$cfg = new Configurator();
             	$cfg->config['languages'] = $new_langs;
				$cfg->config['default_language'] = $default_sugar_instance_lang;
				$cfg->handleOverride();
	        	$lang_changed_string .= $mod_strings['LBL_DEFAULT_LANGUAGE_CHANGE'].$sugar_config['languages'][$default_sugar_instance_lang].'<br/>';
	        }
        }
        ksort( $sugar_config );
        if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) ){
            die($mod_strings['ERR_UW_CONFIG_FAILED']);
        }
        break;
    case "module":
        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();
        switch( $mode ){
            case "Install":
                $patch = UW_get_patch_from_request($_REQUEST);
                $mi->setPatch($patch);
            //here we can determine if this is an upgrade or a new version
            	if(!empty($previous_version)){
            		$mi->install( "$unzip_dir", true, $previous_version);
            	}else{
                	$mi->install( "$unzip_dir" );
            	}

				$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
				if(is_file($file))
				{
					print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
					include FileLoader::validateFilePath($file);
					post_install();
				}
            	break;
            case "Uninstall":
                if($remove_tables == 'false')
                	$GLOBALS['mi_remove_tables'] = false;
                else
                	$GLOBALS['mi_remove_tables'] = true;
                $patch = UW_get_patch_for_file($install_file);
                $mi->setPatch($patch);
                $mi->uninstall( "$unzip_dir" );
                break;
             case "Disable":
                if(!$overwrite_files)
                	$GLOBALS['mi_overwrite_files'] = false;
                else
                	$GLOBALS['mi_overwrite_files'] = true;
                $patch = UW_get_patch_for_file($install_file);
                $mi->setPatch($patch);
                $mi->disable( "$unzip_dir" );
                break;
             case "Enable":
                if(!$overwrite_files)
                	$GLOBALS['mi_overwrite_files'] = false;
                else
                	$GLOBALS['mi_overwrite_files'] = true;
                $patch = UW_get_patch_for_file($install_file);
                $mi->setPatch($patch);
                $mi->enable( "$unzip_dir" );
                break;
            default:
                break;
        }
        $current_user->incrementETag("mainMenuETag");
        break;
    case "full":
        // purposely flow into "case: patch"
    case "patch":
 		switch($mode)
 		{
 			case 'Install':
 				$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
				if(is_file($file))
				{
					print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
					include FileLoader::validateFilePath($file);
					post_install();
				}

				UWrebuild();
 				break;
 			case 'Uninstall':
 				$file = "$unzip_dir/" . constant('SUGARCRM_POST_UNINSTALL_FILE');
 				if(is_file($file)) {
					print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
					include FileLoader::validateFilePath($file);
					post_uninstall();
				}

				if(is_dir($rest_dir))
				{
					rmdir_recursive($rest_dir);
				}

				UWrebuild();
 				break;
 			default:
 				break;
 		}

		require( "sugar_version.php" );
		$sugar_config['sugar_version'] = $sugar_version;
		ksort( $sugar_config );

		if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) )
		{
			die($mod_strings['ERR_UW_UPDATE_CONFIG']);
		}
        break;
    default:
        break;
}

switch( $mode ){
    case "Install":
        $file_action = "copied";
        // if error was encountered, script should have died before now
        $new_upgrade = new UpgradeHistory();
        //determine if this module has already been installed given the unique_key to
        //identify the module
       // $new_upgrade->checkForExisting($unique_key);
       	if(!empty($previous_id)){
       		$new_upgrade->id = $previous_id;
       		$uh = new UpgradeHistory();
       		$uh->retrieve($previous_id);
       		if(is_file($uh->filename)) {
       		unlink($uh->filename);
       		}
       	}
        $new_upgrade->filename      = $install_file;
        $new_upgrade->md5sum        = md5_file( $install_file );
        $new_upgrade->type          = $install_type;
        $new_upgrade->version       = $version;
        $new_upgrade->status        = "installed";
        $new_upgrade->name          = $name;
        $new_upgrade->description   = $description;
        $new_upgrade->id_name		= $id_name;
        $new_upgrade->manifest		= $s_manifest;
        $new_upgrade->patch         = $s_patch;
        $new_upgrade->save();

        //Check if we need to show a page for the user to finalize their install with.
        if (is_file("$unzip_dir/manifest.php"))
        {
        	include FileLoader::validateFilePath("$unzip_dir/manifest.php");
        	if (!empty($manifest['post_install_url']))
        	{
        		$url_conf = $manifest['post_install_url'];
        		if (is_string($url_conf))
        			$url_conf = array('url' => $url_conf);
        		if (isset($url_conf['type']) && $url_conf['type'] == 'popup')
        		{
        			echo '<script type="text/javascript">window.open("' . $url_conf['url']
        			   . '","' . (empty($url_conf['name']) ? 'sugar_popup' : $url_conf['name']) . '","'
        			   . 'height=' . (empty($url_conf['height']) ? '500' : $url_conf['height']) . ','
        			   . 'width=' . (empty($url_conf['width']) ? '800' : $url_conf['width']) . '");</script>';
        		} else
        		{
        			echo '<iframe src="' . $url_conf['url'] . '" '
        			   . 'width="' . (empty($url_conf['width']) ? '100%' : $url_conf['width']) . '" '
        			   . 'height="' . (empty($url_conf['height']) ? '500px' : $url_conf['height']) . '"></iframe>';
        		}
        	}
        }
        $shouldClearCache = false;
    break;
    case "Uninstall":
        $file_action = "removed";
        $uh = new UpgradeHistory();
        $the_md5 = md5_file( $install_file );
        $md5_matches = $uh->findByMd5( $the_md5 );
        if( sizeof( $md5_matches ) == 0 ){
            die( "{$mod_strings['ERR_UW_NO_UPDATE_RECORD']} $install_file." );
        }
        foreach( $md5_matches as $md5_match ){
            $md5_match->delete();
        }
        $shouldClearCache = true;
        break;
    case "Disable":
        $file_action = "disabled";
        $uh = new UpgradeHistory();
        $the_md5 = md5_file( $install_file );
        $md5_matches = $uh->findByMd5( $the_md5 );
        if( sizeof( $md5_matches ) == 0 ){
            die( "{$mod_strings['ERR_UW_NO_UPDATE_RECORD']} $install_file." );
        }
        foreach( $md5_matches as $md5_match ){
             $md5_match->enabled = 0;
            $md5_match->save();
        }
        $shouldClearCache = true;
        break;
    case "Enable":
        $file_action = "enabled";
        $uh = new UpgradeHistory();
        $the_md5 = md5_file( $install_file );
        $md5_matches = $uh->findByMd5( $the_md5 );
        if( sizeof( $md5_matches ) == 0 ){
            die( "{$mod_strings['ERR_UW_NO_UPDATE_RECORD']} $install_file." );
        }
        foreach( $md5_matches as $md5_match ){
            $md5_match->enabled = 1;
            $md5_match->save();
        }
        $shouldClearCache = true;
        break;
}

// present list to user
if ($shouldClearCache) {
    MetaDataManager::clearAPICache();
}

// present list to user
?>
<form action="<?php print( $form_action ); ?>" method="post">
<input type="hidden" name="reloadMetadata" value="true" />

<?php
echo smarty_function_sugar_csrf_form_token(array(), $smarty);
echo "<div>";
print( UpgradeWizardCommon::getUITextForType($install_type) . " ". UpgradeWizardCommon::getUITextForMode($mode) . " ". $mod_strings['LBL_UW_SUCCESSFULLY']);
echo "<br>";
echo "<br>";
print( "<input type=submit value=\"{$mod_strings['LBL_UW_BTN_BACK_TO_MOD_LOADER']}\" /><br>" );
echo "</div>";
echo "<br>";
if(isset($lang_changed_string))
	print($lang_changed_string);
if ($install_type != "module" && $install_type != "langpack"){
    if( sizeof( $files_to_handle ) > 0 ){
        echo '<div style="text-align: left; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'this.style.display="none"; toggleDisplay("more");\' id="all_text">' . SugarThemeRegistry::current()->getImage('advanced_search', '', null, null, ".gif", $mod_strings['LBL_ADVANCED_SEARCH']) . ' Show Details</div><div id=\'more\' style=\'display: none\'>
            <div style="text-align: left; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'document.getElementById("all_text").style.display=""; toggleDisplay("more");\'>' . SugarThemeRegistry::current()->getImage('basic_search', '', null, null, ".gif", $mod_strings['LBL_BASIC_SEARCH']) . ' Hide Details</div><br>';
        print( "{$mod_strings['LBL_UW_FOLLOWING_FILES']} $file_action:<br>\n" );
        print( "<ul id=\"subMenu\">\n" );
        foreach( $files_to_handle as $file_to_copy ){
            print( "<li>$file_to_copy<br>\n" );
        }
        print( "</ul>\n" );
        echo '</div>';
    }
    else if( $mode != 'Disable' && $mode !='Enable' ){
        print( "{$mod_strings['LBL_UW_NO_FILES_SELECTED']} $file_action.<br>\n" );
    }

        print($mod_strings['LBL_UW_UPGRADE_SUCCESSFUL']);
        print( "<input class='button' type=submit value=\"{$mod_strings['LBL_UW_BTN_BACK_TO_UW']}\" />\n" );
}
?>
</form>

<?php
    $GLOBALS['log']->info( "Upgrade Wizard patches" );
?>
