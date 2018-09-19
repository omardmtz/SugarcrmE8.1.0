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
<div class="ydlg-bd">
	<form name="editMailingListForm" id="editMailingListForm">
{sugar_csrf_form_token}
		<input type="hidden" id="mailing_list_id" name="mailing_list_id" value="{$mailing_list_id}">
	<table>
		<tr>
			<td colspan="2">
				<input type="button" class="button" id="ml_save" 
					value="   {$app_strings.LBL_SAVE_BUTTON_LABEL}   "
					onclick="javascript:SUGAR.email2.addressBook.editMailingListSave();"
				>&nbsp;
				<input type="button" class="button" id="ml_save" 
					value="   {$app_strings.LBL_EMAIL_REVERT}   "
					onclick="javascript:SUGAR.email2.addressBook.editMailingListRevert();"
				>&nbsp;
				<input type="button" class="button" id="ml_cancel" 
					value="   {$app_strings.LBL_CANCEL_BUTTON_LABEL}   "
					onclick="javascript:SUGAR.email2.addressBook.cancelEdit();"
				>
				<br>&nbsp;
			</td>
		</tr>
		<tr>
			<td scope="row">
				<b>{$app_strings.LBL_EMAIL_ML_NAME}</b>
			</td>
			<td >
				<input class="input" name="mailing_list_name" id="mailing_list_name" value="{$mailing_list_name}">
			</td>
		</tr>
		<tr>
			<td scope="row" align="top" height="200">
				<b>{$app_strings.LBL_EMAIL_ML_ADDRESSES_1}</b>
				<br />&nbsp;<br />
				<div id="ml_used" style="overflow:auto; height:90%; margin:5px; padding:2px; border:1px solid #ccc;"></div>
			</td>
			<td scope="row" align="top" height="200">
				<b>{$app_strings.LBL_EMAIL_ML_ADDRESSES_2}</b>
				<br />&nbsp;<br />
				<div id="ml_available" style="overflow:auto; height:90%; margin:5px; padding:2px; border:1px solid #ccc;"></div>
			</td>
		</tr>
	</table>
	</form>
</div>