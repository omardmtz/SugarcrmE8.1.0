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
<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}:</td>
	<td>
	{if $hideLevel < 5}
		{html_options id='default' name='default' options=$default_values selected=$vardef.display_default}
	{else}
		<input type='hidden' id='default' name='default' value='{$vardef.display_default}'>{$vardef.display_default}
	{/if}
	</td>
</tr>
{* Readonly fields should not have a massupdate option *}
{if empty($vardef.readonly)}
<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_MASS_UPDATE"}:</td>
	<td>
	{if $hideLevel < 5}
		<input type="checkbox" id="massupdate" name="massupdate" value="1" {if !empty($vardef.massupdate)}checked{/if}/>
	{else}
		<input type="checkbox" id="massupdate" name="massupdate" value="1" disabled {if !empty($vardef.massupdate)}checked{/if}/>
	{/if}
	</td>
</tr>
{/if}
{if $range_search_option_enabled}
<tr>	
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_ENABLE_RANGE_SEARCH"}:</td>
    <td>
        <input type='checkbox' name='enable_range_search' value=1 {if !empty($vardef.enable_range_search) }checked{/if} {if $hideLevel > 5}disabled{/if} />
        {if $hideLevel > 5}<input type='hidden' name='enable_range_search' value='{$vardef.enable_range_search}'>{/if}
    </td>	
</tr>
{/if}
{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}
