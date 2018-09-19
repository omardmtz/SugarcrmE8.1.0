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

// $Id: siteConfig.php 18732 2006-12-15 23:50:49 +0000 (Fri, 15 Dec 2006) majed $

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if( is_file("config.php") ){
	if(!empty($sugar_config['default_theme']))
      $_SESSION['site_default_theme'] = $sugar_config['default_theme'];

	if(!empty($sugar_config['default_language']))
		$_SESSION['default_language'] = $sugar_config['default_language'];
	if(!empty($sugar_config['translation_string_prefix']))
		$_SESSION['translation_string_prefix'] = $sugar_config['translation_string_prefix'];
	if(!empty($sugar_config['default_charset']))
		$_SESSION['default_charset'] = $sugar_config['default_charset'];

	if(!empty($sugar_config['default_currency_name']))
		$_SESSION['default_currency_name'] = $sugar_config['default_currency_name'];
	if(!empty($sugar_config['default_currency_symbol']))
		$_SESSION['default_currency_symbol'] = $sugar_config['default_currency_symbol'];
	if(!empty($sugar_config['default_currency_iso4217']))
		$_SESSION['default_currency_iso4217'] = $sugar_config['default_currency_iso4217'];

	if(!empty($sugar_config['rss_cache_time']))
		$_SESSION['rss_cache_time'] = $sugar_config['rss_cache_time'];
	if(!empty($sugar_config['languages']))
	{
		// We need to encode the languages in a way that can be retrieved later.
		$language_keys = Array();
		$language_values = Array();

		foreach($sugar_config['languages'] as $key=>$value)
		{
			$language_keys[] = $key;
			$language_values[] = $value;
		}

		$_SESSION['language_keys'] = urlencode(implode(",",$language_keys));
		$_SESSION['language_values'] = urlencode(implode(",",$language_values));
	}
}

////	errors
$errors = '';
if( isset($validation_errors) && is_array($validation_errors)){
    if( count($validation_errors) > 0 ){
        $errors  = '<div id="errorMsgs">';
        $errors .= '<p>'.$mod_strings['LBL_SITECFG_FIX_ERRORS'].'</p><ul>';
        foreach( $validation_errors as $error ){
			$errors .= '<li>' . $error . '</li>';
        }
		$errors .= '</ul></div>';
    }
}


////	ternaries
$sugarUpdates = (isset($_SESSION['setup_site_sugarbeet']) && !empty($_SESSION['setup_site_sugarbeet'])) ? 'checked="checked"' : '';
$siteSecurity = (isset($_SESSION['setup_site_defaults']) && !empty($_SESSION['setup_site_defaults'])) ? 'checked="checked"' : '';
$customSession = (isset($_SESSION['setup_site_custom_session_path']) && !empty($_SESSION['setup_site_custom_session_path'])) ? 'checked="checked"' : '';
$customLog = (isset($_SESSION['setup_site_custom_log_dir']) && !empty($_SESSION['setup_site_custom_log_dir'])) ? 'checked="checked"' : '';
$customId = (isset($_SESSION['setup_site_specify_guid']) && !empty($_SESSION['setup_site_specify_guid'])) ? 'checked="checked"' : '';

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
   <title>{$mod_strings['LBL_WIZARD_TITLE']}  {$mod_strings['LBL_SITECFG_TITLE']}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css" />
   <script type="text/javascript" src="install/installCommon.js"></script>
   <script type="text/javascript" src="install/siteConfig.js"></script>
</head>
<body onload="javascript:document.getElementById('button_next2').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<input type="hidden" name="current_step" value="{$next_step}">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
      <tr><td colspan="2" id="help"><a href="{$help_url}" target='_blank'>{$mod_strings['LBL_HELP']} </a></td></tr>
    <tr>
      <th width="500">
		<p>
		<img src="{$sugar_md}" alt="SugarCRM" border="0">
		</p>
   {$mod_strings['LBL_SITECFG_TITLE']}</th>
   <th width="200" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank">
		<IMG src="include/images/sugarcrm_login.png" alt="SugarCRM" border="0"></a>
        </th>
   </tr>
<tr>
    <td colspan="2">
    {$errors}
   <div class="required">{$mod_strings['LBL_REQUIRED']}</div>
   <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
   <tr><th colspan="3" align="left">{$mod_strings['LBL_SITECFG_TITLE2']}</td></tr>
EOQ;



//hide this in typical mode
if(!empty($_SESSION['install_type'])  && strtolower($_SESSION['install_type'])=='custom'){
    $out .=<<<EOQ

   <tr><td colspan="3" align="left"> {$mod_strings['LBL_SITECFG_URL_MSG']}
   </td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_URL']}</td>
       <td align="left"><input type="text" name="setup_site_url" id="button_next2" value="{$_SESSION['setup_site_url']}" size="40" /></td></tr>
    <tr><td colspan="3" align="left"> <br>{$mod_strings['LBL_SITECFG_SYS_NAME_MSG']}</td></tr>
    <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SYSTEM_NAME']}</b></td>
       <td align="left"><input type="text" name="setup_system_name" value="{$_SESSION['setup_system_name']}" size="40" /><br>&nbsp;</td></tr>
EOQ;
    $db = getDbConnection();
    if($db->supports("collation")) {
        $collationOptions = $db->getCollationList();
    }
    if(!empty($collationOptions)) {
        if(isset($_SESSION['setup_db_options']['collation'])) {
            $default = $_SESSION['setup_db_options']['collation'];
        } else {
            $default = $db->getDefaultCollation();
        }
        $options = get_select_options_with_id(array_combine($collationOptions, $collationOptions), $default);
        $out .=<<<EOQ
       <tr><td colspan="3" align="left"> <br>{$mod_strings['LBL_SITECFG_COLLATION_MSG']}</td></tr>
        <tr><td><span class="required">*</span></td>
           <td><b>{$mod_strings['LBL_COLLATION']}</b></td>
           <td align="left"><select name="setup_db_collation" id="setup_db_collation">$options</select><br>&nbsp;</td></tr>
EOQ;
   }
}

$out .=<<<EOQ

    <tr><td colspan="3" align="left"> {$mod_strings['LBL_SITECFG_PASSWORD_MSG']}</td></tr>
    <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_ADMIN_Name']}</b><br>
       </td>
       <td align="left"><input type="text" name="setup_site_admin_user_name" value="{$_SESSION['setup_site_admin_user_name']}" size="20" maxlength="60" /></td></tr>
    <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_ADMIN_PASS']}</b><br>
       </td>
       <td align="left"><input type="password" name="setup_site_admin_password" value="{$_SESSION['setup_site_admin_password']}" autocomplete="off" size="20" /></td></tr>
    <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_ADMIN_PASS_2']}</td>
       <td align="left"><input type="password" name="setup_site_admin_password_retype" value="{$_SESSION['setup_site_admin_password_retype']}" autocomplete="off" size="20" /></td></tr>

EOQ;

$out .= <<<EOQ
</table>
</td>
</tr>
<tr>
   <td align="right" colspan="2">
   <hr>
   <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
   <tr>
    <td>
        <input class="button" type="button" name="goto" value="{$mod_strings['LBL_BACK']}" id="button_back_siteConfig_a" onclick="document.getElementById('form').submit();" />
        <input type="hidden" name="goto" value="{$mod_strings['LBL_BACK']}" />
    </td>
   <td><input class="button" type="submit" name="goto" id="button_next2" value="{$mod_strings['LBL_NEXT']}" /></td>
   </tr>
   </table>
</td>
</tr>
</table>
</form>
<br>
</body>
</html>

EOQ;

echo $out;
?>
