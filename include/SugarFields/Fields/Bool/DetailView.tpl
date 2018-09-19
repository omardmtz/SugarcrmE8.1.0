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
{if strval({{sugarvar key='value' stringFormat='false'}}) == "1" || strval({{sugarvar key='value' stringFormat='false'}}) == "yes" || strval({{sugarvar key='value' stringFormat='false'}}) == "on"} 
{assign var="checked" value="CHECKED"}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{{sugarvar key='name'}}" id="{{sugarvar key='name'}}" value="{{sugarvar key='value' stringFormat='false'}}" disabled="true" {$checked}>
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}} 
{{/if}}