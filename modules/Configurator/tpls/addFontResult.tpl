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

<p>
{$MODULE_TITLE}
</p>
<form name="addFontResult" method="POST" action="index.php" id="addFontResult">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Configurator">
<input type="hidden" name="action" value="FontManager">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding-bottom: 2px;">
            <input title="{$MOD.LBL_FONTMANAGER_TITLE}" class="button"  type="submit" name="back" value="  {$MOD.LBL_FONTMANAGER_BUTTON}  " >
        </td>
    </tr>
</table>
<br>
<div>{if isset($error)}<span class='error'><b>{$MOD.LBL_STATUS_FONT_ERROR}</b></span>{else}<b>{$MOD.LBL_STATUS_FONT_SUCCESS}</b>{/if}</div>
<span class='error'><pre>{$error}</pre></span>
<pre>{$info}</pre>
</form>