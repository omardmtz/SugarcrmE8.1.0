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

<div id='SpotResults'>
{if !empty($displayResults)}
    {foreach from=$displayResults key=module item=data}
    <section>
        <div class="resultTitle">
            {if isset($appListStrings.moduleList[$module])}
                {$appListStrings.moduleList[$module]}
            {else}
                {$module}
            {/if}
            {if !empty($displayMoreForModule[$module])}
                {assign var="more" value=$displayMoreForModule[$module]}
                <br>
                <small class='more' onclick="DCMenu.spotZoom('{$more.query}', '{$module}', '{$more.offset}');">({$more.countRemaining} {$appStrings.LBL_SEARCH_MORE})</small>
            {/if}
        </div>
            <ul>
                {foreach from=$data key=id item=name}
                        <div class="gsLinkWrapper" >
                            <a href="index.php?module={$module}&action=DetailView&record={$id}" class="gs_link">{$name}</a>
                        </div>
                        </div>
                {/foreach}
            </ul>
        <div class="clear"></div>
    </section>
    {/foreach}
    <a href='index.php?module=Home&action=UnifiedSearch&search_form=false&advanced=false&query_string={$queryEncoded|escape:'html':'UTF-8'}' class="resultAll" data-sidecar-rewrite="false">
        {$appStrings.LNK_SEARCH_NONFTS_VIEW_ALL}
    </a>
{else}
    <section class="resultNull">
        <h1>{$appStrings.LBL_EMAIL_SEARCH_NO_RESULTS}</h1>
        <div style="float:right;">
            <a href="index.php?module=Home&action=UnifiedSearch&search_form=false&advanced=false&query_string={$queryEncoded|escape:'html':'UTF-8'}" data-sidecar-rewrite="false">{$appStrings.LNK_ADVANCED_SEARCH}</a>
        </div>
    </section>
{/if}
</div>
