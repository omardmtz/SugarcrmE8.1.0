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
<form name="enableWirelessModules" method="POST">
    {sugar_csrf_form_token}
	<input type="hidden" name="module" value="Administration">
	<input type="hidden" name="action" value="updateWirelessEnabledModules">
	<input type="hidden" name="enabled_modules" value="">
	
	<table border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td>
			<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button primary" onclick="SUGAR.saveMobileSettings();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
			<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="document.enableWirelessModules.action.value='';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
			</td>
		</tr>
	</table>
	
	<div class='add_table' style='margin-bottom:5px'>
		<table id="enableWirelessModules" class="enableWirelessModules edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0" width="25%">
			<tr>
			    <td colspan="2">
			        <table>
                    {if $url}
                    <tr>
                        <td scope="row" nowrap="nowrap">
                            {sugar_translate module='Configurator' label='LBL_WIRELESS_SERVER_URL'}:
                            {sugar_help text=$MOD.LBL_WIRELESS_URL_HELP}
                        </td>
                        </td>
                        <td>
                            <a href="{$url}" target="_blank">{$url}</a>
                        </td>
                    </tr>
                    {/if}
                </td>
            </tr>
            <tr>
                <td colspan="2" white-space="wrap" style="font-style: italic;"><span>{sugar_translate label='LBL_WIRELESS_MODULES_ENABLE_DESC2'}</span></td>
            </tr>
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

    <div  style="border: 1px solid gray; margin: 0 8px;">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="enableWirelessModules edit view" style="margin-bottom: 0;">
        <tr>
            <th align="left" scope="row" colspan="2">
                <h4>{sugar_translate module='Administration' label='LBL_OFFLINE_SETTINGS'}</h4>
            </th>
        </tr>
        <tr>
            <td scope="row" style="width: 50%">
                <label for="offline_enabled">{sugar_translate module='Administration' label='LBL_OFFLINE_ENABLED'}</label>
            </td>
            <td>
                <input type='checkbox' id="offline_enabled" {if $config.offlineEnabled}checked{/if} />
            </td>
        </tr>
    </table>
    </div>
	
	<table border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td>
				<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" class="button primary" onclick="SUGAR.saveMobileSettings();" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
				<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="button" onclick="document.enableWirelessModules.action.value='';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
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
	SUGAR.mobileEnabledTable = new YAHOO.SUGAR.DragDropTable(
		"enabled_div",
		[{key:"label",  label: lblEnabled, width: 200, sortable: false},
		 {key:"module", label: lblEnabled, hidden:true}],
		new YAHOO.util.LocalDataSource(enabled_modules, {
			responseSchema: {fields : [{key : "module"}, {key : "label"}]}
		}),  
		{height: "300px"}
	);
	SUGAR.mobileDisabledTable = new YAHOO.SUGAR.DragDropTable(
		"disabled_div",
		[{key:"label",  label: lblDisabled, width: 200, sortable: false},
		 {key:"module", label: lblDisabled, hidden:true}],
		new YAHOO.util.LocalDataSource(disabled_modules, {
			responseSchema: {fields : [{key : "module"}, {key : "label"}]}
		}),
		{height: "300px"}
	);
	SUGAR.mobileEnabledTable.disableEmptyRows = true;
	SUGAR.mobileDisabledTable.disableEmptyRows = true;
	SUGAR.mobileEnabledTable.addRow({module: "", label: ""});
	SUGAR.mobileDisabledTable.addRow({module: "", label: ""});
	SUGAR.mobileEnabledTable.render();
	SUGAR.mobileDisabledTable.render();
	
	SUGAR.saveMobileSettings = function()
	{
		var enabledTable = SUGAR.mobileEnabledTable;
		var modules = "";
		for(var i=0; i < enabledTable.getRecordSet().getLength(); i++){
			var data = enabledTable.getRecord(i).getData();
			if (data.module && data.module != '')
			    modules += "," + data.module;
		}
		modules = modules == "" ? modules : modules.substr(1);

        var urlParams = {
            module: "Administration",
            action: "updateWirelessEnabledModules",
            enabled_modules: modules,
            offlineEnabled: $('#offline_enabled').is(':checked'),
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
	       window.location.assign('index.php?module=Administration&action=index');
	   } 
	   else 
	   {
           YAHOO.SUGAR.MessageBox.show({msg: response});
	   }
	}	
})();
{/literal}
</script>
