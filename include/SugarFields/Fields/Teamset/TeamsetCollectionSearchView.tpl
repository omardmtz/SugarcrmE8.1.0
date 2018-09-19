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

<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='include/javascript/yui/build/container/assets/container.css'}" />
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
<input type="hidden" id="{$vardef.name}_allowed_to_check" name="{$vardef.name}_allowed_to_check" value="false">

{if !empty($vardef.required)}
<input type="hidden" id="{$vardef.name}_field" name="{$vardef.name}_field" value="{$vardef.name}_table" style="border-spacing: 0pt;">
{/if}

<table name='{$displayParams.formName}_{$vardef.name}_table' id='{$displayParams.formName}_{$vardef.name}_table' style="border-spacing: 0pt;">
    <tr>
    	<td colspan="2" NOWRAP>
            {if empty($displayParams.clearOnly) }<button type="button" class="button firstChild" value="{sugar_translate label='LBL_SELECT_BUTTON_LABEL'}" onclick='javascript:open_popup("Teams", 600, 400, "", true, false, {literal}{"call_back_function":"set_return_teams_for_editview","form_name": {/literal} "{$displayParams.formName}" {literal},"field_name":"team_name_advanced","field_to_name_array":{"id":"team_id","name":"team_name_advanced"}}{/literal}, "MULTISELECT", true);' name="teamSelect">{sugar_getimage alt=$app_strings.LBL_ID_FF_SELECT name="id-ff-select" ext=".png" other_attributes='' alt="$alt_selectButton"}</button>{/if}<button type="button" class="button lastChild" value="{sugar_translate label='LBL_ADD_BUTTON'}" onclick="javascript:collection['{$displayParams.formName}_{$vardef.name}'].add();" name="teamAdd">{sugar_getimage alt=$app_strings.LBL_ID_FF_ADD name="id-ff-add" ext=".png" other_attributes='' alt="$alt_addButton"}</button>
			</span>
        </td>
        <th scope='col' id="lineLabel_{$vardef.name}_primary">
            &nbsp;&nbsp;{sugar_translate label='LBL_COLLECTION_PRIMARY'}
        </th>
        {if $isTBAEnabled}
            <th scope='col' id="lineLabel_{$vardef.name}_selected" style='white-space: nowrap; word-wrap:normal;'>
                &nbsp;&nbsp;{sugar_translate label='LBL_TEAM_SET_SELECTED'}
            </th>
        {/if}
        <td>
        	<a class="utilsLink" href="javascript:collection['{$displayParams.formName}_{$vardef.name}'].js_more();" id='more_{$displayParams.formName}_{$vardef.name}' {if empty($values.secondaries)}style="display:none"{/if}></a>
        </td>
    </tr>
    <tr id="lineFields_{$displayParams.formName}_{$vardef.name}_0" class="lineFields_{$displayParams.formName}_{$vardef.name}">
        <td scope="row" valign='top'>
            <span id='{$displayParams.formName}_{$vardef.name}_input_div_0' name="teamset_div">          
            <input type="text" name="{$vardef.name}_collection_0" id="{$displayParams.formName}_{$vardef.name}_collection_0" class="sqsEnabled" tabindex="{$tabindex}" size="{$displayParams.size}" value=""  title="{sugar_translate label='LBL_TEAM_SELECTED_TITLE'}"  autocomplete="off" {$displayParams.readOnly} {$displayParams.field}>
            <input type="hidden" name="id_{$vardef.name}_collection_0" id="id_{$displayParams.formName}_{$vardef.name}_collection_0" value="">
            </span>
        </td>
<!-- BEGIN Remove and Radio -->
        <td valign='top' class="teamset-row">
            &nbsp;
            {capture assign="attr"}class="id-ff-remove" id="remove_{$vardef.name}_collection_0" name="remove_{$vardef.name}_collection_0" onclick='collection["{$displayParams.formName}_{$vardef.name}"].remove(0);'{/capture}

            <button type="button" class="id-ff-remove" {$attr}>
                {sugar_getimage name="id-ff-remove-nobg" ext=".png" attr="" alt=$alt_removeButton}
                {if !empty($displayParams.allowNewValue) }<input type="hidden" name="allow_new_value_{$idname}_collection_0" id="allow_new_value_{$idname}_collection_0" value="true">{/if}
            </button>
        </td>
        <td valign='top' align="center" class="teamset-row">
            <span id='{$displayParams.formName}_{$vardef.name}_radio_div_0'>
            <input id="primary_{$vardef.name}_collection_0" name="primary_{$vardef.name}_collection" type="radio" class="radio" value="0" title="{sugar_translate label='LBL_TEAM_SELECT_AS_PRIM_TITLE'}" onclick="collection['{$displayParams.formName}_{$vardef.name}'].changePrimary(true);" />
            </span>
        </td>
        {if $isTBAEnabled}
            <td valign='top' align='center' class="teamset-row">
                <span id='{$displayParams.formName}_{$vardef.name}_checkbox_div_0'>
                &nbsp;
                <input id="selected_{$vardef.name}_collection_0" name="selected_{$vardef.name}_collection_0"
                       type="checkbox" class="checkbox"
                       title="{sugar_translate label='LBL_TEAM_SELECT_AS_TBSELECTED_TITLE'}"/>
                </span>
            </td>
        {/if}
<!-- END Remove and Radio -->
    </tr>
</table>
<table style="border-spacing: 0pt;">
    <tr>
    	<td NOWRAP>
            <input type="radio" name="{$vardef.name}_type" id="{$vardef.name}_type" value="any" checked> {$APP.LBL_ROUTING_ANY}
			<input type="radio" name="{$vardef.name}_type" id="{$vardef.name}_type" value="all"> {$APP.LBL_ROUTING_ALL}
			<input type="radio" name="{$vardef.name}_type" id="{$vardef.name}_type" value="exact"> {$APP.LBL_COLLECTION_EXACT}
        </td>    
    </tr>
</table>
{if !empty($values.secondaries)}
            {foreach item=secondary_field from=$values.secondaries key=key}
                <script type="text/javascript">
                    var temp_array = new Array();
                    temp_array['name'] = replaceHTMLChars('{$secondary_field.name}');
                    temp_array['id'] = '{$secondary_field.id}';
                    {if $isTBAEnabled}temp_array['selected'] = '{$secondary_field.selected}';{/if}
                    collection["{$displayParams.formName}_{$vardef.name}"].secondaries_values.push(temp_array);
                </script>
            {/foreach}
{/if}
<!--
Put this button in here since we have moved the Add and Select buttons above the text fields, the accesskey will skip these. So create this button
and push it outside the screen.
-->
 <input style='position:absolute; left:-9999px; width: 0px; height: 0px;' halign='left' type="button" class="button" value="{sugar_translate label='LBL_SELECT_BUTTON_LABEL'}" onclick='javascript:open_popup("Teams", 600, 400, "", true, false, {literal}{"call_back_function":"set_return_teams_for_editview","form_name": {/literal} "{$displayParams.formName}" {literal},"field_name":"team_name_advanced","field_to_name_array":{"id":"team_id","name":"team_name_advanced"}}{/literal}, "MULTISELECT", true);'>

<script type="text/javascript">
collection["{$displayParams.formName}_{$vardef.name}"].add_secondaries(collection["{$displayParams.formName}_{$vardef.name}"].secondaries_values);

//If the searchType (any, all, exact) is specified, select it 
{if !empty($displayParams.searchType)}
{literal}
YAHOO.util.Event.onDOMReady(function() {
{/literal}
    radio_el = document.forms.{$displayParams.formName}.{$vardef.name}_type;
{literal}
    for (var i=0; i< radio_el.length; i++)  {
        if(typeof radio_el[i] != 'undefined' && radio_el[i].value == {/literal}'{$displayParams.searchType}'{literal}) {
           radio_el[i].checked = true;
        }
    }
});
{/literal}
{/if}

//If there was a primary team chosen, select it
{if $hasPrimaryTeam}
{literal}
YAHOO.util.Event.onDOMReady(function() {
{/literal}
    set_primary_team('{$displayParams.formName}', 'primary_{$vardef.name}_collection', '{$values.primary.id}');
{literal}
});
{/literal}
{/if}
</script>
{if !empty($values.primary.id) && !empty($values.primary.name)}
<script type="text/javascript">
 	document.forms["{$displayParams.formName}"].elements["id_{$vardef.name}_collection_0"].value = "{$values.primary.id}";
 	document.forms["{$displayParams.formName}"].elements["{$vardef.name}_collection_0"].value = replaceHTMLChars("{$values.primary.name}");
</script>
{/if}
{$quickSearchCode}