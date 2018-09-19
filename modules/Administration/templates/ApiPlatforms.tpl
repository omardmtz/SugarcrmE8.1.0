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
<script type="text/javascript"
        src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
{*<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>*}
<form id="apiplatforms" name="apiplatforms" method="POST">
    {sugar_csrf_form_token}
    <input type="hidden" name="module" value="Administration">
    <input type="hidden" name="action" value="apiplatforms">
    <input type="hidden" id="custom_api_platforms" name="custom_api_platforms" value="">

    <table border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td>
                <input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}"
                       class="button primary" onclick="SUGAR.saveApiPlatforms();" type="button" name="button"
                       value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="button"
                       onclick="document.apiplatforms.action.value='';" type="submit" name="button"
                       value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </td>
        </tr>
    </table>
    <div class='add_table' style='margin-bottom:5px'>
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view" style="margin-bottom: 0;">
            <tr><td>
                    <div id="api_platforms"></div>
                    <div style="margin-left: 25px;max-width: 25%;vertical-align: top;">{$MOD.LBL_CUSTOM_API_PLATFORMS_HELP}</div>
            </td></tr>
            <tr>
                <td>
                    <input type='text' id='platform_name' name='platform_name' maxlength='100' style="width: 182px;">
                    <input type="button" class="button" style="margin-top: -4px;"
                           onclick="SUGAR.apiPlatformsTable.addPlatform()" value="{sugar_translate label='LBL_ADD_BUTTON'}">
                    </input>
                </td>
            </tr>
        </table>
    </div>
    <table border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td>
                <input title="{$APP.LBL_SAVE_BUTTON_LABEL}" class="button primary" onclick="SUGAR.saveApiPlatforms();"
                       type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="button"
                       onclick="document.apiplatforms.action.value='';" type="submit" name="button"
                       value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </td>
        </tr>
    </table>
    <span></span>
</form>

<script type="text/javascript">
    (function() {ldelim}
        var Connect = YAHOO.util.Connect;

        Connect.url = 'index.php';
        Connect.method = 'POST';
        Connect.timeout = 300000;

        var api_platforms = {$api_platforms};
        var lbl_api_platforms = '{sugar_translate label="LBL_API_PLATFORMS"}';
        var deleteImage = '{$deleteImage}';
        {literal}
        deleteRow = function(el) {
            if(confirm(SUGAR.language.get('Administration', 'LBL_REMOVE_PLATFORM'))) {
                SUGAR.apiPlatformsTable.deleteRow(el);
            }
        };

        $('head').append(
            "<style type='text/css'>" +
                ".yui-dt > div { margin-right: -15px; }" +
                "tr.yui-dt-rec { border-bottom: 1px solid #BBB; }" +
                ".add_table td>div {" +
                    "display: inline-block;" +
                "}" +
            "</style>"
        );

        SUGAR.apiPlatformsTable = new YAHOO.widget.ScrollingDataTable(
            "api_platforms",
            [
                {key: "name", label: lbl_api_platforms, width: 200, sortable: false, formatter: function (cell, rec, col, data) {
                    if (rec.getData('custom')) {
                        cell.innerHTML = data + '<a style="float: right;" href="javascript:void()" ' +
                            'onclick="deleteRow(this);">' + deleteImage + '</a>';
                    } else {
                        cell.innerHTML = '<i>' + data + '</i>';
                    }
                }}
            ],
            new YAHOO.util.LocalDataSource(api_platforms, {
                responseSchema: {fields: ['name', 'custom']}
            }),
            {
                height: "300px"
                // , width: "275px"
            }
        );

        SUGAR.apiPlatformsTable.disableEmptyRows = true;
        SUGAR.apiPlatformsTable.render();
        SUGAR.apiPlatformsTable.addPlatform = function(){
            this.addRow({
                name:$('#platform_name').val(),
                custom:true
            });
            $('#platform_name').val("");
        }

        SUGAR.saveApiPlatforms = function() {
            var apiTable = SUGAR.apiPlatformsTable;
            var platforms = [];
            for (var i = 0; i < apiTable.getRecordSet().getLength(); i++) {
                var data = apiTable.getRecord(i).getData();
                if (data.custom && data.name != '')
                    platforms.push(data.name);
            }
            var urlParams = {
                module: "Administration",
                action: "saveApiPlatforms",
                custom_api_platforms: JSON.stringify(platforms),
                csrf_token: SUGAR.csrf.form_token
            }

            ajaxStatus.showStatus(SUGAR.language.get('Administration', 'LBL_SAVING'));
            Connect.asyncRequest(
                Connect.method,
                Connect.url,
                {success: function(){
                    ajaxStatus.flashStatus(SUGAR.language.get('Administration', 'LBL_DONE'), 1000)
                }},
                SUGAR.util.paramsToUrl(urlParams) + "to_pdf=1"
            );

            return true;
        }
    })();
    {/literal}
</script>
