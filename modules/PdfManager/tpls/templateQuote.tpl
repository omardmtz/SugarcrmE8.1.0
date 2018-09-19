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
<table border="0" cellspacing="2">
<tbody>
<tr>
<td rowspan="4" width="180%"><img src="{$logoUrl}" alt="" /></td>
<td width="60%"><strong>{$MOD.LBL_TPL_QUOTE}</strong></td>
<td width="60%">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#DCDCDC" width="75%">{$MOD.LBL_TPL_QUOTE_NUMBER}</td>
<td width="75%">{literal}{$fields.quote_num}{/literal}</td>
</tr>
<tr>
<td bgcolor="#DCDCDC" width="75%">{$MOD.LBL_TPL_SALES_PERSON}</td>
<td width="75%">{literal}{if isset($fields.assigned_user_link.name)}{$fields.assigned_user_link.name}{/if}{/literal}</td>
</tr>
<tr>
<td bgcolor="#DCDCDC" width="75%">{$MOD.LBL_TPL_VALID_UNTIL}</td>
<td width="75%">{literal}{$fields.date_quote_expected_closed}{/literal}</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="width: 50%;" border="0" cellspacing="2">
<tbody>
<tr style="color: #ffffff;" bgcolor="#4B4B4B">
<td>{$MOD.LBL_TPL_BILL_TO}</td>
<td>{$MOD.LBL_TPL_SHIP_TO}</td>
</tr>
<tr>
<td>{literal}{$fields.billing_contact_name}{/literal}</td>
<td>{literal}{$fields.shipping_contact_name}{/literal}</td>
</tr>
<tr>
<td>{literal}{$fields.billing_account_name}{/literal}</td>
<td>{literal}{$fields.shipping_account_name}{/literal}</td>
</tr>
<tr>
<td>{literal}{$fields.billing_address_street}{/literal}</td>
<td>{literal}{$fields.shipping_address_street}{/literal}</td>
</tr>
<tr>
<td>{literal}{if $fields.billing_address_city!=""}{$fields.billing_address_city},{/if} {if $fields.billing_address_state!=""}{$fields.billing_address_state},{/if} {$fields.billing_address_postalcode}{/literal}</td>
<td>{literal}{if $fields.shipping_address_city!=""}{$fields.shipping_address_city},{/if} {if $fields.shipping_address_state!=""}{$fields.shipping_address_state},{/if} {$fields.shipping_address_postalcode}{/literal}</td>
</tr>
<tr>
<td>{literal}{$fields.billing_address_country}{/literal}</td>
<td>{literal}{$fields.shipping_address_country}{/literal}</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
{literal}{foreach from=$product_bundles item="bundle"}{/literal}
{literal}{if $bundle.products|@count}{/literal}
<p>&nbsp;</p>
<h3>{literal}{$bundle.name}{/literal}</h3>
<table style="width: 100%;" border="0">
<tbody>
<tr style="color: #ffffff;" bgcolor="#4B4B4B">
<td width="70%">{$MOD.LBL_TPL_QUANTITY}</td>
<td width="175%">{$MOD.LBL_TPL_PART_NUMBER}</td>
<td width="175%">{$MOD.LBL_TPL_PRODUCT}</td>
<td width="70%">{$MOD.LBL_TPL_LIST_PRICE}</td>
<td width="70%">{$MOD.LBL_TPL_UNIT_PRICE}</td>
<td width="70%">{$MOD.LBL_TPL_EXT_PRICE}</td>
<td width="70%">{$MOD.LBL_TPL_DISCOUNT}</td>
</tr>
<!--START_PRODUCT_LOOP-->
<tr>
<td width="70%">{literal}{if isset($product.quantity)}{$product.quantity}{/if}{/literal}</td>
<td width="175%">{literal}{if isset($product.mft_part_num)}{$product.mft_part_num}{/if}{/literal}</td>
<td width="175%">{literal}{if isset($product.name)}{$product.name}{/if}{if isset($product.list_price)}<br></br>{$product.description}{/if}{/literal}</td>
<td align="right" width="70%">{literal}{if isset($product.list_price)}{$product.list_price}{/if}{/literal}</td>
<td align="right" width="70%">{literal}{if isset($product.discount_price)}{$product.discount_price}{/if}{/literal}</td>
<td align="right" width="70%">{literal}{if isset($product.ext_price)}{$product.ext_price}{/if}{/literal}</td>
<td align="right" width="70%">{literal}
    {if isset($product.discount_amount)}
        {if !empty($product.discount_select)}
            {sugar_number_format var=$product.discount_amount}%
        {else}
            {sugar_currency_format var=$product.discount_amount currency_id=$product.currency_id}
        {/if}
    {/if}{/literal}</td>
</tr>
<!--END_PRODUCT_LOOP--></tbody>
</table>
<table>
<tbody>
<tr>
<td><hr /></td>
</tr>
</tbody>
</table>
<table style="width: 100%; margin: auto;" border="0">
<tbody>
<tr>
<td width="210%">&nbsp;</td>
<td width="45%">{$MOD.LBL_TPL_SUBTOTAL}</td>
<td align="right" width="45%">{literal}{$bundle.subtotal}{/literal}</td>
</tr>
<tr>
<td width="210%">&nbsp;</td>
<td width="45%">{$MOD.LBL_TPL_DISCOUNT}</td>
<td align="right" width="45%">{literal}{$bundle.deal_tot}{/literal}</td>
</tr>
<tr>
<td width="210%">&nbsp;</td>
<td width="45%">{$MOD.LBL_TPL_DISCOUNTED_SUBTOTAL}</td>
<td align="right" width="45%">{literal}{$bundle.new_sub}{/literal}</td>
</tr>
<tr>
<td width="210%">&nbsp;</td>
<td width="45%">{$MOD.LBL_TPL_TOTAL}</td>
<td align="right" width="45%">{literal}{$bundle.total}{/literal}</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
{literal}{/if}{/literal}
{literal}{/foreach}{/literal}
<p>&nbsp;</p>
<p>&nbsp;</p>
<table>
<tbody>
<tr>
<td><hr /></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="width: 100%; margin: auto;" border="0">
<tbody>
<tr>
<td width="200%">&nbsp;</td>
<td style="font-weight: bold;" colspan="2" align="center" width="150%"><b>{$MOD.LBL_TPL_GRAND_TOTAL}</b></td>
<td width="75%">&nbsp;</td>
<td align="right" width="75%">&nbsp;</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_CURRENCY}</td>
<td width="75%">{literal}{$fields.currency_iso}{/literal}</td>
<td width="75%">{$MOD.LBL_TPL_SUBTOTAL}</td>
<td align="right" width="75%">{literal}{$fields.subtotal}{/literal}</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">&nbsp;</td>
<td align="right" width="75%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_DISCOUNT}</td>
<td align="right" width="75%">{literal}{$fields.deal_tot}{/literal}</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">&nbsp;</td>
<td width="75%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_DISCOUNTED_SUBTOTAL}</td>
<td align="right" width="75%">{literal}{$fields.new_sub}{/literal}</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_TAX_RATE}</td>
<td width="75%">{literal}{$fields.taxrate_value}{/literal}</td>
<td width="75%">{$MOD.LBL_TPL_TAX}</td>
<td align="right" width="75%">{literal}{$fields.tax}{/literal}</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_SHIPPING_PROVIDER}</td>
<td width="75%">{literal}{$fields.shipper_name}{/literal}</td>
<td width="75%">{$MOD.LBL_TPL_SHIPPING}</td>
<td align="right" width="75%">{literal}{$fields.shipping}{/literal}</td>
</tr>
<tr>
<td width="200%">&nbsp;</td>
<td width="75%">&nbsp;</td>
<td width="75%">&nbsp;</td>
<td width="75%">{$MOD.LBL_TPL_TOTAL}</td>
<td align="right" width="75%">{literal}{$fields.total}{/literal}</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table>
<tbody>
<tr>
<td><hr /></td>
</tr>
</tbody>
</table>
