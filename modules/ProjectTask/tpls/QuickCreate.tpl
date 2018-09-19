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


<form name="projectTaskQuickCreate" id="projectTaskQuickCreate" method="POST" action="index.php">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="ProjectTask">
<input type="hidden" name="project_id" value="{$REQUEST.project_id}">
<input type="hidden" name="contact_name" value="{$REQUEST.contact_name}">
<input type="hidden" name="email_id" value="{$REQUEST.email_id}">
<input type="hidden" name="bug_id" value="{$REQUEST.bug_id}">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
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
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('ProjectTaskQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='ProjectTask';" value="  {$APP.LBL_FULL_FORM_BUTTON_LABEL}  "></td>
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<th align="left" scope="row" colspan="4"><h4><slot>{$MOD.LBL_PROJECTTYPE_INFORMATION}</slot></h4></th>
	</tr>
	<tr>
	<td valign="top" scope="row"><slot>{$MOD.LBL_NAME} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td><slot><input name="name" tabindex="1" size="35" maxlength="50" type="text" value=""></slot></td>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td valign="top" scope="row" rowspan="2"><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	<td rowspan="2"><slot><textarea name='description' tabindex='3' cols="50" rows="4">{$DESCRIPTION}</textarea></slot></td>
	<td scope="row"><slot>{$MOD.LBL_PERCENT_COMPLETE}</slot></td>
	<td ><slot><input name="percent_complete" type="text" tabindex="2"  size="4" maxlength="3"
	/></slot></td>
	</tr>
	<tr>
	{if $REQUEST.project_id != ''}
	<td scope="row"><slot>{$MOD.LBL_PARENT_ID}</slot></td>
	<td ><slot>{$REQUEST.parent_name}<input id='project_name' name='project_name' type="hidden" value='{$REQUEST.parent_name}'><input id='project_id' name='project_id' type="hidden" value='{$REQUEST.project_id}'>&nbsp;</slot></td>
	{else}
	<td scope="row"><slot>{$MOD.LBL_PARENT_ID}</slot></td>
	<td ><slot><input type="text" class="sqsEnabled" tabindex="2" autocomplete="off" name="parent_name" id="parent_name"
	value="{$parent_name}" tabindex="16" /><input type="hidden" name="project_id" id="project_id"
	value="{$project_id}" />&nbsp;<input
	title="{$APP.LBL_SELECT_BUTTON_TITLE}" type="button" class="button"
	value="{$APP.LBL_SELECT_BUTTON_LABEL}" name="change_parent" tabindex="2"
	onclick='open_popup("Project", 600, 400, "", true, false, {$encoded_parent_popup_request_data});'
	/></slot></td>	{/if}
	</tr>
	</table>
	</form>
<script>
	{$additionalScripts}
</script>