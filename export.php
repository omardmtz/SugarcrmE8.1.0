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
//Bug 30094, If zlib is enabled, it can break the calls to header() due to output buffering. This will only work php5.2+
ini_set('zlib.output_compression', 'Off');

ob_start();
require_once('include/export_utils.php');
global $sugar_config;
global $locale;
global $current_user;
global $app_list_strings;

$the_module = clean_string($_REQUEST['module']);

if($sugar_config['disable_export'] 	|| (!empty($sugar_config['admin_export_only']) && !(is_admin($current_user) || (ACLController::moduleSupportsACL($the_module)  && ACLAction::getUserAccessLevel($current_user->id,$the_module, 'access') == ACL_ALLOW_ENABLED &&
    (ACLAction::getUserAccessLevel($current_user->id, $the_module, 'admin') == ACL_ALLOW_ADMIN ||
     ACLAction::getUserAccessLevel($current_user->id, $the_module, 'admin') == ACL_ALLOW_ADMIN_DEV))) ||
        !SugarACL::checkAccess($the_module, 'export'))) {
	die($GLOBALS['app_strings']['ERR_EXPORT_DISABLED']);
}

//check to see if this is a request for a sample or for a regular export
if(!empty($_REQUEST['sample'])){
    //call special method that will create dummy data for bean as well as insert standard help message.
    $content = exportSample(clean_string($_REQUEST['module']));

}else if(!empty($_REQUEST['uid'])){
	$content = export(clean_string($_REQUEST['module']), $_REQUEST['uid'], isset($_REQUEST['members']) ? $_REQUEST['members'] : false);
}else{
	$content = export(clean_string($_REQUEST['module']));
}
$filename = $_REQUEST['module'];
//use label if one is defined
if(!empty($app_list_strings['moduleList'][$_REQUEST['module']])){
    $filename = $app_list_strings['moduleList'][$_REQUEST['module']];
}

//strip away any blank spaces
$filename = str_replace(' ','',$filename);

$transContent = $GLOBALS['locale']->translateCharset($content, 'UTF-8', $GLOBALS['locale']->getExportCharset());

if(isset($_REQUEST['members']) && $_REQUEST['members'] == true)
{
	$filename .= '_'.'members';
}
///////////////////////////////////////////////////////////////////////////////
////	BUILD THE EXPORT FILE
ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream; charset=".$GLOBALS['locale']->getExportCharset());
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . TimeDate::httpTime() );
header("Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".mb_strlen($transContent, '8bit'));
if (!empty($sugar_config['export_excel_compatible'])) {
    $transContent=chr(255) . chr(254) . mb_convert_encoding($transContent, 'UTF-16LE', 'UTF-8');
}
print $transContent;

sugar_cleanup(true);
