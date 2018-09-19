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
<form method='POST' name='EditView' id='ACLEditView'>
{sugar_csrf_form_token}
<input type='hidden' name='record' value='{$ROLE.id}'>
<input type='hidden' name='module' value='ACLRoles'>
<input type='hidden' name='action' value='Save'>
<input type='hidden' name='return_record' value='{$RETURN.record}'>
<input type='hidden' name='return_action' value='{$RETURN.action}'>
<input type='hidden' name='return_module' value='{$RETURN.module}'>
<input id="ACLROLE_SAVE_BUTTON" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" onclick="this.form.action.value='Save';aclviewer.save('ACLEditView');return false;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" > &nbsp;
<input id="ACLROLE_CANCEL_BUTTON" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" class='button' accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" type='button' name='save' value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class='button' onclick='aclviewer.view("{$ROLE.id}", "All");'>
<br>
<TABLE width='100%' class='detail view' border='0' cellpadding=0 cellspacing = 1  >
{if !empty($CATEGORIES[$CATEGORY_NAME])}
	<TR>
	{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
		{foreach from=$CATEGORIES[$CATEGORY_NAME] item="ACTIONS"}
			{foreach from=$ACTIONS item="ACTION" key="ACTION_NAME_ACTIVE"}
				{if $ACTION_NAME==$ACTION_NAME_ACTIVE}
					<td align='center'><div align='center'><b>{$ACTION_LABEL}</b></div></td>
				{/if}
			{/foreach}
		{/foreach}
	{foreachelse}
	
	          <td colspan="2">&nbsp;</td>
	
	{/foreach}
	</TR>
	
	<TR>
	{foreach from=$ACTION_NAMES item="ACTION_LABEL" key="ACTION_NAME"}
	    {foreach from=$CATEGORIES[$CATEGORY_NAME] item="ACTIONS"}
	        {foreach from=$ACTIONS item="ACTION" key="ACTION_NAME_ACTIVE"}
	            {if $ACTION_NAME==$ACTION_NAME_ACTIVE}	
					<td nowrap width='{$TDWIDTH}%' style="text-align: center;" >
					<div  style="display: none" id="{$ACTION.id}">
					{if $APP_LIST.moduleList[$CATEGORY_NAME]==$APP_LIST.moduleList.Users && $ACTION_LABEL != $MOD.LBL_ACTION_ADMIN}
					<select DISABLED class="acl{$ACTION.accessName}" name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; aclviewer.toggleDisplay('{$ACTION.id}');" >
				   		{html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
					</select>
					{else}
                    <select class="acl{$ACTION.accessName}" name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; aclviewer.toggleDisplay('{$ACTION.id}');" >
                        {html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
                    </select>					
					{/if}
					</div>
					<div class="acl{$ACTION.accessName}"  id="{$ACTION.id}link" onclick="aclviewer.toggleDisplay('{$ACTION.id}')">{$ACTION.accessName}</div>
					</td>
	            {/if}
	        {/foreach}
	    {/foreach}
	{foreachelse}
	          <td colspan="2">&nbsp;</td>
	{/foreach}
	
	</TR>
{else}
    <tr> <td colspan="2">No Actions Defined</td></tr>
{/if}
</TABLE>
