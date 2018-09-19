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
<a href="index.php?entryPoint=download&id={$fields.{{$vardef.fileId}}.value}&type={{$vardef.linkModule}}" class="tabDetailViewDFLink" target='_blank'>{{sugarvar key='value'}}</a>
</span>
{{if isset($vardef) && isset($vardef.allowEapm) && $vardef.allowEapm}}
{if isset($fields.{{$vardef.docType}}) && !empty($fields.{{$vardef.docType}}.value) && $fields.{{$vardef.docType}}.value != 'SugarCRM' && !empty($fields.{{$vardef.docUrl}}.value) }
{capture name=imageNameCapture assign=imageName}
{$fields.{{$vardef.docType}}.value}_image_inline.png
{/capture}
<a href="{$fields.{{$vardef.docUrl}}.value}" class="tabDetailViewDFLink" target="_blank">{sugar_getimage name=$imageName alt=$imageName other_attributes='border="0" '}</a>
{/if}
{{/if}}
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}
