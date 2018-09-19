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
{literal}
<script type="text/javascript">
    var accountText = document.getElementById('account_name');

    // add focus() to the onclick event handler of the clear account name button
    // because we need onblur to be triggered after account name is cleared
    var clearButton = document.getElementById('btn_clr_account_name');
    if (clearButton && accountText) {
        clearButton.attributes['onclick'].value = "accountText.focus();" + clearButton.attributes['onclick'].value + "clearButton.focus();";
    }

    // add onblur event handler to the account name text to update the module dropdown
    var account_name = document.getElementById('account_name');

    function onBlurKeyUpHandler() {
        var dropdown = document.getElementById('lead_conv_ac_op_sel');
        if (!dropdown || !account_name) {
            return;
        }
        var found = false;
        var i;
        var module = 'Accounts';
        for (i=dropdown.options.length-1; i>=0; i--) {
            if (dropdown.options[i].value == module) {
                found = true;
                if (account_name.value == '') {
                    dropdown.remove(i);
                }
                break;
            }
        }
        if (!found && account_name.value != '') {
            var opt = document.createElement("option");
            opt.text = SUGAR.language.get('app_list_strings', "moduleListSingular")[module];
            opt.value = module;
            opt.label = opt.text;
            dropdown.options.add(opt);
        }
    }
    if (account_name) {
        account_name.onblur = onBlurKeyUpHandler;
        account_name.onkeyup = onBlurKeyUpHandler;
    }
</script>
{/literal}

{if $lead_conv_activity_opt == 'move'}
<table width="100%" border="0" cellspacing="1" cellpadding="0"  class="{$def.templateMeta.panelClass|default:'edit view'}" id ="lead_conv_ac_op">
<tr>
    <td width="15%" class="rssItemDate">
        {sugar_translate label='LBL_ACTIVITIES_MOVE' module='Leads'}:&nbsp;{sugar_help text=$MOD.LBL_ACTIVITIES_MOVE_HELP}
    </td>
    <td>
        <select id="lead_conv_ac_op_sel" name="lead_conv_ac_op_sel">
            {$convertModuleListOptions}
        </select>
    </td>
</tr>
</table>
{elseif $lead_conv_activity_opt == 'copy' || $lead_conv_activity_opt == ''}
<table width="100%" border="0" cellspacing="1" cellpadding="0"  class="{$def.templateMeta.panelClass|default:'edit view'}" id ="lead_conv_ac_op">
<tr>
    <td width="15%" class="rssItemDate">
        {sugar_translate label='LBL_ACTIVITIES_COPY' module='Leads'}:&nbsp;{sugar_help text=$MOD.LBL_ACTIVITIES_COPY_HELP}
    </td>
    <td>
        <select id="lead_conv_ac_op_sel" name="lead_conv_ac_op_sel[]" size="5" multiple="">
            {$convertModuleListOptions}
        </select>
    </td>
</tr>
</table>
{/if}

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="buttons">
{if $bean->aclAccess("save")}
    <input title='{sugar_translate label="LBL_SAVE_BUTTON_LABEL"}' class="button primary"
        onclick="return check_form('{$form_name}');"
        type="submit" name="button" id="SAVE_FOOTER" value="{sugar_translate label='LBL_SAVE_BUTTON_LABEL'}">
{/if}

{if !empty($smarty.request.return_action) && ($smarty.request.return_action == "DetailView" && !empty($record_id))}
    <input title="{sugar_translate label='LBL_CANCEL_BUTTON'}"  class="button"
        onclick="this.form.action.value='DetailView'; this.form.module.value='{$smarty.request.return_module}'; this.form.record.value='{$smarty.request.return_id}';"
        type="submit" id="CANCEL_FOOTER" name="button" value="{sugar_translate label='LBL_CANCEL_BUTTON_LABEL'}">
{elseif !empty($smarty.request.return_action) && ($smarty.request.return_action == "DetailView" && !empty($smarty.request.return_id))}';
    <input title="{sugar_translate label='LBL_CANCEL_BUTTON_TITLE'}" class="button"
        onclick="this.form.action.value='DetailView'; this.form.module.value='{$smarty.request.return_module}'; this.form.record.value='{$smarty.request.return_id}';"
        type="submit" id="CANCEL_FOOTER" name="button" value="{sugar_translate label='LBL_CANCEL_BUTTON_LABEL'}">
{else}
    <input title="{sugar_translate label='LBL_CANCEL_BUTTON_TITLE'}"  class="button"
        onclick="this.form.action.value='DetailView'; this.form.module.value='Leads'; this.form.record.value='{$smarty.request.record}';"
        type="submit" id="CANCEL_FOOTER" name="button" value="{sugar_translate label='LBL_CANCEL_BUTTON_LABEL'}">
{/if}
</td>
</tr>
</table>
 <script type="text/javascript">
   addDropdownElements();//Bug#50590 after  lead_conv_ac_op_sel is loaded fill it with all required modules
 </script>
