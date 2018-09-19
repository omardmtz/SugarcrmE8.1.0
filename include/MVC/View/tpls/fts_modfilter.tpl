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

<div class="ftsModuleFilterSpan">
    {if empty($moduleFilter)}
        <input type="checkbox" checked="checked" id="all" name="module_filter" class="ftsModuleFilter">
        <span id="all_label" class="checked">&nbsp;{$APP.LBL_EMAIL_SHOW_READ}</span>
    {else}
        <input type="checkbox" id="all" name="module_filter" class="ftsModuleFilter">
        <span id="all_label" class="unchecked">&nbsp;{$APP.LBL_EMAIL_SHOW_READ}</span>
    {/if}
</div>
{foreach from=$filterModules item=entry key=module}
    <div class="ftsModuleFilterSpan">
        {if is_array($moduleFilter) && in_array($entry.module, $moduleFilter)}
            <input type="checkbox" checked="checked" id="{$entry.module}" name="module_filter" class="ftsModuleFilter">
            <span id="{$entry.module}_label" class="checked">&nbsp;{$entry.label}</span>
            <span id="{$entry.module}_count" class="checked">{if is_int($entry.count)}({$entry.count}){/if}</span>
        {else}
            <input type="checkbox" id="{$entry.module}" name="module_filter" class="ftsModuleFilter">
            <span id="{$entry.module}_label" class="unchecked">&nbsp;{$entry.label}</span>
            <span id="{$entry.module}_count" class="unchecked">{if is_int($entry.count) }({$entry.count}){/if}</span>
        {/if}
    </div>
{/foreach}
