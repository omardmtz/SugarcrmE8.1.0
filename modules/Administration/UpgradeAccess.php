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

require_once 'install/install_utils.php';
global $mod_strings;
global $sugar_config;

$ignoreCase = (substr_count(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache/2') > 0)?'(?i)':'';
$htaccess_file   = getcwd() . "/.htaccess";
$contents = getHtaccessData($htaccess_file);

$status =  file_put_contents($htaccess_file, $contents);
if( !$status ){
    echo '<p>' . $mod_strings['LBL_HT_NO_WRITE'] . "<span class=stop>{$htaccess_file}</span></p>\n";
    echo '<p>' . $mod_strings['LBL_HT_NO_WRITE_2'] . "</p>\n";
    echo "{$contents}\n";
}


// cn: bug 9365 - security for filesystem
$uploadDir='';
$uploadHta='';

if (empty($GLOBALS['sugar_config']['upload_dir'])) {
    $GLOBALS['sugar_config']['upload_dir']='upload/';
}

$uploadHta = "upload://.htaccess";

$denyAll =<<<eoq
	Order Deny,Allow
	Deny from all
eoq;

if(file_exists($uploadHta) && filesize($uploadHta)) {
	// file exists, parse to make sure it is current
	if(is_writable($uploadHta)) {
		$oldHtaccess = file_get_contents($uploadHta);
		// use a different regex boundary b/c .htaccess uses the typicals
		if(strstr($oldHtaccess, $denyAll) === false) {
		    $oldHtaccess .= "\n";
			$oldHtaccess .= $denyAll;
		}
		if(!file_put_contents($uploadHta, $oldHtaccess)) {
		    $htaccess_failed = true;
		}
	} else {
		$htaccess_failed = true;
	}
} else {
	// no .htaccess yet, create a fill
	if(!file_put_contents($uploadHta, $denyAll)) {
		$htaccess_failed = true;
	}
}
