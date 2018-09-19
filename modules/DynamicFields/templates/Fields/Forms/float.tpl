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
		<input type='text' id='default' name='default' value='{$vardef.default}'>
		<script>
		addToValidate('popup_form', 'default', 'float', false,'{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}' );
		formWithPrecision = new addToValidatePrecision('popup_form_id', 'default', 'precision');
		</script>
	{else}
		<input type='hidden' name='default' id='default' value='{$vardef.default}'>{$vardef.default}
	{/if}
	</td>
</tr>
<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_MAX_SIZE"}:</td>
	<td>
	{if $hideLevel < 5}
		<input type='text' name='len' value='{$vardef.len|default:18}' onchange="changeMaxLength(document.getElementById('default'),this.value);"></td>
        <script>
            addToValidate('popup_form', 'len', 'int', false, '{sugar_translate module="DynamicFields" label="COLUMN_TITLE_MAX_SIZE"}' );

            //warn user if default value is greater than max length
            function changeMaxLength(field, length) {ldelim}
                field.maxLength = parseInt(length);

                //clear previous max length validation
                removeFromValidate('popup_form', field.name);
                //add new max length validation based on specified value
                addToValidateMaxLength('popup_form', field.name, 'float', false, field.maxLength,  '{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}');
            {rdelim}
        </script>
	{else}
		<input type='hidden' name='len' value='{$vardef.len}'>{$vardef.len}
	{/if}
	</td>
</tr>
{if $range_search_option_enabled}
<tr>	
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_ENABLE_RANGE_SEARCH"}:</td>
    <td>
        <input type='checkbox' name='enable_range_search' value=1 {if !empty($vardef.enable_range_search) }checked{/if} {if $hideLevel > 5}disabled{/if} />
        {if $hideLevel > 5}<input type='hidden' name='enable_range_search' value='{$vardef.enable_range_search}'>{/if}
    </td>	
</tr>
{/if}
<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_PRECISION"}:</td>
	<td>
	{if $hideLevel < 5}
		<input type='text' id='precision' name='precision' value='{$vardef.precision|default:0}'>
		<script>addToValidate('popup_form', 'ext1', 'int', false,'{sugar_translate module="DynamicFields" label="COLUMN_TITLE_PRECISION"}' );</script>
	{else}
		<input type='hidden' name='precision' value='{$vardef.precision}'>{$vardef.precision}
	{/if}
	</td>
</tr>

{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}