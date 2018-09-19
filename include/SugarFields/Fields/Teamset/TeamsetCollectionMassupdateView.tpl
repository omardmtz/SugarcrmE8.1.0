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

{capture name=alt1 assign=alt_selectButton}{sugar_translate label='LBL_SELECT_TEAMS_LABEL'}{/capture}
{capture name=alt2 assign=alt_removeButton}{sugar_translate label='LBL_ALT_REMOVE_TEAM_ROW'}{/capture}
{capture name=alt3 assign=alt_addButton}{sugar_translate label='LBL_ALT_ADD_TEAM_ROW'}{/capture}
<script type="text/javascript" src='{sugar_getjspath file="include/SugarFields/Fields/Collection/SugarFieldCollection.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="include/SugarFields/Fields/Teamset/Teamset.js"}'></script>
<script type="text/javascript">
    var collection = (typeof collection == 'undefined') ? new Array() : collection;
    collection["{$displayParams.formName}_{$vardef.name}"] = new SUGAR.collection('{$displayParams.formName}', '{$vardef.name}', '{$module}', '{$displayParams.popupData}');
	collection["{$displayParams.formName}_{$vardef.name}"].show_more_image = false;
</script>
<input type="hidden" id="update_fields_{$vardef.name}_collection" name="update_fields_{$vardef.name}_collection" value="">
<input type="hidden" id="{$vardef.name}_new_on_update" name="{$vardef.name}_new_on_update" value="{$displayParams.new_on_update}">
<input type="hidden" id="{$vardef.name}_allow_update" name="{$vardef.name}_allow_update" value="{$displayParams.allow_update}">
<input type="hidden" id="{$vardef.name}_allow_new" name="{$vardef.name}_allow_new" value="{$displayParams.allow_new}">
<input type="hidden" id="{$vardef.name}_allowed_to_check" name="{$vardef.name}_allowed_to_check" value="false">
<input type="hidden" id="{$vardef.name}_mass" name="{$vardef.name}_mass" value="{$vardef.name}_table">
<table name='{$displayParams.formName}_{$vardef.name}_table' id='{$displayParams.formName}_{$vardef.name}_table' style="border-spacing: 0pt;">
	 <tr>
	    <td colspan='2' nowrap>
			<span class="id-ff multiple ownline">
            <button title="{$app_strings.LBL_ID_FF_SELECT}" type="button" class="button firstChild" value="{sugar_translate label='LBL_SELECT_BUTTON_LABEL'}" onclick='javascript:open_popup("Teams", 600, 400, "", true, false, {literal}{"call_back_function":"set_return_teams_for_editview","form_name": {/literal} "{$displayParams.formName}","field_name":"{$vardef.name}",{literal}"field_to_name_array":{"id":"team_id","name":"team_name"}}{/literal}, "MULTISELECT", true); if(collection["{$displayParams.formName}_{$vardef.name}"].more_status)collection["{$displayParams.formName}_{$vardef.name}"].js_more();' name="teamSelect">
            {sugar_getimage name="id-ff-select.png" alt="$alt_selectButton"}
            </button><button title="{$app_strings.LBL_ID_FF_ADD}" type="button" class="button lastChild" value="{sugar_translate label='LBL_ADD_BUTTON'}" onclick="javascript:collection['{$displayParams.formName}_{$vardef.name}'].add(); if(collection['{$displayParams.formName}_{$vardef.name}'].more_status)collection['{$displayParams.formName}_{$vardef.name}'].js_more();"  name="teamAdd">
            {sugar_getimage name="id-ff-add.png" alt="$alt_addButton"}</button>
			</span>			
		</td>
        <th scope='col' id="lineLabel_{$vardef.name}_primary" {if empty($values.role_field)}style="display:none"{/if}>
            {sugar_translate label='LBL_COLLECTION_PRIMARY'}
        </th>
         {if $isTBAEnabled}
             <th scope='col' align='center' id="lineLabel_{$vardef.name}_selected" rowspan='1' scope='row'
                 style='white-space: nowrap; word-wrap:normal;'>
                 {sugar_translate label='LBL_TEAM_SET_SELECTED'}
             </th>
         {/if}
		<td>
        <a class="utilsLink" href="javascript:collection['{$displayParams.formName}_{$vardef.name}'].js_more();" id='more_{$displayParams.formName}_{$vardef.name}' {if empty($values.secondaries)}style="display:none"{/if}></a>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr id="lineFields_{$displayParams.formName}_{$vardef.name}_0" class="lineFields_{$displayParams.formName}_{$vardef.name}">
        <td scope='row' valign="top">
            <span id='{$displayParams.formName}_{$vardef.name}_input_div_0' name='teamset_div'>   
            <input type="text" name="{$vardef.name}_collection_0" class="sqsEnabled" tabindex="{$tabindex}" id="{$displayParams.formName}_{$vardef.name}_collection_0" size="{$displayParams.size}" value=""  title="{sugar_translate label='LBL_TEAM_SELECTED_TITLE'}"  autocomplete="off" {$displayParams.readOnly} {$displayParams.field}>
            <input type="hidden" name="id_{$vardef.name}_collection_0" id="id_{$displayParams.formName}_{$vardef.name}_collection_0" value="">
            </span>
        </td>
<!-- BEGIN Remove and Radio -->
        <td valign="top" nowrap class="teamset-row">
            &nbsp;
			{capture name=tmp assign=attr}id="remove_{$vardef.name}_collection_0" name="remove_{$vardef.name}_collection_0" onclick="collection['{$displayParams.formName}_{$vardef.name}'].remove(0);"{/capture}

            <button type="button" class="id-ff-remove" {$attr}>
                {sugar_getimage name="id-ff-remove-nobg" ext=".png" attr="" alt=$alt_removeButton}
                {if !empty($displayParams.allowNewValue) }<input type="hidden" name="allow_new_value_{$idname}_collection_0" id="allow_new_value_{$idname}_collection_0" value="true">{/if}
            </button>
        </td>
        <td valign="top" align="center" class="teamset-row">
            <span id='{$displayParams.formName}_{$vardef.name}_radio_div_0'>
            <input id="primary_{$vardef.name}_collection_0" name="primary_{$vardef.name}_collection" type="radio" class="radio" value="0" style="visibility:visible{if empty($values.role_field)};display:none;{/if}" onclick="collection['{$displayParams.formName}_{$vardef.name}'].changePrimary(true);" title="{sugar_translate label='LBL_TEAM_SELECT_AS_PRIM_TITLE'}" />
            </span>
        </td>
        {if $isTBAEnabled}
        <td valign='top' align='center' class="teamset-row">
            <span id='{$displayParams.formName}_{$vardef.name}_checkbox_div_0'>
            &nbsp;
            <input id="selected_{$vardef.name}_collection_0" name="selected_{$vardef.name}_collection_0"
                   type="checkbox" class="checkbox" value="on"
                   title="{sugar_translate label='LBL_TEAM_SELECT_AS_TBSELECTED_TITLE'}"/>
            </span>
        </td>
        {/if}
        <td>&nbsp;</td>
<!-- END Remove and Radio -->
    </tr>
</table>
<table style="border-spacing: 0pt;">
    <tr>
		<td nowrap>
            <div id='{$displayParams.formName}_{$vardef.name}_mass_operation_div'>
        	<input type="radio" class="radio" name="{$vardef.name}_type" value="replace" checked> {sugar_translate label='LBL_REPLACE_BUTTON'}
			<input type="radio" class="radio" name="{$vardef.name}_type" value="add"> {sugar_translate label='LBL_ADD_BUTTON'}
            </div>	
		</td>
	</tr>    
</table>
{if !empty($values.secondaries)}
            {foreach item=secondary_field from=$values.secondaries key=key}
                <script type="text/javascript">
                    var temp_array = new Array();
                    temp_array['name'] = '{$secondary_field.name}';
                    temp_array['id'] = '{$secondary_field.id}';
                    {if $isTBAEnabled}temp_array['selected'] = '{$secondary_field.selected}';{/if}
                    //collection["{$displayParams.formName}_{$vardef.name}"].secondaries_values.push(temp_array);
                </script>
            {/foreach}
{/if}
<!--
Put this button in here since we have moved the Add and Select buttons above the text fields, the accesskey will skip these. So create this button
and push it outside the screen.
-->
 <input style='position:absolute; left:-9999px; width: 0px; height: 0px;' halign='left' type="button" class="button" value="{sugar_translate label='LBL_SELECT_BUTTON_LABEL'}" onclick='javascript:open_popup("Teams", 600, 400, "", true, false, {literal}{"call_back_function":"set_return_teams_for_editview","form_name": {/literal} "{$displayParams.formName}" {literal},"field_name":"team_name","field_to_name_array":{"id":"team_id","name":"team_name"}}{/literal}, "MULTISELECT", true);'>

<script type="text/javascript"> 
(function() {ldelim}
    SUGAR_callsInProgress++;
    var field_id = '{$displayParams.formName}_{$vardef.name}';
    YAHOO.util.Event.onContentReady(field_id + "_table", function(){ldelim}
        SUGAR_callsInProgress--;
		if(collection[field_id] && collection[field_id].secondaries_values.length == 0) {ldelim}
            {if !empty($values.secondaries)}
                {foreach from=$values.secondaries item=secondary_field}
                var temp_array = new Array();
                temp_array['name'] = '{$secondary_field.name}';
                temp_array['id'] = '{$secondary_field.id}';
                {if $isTBAEnabled}temp_array['selected'] = '{$secondary_field.selected}';{/if}
                collection[field_id].secondaries_values.push(temp_array);
                {/foreach}
            {/if}
            var collection_field = collection[field_id];
			collection_field.add_secondaries(collection_field.secondaries_values);
        {rdelim}
    {rdelim});
{rdelim})();
//	collection["{$displayParams.formName}_{$vardef.name}"].add_secondaries(collection["{$displayParams.formName}_{$vardef.name}"].secondaries_values);
</script>
{$quickSearchCode}

<script type="text/javascript">
addToValidate('MassUpdate', 'team_name_mass', 'teamset_mass', true, 'Team');
</script>
