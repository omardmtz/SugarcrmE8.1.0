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
{if !empty({{sugarvar key='value' string=true}}) && ({{sugarvar key='value' string=true}} != '^^')}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{{sugarvar key='value'}}">
{multienum_to_array string={{sugarvar key='value' string=true}} assign="vals"}
{foreach from=$vals item=item}
<li style="margin-left:10px;">{ {{sugarvar key='options' string=true}}.$item }</li>
{/foreach}
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}
{/if}