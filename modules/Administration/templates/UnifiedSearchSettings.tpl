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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td colspan="100">
        <h2> {$MOD.LBL_LEGACY_FTS_SETTINGS}</h2>
    </td>
</tr>
<tr>
    <td colspan="100">{$MOD.LBL_UNIFIED_SEARCH_SETTINGS_TITLE}</td>
</tr>
<tr>
    <td>
        <br>
    </td>
</tr>
<tr>
<td colspan="100">

<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<form name="GlobalSearchSettings" method="POST">
{sugar_csrf_form_token}
    <input type="hidden" name="module" value="Administration">
    <input type="hidden" name="action" value="saveGlobalSearchSettings">
    <input type="hidden" name="enabled_modules" value="">

    <table border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td>
                <input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button primary" onclick="SUGAR.saveUnifiedSearchSettings();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="document.GlobalSearchSettings.action.value='';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </td>
        </tr>
    </table>

    <div class='add_table' style='margin-bottom:5px'>
        <table id="GlobalSearchSettings" class="GlobalSearchSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width='1%'>
                    <div id="enabled_div"></div>
                </td>
                <td>
                    <div id="disabled_div"></div>
                </td>
            </tr>
        </table>
    </div>
</form>

</table>

<div id='selectFTSModules' class="yui-hidden">
    <div style="background-color: white; padding: 20px; overflow:scroll; height:400px;">
        <div id='selectFTSModulesTable' ></div>
        <div style="padding-top: 10px"><input type="checkbox" name="clearDataOnIndex" id="clearDataOnIndex" >&nbsp;{$MOD.LBL_DELETE_FTS_DATA}</div>
    </div>
</div>
<script type="text/javascript">
    (function(){ldelim}
        var Connect = YAHOO.util.Connect;
        Connect.url = 'index.php';
        Connect.method = 'POST';
        Connect.timeout = 300000;
        var get = YAHOO.util.Dom.get;

        var enabled_modules = {$enabled_modules};
        var disabled_modules = {$disabled_modules};
        var lblEnabled = '{sugar_translate label="LBL_ACTIVE_MODULES"}';
        var lblDisabled = '{sugar_translate label="LBL_DISABLED_MODULES"}';
        {literal}
        SUGAR.globalSearchEnabledTable = new YAHOO.SUGAR.DragDropTable(
                "enabled_div",
                [{key:"label",  label: lblEnabled, width: 200, sortable: false},
                    {key:"module", label: lblEnabled, hidden:true}],
                new YAHOO.util.LocalDataSource(enabled_modules, {
                    responseSchema: {fields : [{key : "module"}, {key : "label"}]}
                }),
                {height: "300px"}
        );
        SUGAR.globalSearchDisabledTable = new YAHOO.SUGAR.DragDropTable(
                "disabled_div",
                [{key:"label",  label: lblDisabled, width: 200, sortable: false},
                    {key:"module", label: lblDisabled, hidden:true}],
                new YAHOO.util.LocalDataSource(disabled_modules, {
                    responseSchema: {fields : [{key : "module"}, {key : "label"}]}
                }),
                {height: "300px"}
        );

        SUGAR.globalSearchEnabledTable.disableEmptyRows = true;
        SUGAR.globalSearchDisabledTable.disableEmptyRows = true;
        SUGAR.globalSearchEnabledTable.addRow({module: "", label: ""});
        SUGAR.globalSearchDisabledTable.addRow({module: "", label: ""});
        SUGAR.globalSearchEnabledTable.render();
        SUGAR.globalSearchDisabledTable.render();

        SUGAR.getEnabledModules = function()
        {
            var enabledTable = SUGAR.globalSearchEnabledTable;
            var modules = "";
            for(var i=0; i < enabledTable.getRecordSet().getLength(); i++)
            {
                var data = enabledTable.getRecord(i).getData();
                if (data.module && data.module != '')
                    modules += "," + data.module;
            }
            return modules;
        }
        SUGAR.getEnabledModulesForFTSSched = function()
        {
            var enabledTable = SUGAR.FTS.selectedDataTable;
            var modules = [];
            var selectedIDs = enabledTable.getSelectedRows();
            for(var i=0; i < selectedIDs.length; i++)
            {
                var data = enabledTable.getRecord(selectedIDs[i]).getData();
                modules.push(data.module);
            }

            return modules;
        }
        SUGAR.getTranslatedEnabledModules = function()
        {
            var enabledTable = SUGAR.globalSearchEnabledTable;
            var modules = [{module:'', label: SUGAR.language.get('Administration', 'LBL_ALL')}];
            for(var i=0; i < enabledTable.getRecordSet().getLength(); i++)
            {
                var data = enabledTable.getRecord(i).getData();
                if (data.module && data.module != '')
                {
                    var tmp = {'module' : data.module, 'label' : data.label};
                    modules.push(tmp);
                }
            }

            return modules;
        }
        SUGAR.saveUnifiedSearchSettings = function()
        {
            var modules = SUGAR.getEnabledModules();
            modules = modules == "" ? modules : modules.substr(1);

            ajaxStatus.showStatus(SUGAR.language.get('Administration', 'LBL_SAVING'));
            Connect.asyncRequest(
                    Connect.method,
                    Connect.url,
                    {success: SUGAR.saveCallBack},
                    SUGAR.util.paramsToUrl({
                        module: "Administration",
                        action: "saveunifiedsearchsettings",
                        enabled_modules: modules,
                        csrf_token: SUGAR.csrf.form_token
                    }) + "to_pdf=1"
            );

            return true;
        }

        SUGAR.saveCallBack = function(o)
        {
            ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_DONE'));
            var response = YAHOO.lang.trim(o.responseText);
            if (response === "true") {
                var app = parent.SUGAR.App;
                app.metadata.sync(function (){
                    app.additionalComponents.header.getComponent('globalsearch').populateModules();
                });

                window.location.assign('index.php?module=Administration&action=index');
            } else {
                YAHOO.SUGAR.MessageBox.show({msg: response});
            }
        }
    })();
    {/literal}
</script>

