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
{if count($resultSet) > 0}
    {foreach from=$resultSet item=result name=searchresult}
    <section class="{if $smarty.foreach.searchresult.index  is even}even{else}odd{/if}">
        <div class="resultTitle">

        {$result->getModuleName()|upper}
 		</div>
 		{capture assign=url}index.php?module={$result->getModule()}&record={$result->getId()}&action=DetailView{/capture}
            <ul class='fts_spot_ul' >
                <li >
                    <span class="spot_fts_summary"><a href="{$url}">{$result->getSummaryText()}</a></span>
                    <br>
                    <span class="details">
                            {foreach from=$result->getHighlightedHitText(1) key=k item=v}
                            <p>
                                {sugar_translate label=$v.label module=$v.module}: {$v.text}
                                <br>
                            </p>
                            {/foreach}
                    </span>
                </li>
            </ul>
        <div class="clear"></div>
    </section>
    {/foreach}

    <p class="fullResults"><a href="index.php?module=Home&append_wildcard=true&action=spot&full=true&q={$queryEncoded|escape:'html':'UTF-8'}">{$APP.LNK_SEARCH_FTS_VIEW_ALL}</a></p>
{elseif !isset($resultSet) }
    <section class="resultNull">
        <h1>{$APP.LBL_SEARCH_UNAVAILABLE}</h1>
   	</section>
{else}
	<section class="resultNull">
        <h1>{$APP.LBL_EMAIL_SEARCH_NO_RESULTS}</h1>
        <div style="float:right;">
            <a href="index.php?module=Home&append_wildcard=true&action=spot&full=true&q={$queryEncoded|escape:'html':'UTF-8'}">{$APP.LNK_ADVANCED_SEARCH}</a>
        </div>
   	</section>
{/if}
