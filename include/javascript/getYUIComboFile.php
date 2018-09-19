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
if (empty($_REQUEST)) die();

$yui_path = array(
    "2.9.0" => "include/javascript/yui/build",
    "2_9_0" => "include/javascript/yui/build",
    "3.15.0" => "include/javascript/yui3/build",
    "3_15_0" => "include/javascript/yui3/build",
);
$types = array(
    "js" => "application/javascript",
	"css" => "text/css",
);
$out = "";

$contentType = "";
$allpath = "";

foreach ($_REQUEST as $param => $val)
{
	//No backtracking in the path
	if (strpos($param, "..") !== false)
        continue;

	$version = explode("/", $param);
	$version = $version[0];
    if (empty($yui_path[$version])) continue;

    $path = $yui_path[$version] . substr($param, strlen($version));

	$extension = substr($path, strrpos($path, "_") + 1);

	//Only allowed file extensions
	if (empty($types[$extension]))
	   continue;

	if (empty($contentType))
    {
        $contentType = $types[$extension];
    }
	//Put together the final filepath
	$path = substr($path, 0, strrpos($path, "_")) . "." . $extension;
	$contents = '';
	if (is_file($path)) {
	   $out .= "/*" . $path . "*/\n";
	   $contents =  file_get_contents($path);
	   $out .= $contents . "\n";
	}
	$path = empty($contents) ? $path : $contents;
	$allpath .= md5($path);
}

$etag = '"'.md5($allpath).'"';

// try to use the content cached locally if it's the same as we have here.
header("Cache-Control: private");
header("Pragma: dummy=bogus");
header("Etag: $etag");
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
header("Content-Type: $contentType");
echo ($out);
