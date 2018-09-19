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
<link type="text/css" href="{sugar_getjspath file='modules/ProcessMaker/css/jcore.adam-ui.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/ProcessMaker/css/jcore.adam.css'}" rel="stylesheet" />
<div class="moduleTitle">
    <h2>{$title}</h2>
    <div class="clear"></div>
</div>
<form action="{$form.action}" id="{$form.id}" method="POST" {if isset($form.enctype) && $form.enctype ne ''} enctype="{$form.enctype}"{/if}>
{sugar_csrf_form_token}
    {foreach from=$form.hidden item=hidden}
        <input type="hidden" value="{$hidden.value}" name="{$hidden.name}" id="{$hidden.id}">
    {/foreach}
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="dcQuickEdit">
        <tbody><tr>
            <td class="buttons">
                <!-- to be used for id for buttons with custom code in def files-->
                <div class="action_buttons">
                    {foreach from=$buttons item=button}
                        <input type="{$button.type}" id="{$button.id}" value="{$button.value}" name="{$button.name}" onclick="{$button.onclick}" class="{$button.class}" title="{$button.title}">
                    {foreachelse}
                        <input type="submit" id="SAVE_HEADER" value="Save" name="button" onclick="" class="button primary" accesskey="a" title="Save">
                        <input type="button" id="CANCEL_HEADER" value="Cancel" name="button" onclick="SUGAR.ajaxUI.loadContent('index.php?action=index&amp;module=Home&amp;record='); return false;" class="button" accesskey="l" title="Cancel [Alt+Shift+l]">
                    {/foreach}
                    <div class="clear"></div>
                </div>
            </td>
            <td align="right">
                <span class="error">*</span>
                {sugar_translate label='LBL_PMSE_MESSAGE_REQUIREDFIELDS' module=$moduleName}
            </td>
        </tr>
        </tbody>
    </table>
<div class="edit view edit508">
<h4>&nbsp;&nbsp;</h4>
<table width="100%" cellspacing="1" cellpadding="0" border="0" class="edit view panelContainer" id="LBL_ACCOUNT_INFORMATION">
    <tbody>
    <tr>
        <td align="center">
            <table width="800" cellspacing="1" cellpadding="0" border="0">
                <tbody>
                {foreach from=$fields item=row}
                <tr>
                    <td width="35%" valign="top" scope="col" id="name_label" align="right">
                        {$row.label.name}
                        {if $row.label.required}
                            <span class="error">*</span>
                        {/if}
                    </td>
                    <td width="65%" valign="top">
                        {if $row.field.type eq 'text'}
                            <input type="text" title="{$row.field.title}" value="{$row.field.value}" maxlength="{$row.field.maxlength}" size="{$row.field.size}" id="{$row.field.id}" name="{$row.field.name}" {if $row.field.required}required{/if}>
                        {/if}
                        {if $row.field.type eq 'textarea'}
                            <textarea name="{$row.field.name}" id="{$row.field.id}" rows="{$row.field.rows}" cols="{$row.field.cols}" {if $row.field.required}required{/if}>{$row.field.value}</textarea>
                        {/if}
                        {if $row.field.type eq 'file'}
                            <input type="file" id="{$row.field.id}" name="{$row.field.name}" value="{$row.field.value}" {if $row.field.required}required{/if}/>
                        {/if}
                        {if $row.field.type eq 'select'}
                            <select name="{$row.field.name}" id="{$row.field.id}" title="{$row.field.title}" {if $row.field.required}required{/if}>
                                {foreach from=$row.field.values item=val}
                                    <option value="{$val.value}" {if $val.selected}selected{/if}>{$val.text}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</div>
</form>
<script type="text/javascript" src='{sugar_getjspath file="cache/include/javascript/pmse.libraries.min.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="cache/include/javascript/pmse.jcore.min.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="cache/include/javascript/pmse.ui.min.js"}'></script>
{$script}

<script type="text/javascript">
    var SUGAR_REST = {$SUGAR_REST},
        SUGAR_AJAX_URL = '{$SUGAR_AJAX_URL}',
        SUGAR_URL = '{$SUGAR_URL}';
</script>