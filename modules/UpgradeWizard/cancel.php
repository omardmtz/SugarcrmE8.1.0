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
/*********************************************************************************

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
logThis('[At cancel.php]');
logThis('cleaning up files and session.  goodbye.');


//Check the current step.

if(isset($_SESSION['install_file']) && file_exists(isset($_SESSION['install_file']))){
	@unlink(isset($_SESSION['install_file']));
}
unlinkUWTempFiles();
resetUwSession();

$uwMain =<<<eoq
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td align="left">
			<p>
			{$mod_strings['LBL_UW_CANCEL_DESC']}
			</p>
		</td>
	</tr>
	<tr>
		<th align="left">
			<input	title		= "{$mod_strings['LBL_BUTTON_RESTART']}"
					class		= "button"
					onclick		= "window.location.href ='{$sugar_config['site_url']}/index.php?module=UpgradeWizard&action=index';"
					type		= "submit"
					value		= "  {$mod_strings['LBL_BUTTON_RESTART']}  "
					id			= "restart_button" >
		</th>
	</tr>
</table>
eoq;


$showBack		= false;
$showCancel		= false;
$showRecheck	= false;
$showNext		= false;
$showExit       = true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

?>
