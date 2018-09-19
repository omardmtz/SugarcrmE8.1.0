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
{html_options name='{{$vardef.name}}[]' options={{sugarvar key='options' string=true}} size="{{$displayParams.size|default:6}}" style="width: 150px" multiple=1 selected={{sugarvar key='value' string=true}}}