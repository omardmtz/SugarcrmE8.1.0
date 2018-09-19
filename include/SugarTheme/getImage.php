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

$spriteNamespace = isset($_REQUEST['spriteNamespace']) ? basename($_REQUEST['spriteNamespace']) : '';
$imageName = basename($_REQUEST['imageName']);

// try to use the user's theme if we can figure it out
if ( isset($_REQUEST['themeName']) && SugarThemeRegistry::current()->name != $_REQUEST['themeName']) {
    SugarThemeRegistry::set($_REQUEST['themeName']);
} elseif ( isset($_SESSION['authenticated_user_theme']) ) {
    SugarThemeRegistry::set($_SESSION['authenticated_user_theme']);
}

if (!empty($spriteNamespace)) {
    $filename = "cache/sprites/$spriteNamespace/$imageName";
	if(! file_exists($filename)) {
		header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
		die;
	}
} else {
	$filename = SugarThemeRegistry::current()->getImageURL($imageName);
	if ( empty($filename) ) {
		header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
		die;
	}
}

$filename_arr = explode('?', $filename);
$filename = $filename_arr[0];
$file_ext = substr($filename,-3);

$extensions = SugarThemeRegistry::current()->imageExtensions;
if(!in_array($file_ext, $extensions)){
	header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
    die;
}


// try to use the content cached locally if it's the same as we have here.
if(defined('TEMPLATE_URL'))
	$last_modified_time = time();
else
	$last_modified_time = filemtime($filename);

$etag = '"'.md5_file($filename).'"';

header("Cache-Control: private");
header("Pragma: dummy=bogus");
header("Etag: $etag");
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));

$ifmod = isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
    ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $last_modified_time : null;
$iftag = isset($_SERVER['HTTP_IF_NONE_MATCH'])
    ? $_SERVER['HTTP_IF_NONE_MATCH'] == $etag : null;
if (($ifmod || $iftag) && ($ifmod !== false && $iftag !== false)) {
    header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified');
    die;
}

header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', $last_modified_time));

// now send the content
if ( substr($filename,-3) == 'gif' )
    header("Content-Type: image/gif");
elseif ( substr($filename,-3) == 'png' )
    header("Content-Type: image/png");

if(!defined('TEMPLATE_URL')) {
    if(!file_exists($filename)) {
        sugar_touch($filename);
    }
}

readfile($filename);
