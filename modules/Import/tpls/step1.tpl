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

{literal}
<style>
#smtpButtonGroup .yui-button {
    padding-top: 10px;
}
#smtpButtonGroup .yui-radio-button-checked button, .yui-checkbox-button-checked button {
    background-color: #CCCCCC;
    color: #FFFFFF;
    text-shadow: none;
}


{/literal}
</style>
{if $ERROR != ''}
<span class="error">{$ERROR}</span>
{/if}
{$INSTRUCTION}

<script>
    var externalApis = {$EXTERNAL_APIS|@json};
</script>
<form enctype="multipart/form-data" name="importstep1" method="post" action="index.php" id="importstep1">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Import">
<input type="hidden" name="action" value="Step2">
<input type="hidden" name="current_step" value="1">
<input type="hidden" name="external_source" value="">
<input type="hidden" name="from_admin_wizard" value="{$FROM_ADMIN}">
<input type="hidden" name="import_module" value="{$IMPORT_MODULE}">
<p>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" width='100%' scope="row">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            {if $showModuleSelection}
                                <tr>
                                    <td align="left" scope="row" colspan="3"><h3>{$MOD.LBL_STEP_MODULE}&nbsp;</h3></td>
                                </tr>
                                <tr>
                                    <td><select tabindex='4' name='admin_import_module' id='admin_import_module'>{$IMPORTABLE_MODULES_OPTIONS}</select></td>
                                </tr>
                                <tr>
                                    <td align="left" scope="row" colspan="3"><div class="hr">&nbsp;</div></td>
                                </tr>
                            {/if}
                            <tr id="ext_source_help">
                                <td align="left" scope="row" colspan="3"><h3>{$MOD.LBL_WHAT_IS}&nbsp;</h3></td>
                            </tr>
                            <tr id="ext_source_csv">
                                <td colspan="3" scope="row">
                                    <span><input class="radio" type="radio" name="source" value="csv" checked="checked" id="csv_source" />
                                  &nbsp;<label for="source">{$MOD.LBL_CSV}</label>&nbsp;</span>{sugar_help text=$MOD.LBL_DELIMITER_COMMA_HELP}
                                </td>
                            </tr>
                            <tr id="ext_source_tr">
                                <td colspan="3" scope="row"><span><input {if !$EXTERNAL_SOURCES} disabled {/if} class="radio" type="radio" name="source" value="external" id="ext_source" />
                  &nbsp;<label for="source">{$MOD.LBL_EXTERNAL_SOURCE}</label>&nbsp;</span>{sugar_help text=$MOD.LBL_EXTERNAL_SOURCE_HELP}
                                </td>
                            </tr>
                            <tr scope="row" id="external_sources_tr" style="display:none;" >
                                <td colspan="2" width="35%" style="padding-top: 10px;">
                                    <div id="smtpButtonGroup" class="yui-buttongroup">
                                    {foreach from=$EXTERNAL_SOURCES key=k item=v}
                                        <span id="{$k}" class="yui-button yui-radio-button{if $selectExternalSource == $k} yui-button-checked{/if}">
                                            <span class="first-child">
                                                <button type="button" name="external_source_button" value="{$k}">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;{$v}&nbsp;&nbsp;&nbsp;&nbsp;
                                                </button>
                                            </span>
                                        </span>
                                    {/foreach}

                                    </div>
                                </td>
                                <td  style="padding-top: 10px;">
                                    <input id="ext_source_sign_in_bttn" type="button" value="{$MOD.LBL_EXT_SOURCE_SIGN_IN}" style="display:none;vertical-align:top; !important">
                                    <input id="ext_source_sign_out_bttn" type="button" value="{$MOD.LBL_EXT_SOURCE_SIGN_OUT}" style="display:none;vertical-align:top; !important">
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</p>
<br>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left"><input title="{$MOD.LBL_NEXT}"  class="button" type="submit" name="button" value="  {$MOD.LBL_NEXT}  "  id="gonext"></td>
    </tr>
</table>
</form>
