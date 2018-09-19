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


<form name="holidaysQuickCreate" id="holidaysQuickCreate" method="POST" action="index.php">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Holidays">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
<input type="hidden" name="return_module" value="{$REQUEST.return_module}">
<input type="hidden" name="return_id" value="{$REQUEST.return_id}">
{if $PROJECT}
<input type="hidden" name="related_module_id" value="{$REQUEST.return_id}">
<input type="hidden" name="related_module" value="{$REQUEST.return_module}">
{else}
<input type="hidden" name="person_id" value="{$REQUEST.return_id}">
<input type="hidden" name="person_type" value="{$REQUEST.return_module}">
{/if}

<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="{$REQUEST.duplicate_parent_id}">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="{$ASSIGNED_USER_ID}" />
<input type="hidden" name="to_pdf" value='1'>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('holidaysQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_NAME} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td><slot><input type='text' id='holiday_date' name='holiday_date' cols="50" tabindex='2' rows="1" value='{$NAME}' />{sugar_getimage name="jscalendar" ext=".gif" alt=$CALENDAR_DATEFORMAT other_attributes='align="absmiddle" id="jscal_trigger" '}</slot></td>
	</tr>
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	<td><slot><textarea name='description' tabindex='3' cols="50" rows="4">{$DESCRIPTION}</textarea></slot></td>
	</tr>
	{if $PROJECT}
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_RESOURCE} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td id='resourceSelect'>
		<select name="person_type" id="person_type" onChange="showResourceSelect();">
			<option value="">{$MOD.LBL_SELECT_RESOURCE_TYPE}</option>
			<option value="Users">{$MOD.LBL_USER}</option>
			<option value="Contacts">{$MOD.LBL_CONTACT}</option>
		</select>
		<span id="resourceSelector"></span>
	</td>
	</tr>
	{/if}
	</table>
	</form>
<script type="text/javascript">
{literal}
Calendar.setup ({
	inputField : "holiday_date", ifFormat : "{/literal}{$CALENDAR_DATEFORMAT}{literal}", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1, weekNumbers:false
});
{/literal}
{$additionalScripts}
</script>

