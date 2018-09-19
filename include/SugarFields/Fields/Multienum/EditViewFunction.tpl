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
<input type="hidden" id="{{sugarvar key='name'}}_multiselect" name="{{sugarvar key='name'}}_multiselect" value="true">
<select id="{{sugarvar key='name'}}" name="{{sugarvar key='name'}}[]" multiple="true" size="6" style="width:150" tabindex="{{$tabindex}}">
{{sugarvar key='value'}}
</select>