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
<h3>{sugar_translate label="LBL_REMOVE_CUSTOM"}</h3>
<form name="remove_custom">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="ModuleBuilder">
<input type="hidden" name="action" value="resetmodule">
<input type="hidden" name="view_module" value="{$module}">
<input type="hidden" name="handle" value="execute">
<ul id="repair_actions">
{foreach from=$actions item='action'}
<li>
    <input type="checkbox" name="{$action.name}" value="{$action.name}" checked="checked" />
    {$action.label}
</li> 
{/foreach}
</ul>
</form>
<button id="execute_repair" onclick="this.disabled = true;
ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_LOADING'));
ModuleBuilder.submitForm('remove_custom')">{sugar_translate label="LBL_RESET"}</button>
