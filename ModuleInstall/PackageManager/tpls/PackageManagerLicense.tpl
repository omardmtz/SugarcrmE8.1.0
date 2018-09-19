{*
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
*}
 <table>
 	<tr>
 		<td>{$MOD.LBL_MODULE_LICENSE}</td>
 	</tr>
 	<tr>
 		<td><textarea id='license' cols='75' rows='8'>{$LICENSE_CONTENTS}</textarea></td>
 	</tr>
 	<tr>
 		<td><input type='radio' id='radio_license_agreement_accept' name='radio_license_agreement' value='accept'>{$MOD.LBL_ACCEPT}&nbsp;<input type='radio' id='radio_license_agreement_reject' name='radio_license_agreement' value='reject'>{$MOD.LBL_DENY}</td>
 	</tr>
 	<tr>
 		<tr><td><input type='button' id='btnLicense' value='OK' onClick='PackageManager.processLicense("{$FILE}");' class='button'></td>
 	</tr>
</table>