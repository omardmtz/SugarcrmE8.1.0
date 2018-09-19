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
{*
    check to see if 'date_formatted_value' has been added to the vardefs, and use it if it has, otherwise use the normal sugarvar function
*}

{if strlen({{sugarvar key='value' string=true}}) <= 0}
    {assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
    {assign var="value" value={{sugarvar key='value' string=true}} }
{/if}



<span class="sugar_field" id="{{sugarvar key='name'}}">{$value}</span>
{{if !empty($vardef.group) && $vardef.group == 'created_by_name' }}
    {$APP.LBL_BY} {$fields.created_by_name.value}
{{elseif !empty($vardef.group) && $vardef.group == 'modified_by_name' }}
    {$APP.LBL_BY} {$fields.modified_by_name.value}
{{/if}}

{{if !empty($displayParams.enableConnectors)}}
{if !empty($value)}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}