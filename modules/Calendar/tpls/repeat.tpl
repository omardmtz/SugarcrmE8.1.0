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

<div id="edit_all_recurrences_block" style="display: none; margin-bottom: 10px;">
	<button type="button" id="btn-edit-all-recurrences" onclick="CAL.editAllRecurrences();"> {$MOD.LBL_EDIT_ALL_RECURRENCES} </button>
	<button type="button" id="btn-remove-all-recurrences" onclick="CAL.removeAllRecurrences();"> {$MOD.LBL_REMOVE_ALL_RECURRENCES} </button>
</div>

<div id="cal-repeat-block" style="dispaly: none;">
<form name="CalendarRepeatForm" id="CalendarRepeatForm" onsubmit="return false;">
{sugar_csrf_form_token}

<input type="hidden" name="repeat_parent_id">
<table class="edit view" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_TYPE}:</td>
		<td width="37.5%" valign="top">
			<select name="repeat_type" onchange="toggle_repeat_type();">{html_options options=$APPLIST.repeat_type_dom}</select>
		</td>
	</tr>
	
	<tr id="repeat_interval_row" style="display: none;">
		<td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_INTERVAL}:</td>
		<td width="37.5%" valign="top">
			<select name="repeat_interval">{html_options options=$repeat_intervals selected="1"}</select> <span id="repeat-interval-text"></span>
		</td>
	</tr>
	
	<tr id="repeat_end_row" style="display: none;">
		<td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_END}:</td>
		<td width="37.5%" valign="top">
			<div>
				<input type="radio" name="repeat_end_type" value="number" id="repeat_count_radio" checked onclick="toggle_repeat_end();" style="position: relative; top: -5px;"> {$MOD.LBL_REPEAT_END_AFTER} 
				<input type="input" size="3" name="repeat_count" value="10"> {$MOD.LBL_REPEAT_OCCURRENCES}
			</div>
			<div>					
				<input type="radio" name="repeat_end_type" id="repeat_until_radio" value="date" onclick="toggle_repeat_end();" style="position: relative; top: -5px;"> {$MOD.LBL_REPEAT_END_BY}
				<input type="input" size="11" maxlength="10" id="repeat_until_input" name="repeat_until" value="" disabled>
				<img border="0" src="index.php?entryPoint=getImage&imageName=jscalendar.gif" alt="{$APP.LBL_ENTER_DATE}" id="repeat_until_trigger" align="absmiddle" style="display: none;">	
							
				<script type="text/javascript">
						Calendar.setup ({literal}{{/literal}
							inputField : "repeat_until_input",
							ifFormat : "{$CALENDAR_FORMAT}",
							daFormat : "{$CALENDAR_FORMAT}",
							button : "repeat_until_trigger",
							singleClick : true,
							dateStr : "",
							step : 1,
							startWeekday: {$CALENDAR_FDOW|default:'0'},
							weekNumbers:false
						{literal}}{/literal});
				</script>														
			</div>
		</td>
	</tr>
	
	<tr id="repeat_dow_row" style="display: none;">
		<td width="12.5%" valign="top" scope="row">{$MOD.LBL_REPEAT_DOW}:</td>
		<td width="37.5%" valign="top">
			{foreach name=dow from=$dow key=i item=d}
				{$d.label} <input type="checkbox" id="repeat_dow_{$d.index}" name="repeat_dow[]" style="margin-right: 10px;"> 	
			{/foreach}
		</td>
	</tr>
	
</table>
</form>
</div>

<script type="text/javascript">	
{literal}
	function toggle_repeat_type(){
		
		if(typeof validate != "undefined" && typeof validate['CalendarRepeatForm'] != "undefined")
			validate['CalendarRepeatForm'] = undefined;
			
		if(document.forms['CalendarRepeatForm'].repeat_type.value == ""){
			document.getElementById("repeat_interval_row").style.display = "none";
			document.getElementById("repeat_end_row").style.display = "none";
		}else{						
			document.getElementById("repeat_interval_row").style.display = "";
			document.getElementById("repeat_end_row").style.display = "";
			toggle_repeat_end();
		}
		
		var repeat_dow_row = document.getElementById("repeat_dow_row");
		if(document.forms['CalendarRepeatForm'].repeat_type.value == "Weekly"){
			repeat_dow_row.style.display = "";
		}else{
			repeat_dow_row.style.display = "none";
		}
		
		var intervalTextElm = document.getElementById('repeat-interval-text');		
		if (intervalTextElm && typeof SUGAR.language.languages.app_list_strings['repeat_intervals'] != 'undefined') {
			intervalTextElm.innerHTML = SUGAR.language.languages.app_list_strings['repeat_intervals'][document.forms['CalendarRepeatForm'].repeat_type.value];
		}
	}

	function toggle_repeat_end(){	
		if(document.getElementById("repeat_count_radio").checked){
			document.forms['CalendarRepeatForm'].repeat_until.setAttribute("disabled","disabled");
			document.forms['CalendarRepeatForm'].repeat_count.removeAttribute("disabled");
			document.getElementById("repeat_until_trigger").style.display = "none";	
			
			if(typeof validate != "undefined" && typeof validate['CalendarRepeatForm'] != "undefined"){
				removeFromValidate('CalendarRepeatForm', 'repeat_until');
			}
			addToValidateMoreThan('CalendarRepeatForm', 'repeat_count', 'int', true,'{/literal}{$MOD.LBL_REPEAT_COUNT}{literal}', 1);			
		}else{
			document.forms['CalendarRepeatForm'].repeat_count.setAttribute("disabled","disabled");			
			document.forms['CalendarRepeatForm'].repeat_until.removeAttribute("disabled");
			document.getElementById("repeat_until_trigger").style.display = "";
			
			if(typeof validate != "undefined" && typeof validate['CalendarRepeatForm'] != "undefined"){
				removeFromValidate('CalendarRepeatForm', 'repeat_count');
			}
			addToValidate('CalendarRepeatForm', 'repeat_until', 'date', true,'{/literal}{$MOD.LBL_REPEAT_UNTIL}{literal}');			
		}
		
		// prevent an issue when a calendar date picker is hidden under a dialog
		var editContainer = document.getElementById('cal-edit_c');
		if (editContainer) {
			var pickerContainer = document.getElementById('container_repeat_until_trigger_c');
			if (pickerContainer) {
				pickerContainer.style.zIndex = editContainer.style.zIndex + 1;
			}
		}		
	}
{/literal}
</script>

