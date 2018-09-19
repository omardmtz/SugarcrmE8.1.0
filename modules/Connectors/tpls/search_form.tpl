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
<table class="h3Row" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td nowrap>
<h3>{$mod.LBL_MODIFY_SEARCH}</h3></td><td width='100%'>
<IMG height='1' width='1' src="{sugar_getjspath file='include/images/blank.gif'}" alt=''>
</td>
</tr>
</table>
<form name='SearchForm' method='POST' id='SearchForm'>
{sugar_csrf_form_token}
    {sugar_csrf_form_token}
 	<input type='hidden' name='source_id' id='source_id' value='{$source_id}' />
 	<input type='hidden' name='merge_module' value='{$module}' />
 	<input type='hidden' name='record' value='{$RECORD}' />
 	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabForm">
{if !empty($search_fields) }
 	<tr>
 	 {counter assign=field_count start=0 print=0} 
	 {foreach from=$search_fields key=field_name item=field_value} 
	 	{counter assign=field_count}
		{if ($field_count % 3 == 1 && $field_count != 1)}
		</tr><tr>
		{/if}
		<td nowrap="nowrap" width='10%' class="dataLabel">
		{$field_value.label}: 
		</td>
		<td nowrap="nowrap" width='30%' class="dataField">
		<input type='text' onkeydown='checkKeyDown(event);' name='{$field_name}' value='{$field_value.value}'/>
		</td>
	 {/foreach}
{else}
     {$mod.ERROR_NO_SEARCHDEFS_MAPPING}
{/if}
</table>
<input type='button' name='btn_search' id='btn_search' title="{$APP.LBL_SEARCH_BUTTON_LABEL}" accessKey="{$APP.LBL_SEARCH_BUTTON_KEY}" class="button" onClick="javascript:SourceTabs.search();" value="      {$APP.LBL_SEARCH_BUTTON_LABEL}      "/>&nbsp;
<input type='button' name='btn_clear' title="{$APP.LBL_CLEAR_BUTTON_LABEL}" class="button" onClick="javascript:SourceTabs.clearForm();" value="{$APP.LBL_CLEAR_BUTTON_LABEL}"/>
</form>
