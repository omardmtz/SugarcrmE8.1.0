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

if (!isset($_SESSION['SHOW_DUPLICATES']))
    sugar_die("Unauthorized access to this area.");

// retrieve $_POST values out of the $_SESSION variable - placed in there by AccountFormBase to avoid the length limitations on URLs implicit with GETS
//$GLOBALS['log']->debug('ShowDuplicates.php: _POST = '.print_r($_SESSION['SHOW_DUPLICATES'],true));
parse_str($_SESSION['SHOW_DUPLICATES'],$_POST);
$post = array_map("securexss", $_POST);
foreach ($post as $k => $v) {
    $_POST[$k] = $v;
}
unset($_SESSION['SHOW_DUPLICATES']);
//$GLOBALS['log']->debug('ShowDuplicates.php: _POST = '.print_r($_POST,true));

global $app_strings;
global $app_list_strings;

$error_msg = '';

$db = DBManagerFactory::getInstance();

global $current_language;
$mod_strings = return_module_language($current_language, 'Accounts');
$moduleName = $GLOBALS['app_list_strings']['moduleList']['Accounts'];
echo getClassicModuleTitle('Accounts', array($moduleName, $mod_strings['LBL_SAVE_ACCOUNT']), true);
$xtpl=new XTemplate ('modules/Accounts/ShowDuplicates.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

if((isset($_REQUEST['popup']) && $_REQUEST['popup'] == 'true') ||(isset($_POST['popup']) && $_POST['popup']==true)) insert_popup_header($theme);


$account = BeanFactory::newBean('Accounts');
$accountForm = new AccountFormBase();
$GLOBALS['check_notify'] = FALSE;

$query = 'select id, name, website, billing_address_city  from accounts where deleted=0 ';
$duplicates = $_POST['duplicate'];
$count = count($duplicates);
if ($count > 0)
{
	$query .= "and (";
	$first = true;
	foreach ($duplicates as $duplicate_id)
	{
		if (!$first) $query .= ' OR ';
		$first = false;
        $query .= "id=".$db->quoted($duplicate_id)." ";
	}
	$query .= ')';
}

$duplicateAccounts = array();


$result = $db->query($query);
$i=-1;
while(($row=$db->fetchByAssoc($result)) != null) {
	$i++;
	$duplicateAccounts[$i] = $row;
}

$xtpl->assign('FORMBODY', $accountForm->buildTableForm($duplicateAccounts,  'Accounts'));

$input = '';
foreach ($account->column_fields as $field)
{
	if (!empty($_POST['Accounts'.$field])) {
		$value = urldecode($_POST['Accounts'.$field]);
		$input .= "<input type='hidden' name='$field' value='{$value}'>\n";
	}
}
foreach ($account->additional_column_fields as $field)
{
	if (!empty($_POST['Accounts'.$field])) {
		$value = urldecode($_POST['Accounts'.$field]);
		$input .= "<input type='hidden' name='$field' value='{$value}'>\n";
	}
}

// Bug 25311 - Add special handling for when the form specifies many-to-many relationships
if(!empty($_POST['Contactsrelate_to'])) {
    $input .= "<input type='hidden' name='relate_to' value='{$_POST['Contactsrelate_to']}'>\n";
}
if(!empty($_POST['Contactsrelate_id'])) {
    $input .= "<input type='hidden' name='relate_id' value='{$_POST['Contactsrelate_id']}'>\n";
}

$input .= get_teams_hidden_inputs('Accounts');

$emailAddress = BeanFactory::newBean('EmailAddresses');
$input .= $emailAddress->getEmailAddressWidgetDuplicatesView($account);

$get = '';
if(!empty($_POST['return_module'])) $xtpl->assign('RETURN_MODULE', $_POST['return_module']);
else $get .= "Accounts";
$get .= "&return_action=";
if(!empty($_POST['return_action'])) $xtpl->assign('RETURN_ACTION', $_POST['return_action']);
else $get .= "DetailView";
if(!empty($_POST['return_id'])) $xtpl->assign('RETURN_ID', $_POST['return_id']);

if(!empty($_POST['popup']))
	$input .= '<input type="hidden" name="popup" value="'.$_POST['popup'].'">';
else
	$input .= '<input type="hidden" name="popup" value="false">';

if(!empty($_POST['to_pdf']))
	$input .= '<input type="hidden" name="to_pdf" value="'.$_POST['to_pdf'].'">';
else
	$input .= '<input type="hidden" name="to_pdf" value="false">';

if(!empty($_POST['create']))
	$input .= '<input type="hidden" name="create" value="'.$_POST['create'].'">';
else
	$input .= '<input type="hidden" name="create" value="false">';

$xtpl->assign('INPUT_FIELDS',$input);
$xtpl->parse('main');
$xtpl->out('main');


?>
