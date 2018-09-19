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


<form name="meetingsQuickCreate" id="meetingsQuickCreate" method="POST" action="index.php">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Meetings">
<input type="hidden" name="record" value="">
<input type="hidden" name="lead_id" value="{$REQUEST.lead_id}">
<input type="hidden" name="contact_id" value="{$REQUEST.contact_id}">
<input type="hidden" name="contact_invitees" value="{$REQUEST.contact_id}">
<input type="hidden" name="contact_name" value="{$REQUEST.contact_name}">
<input type="hidden" name="email_id" value="{$REQUEST.email_id}">
<input type="hidden" name="account_id" value="{$REQUEST.account_id}">			
<input type="hidden" name="opportunity_id" value="{$REQUEST.opportunity_id}">
<input type="hidden" name="acase_id" value="{$REQUEST.acase_id}">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
<input type="hidden" name="return_module" value="{$REQUEST.return_module}">
<input type="hidden" name="return_id" value="{$REQUEST.return_id}">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="{$REQUEST.duplicate_parent_id}">
<!--
CL: Bug fix for 9291 and 9427 - parent_id should be parent_type, not the module type (if set)
-->
{if $REQUEST.parent_id}
	<input type="hidden" name="parent_id" value="{$REQUEST.parent_id}">
{else}
	<input type="hidden" name="parent_id" value="{$REQUEST.return_id}">
{/if}	
{if $REQUEST.parent_type}
	<input type="hidden" name="parent_type" value="{$REQUEST.parent_type}">
{else}
	<input type="hidden" name="parent_type" value="{$REQUEST.return_module}">
{/if}
<input type="hidden" name="parent_name" value="{$REQUEST.parent_name}">

<input type="hidden" name="to_pdf" value='1'>
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="{$ASSIGNED_USER_ID}" />
<input id='team_id' name='team_id' type="hidden" value="{$TEAM_ID}" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('MeetingsQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Meetings';" value="  {$APP.LBL_FULL_FORM_BUTTON_LABEL}  "></td>
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<th align="left" scope="row" colspan="4"><h4><slot>{$MOD.LBL_NEW_FORM_TITLE}</slot></h4></th>
	</tr>
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_SUBJECT} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td><slot><textarea name='name' cols="50" tabindex='1' rows="1">{$NAME}</textarea></slot></td>
	<td scope="row" width="15%"><slot>{$MOD.LBL_STATUS} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td width="35%"><slot><select tabindex='2' name='status'>{$STATUS_OPTIONS}</select></slot></td>
	</tr>
	<tr>
	<td valign="top" scope="row" rowspan="2"><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	<td rowspan="2"><slot><textarea name='description' tabindex='1' cols="50" rows="4">{$DESCRIPTION}</textarea></slot></td>
	<td scope="row"><slot>{$MOD.LBL_DATE_TIME}</slot></td>
	<td >
	<slot>
		<table cellpadding="0" cellspacing="0">
		<tr>
		<td nowrap>
		<input name='date_start' id='jscal_field' onblur="parseDate(this, '{$USER_DATEFORMAT}');" tabindex='2' size='11' maxlength='10' type="text" value="{$DATE_START}">
		{sugar_getimage name="jscalendar" ext=".gif" alt=$USER_DATEFORMAT other_attributes='align="absmiddle" id="jscal_trigger" '}&nbsp;</td>
        <td nowrap>
        <select name='time_hour_start' tabindex="2">{$TIME_START_HOUR_OPTIONS}</select>{$TIME_SEPARATOR}
        <select name='time_minute_start' tabindex="2">{$TIME_START_MINUTE_OPTIONS}</select>
        {if $TIME_MERIDIEM}
        <select name='meridiem' tabindex="2">{$TIME_MERIDIEM}</select>
        {/if}
        </td>
        </tr>
        <tr>
        <td nowrap><span class="dateFormat">{$USER_DATEFORMAT}</span></td>
        <td nowrap><span class="dateFormat">{$TIME_FORMAT}</span></td>
        </tr>
        </table>
    </slot>
    </td>
	</tr>
	<tr>
	<td scope="row" valign="top"><slot>{$MOD.LBL_DURATION} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td valign="top" >
	<slot><input name='duration_hours' tabindex="2" size='2' maxlength='2' type="text" value='{$DURATION_HOURS}'> <select tabindex="2" name='duration_minutes'>{$DURATION_MINUTES_OPTIONS}</select> {$MOD.LBL_HOURS_MINS}</slot></td>
	</tr>
	</table>
</td>
</tr>
</table>
</form>
<script type="text/javascript">
{literal}
Calendar.setup ({
    inputField : "jscal_field", daFormat : "{/literal}{$CALENDAR_FORMAT}{literal}", onClose: function(cal) {cal.hide();},
    showsTime : false, button : "jscal_trigger", singleClick : true, step : 1, startWeekday: {/literal}{$CALENDAR_FDOW|default:'0'}{literal}, weekNumbers:false
});
{/literal}
</script>
<script type="text/javascript">
	{$additionalScripts}
</script>
