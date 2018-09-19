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
{{* If templateMeta.maxColumnsBasic is not set, use maxColumns *}}
{if !isset($templateMeta.maxColumnsBasic)}
	{assign var="basicMaxColumns" value=$templateMeta.maxColumns}
{else}
    {assign var="basicMaxColumns" value=$templateMeta.maxColumnsBasic}
{/if}
<script>
{literal}
	$(function() {
	var $dialog = $('<div></div>')
		.html(SUGAR.language.get('app_strings', 'LBL_SEARCH_HELP_TEXT'))
		.dialog({
			autoOpen: false,
			title: SUGAR.language.get('app_strings', 'LBL_HELP'),
			width: 700
		});
		
		$('#filterHelp').click(function() {
		$dialog.dialog('open');
		// prevent the default action, e.g., following a link
	});
	
	});
{/literal}
</script>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
{{assign var='accesskeycount' value=0}}  {{assign var='ACCKEY' value=''}}
{{foreach name=colIteration from=$formData key=col item=colData}}
    {{math assign="accesskeycount" equation="$accesskeycount + 1"}}
    {{if $accesskeycount==1}} {{assign var='ACCKEY' value=$APP.LBL_FIRST_INPUT_SEARCH_KEY}} {{else}} {{assign var='ACCKEY' value=''}} {{/if}}

	{{if !empty($colData.field.name)}}
	
		{if $fields.{{$colData.field.name}}.acl > 0}
	{{/if}}
	{counter assign=index}
	{math equation="left % right"
   		  left=$index
          right=$basicMaxColumns
          assign=modVal
    }
	{if ($index % $basicMaxColumns == 1 && $index != 1)}
		</tr><tr>
	{/if}
	
	<td scope="row" nowrap="nowrap" width='1%' >
	{{if isset($colData.field.label)}}	
		<label for='{{$colData.field.name}}' >{sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}</label>
    {{elseif isset($fields[$colData.field.name])}}
		<label for='{{$fields[$colData.field.name].name}}'> {sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}
	{{/if}}
	</td>

	
	<td  nowrap="nowrap" width='1%'>
	{{if $fields[$colData.field.name]}}
		{{sugar_field parentFieldArray='fields' vardef=$fields[$colData.field.name] accesskey=$ACCKEY displayType='searchView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type formName=$form_name}}
   	{{/if}}
		{{if !empty($colData.field.name)}}
			{/if}
		{{/if}}
   	</td>
{{/foreach}}
    {if $formData|@count >= $basicMaxColumns+1}
    </tr>
    <tr>
	<td colspan="{$searchTableColumnCount}">
    {else}
	<td class="sumbitButtons">
    {/if}
        {{sugar_button module="$module" id="search" view="searchView"}}
	    <input tabindex='2' title='{$APP.LBL_CLEAR_BUTTON_TITLE}' onclick='SUGAR.searchForm.clear_form(this.form); return false;' class='button' type='button' name='clear' id='search_form_clear' value='{$APP.LBL_CLEAR_BUTTON_LABEL}'/>
        {if $HAS_ADVANCED_SEARCH}
	    &nbsp;&nbsp;<a id="advanced_search_link" onclick="SUGAR.searchForm.searchFormSelect('{$module}|advanced_search','{$module}|basic_search')" href="javascript:void(0);" accesskey="{$APP.LBL_ADV_SEARCH_LNK_KEY}" >{$APP.LNK_ADVANCED_SEARCH}</a>
	    {/if}
    </td>
	<td class="helpIcon" width="*"><img alt="Help" border='0' id="filterHelp" src='{sugar_getimagepath file="help-dashlet.gif"}'></td>
	</tr>
</table>
