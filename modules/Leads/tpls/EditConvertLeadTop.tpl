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
{*  $Id: EditConvertLeadTop.tpl 21451 2007-04-04 22:04:34Z dwheeler $ *}

{*
<table class="edit view" id="convertLayoutExtraData"><tr>
<td scope="row">Required:</td>
<td><input type="checkbox" onclick="document.getElementById('convertRequired').value = this.checked" {if $required}checked="checked"{/if}/></td>
<td scope="row">Copy Data:</td>
<td><input type="checkbox"  onclick="document.getElementById('convertCopy').value = this.checked" {if $copyData}checked="checked"{/if}/></td>
<td scope="row">Allow Selection:</td>
<td>
<select>
    <option value="none" label="No">No</option>
    {if $relationships.length == 1}
        <option value="{$relationships.0.name}" label="Yes">Yes</option>
    {else}
	    {foreach from=$relationships item=rel}
	    <option value="{$rel.name}" label="{$rel.label}" {if $rel.selected}selected="selected"{/if}>{$rel.label}</option>
	    {/foreach}
    {/if}
</select>
</td></tr>
</table>
<div style="display:none" id="convertDataForSave">
<input type="hidden" name="convertRequired" id="convertRequired" value="{$required}">
<input type="hidden" name="convertCopy" id="convertCopy" value="{$copyData}">
<input type="hidden" name="convertSelection" id="convertSelection" value="{$select}">
</div> *}
{if !empty($warningMessage)}
<p class="error">{$warningMessage}</p>
{/if}
<script type="text/javascript">
//This script will be invoked by ModuleBuilder after the HTML is already on the page
//YAHOO.util.Dom.insertAfter("convertLayoutExtraData", "layoutEditorButtons");
//YAHOO.util.Dom.insertBefore("convertDataForSave", document.getElementById("prepareForSave").firstChild);
document.forms.prepareForSave.module.value = "Leads";
</script>
