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
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Reports/tpls/reports.css'}" />

{if ($issetSaveResults)}

	{if ($isSaveResults)}
<p>	<span><b>{$mod_strings.LBL_SUCCESS_REPORT}{$save_report_as_str}{$mod_strings.LBL_WAS_SAVED}</b></span></p>
	{else}
	<span><b>{$mod_strings.LBL_FAILURE_REPORT}{$save_report_as_str}{$mod_strings.LBL_WAS_NOT_SAVED}</b></span></p>
	{/if}
{/if}
{$form_header}

{$chartResources}


<form action="index.php?action=ReportCriteriaResults&module=Reports&page=report&id={$report_id}" method="post" name="EditView" id="EditView" onSubmit="return fill_form();">
{sugar_csrf_form_token}
<input type="hidden" name='report_offset' value ="{$report_offset}">
<input type="hidden" name='sort_by' value ="{$sort_by}">
<input type="hidden" name='sort_dir' value ="{$sort_dir}">
<input type="hidden" name='summary_sort_by' value ="{$summary_sort_by}">
<input type="hidden" name='summary_sort_dir' value ="{$summary_sort_dir}">
<input type="hidden" name='expanded_combo_summary_divs' id='expanded_combo_summary_divs' value=''>
<input type="hidden" name="action" value="ReportCriteriaResults">
<input type="hidden" name="module" value="Reports">
<input type="hidden" id="record" name="record" value="{$report_id}">
<input type="hidden" id="id" name="id" value="{$id}">
<input type="hidden" name='report_def' value ="">
<input type="hidden" name='save_as' value ="">
<input type="hidden" name='save_as_report_type' value ="">
<input type="hidden" name="page" value="report">
<input type="hidden" name="is_delete" value="0">
<input type="hidden" name="to_pdf" value="{$to_pdf}"/>
<input type="hidden" name="to_csv" value="{$to_csv}"/>
<input type="hidden" name="save_report" value=""/>
<input type="hidden" name='showReportDetails' value ="{$showReportDetails}">
<input type="hidden" name='showChart' value ="{$showChart}">
<input type="hidden" id="blankimagepath" name="blankimagepath" value="{sugar_getimagepath file='blank.gif'}">


<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

{sugar_action_menu id="detail_header_action_menu" buttons=$action_button class="clickMenu fancymenu"}
</td>
</tr>
</table>
<script language="javascript">
var form_submit = "{$form_submit}";
LBL_RELATED = '{$mod_strings.LBL_RELATED}';
{literal}
{/literal}
ACLAllowedModules = {$ACLAllowedModules};
</script>
<BR>





{if ($warnningMessage neq '')}
<table width="100%" cellspacing=0 cellpadding=0>
<tr>
	<td><h3>{$warnningMessage}</h3>
	</td>
</tr>
</table>
{/if}
<table width="100%" cellspacing=0 cellpadding=0 class="actionsContainer">
<tr>
<td><input type=button name="showHideReportDetails" id="showHideReportDetails" class="button" title="{$reportDetailsButtonTitle}" value="{$reportDetailsButtonTitle}" onClick="showHideReportDetailsButton();">
</td>
</tr>
</table>
<table width="100%" cellspacing=0 cellpadding=0>
<tr>
	<td width="100%" scope="row">
		<table width="100%" id="reportDetailsTable" name="reportDetailsTable" style="{$reportDetailsTableStyle}">
			<tr>
				<td wrap="true">
					<b>{$mod_strings.LBL_REPORT_ATT_NAME}:</b> {$reportName}
				</td>
				<td wrap="true">
					<b>{$mod_strings.LBL_REPORT__ATT_TYPE}:</b> {$reportType}
				</td>
			</tr>
			<tr>
				<td wrap="true">
					<b>{$mod_strings.LBL_REPORT_ATT_MODULES}:</b> {$reportModuleList}
				</td>
				<td wrap="true">
					<b>{$mod_strings.LBL_TEAM}:</b> {$reportTeam}
				</td>
			</tr>
			<tr>
				<td wrap="true">
					<b>{$mod_strings.LBL_DISPLAY_COLUMNS}:</b> {$reportDisplayColumnsList}
				</td>
				<td wrap="true">
					<b>{$mod_strings.LBL_OWNER}:</b> {$reportAssignedToName}
				</td>
			</tr>
			{$summaryAndGroupDefData}
			<tr>
			<tr>
				<td wrap="true" colspan="2">
					<b>{$mod_strings.LBL_REPORT_SCHEDULE_TITLE}:</b> <span id="schduleDateTimeDiv">{$schedule_value}</span>
				</td>
			</tr>
			<tr>
				<td wrap="true" colspan="2">
					<b>{$mod_strings.LBL_FILTERS}:</b>{$reportFilters}
				</td>
			</tr>
			</tr>
		</table>
	</td>
</tr>
<tr>
<td valign="top" width="90%">
<div id="filters_tab" style={$filterTabStyle}>
<div scope="row"><h3>{$mod_strings.LBL_RUNTIME_FILTERS}:<span valign="bottom">&nbsp;{sugar_help text=$mod_strings.LBL_VIEWER_RUNTIME_HELP }</span></h3>
</div>
<input type=hidden name='filters_def' value ="">
<table id='filters_top' border=0 cellpadding="0" cellspacing="0">
<tbody id='filters'></tbody>
</table>
<table>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
		<td>
			<input type=submit class="button" title="{$mod_strings.LBL_REPORT_RUN_WITH_FILTER}"
			    value="{$mod_strings.LBL_REPORT_RUN_WITH_FILTER}"
			    onclick="this.form.to_pdf.value='';this.form.to_csv.value='';this.form.save_report.value='';">
		</td>
		<td>
			<input type=submit class="button" title="{$mod_strings.LBL_REPORT_RESET_FILTER}"
			    value="{$mod_strings.LBL_REPORT_RESET_FILTER}"
			    onclick="this.form.to_pdf.value='';this.form.to_csv.value='';this.form.save_report.value='';this.form.reset_filters.value='1'">
                        <input type="hidden" name="reset_filters" id="reset_filters" value="0">
		</td>
        </tr>
</table>
</div>

</td>

</tr>
</table>
</form>
</p>
<script type="text/javascript" src="cache/modules/modules_def_{$current_language}_{$md5_current_user_id}.js?v=_{$ENTROPY}"></script>
{if !empty($fiscalStartDate)}
<script type="text/javascript" src="cache/modules/modules_def_fiscal_{$current_language}_{$md5_current_user_id}.js?v=_{$ENTROPY}"></script>
{/if}
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script>

var visible_modules;
var report_def;
var current_module;
var visible_fields = new Array();
var visible_fields_map =  new Object();
var visible_summary_fields = new Array();
var visible_summary_field_map =  new Object();
var current_report_type;
var display_columns_ref = 'display';
var hidden_columns_ref = 'hidden';
var field_defs;
var previous_links_map = new Object();
var previous_links =  new Array();
var display_summary_ref = 'display';
var hidden_summary_ref = 'hidden';
var users_array = new Array();

</script>
<script language="javascript">
var image_path = "{$args_image_path}";
var lbl_and = "{$mod_strings.LBL_AND}";
var lbl_select = "{$mod_strings.LBL_SELECT}";
var lbl_remove = "{$mod_strings.LBL_REMOVE}";
var lbl_missing_fields = "{$mod_strings.LBL_MISSING_FIELDS}";
var lbl_at_least_one_display_column = "{$mod_strings.LBL_AT_LEAST_ONE_DISPLAY_COLUMN}";
var lbl_at_least_one_summary_column = "{$mod_strings.LBL_AT_LEAST_ONE_SUMMARY_COLUMN}";
var lbl_missing_input_value  = "{$mod_strings.LBL_MISSING_INPUT_VALUE}";
var lbl_missing_second_input_value = "{$mod_strings.LBL_MISSING_SECOND_INPUT_VALUE}";
var lbl_nothing_was_selected = "{$mod_strings.LBL_NOTHING_WAS_SELECTED}"
var lbl_none = "{$mod_strings.LBL_NONE}";
var lbl_outer_join_checkbox = "{$mod_strings.LBL_OUTER_JOIN_CHECKBOX}";
var lbl_add_related = "{$mod_strings.LBL_ADD_RELATE}";
var lbl_del_this = "{$mod_strings.LBL_DEL_THIS}";
var lbl_alert_cant_add = "{$mod_strings.LBL_ALERT_CANT_ADD}";
var lbl_related_table_blank = "{$mod_strings.LBL_RELATED_TABLE_BLANK}";
var lbl_optional_help = "{$mod_strings.LBL_OPTIONAL_HELP}";
</script>
<script type="text/javascript" src="{sugar_getjspath file='include/javascript/reportCriteria.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='include/javascript/reportsInlineEdit.js'}"></script>
<script language="javascript">
visible_modules = {$allowed_modules_js};
report_def = {$reporter_report_def_str1};
goto_anchor = {$goto_anchor};
{literal}
function report_onload() {
	if (goto_anchor != '') {
		var anch = document.getElementById(goto_anchor);
	  	if ( typeof(anch) != 'undefined' && anch != null) {
	    	anch.focus();
	  	} // if
	} else {
		// no op
	}
} // fn

function showFilterString() {
	if(YAHOO.util.Dom.get('filter_results') && YAHOO.util.Dom.get('filter_results_text')) {
	   filter_span = YAHOO.util.Dom.get('filter_results');
	   filter_results_text_span = YAHOO.util.Dom.get('filter_results_text');
	   expand = (filter_results_text_span.style.visibility == 'hidden') ? true : false;
	   document.getElementById('filter_results_image').src = 'index.php?entryPoint=getImage&themeName=' + SUGAR.themes.theme_name + '&imageName=' + (expand ? 'advanced_search.gif' : 'basic_search.gif');
       filter_results_text_span.innerHTML = '&nbsp;' + (expand ? filterString : '');
       filter_results_text_span.style.visibility = expand ? 'visible' : 'hidden';
	}
} // fn

function schedulePOPUP(){
	var id = document.getElementById('record').value;
	window.open("index.php?module=Reports&action=add_schedule&to_pdf=true&refreshPage=false&id=" + id ,"test","width=650,height=400,resizable=1,scrollbars=1")
}

function performFavAction(actionToPerfrom) {
	var callback = {
        success:function(o){},
        failure:function(o){}
    };
	var postDataString = actionToPerfrom + '=1&report_id=' + document.getElementById('record').value;
	YAHOO.util.Connect.asyncRequest("POST", "index.php?action=ReportCriteriaResults&module=Reports&page=report", callback, postDataString);
	var imageTag = "<img border=\"0\" height='16px' width='11px' align=\"absmiddle\" src=\"" + document.getElementById('blankimagepath').value + "\"/>";

	var favButton = document.getElementById('favButton');
	if (actionToPerfrom == 'addtofavorites') {
		favButton.title = {/literal}"{$app_strings.LBL_REMOVE_FROM_FAVORITES}";
		favButton.value = "{$app_strings.LBL_REMOVE_FROM_FAVORITES}";
		{literal}favButton.onclick = function() {performFavAction('removefromfavorites')};
	} else {
		favButton.title = {/literal}"{$app_strings.LBL_ADD_TO_FAVORITES}";
		favButton.value = "{$app_strings.LBL_ADD_TO_FAVORITES}";
		{literal}favButton.onclick = function() {performFavAction('addtofavorites')};
	} // else
} // fn

function showHideReportDetailsButton() {
	var reportDetailsTable = document.getElementById("reportDetailsTable");
	var showHideReportDetailsButton = document.getElementById("showHideReportDetails");
	if (reportDetailsTable.style.display == "none") {
		saveReportOptionsState("showDetails", "1");
		reportDetailsTable.style.display = "";
		showHideReportDetailsButton.title = {/literal}"{$mod_strings.LBL_REPORT_HIDE_DETAILS}";
		{literal}showHideReportDetailsButton.value = {/literal}"{$mod_strings.LBL_REPORT_HIDE_DETAILS}";{literal}
	} else {
		saveReportOptionsState("showDetails", "0");
		reportDetailsTable.style.display = "none";
		showHideReportDetailsButton.title = {/literal}"{$mod_strings.LBL_REPORT_SHOW_DETAILS}";
		{literal}showHideReportDetailsButton.value = {/literal}"{$mod_strings.LBL_REPORT_SHOW_DETAILS}";{literal}
	} // else
} // fn
function saveReportOptionsState(name, value) {
	var callback = {
        success:function(o){},
        failure:function(o){}
    };
	var postDataString = 'to_pdf=true&report_options=1&report_id=' + document.getElementById('record').value + "&" + name + "=" + value;
	YAHOO.util.Connect.asyncRequest("POST", "index.php?action=ReportCriteriaResults&module=Reports&page=report", callback, postDataString);
} // fn

window.onload = report_onload;
current_module = report_def.module;
field_defs = module_defs[current_module].field_defs;
{/literal}
current_report_type = "{$report_type}";
{literal}
for(var i in report_def.display_columns) {
	visible_fields.push(getFieldKey(report_def.display_columns[i]));
    visible_fields_map[getFieldKey(report_def.display_columns[i])] = report_def.display_columns[i];
} // for

for(var i in report_def.summary_columns) {
	if (typeof(report_def.summary_columns[i].is_group_by) != 'undefined' && report_def.summary_columns[i].is_group_by == 'hidden') {
    continue;
  	} // if
  	visible_summary_fields.push(getFieldKey(report_def.summary_columns[i]));
  	visible_summary_field_map[getFieldKey(report_def.summary_columns[i])] = report_def.summary_columns[i];
} // for


for(var i in report_def.links_def) {
    previous_links_map[ report_def.links_def[i] ] = 1;
	previous_links.push( report_def.links_def[i]);
} // for

function load_page() {
	displayGroupCount();
	reload_joins();
    //current_module = document.EditView.self.options[document.EditView.self.options.selectedIndex].value;
    //reload_join_rows('regular');
    all_fields = getAllFieldsMapped(current_module);
    if(form_submit != "true")
    {
        //remakeGroups();
        //reload_groups();
        reload_actual_filters();
    }
    loadChartForReports();
    doExpandCollapseAll();
    //reload_columns('regular');
}

function doExpandCollapseAll() {

} // fn

function loadChartForReports() {
	var idObject = document.getElementById('record');
	var id = '';
	if (idObject != null) {
		id = idObject.value;
	} // if
	var chartId = document.getElementById(id + '_div');
	var showHideChartButton = document.getElementById('showHideChartButton');
	if (chartId == null && showHideChartButton != null) {
		showHideChartButton.style.display = 'none';
	} // if
} // fn

function setSchuleTime(scheduleDateTime) {
	document.getElementById("schduleDateTimeDiv").innerHTML = scheduleDateTime;
} // fn

function displayGroupCount() {
	// no op
} // fn
{/literal}
var current_user_id = '{$current_user_id}';
users_array[0]={literal}{text{/literal}:'{$mod_strings.LBL_CURRENT_USER}',value:'Current User'{literal}}{/literal};
{foreach from=$user_array key=user_id item=user_name}
{literal}users_array[users_array.length] = {text:{/literal}'{$user_name|escape}',value:'{$user_id}'};
{/foreach}
</script>
<script language="javascript">
if(typeof YAHOO != 'undefined') YAHOO.util.Event.addListener(window, 'load', load_page);
</script>
