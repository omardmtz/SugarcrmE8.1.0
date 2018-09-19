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
<form name="editProperty" id="editProperty" onsubmit='return false;'>
{sugar_csrf_form_token}
<input type='hidden' name='module' value='ModuleBuilder'>
<input type='hidden' name='action' value='saveProperty'>
<input type='hidden' name='view_module' value='{$view_module}'>
{if isset($view_package)}<input type='hidden' name='view_package' value='{$view_package}'>{/if}
<input type='hidden' name='subpanel' value='{$subpanel}'>
<input type='hidden' name='to_pdf' value='true'>

{if isset($MB)}
<input type='hidden' name='MB' value='{$MB}'>
<input type='hidden' name='view_package' value='{$view_package}'>
{/if}

{literal}
<script>
    function saveAction() {
{/literal}
        var widthUnit = '{$widthUnit}';
{literal}
        for(var i=0, l=document.editProperty.elements.length; i<l; i++) {
            var field = document.editProperty.elements[i];
            if (field.className.indexOf('save') != -1 )
            {
                if (field.value != 'no_change')
                {
                    var id = field.id.substring('editProperty_'.length);
                    var fieldSpan = document.getElementById(id);
                    var value = YAHOO.lang.escapeHTML(field.value);

                    // If editing a width on list layouts, update the unit
                    if (field.name.toLowerCase().indexOf('width') != -1) {
                        value = value.replace('px', '').replace('%', '').trim();
                        fieldSpan.nextElementSibling.innerHTML = field.value == '' || isNaN(+value) ? '' : widthUnit;
                    }
                    fieldSpan.innerHTML = value;
                }
            }
        }
    }

	function switchLanguage( language )
	{
{/literal}
        var request = 'module=ModuleBuilder&action=editProperty&view_module={$editModule}&selected_lang=' + language ;
        {foreach from=$properties key='key' item='property'}
                request += '&id_{$key}={$property.id}&name_{$key}={$property.name}&title_{$key}={$property.title}&label_{$key}={$property.label}' ;
        {/foreach}
{literal}
        ModuleBuilder.getContent( request ) ;
    }

</script>
{/literal}

<table style="width:100%">

	{foreach from=$properties key='key' item='property'}
	<tr>
		<td width="25%" align='right'>{if isset($property.title)}{$property.title}{else}{$property.name}{/if}:</td>
		<td width="75%">
			<input class='save' type='hidden' name='{$property.name}' id='editProperty_{$id}{$property.id}' value='no_change'>
			{if isset($property.hidden)}
				{$property.value}
			{else}
				{if $key == 'width'}
					<select id="selectWidthClass_{$id}{$property.id}" onchange="handleClassSelection(this)">
						<option value="" selected="selected">default</option>
                        {foreach from=$defaultWidths item='width'}
                            <option value="{$width}">{$width}</option>
                        {/foreach}
						<option value="custom">custom</option>
					</select>
					<input id="widthValue_{$id}{$property.id}" onchange="handleWidthChange(this.value)" value="{$property.value}" style="display:none">
                    {literal}
                    <script>
                    var propertyValue, widthValue, saveWidthProperty, selectWidthClass;
                    {/literal}

                    propertyValue = '{$property.value}';
                    saveWidthProperty = document.getElementById('editProperty_{$id}{$property.id}');
                    widthValue = document.getElementById('widthValue_{$id}{$property.id}');
                    selectWidthClass = document.getElementById('selectWidthClass_{$id}{$property.id}');

                    {literal}
                    if (propertyValue != '') {
                        if (isNaN(propertyValue)) {
                            selectWidthClass.value = propertyValue;
                            widthValue.style.display = 'none';
                            widthValue.value = '';
                        } else {
                            selectWidthClass.value = 'custom';
                            widthValue.style.display = 'inline';
                            widthValue.value = isNaN(propertyValue) ? '' : propertyValue;
                        }
                    }
                    function handleClassSelection(el) {
                        var selected = el.options[el.selectedIndex].value;

                        if (selected === 'custom') {
                            widthValue.style.display = 'inline';
                            widthValue.value = isNaN(propertyValue) ? '' : propertyValue;
                        } else {
                            widthValue.style.display = 'none';
                            widthValue.value = '';
                            saveWidthProperty.value = selected;
                        }
                    }

                    function handleWidthChange(w) {
                        saveWidthProperty.value = w;
                    }
                    </script>
                    {/literal}
				{else}
                    <input onchange='document.getElementById("editProperty_{$id}{$property.id}").value = this.value' value='{$property.value}'>
                {/if}
			{/if}
		</td>
	</tr>
	{/foreach}
	<tr>
		<td><input class="button" type="Button" name="save" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="saveAction(); ModuleBuilder.submitForm('editProperty'); ModuleBuilder.closeAllTabs();"></td>
	</tr>
</table>
</form>

<script>
ModuleBuilder.helpSetup('layoutEditor','property', 'east');
</script>


