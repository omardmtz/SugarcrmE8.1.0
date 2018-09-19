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
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="view">
	<tr>
		<td NOWRAP>
			{include file="modules/Emails/templates/emailSettingsAccountDetails.tpl"}
		</td>
	</tr>
    	<tr>
                                	<td align="right">
                                	   <input type="button" class="button" style="margin-left:5px;" value="   {$app_strings.LBL_EMAIL_DONE_BUTTON_LABEL}   " onclick="javascript:SUGAR.email2.settings.saveOptionsGeneral(true);">
                                    </td>
                            	</tr>
	

</table>
