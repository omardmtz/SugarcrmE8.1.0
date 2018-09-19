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
{sugarvar_teamset parentFieldArray={{$parentFieldArray}} vardef=$fields.team_name tabindex='{{$tabindex}}' display='{{$displayParams.display}}' labelSpan='{{$displayParams.labelSpan}}' fieldSpan='{{$displayParams.fieldSpan}}' formName='{{$displayParams.formName}}' tabindex=1 displayType='{{$renderView}}' {{if !empty($displayParams.idName)}} idName='{{$displayParams.idName}}'{{/if}} 	{{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}} }
