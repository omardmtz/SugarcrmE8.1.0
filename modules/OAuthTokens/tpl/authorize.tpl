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
<form name="OAuthAuthorize" method="POST" action="index.php" >
{sugar_csrf_form_token}
<input type='hidden' name='action' value='authorize'/>
<input type='hidden' name='module' value='OAuthTokens'/>
<input type='hidden' name='sid' value='{$sid}'/>
<input type='hidden' name='hash' value='{$hash}'/>
<input type='hidden' name='confirm' value='1'/>

{$consumer}<br/>
<table>
<tr>
<td>{$MOD.LBL_OAUTH_REQUEST}: </td><td><input name="token" value="{$token}"/></td>
</tr>
</table>

<input type="submit" name="authorize" value="{$MOD.LBL_OAUTH_AUTHORIZE}"/><br/>
</form>
