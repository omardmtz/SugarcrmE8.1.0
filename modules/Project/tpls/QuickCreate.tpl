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


<form name="projectQuickCreate" id="projectQuickCreate" method="POST" action="index.php">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Project">
<input type="hidden" name="quote_id" value="{$REQUEST.quote_id}">
<input type="hidden" name="contact_id" value="{$REQUEST.contact_id}">
<input type="hidden" name="email_id" value="{$REQUEST.email_id}">
<input type="hidden" name="account_id" value="{$REQUEST.account_id}">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
<input type="hidden" name="opportunity_id" value="{$REQUEST.opportunity_id}">
<input type="hidden" name="return_module" value="{$REQUEST.return_module}">
<input type="hidden" name="return_id" value="{$REQUEST.return_id}">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="{$REQUEST.duplicate_parent_id}">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="{$ASSIGNED_USER_ID}" />
<input type="hidden" name="to_pdf" value='1'>
<input id='team_id' name='team_id' type="hidden" value="{$TEAM_ID}" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('ProjectQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Project';" value="  {$APP.LBL_FULL_FORM_BUTTON_LABEL}  "></td>
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_NAME} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td><slot><textarea name='name' cols="50" tabindex='2' rows="1">{$NAME}</textarea></slot></td>
	</tr>
	<tr>
	<td valign="top" scope="row" width="15%"><slot>{$MOD.LBL_DATE_START} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td width="35%"><slot><input name='estimated_start_date' onblur="parseDate(this, '{$CALENDAR_DATEFORMAT}');" id='jscal_field_start' type="text" tabindex='2' size='11' maxlength='10' value="{$START_DATE}"> {sugar_getimage name="jscalendar" ext=".gif" alt=$APP.LBL_ENTER_DATE other_attributes='align="absmiddle" id="jscal_trigger_start" '}</slot></td>
	<td valign="top" scope="row" width="15%"><slot>{$MOD.LBL_DATE_END} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td width="35%"><slot><input name='estimated_end_date' onblur="parseDate(this, '{$CALENDAR_DATEFORMAT}');" id='jscal_field_end' type="text" tabindex='2' size='11' maxlength='10' value="{$END_DATE}"> {sugar_getimage name="jscalendar" ext=".gif" alt=$APP.LBL_ENTER_DATE other_attributes='align="absmiddle" id="jscal_trigger_end" '}</slot></td>
	</tr>	
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	<td><slot><textarea name='description' tabindex='3' cols="50" rows="4">{$DESCRIPTION}</textarea></slot></td>
	</tr>
	</table>
	</form>
<script>
	Calendar.setup ({literal}{{/literal}
		inputField : "jscal_field_start", ifFormat : "{$CALENDAR_DATEFORMAT}", showsTime : false, button : "jscal_trigger_start", singleClick : true, step : 1, weekNumbers:false
	{literal}}{/literal});
	Calendar.setup ({literal}{{/literal}
		inputField : "jscal_field_end", ifFormat : "{$CALENDAR_DATEFORMAT}", showsTime : false, button : "jscal_trigger_end", singleClick : true, step : 1, weekNumbers:false
	{literal}}{/literal});
	
	{$additionalScripts}
</script>