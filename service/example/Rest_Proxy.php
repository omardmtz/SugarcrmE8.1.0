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

include "../../config.php";

ob_start();
$fp =  fopen('proxy.log', 'a');
define('PROXY_SERVER',  $sugar_config['site_url'] . "service/v4_1/rest.php");
$headers = (function_exists('getallheaders'))?getallheaders(): array();
$_headers  = array();
foreach($headers as $k=>$v){
	$_headers[strtolower($k)] = $v;
}
$url = parse_url(PROXY_SERVER);
$curl_headers = array();
if(!empty($_headers['referer']))$curl_headers['referer'] = 'Referer: '  . $_headers['referer'];
if(!empty($_headers['user-agent']))$curl_headers['user-agent'] = 'User-Agent: ' . $_headers['user-agent'];
if(!empty($_headers['accept']))$curl_headers['accept'] = 'Accept: ' . $_headers['accept'];
if(!empty($_headers['accept-language']))$curl_headers['accept-Language'] = 'Accept-Language: ' . $_headers['accept-language'];
if(!empty($_headers['accept-encoding']))$curl_headers['accept-encoding:'] = 'Accept-Encoding: ' .$_headers['accept-encoding'];
if(!empty($_headers['accept-charset']))$curl_headers['accept-charset:'] = 'Accept-Charset: ' .$_headers['accept-charset'];

// create a new cURL resource
$ch = curl_init();
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, PROXY_SERVER);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0  );
$post_data = '';
if(!empty($_POST)){
	foreach($_POST as $k=>$v){
		if(get_magic_quotes_gpc())$v = stripslashes($v);
		if(!empty($post_data))$post_data .= '&';
		$post_data .= "$k=" . $v;
	}
}
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
// grab URL and pass it to the browser
fwrite($fp, 'client headers:' . var_export($headers, true) . "\n");
fwrite($fp, 'starting curl request' . "\n");
fwrite($fp, $post_data . "\n");
$result = curl_exec($ch);
curl_close($ch);
fwrite($fp, 'finished curl request' . "\n");
fwrite($fp, 'response:' . var_export($result, true) . "\n");
//we only handle 1 response no redirects
$result = explode("\r\n\r\n", $result, 2);
//we neeed to split up the ehaders
$result_headers = explode("\r\n", $result[0]);
//now echo out the same headers the server passed to us
fwrite($fp, "setting headers\n");
foreach($result_headers as &$header){
	if(substr_count($header, 'Set-Cookie:') ==0)
	header($header);
}
header('Content-Length: ' . strlen($result[1]));
header('Connection: close');
// now echo out the body
fwrite($fp, "sending body\n");
echo $result[1];
ob_end_flush();
fwrite($fp, "done\n");
die();
