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
<h4>{$lblSearchResults} - <i>{$searchString|escape:'html':'UTF-8'}</i>:</h4>
<hr>
{if count($charts)}
<h3>{sugar_translate label='LBL_BASIC_CHARTS' module='Home'}</h3>
<table width="100%">
	{foreach from=$charts item=chart}
	<tr>
		<td width="100%" align="left"><a href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.icon}</a>&nbsp;<a class="mbLBLL" href="#" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
{/if}
{if count($myFavoriteReports)}
<h3>{sugar_translate label='LBL_MY_FAVORITE_REPORT_CHARTS' module='Home'}</h3>
<table width="100%">
	{foreach from=$myFavoriteReports item=chart}
	<tr>
		<td width="100%" align="left">&nbsp;<a class="mbLBLL" href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
{/if}
{if count($mySavedReports)}
<h3>{sugar_translate label='LBL_MY_SAVED_REPORT_CHARTS' module='Home'}</h3>
<table width="100%">
	{foreach from=$mySavedReports item=chart}
	<tr>
		<td width="100%" align="left">&nbsp;<a class="mbLBLL" href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
{/if}
{if count($myTeamReports)}
<h3>{sugar_translate label='LBL_MY_TEAM_REPORT_CHARTS' module='Home'}</h3>
<table width="100%">
	{foreach from=$myTeamReports item=chart}
	<tr>
		<td width="100%" align="left">&nbsp;<a class="mbLBLL" href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
{/if}
{if count($globalReports)}
<h3>{sugar_translate label='LBL_GLOBAL_REPORT_CHARTS' module='Home'}</h3>
<table width="100%">
	{foreach from=$globalReports item=chart}
	<tr>
		<td width="100%" align="left">&nbsp;<a class="mbLBLL" href="javascript:void(0)" onclick="{$chart.onclick}">{$chart.title}</a><br /></td>
	</tr>
	{/foreach}
</table>
{/if}
