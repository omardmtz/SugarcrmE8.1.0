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
{if $property.type == "image"}
{if $count is not odd}</tr>{/if}
<tr>
    <td scope="row" width="20%">{$property.label}:<span class="error" id="resized_{$name}_img" style="display:none"> {$MOD.LBL_IMG_RESIZED}</span>{if $property.info_label != ""}{sugar_help text=$property.info_label}{/if} </td>
    <td colspan="3">
        <img src='{$property.path}' id='{$name}_img' style='margin-bottom: 10px;'>
        <input type='hidden' id='{$name}' name='{$name}' value='{$property.value}'>
        <script type='text/javascript'>
            {literal}
            YAHOO.util.Event.onDOMReady(function() {
                if(document.getElementById({/literal}"{$name}_img"{literal}).width>document.width/2){
                    document.getElementById({/literal}"{$name}_img"{literal}).width = document.width/2;
                    document.getElementById({/literal}"resized_{$name}_img"{literal}).style.display="";
                }
            });
            {/literal}
        </script>
    </td>
</tr>
{counter}
{else}
    {if $count is odd}
    <tr>
    {/if}
        <td scope="row" width="20%">{$property.label}:{if isset($property.required) && $property.required == true} <span class="required">*</span>{/if}{if $property.info_label != ""}{sugar_help text=$property.info_label}{/if} </td>
        <td  width="30%">
            {if isset($property.custom)}
                {$property.custom}
            {elseif $property.type == "text"}
                <input type='text' size='40' name='{$name}' id='{$name}' value='{$property.value}'>
            {elseif $property.type == "number"}
                <input type='text' size='10' name='{$name}' id='{$name}' value='{$property.value}' onchange="verifyNumber('{$name}')">
                {if isset($property.unit)}
                    {$property.unit}
                {/if}
            {elseif $property.type == "percent"}
                <input type='text' size='20' name='{$name}' id='{$name}' value='{$property.value}' onchange="verifyPercent('{$name}')">
            {elseif $property.type == "select"}
                {html_options name=$name options=$property.selectList selected=$property.value}
            {elseif $property.type == "multiselect"}
                <select name='{$name}[]' multiple size=4>
                {html_options options=$property.selectList selected=$property.value}
                </select>
            {elseif $property.type == "bool"}
                <input type="hidden" name='{$name}' value='false'>
                <input type='checkbox' name='{$name}' value='true' id='{$name}' {if $property.value == "true"}CHECKED{/if}>
            {elseif $property.type == "password"}
                <input type='password' size='20' name='{$name}' id='{$name}' value='{$property.value}'>
            {elseif $property.type == "file"}
                <input type="file" id='{$name}' name='{$name}' size="20" onBlur="checkFileType('{$name}',0);"/>
            {/if}
        </td>
    {if $count is not odd}
    </tr>
    {/if}
{/if}