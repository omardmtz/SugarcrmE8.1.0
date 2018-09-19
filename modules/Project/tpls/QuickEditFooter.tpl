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
*}<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
    <tr>
        <td>
        {if $bean->aclAccess("save")}<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="this.form.action.value='Save';this.form.return_action.value='ProjectTemplatesListView';if(check_form('{$view}'))return DCMenu.save(this.form.id, 'Project_subpanel_save_button');return false;" type="submit" name="Project_dcmenu_save_button" id="Project_dcmenu_save_button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">{/if}
        {{foreach from=$form.buttons key=val item=button}}
           {{sugar_button module="$module" id="$button" view="$view"}}
        {{/foreach}}
            {* // FIXME check these SUGAR.ajaxUI.* methods *}
        <input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" accept=""  onclick="this.form.return_action.value='ProjectTemplatesDetailView'; this.form.action.value='ProjectTemplatesEditView'; this.form.return_module.value='Project';this.form.return_id.value=this.form.record.value;if(typeof(this.form.to_pdf)!='undefined') this.form.to_pdf.value='0';SUGAR.ajaxUI.submitForm(this.form,null,true);DCMenu.closeOverlay();"  type="button" name="Project_subpanel_full_form_button"  id="Project_subpanel_full_form_button"  value="{$APP.LBL_FULL_FORM_BUTTON_LABEL}">
        <input type="hidden" name="full_form" value="full_form">
        </td>
        <td align="right" nowrap>
            <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
        </td>
    </tr>
</table>
<script>
    {literal}
// TODO no more DCMenu
    //lets overwrite dcmenu value that is prepoulated and passed into ajaxui to navigate.  This makes sure we go to
    //projectstemplate list view after the save has been processed.
//    if(DCMenu){
//        DCMenu.qe_refresh = 'SUGAR.ajaxUI.loadContent("index.php?module=Project&action=ProjectTemplatesListView&ignore='+new Date().getTime()+'");';
//    }
    {/literal}
</script>
