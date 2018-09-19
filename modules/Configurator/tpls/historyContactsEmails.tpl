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
<div class="moduleTitle">
    <h2>{sugar_translate module='Administration' label='LBL_HISTORY_CONTACTS_EMAILS'}</h2>
    <div class="clear"></div>
</div>
<form name="AdminSettings" method="POST">
{sugar_csrf_form_token}
    <input type="hidden" name="action" value="historyContactsEmailsSave">
    <input type="hidden" name="module" value="Configurator">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
        <tr>
            <td width="100%" colspan="2">
                <input type="submit" id="configuratorHistoryContactsEmails_admin_save"  class="button primary" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input type="button" id="configuratorHistoryContactsEmails_admin_cancel" onclick="location.href='index.php?module=Administration&amp;action=index';" class="button" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <td scope="row" align="right" valign="top" nowrap>{$MOD.LBL_ENABLE_HISTORY_CONTACTS_EMAILS}:</td>
            <td colspan="4" width="95%">
                <table id="sugarfeed_modulelist" cellspacing=3 border=0>
                    {foreach name=feedModuleList from=$modules item=entry}
                        {if ($smarty.foreach.feedModuleList.index % 2)==0}<tr>{/if}
                        <td scope="row" align="right">{$entry.label}:</td>
                        <td>
                            <input type="hidden" name="modules[{$entry.module}]" value="0">
                            <input type="checkbox" id="modules[{$entry.module}]" name="modules[{$entry.module}]" value="1" {if $entry.enabled==1}CHECKED{/if}>
                        </td>
                        {if ($i % 2)==1}</tr>{/if}
                    {/foreach}
                </table>
            </td></tr>
    </table>
</form>
