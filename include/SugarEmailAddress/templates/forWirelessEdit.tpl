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
<input type=hidden name='emailAddressWidget' value=1>
<table cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td  valign="top" NOWRAP>
			<table cellpadding="0" cellspacing="0" border="0" id="{$module}emailAddressesTable0">
			    <tr>
			        <td>
			            <input type="hidden" value="0" name="{$module}_email_widget_id" id="{$module}_email_widget_id" />
			            <input type="hidden" value="1" name="emailAddressWidget" id="emailAddressWidget" />
                    </td>
                </tr>
				{foreach from=$prefillData item="record" name="recordlist"}
                <tr id="{$module}0emailAddressRow{$smarty.foreach.recordlist.index}">
                    <td classname="dataLabel" class="tabEditViewDF" nowrap="NOWRAP">
                        <input value="{$record.email_address}" size="20" 
                            id="{$module}0emailAddress{$smarty.foreach.recordlist.index}" name="{$module}0emailAddress{$smarty.foreach.recordlist.index}" type="text"><br>
                        <input enabled="true" {if $record.primary_address == '1'}checked="true"{/if} enabled="{if $record.primary_address == '1'}true{else}false{/if}" value="{$module}0emailAddress{$smarty.foreach.recordlist.index}" 
                            id="{$module}0emailAddressPrimaryFlag{$smarty.foreach.recordlist.index}" name="{$module}0emailAddressPrimaryFlag" type="radio">{$app_strings.LBL_EMAIL_PRIMARY}<br>
                        <input enabled="true" {if $record.opt_out == '1'}checked="true"{/if} value="{$module}0emailAddress{$smarty.foreach.recordlist.index}" 
                            id="{$module}0emailAddressOptOutFlag{$smarty.foreach.recordlist.index}" name="{$module}0emailAddressOptOutFlag[]" type="checkbox">{$app_strings.LBL_EMAIL_OPT_OUT}<br>
                        <input enabled="true" {if $record.invalid_email == '1'}checked="true"{/if} value="{$module}0emailAddress{$smarty.foreach.recordlist.index}" 
                            id="{$module}0emailAddressInvalidFlag{$smarty.foreach.recordlist.index}" name="{$module}0emailAddressInvalidFlag[]" type="checkbox">{$app_strings.LBL_EMAIL_INVALID}<br>
                        <input enabled="true" value="{$module}0emailAddress{$smarty.foreach.recordlist.index}" 
                            id="{$module}0emailAddressDeleteFlag{$smarty.foreach.recordlist.index}" name="{$module}0emailAddressDeleteFlag[]" type="checkbox">{$app_strings.LBL_EMAIL_DELETE}<br>
                    </td>
                </tr>
                {/foreach}
                <tr id="{$module}0emailAddressRow{$smarty.foreach.recordlist.total}">
                    <td classname="dataLabel" class="tabEditViewDF" nowrap="NOWRAP">
                        <b>{$app_strings.LBL_EMAIL_ADD}</b><br />
                        <input value="" size="20" 
                            id="{$module}0emailAddress{$smarty.foreach.recordlist.total}" name="{$module}0emailAddress{$smarty.foreach.recordlist.total}" type="text"><br>
                        <input enabled="true" value="{$module}0emailAddress{$smarty.foreach.recordlist.total}" 
                            id="{$module}0emailAddressPrimaryFlag{$smarty.foreach.recordlist.total}" name="{$module}0emailAddressPrimaryFlag" type="radio"{if $noemail} checked="true"{/if}>{$app_strings.LBL_EMAIL_PRIMARY}<br>
                        <input enabled="true" value="{$module}0emailAddress{$smarty.foreach.recordlist.total}" 
                            id="{$module}0emailAddressOptOutFlag{$smarty.foreach.recordlist.total}" name="{$module}0emailAddressOptOutFlag[]" type="checkbox">{$app_strings.LBL_EMAIL_OPT_OUT}<br>
                        <input enabled="true" value="{$module}0emailAddress{$smarty.foreach.recordlist.total}" 
                            id="{$module}0emailAddressInvalidFlag{$smarty.foreach.recordlist.total}" name="{$module}0emailAddressInvalidFlag[]" type="checkbox">{$app_strings.LBL_EMAIL_INVALID}<br>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="useEmailWidget" value="true">
