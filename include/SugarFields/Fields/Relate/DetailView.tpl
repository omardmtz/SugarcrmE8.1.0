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
{{if !$nolink && !empty($vardef.id_name)}} 
{if !empty({{sugarvar memberName='vardef.id_name' key='value' string='true'}})}
{capture assign="detail_url"}index.php?module={{$vardef.module}}&action=DetailView&record={{sugarvar  memberName='vardef.id_name' key='value'}}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
{{/if}}
<span id="{{$vardef.id_name}}" class="sugar_field" data-id-value="{{sugarvar memberName='vardef.id_name' key='value'}}">{{sugarvar key='value'}}</span>
{{if !$nolink && !empty($vardef.id_name)}}
{if !empty({{sugarvar memberName='vardef.id_name' key='value' string='true'}})}</a>{/if}
{{/if}}
{{if !empty($displayParams.enableConnectors) && !empty($vardef.id_name)}}
{if !empty({{sugarvar memberName='vardef.id_name' key='value' string='true'}})}
{{sugarvar_connector view='DetailView'}} 
{/if}
{{/if}}
