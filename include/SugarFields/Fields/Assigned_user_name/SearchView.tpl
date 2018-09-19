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
{{capture name=display_size assign=size}}{{$displayParams.size|default:6}}{{/capture}}
{php}$this->_tpl_vars['user_options'] = get_user_array(false);{/php}
{html_options name='{{$vardef.name}}[]' options=$user_options size="{{$size}}" style="width: 150px" {{if $size > 1}}multiple="1"{{/if}} selected={{sugarvar key='value' string=true}}}
