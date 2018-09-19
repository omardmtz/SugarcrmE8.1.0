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
{$moduleTitle}
<script type="text/javascript"
        src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_tba.js'}"></script>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Teams/css/custom.css'}"/>

<form name="TBAConfiguration" method="POST">

    <input type="hidden" name="module" value="Administration">
    <input type="hidden" name="action" value="saveTBAConfiguration">

    <span class="error">{$error.main}</span>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
        <tr>
            <td>
                <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}"
                       class="button" type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
                &nbsp;
                <input title="{$APP.LBL_SAVE_BUTTON_TITLE}"
                       accessKey="{$APP.LBL_SAVE_BUTTON_KEY}"
                       class="button primary"
                       type="button"
                       name="save"
                       value="{$APP.LBL_SAVE_BUTTON_LABEL}"/>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <td align="left" scope="row" colspan="2" class="left">
                <div class="padding-bottom-20">{$MOD.LBL_TBA_CONFIGURATION_TITLE}</div>
                <div class="padding-bottom-20">{if $isUserAdmin}{$MOD.LBL_TBA_CONFIGURATION_WARNING_DESC}{else}{$MOD.LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN}{/if}</div>
            </td>
        </tr>
        <tr>
            <td align="left" scope="row" width="300" class="left">{$MOD.LBL_TBA_CONFIGURATION_LABEL}</td>
            <td scope="row" class="left bg-white">
                <input id="tba_set_enabled" type="checkbox" name="team_based[enable]" value="true"
                       {if $config.enabled}checked="checked"{/if} />
            </td>
        </tr>
    </table>

    <table id="tba_em_block" width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view"
           {if !$config.enabled}style="display: none;"{/if}>
        <tr>
            <th align="left" scope="row"><h4>{$MOD.LBL_TBA_CONFIGURATION_MOD_LABEL}</h4></th>
        </tr>
        <tr>
            <td align="left" class="padding-0">
                <table width="100%" border="0" cellspacing="10" cellpadding="0" class="edit view">
                    <tr>
                    {foreach from=$actionsList key=key item=value name=tba}
                        <td class="title {if $value|in_array:$config.enabled_modules}active{/if}">
                            <div class="tba-container">
                                <input type="checkbox" name="team_based[enabled_modules][]"
                                       data-group="tba_em" data-module-name="{$value}" value="{$value}" id="tba_em_{$key}"
                                       {if $value|in_array:$config.enabled_modules}checked="checked"{/if}/>
                                <label for="tba_em_{$key}">{$APP_LIST.moduleList[$value]}</label>
                            </div>
                        </td>
                        {if $smarty.foreach.tba.iteration % 4 eq 0}</tr><tr>{/if}
                    {/foreach}
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
