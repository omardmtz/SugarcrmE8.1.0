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


{include file="modules/DynamicFields/templates/Fields/Forms/coreTop.tpl"}
<tr><td class='mbLBL'>{sugar_translate module="DynamicFields" label="COLUMN_TITLE_DEFAULT_VALUE"}:</td><td><input type='checkbox' id='default' name='default' value=1 {if !empty($vardef.default) }checked{/if} {if $hideLevel > 5}disabled{/if} />{if $hideLevel > 5}<input type='hidden' name='default' value='{$vardef.default}'>{/if}</td></tr>
{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}