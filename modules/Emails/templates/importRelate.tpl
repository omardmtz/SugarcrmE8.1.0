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
{$SQS}
{literal}
<script>

disabledModules = [];
enableQS(true);
function parent_typechangeQS() {
    var formName = {/literal}'{$formName}';{literal}
    var parentFieldName = formName + "_parent_name";
    
    disabledModules = [];
    new_module = document[formName].parent_type.value;
    
    if(typeof(disabledModules[new_module]) != 'undefined') {
        sqs_objects[parentFieldName]['disable'] = true;
        document.getElementById('parent_name').readOnly = true;
        document.getElementById('parent_name').value = mod_strings['LBL_QS_DISABLED'];
    }
    else {
        sqs_objects[parentFieldName]['disable'] = false;
        document.getElementById('parent_name').readOnly = false;
    }   
    sqs_objects[parentFieldName]['modules'] = new Array(new_module);
    if (document.getElementById('smartInputFloater')) document.getElementById('smartInputFloater').style.display = 'none';
    //var newArray = array();
    QSFieldsArray[parentFieldName].sqs.modules = new Array(new_module);

    enableQS(true);
}
</script>
{/literal}
{$JS}
<form name="{$formName}" id="{$formName}">
{sugar_csrf_form_token}
<div id="importDiv" class='edit view'>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody><tr>
<td>
<input name="module" value="Emails" type="hidden">
<input name="record" value="{$emailId}" type="hidden">
<input name="isDuplicate" value="false" type="hidden">
<input name="action" type="hidden">
<input name="return_module" type="hidden">
<input name="return_action" type="hidden">
<input name="return_id" type="hidden">
</td>
</tbody></table>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tbody>
{if $showTeam}
<tr>
<td scope="row" nowrap="nowrap" valign="top" width="12%">
<script type="text/javascript">addToValidate("{$formName}", "team_id", "relate", true, "{sugar_translate label="LBL_TEAM_ID"}");</script>
<script type="text/javascript">addToValidate("{$formName}", "team_name", "relate", true, "{sugar_translate label="LBL_TEAM"}");</script>
{sugar_translate label="LBL_TEAMS"}:
<span class="required">*</span>
</td>
<td nowrap="nowrap" width="37%">
{$TEAM_SET_FIELD}
</td></tr>
{/if}
<tr>
{if $showAssignedTo}
<td scope="row" nowrap="nowrap" valign="top" width="12%">
<script type="text/javascript">addToValidate("{$formName}", "assigned_user_id", "relate", false, "{sugar_translate label="LBL_ASSIGNED_TO_ID"}");</script>
<script type="text/javascript">addToValidate("{$formName}", "assigned_user_name", "relate", false, "{sugar_translate label="LBL_ASSIGNED_TO"}");</script>
{sugar_translate label="LBL_ASSIGNED_TO"}:
</td>
<td nowrap="nowrap" width="37%">
<input name="assigned_user_name" class="sqsEnabled" tabindex="2" id="assigned_user_name" size="" value="{$userName}" type="text">
<input name="assigned_user_id" id="assigned_user_id" value="{$userId}" type="hidden">
<input name="btn_assigned_user_name" tabindex="2" title="{$APP.LBL_SELECT_BUTTON_TITLE}"  class="button" value="{$APP.LBL_SELECT_BUTTON_LABEL}" onclick='open_popup("Users", 600, 400, "", true, false, {literal}{"call_back_function":"set_return","form_name":"{/literal}{$formName}{literal}","field_to_name_array":{"id":"assigned_user_id","name":"assigned_user_name"}}{/literal}, "single", true);' type="button">
<input name="btn_clr_assigned_user_name" tabindex="2" title="{$APP.LBL_CLEAR_BUTTON_TITLE}"  class="button" onclick="this.form.assigned_user_name.value = ''; this.form.assigned_user_id.value = '';" value="{$APP.LBL_CLEAR_BUTTON_LABEL}" type="button">
</td>
</tr>
{/if}
<tr>
<td scope="row" nowrap="nowrap" valign="top" width="12%">
{sugar_translate label="LBL_EMAIL_RELATE"}:
</td>
<td nowrap="nowrap" width="37%"><slot _moz-userdefined="">
<table><tr><td>
<select onchange=" document['{$formName}'].parent_name.value=''; checkParentType(document['{$formName}'].parent_type.value, document['{$formName}'].change_parent); parent_typechangeQS();" name="parent_type" id="parent_type" tabindex="2">
{$parentOptions}</select>
</slot>
</td><td>
<slot _moz-userdefined="">
<input type="hidden" value="" name="parent_id" id="parent_id"/>
<input type="text" value="" tabindex="2" name="parent_name" id="parent_name" class="sqsEnabled" autocomplete="OFF"/>
<input type="button"  onclick='{literal} if(document["{/literal}{$formName}{literal}"].parent_type.value != ""){open_popup(document["{/literal}{$formName}{literal}"].parent_type.value,600,400,"",true,false,{"call_back_function":"set_return","form_name":"{/literal}{$formName}{literal}","field_to_name_array":{"id":"parent_id","name":"parent_name"}});}'{/literal} value="{$APP.LBL_SELECT_BUTTON_LABEL}"  title="{$APP.LBL_SELECT_BUTTON_TITLE}" class="button" tabindex="2" name="button" id="change_parent"/>
</slot>
</td></tr></table>
</td>
</tr>
{if $showDelete}
<tr><td scope="row" nowrap="nowrap" valign="top" width="12%">
{sugar_translate label="LBL_DELETE_FROM_SERVER"}:
</td>
<td nowrap="nowrap" width="37%">
<input class='ctabEditViewDF' type='checkbox' name='serverDelete'>
</td></tr>
{/if}
</tbody></table>
</div>
</form>
