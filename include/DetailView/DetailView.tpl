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
{{include file=$headerTpl}}
{sugar_include include=$includes}
<div id="{{$module}}_detailview_tabs"
{{if $useTabs}}
class="yui-navset detailview_tabs"
{{/if}}
>
    {{if $useTabs}}
    {* Generate the Tab headers *}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="yui-nav">
    {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* override from tab definitions *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
            {{counter name="tabCount" print=false}}
            <li><a id="tab{{$tabCount}}" href="javascript:void(0)"><em>{sugar_translate label='{{$label}}' module='{{$module}}'}</em></a></li>
        {{/if}}
    {{/foreach}}
    </ul>
    {{/if}}
    <div {{if $useTabs}}class="yui-content"{{/if}}>
{{* Loop through all top level panels first *}}
{{counter name="panelCount" print=false start=0 assign="panelCount"}}
{{counter name="tabCount" start=-1 print=false assign="tabCount"}}
{{foreach name=section from=$sectionPanels key=label item=panel}}
{{assign var='panel_id' value=$panelCount}}
{{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
  {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
    {{counter name="tabCount" print=false}}
    {{if $tabCount != 0}}</div>{{/if}}
    <div id='tabcontent{{$tabCount}}'>
  {{/if}}

    {{if ( isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed" && isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false) }}
        {{assign var='panelState' value=$tabDefs[$label_upper].panelDefault}}
    {{else}}
        {{assign var='panelState' value="expanded"}}
    {{/if}}
<div id='detailpanel_{{$smarty.foreach.section.iteration}}' class='detail view  detail508 {{$panelState}}'>
{counter name="panelFieldCount" start=0 print=false assign="panelFieldCount"}
{{* Print out the panel title if one exists*}}

{{* Check to see if the panel variable is an array, if not, we'll attempt an include with type param php *}}
{{* See function.sugar_include.php *}}
{{if !is_array($panel)}}
    {sugar_include type='php' file='{{$panel}}'}
{{else}}

    {{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && (!isset($tabDefs[$label_upper].newTab) || (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false))}}
    <h4>
      <a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel({{$smarty.foreach.section.iteration}});">
      <img border="0" id="detailpanel_{{$smarty.foreach.section.iteration}}_img_hide" src="{sugar_getimagepath file="basic_search.gif"}"></a>
      <a href="javascript:void(0)" class="expandLink" onclick="expandPanel({{$smarty.foreach.section.iteration}});">
      <img border="0" id="detailpanel_{{$smarty.foreach.section.iteration}}_img_show" src="{sugar_getimagepath file="advanced_search.gif"}"></a>
      {sugar_translate label='{{$label}}' module='{{$module}}'}
    {{if isset($panelState) && $panelState == 'collapsed'}}
    <script>
      document.getElementById('detailpanel_{{$smarty.foreach.section.iteration}}').className += ' collapsed';
    </script>
    {{else}}
    <script>
      document.getElementById('detailpanel_{{$smarty.foreach.section.iteration}}').className += ' expanded';
    </script>
    {{/if}}
    </h4>

    {{/if}}
	{{* Print out the table data *}}
  <table id='{{$label}}' class="panelContainer" cellspacing='{$gridline}'>



	{{foreach name=rowIteration from=$panel key=row item=rowData}}
	{counter name="fieldsUsed" start=0 print=false assign="fieldsUsed"}
	{counter name="fieldsHidden" start=0 print=false assign="fieldsHidden"}
	{capture name="tr" assign="tableRow"}
	<tr>
		{{assign var='columnsInRow' value=$rowData|@count}}
		{{assign var='columnsUsed' value=0}}
		{{foreach name=colIteration from=$rowData key=col item=colData}}
	    {{if !empty($colData.field.hideIf)}}
	    	{if !({{$colData.field.hideIf}}) }
	    {{/if}}
	    {{if !empty($colData.field.name)}}
			{if $fields.{{$colData.field.name}}.acl > 0}
		{{/if}}
			{counter name="fieldsUsed"}
			{{if empty($colData.field.hideLabel)}}
			<td width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope="col">
				{{if !empty($colData.field.name)}}
				    {if !$fields.{{$colData.field.name}}.hidden}
                {{/if}}
				{{if isset($colData.field.customLabel)}}
			       {{$colData.field.customLabel}}
				{{elseif isset($colData.field.label) && strpos($colData.field.label, '$')}}
				   {capture name="label" assign="label"}{{$colData.field.label}}{/capture}
			       {$label|strip_semicolon}:
				{{elseif isset($colData.field.label)}}
				   {capture name="label" assign="label"}{sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}{/capture}
			       {$label|strip_semicolon}:
				{{elseif isset($fields[$colData.field.name])}}
				   {capture name="label" assign="label"}{sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}{/capture}
			       {$label|strip_semicolon}:
				{{else}}
				   &nbsp;
				{{/if}}
                {{if isset($colData.field.popupHelp) || isset($fields[$colData.field.name]) && isset($fields[$colData.field.name].popupHelp) }}
                   {{if isset($colData.field.popupHelp) }}
                     {capture name="popupText" assign="popupText"}{sugar_translate label="{{$colData.field.popupHelp}}" module='{{$module}}'}{/capture}
                   {{elseif isset($fields[$colData.field.name].popupHelp)}}
                     {capture name="popupText" assign="popupText"}{sugar_translate label="{{$fields[$colData.field.name].popupHelp}}" module='{{$module}}'}{/capture}
                   {{/if}}
                   {sugar_help text=$popupText WIDTH=400}
                {{/if}}
                {{if !empty($colData.field.name)}}
                {else}
                    {counter name="fieldsHidden"}
                {/if}
                {{/if}}
                {{/if}}
			</td>
			<td width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].field}}%' {{if $colData.colspan}}colspan='{{$colData.colspan}}'{{/if}} {{if isset($fields[$colData.field.name].type) && $fields[$colData.field.name].type == 'phone'}}class="phone"{{/if}}>
			    {{if !empty($colData.field.name)}}
			    {if !$fields.{{$colData.field.name}}.hidden}
			    {{/if}}
				{{$colData.field.prefix}}
				{{if ($colData.field.customCode && !$colData.field.customCodeRenderField) || $colData.field.assign}}
					{counter name="panelFieldCount"}
					<span id="{{$colData.field.name}}" class="sugar_field">{{sugar_evalcolumn var=$colData.field colData=$colData}}</span>
				{{elseif $fields[$colData.field.name] && !empty($colData.field.fields) }}
				    {{foreach from=$colData.field.fields item=subField}}
				        {{if $fields[$subField]}}
				        	{counter name="panelFieldCount"}
				            {{sugar_field parentFieldArray='fields' tabindex=$tabIndex vardef=$fields[$subField] displayType='DetailView'}}&nbsp;
				        {{else}}
				        	{counter name="panelFieldCount"}
				            {{$subField}}
				        {{/if}}
				    {{/foreach}}
				{{elseif $fields[$colData.field.name]}}
					{counter name="panelFieldCount"}
					{{sugar_field parentFieldArray='fields' vardef=$fields[$colData.field.name] displayType='DetailView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type}}
				{{/if}}
				{{if !empty($colData.field.customCode) && $colData.field.customCodeRenderField}}
				    {counter name="panelFieldCount"}
				    <span id="{{$colData.field.name}}" class="sugar_field">{{sugar_evalcolumn var=$colData.field colData=$colData}}</span>
                {{/if}}
				{{$colData.field.suffix}}
				{{if !empty($colData.field.name)}}
				{/if}
				{{/if}}
			</td>
		{{if !empty($colData.field.name)}}
			{else}

			<td>&nbsp;</td><td>&nbsp;</td>
			{/if}
		{{/if}}
		{{if !empty($colData.field.hideIf)}}
			{else}

			<td>&nbsp;</td><td>&nbsp;</td>
			{/if}
	    {{/if}}
		{{/foreach}}
	</tr>
	{/capture}
	{if $fieldsUsed > 0 && $fieldsUsed != $fieldsHidden}
	{$tableRow}
	{/if}
	{{/foreach}}
	</table>
    {{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && (!isset($tabDefs[$label_upper].newTab) || (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false))}}
    <script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() {ldelim} initPanel({{$smarty.foreach.section.iteration}}, '{{$panelState}}'); {rdelim}); </script>
    {{/if}}
{{/if}}
</div>
{if $panelFieldCount == 0}

<script>document.getElementById("{{$label}}").style.display='none';</script>
{/if}
{{/foreach}}
{{if $useTabs}}
  </div>
{{/if}}

</div>
</div>
{{include file=$footerTpl}}
{{if $useTabs}}
<script type='text/javascript' src='{sugar_getjspath file='include/javascript/popup_helper.js'}'></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
<script type="text/javascript">
var {{$module}}_detailview_tabs = new YAHOO.widget.TabView("{{$module}}_detailview_tabs");
{{$module}}_detailview_tabs.selectTab(0);
</script>
{{/if}}
<script type="text/javascript">
SUGAR.util.doWhen("typeof collapsePanel == 'function'",
        function(){ldelim}
            var sugar_panel_collase = Get_Cookie("sugar_panel_collase");
            if(sugar_panel_collase != null) {ldelim}
                sugar_panel_collase = YAHOO.lang.JSON.parse(sugar_panel_collase);
                for(panel in sugar_panel_collase['{{$module}}_d'])
                    if(sugar_panel_collase['{{$module}}_d'][panel])
                        collapsePanel(panel);
                    else
                        expandPanel(panel);
            {rdelim}
        {rdelim});
</script>
