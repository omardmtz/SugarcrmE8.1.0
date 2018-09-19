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
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>

<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<style>.yui-dt-scrollable .yui-dt-bd {ldelim}overflow-x: hidden;{rdelim}</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr><td colspan='100'><h2>{$title}</h2></td></tr>
    <tr><td><i class="info">{$msg}</i></td></tr>
    <tr><td colspan='100'>
        {if empty($msg)}
            <form name="ConfigureShortcutBar" method="POST" action="index.php">
{sugar_csrf_form_token}
                <input type="hidden" name="module" value="Administration">
                <input type="hidden" name="action" value="ConfigureShortcutBar">
                <input type="hidden" id="enabled_modules" name="enabled_modules" value="">
                <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
                <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
                <br>
                <p>{$MOD.LBL_CONFIGURE_SHORTCUT_BAR_HELP}</p>
                <br>
                <table border="0" cellspacing="1" cellpadding="1">
                    <tr>
                        <td>
                            <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.saveShortcutBar();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" >
                            <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="parent.SUGAR.App.router.navigate(parent.SUGAR.App.router.buildRoute('{$RETURN_MODULE}'), {literal}{trigger: true}{/literal}); return false;" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
                        </td>
                    </tr>
                </table>
                <div class='add_table' style='margin-bottom:5px'>
                    <table id="ConfigureTabs" class="themeSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width='1%'><div id="enabled_div"></div></td>
                            <td><div id="disabled_div"></div></td>
                        </tr>
                    </table>
                </div>
                <table border="0" cellspacing="1" cellpadding="1">
                    <tr>
                        <td>
                            <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button primary" onclick="SUGAR.saveShortcutBar();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" >
                            <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="parent.SUGAR.App.router.navigate(parent.SUGAR.App.router.buildRoute('{$RETURN_MODULE}'), {literal}{trigger: true}{/literal}); return false;" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
                        </td>
                    </tr>
                </table>
            </form>
        <script type="text/javascript">
            (function(){ldelim}
                var Connect = YAHOO.util.Connect;
                Connect.url = 'index.php';
                Connect.method = 'POST';
                Connect.timeout = 300000;

                var enabled_modules = {$enabled_modules};
                var disabled_modules = {$disabled_modules};
                var lblEnabled = '{sugar_translate label="LBL_ACTIVE_MODULES"}';
                var lblDisabled = '{sugar_translate label="LBL_DISABLED_MODULES"}';
                {literal}
                SUGAR.prodEnabledTable = new YAHOO.SUGAR.DragDropTable(
                        "enabled_div",
                        [
                            {key: "label", label: lblEnabled, width: 200, sortable: false},
                            {key: "module", label: lblEnabled, hidden: true}
                        ],
                        new YAHOO.util.LocalDataSource(enabled_modules, {
                            responseSchema: {
                                resultsList: "modules",
                                fields: [
                                    {key: "module"},
                                    {key: "label"}
                                ]
                            }
                        }),
                        {height: "300px"}
                );
                SUGAR.prodDisabledTable = new YAHOO.SUGAR.DragDropTable(
                        "disabled_div",
                        [
                            {key: "label", label: lblDisabled, width: 200, sortable: false},
                            {key: "module", label: lblDisabled, hidden: true}
                        ],
                        new YAHOO.util.LocalDataSource(disabled_modules, {
                            responseSchema: {
                                resultsList: "modules",
                                fields: [
                                    {key: "module"},
                                    {key: "label"}
                                ]
                            }
                        }),
                        {height: "300px"}
                );
                SUGAR.prodEnabledTable.disableEmptyRows = true;
                SUGAR.prodDisabledTable.disableEmptyRows = true;
                SUGAR.prodEnabledTable.addRow({module: "", label: ""});
                SUGAR.prodDisabledTable.addRow({module: "", label: ""});
                SUGAR.prodEnabledTable.render();
                SUGAR.prodDisabledTable.render();

                SUGAR.saveShortcutBar = function() {
                    var enabledTable = SUGAR.prodEnabledTable;
                    var modules = [];
                    for (var i = 0; i < enabledTable.getRecordSet().getLength(); i++) {
                        var data = enabledTable.getRecord(i).getData();
                        if (data.module && data.module != '')
                            modules[i] = data.module;
                    }

                    ajaxStatus.showStatus(SUGAR.language.get('Administration', 'LBL_SAVING'));
                    //YAHOO.SUGAR.MessageBox.show({title:"saving",msg:"Saving",close:false})
                    Connect.asyncRequest(
                            Connect.method,
                            Connect.url,
                            {success: SUGAR.saveCallBack},
                            'to_pdf=1&module=Administration&action=ConfigureShortcutBar&enabled_modules=' + YAHOO.lang.JSON.stringify(modules) + '&csrf_token=' + SUGAR.csrf.form_token
                    );

                    return true;
                }
                SUGAR.saveCallBack = function(o) {
                    ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_DONE'));
                    var response = YAHOO.lang.trim(o.responseText);
                    if (response === "true") {
                        parent.window.location.reload();
                    }
                    else {
                        YAHOO.SUGAR.MessageBox.show({msg: response});
                    }
                }
            })();
            {/literal}
        </script>
        {/if}
    </td></tr>
