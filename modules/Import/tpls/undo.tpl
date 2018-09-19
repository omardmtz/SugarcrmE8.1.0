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
<br>
{if $UNDO_SUCCESS}
<h3>{$MOD.LBL_LAST_IMPORT_UNDONE}</h3>
{else}
<h3>{$MOD.LBL_NO_IMPORT_TO_UNDO}</h3>
{/if}
<br />
<form enctype="multipart/form-data" name="importundo" method="POST" action="index.php" id="importundo">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Import">
<input type="hidden" name="action" value="Step1">
<input type="hidden" name="import_module" value="{$IMPORT_MODULE}">
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
    <td align="left">
       <input title="{$MOD.LBL_MODULE_NAME}&nbsp;{$MODULENAME}"  class="button" type="submit" name="button"
            value="{$MOD.LBL_MODULE_NAME}&nbsp;{$MODULENAME}">

        <input title="{$MOD.LBL_FINISHED}{$MODULENAME}"  class="button" type="button"
            name="finished" id="finished" value="{$MOD.LBL_IMPORT_COMPLETE}">
    </td>
</tr>
</table>
<br />
</form>

