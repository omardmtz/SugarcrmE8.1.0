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

<b>{$MOD.LBL_IMPORT_VCARDTEXT}</b>
{literal}
<script type="text/javascript" src="cache/include/javascript/sugar_grp_yui_widgets.js"></script>
<script type="text/javascript">
<!--
function validate_vcard()
{
    if (document.getElementById("vcard_file").value=="") {
        YAHOO.SUGAR.MessageBox.show({msg: '{/literal}{$ERROR_TEXT}{literal}'} );
    }
    else
        document.EditView.submit();
}
-->
</script>
{/literal}
<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php">
{sugar_csrf_form_token}
<input type="hidden" name="max_file_size" value="30000">
<input type='hidden' name='action' value='ImportVCardSave'>
<input type='hidden' name='module' value='{$MODULE}'>
<input type='hidden' name='from' value='ImportVCard'>

<input size="60" name="vcard" id="vcard_file" type="file" />&nbsp;
<input class='button' type="button" onclick='validate_vcard()' value="{$APP.LBL_IMPORT_VCARD_BUTTON_LABEL}" 
    title="{$APP.LBL_IMPORT_VCARD_BUTTON_TITLE}" />
</form>
<div class="error">{$ERROR}</div>
