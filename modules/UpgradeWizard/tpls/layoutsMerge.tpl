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
 <br>
 <p>{$CONFIRM_LAYOUT_DESC}</p>
 <br>
 
 <table width="100%" id="layoutSelection">
 <thead>
    <tr>
        {if $showCheckboxes}
        <th width="5%">&nbsp;</th>
        {/if}
        <th width="25%">{$APP.LBL_MODULE}</th>
        <th width="50%">{$MOD.LBL_LAYOUT_MODULE_TITLE}</th>
    </tr>
</thead>
<tbody>
{foreach from=$METADATA_DATA key=moduleKey item=data}
    <tr>
        {if $showCheckboxes}
        <td>
            <input type="checkbox" name="lm_{$moduleKey}" checked>
        </td>
        {/if}
        <td>
        {$data.moduleName}
        </td>
        <td>
            {foreach from=$data.layouts item=layout}
                    {$layout.label}
                 <br> 
            {/foreach}
        </td>
    </tr>
{/foreach}
</tbody>
</table>

<div id="upgradeDiv" style="display:none">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr><td>
           <p><img src='modules/UpgradeWizard/processing.gif' alt='{$mod_strings.LBL_PROCESSING}'></p>
        </td></tr>
     </table>
 </div>
