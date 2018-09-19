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
<style type="text/css">{literal}
.warn { font-style:italic;
        font-weight:bold;
        color:red;
}{/literal}
</style>

<script type='text/javascript' src='{sugar_getjspath file='include/javascript/popup_helper.js'}'></script>

<div id="{$tableID}_content">
    <table cellpadding='0' cellspacing='0' width='50%' border='0' class='list view'>
        {include file='modules/Import/tpls/listviewpaginator.tpl'}
        <tr height='20'>
            {counter start=0 name="colCounter" print=false assign="colCounter"}
            {if $displayColumns eq false}
                <th scope='col'  style="text-align: left;" nowrap="nowrap" colspan="{$maxColumns}">{$MOD.LBL_MISSING_HEADER_ROW}</th>
            {else}
                {foreach from=$displayColumns key=colHeader item=label}
                    <th scope='col' nowrap="nowrap">
                        <div style='white-space: nowrap;'width='100%' align='left' >
                        {$label}
                        </div>
                    </th>
                    {counter name="colCounter"}
                {/foreach}
            {/if}
        </tr>
        {counter start=$pageData.offsets.current print=false assign="offset" name="offset"}
        {foreach name=rowIteration from=$data key=id item=rowData}
            {counter name="offset" print=false}

            {if $smarty.foreach.rowIteration.iteration is odd}
                {assign var='_rowColor' value=$rowColor[0]}
            {else}
                {assign var='_rowColor' value=$rowColor[1]}
            {/if}
            <tr height='20' class='{$_rowColor}S1'>
                {counter start=0 name="colCounter" print=false assign="colCounter"}
                {foreach from=$rowData key=col item=params}
                    {strip}
                    <td align='left' valign="top">
                        {$params}
                    </td>
                    {/strip}
                    {counter name="colCounter"}
                {/foreach}
                </tr>
        {foreachelse}
        <tr height='20' class='{$rowColor[0]}S1'>
            <td colspan="{$colCounter}">
                <em>{$APP.LBL_NO_DATA}</em>
            </td>
        </tr>
        {/foreach}
    {include file='modules/Import/tpls/listviewpaginator.tpl'}
    </table>
</div>