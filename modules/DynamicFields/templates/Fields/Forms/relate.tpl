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
<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields" label="LBL_MODULE"}:</td>
	<td>
	{if $hideLevel == 0}
		{html_options name="ext2" id="ext2" selected=$vardef.module options=$modules}
	{else}
		<input type='hidden' name='ext2' value='{$vardef.module}'>{$vardef.module}
	{/if}
	<input type='hidden' name='ext3' value='{$vardef.id_name}'>
	</td>
</tr>
{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}