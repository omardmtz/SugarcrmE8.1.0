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
{{if !$nolink}}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{{sugarvar key='value'}}">
<input type="hidden" class="sugar_field" id="{{$vardef.id_name}}" value="{{sugarvar key='value' memberName='vardef.id_name'}}">
<a href="index.php?module={{sugarvar objectName='fields' memberName='vardef.type_name' key='value'}}&action=DetailView&record={{sugarvar key='value' memberName='vardef.id_name'}}" class="tabDetailViewDFLink">{{/if}}{{sugarvar key='value'}}{{if !$nolink}}</a>
{{/if}}
{{if !empty($displayParams.enableConnectors)}}
{if !empty({{sugarvar key='value'}})}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}