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
{{capture name=idname assign=idname}}{{sugarvar key='name'}}{{/capture}}
{{if !empty($displayParams.idName)}}
    {{assign var=idname value=$displayParams.idName}}
{{/if}}

{{assign var=flag_field value=$vardef.name|cat:_flag}}
<table border="0" cellpadding="0" cellspacing="0" class="dateTime">
<tr valign="middle">
<td nowrap>
<input autocomplete="off" type="text" id="{{$idname}}_date" value="{$fields[{{sugarvar key='name' stringFormat=true}}].value}" size="11" maxlength="10" title='{{$vardef.help}}' tabindex="{{$tabindex}}" onblur="combo_{{$idname}}.update();" onchange="combo_{{$idname}}.update(); {{if isset($displayParams.updateCallback)}}{{$displayParams.updateCallback}}{{/if}}"   {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}} >
{capture assign="other_attributes"}alt="{$APP.LBL_ENTER_DATE}" style="position:relative; top:6px" border="0" id="{{$idname}}_trigger"{/capture}
{sugar_getimage name="jscalendar" ext=".gif" other_attributes="$other_attributes"}&nbsp;
{{if empty($displayParams.splitDateTime)}}
</td>
<td nowrap>
{{else}}
<br>
{{/if}}
<div id="{{$idname}}_time_section"></div>
{{if $displayParams.showNoneCheckbox}}
<script type="text/javascript">
function set_{{$idname}}_values(form) {ldelim}
 if(form.{{$idname}}_flag.checked)  {ldelim}
	form.{{$idname}}_flag.value=1;
	form.{{$idname}}.value="";
	form.{{$idname}}.readOnly=true;
 {rdelim} else  {ldelim}
	form.{{$idname}}_flag.value=0;
	form.{{$idname}}.readOnly=false;
 {rdelim}
{rdelim}
</script>
{{/if}}
</td>
</tr>
{{if $displayParams.showFormats}}
<tr valign="middle">
<td nowrap>
<span class="dateFormat">{$USER_DATEFORMAT}</span>
</td>
<td nowrap>
<span class="dateFormat">{$TIME_FORMAT}</span>
</td>
</tr>
{{/if}}
</table>
<input type="hidden" class="DateTimeCombo" id="{{$idname}}" name="{{$idname}}" value="{$fields[{{sugarvar key='name' stringFormat=true}}].value}">
<script type="text/javascript" src="{sugar_getjspath file="include/SugarFields/Fields/Datetimecombo/Datetimecombo.js"}"></script>
<script type="text/javascript">
var combo_{{$idname}} = new Datetimecombo("{$fields[{{sugarvar key='name' stringFormat=true}}].value}", "{{$idname}}", "{$TIME_FORMAT}", "{{$tabindex}}", '{{$displayParams.showNoneCheckbox}}', false, true);
//Render the remaining widget fields
text = combo_{{$idname}}.html('{{$displayParams.updateCallback}}');
document.getElementById('{{$idname}}_time_section').innerHTML = text;

//Call eval on the update function to handle updates to calendar picker object
eval(combo_{{$idname}}.jsscript('{{$displayParams.updateCallback}}'));

//bug 47718: this causes too many addToValidates to be called, resulting in the error messages being displayed multiple times
//    removing it here to mirror the Datetime SugarField, where the validation is not added at this level
//addToValidate('{$form_name}',"{{$idname}}_date",'date',false,"{{$idname}}");
addToValidateBinaryDependency('{$form_name}',"{{$idname}}_hours", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_HOURS}" ,"{{$idname}}_date");
addToValidateBinaryDependency('{$form_name}', "{{$idname}}_minutes", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_MINUTES}" ,"{{$idname}}_date");
addToValidateBinaryDependency('{$form_name}', "{{$idname}}_meridiem", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_MERIDIEM}","{{$idname}}_date");

YAHOO.util.Event.onDOMReady(function()
{ldelim}

	Calendar.setup ({ldelim}
	onClose : update_{{$idname}},
	inputField : "{{$idname}}_date",
	ifFormat : "{$CALENDAR_FORMAT}",
	daFormat : "{$CALENDAR_FORMAT}",
	button : "{{$idname}}_trigger",
	singleClick : true,
	step : 1,
	weekNumbers: false,
        startWeekday: {$CALENDAR_FDOW|default:'0'},
	comboObject: combo_{{$idname}}
	{rdelim});

	//Call update for first time to round hours and minute values
	combo_{{$idname}}.update(false);

{rdelim}); 
</script>
