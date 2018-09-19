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
{if !empty({{sugarvar key='value' string=true}})}
{assign var="phone_value" value={{sugarvar key='value' string=true}} }

{sugar_phone value=$phone_value usa_format="{{if !empty($vardef.validate_usa_format)}}1{{else}}0{{/if}}"}

{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}
{/if}