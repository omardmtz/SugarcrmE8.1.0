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
	<form name="editContactForm" id="editContactForm">
{sugar_csrf_form_token}
		<input type="hidden" id="contact_id" name="contact_id" value="{$contact.id}">
	<table>
		<tr>
			<td colspan="4">
				<input type="button" class="button" id="contact_save" 
					value="   {$app_strings.LBL_SAVE_BUTTON_LABEL}   "
					onclick="javascript:SUGAR.email2.addressBook.saveContact();"
				>&nbsp;
				<input type="button" class="button" id="contact_full_form" 
					value="   {$app_strings.LBL_FULL_FORM_BUTTON_LABEL}   "
					onclick="javascript:SUGAR.email2.addressBook.fullForm('{$contact.id}', '{$contact.module}');"
				>&nbsp;
				<input type="button" class="button" id="contact_cancel" 
					value="   {$app_strings.LBL_CANCEL_BUTTON_LABEL}   "
					onclick="javascript:SUGAR.email2.addressBook.cancelEdit();"
				>
				<br>&nbsp;
			</td>
		</tr>
		<tr>
			<td scope="row">
				<b>{$contact_strings.LBL_FIRST_NAME}</b>
			</td>
			<td >
				<input class="input" name="contact_first_name" id="contact_first_name" value="{$contact.first_name}">
			</td>
			<td scope="row">
				<b>{$contact_strings.LBL_LAST_NAME}</b> <span class="error">*</span>
			</td>
			<td >
				<input class="input" name="contact_last_name" id="contact_last_name" value="{$contact.last_name}">
			</td>
		</tr>
		<tr>
			<td scope="row" colspan="4">
				<b>{$contact_strings.LBL_EMAIL_ADDRESSES}</b>
			</td>
		</tr>
		<tr>
			<td  colspan="4">
				{$emailWidget}
			</td>
		</tr>
		<tr>
			<td scope="row" colspan="4">
				<i>{$app_strings.LBL_EMAIL_EDIT_CONTACT_WARN}</i>
			</td>
		</tr>
	</table>
	</form>
</div>