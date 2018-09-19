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
{sugar_include include=$includes}
<span id='tabcounterJS'><script>SUGAR.TabFields=new Array();</script></span>
<div id="{{$form_name}}_tabs"
        {{if $useTabs}}
     class="yui-navset"
        {{/if}}
        >
    {{if $useTabs}}
    {* Generate the Tab headers *}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="yui-nav">
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{counter name="tabCount" print=false}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
        <li class="selected"><a id="tab{{$tabCount}}" href="javascript:void({{$tabCount}})"><em>{sugar_translate label='{{$label}}' module='{{$module}}'}</em></a></li>
        {{/if}}
        {{/foreach}}
    </ul>
    {{/if}}
    <div {{if $useTabs}}class="yui-content"{{/if}}>

        {{assign var='tabIndexVal' value=0}}
        {{* Loop through all top level panels first *}}
        {{counter name="panelCount" start=-1 print=false assign="panelCount"}}
        {{counter name="tabCount" start=-1 print=false assign="tabCount"}}

        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{counter name="panelCount" print=false}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
        {{counter name="tabCount" print=false}}
        {{if $tabCount != 0}}</div>{{/if}}
    <div id='tabcontent{{$tabCount}}'>
        {{/if}}

        {{* Print out the table data *}}
        {{if $label == 'DEFAULT'}}
        <div id="detailpanel_{{$smarty.foreach.section.iteration}}" >
            {{else}}
            <div id="detailpanel_{{$smarty.foreach.section.iteration}}" class="{$def.templateMeta.panelClass|default:'edit view edit508'}">
                {{/if}}

                {counter name="panelFieldCount" start=0 print=false assign="panelFieldCount"}
                {{* Check to see if the panel variable is an array, if not, well attempt an include with type param php *}}
                {{* See function.sugar_include.php *}}
                {{if !is_array($panel)}}
                {sugar_include type='php' file='{{$panel}}'}
                {{else}}

                {{* Only show header if it is not default or an int value *}}
                {{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && $showSectionPanelsTitles && (!isset($tabDefs[$label_upper].newTab) || (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false)) && $view != "QuickCreate"}}
                <h4>&nbsp;&nbsp;
                    <a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel({{$smarty.foreach.section.iteration}});">
                        <img border="0" id="detailpanel_{{$smarty.foreach.section.iteration}}_img_hide" src="{sugar_getimagepath file="basic_search.gif"}"></a>
                    <a href="javascript:void(0)" class="expandLink" onclick="expandPanel({{$smarty.foreach.section.iteration}});">
                        <img border="0" id="detailpanel_{{$smarty.foreach.section.iteration}}_img_show" src="{sugar_getimagepath file="advanced_search.gif"}"></a>
                    {sugar_translate label='{{$label}}' module='{{$module}}'}
                    {{if ( isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed" && isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false) }}
                    {{assign var='panelState' value=$tabDefs[$label_upper].panelDefault}}
                    {{else}}
                    {{assign var='panelState' value="expanded"}}
                    {{/if}}
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
                <table width="100%" border="0" cellspacing="1" cellpadding="0" {{if $label == 'DEFAULT'}} id='Default_{$module}_Subpanel' {{else}} id='{{$label}}' {{/if}} class="edit view panelContainer">


                    {{assign var='rowCount' value=0}}
                    {{assign var='ACCKEY' value=''}}
                    {{foreach name=rowIteration from=$panel key=row item=rowData}}
                    {counter name="fieldsUsed" start=0 print=false assign="fieldsUsed"}
                    {capture name="tr" assign="tableRow"}
                    <tr>
                        {{math assign="rowCount" equation="$rowCount + 1"}}

                        {{assign var='columnsInRow' value=$rowData|@count}}
                        {{assign var='columnsUsed' value=0}}

                        {{* Loop through each column and display *}}
                        {{counter name="colCount" start=0 print=false assign="colCount"}}

                        {{foreach name=colIteration from=$rowData key=col item=colData}}

                        {{counter name="colCount" print=false}}

                        {{if count($rowData) == $colCount}}
                        {{assign var="colCount" value=0}}
                        {{/if}}

                        {{if !empty($colData.field.hideIf)}}
                        {if !({{$colData.field.hideIf}}) }
                            {{/if}}
                        {{if !empty($colData.field.name)}}
                        {if $fields.{{$colData.field.name}}.acl > 1 || ($showDetailData && $fields.{{$colData.field.name}}.acl > 0)}
                            {{/if}}

                        {{if empty($def.templateMeta.labelsOnTop) && empty($colData.field.hideLabel)}}
                        <td valign="top" id='{{$colData.field.name}}_label' width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope="col">
                            {{if isset($colData.field.customLabel)}}
                            <label for="{{$fields[$colData.field.name].name}}">{{$colData.field.customLabel}}</label>
                            {{elseif isset($colData.field.label)}}
                            {capture name="label" assign="label"}{sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}{/capture}
                            {$label|strip_semicolon}:
                            {{elseif isset($fields[$colData.field.name])}}
                            {capture name="label" assign="label"}{sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}{/capture}
                            {$label|strip_semicolon}:
                            {{else}}
                            &nbsp;
                            {{/if}}
                            {{* Show the required symbol if field is required, but override not set.  Or show if override is set *}}
                            {{if ($fields[$colData.field.name].required && (!isset($colData.field.displayParams.required) || $colData.field.displayParams.required)) ||
                            (isset($colData.field.displayParams.required) && $colData.field.displayParams.required)}}
                            <span class="required" style="box-shadow:none;">{{$APP.LBL_REQUIRED_SYMBOL}}</span>
                            {{/if}}
                            {{if isset($colData.field.popupHelp) || isset($fields[$colData.field.name]) && isset($fields[$colData.field.name].popupHelp) }}
                            {{if isset($colData.field.popupHelp) }}
{capture name="popupText" assign="popupText"}{sugar_translate label="{{$colData.field.popupHelp}}" module='{{$module}}'}{/capture}
              {{elseif isset($fields[$colData.field.name].popupHelp)}}
                {capture name="popupText" assign="popupText"}{sugar_translate label="{{$fields[$colData.field.name].popupHelp}}" module='{{$module}}'}{/capture}
              {{/if}}
              {sugar_help text=$popupText WIDTH=-1}
            {{/if}}

		</td>

		{{/if}}
		{counter name="fieldsUsed"}
		{{math assign="tabIndexVal" equation="$tabIndexVal + 1"}}
		{{if $tabIndexVal==1}} {{assign var='ACCKEY' value=$APP.LBL_FIRST_INPUT_EDIT_VIEW_KEY}}{{else}}{{assign var='ACCKEY' value=''}}{{/if}}
		{{if !empty($colData.field.tabindex)  && $colData.field.tabindex !=0}}
		    {{assign var='tabindex' value=$colData.field.tabindex}}
            {{** instead of tracking tabindex values for all fields, just track for email as email does not get created directly from
                a tpl that has access to smarty values.  Email gets created through addEmailAddress() function in SugarEmailAddress.js
                which will use the value in tabFields array
             **}}
            {{if $colData.field.name == 'email1'}}<script>SUGAR.TabFields['{{$colData.field.name}}'] = '{{$tabindex}}';</script>{{/if}}
		{{else}}
		    {** if not explicitly assigned, we will default to 0 for 508 compliance reasons, instead of the calculated tabIndexVal value **}
		    {{assign var='tabindex' value=0}}
		{{/if}}
		<td valign="top" width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].field}}%' {{if $colData.colspan}}colspan='{{$colData.colspan}}'{{/if}}>
			{{if !empty($def.templateMeta.labelsOnTop)}}
				{{if isset($colData.field.label)}}
				    {{if !empty($colData.field.label)}}
			   		    <label for="{{$fields[$colData.field.name].name}}">{sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}:</label>
				    {{/if}}
				{{elseif isset($fields[$colData.field.name])}}
			  		<label for="{{$fields[$colData.field.name].name}}">{sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}:</label>
				{{/if}}

				{{* Show the required symbol if field is required, but override not set.  Or show if override is set *}}
				{{if ($fields[$colData.field.name].required && (!isset($colData.field.displayParams.required) || $colData.field.displayParams.required)) ||
				     (isset($colData.field.displayParams.required) && $colData.field.displayParams.required)}}
				    <span class="required" title="{{$APP.LBL_REQUIRED_TITLE}}">{{$APP.LBL_REQUIRED_SYMBOL}}</span>
				{{/if}}
				{{if !isset($colData.field.label) || !empty($colData.field.label)}}
				<br>
				{{/if}}
			{{/if}}

		{{$colData.field.prefix}}
		{{if !empty($colData.field.name)}}
			{if $fields.{{$colData.field.name}}.acl > 1}
		{{/if}}

			{{if $fields[$colData.field.name] && !empty($colData.field.fields) }}
			    {{foreach from=$colData.field.fields item=subField}}
			        {{if $fields[$subField.name]}}
			        	{counter name="panelFieldCount"}
			            {{sugar_field parentFieldArray='fields'  accesskey=$ACCKEY tabindex=$tabindex vardef=$fields[$subField.name] displayType=$fields[$subField.name].viewType displayParams=$subField.displayParams formName=$form_name module=$module}}&nbsp;
			        {{/if}}
			    {{/foreach}}
			{{elseif !empty($colData.field.customCode) && empty($colData.field.customCodeRenderField)}}
				{counter name="panelFieldCount"}
                                {{if $fields[$colData.field.name].viewType=='DetailView'}}
                                    {{sugar_field parentFieldArray='fields'  accesskey=$ACCKEY tabindex=$tabindex vardef=$fields[$colData.field.name] displayType='DetailView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type formName=$form_name module=$module}}
                                {{else}}
                                    {{sugar_evalcolumn var=$colData.field.customCode colData=$colData  accesskey=$ACCKEY tabindex=$tabindex}}
                                {{/if}}

			{{elseif $fields[$colData.field.name]}}
				{counter name="panelFieldCount"}
			    {{$colData.displayParams}}
                                {{sugar_field parentFieldArray='fields'  accesskey=$ACCKEY tabindex=$tabindex vardef=$fields[$colData.field.name] displayType=$fields[$colData.field.name].viewType displayParams=$colData.field.displayParams typeOverride=$colData.field.type formName=$form_name module=$module}}
			{{/if}}
		{{if !empty($colData.field.name)}}
		{{if $showDetailData }}
		{else}
			{{if $fields[$colData.field.name] && !empty($colData.field.fields) }}
			    {{foreach from=$colData.field.fields item=subField}}
			        {{if $fields[$subField.name]}}
                                    {{sugar_field parentFieldArray='fields' tabindex=$tabindex vardef=$fields[$subField.name] displayType='DetailView' displayParams=$subField.displayParams formName=$form_name module=$module}}&nbsp;
			        {{/if}}
			    {{/foreach}}
			{{elseif !empty($colData.field.customCode)}}
                {{if !empty($colData.field.customCodeReadOnly)}}
                   {{$colData.field.customCodeReadOnly}}
                {{/if}}
                </td>
				<td></td><td></td>
			{{elseif $fields[$colData.field.name]}}
			    {{$colData.displayParams}}
			    {counter name="panelFieldCount"}
				{{sugar_field parentFieldArray='fields' tabindex=$tabindex vardef=$fields[$colData.field.name] displayType='DetailView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type formName=$form_name module=$module}}
			{{/if}}
	    {{$colData.field.suffix}}
		{{if !empty($colData.field.customCode)}}</td>{{/if}}
		{{/if}}

		{/if}

		{else}

		  <td></td><td></td>

	{/if}

	{{else}}

		</td>
	{{/if}}
	{{if !empty($colData.field.customCode) && !empty($colData.field.customCodeRenderField)}}
	    {counter name="panelFieldCount"}
	    {{sugar_evalcolumn var=$colData.field.customCode colData=$colData tabindex=$tabindex}}
    {{/if}}
    {{if !empty($colData.field.hideIf)}}
		{else}
		<td></td><td></td>
		{/if}
    {{/if}}

	{{/foreach}}
</tr>
{/capture}
{if $fieldsUsed > 0 }
{$tableRow}
{/if}
{{/foreach}}
</table>
{{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && $showSectionPanelsTitles && (!isset($tabDefs[$label_upper].newTab) || (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == false)) && $view != "QuickCreate"}}
<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() {ldelim} initPanel({{$smarty.foreach.section.iteration}}, '{{$panelState}}'); {rdelim}); </script>
{{/if}}

{{/if}}

</div>
{if $panelFieldCount == 0}

<script>document.getElementById("{{$label}}").style.display='none';</script>
{/if}
{{/foreach}}
</div></div>

{{if $useTabs}}
{sugar_getscript file="cache/include/javascript/sugar_grp_yui_widgets.js"}
<script type="text/javascript">
var {{$form_name}}_tabs = new YAHOO.widget.TabView("{{$form_name}}_tabs");
{{$form_name}}_tabs.selectTab(0);
</script>
{{/if}}
<script type="text/javascript">
YAHOO.util.Event.onContentReady(
    "{{$form_name}}",
    function () {ldelim} {rdelim}
);
//window.setTimeout(, 100);

// bug 55468 -- IE is too aggressive with onUnload event
{{literal}}
if ($.browser.msie){{/literal}} {ldelim}
{{literal}}$(document).ready(function() {{/literal}} {ldelim}
   {{literal}} $(".collapseLink,.expandLink").click(function (e) {{/literal}} {ldelim} e.preventDefault(); {rdelim});
  {rdelim});
{rdelim}
</script>
{{if $detailView == false}}
<script>
    {foreach from=$readOnlyFields item=fd}
        if ((document.getElementById('{$fd}') != null) )
            document.getElementById('{$fd}').disabled = 'true';
    {/foreach}
</script>
{{/if}}
{{if isset($footerTpl)}}
{{include file=$footerTpl}}
{{/if}}
<script src="{sugar_getjspath file='include/javascript/select2/select2.js'}"></script>
<link rel="stylesheet" href="{sugar_getjspath file='include/javascript/select2/select2.css'}"/>
