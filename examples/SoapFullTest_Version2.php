<?php
die('comment out this die to use this file');
define('sugarEntry', true);
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

$user_name ='';
$user_password = '';
$quick_test = false;
$url = $GLOBALS['sugar_config']['site_url'].'/service/v2/soap.php';
foreach($_POST as $name=>$value){
		$$name = $value;
}

echo <<<EOQ
<form name='test' method='POST'>
<table width ='800'><tr>
<tr><th colspan='6'>Enter  SugarCRM  User Information - this is the same info entered when logging into sugarcrm</th></tr>
<td >USER NAME:</td><td><input type='text' name='user_name' value='$user_name'></td><td>USER PASSWORD:</td><td><input type='password' name='user_password' value='$user_password'></td>
</tr>
<td >SOAP URL:</td><td colspan=3><input type='text' name='url' value='$url' size = 60></td>
</tr>

<tr><td><input type='submit' value='Submit'></td></tr>
</table>
</form>
EOQ;


if(!empty($user_name)){
$offset = 0;
if(isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'] + 20;
	echo $offset;
}
function print_result($result){
global $soapclient;
if(!empty($soapclient->error_str)){
	echo '<b>HERE IS ERRORS:</b><BR>';
	echo $soapclient->error_str;

	echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
	echo $soapclient->response;

}

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
print_r($result);
echo '<br>';
}

require_once('include/entryPoint.php');

require_once('soap/SoapHelperFunctions.php');


require_once('vendor/nusoap//nusoap.php');





require_once('vendor/nusoap//nusoap.php');  //must also have the nusoap code on the ClientSide.

$GLOBALS['log'] =& LoggerManager::getLogger('SugarCRM');
//ignore notices
error_reporting(E_ALL ^ E_NOTICE);

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

// Administration include


$administrator = new Administration();
$administrator->retrieveSettings();
$soapclient = new nusoapclient($url. '?wsdl', true);  //define the SOAP Client an

echo '<b>Get Server info: - get_server_info test</b><BR>';
$result = $soapclient->call('get_server_info');
print_result($result);

echo '<b>LOGIN: -login test</b><BR>';
$result = $soapclient->call('login',array('user_auth'=>array('user_name'=>$user_name,'password'=>md5($user_password), 'version'=>'.01'), 'application_name'=>'SoapTest'));
print_result($result);
$session = $result['id'];

echo '<b>Get User Id: - get_user_id test</b><BR>';
$result = $soapclient->call('get_user_id',array('session'=>$session));
print_result($result);

if(!$quick_test){
echo '<b>Get Contact Module Fields: - get_module_fields test</b><BR>';
$result = $soapclient->call('get_module_fields',array('session'=>$session, 'module_name'=>'Contacts'));
print_result($result);
}
echo '<br><br><b>Set A Contact - set_entry test:</b><BR>';
$time = date($GLOBALS['timedate']->get_db_date_time_format()) ;
$date = date($GLOBALS['timedate']->dbDayFormat, time() + rand(0,360000));
$hour = date($GLOBALS['timedate']->dbTimeFormat, time() + rand(0,360000));
$result = $soapclient->call('set_entry',array('session'=>$session,'module_name'=>'Contacts', 'name_value_list'=>array(array('name'=>'last_name' , 'value'=>"$time Contact SINGLE"), array('name'=>'first_name' , 'value'=>'tester'))));
print_result($result);
if(!$quick_test){
$name_value_lists = array();
echo '<br><br><b>Set list of Contacts - set_entries test:</b><BR>';
$dm = date($GLOBALS['timedate']->get_db_date_time_format(), time() - 36000) ;
$timestart = microtime(true);
for($i = 0; $i < 10; $i++){
$time = date($GLOBALS['timedate']->get_db_date_time_format()) ;
$date = date($GLOBALS['timedate']->dbDayFormat, time() + rand(0,360000));
$hour = date($GLOBALS['timedate']->dbTimeFormat, time() + rand(0,360000));
$name_value_lists[] =  array(array('name'=>'last_name' , 'value'=>"$time Contact $i"), array('name'=>'first_name' , 'value'=>'tester'));
}


$result = $soapclient->call('set_entries',array('session'=>$session,'module_name'=>'Contacts','name_value_lists'=>$name_value_lists));
$diff = microtime(true) - $timestart;
echo "<b>Time for creating $i Contacts is $diff </b> <br><br>";
print_result($result);
}
echo "<br><br><b>Get list of Contacts and their email addresses: -test get_entry_list</b><BR>";
$timestart = microtime(true);
$result = $soapclient->call('get_entry_list',array('session'=>$session,'module_name'=>'Contacts','query'=>'', 'order_by'=>'','offset'=>0,'select_fields'=>array(),'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))), 'max_results'=>10,'deleted'=>-1));
$diff = microtime(true) - $timestart;
echo "<b>Time for retrieving 10 Contacts is $diff </b> <br><br>";
print_result($result);

$contact_ids = array();
foreach($result['entry_list'] as $entry){
	$contact_ids[] = $entry['id'];

}
if(!$quick_test){
	echo "<br><br><b>Get a contact : -test get_entry</b><BR>";
	$timestart = microtime(true);
	$result = $soapclient->call('get_entry',array('session'=>$session,'module_name'=>'Contacts','id'=>$contact_ids[0],'select_fields'=>array('last_name', 'email1', 'date_modified','description'), 'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')), array('name' =>  'accounts', 'value' => array('name', 'city', 'phone')))));
	$diff = microtime(true) - $timestart;
	echo "<b>Time for retrieving a Contact is $diff </b> <br><br>";
	print_result($result);


	echo "<br><br><b>Get a list of contacts : -test get_entries</b><BR>";
	$timestart = microtime(true);
	$result = $soapclient->call('get_entries',array('session'=>$session,'module_name'=>'Contacts','ids'=>$contact_ids,'select_fields'=>array(), 'link_name_to_fields_array' => array(array('name' =>  'accounts', 'value' => array('name', 'id', 'phone', '')) ,array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))));
	$diff = microtime(true) - $timestart;
	echo "<b>Time for retrieving a list of Contacts is $diff </b> <br><br>";
	print_result($result);

}
echo '<BR>TESTING NOTES<BR>';
echo '<br><br><b>Set A Note - set_entry test:</b><BR>';
$time = date($GLOBALS['timedate']->get_db_date_time_format()) ;
$date = date($GLOBALS['timedate']->dbDayFormat, time() + rand(0,360000));
$hour = date($GLOBALS['timedate']->dbTimeFormat, time() + rand(0,360000));
$result = $soapclient->call('set_entry',array('session'=>$session,'module_name'=>'Notes', 'name_value_list'=>array(array('name'=>'name' , 'value'=>"$time Note $i"), )));
$note_id = $result['id'];
print_result($result);
echo '<br><br><b>Set A Note attachment - set_note_attachment test:</b><BR>';
$file = base64_encode('This is an attached file');
$result = $soapclient->call('set_note_attachment',array('session'=>$session,'note'=>array('id'=>$note_id, 'filename'=>'Attach.txt','file'=>$file) ));
print_result($result);

echo '<br><br><b>Get A Note attachment - get_note_attachment test:</b><BR>';
$result = $soapclient->call('get_note_attachment',array('session'=>$session,'id'=>$note_id ));
echo 'File Contents: ' . base64_decode($result['note_attachment']['file']).'<br>';
print_result($result);

echo '<BR>TESTING RELATIONSHIPS<BR>';
echo '<br><br><b>Create an Account - set_entry test:</b><BR>';
$result = $soapclient->call('set_entry',array('session'=>$session,'module_name'=>'Accounts', 'name_value_list'=>array(array('name'=>'name' , 'value'=>"$time Account "), )));
$account_id = $result['id'];
echo 'Account Id ' . $account_id;
echo '<br><br><b>Create an Email - set_entry test:</b><BR>';
$result = $soapclient->call('set_entry',array('session'=>$session,'module_name'=>'Emails', 'name_value_list'=>array(array('name'=>'name' , 'value'=>"$time Email"), )));
$email_id = $result['id'];
print_result($result);
echo '<br><br><b>Link Account to a Contact - set_relationship test:</b><BR>';
$result = $soapclient->call('set_relationship',array('session'=>$session,'module_name' => 'Accounts', 'module_id' => $account_id, 'link_field_name' => 'contacts', 'related_ids' => array($contact_ids[0])));
print_result($result);

echo '<br><br><b>Link Email to a Contact - set_relationship test:</b><BR>';
$result = $soapclient->call('set_relationship',array('session'=>$session,'module_name' => 'Emails','module_id' => $email_id,'link_field_name' => 'contacts','related_ids' =>array($contact_ids[0])));
print_result($result);

echo '<br><br><b>Link Email to two Contacts - set_relationships test:</b><BR> ';
$result = $soapclient->call('set_relationships',array('session'=>$session,'module_names' => array('Emails', 'Emails'),'module_ids' => array($email_id, $email_id),'link_field_names' => array('contacts', 'contacts'),'related_ids' => array(array($contact_ids[1]), array($contact_ids[2]))));

print_result($result);
echo '<br><br><b>Link Account to two Contacts - set_relationships test:</b><BR>';
$result = $soapclient->call('set_relationships',array('session'=>$session,'module_names' => array('Accounts', 'Accounts'), 'module_ids' => array($account_id, $account_id),'link_field_names' => array('contacts', 'contacts'),'related_ids' => array(array($contact_ids[1]), array($contact_ids[2]))));
print_result($result);

echo '<br><br><b>Retrieve Relationships for that account - get_relationships test:</b><BR>';
$result = $soapclient->call('get_relationships',array('session'=>$session,'module_name'=>'Accounts', 'module_id'=>$account_id, 'link_field_name'=>'contacts', 'related_module_query'=>'', 'related_fields' => array('first_name', 'last_name', 'primary_address_city'),'related_module_link_name_to_fields_array' => array(array('name' =>  'opportunities', 'value' => array('name', 'type', 'lead_source')), array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))), 'deleted'=>1 ));
print_result($result);

echo '<br><br><b>Get Available Modules - get_available_modules test:</b><BR>';
$result = $soapclient->call('get_available_modules',array('session'=>$session));
print_result($result);

echo '<br><br><b>Get Users Team Id - get_user_team_id test:</b><BR>';
$result = $soapclient->call('get_user_team_id',array('session'=>$session));
print_result($result);

echo '<br><br><b>Get Entries Count - get_entries_count test:</b><BR>';
$result = $soapclient->call('get_entries_count',array('session'=>$session, 'module_name' => 'Accounts', 'query' => '', 'deleted' => 0));
print_result($result);

echo '<br><br><b>LOGOUT:</b><BR>';
$result = $soapclient->call('logout',array('session'=>$session));
print_result($result);

}
?>
