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
{if empty({{sugarvar key='value' string=true}})}
{assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
{assign var="value" value={{sugarvar key='value' string=true}} }
{/if}
{capture name=idname assign=idname}{{sugarvar key='name'}}{/capture}
{{if !empty($displayParams.idName)}}
    {assign var=idname value=$displayParams.idName}
{{/if}}

{if isset({{sugarvar key='value' string=true}}) && {{sugarvar key='value' string=true}} != ''}
	{html_radios id="$idname" {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}}   name="$idname" title="{{$vardef.help}}" options={{sugarvar key='options' string=true}} selected={{sugarvar key='value' string=true}} separator="{{$vardef.separator}}"}
{else}
	{html_radios id="$idname" {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}}  name="$idname" title="{{$vardef.help}}" options={{sugarvar key='options' string=true}} selected={{sugarvar key='default' string=true}} separator="{{$vardef.separator}}"}
{/if}