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

{if !empty($vardef.value) && ($vardef.value != '^^')}
    <input type="hidden" class="sugar_field" id="{$vardef.name}" value="{$vardef.value}">
    {multienum_to_array string=$vardef.value assign="vals"}
    {foreach from=$vals item='item'}
    <li style="margin-left:10px;">{$vardef.options.$item}</li>
    {/foreach}
{/if}
