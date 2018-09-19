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
<!-- BEGIN: main -->

{$chartResources}
<script>SUGAR.loadChart = true;</script>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<form action="index.php" method="post" name="DetailView" id="form">
{sugar_csrf_form_token}
			<input type="hidden" name="module" value="CampaignLog">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="isDuplicate" value=false>
			<input type="hidden" name="action">
			<input type="hidden" name="return_module">
			<input type="hidden" name="return_action">
			<input type="hidden" name="return_id" >
			<input type="hidden" name="campaign_id" value="{$ID}">
			<input type="hidden" name="mode" value="">
			<input id="deleteTestEntriesButtonId" title="{$MOD.LBL_TRACK_DELETE_BUTTON_TITLE}" class="button" onclick="this.form.module.value='Campaigns'; this.form.action.value='Delete';this.form.return_module.value='Campaigns'; this.form.return_action.value='TrackDetailView';this.form.mode.value='Test';return confirm('{$MOD.LBL_TRACK_DELETE_CONFIRM}');" type="submit" name="button" value="  {$MOD.LBL_TRACK_DELETE_BUTTON_LABEL}  ">
	</td>
	<td align='right'><span style="{$DISABLE_LINK}" >
		<input type="button" class="button" id="launch_wizard_button" onclick="javascript:window.location='index.php?module=Campaigns&action=WizardHome&record={$ID}';" value="{$MOD.LBL_TO_WIZARD_TITLE}" />
		<input type="button" class="button" id="view_status_button" onclick="javascript:window.location='index.php?module=Campaigns&action=TrackDetailView&record={$ID}';" value="{$MOD.LBL_TRACK_BUTTON_LABEL}" /></SPAN>{$ADMIN_EDIT}
		<input type="button" class="button" id="view_details_button" onclick="javascript:window.location='index.php?module=Campaigns&action=DetailView&record={$ID}';" value="{$MOD.LBL_TODETAIL_BUTTON_LABEL}" />
	</td>
	</form>
	<td align='right'>{$ADMIN_EDIT}</td>
</tr>
</table>
<p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="detail view">
<tr>
{$PAGINATION}
	<td width="20%"><slot>{$MOD.LBL_NAME}</slot></td>
	<td width="30%"><slot>{$NAME}</slot></td>
	<td width="20%"><slot>{$MOD.LBL_ASSIGNED_TO}</slot></td>
	<td width="30%"><slot>{$ASSIGNED_TO}</slot></td>
	</tr><tr>
	<td width="20%"><slot>{$MOD.LBL_CAMPAIGN_STATUS}</slot></td>
	<td width="30%"><slot>{$STATUS}</slot></td>

	<td width="20%"><slot>{$MOD.LBL_TEAM}</slot></td>
	<td width="30%"><slot>{$TEAM_NAME}</slot></td>
	</tr><tr>
	<td width="20%"><slot>{$MOD.LBL_CAMPAIGN_START_DATE}</slot></td>
	<td width="30%"><slot>{$START_DATE}</slot></td>
	<td ><slot>{$APP.LBL_DATE_MODIFIED}&nbsp;</slot></td>
	<td ><slot>{$DATE_MODIFIED} {$APP.LBL_BY} {$MODIFIED_BY}</slot></td>
	</tr><tr>
	<td width="20%"><slot>{$MOD.LBL_CAMPAIGN_END_DATE}</slot></td>
	<td width="30%"><slot>{$END_DATE}</slot></td>
	<td ><slot>{$APP.LBL_DATE_ENTERED}&nbsp;</slot></td>
	<td ><slot>{$DATE_ENTERED} {$APP.LBL_BY} {$CREATED_BY}</slot></td>
	</tr><tr>
	<td width="20%"><slot>{$MOD.LBL_CAMPAIGN_TYPE}</slot></td>
	<td width="30%"><slot>{$TYPE}</slot></td>
	<td width="20%"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr><tr>
	<td width="20%"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	<td width="20%"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr><tr>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_BUDGET} ({$CURRENCY})</slot></td>
	<td width="30%"><slot>{$BUDGET}</slot></td>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_IMPRESSIONS}</slot></td>
	<td width="30%" nowrap><slot>{$IMPRESSIONS}</slot></td>
	</tr><tr>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_EXPECTED_COST} ({$CURRENCY})</slot></td>
	<td width="30%"><slot>{$EXPECTED_COST}</slot></td>
		<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_OPPORTUNITIES_WON}</slot></td>
	<td width="30%"><slot>{$OPPORTUNITIES_WON}</slot></td>
	</tr><tr>
	</tr><tr>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_ACTUAL_COST} ({$CURRENCY})</slot></td>
	<td width="30%"><slot>{$ACTUAL_COST}</slot></td>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_COST_PER_IMPRESSION} ({$CURRENCY})</slot></td>
	<td width="30%" nowrap><slot>{$COST_PER_IMPRESSION}</slot></td>
	</tr><tr>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_EXPECTED_REVENUE} ({$CURRENCY})</slot></td>
	<td width="30%" nowrap><slot>{$EXPECTED_REVENUE}</slot></td>
	<td width="20%" nowrap><slot>{$MOD.LBL_CAMPAIGN_COST_PER_CLICK_THROUGH} ({$CURRENCY})</slot></td>
	<td width="30%"><slot>{$COST_PER_CLICK_THROUGH}</slot></td>
	</tr><tr>
	<td width="20%"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	<td width="20%"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr>
<!--
	<tr>
	<td width="20%" valign="top" valign="top"><slot>{$MOD.LBL_CAMPAIGN_OBJECTIVE}</slot></td>
	<td colspan="3"><slot>{$OBJECTIVE}</slot></td>
</tr><tr>
	<td width="20%" valign="top" valign="top"><slot>{$MOD.LBL_CAMPAIGN_CONTENT}</slot></td>
	<td colspan="3"><slot>{$CONTENT}</slot></td>
</tr>
-->
</table>
</p>
<div align=center class="reportChartContainer">{$MY_CHART_ROI}</div>

<!-- END: main -->
