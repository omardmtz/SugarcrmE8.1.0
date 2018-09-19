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
{sugar_number_format var={{sugarvar key='value' stringFormat='false'}} {{if isset($vardef.precision)}}precision={{$vardef.precision}}{{/if}} }
</span>
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}} 
{{/if}}