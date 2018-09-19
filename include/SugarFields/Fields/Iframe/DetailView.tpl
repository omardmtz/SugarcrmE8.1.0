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
{capture name=getLink assign=link}{{sugarvar key='value'}}{/capture}
{{if $vardef.gen}}
{sugar_replace_vars subject='{{$vardef.default|replace:'{':'['|replace:'}':']'}}' assign='link'}
{{/if}}
{if !empty($link) && $link != "http://" && $link != "https://"}
{capture name=getStart assign=linkStart}{$link|substr:0:7}{/capture}
<input type="hidden" class="sugar_field" id="{{if empty($displayParams.idName)}}{{sugarvar key='name'}}{{else}}{{$displayParams.idName}}{{/if}}" value="{if ( $linkStart != 'http://' || $linkStart != 'https:/' ) && $link}http://{/if}{$link}">
<iframe src="{if $linkStart != 'http://' && $linkStart != 'https:/' && $link}http://{/if}{$link}" title="{if $linkStart != 'http://' && $linkStart != 'https:/' && $link}http://{/if}{$link}" height="{{sugarvar key='height'}}" width="100%"/></iframe>
{/if}
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}} 
{{/if}}