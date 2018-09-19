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
<input type="hidden" name="{$moduleDir}_email_widget_id" value="{$email_widget_id}">
<input type="hidden" name="emailAddressWidget" value="{$emailAddressWidget}">
{counter assign="count" start=-1 print=false}
{foreach from=$emails item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddress{counter print=true}" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
{counter assign="count" start=-1 print=false}
{foreach from=$verified item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressVerifiedValue{counter print=true}" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
{if isset($primary)}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressPrimaryFlag" value="{$primary|escape:'html':'UTF-8'}">
{/if}
{foreach from=$optOut item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressOptOutFlag[]" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
{foreach from=$invalid item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressInvalidFlag[]" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
{foreach from=$replyTo item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressReplyToFlag[]" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
{foreach from=$delete item=email}
<input type="hidden" name="{$moduleDir}{$email_widget_id}emailAddressDeleteFlag[]" value="{$email|escape:'html':'UTF-8'}">
{/foreach}
<input type="hidden" name="useEmailWidget" value="true">
