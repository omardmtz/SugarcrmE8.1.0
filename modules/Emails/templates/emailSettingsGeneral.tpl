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
<form name="formEmailSettingsGeneral" id="formEmailSettingsGeneral">
{sugar_csrf_form_token}
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="view">
	<tr>
		<th colspan="4" align="left" colspan="4" scope="row">
			<h4>{$app_strings.LBL_EMAIL_SETTINGS_TITLE_PREFERENCES}</h4>
		</th>
	</tr>
	<tr>
		<td scope="row" width="20%">
			{$app_strings.LBL_EMAIL_SETTINGS_CHECK_INTERVAL}:
		</td>
		<td width="25%">
			{html_options options=$emailCheckInterval.options selected=$emailCheckInterval.selected name='emailCheckInterval' id='emailCheckInterval'}
		</td>
		<td scope="row" width="20%">
			<a href="javascript:parent.SUGAR.App.router.navigate('#UserSignatures', {$smarty.ldelim}trigger: true{$smarty.rdelim})">{$mod_strings.LNK_EMAIL_SIGNATURE_LIST}</a>
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td  scope="row">
			{$app_strings.LBL_EMAIL_SETTINGS_SEND_EMAIL_AS}:
		</td>
		<td >
			<input class="checkbox" type="checkbox" id="sendPlainText" name="sendPlainText" value="1" {$sendPlainTextChecked} />
		</td>
		<td NOWRAP scope="row"></td>
		<td NOWRAP></td>
	</tr>
	<tr>
		<td NOWRAP scope="row">
        	{$app_strings.LBL_EMAIL_CHARSET}:
        </td>
		<td NOWRAP>
        	{html_options options=$charset.options selected=$charset.selected name='default_charset' id='default_charset'}
        </td>
		<td NOWRAP scope="row">
            &nbsp;
        </td>
		<td NOWRAP>
        	&nbsp;
        </td>
	</tr>
</table>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="view">
	<tr>
		<th colspan="4">
			<h4>{$app_strings.LBL_EMAIL_SETTINGS_TITLE_LAYOUT}</h4>
		</th>
	</tr>
	<tr>
		<td NOWRAP scope="row" width="20%">
			{$app_strings.LBL_EMAIL_SETTINGS_SHOW_NUM_IN_LIST}:
			<div id="rollover">
                            <a href="#" class="rollover">{sugar_getimage alt=$mod_strings.LBL_HELP name="helpInline" ext=".gif" other_attributes='border="0" '}<span>{$app_strings.LBL_EMAIL_SETTINGS_REQUIRE_REFRESH}</span></a>
            </div>
		</td>
		<td NOWRAP >
			<select name="showNumInList" id="showNumInList">
			{$showNumInList}
			</select>
		</td>
		<td NOWRAP scope="row" width="20%">&nbsp;</td>
		<td NOWRAP >&nbsp;</td>
	</tr>
</table>

{include file="modules/Emails/templates/emailSettingsFolders.tpl"}


</form>

