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
<style>
{literal}
.searchHighlight{
    background-color: #FFFF00;
}

.detail_label{
	color:#666666;
	font-weight:bold;
}
.odd {
    background-color: #f6f6f6;
}
.even {
    background-color: #eeeeee;
}
div.sec .odd a, div.sec .even a {
    font-size: 12px;
}
#gsdetail_div {
    overflow-x:auto;
}
{/literal}
</style>
<!-- Global Search Detail View -->
<div id="gsdetail_div">
<div><h3>{$BEAN_NAME}</h3></div>
<br>
<div style="height:325px;width:300px" >
<table>
	{foreach from=$DETAILS item=DETAIL name="recordlist"}
	{if !$fields[$DETAIL.field].hidden}
    <tr>
		<td class="detail_label {if $smarty.foreach.recordlist.index % 2 == 0}odd{else}even{/if}">{$DETAIL.label|strip_semicolon}:</td>
		<td class="{if $smarty.foreach.recordlist.index % 2 == 0}odd{else}even{/if}">
		{if !empty($DETAIL.customCode)}
            {eval var=$DETAIL.customCode}
        {else}
            {sugar_field parentFieldArray='fields' vardef=$fields[$DETAIL.field] displayType='wirelessDetailView' displayParams='' typeOverride=$DETAIL.type}
        {/if}
		</td>
	</tr>
    {/if}
	{/foreach}
	<tr><td>&nbsp;<td></tr>
	<tr>
	<td colspan="2">
	<h3>{$LBL_GS_HELP}</h3>
	</td>
	</tr>
</table>
</div>
<div style="padding-right:200px">
</div>
</div>
