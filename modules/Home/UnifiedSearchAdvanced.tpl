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

<form name='UnifiedSearchAdvanced' method='get'>
{sugar_csrf_form_token}
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='query_string' value=''>
<input type='hidden' name='advanced' value='true'>
<input type='hidden' name='action' value='UnifiedSearch'>
<input type='hidden' name='search_form' value='false'>

<table border="0" class="actionsContainer">
<tr><td>
<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}" type="submit" class="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="document.UnifiedSearchAdvanced.action.value='index'; document.UnifiedSearchAdvanced.module.value='Administration';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
</td></tr>
</table>

<table width='600' class='edit view' border='0' cellpadding='0' cellspacing='1'>
{foreach from=$MODULES_TO_SEARCH name=m key=module item=info}
{if $smarty.foreach.m.first}
	<tr style='padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px'>
{/if}
	<td width='1' style='border: none; padding: 0px 10px 0px 0px; margin: 0px 0px 0px 0px'>
		<input class='checkbox' id='cb_{$module}' type='checkbox' name='search_mod_{$module}' value='true' {if $info.checked}checked{/if}>
	</td>
	<td style='border: none; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; cursor: hand; cursor: pointer' onclick="document.getElementById('cb_{$module}').checked = !document.getElementById('cb_{$module}').checked;">
		{$info.translated}
	</td>
{if $smarty.foreach.m.index % 2 == 1}
	</tr><tr style='padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px'>
{/if}
{/foreach}
</tr>
</table>

<table border="0" class="actionsContainer">
<tr><td>
<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_TITLE}" type="submit" class="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="document.UnifiedSearchAdvanced.action.value='index'; document.UnifiedSearchAdvanced.module.value='Administration';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
</td></tr>
</table>
</form>