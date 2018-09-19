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
{{if !$vardef.gen}}
	{if !empty({{sugarvar key='value' string=true}})}
	<input type='text' name='{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}' 
	   id='{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}' size='{{$displayParams.size|default:30}}' 
	   {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}} value='{{sugarvar key='value'}}' title='{{$vardef.help}}' tabindex='{{$tabindex}}'  {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}} >
	{else}
	<input type='text' name='{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}' 
	   id='{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}' size='{{$displayParams.size|default:30}}' 
	   {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}} 
	   {if $displayView=='advanced_search'||$displayView=='basic_search'}value=''{else}value='http://'{/if} title='{{$vardef.help}}' tabindex='{{$tabindex}}' {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}} >
	{/if}
{{/if}}