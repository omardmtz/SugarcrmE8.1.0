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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$langHeader}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <title>{$MOD.LBL_WIZARD_TITLE} {$MOD.LBL_CONFIRM_TITLE}</title>
    <link REL="SHORTCUT ICON" HREF="{$icon}">
    <link rel="stylesheet" href="{$css}" type="text/css" />
</head>
<body onload="javascript:document.getElementById('button_next2').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
    <input type="hidden" name="current_step" value="{$next_step}">
    <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
        <tr>
            <td colspan="2" id="help"><a href="{$help_url}" target='_blank'>{$MOD.LBL_HELP} </a></td>
        </tr>
        <tr>
            <th width="500">
                <p>
                    <img src="{$sugar_md}" alt="SugarCRM" border="0">
                </p>
                {$MOD.LBL_CONFIRM_TITLE}</th>
            <th width="200" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank"><IMG
                            src="{$loginImage}" alt="SugarCRM" border="0"></a>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
                    <tr>
                        <th colspan="3" align="left">{$MOD.LBL_DBCONF_TITLE}</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CONFIRM_DB_TYPE}</strong></td>
                        <td>{$smarty.session.setup_db_type}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_HOST_NAME}</strong></td>
                        <td>{$smarty.session.setup_db_host_name}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_DB_NAME}</strong></td>
                        <td>
                            {$smarty.session.setup_db_database_name} {$dbCreate}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_DB_ADMIN_USER}</strong></td>
                        <td>{$smarty.session.setup_db_admin_user_name}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_DEMO_DATA}</strong></td>
                        <td>{$demoData}</td>
                    </tr>
                    {if $yesNoDropCreate}
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_DBCONF_DB_DROP}</strong></td>
                            <td>{$yesNoDropCreate}</td>
                        </tr>
                    {/if}
                    {if $db->supports('fulltext')}
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_FTS}</strong></td>
                            <td>
                                {if $db->full_text_indexing_installed()}
                                    {$MOD.LBL_FTS_INSTALLED}
                                {else}
                                    <span class="stop">
                                        <strong>{$MOD.LBL_FTS_INSTALLED_ERR1}</strong>
                                        <br>
                                        {$MOD.LBL_FTS_INSTALLED_ERR2}
                                    </span>
                                {/if}
                            </td>
                        </tr>
                    {/if}
                    {if $smarty.session.install_type && $smarty.session.install_type eq 'custom'}
                        <tr>
                            <td colspan="3" align="left"></td>
                        </tr>
                        <tr>
                            <th colspan="3" align="left">{$MOD.LBL_SITECFG_TITLE}</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_SITECFG_URL}</strong></td>
                            <td>{$smarty.session.setup_site_url}</td>
                        </tr>
                        <tr>
                        <tr>
                            <td colspan="3" align="left"></td>
                        </tr>
                        <th colspan="3" align="left">{$MOD.LBL_SITECFG_SUGAR_UPDATES}</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_SITECFG_SUGAR_UP}</strong></td>
                            <td>{$yesNoSugarUpdates}</td>
                        </tr>
                        <tr>
                        <tr>
                            <td colspan="3" align="left"></td>
                        </tr>
                        <th colspan="3" align="left">{$MOD.LBL_SITECFG_SITE_SECURITY}</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_SITECFG_CUSTOM_SESSION}?</strong></td>
                            <td>{$yesNoCustomSession}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_SITECFG_CUSTOM_LOG}?</strong></td>
                            <td>{$yesNoCustomLog}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>{$MOD.LBL_SITECFG_CUSTOM_ID}?</strong></td>
                            <td>{$yesNoCustomId}</td>
                        </tr>
                    {/if}
                    <tr>
                        <td colspan="3" align="left"></td>
                    </tr>
                    <tr>
                        <th colspan="3" align="left">{$MOD.LBL_SYSTEM_CREDS}</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_DB_USER}</strong></td>
                        <td>
                            {$smarty.session.setup_db_sugarsales_user}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_DBCONF_DB_PASSWORD}</strong></td>
                        <td>
                            <span id="hide_db_admin_pass">{$MOD.LBL_HIDDEN}</span>
                            <span style="display:none"
                                  id="show_db_admin_pass">{$smarty.session.setup_db_sugarsales_password}</span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_SITECFG_ADMIN_Name}</strong></td>
                        <td>Admin</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_SITECFG_ADMIN_PASS}</strong></td>
                        <td>
                            <span id="hide_site_admin_pass">{$MOD.LBL_HIDDEN}</span>
                            <span style="display:none"
                                  id="show_site_admin_pass">{$smarty.session.setup_site_admin_password}</span>
                        </td>
                    </tr>
                    <tr><td colspan="3" align="left"></td></tr>
                    <tr><th colspan="3" align="left">{$MOD.LBL_SYSTEM_ENV}</th></tr>
                    {* PHP VERSION *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_PHPVER}</strong></td>
                        <td>{'PHP_VERSION'|constant}</td>
                    </tr>
                    {* Begin List of already known good variables. These were checked during the initial sys check *}
                    {* XML Parsing *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_XML}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* mbstrings *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_MBSTRING}</strong></td>
                        <td>{$mbStatus}</td>
                    </tr>
                    {* config.php *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_CONFIG}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* custom dir *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_CUSTOM}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* modules dir *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_MODULE}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* upload dir *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_UPLOAD}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* data dir *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_DATA}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* cache dir *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_CACHE}</strong></td>
                        <td>{$MOD.LBL_CHECKSYS_OK}</td>
                    </tr>
                    {* End already known to be good *}
                    {* memory limit *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_MEM}</strong></td>
                        <td>{$memory_msg}</td>
                    </tr>
                    {* zlib *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_ZLIB}</strong></td>
                        <td>{$zlibStatus}</td>
                    </tr>
                    {* zip *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_ZIP}</strong></td>
                        <td>{$zipStatus}</td>
                    </tr>
                    {* imap *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_IMAP}</strong></td>
                        <td>{$imapStatus}</td>
                    </tr>
                    {* cURL *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_CURL}</strong></td>
                        <td>{$curlStatus}</td>
                    </tr>
                    {* UPLOAD FILE SIZE *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_UPLOAD_MAX_FILESIZE_TITLE}</strong></td>
                        <td>{$fileMaxStatus}</td>
                    </tr>
                    {* Sprite support *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_SPRITE_SUPPORT}</strong></td>
                        <td>{$spriteSupportStatus}</td>
                    </tr>
                    {* suhosin status *}
                    <tr>
                        <td></td>
                        <td><strong>PHP allows to use stream ({$uploadStream}://)</strong></td>
                        <td>{$suhosinStatus}</td>
                    </tr>
                    {* PHP.ini *}
                    <tr>
                        <td></td>
                        <td><strong>{$MOD.LBL_CHECKSYS_PHP_INI}</strong></td>
                        <td>{$phpIniLocation}</td>
                    </tr>
                </table>
            </td>
        </tr>
        {* CRON Settings *}
        <tr>
            <td align="right" colspan="2">
                <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
                    <tr><th align="left" colspan="2">&nbsp;</th></tr>
                    <tr>
                        <td align="left" colspan="2">
                            {if $is_windows}
                                <font color="red">
                                    {$mod_strings_scheduler.LBL_CRON_WINDOWS_DESC}<br>
                                </font>
                                cd {'./'|realpath}<br>
                                php.exe -f cron.php
                            {else}
                                <font color="red">
                                    {$mod_strings_scheduler.LBL_CRON_INSTRUCTIONS_LINUX}
                                </font>
                                {$mod_strings_scheduler.LBL_CRON_LINUX_DESC}
                                <br>
                                *&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;
                                cd {'./'|realpath}; php -f cron.php > /dev/null 2>&1
                            {/if}
                        </td>
                    </tr>
                </table>
            </td>
        <tr>
            <td colspan="3" align="right">
                <input type="button" class="button" name="print_summary" id="button_print_summary_settings"
                       value="{$MOD.LBL_PRINT_SUMM}"
                       onClick='window.print()'
                       onCluck='window.open("install.php?current_step="+(document.setConfig.current_step.value -1)+"&goto={$MOD.LBL_NEXT}&print=true");'/>&nbsp;
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <hr>
                <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
                    <tr>
                        <td align=right>
                            <input type="button" class="button" id="show_pass_button" value="{$MOD.LBL_SHOW_PASS}"
                                   onClick='togglePass();'/>
                        </td>
                        <td>
                            <input type="hidden" name="goto" id="goto">
                            <input class="button" type="button" value="{$MOD.LBL_BACK}" id="button_back_settings"
                                   onclick="document.getElementById('goto').value='{$MOD.LBL_BACK}';document.getElementById('form').submit();"/>
                        </td>
                        <td>
                            <input class="button" type="button" value="{$MOD.LBL_LANG_BUTTON_COMMIT}"
                                   onclick="document.getElementById('goto').value='{$MOD.LBL_NEXT}';document.getElementById('form').submit();"
                                   id="button_next2"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<script>
    function togglePass(){ldelim}
        if(document.getElementById('show_site_admin_pass').style.display == ''){ldelim}
            document.getElementById('show_pass_button').value = "{$MOD.LBL_SHOW_PASS}";
            document.getElementById('hide_site_admin_pass').style.display = '';
            document.getElementById('hide_db_admin_pass').style.display = '';
            document.getElementById('show_site_admin_pass').style.display = 'none';
            document.getElementById('show_db_admin_pass').style.display = 'none';
        {rdelim} else {ldelim}
            document.getElementById('show_pass_button').value = "{$MOD.LBL_HIDE_PASS}";
            document.getElementById('show_site_admin_pass').style.display = '';
            document.getElementById('show_db_admin_pass').style.display = '';
            document.getElementById('hide_site_admin_pass').style.display = 'none';
            document.getElementById('hide_db_admin_pass').style.display = 'none';

        {rdelim}
    {rdelim}
</script>
</body>
</html>
