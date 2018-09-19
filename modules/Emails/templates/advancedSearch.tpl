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
<br/>
<strong>{$mod_strings.LBL_SEARCH_IMPORTED_EMAIL}</strong>
<form name="advancedSearchForm" id="advancedSearchForm">
{sugar_csrf_form_token}
<table cellpadding="4" cellspacing="0" border="0" id='advancedSearchTable'>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="advancedSearchTD">
			{$app_strings.LBL_EMAIL_SUBJECT}:<br/>
			<input type="text" class="input" name="name" id="searchSubject" size="20">
		</td>
	</tr>
	<tr>
		<td class="advancedSearchTD">
			{$app_strings.LBL_EMAIL_FROM}:<br/>
			<input type="text" class="input" name="from_addr" id="searchFrom" size="20">
		</td>
	</tr>
	<tr>
		<td class="advancedSearchTD">
			{$app_strings.LBL_EMAIL_TO}:<br/>
			<input type="text" class="input" name="to_addrs" id="searchTo" size="20">
		</td>
	</tr>
    <tr class="toggleClass visible-search-option">
        <td ><a href="javascript:void(0);" onclick="SE.search.toggleAdvancedOptions();">{$mod_strings.LBL_MORE_OPTIONS}</a></td>
        <td>&nbsp;</td>
    </tr>
	<tr class="toggleClass yui-hidden">
		<td class="advancedSearchTD" style="padding-bottom: 2px">
			{$app_strings.LBL_EMAIL_SEARCH_DATE_FROM}:&nbsp;<i>({$dateFormatExample})</i><br/>
			<input name='searchDateFrom' id='searchDateFrom' onblur="parseDate(this, '{$dateFormat}');" maxlength='10' size='11' value="" type="text">&nbsp;
			{sugar_getimage name="jscalendar" ext=".gif" alt=$app_strings.LBL_ENTER_DATE other_attributes='align="absmiddle" id="searchDateFrom_trigger" '}
		</td>
	</tr>

	<tr class="toggleClass yui-hidden">
		<td class="advancedSearchTD">
			{$app_strings.LBL_EMAIL_SEARCH_DATE_UNTIL}:&nbsp;<i>({$dateFormatExample})</i><br/>
			<input name='searchDateTo' id='searchDateTo' onblur="parseDate(this, '{$dateFormat}');" maxlength='10' size='11' value="" type="text">&nbsp;
			{sugar_getimage name="jscalendar" ext=".gif" alt=$app_strings.LBL_ENTER_DATE other_attributes='align="absmiddle" id="searchDateTo_trigger" '}		
		</td>
	</tr>

    <tr class="toggleClass yui-hidden">
        <td class="advancedSearchTD">
        {sugar_translate label="LBL_ASSIGNED_TO"}: <br/>
        <input name="assigned_user_name" class="sqsEnabled" tabindex="2" id="assigned_user_name" size="" value="{$currentUserName}" type="text" >
        <input name="assigned_user_id" id="assigned_user_id" value="{$currentUserId}" type="hidden">      
        
        <a href="javascript:void(0);">
            <img src="{sugar_getimagepath file='select.gif'}" align="absmiddle" border="0" alt=$mod_strings.LBL_EMAIL_SELECTOR onclick='open_popup("Users", 600, 400, "", true, false, {literal}{"call_back_function":"set_return","form_name":"advancedSearchForm","field_to_name_array":{"id":"assigned_user_id","name":"assigned_user_name"}}{/literal}, "single", true);'>
        </a>
        </td>
    </tr>
      <tr class="toggleClass yui-hidden">
        <td class="advancedSearchTD">
        {$mod_strings.LBL_HAS_ATTACHMENT}<br/>
        {html_options options=$attachmentsSearchOptions name='attachmentsSearch' id='attachmentsSearch'} 
        </td>
    </tr>
    <tr class="toggleClass yui-hidden">
        <td NOWRAP class="advancedSearchTD">
        {$mod_strings.LBL_EMAIL_RELATE}:<br/>
        {html_options options=$linkBeansOptions name='data_parent_type_search' id='data_parent_type_search'}
        <input id="data_parent_id_search" name="data_parent_id_search" type="hidden" value="">
        <br/><br/>
        <input class="sqsEnabled" id="data_parent_name_search" name="data_parent_name_search" type="text" value="">
        <a href="javascript:void(0);"><img src="{sugar_getimagepath file='select.gif'}" align="absmiddle" border="0" alt=$mod_strings.LBL_EMAIL_SELECTOR onclick="SUGAR.email2.composeLayout.callopenpopupForEmail2('_search',{ldelim}'form_name':'advancedSearchForm'{rdelim} );">
         </a>
        </td>
    </tr>
     <tr class="toggleClass yui-hidden">
        <td class="visible-search-option"><a href="javascript:void(0);" onclick="SE.search.toggleAdvancedOptions();">{$mod_strings.LBL_LESS_OPTIONS}</a></td>
        <td>&nbsp;</td>
    </tr>
	<tr>
		<td NOWRAP>
			<br />&nbsp;<br />
			<input type="button" id="advancedSearchButton" class="button" onclick="SUGAR.email2.search.searchAdvanced()" value="   {$app_strings.LBL_SEARCH_BUTTON_LABEL}   ">&nbsp;
			<input type="button" class="button" onclick="SUGAR.email2.search.searchClearAdvanced()" value="   {$app_strings.LBL_CLEAR_BUTTON_LABEL}   ">
		</td>
	</tr>
</table>
</form>
