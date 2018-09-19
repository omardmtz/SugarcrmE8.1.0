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

// $Id: welcome.php 56115 2010-04-26 17:08:09Z kjing $

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}
// $mod_strings come from calling page.

$langDropDown = get_select_options_with_id($supportedLanguages, $current_language);

///////////////////////////////////////////////////////////////////////////////
////	START OUTPUT

$langHeader = get_language_header();
$out = <<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$langHeader}>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version} {$mod_strings['LBL_WELCOME_SETUP_WIZARD']}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css">
</head>

<body onload="javascript:document.getElementById('button_next2').focus();">
	<form action="install.php" method="post" name="form" id="form">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
  <tr><td colspan="2" id="help"><a href="{$help_url}" target='_blank'>{$mod_strings['LBL_HELP']} </a></td></tr>
    <tr>
			<th width="500">
		<p>
		<img src="{$sugar_md}" alt="SugarCRM" border="0">
		</p>
		{$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version} {$mod_strings['LBL_WELCOME_SETUP_WIZARD']}</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
          "_blank"><IMG src="include/images/sugarcrm_login.png" alt="SugarCRM" border="0"></a>
      </th>
    </tr>
   <tr>
      <td colspan="2"  id="ready_image"><IMG src="include/images/install_themes.jpg" width="698" height="247" alt="Sugar Themes" border="0"></td>
    </tr>
                <td>
			    {$mod_strings['LBL_WELCOME_CHOOSE_LANGUAGE']}: <select name="language" onchange='this.form.submit()';>{$langDropDown}</select>
                </td>
                <td>
			    &nbsp;
                </td>
    </tr>

    <tr>
			<td align="right" colspan="2">
        <hr>
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
                <td>
						    <input type="hidden" name="current_step" value="{$next_step}">
						    <input type="hidden" name="instance_url" value="">
						</td>
					    <td>
					        <input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" id="button_next2" onFocus="this.form.instance_url.value = window.location;" />
			            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
	</form>
    <script>
        function showtime(div){

            if(document.getElementById(div).style.display == ''){
                document.getElementById(div).style.display = 'none';
                document.getElementById('adv_'+div).style.display = '';
                document.getElementById('basic_'+div).style.display = 'none';
            }else{
                document.getElementById(div).style.display = '';
                document.getElementById('adv_'+div).style.display = 'none';
                document.getElementById('basic_'+div).style.display = '';
            }

        }
    </script>
</body>
</html>
EOQ;

echo $out;
?>
