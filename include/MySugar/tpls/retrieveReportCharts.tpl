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
<table width="100%">
	{foreach from=$reportCharts item=chart}
	<tr>
		<td align="left"><a class="mbLBLL" href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
