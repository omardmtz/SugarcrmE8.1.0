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
        <h2>{$MOD.LBL_FTS_SETTINGS}</h2>
    </td>
</tr>
<tr>
    <td colspan="100">{$MOD.LBL_GLOBAL_SEARCH_SETTINGS_TITLE}</td>
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
			<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button primary" onclick="SUGAR.saveGlobalSearchSettings();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$MOD.LBL_SAVE_SCHED_BUTTON}" class="button primary schedFullSystemIndex" onclick="SUGAR.FTS.schedFullSystemIndex();" style="{if !$showSchedButton}display:none;{/if}text-decoration: none;" id='schedFullSystemIndexBtn' type="button" name="button" value="{$MOD.LBL_SAVE_SCHED_BUTTON}">
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
    <div {if $hide_fts_config}style="display:none;"{/if}>

        <br>
        {$MOD.LBL_FTS_PAGE_DESC}
        <br><br>
        <table width="50%" border="0" cellspacing="1" cellpadding="0" class="edit view">
            <tbody>
                <tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_FTS_SETTINGS_TITLE}</h4></th></tr>

                <tr>
                    <td width="25%" scope="row" valign="middle">{$MOD.LBL_FTS_TYPE}:&nbsp;{sugar_help text=$MOD.LBL_FTS_TYPE_HELP}</td>
                    <td width="25%" align="left" valign="middle"><select name="fts_type" id="fts_type">{$fts_type}</select></td>
                    <td width="60%">&nbsp;</td>
                </tr>
                <tr class="shouldToggle">
                    <td width="25%" scope="row" valign="middle">{$MOD.LBL_FTS_HOST}:&nbsp;{sugar_help text=$MOD.LBL_FTS_HOST_HELP}</td>
                    <td width="25%" align="left" valign="middle"><input type="text" name="fts_host" id="fts_host" value="{$fts_host}" ></td>
                    <td width="60%" valign="bottom">&nbsp;<a href="javascript:void(0);" onclick="SUGAR.FTS.testSettings();" style="text-decoration: none;">{$MOD.LBL_FTS_TEST}</a></td>
                </tr>
                <tr class="shouldToggle">
                    <td width="25%" scope="row" valign="middle">{$MOD.LBL_FTS_PORT}:&nbsp;{sugar_help text=$MOD.LBL_FTS_PORT_HELP}</td>
                    <td width="25%" align="left" valign="middle"><input type="text" name="fts_port" id="fts_port" maxlength="5" size="5" value="{$fts_port}"></td>
                    <td width="60%"></td>
                </tr>
                <tr class="shouldToggle">
                    <td colspan="2">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
	<table border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td>
				<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" class="button primary" onclick="SUGAR.saveGlobalSearchSettings();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$MOD.LBL_SAVE_SCHED_BUTTON}" class="button primary schedFullSystemIndex" onclick="SUGAR.FTS.schedFullSystemIndex();" style="{if !$showSchedButton}display:none;{/if}text-decoration: none;" id='schedFullSystemIndex' type="button" name="button" value="{$MOD.LBL_SAVE_SCHED_BUTTON}">
                <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="button" onclick="document.GlobalSearchSettings.action.value='';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
			</td>
		</tr>
	</table>
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

        SUGAR.getModulesFromTable = function(table)
        {
            var modules = "";
            for(var i=0; i < table.getRecordSet().getLength(); i++)
            {
                var data = table.getRecord(i).getData();
                if (data.module && data.module != '')
                    modules += "," + data.module;
            }
            modules = modules == "" ? modules : modules.substr(1);
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
	SUGAR.saveGlobalSearchSettings = function()
	{
        var host = document.getElementById('fts_host').value;
        var port = document.getElementById('fts_port').value;
        var typeEl = document.getElementById('fts_type');
        var type = typeEl.options[typeEl.selectedIndex].value;

        if( type != "")
        {
            if(!check_form('GlobalSearchSettings'))
                return;
        }
        var enabled = SUGAR.getModulesFromTable(SUGAR.globalSearchEnabledTable);
        var disabled = SUGAR.getModulesFromTable(SUGAR.globalSearchDisabledTable);

        var urlParams = {
            module: "Administration",
            action: "saveglobalsearchsettings",
            host: host,
            port: port,
            type: type,
            enabled_modules: enabled,
            disabled_modules: disabled,
            csrf_token: SUGAR.csrf.form_token
        }

		ajaxStatus.showStatus(SUGAR.language.get('Administration', 'LBL_SAVING'));
		Connect.asyncRequest(
            Connect.method,
            Connect.url,
            {success: SUGAR.saveCallBack},
			SUGAR.util.paramsToUrl(urlParams) + "to_pdf=1"
        );

		return true;
	}

	SUGAR.saveCallBack = function(o)
	{
	   ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_DONE'));
        var response = YAHOO.lang.trim(o.responseText);
        if (response === "true") {
            var app = parent.SUGAR.App;
            app.sync();
            window.location.assign('index.php?module=Administration&action=index');
	   } else {
            YAHOO.SUGAR.MessageBox.show({msg: response});
	   }
	}
})();
{/literal}
</script>
<script type="text/javascript">
    var justRequestedAScheduledIndex = '{$justRequestedAScheduledIndex}';
    {literal}
    $(document).ready(function()
    {
        if(justRequestedAScheduledIndex)
            alert(SUGAR.language.get('Administration','LBL_FTS_CONN_SUCCESS_SHORT'));
    });

    SUGAR.FTS = {

        saveFullSysIndexSched: function()
        {
            var host = document.getElementById('fts_host').value;
            var port = document.getElementById('fts_port').value;
            var typeEl = document.getElementById('fts_type');
            var type = typeEl.options[typeEl.selectedIndex].value;
            var clearData = $('#clearDataOnIndex').attr('checked') ? 1 : 0;

            var modules = SUGAR.getEnabledModulesForFTSSched();
            if(modules.length == 0)
            {
                alert(SUGAR.language.get('app_strings','LBL_FTS_NO_MODULES_FOR_SCHED'));
                return;
            }
            modules = modules.join(",");
            if(host == "" || port == "" || type == "")
            {
                SUGAR.FTS.selectFTSModulesDialog.cancel();
                check_form('GlobalSearchSettings');
                return;
            }
            var sUrl = 'index.php?to_pdf=1&module=Administration&action=ScheduleFTSIndex&sched=true&type='
                            + encodeURIComponent(type) + '&host=' + encodeURIComponent(host) + '&port=' + encodeURIComponent(port)
                            + "&clearData=" + clearData + '&modules=' + encodeURIComponent(modules);

            var transaction = YAHOO.util.Connect.asyncRequest('GET', sUrl, null, null);
            alert(SUGAR.language.get('Administration','LBL_FTS_CONN_SUCCESS_SHORT'));
            SUGAR.FTS.selectFTSModulesDialog.cancel();

        },
        schedFullSystemIndex : function()
        {
            if(!confirm(SUGAR.language.get('Administration','LBL_SAVE_SCHED_WARNING')))
                return;

            var modules = SUGAR.getTranslatedEnabledModules();
            if(modules.length == 1)
            {
                alert(SUGAR.language.get('app_strings','LBL_FTS_NO_MODULES_FOR_SCHED'));
                return;
            }
            var handleCancel = function() {
                this.cancel();
            };
            var handleSubmit = function() {
                SUGAR.FTS.saveFullSysIndexSched();
            };

            var buttons = [
                { text: SUGAR.language.get('Administration','LBL_FTS_INDEX_BUTTON'), handler: handleSubmit, isDefault: true },
                { text: SUGAR.language.get('app_strings','LBL_CANCEL_BUTTON_LABEL'), handler: handleCancel }
            ];

            if(!SUGAR.FTS.selectFTSModulesDialog)
            {
                SUGAR.FTS.selectFTSModulesDialog = new YAHOO.widget.Dialog("selectFTSModules", {
                    modal:true,
                    visible:true,
                    fixedcenter:true,
                    constraintoviewport: true,
                    width	: '300px',
                    shadow	: false,
                    buttons : buttons
                });
            }
            SUGAR.FTS.selectFTSModulesDialog.setHeader(SUGAR.language.get('Administration','LBL_SAVE_SCHED_BUTTON'));
            YAHOO.util.Dom.removeClass("selectFTSModules", "yui-hidden");
            SUGAR.FTS.selectFTSModulesDialog.render();
            SUGAR.FTS.selectFTSModulesDialog.show();

            var enabledModules = {'modules': modules};

            var myColumnDefs = [{key:"label",label:SUGAR.language.get('Administration','LBL_AVAILABLE_FTS_MODULES'), sortable:false, width:200}];

            this.myDataSource = new YAHOO.util.DataSource(enabledModules);
            this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            this.myDataSource.responseSchema = {
                resultsList: "modules",
                fields: ["label","module"]
            };

            SUGAR.FTS.selectedDataTable = new YAHOO.widget.DataTable("selectFTSModulesTable",myColumnDefs, this.myDataSource, {});

            // Subscribe to events for row selection
            SUGAR.FTS.selectedDataTable.subscribe("rowMouseoverEvent", SUGAR.FTS.selectedDataTable.onEventHighlightRow);
            SUGAR.FTS.selectedDataTable.subscribe("rowMouseoutEvent", SUGAR.FTS.selectedDataTable.onEventUnhighlightRow);
            SUGAR.FTS.selectedDataTable.subscribe("rowClickEvent", SUGAR.FTS.selectedDataTable.onEventSelectRow);

            // Programmatically select the first row
            SUGAR.FTS.selectedDataTable.selectRow(SUGAR.FTS.selectedDataTable.getTrEl(0));
            // Programmatically bring focus to the instance so arrow selection works immediately
            SUGAR.FTS.selectedDataTable.focus();

        },
        testSettings : function()
        {
            var host = document.getElementById('fts_host').value;
            var port = document.getElementById('fts_port').value;
            var typeEl = document.getElementById('fts_type');
            var type = typeEl.options[typeEl.selectedIndex].value;
            if(type != "")
            {
                if(!check_form('GlobalSearchSettings'))
                    return
            }

            SUGAR.FTS.rsPanel = new YAHOO.widget.SimpleDialog("FTSPanel", {
                                    modal: true,
                                    width: "260px",
                                    visible: true,
                                    constraintoviewport: true,
                                    loadingText: SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING"),
                                    close: true
                                });

            var panel = SUGAR.FTS.rsPanel;
            panel.setHeader(SUGAR.language.get('Administration','LBL_CONNECT_STATUS')) ;
            panel.setBody(SUGAR.language.get("app_strings", "LBL_EMAIL_LOADING"));
            panel.render(document.body);
            panel.show();
            panel.center();

            var callback = {
                success: function(o) {
                    var r = YAHOO.lang.JSON.parse(o.responseText);
                    panel.setBody(r.status);
                    if(r.valid)
                    {
                        $('.schedFullSystemIndex').show();
                    }
                    else
                    {
                        $('.schedFullSystemIndex').hide();
                    }

                },
                failure: function(o) {}
            }

            var sUrl = 'index.php?to_pdf=1&module=Administration&action=checkFTSConnection&type='
                + encodeURIComponent(type) + '&host=' + encodeURIComponent(host) + '&port=' + encodeURIComponent(port);

            var transaction = YAHOO.util.Connect.asyncRequest('GET', sUrl, callback, null);

        }
    };
    {/literal}
    addForm('GlobalSearchSettings');
    addToValidateMoreThan('GlobalSearchSettings', 'fts_port', 'int', true, '{$MOD.LBL_FTS_PORT}', 1);
    addToValidate('GlobalSearchSettings', 'fts_host', 'varchar', 'true', '{$MOD.LBL_FTS_URL}');
</script>
