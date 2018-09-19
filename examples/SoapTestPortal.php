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
$portal_name ='';
$portal_password = '';
$user_name ='';
$user_password = '';
foreach($_POST as $name=>$value){
		$$name = $value;
}
echo <<<EOQ
<form name='test' method='POST'>
<table width ='800'><tr>
<tr><th colspan='6'>Enter  SugarCRM Portal User Information (to configure this login to SugarCRM as an admin and go the administration panel then select a user from user management)</th></tr>
<td >PORTAL NAME:</td><td><input type='text' name='portal_name' value='$portal_name'></td><td>PORTAL PASSWORD:</td><td><input type='password' name='portal_password' value='$portal_password'></td>
</tr>
<tr><th colspan='6'>Enter  SugarCRM Portal Contact Information (to configure this login to SugarCRM as an admin and edit a contact. You will see Portal Information at the bottom of the Contact EditView Panel)</th></tr>
<tr>
<td>CONTACT NAME:</td><td><input type='text' name='user_name' value='$user_name'></td>
</tr>
<tr><td><input type='submit' value='Submit'></td></tr>
</table>
</form>


EOQ;

if(!empty($portal_name)){
$portal_password = md5($portal_password);
require_once('../vendor/nusoap//nusoap.php');  //must also have the nusoap code on the ClientSide.
$soapclient = new nusoapclient('http://localhost/sugarcrm/soap.php');  //define the SOAP Client an
//application_name is the client application connecting to sugar CRM for example mambo
echo '<b>LOGIN:</b><BR>';
$result = $soapclient->call('portal_login',array('portal_auth'=>array('user_name'=>$portal_name,'password'=>$portal_password, 'version'=>'.01'),'user_name'=>$user_name, 'application_name'=>'SoapTestPortal'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;
echo '<BR><BR>';
echo $soapclient->responseHeaders;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$session = $result['id'];
echo '<br><br><b>GET CASES:</b><BR>';
$result = $soapclient->call('portal_get_entry_list',array('session'=>$session , 'module_name'=>'Cases','where'=>"date_entered > '".date($GLOBALS['timedate']->dbDayFormat) ."'", 'order_by'=>'', 'select_fields'=>array('name', 'description', 'priority', 'status')));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$case_id = $result['entry_list'][0]['id'];

echo '<br><br><b>GET Case fields:</b><BR>';
$result = $soapclient->call('portal_get_module_fields',array('session'=>$session , 'module_name'=>'Cases'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>CREATE Case:</b><BR>';
$result = $soapclient->call('portal_set_entry',array('session'=>$session , 'module_name'=>'Cases', 'name_value_list'=>array(array('name'=>'name', 'value'=>'a case'), array('name'=>'description', 'value'=>'A case created through webservices'))));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

$case_id = $result['id'];
echo '<br><br><b>CREATE Note:</b><BR>';
$result = $soapclient->call('portal_set_entry',array('session'=>$session , 'module_name'=>'Notes', 'name_value_list'=>array(array('name'=>'name', 'value'=>'a note attached to a case'), array('name'=>'description', 'value'=>'A note created through webservices'))));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$note_id = $result['id'];
echo '<br><br><b>ATTACH A FILE:</b><BR>';
$file = base64_encode('this would be the contents of your file');
$result = $soapclient->call('portal_set_note_attachment',array('session'=>$session, 'note_attachment'=>array('id'=>$note_id, 'filename'=>'an attached file', 'file'=>$file)));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>ATTACH NOTE TO THE CASE:</b><BR>';

$result = $soapclient->call('portal_relate_note_to_module',array('session'=>$session, 'note_id'=>$note_id, 'module_name'=>'Cases', 'module_id'=>$case_id));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br><br><b>GET NOTES RELATED TO A CASE:</b><BR>';
$result = $soapclient->call('portal_get_related_notes',array('session'=>$session , 'module_name'=>'Cases', 'module_id'=>$case_id, 'select_fields'=>array('name', 'description', 'filename')));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$note_id = $result['entry_list'][0]['id'];

echo '<br><br><b>GET ATTACHMENT TO A NOTE:</b><BR>';
$result = $soapclient->call('portal_get_note_attachment',array('session'=>$session , 'id'=>$note_id));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

echo '<br>It Reads:' . base64_decode($result['note_attachment']['file']);

echo '<br><br><b>LOGOUT:</b><BR>';
$result = $soapclient->call('portal_logout',array('session'=>$session));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);


echo '<b>LOG BACK IN:</b><BR>';
$result = $soapclient->call('portal_login',array('portal_auth'=>array('user_name'=>$portal_name,'password'=>$portal_password, 'version'=>'.01'),'user_name'=>$user_name, 'application_name'=>'SoapTestPortal'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$session = $result['id'];


echo '<br><br><b>GET CASES:</b><BR>';
$result = $soapclient->call('portal_get_entry_list',array('session'=>$session , 'module_name'=>'Cases','where'=>"date_entered > '".date($GLOBALS['timedate']->dbDayFormat) ."'", 'order_by'=>'', 'select_fields'=>array('name', 'description', 'priority', 'status')));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$case_id = $result['entry_list'][0]['id'];
//FOR AUTHENTICATION YOU MUST DO A LOOK UP OF CASES BEFORE YOU TRY TO GET A NOTE RELATED TO A CASE
echo '<br><br><b>GET NOTES RELATED TO A CASE:</b><BR>';
$result = $soapclient->call('portal_get_related_notes',array('session'=>$session , 'module_name'=>'Cases', 'module_id'=>$case_id, 'select_fields'=>array('name', 'description', 'filename')));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$note_id = $result['entry_list'][0]['id'];

echo '<br><br><b>GET ATTACHMENT TO A NOTE:</b><BR>';
$result = $soapclient->call('portal_get_note_attachment',array('session'=>$session , 'id'=>$note_id));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
//does not have accesss to this case
$case_id = 'bf90ee6f-c7fa-520c-f4b4-4212a55eb969';
echo '<br><br><b>GET NOTES RELATED TO A CASE NOT RELATED TO THE LOGGED IN CONTACT:</b><BR>';
$result = $soapclient->call('portal_get_related_notes',array('session'=>$session , 'module_name'=>'Cases', 'module_id'=>$case_id, 'select_fields'=>array('name', 'description')));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$note_id = $result['entry_list'][0]['id'];



echo '<br><br><b>LOGOUT:</b><BR>';
$result = $soapclient->call('portal_logout',array('session'=>$session));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);

}
?>