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

{include file="modules/DynamicFields/templates/Fields/Forms/coreTop.tpl"}
{if $range_search_option_enabled}
<tr>	
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_ENABLE_RANGE_SEARCH"}:</td>
    <td>
        <input type='checkbox' name='enable_range_search' value=1 {if !empty($vardef.enable_range_search) }checked{/if} {if $hideLevel > 5}disabled{/if} />
        {if $hideLevel > 5}<input type='hidden' name='enable_range_search' value='{$vardef.enable_range_search}'>{/if}
    </td>	
</tr>
{/if}
<tr><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}:</td><td>
{if $hideLevel < 5}
<input type='text' id='default' name='default' value='{sugar_currency_format var=$vardef.default currency_symbol=false}'>
<script>
addToValidate('popup_form', 'default', 'float', false,'{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}' );
</script>
{else}
<input type='hidden' name='default' value='{sugar_currency_format var=$vardef.default currency_symbol=false}'>{sugar_currency_format var=$vardef.default}
{/if}
</td></tr>{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}
