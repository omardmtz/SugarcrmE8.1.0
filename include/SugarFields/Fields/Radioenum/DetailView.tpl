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
<span class="sugar_field" id="{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}">
{ {{sugarvar  key='options' string=true}}[{{sugarvar key='value' string=true}}]}
</span>
{{if !empty($displayParams.enableConnectors)}}
{if !empty({{sugarvar  key='options' string=true}}[{{sugarvar key='value' string=true}}])}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}