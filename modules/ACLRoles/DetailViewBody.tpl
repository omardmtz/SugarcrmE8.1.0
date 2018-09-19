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

{strip}
<TABLE width='100%' class='detail view' border='0' cellpadding=0 cellspacing = 1  >
<TR>
<td style="background: transparent;"></td>
{foreach from=$ACTION_NAMES item="ACTION_NAME" }
	<td style="text-align: center;" scope="row"><b>{$ACTION_NAME}</b></td>
{foreachelse}

          <td colspan="2">&nbsp;</td>

{/foreach}
</TR>
{foreach from=$CATEGORIES item="TYPES" key="CATEGORY_NAME"}
	<TR>
	{if $APP_LIST.moduleList[$CATEGORY_NAME]=='Users'}
	<td nowrap width='1%' scope="row"><b>{$MOD.LBL_USER_NAME_FOR_ROLE}</b></td>
	{else}
	<td nowrap width='1%' scope="row"><b>{$APP_LIST.moduleList[$CATEGORY_NAME]}</b></td>
	{/if}
	{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
		{assign var='ACTION_FIND' value='false'}
		{foreach from=$TYPES item="ACTIONS" key="TYPE_NAME"}
			{foreach from=$ACTIONS item="ACTION" key="ACTION_NAME_ACTIVE"}
				{if $ACTION_NAME==$ACTION_NAME_ACTIVE}
					{assign var='ACTION_FIND' value='true'}
					<td  width='{$TDWIDTH}%' align='center'><div align='center' class="acl{$ACTION.accessLabel|capitalize}"><b>{$ACTION.accessName}</b></div></td>
				{/if}
			{/foreach}
		{/foreach}
		{if $ACTION_FIND=='false'}
			<td nowrap width='{$TDWIDTH}%' style="text-align: center;">
			<div><font color='red'>N/A</font></div>
			</td>
		{/if}
	{/foreach}
	</TR>
{foreachelse}
	<tr> <td colspan="2">No Actions</td></tr>
{/foreach}
</TABLE>
{/strip}