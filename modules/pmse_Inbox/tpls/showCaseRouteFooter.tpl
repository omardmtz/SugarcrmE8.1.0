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
<!---------------  END WORKFLOW SHOWCASE ------------>
    {foreach from=$customButtons key='key' item='item'}
        {*<input name="{$item.name}" type="{$item.type}" value={$item.value} onclick="{$item.onclick}">*}
        {if $item.value=='Claim'}
            <a href="{$item.onclick}" title="{$item.value}"><span class="btn">{$item.value}</span></a>
        {else}
            <input id="{$item.id}" name="{$item.name}" type="{$item.type}" value={$item.value} onclick="{$item.onclick}">
        {/if}
    {/foreach} 
</form>
<!---------------  END WORKFLOW SHOWCASE ------------>