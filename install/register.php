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

// $Id: register.php 54512 2010-02-12 19:13:35Z jmertic $
$suicide = true;
if(isset($install_script)) {
	if($install_script) {
		$suicide = false;
	}
}

if($suicide) {
   // mysterious suicide note
   die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}


if (!isset($_POST['confirm']) || !$_POST['confirm']) {
	include("sugar_version.php"); // provide $sugar_flavor
       global $sugar_config;
        $ik = '';
       if(isset($sugar_config['unique_key']) && !empty($sugar_config['unique_key']) ){
        $ik = $sugar_config['unique_key'];
       }

	//$regPhp = file_get_contents("http://www.sugarcrm.com/product-registration/registration_php.php?edition={$sugar_flavor}&instance_key=".$ik);
	//changing the reg form. placing in an iframe
	/*
	$regPhp="<iframe src='https://www.sugarcrm.com/product-registration/
	registration_php_080428.php?edition={$sugar_flavor}&instance_key=
	{$ik}' height='400px' width='700px' frameborder='0' scrolling='no'
	allowtransparency='true'</iframe>";
	*/
    $regPhp="<iframe src='https://www.sugarcrm.com/product-registration/registration_php_080428.php?edition={$sugar_flavor}&instance_key=
    {$ik}' height='595px' width='700px' frameborder='0' style='overflow-x:hidden; overflow-y: scroll;'
    allowtransparency='true'></iframe>";


	$notConfirmed =<<<CONF
		<!-- <p>{$mod_strings['LBL_REG_CONF_1']}</p> -->
		<!-- begin registration -->
		{$regPhp}
		<!-- end registration -->
CONF;

} else {
	$notConfirmed = $mod_strings['LBL_REG_CONF_3'];
}


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
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_REG_TITLE']}</title>
   <link REL="SHORTCUT ICON" HREF="$icon">
   <link rel="stylesheet" href="$css" type="text/css" />
   <script type="text/javascript" src="$common"></script>
</head>
<body onload="javascript:document.getElementById('button_next2').focus();">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
      <tr><td colspan="2" id="help">&nbsp;</td></tr>
    <tr>
      <th width="500">
		<p>
		<img src="{$sugar_md}" alt="SugarCRM" border="0">
		</p>
		{$mod_strings['LBL_REG_TITLE']} <span style="font-size: 9px;"> (Optional)</span></th>
	<th width="200" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank"><IMG src="$loginImage" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
    <td colspan="2">{$notConfirmed}</td>
</tr>
<tr>
	<td align="right" colspan="2">
	<hr>
	<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
		<tr>
		<td>&nbsp;</td>
		    <td>
                <form action="index.php" method="post" name="appform" id="appform">
                    <input type="hidden" name="default_user_name" value="admin">
                    <input class="button" type="submit" name="next" value="{$mod_strings['LBL_NEXT']}" id="button_next2"/>
		    	</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
<br>
</body>
</html>
EOQ;

echo $out;

?>