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
{$rollover}
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	   <td colspan="2" >
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="view">
    		<tr>
					<th colspan="4" align="left" colspan="4" scope="row" style="padding-bottom: 5px;">
					<h4>{$mod_strings.LBL_EMAIL_SETTINGS_INBOUND_ACCOUNTS}</h4>
					</th>
			</tr>
			<tr>
                <td colspan="4" scope="row" >{$app_strings.LBL_EMAIL_ACCOUNTS_SUBTITLE}</td>
            </tr>
            <tr><td>&nbsp;</td></tr>            
			<tr>
					<td><div id="inboundAccountsTable" class="yui-skin-sam"></div></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			{if empty($disable_account_config) || $disable_account_config == "false"}
			<tr>
				<td> <input title="{$mod_strings.LBL_ADD_INBOUND_ACCOUNT}"
	                        type='button' 
	                        class="button"
	                        onClick='SUGAR.email2.accounts.showEditInboundAccountDialogue();'
	                        name="button" id="addButton" value="{$mod_strings.LBL_ADD_INBOUND_ACCOUNT}">
				</td>
			</tr>
			{/if}
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>    
     </td>
    </tr>                
	<tr>
	<td colspan="2">
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="view">
			    <tr>
					<th colspan="4" align="left" colspan="4" scope="row" style="padding-bottom: 5px;">
					<h4>{$mod_strings.LBL_EMAIL_SETTINGS_OUTBOUND_ACCOUNTS}</h4>
					</th>
				</tr>
				<tr><td colspan="2"  style="text-align:left;" scope="row">{$app_strings.LBL_EMAIL_ACCOUNTS_OUTBOUND_SUBTITLE}</td></tr>	
				<tr>
				    <td>&nbsp;</td></tr>
			 	<tr>
					<td valign="top" NOWRAP>
						<div>
        					<table>
                			    <tr>
                				    <td><div id="outboundAccountsTable" class="yui-skin-sam"></div></td>
                				</tr>
                				<tr><td>&nbsp;</td></tr>
								{if empty($disable_account_config) || $disable_account_config == "false"}
								<tr>
                				    <td style="padding-bottom: 5px">
                					   <input id="outbound_email_add_button" title="{$app_strings.LBL_EMAIL_FOLDERS_ADD}" type='button' 
                					   	class="button" onClick='SUGAR.email2.accounts.showAddSmtp();' name="button" value="{$mod_strings.LBL_ADD_OUTBOUND_ACCOUNT}">
                					</td>
                				</tr>
								{/if}
                            </table>
                       </div>     
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="testSettingsDiv"></div>