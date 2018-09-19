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


// $Id: licenseKey.php 54512 2010-02-12 19:13:35Z jmertic $

if( !isset( $install_script ) || !$install_script ) {
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if( !isset($_SESSION['licenseKey_submitted']) || !$_SESSION['licenseKey_submitted'] ) {
    $_SESSION['setup_license_key_users']        = 0;
    $_SESSION['setup_license_key_expire_date']  = "";
    $_SESSION['setup_license_key']              = "";
}

////	errors
$errors = '';
if( isset($validation_errors) ){
    if( count($validation_errors) > 0 ){
        $errors  = '<div id="errorMsgs">';
        $errors .= '<p>'.$mod_strings['LBL_SITECFG_FIX_ERRORS'].'</p><ul>';
        foreach( $validation_errors as $error ){
			$errors .= '<li>' . $error . '</li>';
        }
		$errors .= '</ul></div>';
    }
}

////	javascript
$javascript = "
<script>
	requiredTxt = '".$mod_strings['ERR_LICENSE_MISSING']."'
	addToValidate('setLicense', 'setup_license_key_users',       'int',  false,  '".$mod_strings["LBL_LICENSE_NUM_USERS"]."' );
	addToValidate('setLicense', 'setup_license_key_expire_date', 'date', false,  '".$mod_strings["LBL_LICENSE_EXPIRY"]." (yyyy-mm-dd)' );
</script>";


///////////////////////////////////////////////////////////////////////////////
////	START OUTPUT
$langHeader = get_language_header();
$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$langHeader}>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$next_step}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css" />
	<script type="text/javascript" src="include/javascript/sugar_3.js"></script>
   <script type="text/javascript" src="install/installCommon.js"></script>
</head>
<body onload="javascript:document.getElementById('button_next2').focus();">
<form action="install.php" method="post" name="setLicense" id="form" onSubmit="return check_form('setLicense');">
<input type="hidden" name="current_step" value="{$next_step}">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
      <tr><td colspan="2" id="help">&nbsp;</td></tr>
    <tr>
      <th width="500">
		<p>
		<img src="{$sugar_md}" alt="SugarCRM" border="0">
		</p>
		{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_LICENSE_TITLE']}</th>

   <th width="200" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank"><IMG src="include/images/sugarcrm_login.png" alt="SugarCRM" border="0"></a></th>
   </tr>
<tr>
    <td colspan="2">
    <p>{$mod_strings['LBL_LICENSE_DIRECTIONS']}</p>
    {$errors}

   <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
   <tr><th colspan="3" align="left">{$mod_strings['LBL_LICENSE_TITLE']}</td></tr>
   <tr><td></td>
       <td><b>{$mod_strings['LBL_LICENSE_USERS']}</td>
       <td align="left"><input type="text" name="setup_license_key_users" id="button_next2"
			value="{$_SESSION['setup_license_key_users']}" size="6" /></td></tr>
   <tr><td></td>
       <td><b>{$mod_strings['LBL_LICENSE_EXPIRY']} (yyyy-mm-dd)</td>
       <td align="left"><input type="text" name="setup_license_key_expire_date"
            value="{$_SESSION['setup_license_key_expire_date']}" size="10" /></td></tr>
   <tr><td></td>
       <td><b>{$mod_strings['LBL_LICENSE_DOWNLOAD_KEY']}</td>
       <td align="left"><input type="text" name="setup_license_key"
            value="{$_SESSION['setup_license_key']}" size="20" /></td></tr>
</table>
</td>
</tr>
<tr>
   <td align="right" colspan="2">
   <hr>
   <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
   <tr>
   <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
   <td>
       <input class="button" type="button" name="goto" value="{$mod_strings['LBL_BACK']}" id="button_back_licenseKey" onclick="document.getElementById('form').submit();" />
		<input type="hidden" name="goto" value="{$mod_strings['LBL_BACK']}" />

   </td>
   <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" /></td>
   </tr>
   </table>
</td>
</tr>
</table>
</form>
<br>

{$javascript}

</body>
</html>
EOQ;

echo $out;
?>
