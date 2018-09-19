<?php if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

require_once('soap/SoapHelperFunctions.php');
$GLOBALS['log']->debug("JSON_SERVER:");
$global_registry_var_name = 'GLOBAL_REGISTRY';

///////////////////////////////////////////////////////////////////////////////
////	SUPPORTED METHODS
/*
 * ADD NEW METHODS TO THIS ARRAY:
 * then create a function called "function json_$method($request_id, &$params)"
 * where $method is the method name
 */
$SUPPORTED_METHODS = array(
	'retrieve',
	'query',
);

/**
 * Generic retrieve for getting data from a sugarbean
 */
function json_retrieve($request_id, $params) {
    $jsonServer = new LegacyJsonServer();
    print $jsonServer->jsonRetrieve($request_id, $params);
}

function json_query($request_id, $params) {
    $jsonServer = new LegacyJsonServer();
    echo $jsonServer->jsonQuery($request_id, $params);;
}

////	END SUPPORTED METHODS
///////////////////////////////////////////////////////////////////////////////

// ONLY USED FOR MEETINGS
// HAS MEETING SPECIFIC CODE:
function populateBean(&$focus) {
    $jsonServer = new LegacyJsonServer();
    return $jsonServer->populateBean($focus);
}

///////////////////////////////////////////////////////////////////////////////
////	UTILS
function authenticate() {
	global $sugar_config;

	$user_unique_key =(isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
	$server_unique_key =(isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";

	if($user_unique_key != $server_unique_key) {
		$GLOBALS['log']->debug("JSON_SERVER: user_unique_key:".$user_unique_key."!=".$server_unique_key);
		session_destroy();
		return null;
	}

	if(!isset($_SESSION['authenticated_user_id'])) {
		$GLOBALS['log']->debug("JSON_SERVER: authenticated_user_id NOT SET. DESTROY");
		session_destroy();
		return null;
	}

	$current_user = BeanFactory::newBean('Users');

	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
	$GLOBALS['log']->debug("JSON_SERVER: retrieved user from SESSION");


	if($result == null) {
		$GLOBALS['log']->debug("JSON_SERVER: could get a user from SESSION. DESTROY");
		session_destroy();
		return null;
	}

	return $result;
}

function construct_where(&$query_obj, $table='',$module=null)
{
    $jsonServer = new LegacyJsonServer();
    return $jsonServer->constructWhere($query_obj, $table, $module);
}

////	END UTILS
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	JSON SERVER HANDLER LOGIC
//ignore notices
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ob_start();
insert_charset_header();
global $sugar_config;
if(!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
	$GLOBALS['log']->debug("JSON_SERVER:session_save_path:".$sugar_config['session_dir']);
}

session_start();
$GLOBALS['log']->debug("JSON_SERVER:session started");

$current_language = 'en_us'; // defaulting - will be set by user, then sys prefs

// create json parser
$json = getJSONobj();

// if the language is not set yet, then set it to the default language.
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '') {
	$current_language = $_SESSION['authenticated_user_language'];
} else {
	$current_language = $sugar_config['default_language'];
}

$locale = Localization::getObject();

$GLOBALS['log']->debug("JSON_SERVER: current_language:".$current_language);

// if this is a get, than this is spitting out static javascript as if it was a file
// wp: DO NOT USE THIS. Include the javascript inline using include/json_config.php
// using <script src=json_server.php></script> does not cache properly on some browsers
// resulting in 2 or more server hits per page load. Very bad for SSL.
if(strtolower($_SERVER['REQUEST_METHOD'])== 'get') {
	echo "alert('DEPRECATED API\nPlease report as a bug.');";
} else {
	// else act as a JSON-RPC server for SugarCRM
	// create result array
	$response = array();
	$response['result'] = null;
	$response['id'] = "-1";

	// authenticate user
	$current_user = authenticate();

	if(empty($current_user)) {
		$response['error'] = array("error_msg"=>"not logged in");
		print $json->encode($response, true);
		print "not logged in";
	}

	// extract request
    $request = $json->decode(file_get_contents("php://input"), true);

	if(!is_array($request)) {
		$response['error'] = array("error_msg"=>"malformed request");
		print $json->encode($response, true);
	}

	// make sure required RPC fields are set
	if(empty($request['method']) || empty($request['id'])) {
		$response['error'] = array("error_msg"=>"missing parameters");
		print $json->encode($response, true);
	}

	$response['id'] = $request['id'];

	if(in_array($request['method'], $SUPPORTED_METHODS)) {
		call_user_func('json_'.$request['method'],$request['id'],$request['params']);
	} else {
		$response['error'] = array("error_msg"=>"method:".$request["method"]." not supported");
		print $json->encode($response, true);
	}
}

ob_end_flush();
sugar_cleanup();
exit();
