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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

/*********************************************************************************
 * $Id$
 * Description:
 ********************************************************************************/
// cn: bug 6078: zlib breaks test-settings
$iniError = '';
if(ini_get('zlib.output_compression') == 1) { // ini_get() returns 1/0, not value
	if(!ini_set('zlib.output_compression', 'Off')) { // returns False on failure
		$iniError = $mod_strings['ERR_INI_ZLIB'];
	}
}

global $current_user;

if (SugarConfig::getInstance()->get("disable_user_email_config", false)
        && !$current_user->isAdminForModule("Emails")
) {
    ACLController::displayNoAccess(false);
    sugar_cleanup(true);
}


// hack to allow "&", "%" and "+" through a $_GET var
// set by ie_test_open_popup() javascript call
foreach($_REQUEST as $k => $v) {
	$v = str_replace('::amp::', '&', $v);
	$v = str_replace('::plus::', '+', $v);
	$v = str_replace('::percent::', '%', $v);
	$_REQUEST[$k] = $v;
}

if(ob_get_level() > 0) {
	ob_end_clean();
}

if(ob_get_level() < 1) {
	ob_start();
}


require_once FileLoader::validateFilePath('modules/InboundEmail/language/'.$current_language.'.lang.php');
global $theme;

$title				= '';
$msg				= '';
$tls				= '';
$cert				= '';
$ssl				= '';
$notls				= '';
$novalidate_cert	= '';
$useSsl				= false;

///////////////////////////////////////////////////////////////////////////////
////	TITLES

$popupBoolean = false;
if (isset($_REQUEST['target']) && $_REQUEST['target'] == 'Popup') {
	$popupBoolean = true;
}
if (isset($_REQUEST['target1']) && $_REQUEST['target1'] == 'Popup') {
	$popupBoolean = true;
}

if($popupBoolean) {
	$title = '';
	$msg = $mod_strings['LBL_TEST_WAIT_MESSAGE'];
}

if(isset($_REQUEST['ssl']) && ($_REQUEST['ssl'] == "true" || $_REQUEST['ssl'] == 1)) {
	$msg .= $mod_strings['LBL_FIND_SSL_WARN'];
	$useSsl = true;
}


$ie = BeanFactory::newBean('InboundEmail');
if(!empty($_REQUEST['ie_id'])) {
    $ie->disable_row_level_security = true;
    $ie->retrieve($_REQUEST['ie_id']);
    if (!empty($ie->email_password)) {
        $ie->email_password = html_entity_decode($ie->email_password, ENT_QUOTES);
    }
}
$ie->email_user     = $_REQUEST['email_user'];
$ie->server_url     = $_REQUEST['server_url'];
$ie->port           = $_REQUEST['port'];
$ie->protocol       = $_REQUEST['protocol'];
//Bug 23083.Special characters in email password results in IMAP authentication failure
if(!empty($_REQUEST['email_password'])) {
    $ie->email_password = html_entity_decode($_REQUEST['email_password'], ENT_QUOTES);
    $ie->email_password = str_rot13($ie->email_password);
}
$ie->mailbox        = 'INBOX';

if($popupBoolean) {
    $msg = $ie->connectMailserver(true);
}

////	END TITLES
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	COMMON CODE
echo '<p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					'.SugarThemeRegistry::current()->getImage('h3Arrow', 'border="0"', 11, 11, ".gif", $mod_strings['LBL_POPUP_TITLE']).'
				</td>
				<td>
					<h3>&nbsp;'.$title.'</h3>
				</td>
			</tr>
			<tr>
				<td></td>
				<td valign="top">
					<div id="testSettingsMsg">
					'.$msg.'
					</div>
					<div id="testSettingsTic"></div>
					<div id="testSettingsErr">'.$iniError.'</div>
				</td>
			</tr>';
echo '	</table>';


////	END COMMON CODE
///////////////////////////////////////////////////////////////////////////////
