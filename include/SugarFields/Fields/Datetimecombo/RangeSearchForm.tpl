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
{{if empty($displayParams.idName)}}
{assign var="id" value={{sugarvar key='name' string=true}} }
{{else}}
{assign var="id" value={{$displayParams.idName}} }
{{/if}}

{if isset($smarty.request.{{$id_range_choice}})}
{assign var="starting_choice" value=$smarty.request.{{$id_range_choice}}}
{else}
{assign var="starting_choice" value="="}
{/if}

<div style="white-space:nowrap !important;">
<select id="{$id}_range_choice" name="{$id}_range_choice" style="width:125px !important;" onchange="{$id}_range_change(this.value);">
{html_options options={{sugarvar key='options' string=true}} selected=$starting_choice}
</select>
</div>

<div id="{$id}_range_div" style="{if preg_match('/^\[/', $smarty.request.{{$id_range}})  || $starting_choice == 'between'}display:none{else}display:''{/if};">
<input autocomplete="off" type="text" name="range_{$id}" id="range_{$id}" value='{if empty($smarty.request.{{$id_range}}) && !empty($smarty.request.{{$original_id}})}{$smarty.request.{{$original_id}}}{else}{$smarty.request.{{$id_range}}}{/if}' title='{{$vardef.help}}' {{$displayParams.field}} {{if !empty($tabindex)}} tabindex='{{$tabindex}}' {{/if}} size="11" style="width:100px !important;">
{{if !$displayParams.hiddeCalendar}}
{capture assign="other_attributes"}alt="{$APP.LBL_ENTER_DATE}" style="position:relative; top:6px" border="0" id="{$id}_trigger"{/capture}
{sugar_getimage name="jscalendar" ext=".gif" other_attributes="$other_attributes"}
{{/if}}
{{if $displayParams.showFormats}}
&nbsp;(<span class="dateFormat">{$USER_DATEFORMAT}</span>)
{{/if}}
{{if !$displayParams.hiddeCalendar}}
<script type="text/javascript">
Calendar.setup ({ldelim}
inputField : "range_{$id}",
daFormat : "{$CALENDAR_FORMAT}",
button : "{$id}_trigger",
singleClick : true,
dateStr : "{$date_value}",
startWeekday: {$CALENDAR_FDOW|default:'0'},
step : 1,
weekNumbers:false
{rdelim}
);
</script>
{{/if}}    
</div>

<div id="{$id}_between_range_div" style="{if $starting_choice=='between'}display:'';{else}display:none;{/if}">
{assign var=date_value value={{sugarvar key='value' string=true}} }
<input autocomplete="off" type="text" name="start_range_{$id}" id="start_range_{$id}" value='{$smarty.request.{{$id_range_start}} }' title='{{$vardef.help}}' {{$displayParams.field}} tabindex='{{$tabindex}}' size="11" style="width:100px !important;">
{{if !$displayParams.hiddeCalendar}}
{capture assign="other_attributes"}align="absmiddle" border="0" id="start_range_{$id}_trigger"{/capture}
{sugar_getimage name="jscalendar" ext=".gif" alt=$APP.LBL_ENTER_DATE|escape:'html' other_attributes=$other_attributes}
{{/if}}
{{if $displayParams.showFormats}}
&nbsp;(<span class="dateFormat">{$USER_DATEFORMAT}</span>)
{{/if}}
{{if !$displayParams.hiddeCalendar}}
<script type="text/javascript">
Calendar.setup ({ldelim}
inputField : "start_range_{$id}",
daFormat : "{$CALENDAR_FORMAT}",
button : "start_range_{$id}_trigger",
singleClick : true,
dateStr : "{$date_value}",
step : 1,
weekNumbers:false
{rdelim}
);
</script>
{{/if}} 
{$APP.LBL_AND}
{assign var=date_value value={{sugarvar key='value' string=true}} }
<input autocomplete="off" type="text" name="end_range_{$id}" id="end_range_{$id}" value='{$smarty.request.{{$id_range_end}} }' title='{{$vardef.help}}' {{$displayParams.field}} tabindex='{{$tabindex}}' size="11" style="width:100px !important;" maxlength="10">
{{if !$displayParams.hiddeCalendar}}
{capture assign="other_attributes"}align="absmiddle" border="0" id="end_range_{$id}_trigger"{/capture}
{sugar_getimage name="jscalendar" ext=".gif" alt=$APP.LBL_ENTER_DATE|escape:'html' other_attributes=$other_attributes}
{{/if}}
{{if $displayParams.showFormats}}
&nbsp;(<span class="dateFormat">{$USER_DATEFORMAT}</span>)
{{/if}}
{{if !$displayParams.hiddeCalendar}}
<script type="text/javascript">
Calendar.setup ({ldelim}
inputField : "end_range_{$id}",
daFormat : "{$CALENDAR_FORMAT}",
button : "end_range_{$id}_trigger",
singleClick : true,
dateStr : "{$date_value}",
step : 1,
weekNumbers:false
{rdelim}
);
</script>
{{/if}} 
</div>


<script type='text/javascript'>

function {$id}_range_change(val) 
{ldelim}
  if(val == 'between') {ldelim}
     document.getElementById("range_{$id}").value = '';  
     document.getElementById("{$id}_range_div").style.display = 'none';
     document.getElementById("{$id}_between_range_div").style.display = ''; 
  {rdelim} else if (val == '=' || val == 'not_equal' || val == 'greater_than' || val == 'less_than') {ldelim}
     if((/^\[.*\]$/).test(document.getElementById("range_{$id}").value))
     {ldelim}
     	document.getElementById("range_{$id}").value = '';
     {rdelim}
     document.getElementById("start_range_{$id}").value = '';
     document.getElementById("end_range_{$id}").value = '';
     document.getElementById("{$id}_range_div").style.display = '';
     document.getElementById("{$id}_between_range_div").style.display = 'none';
  {rdelim} else {ldelim}
     if (val != '') {ldelim}
        val = '[' + val + ']';
     {rdelim}
     document.getElementById("range_{$id}").value = val;
     document.getElementById("start_range_{$id}").value = '';
     document.getElementById("end_range_{$id}").value = ''; 
     document.getElementById("{$id}_range_div").style.display = 'none';
     document.getElementById("{$id}_between_range_div").style.display = 'none';         
  {rdelim}
{rdelim}

var {$id}_range_reset = function()
{ldelim}
{$id}_range_change('=');
{rdelim}

YAHOO.util.Event.onDOMReady(function() {ldelim}
if(document.getElementById('search_form_clear'))
{ldelim}
YAHOO.util.Event.addListener('search_form_clear', 'click', {$id}_range_reset);
{rdelim}

{rdelim});

YAHOO.util.Event.onDOMReady(function() {ldelim}
 	if(document.getElementById('search_form_clear_advanced'))
 	 {ldelim}
 	     YAHOO.util.Event.addListener('search_form_clear_advanced', 'click', {$id}_range_reset);
 	 {rdelim}

{rdelim});

</script>
