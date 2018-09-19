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

<table border="0" cellpadding="0" cellspacing="0">
<tr valign="middle">
<td nowrap>
<div id="{{$idname}}_time"></div>
{{if $displayParams.showFormats}}
<span class="timeFormat">{$TIME_FORMAT}</span>
{{/if}}
</td>
</tr>
</table>
<input type="hidden" id="{{$idname}}" name="{{$idname}}" value="{$fields[{{sugarvar key='name' stringFormat=true}}].value}">
<script type="text/javascript" src="{sugar_getjspath file='include/SugarFields/Fields/Time/Time.js'}"></script>
<script type="text/javascript">

//cleanup because this happens in a screwy order in a quickcreate, and the standard $(document).ready and YUI functions don't work quite right
var timeclosure_{{$idname}} = function(){ldelim}
	var idname = "{{$idname}}";
	var timeField = "{$fields[{{sugarvar key='name' stringFormat=true}}].value}";
	var timeFormat = "{$fields[{{sugarvar key='name' stringFormat=true}}].value}";
	var tabIndex = "{{$tabindex}}";
	var callback = "{{$displayParams.updateCallback}}";
	
	{literal}
	
	SUGAR.util.doWhen(typeof(Time) != "undefined", function(){
		var combo = new Time(timeField, idname, timeFormat, tabIndex);
		//Render the remaining widget fields
		var text = combo.html(callback);
		document.getElementById(idname + "_time").innerHTML = text;	
	});
	{/literal}
{rdelim}
timeclosure_{{$idname}}();
</script>

<script type="text/javascript">
function update_{{$idname}}_available() {ldelim}
      YAHOO.util.Event.onAvailable("{{$idname}}_time_hours", this.handleOnAvailable, this);
{rdelim}

update_{{$idname}}_available.prototype.handleOnAvailable = function(me) {ldelim}
	//Call update for first time to round hours and minute values
	combo_{{$idname}}.update();
{rdelim}

var obj_{{$idname}} = new update_{{$idname}}_available();
</script>