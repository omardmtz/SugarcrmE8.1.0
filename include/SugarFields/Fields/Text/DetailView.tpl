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
<span class="sugar_field" id="{{sugarvar key='name'}}">{{if empty($displayParams.textonly)}}{{sugarvar key='value' htmlentitydecode='true'}}{{else}}{{sugarvar key='value'}}{{/if}}</span>
{{if !empty($displayParams.enableConnectors)}}
{assign var="value" value={{sugarvar key='value' string='true'}} }
{if !empty($value)}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}
