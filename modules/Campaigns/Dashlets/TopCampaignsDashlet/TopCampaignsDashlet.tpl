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


<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="list view">
	<tr>
		<th>&nbsp;</td>
		<th align="center">{$lbl_campaign_name}</td>
		<th align="center">{$lbl_revenue}</td>
	</tr>
	{counter name="num" assign="num"}
	{foreach from=$top_campaigns item="campaign"}
	<tr>
		<td class="oddListRowS1" align="center" valign="top" width="6%">{$num}.</td>
		<td class="oddListRowS1" align="left" valign="top" width="74%"><a href="index.php?module=Campaigns&action=DetailView&record={$campaign.campaign_id}">{$campaign.campaign_name}</a></td>
		<td class="oddListRowS1" align="left" valign="top" width="20%">{$campaign.revenue}</td>
	</tr>
	{counter name="num"}
	{/foreach}
</table>
