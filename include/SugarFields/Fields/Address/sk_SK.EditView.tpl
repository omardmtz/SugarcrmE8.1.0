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
<script type="text/javascript" src='{sugar_getjspath file="include/SugarFields/Fields/Address/SugarFieldAddress.js"}'></script>
{{assign var="key" value=$displayParams.key|upper}}
{{assign var="street" value=$displayParams.key|cat:'_address_street'}}
{{assign var="city" value=$displayParams.key|cat:'_address_city'}}
{{assign var="state" value=$displayParams.key|cat:'_address_state'}}
{{assign var="country" value=$displayParams.key|cat:'_address_country'}}
{{assign var="postalcode" value=$displayParams.key|cat:'_address_postalcode'}}
<fieldset id='{{$key}}_address_fieldset'>
<legend>{sugar_translate label='LBL_{{$key}}_ADDRESS' module='{{$module}}'}</legend>
<table border="0" cellspacing="1" cellpadding="0" class="edit" width="100%">
<tr>
<td valign="top" id="{{$street}}_label" width='25%' scope='row' >
{sugar_translate label='LBL_STREET' module='{{$module}}'}:
{if $fields.{{$street}}.required || {{if $street|lower|in_array:$displayParams.required}}true{{else}}false{{/if}}}
<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span>
{/if}
</td>
<td width="*">
{{if $displayParams.maxlength}}
<textarea id="{{$street}}" name="{{$street}}" maxlength="{{$displayParams.maxlength}}" rows="{{$displayParams.rows|default:4}}" cols="{{$displayParams.cols|default:60}}" tabindex="{{$tabindex}}">{$fields.{{$street}}.value}
