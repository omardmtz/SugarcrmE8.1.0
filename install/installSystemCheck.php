<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
// $Id: checkSystem.php 19538 2007-01-23 01:16:25 +0000 (Tue, 23 Jan 2007) chris $
$_SESSION['setup_license_accept'] = true;

function runCheck($install_script = false, $mod_strings){
installLog("Begin System Check Process *************");

if( !isset( $install_script ) || !$install_script ){
    installLog("Error:: ".$mod_strings['ERR_NO_DIRECT_SCRIPT']);
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if(!defined('SUGARCRM_MIN_MEM')) {
    define('SUGARCRM_MIN_MEM', 40);
}



// for keeping track of whether to enable/disable the 'Next' button
$error_found = false;
$error_txt = '';

    try {
        random_bytes(4);
    } catch (Exception $e) {
        $message = $e->getMessage();
        installLog($mod_strings['ERR_CHECKSYS_CSPRNG'].': '.$message);
        $error_found = true;
        $error_txt .= '
            <tr>
                <td><b>'.$mod_strings['LBL_CHECKSYS_CSPRNG'].'</b></td>
                <td ><span class="error">'.$message.'</span></td>
            </tr>';
    }

// check IIS and FastCGI
$server_software = $_SERVER["SERVER_SOFTWARE"];
if ((strpos($_SERVER["SERVER_SOFTWARE"],'Microsoft-IIS') !== false)
    && php_sapi_name() == 'cgi-fcgi'
    && ini_get('fastcgi.logging') != '0')
{
    installLog($mod_strings['ERR_CHECKSYS_FASTCGI_LOGGING']);
    $iisVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_FASTCGI_LOGGING']}</span></b>";
    $error_found = true;
    $error_txt .= '
          <tr>
            <td><b>'.$mod_strings['LBL_CHECKSYS_FASTCGI'].'</b></td>
            <td ><span class="error">'.$iisVersion.'</span></td>
          </tr>';
}

if(strpos($server_software,'Microsoft-IIS') !== false)
{
	$iis_version = '';
	if(preg_match_all("/^.*\/(\d+\.?\d*)$/",  $server_software, $out))
	$iis_version = $out[1][0];

	$check_iis_version_result = check_iis_version($iis_version);
	if($check_iis_version_result == -1) {
		installLog($mod_strings['ERR_CHECKSYS_IIS_INVALID_VER'].' '.$iis_version);
        $iisVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_IIS_INVALID_VER']} {$iis_version}</span></b>";
		$error_found = true;
        $error_txt .= '
          <tr>
            <td><b>'.$mod_strings['LBL_CHECKSYS_IISVER'].'</b></td>
            <td ><span class="error">'.$iisVersion.'</span></td>
          </tr>';
	} else if(php_sapi_name() != 'cgi-fcgi')
	{
		installLog($mod_strings['ERR_CHECKSYS_FASTCGI'].' '.$iis_version);
		$iisVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_FASTCGI']}</span></b>";
		$error_found = true;
        $error_txt .= '
          <tr>
            <td><b>'.$mod_strings['LBL_CHECKSYS_FASTCGI'].'</b></td>
            <td ><span class="error">'.$iisVersion.'</span></td>
          </tr>';
	} else if(ini_get('fastcgi.logging') != '0')
    {
        installLog($mod_strings['ERR_CHECKSYS_FASTCGI_LOGGING'].' '.$iis_version);
		$iisVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_FASTCGI_LOGGING']}</span></b>";
		$error_found = true;
        $error_txt .= '
          <tr>
            <td><b>'.$mod_strings['LBL_CHECKSYS_FASTCGI'].'</b></td>
            <td ><span class="error">'.$iisVersion.'</span></td>
          </tr>';
    }
}

// PHP VERSION
    $check_php_version_result = check_php_version();

if($check_php_version_result == -1) {
        $php_version = constant('PHP_VERSION');
        installLog($mod_strings['ERR_CHECKSYS_PHP_INVALID_VER'].'  '.$php_version);
        $phpVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_PHP_INVALID_VER']} {$php_version} )</span></b>";
        $error_found = true;
        $error_txt .= '
          <tr>
            <td><b>'.$mod_strings['LBL_CHECKSYS_PHPVER'].'</b></td>
            <td ><span class="error">'.$phpVersion.'</span></td>
          </tr>';

}

//Php Backward compatibility checks
if(ini_get("zend.ze1_compatibility_mode")) {
    installLog($mod_strings['LBL_BACKWARD_COMPATIBILITY_ON'].'  '.'Php Backward Compatibility');
    $phpCompatibility = "<b><span class=stop>{$mod_strings['LBL_BACKWARD_COMPATIBILITY_ON']}</span></b>";
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><b>Php Backward Compatibility</b></td>
        <td ><span class="error">'.$phpCompatibility.'</span></td>
      </tr>';

}

// database and connect

if (!empty($_REQUEST['setup_db_type']))
    $_SESSION['setup_db_type'] = $_REQUEST['setup_db_type'];

$drivers = DBManagerFactory::getDbDrivers();

if( empty($drivers) ){
    $db_name = $mod_strings['LBL_DB_UNAVAILABLE'];
    installLog("ERROR:: {$mod_strings['LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE']}");
    $dbStatus = "<b><span class=stop>{$mod_strings['LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE']}</span></b>";
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$db_name.'</strong></td>
        <td class="error">'.$dbStatus.'</td>
      </tr>';
}

// XML Parsing
if(!function_exists('xml_parser_create')) {
    $xmlStatus = "<b><span class=stop>{$mod_strings['LBL_CHECKSYS_XML_NOT_AVAILABLE']}</span></b>";
    installLog("ERROR:: {$mod_strings['LBL_CHECKSYS_XML_NOT_AVAILABLE']}");
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_XML'].'</strong></td>
        <td class="error">'.$xmlStatus.'</td>
      </tr>';
}else{
    installLog("XML Parsing Support Found");
}


// mbstrings
if(!function_exists('mb_strlen')) {
    $mbstringStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_MBSTRING']}</font></b>";
    installLog("ERROR:: {$mod_strings['ERR_CHECKSYS_MBSTRING']}");
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_MBSTRING'].'</strong></td>
        <td class="error">'.$mbstringStatus.'</td>
      </tr>';
}else{
    installLog("MBString Support Found");
}

// mcrypt extension check
if (!extension_loaded('mcrypt')) {
    $error_found = true;
    installLog(sprintf('ERROR:: %s', $mod_strings['ERR_CHECKSYS_MCRYPT']));
    $error_txt .= sprintf(
        '<tr>
        <td>
            <strong>%s</strong>
        </td>
        <td class="error">
            <b><span class="stop">%s</font></b>
        </td>
    </tr>',
        $mod_strings['LBL_CHECKSYS_MCRYPT'],
        $mod_strings['ERR_CHECKSYS_MCRYPT']
    );
} else {
    installLog("MCrypt is loaded");
}

// zip
if(!class_exists('ZipArchive')) {
    $zipStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_ZIP']}</font></b>";
    installLog("ERROR:: {$mod_strings['ERR_CHECKSYS_ZIP']}");
}else{
    installLog("ZIP Support Found");
}

// config.php
if(file_exists('./config.php') && (!(make_writable('./config.php')) ||  !(is_writable('./config.php')))) {
    installLog("ERROR:: {$mod_strings['ERR_CHECKSYS_CONFIG_NOT_WRITABLE']}");
    $configStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_CONFIG_NOT_WRITABLE']}</span></b>";
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_CONFIG'].'</strong></td>
        <td class="error">'.$configStatus.'</td>
      </tr>';
}

// config_override.php
if(file_exists('./config_override.php') && (!(make_writable('./config_override.php')) ||  !(is_writable('./config_override.php')))) {
    installLog("ERROR:: {$mod_strings['ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE']}");
    $configStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE']}</span></b>";
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_OVERRIDE_CONFIG'].'</strong></td>
        <td class="error">'.$configStatus.'</td>
      </tr>';
}

// custom dir
if(!make_writable('./custom')) {
    $customStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_CUSTOM_NOT_WRITABLE']}</font></b>";
    installLog("ERROR:: {$mod_strings['ERR_CHECKSYS_CUSTOM_NOT_WRITABLE']}");
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_CUSTOM'].'</strong></td>
        <td class="error">'.$customStatus.'</td>
      </tr>';
}else{
 installLog("/custom directory and subdirectory check passed");
}


// cache dir
    $cache_files[] = '';
    $cache_files[] = 'images';
    $cache_files[] = 'layout';
    $cache_files[] = 'pdf';
    $cache_files[] = 'xml';
    $cache_files[] = 'include/javascript';
    $filelist = '';

	foreach($cache_files as $c_file)
	{
		$dirname = sugar_cached($c_file);
		$ok = false;
		if ((is_dir($dirname)) || @sugar_mkdir($dirname,0555)) // set permissions to restrictive - use make_writable to change in a standard way to the required permissions
		{
			$ok = make_writable($dirname);
		}
		if (!$ok)
		{
			$filelist .= '<br>'.getcwd()."/$dirname";

		}
	}
	if (strlen($filelist)>0)
	{
	    $error_found = true;
        installLog("ERROR:: Some subdirectories in cache subfolder were not read/writeable:");
        installLog($filelist);
	    $error_txt .= '
		<tr>
        	<td><strong>'.$mod_strings['LBL_CHECKSYS_CACHE'].'</strong></td>
        	<td align="right" class="error" class="error"><b><span class="stop">'.$mod_strings['ERR_CHECKSYS_FILES_NOT_WRITABLE'].'</span></b></td>
		</tr>
		<tr>
        	<td colspan="2"><b>'.$mod_strings['LBL_CHECKSYS_FIX_FILES'].'</b>'.$filelist. '</td>
		</tr>';
	}else{
     installLog("cache directory and subdirectory check passed");
    }


// check modules dir
$_SESSION['unwriteable_module_files'] = array();
$passed_write = recursive_make_writable('./modules');
if (isset($_SESSION['unwriteable_module_files']['failed']) && $_SESSION['unwriteable_module_files']['failed']){
    $passed_write = false;
}

if(!$passed_write) {

    $moduleStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
    installLog("ERROR:: Module directories and the files under them are not writeable.");
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_MODULE'].'</strong></td>
        <td align="right" class="error">'.$moduleStatus.'</td>
      </tr>';

        //list which module directories are not writeable, if there are less than 10
        $error_txt .= '
          <tr>
            <td colspan="2">
            <b>'.$mod_strings['LBL_CHECKSYS_FIX_MODULE_FILES'].'</b>';
        foreach($_SESSION['unwriteable_module_files'] as $key=>$file){
            if($key !='.' && $key != 'failed'){
                $error_txt .='<br>'.$file;
            }
        }
        $error_txt .= '
            </td>
          </tr>';

}else{
 installLog("/module  directory and subdirectory check passed");
}

// check upload dir
if (!make_writable('./upload'))
{
    $uploadStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
    installLog("ERROR: Upload directory is not writable.");
    $error_found = true;
    $error_txt .= '
    <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_UPLOAD'].'</strong></td>
        <td align="right" class="error">'.$uploadStatus.'</td>
    </tr>';
} else {
    installLog("/upload directory check passed");
}

// check zip file support
if (!class_exists("ZipArchive"))
{
        $zipStatus = "<span class='stop'><b>{$mod_strings['ERR_CHECKSYS_ZIP']}</b></span>";

    installLog("ERROR: Zip support not found.");
    $error_found = true;
    $error_txt .= '
          <tr>
            <td><strong>'.$mod_strings['LBL_CHECKSYS_ZIP'].'</strong></td>
            <td  align="right" class="error">'.$zipStatus.'</td>
          </tr>';
} else {
    installLog("/zip check passed");

}

// check BCMATH support
if (!function_exists("bcadd"))
{
    $bcmathStatus = "<span class='stop'><b>{$mod_strings['ERR_CHECKSYS_BCMATH']}</b></span>";

    installLog("ERROR: BCMATH support not found.");
    $error_found = true;
    $error_txt .= '
      <tr>
        <td><strong>'.$mod_strings['LBL_CHECKSYS_BCMATH'].'</strong></td>
        <td  align="right" class="error">'.$bcmathStatus.'</td>
      </tr>';
} else {
    installLog("/BCMATH check passed");
}


// check htaccess & rewrite working
    if(empty($_SERVER["SERVER_SOFTWARE"]) || strpos($_SERVER["SERVER_SOFTWARE"],'Microsoft-IIS') === false) {
        installLog("Testing .htaccess redirects");
        if(file_exists(".htaccess")) {
            $old_htaccess = file_get_contents(".htaccess");
        }
        $basePath = parse_url($_SESSION['setup_site_url'], PHP_URL_PATH);
        if(empty($basePath)) $basePath = '/';
        $htaccess_test = <<<EOT

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase {$basePath}
    RewriteRule ^itest.txt$ install_test.txt [N,QSA]
</IfModule>
EOT;
       if(!empty($old_htaccess)) {
           $htaccess_test = $old_htaccess.$htaccess_test;
       }
       file_put_contents(".htaccess", $htaccess_test);
       file_put_contents("install_test.txt", "SUCCESS");
       $res = file_get_contents($_SESSION['setup_site_url']."/itest.txt");
       unlink("install_test.txt");
       if(!empty($old_htaccess)) {
           file_put_contents(".htaccess", $old_htaccess);
       } else {
           unlink(".htaccess");
       }
       if($res != "SUCCESS") {
           $error_found = true;
           $error_txt .= '
          <tr>
            <td><strong>'.$mod_strings['LBL_CHECKSYS_HTACCESS'].'</strong></td>
            <td  align="right" class="error"><span class="stop"><b>'.$mod_strings['ERR_CHECKSYS_HTACCESS'].'</b></span></td>
          </tr>';
       } else {
           installLog("Passed .htaccess redirects check");
       }
    }

// custom checks
$customSystemChecks = installerHook('additionalCustomSystemChecks');
if($customSystemChecks != 'undefined'){
	if($customSystemChecks['error_found'] == true){
		$error_found = true;
	}
	if(!empty($customSystemChecks['error_txt'])){
		$error_txt .= $customSystemChecks['error_txt'];
	}
}

// PHP.ini
$phpIniLocation = get_cfg_var("cfg_file_path");
installLog("php.ini location found. {$phpIniLocation}");
// disable form if error found

if($error_found){
    installLog("Outputting HTML for System check");
    installLog("Errors were found *************");
    $disabled = $error_found ? 'disabled="disabled"' : '';

    $help_url = get_help_button_url();
///////////////////////////////////////////////////////////////////////////////
////    BEGIN PAGE OUTPUT
    $out =<<<EOQ

  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">{$mod_strings['LBL_CHECKSYS_TITLE']}</th>
      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" alt="SugarCRM" border="0"></a>
       <br><a href="{$help_url}" target='_blank'>{$mod_strings['LBL_HELP']} </a>
       </th>
    </tr>

    <tr>
      <td colspan="2" width="600">
        <p>{$mod_strings['ERR_CHECKSYS']}</p>

        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="StyleDottedHr">
          <tr>
            <th align="left">{$mod_strings['LBL_CHECKSYS_COMPONENT']}</th>
            <th style="text-align: right;">{$mod_strings['LBL_CHECKSYS_STATUS']}</th>
          </tr>
            $error_txt

        </table>

        <div align="center" style="margin: 5px;">
          <i>{$mod_strings['LBL_CHECKSYS_PHP_INI']}<br>{$phpIniLocation}</i>
        </div>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2">
        <hr>
        <form action="install3.php" method="post" name="theForm" id="theForm">

        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
            <td>
                <input class="button" type="button" name="Re-check" value="{$mod_strings['LBL_CHECKSYS_RECHECK']}" onclick="callSysCheck();" id="button_next2"/>
            </td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table><br>
EOQ;
return $out;
}else{
    installLog("Outputting HTML for System check");
    installLog("No Errors were found *************");
 return 'passed';
}

}
////    END PAGEOUTPUT
///////////////////////////////////////////////////////////////////////////////
