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

{if $hideLevel < 5 && $show_fts}
<tr>
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_FTS"}:</td>
    <td>
        {html_options name="full_text_search[enabled]" id="fts_field_config" selected=$fts_field_config_selected options=$fts_field_config onchange="ModuleBuilder.toggleBoost()"}
        <img border="0" class="inlineHelpTip" alt="Information" src="themes/Sugar/images/helpInline.png" onclick="return SUGAR.util.showHelpTips(this,'{$mod_strings.LBL_POPHELP_FTS_FIELD_CONFIG}','','' );">
    </td>
</tr>
<tr id="ftsFieldBoostRow" {if $fts_field_config_selected < 2}style="display:none"{/if}>
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_FTS_BOOST"}:</td>
    <td>
        <input type="text" name="full_text_search[boost]" id="fts_field_boost" value="{$fts_field_boost_value}" />
        <img border="0" class="inlineHelpTip" alt="Information" src="themes/Sugar/images/helpInline.png" onclick="return SUGAR.util.showHelpTips(this,'{$mod_strings.LBL_POPHELP_FTS_FIELD_BOOST}','','' );">
    </td>
</tr>
{/if}

{include file='modules/DynamicFields/templates/Fields/Forms/coreDependent.tpl'}

{if $vardef.type != 'bool' && !$hideRequired}
<tr ><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_REQUIRED_OPTION"}:</td><td><input type="checkbox" name="required" value="1" {if !empty($vardef.required)}CHECKED{/if} {if $hideLevel > 5}disabled{/if}/>{if $hideLevel > 5}<input type="hidden" name="required" value="{$vardef.required}">{/if}</td></tr>
{/if}
<tr>
{if !$hideReportable}
<td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_REPORTABLE"}:</td>
<td>
	<input type="checkbox" name="reportableCheckbox" value="1" {if !empty($vardef.reportable)}CHECKED{/if} {if $hideLevel > 5}disabled{/if} 
	onClick="if(this.checked) document.getElementById('reportable').value=1; else document.getElementById('reportable').value=0;"/>
	<input type="hidden" name="reportable" id="reportable" value="{if !empty($vardef.reportable)}{$vardef.reportable}{else}0{/if}">
</td>
</tr>
{/if}

{if $auditable && !in_array($vardef.type, array('parent', 'html'))}
<tr><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_AUDIT"}:</td><td><input id="auditedCheckbox" type="checkbox" name="audited" value="1" {if !empty($vardef.audited) || !empty($vardef.pii) }CHECKED{/if} {if $hideLevel > 5}disabled{/if}/>{if $hideLevel > 5}<input type="hidden" name="audited" value="{$vardef.audited}">{/if}</td></tr>
{if !in_array($vardef.type, array('bool', 'image', 'relate'))}
<tr>
    <td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_PII"}:</td>
    <td>
        <input id="piiCheckbox"  type="checkbox" onclick="ModuleBuilder.enforceAuditPii()" name="pii" value="1" {if !empty($vardef.pii) }CHECKED{/if} {if $hideLevel > 5}disabled{/if}/>{if $hideLevel > 5}<input type="hidden" name="pii" value="{$vardef.pii}">{/if}
        <img border="0" class="inlineHelpTip" alt="Information" src="themes/Sugar/images/helpInline.png" onclick="return SUGAR.util.showHelpTips(this,'{$mod_strings.LBL_POPHELP_PII}','','' );">
    </td>
</tr>
{/if}
{/if}

{if !$hideImportable}
<tr><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_IMPORTABLE"}:</td><td>
    {if $hideLevel < 5}
        {html_options name="importable" id="importable" selected=$vardef.importable options=$importable_options}
        {sugar_help text=$mod_strings.LBL_POPHELP_IMPORTABLE FIXX=250 FIXY=80}
    {else}
        {if isset($vardef.importable)}{$importable_options[$vardef.importable]}
        {else}{$importable_options.true}{/if}
    {/if}
</td></tr>
{/if}
{if !$hideDuplicatable}
<tr><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DUPLICATE_MERGE"}:</td><td>
{if $hideLevel < 5}
    {html_options name="duplicate_merge" id="duplicate_merge" selected=$vardef.duplicate_merge_dom_value options=$duplicate_merge_options}
    {sugar_help text=$mod_strings.LBL_POPHELP_DUPLICATE_MERGE FIXX=250 FIXY=80}
{else}
    {if isset($vardef.duplicate_merge_dom_value)}{$vardef.duplicate_merge_dom_value}
    {else}{$duplicate_merge_options[0]}{/if}
{/if}
</td></tr>
{/if}
</table>

{if !empty($vardef.group)}
    <input type="hidden" name="group" value="{$vardef.group}">
{/if}

{if !empty($vardef.options) && !empty($vardef.type) && $vardef.type == 'parent_type'}
    <input type="hidden" name="options" value="{$vardef.options}">
{/if}


<script>
    ModuleBuilder.enforceAuditPii();
</script>