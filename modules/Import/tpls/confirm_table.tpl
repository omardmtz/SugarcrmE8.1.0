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
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="importTable" class="detail view noBorder" style="box-shadow: none; -moz-box-shadow: none. -webkit-box-shadow: none;">
    <tbody>
        {foreach from=$SAMPLE_ROWS item=row name=row}
            <tr>
                {foreach from=$row item=value}
                    {if $smarty.foreach.row.first}
                        {if $HAS_HEADER}
                            <td scope="col" style="text-align: left;">{$value}</td>
                        {else}
                            <td scope="col" style="text-align: left;" colspan="{$column_count}">{$MOD.LBL_MISSING_HEADER_ROW}</td>
                        {/if}
                     {else}
                        <td class="impSample">{$value}</td>
                     {/if}
                {/foreach}
            </tr>
        {/foreach}
    </tbody>
</table>
