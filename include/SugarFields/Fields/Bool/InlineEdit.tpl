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
{{assign var=fieldName value=$vardef.name}}
{{if strval($parentFieldArray->$fieldName) == "1"}}
{{assign var="checked" value="CHECKED"}}
{{else}}
{{assign var="checked" value=""}}
{{/if}}
<input type="hidden" name="{{$fieldName}}" value="0">
<input type="checkbox" class="checkbox" name="{{$fieldName}}" {{$checked}} onblur='InlineEditor.save()'>