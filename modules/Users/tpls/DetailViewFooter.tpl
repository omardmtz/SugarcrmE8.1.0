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
<!-- END METADATA SECTION -->
            <div id='email_options'>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
                    <tr>
                        <th align="left" scope="row" colspan="4">
                            <h4>{$MOD.LBL_MAIL_OPTIONS_TITLE}</h4>
                        </th>
                    </tr>
                    <tr>
                        <td align="top" scope="row" width="15%">
                            {$MOD.LBL_EMAIL|strip_semicolon}:
                        </td>
                        <td align="top" width="85%">
                            {$NEW_EMAIL}
                        </td>
                    </tr>
                    <tr id="email_options_link_type">
                        <td align="top"  scope="row">
                            {$MOD.LBL_EMAIL_LINK_TYPE|strip_semicolon}:
                        </td>
                        <td >
                            {$EMAIL_LINK_TYPE}
                        </td>
                    </tr>
                    {if !$HIDE_IF_CAN_USE_DEFAULT_OUTBOUND}
                    <tr>
                        <td scope="row" width="15%">
                            {$MOD.LBL_EMAIL_PROVIDER|strip_semicolon}:
                        </td>
                        <td width="35%">
                            {$mail_smtpdisplay}
                        </td>
                    </tr>
                    {if !empty($mail_smtpauth_req)}
                    <tr>
                        <td align="top"  scope="row">
                            {$MOD.LBL_MAIL_SMTPUSER|strip_semicolon}:
                        </td>
                        <td width="35%">
                            {$mail_smtpuser}
                        </td>
                    </tr>
                    {/if}
                    {/if}
                </table>
            </div>
        </div>
        <div>
        <div id="settings">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
                <tr>
                <th colspan='4' align="left" width="100%" valign="top"><h4><slot>{$MOD.LBL_USER_SETTINGS}</slot></h4></th>
                </tr>
                <tr>
                <td scope="row"><slot>{$MOD.LBL_RECEIVE_NOTIFICATIONS|strip_semicolon}:</slot></td>
                <td><slot><input class="checkbox" type="checkbox" disabled {$RECEIVE_NOTIFICATIONS}></slot></td>
                <td><slot>{$MOD.LBL_RECEIVE_NOTIFICATIONS_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                <td width="15%" scope="row"><slot>{$MOD.LBL_DEFAULT_TEAM|strip_semicolon}:</slot></td>
                <td><slot>{$DEFAULT_TEAM_LIST}&nbsp;</slot></td>
                <td><slot>{$MOD.LBL_DEFAULT_TEAM_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                <td scope="row" valign="top"><slot>{$MOD.LBL_REMINDER|strip_semicolon}:</td>
                <td valign="top" nowrap><slot>{include file="modules/Meetings/tpls/reminders.tpl"}</slot></td>
                <td ><slot>{$MOD.LBL_REMINDER_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                <td valign="top" scope="row"><slot>{$MOD.LBL_SETTINGS_URL|strip_semicolon}:</slot></td>
                <td valign="top" nowrap><slot>{$SETTINGS_URL}</slot></td>
                <td><slot>{$MOD.LBL_SETTINGS_URL_DESC}&nbsp;</slot></td>
                </tr>
                <tr>
                <td scope="row" valign="top"><slot>{$MOD.LBL_EXPORT_DELIMITER|strip_semicolon}:</slot></td>
                <td><slot>{$EXPORT_DELIMITER}</slot></td>
                <td><slot>{$MOD.LBL_EXPORT_DELIMITER_DESC}</slot></td>
                </tr>
                <tr>
                <td scope="row" valign="top"><slot>{$MOD.LBL_EXPORT_CHARSET|strip_semicolon}:</slot></td>
                <td><slot>{$EXPORT_CHARSET_DISPLAY}</slot></td>
                <td><slot>{$MOD.LBL_EXPORT_CHARSET_DESC}</slot></td>
                </tr>
                <tr>
                <td scope="row" valign="top"><slot>{$MOD.LBL_USE_REAL_NAMES|strip_semicolon}:</slot></td>
                <td><slot><input tabindex='3' name='use_real_names' disabled class="checkbox" type="checkbox" {$USE_REAL_NAMES}></slot></td>
                <td><slot>{$MOD.LBL_USE_REAL_NAMES_DESC}</slot></td>
                </tr>
                {if $DISPLAY_EXTERNAL_AUTH}
                <tr>
                  <td scope="row" valign="top"><slot>{$EXTERNAL_AUTH_CLASS|strip_semicolon}:</slot></td>
                  <td valign="top" nowrap><slot><input id="external_auth_only" name="external_auth_only" type="checkbox" class="checkbox" {$EXTERNAL_AUTH_ONLY_CHECKED}></slot></td>
                  <td><slot>{$MOD.LBL_EXTERNAL_AUTH_ONLY} {$EXTERNAL_AUTH_CLASS}</slot></td>
                </tr>
                {/if}
            </table>
        </div>

        <div id='locale'>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
                <tr>
                    <th colspan='4' align="left" width="100%" valign="top">
                        <h4><slot>{$MOD.LBL_USER_LOCALE}</slot></h4></th>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_DATE_FORMAT|strip_semicolon}:</slot></td>
                    <td><slot>{$DATEFORMAT}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_DATE_FORMAT_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_TIME_FORMAT|strip_semicolon}:</slot></td>
                    <td><slot>{$TIMEFORMAT}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_TIME_FORMAT_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_TIMEZONE|strip_semicolon}:</slot></td>
                    <td nowrap><slot>{$TIMEZONE}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_ZONE_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_CURRENCY|strip_semicolon}:</slot></td>
                    <td><slot>{$CURRENCY_DISPLAY}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_CURRENCY_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_CURRENCY_SHOW_PREFERRED|strip_semicolon}:</slot></td>
                    <td><slot>{if $currency_show_preferred}Yes{else}No{/if}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_CURRENCY_SHOW_PREFERRED_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_SYSTEM_SIG_DIGITS|strip_semicolon}:</slot></td>
                    <td><slot>{$CURRENCY_SIG_DIGITS}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_SYSTEM_SIG_DIGITS_DESC}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_NUMBER_GROUPING_SEP|strip_semicolon}:</slot></td>
                    <td><slot>{$NUM_GRP_SEP}&nbsp;</slot></td>
                    <td><slot>{$MOD.LBL_NUMBER_GROUPING_SEP_TEXT}&nbsp;</slot></td>
                </tr><tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_DECIMAL_SEP|strip_semicolon}:</slot></td>
                    <td><slot>{$DEC_SEP}&nbsp;</slot></td>
                    <td><slot></slot>{$MOD.LBL_DECIMAL_SEP_TEXT}&nbsp;</td>
                </tr>
                </tr><tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_LOCALE_DEFAULT_NAME_FORMAT|strip_semicolon}:</slot></td>
                    <td><slot>{$NAME_FORMAT}&nbsp;</slot></td>
                    <td><slot></slot>{$MOD.LBL_LOCALE_NAME_FORMAT_DESC}&nbsp;</td>
                </tr>
            </table>
        </div>

        {if $SHOW_PDF_OPTIONS}
        <div id='pdf'>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
                <tr>
                    <th colspan='4' align="left"  width="100%" valign="top">
                        <h4><slot>{$MOD.LBL_PDF_SETTINGS}</slot></h4></th>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_PDF_FONT_NAME_MAIN|strip_semicolon}:</slot></td>
                    <td width="35%"><slot>{$PDF_FONT_NAME_MAIN_DISPLAY}&nbsp;</slot></td>
                    <td colspan="2"><slot>{$MOD.LBL_PDF_FONT_NAME_MAIN_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_PDF_FONT_SIZE_MAIN|strip_semicolon}:</slot></td>
                    <td width="35%"><slot>{$PDF_FONT_SIZE_MAIN}&nbsp;</slot></td>
                    <td colspan="2"><slot>{$MOD.LBL_PDF_FONT_SIZE_MAIN_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%" scope="row"><slot>{$MOD.LBL_PDF_FONT_NAME_DATA|strip_semicolon}:</slot></td>
                    <td width="35%"><slot>{$PDF_FONT_NAME_DATA_DISPLAY}&nbsp;</slot></td>
                    <td colspan="2" class="tabDetailViewDF"><slot>{$MOD.LBL_PDF_FONT_NAME_DATA_TEXT}&nbsp;</slot></td>
                </tr>
                <tr>
                    <td width="15%"  scope="row"><slot>{$MOD.LBL_PDF_FONT_SIZE_DATA|strip_semicolon}:</slot></td>
                    <td width="35%" class="tabDetailViewDF"><slot>{$PDF_FONT_SIZE_DATA}&nbsp;</slot></td>
                    <td colspan="2" class="tabDetailViewDF"><slot>{$MOD.LBL_PDF_FONT_SIZE_DATA_TEXT}&nbsp;</slot></td>
                </tr>
            </table>
        </div>
        {/if}

        <div id='calendar_options'>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
            <tr>
            <th colspan='4' align="left" width="100%" valign="top"><h4><slot>{$MOD.LBL_CALENDAR_OPTIONS}</slot></h4></th>
            </tr>
            <tr>
            <td width="15%" scope="row"><slot>{$MOD.LBL_PUBLISH_KEY|strip_semicolon}:</slot></td>
            <td width="20%"><slot>{$CALENDAR_PUBLISH_KEY}&nbsp;</slot></td>
            <td width="65%"><slot>{$MOD.LBL_CHOOSE_A_KEY}&nbsp;</slot></td>
            </tr>
            <tr>
            <td width="15%" scope="row"><slot><nobr>{$MOD.LBL_YOUR_PUBLISH_URL|strip_semicolon}:</nobr></slot></td>
            <td colspan=2>{if $CALENDAR_PUBLISH_KEY}{$CALENDAR_PUBLISH_URL}{else}{$MOD.LBL_NO_KEY}{/if}</td>
            </tr>
            <tr>
            <td width="15%" scope="row"><slot>{$MOD.LBL_SEARCH_URL|strip_semicolon}:</slot></td>
            <td colspan=2><slot>{if $CALENDAR_PUBLISH_KEY}{$CALENDAR_SEARCH_URL}{else}{$MOD.LBL_NO_KEY}{/if}</slot></td>
            </tr>
            <tr>
            <td width="15%" scope="row"><slot>{$MOD.LBL_ICAL_PUB_URL|strip_semicolon}: {sugar_help text=$MOD.LBL_ICAL_PUB_URL_HELP}</slot></td>
            <td colspan=2><slot>{if $CALENDAR_PUBLISH_KEY}{$CALENDAR_ICAL_URL}{else}{$MOD.LBL_NO_KEY}{/if}</slot></td>
            </tr>
            <tr>
            <td width="15%" scope="row"><slot>{$MOD.LBL_FDOW|strip_semicolon}:</slot></td>
            <td><slot>{$FDOWDISPLAY}&nbsp;</slot></td>
            <td><slot></slot>{$MOD.LBL_FDOW_TEXT}&nbsp;</td>
            </tr>
            </table>
        </div>
    </div>
{if $SHOW_ROLES}
    {$ROLE_HTML}
{else}
</div>
{/if}

{if $refreshMetadata}
<script type="text/javascript">
// Make an API request to check for possible http 412 codes so metadata and user
// prefs can be updates in the client
var api = parent.SUGAR.App.api;
api.call('read', api.buildURL('ping'));
</script>
{/if}
