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

<br/>
{if $no_connector}
<span class="error workflow-sunset">{$mod.ERROR_NO_CONNECTOR}</span>
<br />
{else}
{if !empty($connector_language.LBL_LICENSING_INFO)}
{$connector_language.LBL_LICENSING_INFO}
{/if}
<br/>
<table width="100%" border="0" cellspacing="1" cellpadding="0" >
{if !empty($properties)}
{foreach from=$properties key=name item=value}
<tr>
<td class="dataLabel" width="35%">
{$connector_language[$name]}:&nbsp;
{if isset($required_properties[$name])}
<span class="required">*</span>
{/if}
</td>
<td class="dataLabel" width="65%">
<input type="text" id="{$source_id}_{$name}" name="{$source_id}_{$name}" size="75" value="{$value}"></td>
</tr>
{/foreach}
{if $hasTestingEnabled}
<tr>
<td class="dataLabel" colspan="2">
<input id="{$source_id}_test_button" type="button" class="button" value="  {$mod.LBL_TEST_SOURCE}  " onclick="run_test('{$source_id}');">
</td>
</tr>
<tr>
<td class="dataLabel" colspan="2">
<span id="{$source_id}_result">&nbsp;</span>
</td>
</tr>
{/if}
{else}
<tr>
<td class="dataLabel" colspan="2">&nbsp;</td>
<td class="dataLabel" colspan="2">{$mod.LBL_NO_PROPERTIES}</td>
</tr>
{/if}
</table>

<script type="text/javascript">
{foreach from=$required_properties key=id item=label}
addToValidate("ModifyProperties", "{$source_id}_{$id}", "alpha", true, "{$label}");
{/foreach}
</script>
{/if}
