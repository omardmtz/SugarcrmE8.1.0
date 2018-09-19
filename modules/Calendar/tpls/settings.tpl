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

<script type="text/javascript">
{literal}
function toggleDisplayTimeslots() {
	if (document.getElementById('display_timeslots').checked) {
		$(".time_range_options_row").css('display', '');
	} else {
		$(".time_range_options_row").css('display', 'none');
	}
}

$(function() {
	toggleDisplayTimeslots();
});

{/literal}
</script>

<div id="settings_dialog" style="width: 450px; display: none;">
	<div class="hd">{$MOD.LBL_SETTINGS_TITLE}</div>
	<div class="bd">
	<form name="settings" id="form_settings" method="POST" action="index.php?module=Calendar&action=SaveSettings">
{sugar_csrf_form_token}
		<input type="hidden" name="view" value="{$view}">
		<input type="hidden" name="day" value="{$day}">
		<input type="hidden" name="month" value="{$month}">
		<input type="hidden" name="year" value="{$year}">
		
		<table class='edit view tabForm'>
				<tr>
					<td scope="row" valign="top" width="55%">
						{$MOD.LBL_SETTINGS_DISPLAY_TIMESLOTS}
					</td>
					<td width="45%">	
						<input type="hidden" name="display_timeslots" value="">
						<input type="checkbox" id="display_timeslots" name="display_timeslots" {if $display_timeslots}checked{/if} value="1" tabindex="102" onchange="toggleDisplayTimeslots();">
					</td>
				</tr>
				<tr class="time_range_options_row">
					<td scope="row" valign="top">
						{$MOD.LBL_SETTINGS_TIME_STARTS}
					</td>
					<td>
						<div id="d_start_time_section">
							<select size="1" id="day_start_hours" name="day_start_hours" tabindex="102">
								{$TIME_START_HOUR_OPTIONS}
							</select>&nbsp;:
							
							<select size="1" id="day_start_minutes" name="day_start_minutes"  tabindex="102">
								{$TIME_START_MINUTES_OPTIONS}
							</select>
								&nbsp;
							{$TIME_START_MERIDIEM}
						</div>
					</td>
				</tr>
				<tr class="time_range_options_row">
					<td scope="row" valign="top">
						{$MOD.LBL_SETTINGS_TIME_ENDS}
					</td>
					<td>
						<div id="d_end_time_section">
							<select size="1" id="day_end_hours" name="day_end_hours" tabindex="102">
								{$TIME_END_HOUR_OPTIONS}
							</select>&nbsp;:
							
							<select size="1" id="day_end_minutes" name="day_end_minutes"  tabindex="102">
								{$TIME_END_MINUTES_OPTIONS}
							</select>
								&nbsp;
							{$TIME_END_MERIDIEM}
						</div>
					</td>
				</tr>
				<tr>
					<td scope="row" valign="top">
						{$MOD.LBL_SETTINGS_CALLS_SHOW}
					</td>
					<td>	
						<select size="1" name="show_calls" tabindex="102">
							<option value='' {if !$show_calls}selected{/if}>{$MOD.LBL_NO}</option>
							<option value='true' {if $show_calls}selected{/if}>{$MOD.LBL_YES}</option>								
						</select>
					</td>
				</tr>
				<tr>
					<td scope="row" valign="top">
						{$MOD.LBL_SETTINGS_TASKS_SHOW}
					</td>
					<td>	
						<select size="1" name="show_tasks" tabindex="102">
							<option value='' {if !$show_tasks}selected{/if}>{$MOD.LBL_NO}</option>
							<option value='true' {if $show_tasks}selected{/if}>{$MOD.LBL_YES}</option>								
						</select>
					</td>
				</tr>
		</table>
	</form>
	
	
	<div style="text-align: right;">
		<button id="btn-save-settings" class="button" type="button">{$MOD.LBL_APPLY_BUTTON}</button>&nbsp;
		<button id="btn-cancel-settings" class="button" type="button">{$MOD.LBL_CANCEL_BUTTON}</button>&nbsp;
	</div>
	</div>
</div>
