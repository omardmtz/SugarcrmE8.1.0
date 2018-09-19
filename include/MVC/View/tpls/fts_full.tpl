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

{literal}
<style type="text/css">

.yui-ac-content {
width:70%;
}

</style>
{/literal}
<script type="text/javascript" src="cache/include/javascript/sugar_grp_yui_widgets.js"></script>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>

{if (!$smarty.get.ajax)}
<h2>{$APP.LBL_SEARCH_RESULTS}</h2>
<br>

<div id='ftsSearchBarContainer' >
    <div id="ftsAutoCompleteResult" style="width:100%;!important"></div>
    <input type="text" placeholder="{$APP.LBL_SEARCH}" name="ftsSearchField" id="ftsSearchField" value="{$smarty.request.q}"  style="width: 70%!important" >
    <input type="button" class="button primary"value="{$APP.LBL_SEARCH}" onclick="SUGAR.FTS.search();" style="vertical-align: bottom;">
    <a id='advanced_search_ahref' href='javascript:SUGAR.FTS.toggleAdvancedOptions();' style="font-size:12px; font-weight:bold; text-decoration:none; text-shadow:0 1px #FFFFFF;">
        {$APP.LBL_ADVANCED}
    </a>
</div>
<div><span id='totalCount'>{$totalHits}</span> {$APP.LBL_SEARCH_RESULTS_FOUND} (<span id='totalTime' style="font-style: italic;">{$totalTime}</span>{$APP.LBL_SEARCH_RESULTS_TIME})</div>
    <br><br>

    <div id='inlineGlobalSearch' style="display:none;">
        <form method="POST" onsubmit="SUGAR.FTS.saveModuleFilterSettings();" >
{sugar_csrf_form_token}
            <input type="hidden" name="module" value="Users">
            <input type="hidden" name="action" value="saveftsmodules">
            <input type="hidden" name="disabled_modules" value="" id="disabled_modules">
            <input type="hidden" name="q" value="" id="save_q">
        <table id="GlobalSearchSettings" class="GlobalSearchSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0" width="30%">
            <tr>
                <td colspan="2">
                {sugar_translate label="LBL_SELECT_FTS_MODULES_TITLE" module="Administration"}
                </td>
            </tr>
            <tr>
                <td width='1%'>
                    <div id="enabled_div"></div>
                </td>
                <td>
                    <div id="disabled_div"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" class="button primary" value="{$APP.LBL_SAVE_BUTTON_LABEL}">&nbsp;</td>
            </tr>
        </table>
        </form>
    </div>

{/if}


<table width="80%">
<tr ><td width="20%">&nbsp;</td><td width="90%"></td></tr>
<tr valign="top" >
    <td id="moduleListTD" style="">
        <b>{$APP.LBL_MODULE_FILTER}</b>
        <div id='moduleListRs'>
            {include file='include/MVC/View/tpls/fts_modfilter.tpl'}
        </div>
    </td>
<td>
    <div id="sugar_full_search_results" >
        {include file=$rsTemplate}
    </div>
    <div id="showMoreDiv"  onclick="SUGAR.FTS.loadMore();" style="{$showMoreDivStyle}">{$APP.LBL_SEARCH_LOAD_MORE}</div>
</td>
    </tr>
</table>


{if (!$smarty.get.ajax)}

<script>

    var enabled_modules = {$enabled_modules};
    var disabled_modules = {$disabled_modules};
    var lblEnabled = '{sugar_translate label="LBL_ACTIVE_MODULES" module="Administration"}';
    var lblDisabled = '{sugar_translate label="LBL_DISABLED_MODULES" module="Administration"}';
    var noModules = '{sugar_translate label="LBL_FTS_NO_MODULES" module="Administration"}';
    {literal}

    $("#ftsSearchField").keypress(function(event) {
        if(event.keyCode == 13)
            SUGAR.FTS.search();
    });

    SUGAR.FTS = {

        currentOffset: 0,
        limit: 0,
        totalHits: 0,
        showMore: true,
        addModuleFilterHandlers: function()
        {
            $('.ftsModuleFilter').bind('click', function(e)
            {
                // need at least one module selected to search
                var m = SUGAR.FTS.getSelectedModules();
                if (m.length <= 0)
                {
                    alert(noModules);
                    this.checked = !this.checked;
                    return;
                }
                if (this.id == 'all')
                {
                    if (this.checked)
                    {
                        // uncheck all others
                        $('#moduleListTD').find('.ftsModuleFilter:checked').each(function(i) {
                            //results.push($(this).attr('id'));
                            if (this.id != 'all')
                            {
                                $('#'+this.id).removeAttr("checked");
                                var textLabel = this.id + '_label';
                                var countLabel = this.id + '_count';
                                $('#'+textLabel).addClass('unchecked');
                                $('#'+countLabel).addClass('unchecked');
                            }
                        });
                    }
                }
                else {
                    // uncheck all
                    $('#all').removeAttr("checked");
                    $('#all_label').addClass('unchecked');
                }
                SUGAR.FTS.search();
                var textLabel = this.id + '_label';
                var countLabel = this.id + '_count';

                if(this.checked)
                {
                    $('#'+textLabel).removeClass('unchecked');
                    $('#'+countLabel).removeClass('unchecked');
                }
                else
                {
                    $('#'+textLabel).addClass('unchecked');
                    $('#'+countLabel).addClass('unchecked');
                }
            });

        },
        getSelectedModules: function()
        {
            var results = [];
            $('#moduleListTD').find('.ftsModuleFilter:checked').each(function(i){
                results.push($(this).attr('id'));
            });
            return results;
        },
        search: function(append)
        {
            //For new searches reset the offset
            if(typeof(append) == 'undefined' || !append)
            {
                SUGAR.FTS.currentOffset = 0;
            }

            SUGAR.FTS.showMore = true;
            $('#sugar_full_search_results').showLoading();
            //TODO: Check if all modules are selected, then don't send anything down.
            var m = this.getSelectedModules();
            var rml = 1; // refresh module list
            if (m == 'all')
            {
                m = ''; // do not send anything if search all modules
            }
            var q = $("#ftsSearchField").val();

            // this view shouldn't be supported outside of a BWC frame.
            window.parent.SUGAR.App.controller.layout.getComponent('bwc').unbindDom();

            $.ajax({
                type: "POST",
                url: "index.php",
                dataType: 'json',
                data: {'action':'spot', 'ajax': true,'full' : true, 'module':'Home', 'to_pdf' : '1',  'q': q, 'm' : m, 'rs_only': true,
                        'offset': SUGAR.FTS.currentOffset, 'refreshModList': rml, append_wildcard : true},
                success: function(o)
                {
                    SUGAR.FTS.totalHits = o.totalHits;
                    if(typeof(append) != 'undefined' && append)
                    {
                        $("#sugar_full_search_results").append(o.results);

                    }
                    else
                    {
                        $("#sugar_full_search_results").html(o.results);
                    }
                    $("#totalTime").html(o.totalTime);
                    $("#totalCount").html(o.totalHits);
                    $('#sugar_full_search_results').hideLoading();
                    if(typeof(o.mod_filter) != 'undefined')
                    {
                        $("#moduleListRs").html(o.mod_filter);
                        SUGAR.FTS.addModuleFilterHandlers();
                    }
                    SUGAR.FTS.toogleShowMore();
                    window.parent.SUGAR.App.controller.layout.getComponent('bwc').rewriteLinks();
                },
                failure: function(o)
                {
                    $('#sugar_full_search_results').hideLoading();
                }
            });
        },
        toogleShowMore : function()
        {
            if( (SUGAR.FTS.currentOffset + SUGAR.FTS.limit < SUGAR.FTS.totalHits) && SUGAR.FTS.showMore)
            {
                $('#showMoreDiv').show();
            }
            else
            {
                $('#showMoreDiv').hide();
            }
        },
        toggleAdvancedOptions: function()
        {
            if (document.getElementById('inlineGlobalSearch').style.display == 'none')
            {
                SUGAR.FTS.globalSearchEnabledTable.render();
                SUGAR.FTS.globalSearchDisabledTable.render();
                document.getElementById('inlineGlobalSearch').style.display = '';
                document.getElementById('advanced_search_ahref').innerHTML = SUGAR.language.get('app_strings', 'LBL_BASIC');
            }
            else
            {
                document.getElementById('inlineGlobalSearch').style.display = 'none';
                document.getElementById('advanced_search_ahref').innerHTML = SUGAR.language.get('app_strings', 'LBL_ADVANCED');
            }
        },
        globalSearchEnabledTable : new YAHOO.SUGAR.DragDropTable(
                "enabled_div",
                [{key:"label",  label: lblEnabled, width: 200, sortable: false},
                 {key:"module", label: lblEnabled, hidden:true}],
                new YAHOO.util.LocalDataSource(enabled_modules, {
                    responseSchema: {fields : [{key : "module"}, {key : "label"}]}
                }),
                {height: "200px"}
        ),
        globalSearchDisabledTable : new YAHOO.SUGAR.DragDropTable(
                "disabled_div",
                [{key:"label",  label: lblDisabled, width: 200, sortable: false},
                 {key:"module", label: lblDisabled, hidden:true}],
                new YAHOO.util.LocalDataSource(disabled_modules, {
                    responseSchema: {fields : [{key : "module"}, {key : "label"}]}
                }),
                {height: "200px"}
        ),
        saveModuleFilterSettings : function()
        {
            var enabledTable = SUGAR.FTS.globalSearchDisabledTable;
            var modules = "";
            for(var i=0; i < enabledTable.getRecordSet().getLength(); i++){
                var data = enabledTable.getRecord(i).getData();
                if (data.module && data.module != '')
                    modules += "," + data.module;
            }
            modules = modules == "" ? modules : modules.substr(1);
            document.getElementById('disabled_modules').value = modules;
            document.getElementById('save_q').value = document.getElementById('ftsSearchField').value;

        },
        loadMore: function()
        {
            SUGAR.FTS.currentOffset += SUGAR.FTS.limit;
            SUGAR.FTS.search(true);
        }
    }

    SUGAR.FTS.addModuleFilterHandlers();

    //Setup autocomplete
    var data = encodeURIComponent(YAHOO.lang.JSON.stringify({'method':'fts_query','conditions':[]}));
    var autoCom = $( "#ftsSearchField" ).autocomplete({
        source: 'index.php?to_pdf=true&module=Home&action=quicksearchQuery&full=true&rs_only=true&append_wildcard=true&data='+data,
        select: function(event, ui) {},
        minLength: 3,
        search: function(event,ui){
            $('#sugar_full_search_results').showLoading();
        }
        }).data( "autocomplete" )._response = function(content)
        {
            var el = $("#sugar_full_search_results");

            if(typeof(content.results) != 'undefined')
            {
                el.html( content.results);
                SUGAR.FTS.totalHits = content.totalHits;
                $("#totalCount").html(SUGAR.FTS.totalHits);
                $("#totalTime").html(content.totalTime);
            }
            if(typeof(content.mod_filter) != 'undefined')
            {
                $("#moduleListRs").html(content.mod_filter);
                SUGAR.FTS.addModuleFilterHandlers();
            }
            this.pending--;
            SUGAR.FTS.showMore = false;
            SUGAR.FTS.toogleShowMore();
            $('#sugar_full_search_results').hideLoading();
        };

    //Overload the search function so we can pass additional arguments into the source call.
    (function($) {
        $.extend(true, $["ui"]["autocomplete"].prototype, {
            _search: function(value) {
                var self = this;
                self.pending++;
                var m = SUGAR.FTS.getSelectedModules();
                var rml = 1; // refresh module list
                if (m == 'all')
                {
                    m = ''; // do not send anything if search all modules
                }
                var data = { term: value, m: m, refreshModList: rml };
                SUGAR.FTS.currentOffset = 0;
                self.source(data, self.response );
            }
        });
    })(jQuery);
    //Setup enable table
    SUGAR.FTS.globalSearchEnabledTable.disableEmptyRows = true;
    SUGAR.FTS.globalSearchDisabledTable.disableEmptyRows = true;
    SUGAR.FTS.globalSearchEnabledTable.addRow({module: "", label: ""});
    SUGAR.FTS.globalSearchDisabledTable.addRow({module: "", label: ""});
    SUGAR.FTS.globalSearchEnabledTable.render();
    SUGAR.FTS.globalSearchDisabledTable.render();
    {/literal}
    SUGAR.FTS.offset = {$offset};
    SUGAR.FTS.limit = {$limit};
    SUGAR.FTS.totalHits = {$totalHits};
</script>

{/if}

