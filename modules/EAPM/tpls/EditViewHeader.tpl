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
<script language="javascript">
    {literal}
    SUGAR.util.doWhen(function(){
        return $("#contentTable").length == 0;
    }, SUGAR.themes.actionMenu);
    {/literal}
</script>
<form action="index.php" method="POST" name="EditView" id="EditView" >
{sugar_csrf_form_token}
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
<tr>
<td class="buttons">
<input type="hidden" name="module" value="{$module}">

<input type="hidden" name="record" value="{$fields.id.value}">
<input type="hidden" name="isDuplicate" value="{$smarty.request.isDuplicate}">
<input type="hidden" name="action">
<input type="hidden" name="return_module" value="{$smarty.request.return_module}">
<input type="hidden" name="return_action" value="{$smarty.request.return_action}">
<input type="hidden" name="return_id" value="{$smarty.request.return_id}">
<input type="hidden" name="module_tab">
<input type="hidden" name="contact_role">
<input type="hidden" name="relate_to" value="{$smarty.request.return_module}">
<input type="hidden" name="relate_id" value="{$smarty.request.return_id}">
<input type="hidden" name="offset" value="1">
<input name="assigned_user_id" type="hidden" value="{$fields.assigned_user_id.value}" autocomplete="off">
{{if empty($form.button_location) || $form.button_location == 'top'}}
{{if !empty($form) && !empty($form.buttons)}}
   {{foreach from=$form.buttons key=val item=button}}
      {{sugar_button module="$module" id="$button" view="$view" appendTo="action_button"}}
   {{/foreach}}
{{else}}
{{sugar_button module="$module" id="SAVE" view="$view" appendTo="action_button"}}
{{sugar_button module="$module" id="CANCEL" view="$view" appendTo="action_button"}}
{{/if}}
{{if empty($form.hideAudit) || !$form.hideAudit}}
{{sugar_button module="$module" id="Audit" view="$view" appendTo="action_button"}}
{{/if}}
{{/if}}
{{sugar_action_menu buttons=$action_button id="EAPMActionMenu" class="fancymenu" flat=true}}
    <td align='right'>
</td>

</tr>
</table>