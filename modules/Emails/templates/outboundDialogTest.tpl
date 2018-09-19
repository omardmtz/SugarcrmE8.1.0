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
<div id="testOutbound">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
			<tr>
				<td scope="row">
					{$app_strings.LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR} 
					<span class="required">
						{$app_strings.LBL_REQUIRED_SYMBOL}
					</span>
				</td>
				<td >
					<input type="text" id="outboundtest_from_address" name="outboundtest_from_address" size="35" maxlength="64" value="{$CURRENT_USER_EMAIL}">
				</td>
			</tr>
			<tr>
				<td scope="row" colspan="2">
					<input type="button" class="button" value="   {$app_strings.LBL_EMAIL_SEND}   " onclick="javascript:SUGAR.email2.accounts.testOutboundSettings();">&nbsp;
					<input type="button" class="button" value="   {$app_strings.LBL_CANCEL_BUTTON_LABEL}   " onclick="javascript:SUGAR.email2.accounts.testOutboundDialog.hide();">&nbsp;
				</td>
			</tr>

		</table>
</div>
