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
<div id="outboundServers">
	<form id="outboundEmailForm">
{sugar_csrf_form_token}
		<input type="hidden" id="mail_id" name="mail_id">
		<input type="hidden" id="type" name="type" value="user">
		<input type="hidden" id="mail_sendtype" name="mail_sendtype" value="SMTP">

		<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
		    <tr>
				<td scope="row" width="15%" NOWRAP>
					{$app_strings.LBL_EMAIL_ACCOUNTS_NAME}:
					<span class="required">
						{$app_strings.LBL_REQUIRED_SYMBOL}
					</span>
				</td>
				<td  width="35%">
					<input type="text" class="input" id="mail_name" name="mail_name" size="25" maxlength="64">
				</td>
			</tr>
			<tr id="chooseEmailProviderTD">
                <td align="left" scope="row" colspan="4">{sugar_translate module='Emails' label='LBL_CHOOSE_EMAIL_PROVIDER'}</td>
            </tr>
            <tr id="smtpButtonGroupTD">
                <td colspan="4">
                    <div id="smtpButtonGroup" class="yui-buttongroup">
                        <span id="gmail" class="yui-button yui-radio-button">
                            <span class="first-child">
                                <button type="button" name="mail_smtptype" value="gmail">
                                    &nbsp;&nbsp;&nbsp;&nbsp;{$app_strings.LBL_SMTPTYPE_GMAIL}&nbsp;&nbsp;&nbsp;&nbsp;
                                </button>
                            </span>
                        </span>
                        <span id="yahoomail" class="yui-button yui-radio-button">
                            <span class="first-child">
                                <button type="button" name="mail_smtptype" value="yahoomail">
                                    &nbsp;&nbsp;&nbsp;&nbsp;{$app_strings.LBL_SMTPTYPE_YAHOO}&nbsp;&nbsp;&nbsp;&nbsp;
                                </button>
                            </span>
                        </span>
                        <span id="exchange" class="yui-button yui-radio-button">
                            <span class="first-child">
                                <button type="button" name="mail_smtptype" value="exchange">
                                    &nbsp;&nbsp;&nbsp;&nbsp;{$app_strings.LBL_SMTPTYPE_EXCHANGE}&nbsp;&nbsp;&nbsp;&nbsp;
                                </button>
                            </span>
                        </span>
                        <span id="other" class="yui-button yui-radio-button yui-button-checked">
                            <span class="first-child">
                                <button type="button" name="mail_smtptype" value="other">
                                    &nbsp;&nbsp;&nbsp;&nbsp;{$app_strings.LBL_SMTPTYPE_OTHER}&nbsp;&nbsp;&nbsp;&nbsp;
                                </button>
                            </span>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="smtp_settings">
                        <table width="100%" cellpadding="0" cellspacing="1">
                            <tr id="mailsettings1">
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtpserver_label">{sugar_translate module='Emails' label='LBL_MAIL_SMTPSERVER'}</span> <span class="required" id="required_mail_smtpserver">{$app_strings.LBL_REQUIRED_SYMBOL}</span></td>
                                <td width="30%" ><slot><input type="text" id="mail_smtpserver" name="mail_smtpserver" tabindex="1" size="25" maxlength="64"></slot></td>
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtpport_label">{sugar_translate module='Emails' label='LBL_MAIL_SMTPPORT'}</span></td>
                                <td width="30%" ><input type="text" id="mail_smtpport" name="mail_smtpport" tabindex="1" size="5" maxlength="5"></td>
                            </tr>
                            <tr id="mailsettings2">
                                <td width="20%" scope="row"><span id='mail_smtpauth_req_label'>{sugar_translate module='Emails' label='LBL_MAIL_SMTPAUTH_REQ'}</span></td>
                                <td width="30%">
                                    <input id='mail_smtpauth_req' name='mail_smtpauth_req' type="checkbox" class="checkbox" value="1" tabindex='1'
                                        onclick="javascript:SUGAR.email2.accounts.smtp_authenticate_field_display();">
                                </td>
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtpssl_label">{$app_strings.LBL_EMAIL_SMTP_SSL_OR_TLS}</span></td>
                                <td width="30%">
                                <select id="mail_smtpssl" name="mail_smtpssl" tabindex="501" 
                                    onclick="javascript:SUGAR.email2.accounts.smtp_setDefaultSMTPPort();">{$MAIL_SSL_OPTIONS}</select>
                                </td>
                            </tr>
                            <tr id="smtp_auth1">
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtpuser_label">{sugar_translate module='Emails' label='LBL_MAIL_SMTPUSER'}</span> <span class="required">{$app_strings.LBL_REQUIRED_SYMBOL}</span></td>
                                <td width="30%" ><slot><input type="text" id="mail_smtpuser" name="mail_smtpuser" size="25" maxlength="64" tabindex='1' ></slot></td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                            </tr>
                            <tr id="smtp_auth2">
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtppass_label">{sugar_translate module='Emails' label='LBL_MAIL_SMTPPASS'}</span> <span class="required">{$app_strings.LBL_REQUIRED_SYMBOL}</span></td>
                                <td width="30%" ><slot>
                                <input type="password" id="mail_smtppass" name="mail_smtppass" size="25" maxlength="64" abindex='1'>
                                <a href="javascript:void(0)" id='mail_smtppass_link' onClick="SUGAR.util.setEmailPasswordEdit('mail_smtppass')" style="display: none">{$app_strings.LBL_CHANGE_PASSWORD}</a>
                                </slot></td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                            </tr>
                        </table>
                     </div>
                </td>
            </tr>
			<tr>
				<td colspan="2">
				    <input type="button" class="button" value="   {$app_strings.LBL_EMAIL_DONE_BUTTON_LABEL}   " onclick="javascript:SUGAR.email2.accounts.saveOutboundSettings();">&nbsp;
				    <input type="button" class="button" value="   {$app_strings.LBL_EMAIL_TEST_OUTBOUND_SETTINGS}   " onclick="javascript:SUGAR.email2.accounts.testOutboundSettingsDialog();">&nbsp;
				</td>
			</tr>
		</table>
	</form>
</div>
