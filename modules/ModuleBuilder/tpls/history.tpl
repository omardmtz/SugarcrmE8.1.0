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
<table class="tabform" ><tr><th>{sugar_translate label='LBL_HISTORY_TIMESTAMP' module='ModuleBuilder'}</th><th>&nbsp;</th><th>&nbsp;</th></tr>
{if empty($snapshots)}
	<tr><td class='mbLBLL'>{sugar_translate label='ERROR_NO_HISTORY' module='ModuleBuilder'}</td></tr>
{/if}
{foreach from=$snapshots item=snapshot key='id'}
<tr>
	<td class="oddListRowS1">
        <a onclick="ModuleBuilder.history.preview('{$view_module}', '{$view}', '{$id}', '{$subpanel|escape:javascript}');"
            href="javascript:void(0);">
            {$snapshot.label}
        </a>
    </td>
	<td width="1%"><input type='button' value="{sugar_translate label='LBL_MB_PREVIEW' module='ModuleBuilder'}" onclick="ModuleBuilder.history.preview('{$view_module}', '{$view}', '{$id}', '{$subpanel}');"/></td>
    <td width="1%">
        <input type='button' value="{sugar_translate label='LBL_MB_RESTORE' module='ModuleBuilder'}"
            onclick="ModuleBuilder.history.revert('{$view_module}', '{$view}', '{$id}', '{$subpanel}', {$snapshot.isDefault|json});"/>
    </td>
</tr>
{/foreach}
</table>
