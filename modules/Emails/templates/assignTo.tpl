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
<form name="Distribute" id="Distribute">
{sugar_csrf_form_token}
<input type="hidden" name="emailUIAction" value="doAssignmentAssign">

<input type="hidden" name="distribute_method" value="direct">
<input type="hidden" name="action" value="Distribute">


<table cellpadding="4" cellspacing="0" border="0" width="100%" class="edit view"> 
    <tr>
        <td scope="row" nowrap="nowrap" valign="top" >
        {sugar_translate label="LBL_ASSIGNED_TO"}:
        </td>
        <td nowrap="nowrap" width="37%">
        <input name="assigned_user_name" class="sqsEnabled" tabindex="2" id="assigned_user_name" size="" value="{$currentUserName}" type="text">
        <input name="assigned_user_id" id="assigned_user_id" value="{$currentUserId}" type="hidden">
        <input name="btn_assigned_user_name" tabindex="2" title="{$app_strings.LBL_SELECT_BUTTON_TITLE}" class="button" value="{$app_strings.LBL_SELECT_BUTTON_LABEL}" onclick='open_popup("Users", 600, 400, "", true, false, {literal}{"call_back_function":"set_return","form_name":"Distribute","field_to_name_array":{"id":"assigned_user_id","name":"assigned_user_name"}}{/literal}, "single", true);' type="button">
        <input name="btn_clr_assigned_user_name" tabindex="2" title="{$app_strings.LBL_CLEAR_BUTTON_TITLE}" class="button" onclick="this.form.assigned_user_name.value = ''; this.form.assigned_user_id.value = '';" value="{$app_strings.LBL_CLEAR_BUTTON_LABEL}" type="button">
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	   <td scope="row" nowrap="nowrap" valign="top">{$app_strings.LBL_TEAMS}:&nbsp;</td>
    	   <td >{$TEAM_SET_FIELD_FOR_ASSIGNEDTO}</td>
    	   <td>&nbsp;</td>
    </tr>
    <tr><td>&nbsp</td><td>&nbsp</td></tr>
    <tr>
    	   <td>&nbsp;</td>
    	   <td>&nbsp;</td>
    	   <td align="right"><input type="button" class="button" style="margin-left:5px;" value="{$mod_strings.LBL_BUTTON_DISTRIBUTE}" onclick="AjaxObject.detailView.handleAssignmentDialogAssignAction();"></td>
    </tr>
</table>

</form>

