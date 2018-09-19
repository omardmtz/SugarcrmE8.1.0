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
<!-- END METADATA GENERATED CONTENT -->

{{if $useTabs}}
    <!-- include a closing div if the useTabs variable is set to true -->
    </div>
{{/if}}
            <div id="email_options">
            <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
                            <tr>
                                <th align="left" scope="row" colspan="4">
                                    <h4>{$MOD.LBL_MAIL_OPTIONS_TITLE}</h4>
                                </th>
                            </tr>
                            <tr>
                                <td scope="row" width="17%">
                                {$MOD.LBL_EMAIL}:  {if $REQUIRED_EMAIL_ADDRESS}<span class="required" id="mandatory_email">{$APP.LBL_REQUIRED_SYMBOL}</span> {/if}
                                </td>
                                <td>
                                    {$NEW_EMAIL}
                                </td>
                            </tr>
                            <tr id="email_options_link_type" style='display:{$HIDE_FOR_GROUP_AND_PORTAL}'>
                                <td scope="row" width="17%">
                                    {$MOD.LBL_EMAIL_LINK_TYPE}:&nbsp;{sugar_help text=$MOD.LBL_EMAIL_LINK_TYPE_HELP WIDTH=450}
                                </td>
                                <td>
                                    <select id="email_link_type" name="email_link_type" {if $DISABLE_SUGAR_CLIENT} data-sugarclientdisabled="true"{/if} tabindex='410'>
                                    {$EMAIL_LINK_TYPE}
                                    </select>
                                </td>
                            </tr>
                            {if !$HIDE_IF_CAN_USE_DEFAULT_OUTBOUND}
                            <tr id="mail_smtpserver_tr">
                                <td width="20%" scope="row"><span id="mail_smtpserver_label">{$MOD.LBL_EMAIL_PROVIDER}</span></td>
                                <td width="30%" ><slot>{$mail_smtpdisplay}<input id='mail_smtpserver' name='mail_smtpserver' type="hidden" value='{$mail_smtpserver}' /></slot></td>
                                <td>&nbsp;</td>
                                <td >&nbsp;</td>
                            </tr>
                             {if !empty($mail_smtpauth_req) }

                            <tr id="mail_smtpuser_tr">
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtpuser_label">{$MOD.LBL_MAIL_SMTPUSER}</span></td>
                                <td width="30%" ><slot><input type="text" id="mail_smtpuser" name="mail_smtpuser" size="25" maxlength="64" value="{$mail_smtpuser}" tabindex='1' ></slot></td>
                                <td>&nbsp;</td>
                                <td >&nbsp;</td>
                            </tr>
                            <tr id="mail_smtppass_tr">
                                <td width="20%" scope="row" nowrap="nowrap"><span id="mail_smtppass_label">{$MOD.LBL_MAIL_SMTPPASS}</span></td>
                                <td width="30%" ><slot>
                                <input type="password" id="mail_smtppass" name="mail_smtppass" size="25" maxlength="64" value="{$mail_smtppass}" tabindex='1'>
                                <a href="javascript:void(0)" id='mail_smtppass_link' onClick="SUGAR.util.setEmailPasswordEdit('mail_smtppass')" style="display: none">{$APP.LBL_CHANGE_PASSWORD}</a>
                                </slot></td>
                                <td>&nbsp;</td>
                                <td >&nbsp;</td>
                            </tr>
                            {/if}

                            <tr id="test_outbound_settings_tr">
                                <td width="17%" scope="row"><input type="button" class="button" value="{$APP.LBL_EMAIL_TEST_OUTBOUND_SETTINGS}" onclick="startOutBoundEmailSettingsTest();"></td>
                                <td width="33%" >&nbsp;</td>
                                <td width="17%">&nbsp;</td>
                                <td width="33%" >&nbsp;</td>
                            </tr>
                            {/if}
                        </table>
            </div>
</div>
<div>
            {if ($CHANGE_PWD) == '1'}
            <div id="generate_password">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
                <tr>
                    <td width='40%'>
                        <table width='100%' cellspacing='0' cellpadding='0' border='0' >
                            <tr>
                                <th align="left" scope="row" colspan="4">
                                    <h4>{$MOD.LBL_CHANGE_PASSWORD_TITLE}</h4><br>
                                    {$ERROR_PASSWORD}
                                </th>
                            </tr>
                        </table>
                        <!-- field to stop firefox autofill -->
                        <input style="display:none;" type="password" name="stopautofill" />
                            <!-- hide field if user is admin -->
                            <div id='generate_password_old_password' {if ($IS_ADMIN)} style='display:none' {/if}>
                                 <table width='100%' cellspacing='0' cellpadding='0' border='0' >
                                    <tr>
                                        <td width='35%' scope="row">
                                            {$MOD.LBL_OLD_PASSWORD}
                                        </td>
                                        <td >
                                            <input name='old_password' id='old_password' type='password' tabindex='2' {$DISABLED}
                                               onkeyup="password_confirmation();"
                                               onchange="password_confirmation();"
                                            >
                                        </td>
                                        <td width='40%'>
                                        </td>
                                    </tr>
                                 </table>
                            </div>
                        <table width='100%' cellspacing='0' cellpadding='0' border='0' >
                            <tr>
                                <td width='35%' scope="row" snowrap>
                                    {$MOD.LBL_NEW_PASSWORD}
                                    <span class="required" id="mandatory_pwd">{if ($REQUIRED_PASSWORD)}{$APP.LBL_REQUIRED_SYMBOL}{/if}</span>
                                </td>
                                <td class='dataField'>

                                    <input name='new_password' id= "new_password" type='password' tabindex='2' {$DISABLED}
                                       onkeyup="password_confirmation();newrules('{$PWDSETTINGS.minpwdlength}','{$PWDSETTINGS.maxpwdlength}','{$REGEX}');"
                                       onchange="password_confirmation();newrules('{$PWDSETTINGS.minpwdlength}','{$PWDSETTINGS.maxpwdlength}','{$REGEX}');"
                                    />
                                </td>
                                <td width='40%'>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row" width='35%'>
                                    {$MOD.LBL_CONFIRM_PASSWORD}
                                </td>
                                <td class='dataField'>
                                    <input name='confirm_new_password' id='confirm_pwd' style ='' type='password' tabindex='2' {$DISABLED} onkeyup="password_confirmation();"  >
                                </td>
                                <td width='40%'>
                                <div id="comfirm_pwd_match" class="error" style="display: none;">{$MOD.ERR_PASSWORD_MISMATCH}</div>
                                     {*<span id="ext-gen63" class="x-panel-header-text">
                                        Requirements
                                        <span id="Filter.1_help" onclick="return SUGAR.util.showHelpTips(this,help());">
                                            <img src="themes/default/images/help.gif"/>
                                        </span>
                                    </span>*}
                                </td>
                            </tr>
                            <tr>
                                <td class='dataLabel'></td>
                                <td class='dataField'></td>
                            </td>
                        </table>

                        <table width='17%' cellspacing='0' cellpadding='1' border='0'>
                            <tr>
                                <td width='50%'>
                                    <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey='{$APP.LBL_SAVE_BUTTON_KEY}' class='button' id='save_new_pwd_button' LANGUAGE=javascript onclick='if (set_password(this.form)) window.close(); else return false;' type='submit' name='button' style='display:none;' value='{$APP.LBL_SAVE_BUTTON_LABEL}'>
                                </td>
                                <td width='50%'>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width='60%' style="vertical-align:middle;">
                        {if !$IS_PORTALONLY}
                            {sugar_password_requirements_box width='300px' class='x-sqs-list' style='background-color:white; padding:5px !important;'}
                        {/if}
                    </td>
                </tr>
            </table>
            </div>
            {else}
            <div id="generate_password">
                <input name='old_password' id='old_password' type='hidden'>
                <input name='new_password' id= "new_password" type='hidden'>
                <input name='confirm_new_password' id='confirm_pwd' type='hidden'>
            </div>
            {/if}
    </div>
    {if $SHOW_THEMES}
    <div>
        <div id="themepicker" style="display:{$HIDE_FOR_GROUP_AND_PORTAL}">
        <table class="edit view" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td scope="row" colspan="4"><h4>{$MOD.LBL_THEME}</h4></td>
                </tr>
                <tr>
                    <td width="17%">
                        <select name="user_theme" tabindex='366' size="20" id="user_theme_picker" style="width: 100%">
                            {$THEMES}
                        </select>
                    </td>
                    <td width="33%">
                        <img id="themePreview" src="{sugar_getimagepath file='themePreview.png'}" border="1" />
                    </td>
                    <td width="17%">&nbsp;</td>
                    <td width="33%">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
    {/if}
    <div>
        <div id="settings" style="display:{$HIDE_FOR_GROUP_AND_PORTAL}">
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">

                        <tr>
                            <th width="100%" align="left" scope="row" colspan="4"><h4><slot>{$MOD.LBL_USER_SETTINGS}</slot></h4></th>
                        </tr>
                        <tr>
                            <td scope="row"  valign="top"><slot>{$MOD.LBL_EXPORT_DELIMITER}:</slot>&nbsp;{sugar_help text=$MOD.LBL_EXPORT_DELIMITER_DESC }</td>
                            <td ><slot><input type="text" tabindex='12' name="export_delimiter" value="{$EXPORT_DELIMITER}" size="5"></slot></td>
                            <td scope="row" width="17%">
                            <slot>{$MOD.LBL_RECEIVE_NOTIFICATIONS}:</slot>&nbsp;{sugar_help text=$MOD.LBL_RECEIVE_NOTIFICATIONS_TEXT}
                            </td>
                            <td width="33%">
                            <slot><input name='receive_notifications' class="checkbox" tabindex='12' type="checkbox" value="12" {$RECEIVE_NOTIFICATIONS}></slot>
                            </td>
                        </tr>

                        <tr>
                            <td scope="row" valign="top"><slot>{$MOD.LBL_EXPORT_CHARSET}:</slot>&nbsp;{sugar_help text=$MOD.LBL_EXPORT_CHARSET_DESC }</td>
                            <td ><slot><select tabindex='12' name="default_export_charset">{$EXPORT_CHARSET}</select></slot></td>
                            <td scope="row" valign="top">
                            <slot>{$MOD.LBL_REMINDER}:</slot>&nbsp;{sugar_help text=$MOD.LBL_REMINDER_TEXT }
                            </td>
                            <td valign="top"  nowrap>
                                <slot>{include file="modules/Meetings/tpls/reminders.tpl"}</slot>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" valign="top"><slot>{$MOD.LBL_USE_REAL_NAMES}:</slot>&nbsp;{sugar_help text=$MOD.LBL_USE_REAL_NAMES_DESC }</td>
                            <td ><slot><input tabindex='12' type="checkbox" name="use_real_names" {$USE_REAL_NAMES}></slot></td>
                            <td scope="row"></td>
                            <td></td>
                        </tr>
                        <tr>
                            {if !empty($SHOW_TEAM_SELECTION)}
                            <td width="20%" scope="row"><slot>{$MOD.LBL_DEFAULT_TEAM}:</slot>&nbsp;{sugar_help text=$MOD.LBL_DEFAULT_TEAM_TEXT }</td>
                            <td ><slot>{$DEFAULT_TEAM_OPTIONS}</slot></td>
                            {/if}
                            <td scope="row"></td>
                            <td></td>
                        </tr>
                        <!--{if !empty($EXTERNAL_AUTH_CLASS) && !empty($IS_ADMIN)}-->
                            <tr>
                                {capture name=SMARTY_LBL_EXTERNAL_AUTH_ONLY}&nbsp;{$MOD.LBL_EXTERNAL_AUTH_ONLY} {$EXTERNAL_AUTH_CLASS_1}{/capture}
                                <td scope="row" nowrap><slot>{$EXTERNAL_AUTH_CLASS} {$MOD.LBL_ONLY}:</slot>&nbsp;{sugar_help text=$smarty.capture.SMARTY_LBL_EXTERNAL_AUTH_ONLY}</td>
                                <td ><input type='hidden' value='0' name='external_auth_only'><input type='checkbox' value='1' name='external_auth_only' {$EXTERNAL_AUTH_ONLY_CHECKED}></td>
                                <td ></td>
                                <td ></td>
                            </tr>
                        <!--{/if}-->
                    </table>
        </div>
        <div id="layout">
        <table class="edit view" border="0" cellpadding="0" cellspacing="1" width="100%">
            <tbody>
                <tr>
                    <th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_LAYOUT_OPTIONS}</h4></th>
                </tr>
                            <tr>
                                <td colspan="4">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td scope="row" align="left" style="padding-bottom: 2em;">{$TAB_CHOOSER}</td>
                                            <td width="90%" valign="top"><BR>&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
        </div>
        <div id="locale" style="display:{$HIDE_FOR_GROUP_AND_PORTAL}">
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
                        <tr>
                            <th width="100%" align="left" scope="row" colspan="4">
                                <h4><slot>{$MOD.LBL_USER_LOCALE}</slot></h4></th>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_DATE_FORMAT}:</slot>&nbsp;{sugar_help text=$MOD.LBL_DATE_FORMAT_TEXT }</td>
                            <td width="33%"><slot><select tabindex='14' name='dateformat'>{$DATEOPTIONS}</select></slot></td>
                            <!-- END: prompttz -->
                            <!-- BEGIN: currency -->
                            <td width="17%" scope="row"><slot>{$MOD.LBL_CURRENCY}:</slot>&nbsp;{sugar_help text=$MOD.LBL_CURRENCY_TEXT }</td>
                                <td ><slot>
                                    <select tabindex='14' id='currency_select' name='currency' onchange='setSymbolValue(this.options[this.selectedIndex].value);setSigDigits();'>{$CURRENCY}</select>
                                    <input type="hidden" id="symbol" value="">
                                </slot></td>
                            <!-- END: currency -->
                        </tr>
                        <tr>
                            <td scope="row"><slot></td>
                            <td scope="row"><slot></td>
                            <!-- BEGIN: show preferred currency -->
                            <td width="17%" scope="row"><slot>{$MOD.LBL_CURRENCY_SHOW_PREFERRED}:</slot>&nbsp;{sugar_help text=$MOD.LBL_CURRENCY_SHOW_PREFERRED_TEXT }</td>
                            <td ><slot>
                                    <input id="currency_show_preferred" type="checkbox" name="currency_show_preferred" value="YES" {if $currency_show_preferred}checked="checked"{/if}>
                                </slot></td>
                            <!-- END: show preferred currency -->
                        </tr>
                        <tr>
                            <td scope="row"><slot>{$MOD.LBL_TIME_FORMAT}:</slot>&nbsp;{sugar_help text=$MOD.LBL_TIME_FORMAT_TEXT }</td>
                            <td ><slot><select tabindex='14' name='timeformat'>{$TIMEOPTIONS}</select></slot></td>
                            <!-- BEGIN: currency -->
                            <td width="17%" scope="row"><slot>
                                {$MOD.LBL_SYSTEM_SIG_DIGITS}:
                            </slot></td>
                            <td ><slot>
                                <select id='sigDigits' onchange='setSigDigits(this.value);' name='default_currency_significant_digits'>{$sigDigits}</select>
                            </slot></td>
                            <!-- END: currency -->
                        </tr>
                        <tr>
                            <td scope="row"><slot>{$MOD.LBL_TIMEZONE}:</slot>&nbsp;{sugar_help text=$MOD.LBL_TIMEZONE_TEXT }</td>
                            <td ><slot><select tabindex='14' name='timezone'>{html_options options=$TIMEZONEOPTIONS selected=$TIMEZONE_CURRENT}</select></slot></td>
                            <!-- BEGIN: currency -->
                            <td width="17%" scope="row"><slot>
                                <i>{$MOD.LBL_LOCALE_EXAMPLE_NAME_FORMAT}</i>:
                            </slot></td>
                            <td ><slot>
                                <input type="text" disabled id="sigDigitsExample" name="sigDigitsExample">
                            </slot></td>
                            <!-- END: currency -->
                        </tr>
                        <tr>
                        {if ($IS_ADMIN)}
                            <td scope="row"><slot>{$MOD.LBL_PROMPT_TIMEZONE}:</slot>&nbsp;{sugar_help text=$MOD.LBL_PROMPT_TIMEZONE_TEXT }</td>
                            <td ><slot><input type="checkbox" tabindex='14'class="checkbox" name="ut" value="0" {$PROMPTTZ}></slot></td>
                        {else}
                            <td scope="row"><slot></td>
                            <td ><slot></slot></td>
                        {/if}
                            <td width="17%" scope="row"><slot>{$MOD.LBL_NUMBER_GROUPING_SEP}:</slot>&nbsp;{sugar_help text=$MOD.LBL_NUMBER_GROUPING_SEP_TEXT }</td>
                            <td ><slot>
                                <input tabindex='14' name='num_grp_sep' id='default_number_grouping_seperator'
                                    type='text' maxlength='1' size='1' value='{$NUM_GRP_SEP}'
                                    onkeydown='this.value=this.value.replace(/[\w]+/g, "");setSigDigits();'
                                    onkeyup='this.value=this.value.replace(/[\w]+/g, "");setSigDigits();'>
                            </slot></td></tr>
                        {capture name=SMARTY_LOCALE_NAME_FORMAT_DESC}&nbsp;{$MOD.LBL_LOCALE_NAME_FORMAT_DESC}{/capture}
                        <tr>
                            <td  scope="row" valign="top">{$MOD.LBL_LOCALE_DEFAULT_NAME_FORMAT}:&nbsp;{sugar_help text=$smarty.capture.SMARTY_LOCALE_NAME_FORMAT_DESC }</td>
                            <td  valign="top"><slot><select tabindex='14' id="default_locale_name_format" name="default_locale_name_format" selected="{$default_locale_name_format}">{$NAMEOPTIONS}</select></slot></td>
                             <td width="17%" scope="row"><slot>{$MOD.LBL_DECIMAL_SEP}:</slot>&nbsp;{sugar_help text=$MOD.LBL_DECIMAL_SEP_TEXT }</td>
                            <td ><slot>
                                <input tabindex='14' name='dec_sep' id='default_decimal_seperator'
                                    type='text' maxlength='1' size='1' value='{$DEC_SEP}'
                                    onkeydown='this.value=this.value.replace(/[\w]+/g, "");setSigDigits();'
                                    onkeyup='this.value=this.value.replace(/[\w]+/g, "");setSigDigits();'>
                            </slot></td>
                        </tr>
                    </table>
        </div>

        <div id="pdf_settings" style="display:{$HIDE_FOR_GROUP_AND_PORTAL}">
        {if $SHOW_PDF_OPTIONS}
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
                        <tr>
                            <th width="100%" align="left"  colspan="4">
                                <h4 ><slot>{$MOD.LBL_PDF_SETTINGS}</slot></h4></th>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_PDF_FONT_NAME_MAIN}:</slot>&nbsp;{sugar_help text=$MOD.LBL_PDF_FONT_NAME_MAIN_TEXT}</td>
                            <td width="33%"><slot><select name='sugarpdf_pdf_font_name_main' tabindex='16'>{$PDF_FONT_NAME_MAIN}</select></slot></td>
                            <td colspan="2"><slot>&nbsp;</slot></td>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_PDF_FONT_SIZE_MAIN}:</slot></td>
                            <td width="33%"><slot><input type="text" name="sugarpdf_pdf_font_size_main" value="{$PDF_FONT_SIZE_MAIN}" size="5" maxlength="5" tabindex='16'/></slot></td>
                            <td colspan="2"><slot>{$MOD.LBL_PDF_FONT_SIZE_MAIN_TEXT}&nbsp;</slot></td>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_PDF_FONT_NAME_DATA}:</slot>&nbsp;{sugar_help text=$MOD.LBL_PDF_FONT_NAME_DATA_TEXT}</td>
                            <td width="33%"><slot><select name='sugarpdf_pdf_font_name_data' tabindex='16'>{$PDF_FONT_NAME_DATA}</select></slot></td>
                            <td colspan="2"><slot>&nbsp;</slot></td>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_PDF_FONT_SIZE_DATA}:</slot></td>
                            <td width="33%"><slot><input type="text" name="sugarpdf_pdf_font_size_data" value="{$PDF_FONT_SIZE_DATA}" size="5" maxlength="5" tabindex='16'/></slot></td>
                            <td colspan="2"><slot>{$MOD.LBL_PDF_FONT_SIZE_DATA_TEXT}&nbsp;</slot></td>
                        </tr>
                    </table>
        {/if}
        </div>
        <div id="calendar_options" style="display:{$HIDE_FOR_GROUP_AND_PORTAL}">
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
            <tr>
                <th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_CALENDAR_OPTIONS}</h4></th>
            </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_PUBLISH_KEY}:</slot>&nbsp;{sugar_help text=$MOD.LBL_CHOOSE_A_KEY}</td>
                            <td width="20%" ><slot><input id='calendar_publish_key' name='calendar_publish_key' tabindex='17' size='25' maxlength='25' type="text" value="{$CALENDAR_PUBLISH_KEY}"></slot></td>
                            <td width="63%" ><slot>&nbsp;</slot></td>
                        </tr>
                        <tr>
                            <td width="15%" scope="row"><slot><nobr>{$MOD.LBL_YOUR_PUBLISH_URL|strip_semicolon}:</nobr></slot></td>
                            <td colspan=2><span class="calendar_publish_ok">{$CALENDAR_PUBLISH_URL}</span><span class="calendar_publish_none" style="display: none">{$MOD.LBL_NO_KEY}</span></td>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_SEARCH_URL|strip_semicolon}:</slot></td>
                            <td colspan=2><span class="calendar_publish_ok">{$CALENDAR_SEARCH_URL}</span><span class="calendar_publish_none" style="display: none">{$MOD.LBL_NO_KEY}</span></td>
                        </tr>
                        <tr>
                            <td width="15%" scope="row"><slot>{$MOD.LBL_ICAL_PUB_URL|strip_semicolon}: {sugar_help text=$MOD.LBL_ICAL_PUB_URL_HELP}</slot></td>
                            <td colspan=2><span class="calendar_publish_ok">{$CALENDAR_ICAL_URL}</span><span class="calendar_publish_none" style="display: none">{$MOD.LBL_NO_KEY}</span></td>
                        </tr>
                        <tr>
                            <td width="17%" scope="row"><slot>{$MOD.LBL_FDOW}:</slot>&nbsp;{sugar_help text=$MOD.LBL_FDOW_TEXT}</td>
                            <td ><slot>
                                <select tabindex='14' name='fdow'>{html_options options=$FDOWOPTIONS selected=$FDOWCURRENT}</select>
                            </slot></td>
                        </tr>
                    </table>
        </div>
    </div>
    {if $ID}
    <div id="eapm_area" style='display:{$HIDE_FOR_GROUP_AND_PORTAL};'>
        <div style="text-align:center; width: 100%">{sugar_image name="loading"}</div>
    </div>
    {/if}
</div>

<script type="text/javascript">

var mail_smtpport = '{$MAIL_SMTPPORT}';
var mail_smtpssl = '{$MAIL_SMTPSSL}';
{literal}
EmailMan = {};

function Admin_check(){
	if (('{/literal}{$IS_FOCUS_ADMIN}{literal}') && document.getElementById('is_admin').value=='0'){
		r=confirm('{/literal}{$MOD.LBL_CONFIRM_REGULAR_USER}{literal}');
		return r;
		}
	else
		return true;
}


$(document).ready(function() {
	var checkKey = function(key) {
		if(key != '') {
			$(".calendar_publish_ok").css('display', 'inline');
			$(".calendar_publish_none").css('display', 'none');
	        $('#cal_pub_key_span').text( key );
	        $('#ical_pub_key_span').text( key );
	        $('#search_pub_key_span').text( key );
		} else {
			$(".calendar_publish_ok").css('display', 'none');
			$(".calendar_publish_none").css('display', 'inline');
		}
	};
    $('#calendar_publish_key').keyup(function(){
    	checkKey($(this).val());
    });
    $('#calendar_publish_key').change(function(){
    	checkKey($(this).val());
    });
    checkKey($('#calendar_publish_key').val());

    {/literal}
    {if $mail_haspass}
    {literal}
    if(window.addEventListener){
        window.addEventListener("load", function() { SUGAR.util.setEmailPasswordDisplay('mail_smtppass', {/literal}{$mail_haspass}{literal}); }, false);
    }else{
        window.attachEvent("onload", function() { SUGAR.util.setEmailPasswordDisplay('mail_smtppass', {/literal}{$mail_haspass}{literal}); });
    }
    {/literal}
    {/if}
    {literal}
});
{/literal}
</script>
{$JAVASCRIPT}
{literal}
<script type="text/javascript" language="Javascript">
{/literal}
{$getNameJs}
{$getNumberJs}
currencies = {$currencySymbolJSON};
{{if $themeGroupListJSON}}
themeGroupList = {$themeGroupListJSON};
{{/if}}

onUserEditView();

</script>

</form>

<div id="testOutboundDialog" class="yui-hidden">
    <div id="testOutbound">
        <form>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
			<tr>
				<td scope="row">
					{$APP.LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR}
					<span class="required">
						{$APP.LBL_REQUIRED_SYMBOL}
					</span>
				</td>
				<td >
					<input type="text" id="outboundtest_from_address" name="outboundtest_from_address" size="35" maxlength="64" value="{$TEST_EMAIL_ADDRESS}">
				</td>
			</tr>
			<tr>
				<td scope="row" colspan="2">
					<input type="button" class="button" value="   {$APP.LBL_EMAIL_SEND}   " onclick="javascript:sendTestEmail();">&nbsp;
					<input type="button" class="button" value="   {$APP.LBL_CANCEL_BUTTON_LABEL}   " onclick="javascript:EmailMan.testOutboundDialog.hide();">&nbsp;
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
{literal}
<style>
    .actionsContainer.footer td {
        height:120px;
        vertical-align: top;
    }
</style>
{/literal}
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer footer">
    <tr>
        <td>
        {sugar_action_menu id="userEditActions" class="clickMenu fancymenu" buttons=$ACTION_BUTTON_FOOTER flat=true}
        </td>
        <td align="right" nowrap>
            <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
        </td>
    </tr>
</table>
