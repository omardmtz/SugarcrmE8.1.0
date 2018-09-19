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
<input type='text' name='{$vardef.name}' id='{$vardef.name}' size='{$displayParams.size|default:20}' {if !empty($vardef.len)}maxlength='{$vardef.len}'{elseif !empty($displayParams.maxlength)}maxlength='{$displayParams.maxlength}'{/if} value='{$vardef.value}' title='{$vardef.help}' {if !empty($vardef.readOnly) || !empty($displayParams.readOnly)}readonly="1"{/if} {$displayParams.field}> 