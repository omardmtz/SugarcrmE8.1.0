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
{capture name=getLink assign=link}{sugar_fetch object=$parentFieldArray key=$col}{/capture}
{if $vardef.gen && $vardef.default}
    {capture name=getDefault assign=default}{if is_string($vardef.default)}{$vardef.default}{else}{$link}{/if}{/capture}
    {sugar_replace_vars subject=$default use_curly=true assign='link' fields=$parentFieldArray}
{/if}

<a href="{$link|to_url}" {if $displayParams.link_target}target='{$displayParams.link_target}'{elseif $vardef.link_target}target='{$vardef.link_target}'{/if}>{$link}</a>
