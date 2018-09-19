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
<textarea id="{$vardef.name}" name="{$vardef.name}" rows="{$displayParams.rows|default:3}" cols="{$displayParams.cols|default:20}" title='{$vardef.help}' tabindex="{$tabindex}" {if !empty($vardef.readOnly) || !empty($displayParams.readOnly)}readonly="1"{/if}>{$vardef.value}</textarea>
