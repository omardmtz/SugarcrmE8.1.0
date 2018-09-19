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
<div>
    <link rel="stylesheet" type="text/css"
          href="{sugar_getjspath file='modules/ModuleBuilder/tpls/ListEditor.css'}"></link>
    <link rel="stylesheet" type="text/css"
          href="{sugar_getjspath file='modules/ModuleBuilder/tpls/MBModule/dropdown.css'}"></link>
    <form name='dropdown_form' onsubmit="return false">
{sugar_csrf_form_token}
        <input type='hidden' name='module' value='ModuleBuilder'>
        <input type='hidden' name='action' value='{$action}'>
        <input type='hidden' name='to_pdf' value='true'>
        <input type='hidden' name='view_module' value='{$module_name}'>
        <input type='hidden' name='view_package' value='{$package_name}'>
        <input type='hidden' id='list_value' name='list_value' value=''>
        {* This indicates that this dropdown is being created from a new field *}
        {if ($fromNewField)}
            <input type='hidden' name='is_new' value='1'>
        {/if}
        {if ($refreshTree)}
            <input type='hidden' name='refreshTree' value='1'>
        {/if}
        <input type="hidden" name="new" value="{$new|intval}">
        <table>
            <tr>
                <td colspan='2'>
                    <input id="saveBtn" type='button' class='button' onclick='SimpleList.handleSave()'
                           value='{sugar_translate label='LBL_SAVE_BUTTON_LABEL'}'>
                    <input type='button' class='button' onclick='SimpleList.undo()'
                           value='{sugar_translate label='LBL_BTN_UNDO'}'>
                    <input type='button' class='button' onclick='SimpleList.redo()'
                           value='{sugar_translate label='LBL_BTN_REDO'}'>
                    <input type='button' class='button' name='cancel' value='{sugar_translate label='LBL_BTN_CANCEL'}'
                           onclick='ModuleBuilder.tabPanel.get("activeTab").close()'>
                </td>
                <td style="text-align: right">
                    <label>{sugar_translate label='LBL_ROLE'}
                        {if not $new }
                            {html_options name='dropdown_role' options=$roles onchange='this.form.action.value="roledropdownfilter";ModuleBuilder.handleSave("dropdown_form")'}
                        {else}
                           {html_options name='dropdown_role' options=$roles disabled=true}
                        {/if}
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <hr/>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class='mbLBLL'>{sugar_translate label='LBL_DROPDOWN_TITLE_NAME'}:&nbsp;</span>
                    {if not $new }
                        <input type='hidden' id='dropdown_name' name='dropdown_name'
                               value='{$dropdown_name}'>{$dropdown_name}
                    {else}
                        <input type='text' id='dropdown_name' name='dropdown_name' value={$dropdown_name}>
                    {/if}
                </td>
            </tr>
            <tr>
                <td colspan="3" class='mbLBLL'>
                    {sugar_translate label='LBL_DROPDOWN_LANGUAGE'}:&nbsp;
                    {html_options name='dropdown_lang' options=$available_languages selected=$selected_lang onchange='this.form.action.value="dropdown";ModuleBuilder.handleSave("dropdown_form")'}
                </td>
            </tr>
            <tr>
                <td colspan="3" style='padding-top:10px;' class='mbLBLL'>{sugar_translate label='LBL_DROPDOWN_ITEMS'}:
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>{sugar_translate label='LBL_DROPDOWN_ITEM_NAME'}</b><span
                            class='fieldValue'>[{sugar_translate label='LBL_DROPDOWN_ITEM_LABEL'}]<span>
                </td>
                <td style="text-align: right">
                    <input type='button' class='button' value='{sugar_translate label='LBL_BTN_SORT_ASCENDING'}'
                           onclick='SimpleList.sortAscending()'>
                    <input type='button' class='button' value='{sugar_translate label='LBL_BTN_SORT_DESCENDING'}'
                           onclick='SimpleList.sortDescending()'>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <ul id="ul1" class="listContainer">
                        {foreach from=$options key='name' item='val'}
                            {if ($name === "")}
                                {capture assign=name}{sugar_translate label='LBL_BLANK'}{/capture}
                                {assign var=val value=$name}
                                {assign var=is_blank value=true}
                            {else}
                                {assign var=is_blank value=false}
                            {/if}
                            <li class="draggable" id="{$name|escape:'html':'UTF-8'}">
                                <table width='100%'>
                                    <tr>
                                        <td class="first">
                                            {if $is_blank}
                                                {$val|escape:'html':'UTF-8'}
                                            {else}
                                                <b>{$name|escape:'html':'UTF-8'}</b>
                                            {/if}
                                            <input id="value_{$name|escape:'html':'UTF-8'}" value="{$val|escape:'html':'UTF-8'}" type='hidden' />
                                            <span class="fieldValue" id="span_{$name|escape:'html':'UTF-8'}">[{$val|escape:'html':'UTF-8'}]</span>
                                            <span class="fieldValue" id="span_edit_{$name|escape:'html':'UTF-8'}" style="display:none">
                                                <input type="text" id="input_{$name|escape:'html':'UTF-8'}" value="{$val|escape:'html':'UTF-8'}"
                                                    onBlur='SimpleList.setDropDownValue("{$name|escape:'javascript':'UTF-8'}", this.value, true)'>
			               </span>
                                        </td>
                                        <td align='right'>
                                            <a href='javascript:void(0)'
                                               onclick='SimpleList.editDropDownValue("{$name|escape:'javascript':'UTF-8'}", true)'>
                                                {$editImage}</a>
                                            &nbsp;
                                            <a href='javascript:void(0)'
                                               onclick='SimpleList.deleteDropDownValue("{$name|escape:'javascript':'UTF-8'}", true)'>
                                                {$deleteImage}</a>
                                        </td>
                                    </tr>
                                </table>
                            </li>
                        {/foreach}
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <table width='100%'>
                        <tr>
                            <td class='mbLBLL'>{sugar_translate label='LBL_DROPDOWN_ITEM_NAME'}:</td>
                            <td class='mbLBLL'>{sugar_translate label='LBL_DROPDOWN_ITEM_LABEL'}:</td>
                        </tr>
                        <tr>
                            <td><input type='text' id='drop_name' name='drop_name' maxlength='100'></td>
                            <td><input type='text' id='drop_value' name='drop_value'></td>
                            <td><input type='button' id='dropdownaddbtn'
                                       value='{sugar_translate label='LBL_ADD_BUTTON'}' class='button'>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    {literal}
    <script>
        addForm('dropdown_form');
        addToValidate('dropdown_form', 'dropdown_name', 'DBName', false, SUGAR.language.get("ModuleBuilder", "LBL_JS_VALIDATE_NAME"));
        addToValidate('dropdown_form', 'drop_value', 'varchar', false, SUGAR.language.get("ModuleBuilder", "LBL_JS_VALIDATE_LABEL"));
        eval({/literal}{$ul_list}{literal});
        SimpleList.name = {/literal}'{$dropdown_name}'{literal};
        SimpleList.requiredOptions = {/literal}{$required_items}{literal};
        SimpleList.ul_list = list;
        SimpleList.init({/literal}'{$editImage}'{literal}, {/literal}'{$deleteImage}'{literal});
        ModuleBuilder.helpSetup('dropdowns', 'editdropdown');

        var addListenerFields = ['drop_name', 'drop_value']
        YAHOO.util.Event.addListener(addListenerFields, "keydown", function (e) {
            if (e.keyCode == 13) {
                YAHOO.util.Event.stopEvent(e);
            }
        });

    </script>
    <script>// Bug in FF4 where it doesn't run the last script. Remove when the bug is fixed.</script>
    {/literal}
</div>

