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

<script type="text/javascript">
var js_iso4217 = {$JS_ISO4217};
</script>
<script type="text/javascript" src="{sugar_getjspath file='modules/Currencies/EditView.js'}"></script>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="edit view">
<tr>
    <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="15%" scope="row" nowrap><slot>{$MOD.LBL_LIST_NAME}: <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
<td width="35%"><slot><input name='name' tabindex='1' size='30' maxlength='50' type="text" value="{$NAME}"></slot></td>
<td width="15%" scope="row" nowrap><slot>{$MOD.LBL_LIST_ISO4217}:&nbsp;{sugar_help text=$MOD.LBL_LIST_ISO4217_HELP}</slot></td>
<td width="35%"><slot><input name='iso4217' tabindex='1' size='3'
  maxlength='3' type="text" value="{$ISO4217}" onKeyUp='isoUpdate(this);'></slot></td>
</tr>
<tr>

</tr>
<tr>
<td width="15%" scope="row" nowrap><slot> {$MOD.LBL_LIST_RATE}: <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
<td width="35%"><slot><input name='conversion_rate' tabindex='1' size='30' maxlength='50' type="text" value="{$CONVERSION_RATE}">
{sugar_help text=$MOD.LBL_LIST_RATE_HELP }
</slot></td>
<td width="15%" scope="row" nowrap><slot>{$MOD.LBL_LIST_SYMBOL}: <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
<td width="35%"><slot><input name='symbol' tabindex='1' size='3' maxlength='50' type="text" value="{$SYMBOL}"></slot></td>

</tr>
<tr>

</tr>
<tr>
<td scope="row"><slot>{$MOD.LBL_LIST_STATUS}:</slot></td>
<td><slot><select name='status' tabindex='1'>{$STATUS_OPTIONS}</select> <em>{$MOD.NTC_STATUS}</em></slot></td>
</tr></table>
</td>
</tr>
</table>
<table>
    <tr>
        <td>
            <input type='hidden' name='record' value='{$ID}'>
            <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" onclick="this.form.edit.value='true';this.form.action.value='index';return check_form('EditView');" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
            <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.edit.value='false';this.form.action.value='index';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
        </td>
    </tr>
</table>
</form>
{$JAVASCRIPT}
{if $REFRESHMETADATA}
<script type="text/javascript">
    // ping sidecar to force a fresh metadata hit if there was a change that requires it
    var app = parent.SUGAR.App;
    app.api.call('read', app.api.buildURL('ping'));
</script>
{/if}
