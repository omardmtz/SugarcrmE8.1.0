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


<div style='width: 400px'>
<form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.mySugar.uncoverPage);' />
{sugar_csrf_form_token}
<input type='hidden' name='id' value='{$id}' />
<input type='hidden' name='module' value='{$module}' />
<input type='hidden' name='action' value='DynamicAction' />
<input type='hidden' name='DynamicAction' value='configureDashlet' />
<input type='hidden' name='to_pdf' value='true' />
<input type='hidden' name='configure' value='true' />
<input type='hidden' id='dashletType' name='dashletType' value='{$dashletType}' />

<table width="400" cellpadding="10" cellspacing="0" border="0" class="edit view" align="center">
<tr>
    <td valign='top' nowrap class='dataLabel'>{$LBL_LEAD_SOURCES}</td>
    <td valign='top' class='dataField'>
    	<select name="lsbo_lead_sources[]" multiple size='3'>
    		{$selected_datax}
    	</select>
    </td>
</tr>
<tr>
    <td valign='top' nowrap class='dataLabel'>{$LBL_USERS}</td>
	<td valign='top' class='dataField'>
		<select name="lsbo_ids[]" multiple size='3'>
			{$lsbo_ids}
		</select>
	</td>
</tr>

<tr>
    <td align="right" colspan="2">
        <input type='submit' onclick="" class='button' value='{$LBL_SUBMIT_BUTTON_LABEL}'>
   	</td>
</tr>
</table>
</form>
</div>