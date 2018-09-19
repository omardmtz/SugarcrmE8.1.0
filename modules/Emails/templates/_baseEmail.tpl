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
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Emails/EmailUI.css'}" />
{include file="modules/Emails/templates/_baseJsVars.tpl"}
<script type="text/javascript" src='{sugar_getjspath file='include/javascript/tiny_mce/tiny_mce.js'}'></script>
<script type="text/javascript" src='{sugar_getjspath file='cache/include/javascript/sugar_grp_emails.js'}'></script>
<script type="text/javascript" src='{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}'></script>
<script type="text/javascript" src="{sugar_getjspath file='include/javascript/jsclass_base.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='include/javascript/jsclass_async.js'}"></script>

<script type="text/javascript" language="Javascript">

{include file="modules/Emails/templates/_baseConfigData.tpl"}

    var calFormat = '{$calFormat}';
    var theme = "{$theme}";

    {$quickSearchForAssignedUser}

    SUGAR.email2.detailView.qcmodules = {$qcModules};

    SUGAR.email2.disableAccountEdit = {$disable_account_config};

    //SUGAR.email2.composeLayout.teamsSettingsFolder = {$teamsSettingsFolder};

    var isAdmin = {$is_admin};
    var loadingSprite = app_strings.LBL_EMAIL_LOADING + " <img src='{sugar_getjspath file='include/javascript/yui/build/assets/skins/sam/wait.gif'}' alt=$mod_strings.LBL_WAIT height='14' align='absmiddle'>";
</script>
<div class="email">
<form id="emailUIForm" name="emailUIForm">
{sugar_csrf_form_token}
    <input type="hidden" id="module" name="module" value="Emails">
    <input type="hidden" id="action" name="action" value="EmailUIAjax">
    <input type="hidden" id="to_pdf" name="to_pdf" value="true">
    <input type="hidden" id="emailUIAction" name="emailUIAction">
    <input type="hidden" id="mbox" name="mbox">
    <input type="hidden" id="uid" name="uid">
    <input type="hidden" id="ieId" name="ieId">
    <input type="hidden" id="forceRefresh" name="forceRefresh">
    <input type="hidden" id="focusFolder" name="focusFolder">
    <input type="hidden" id="focusFolderOpen" name="focusFolderOpen">
    <input type="hidden" id="sortBy" name="sortBy">
    <input type="hidden" id="reverse" name="reverse">
</form>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td NOWRAP style="padding-bottom: 2px;">
            <button class="button" id="checkEmailButton" onclick="SUGAR.email2.folders.startEmailAccountCheck();"><img src="{sugar_getjspath file='themes/default/images/icon_email_check.gif'}" alt=$mod_strings.LBL_CHECKEMAIL align="absmiddle" border="0"> {$app_strings.LBL_EMAIL_CHECK}</button>
            <button class="button" id="composeButton" onclick="SUGAR.email2.composeLayout.c0_composeNewEmail();"><img src="{sugar_getjspath file='themes/default/images/icon_email_compose.gif'}" alt=$mod_strings.LBL_COMPOSEEMAIL align="absmiddle" border="0"> {$mod_strings.LNK_NEW_SEND_EMAIL}</button>
            <button class="button" id="settingsButton" onclick="SUGAR.email2.settings.showSettings();"><img src="{sugar_getjspath file='themes/default/images/icon_email_settings.gif'}" alt=$mod_strings.LBL_EMAILSETTINGS align="absmiddle" border="0"> {$app_strings.LBL_EMAIL_SETTINGS}</button>
        </td>
    </tr>
</table>


{include file="modules/Emails/templates/overlay.tpl"}

<div id="emailContextMenu"></div>
<div id="folderContextMenu"></div>
<div id="container" class="email" style="position:relative; height:550px; overflow:hidden;"></div>
<div id="innerLayout" class="yui-hidden"></div>
<div id="listViewLayout" class="yui-hidden"></div>
<div id="settingsDialog"></div>

<!-- Hidden Content -->
<div class="yui-hidden">
    <div id="searchTab" style="padding:5px">
        {include file="modules/Emails/templates/advancedSearch.tpl"}
    </div>
    <div id="settings">
        {include file="modules/Emails/templates/emailSettings.tpl"}
    </div>

    <div id="footerLinks" class="yui-hidden"></div>
</div>


<div id="editContact" class="yui-hidden"></div>
<div id="editContactTab" class="yui-hidden"></div>
<div id="editMailingList" class="yui-hidden"></div>
<div id="editMailingListTab" class="yui-hidden"></div>


<!-- for detailView quickCreate() calls -->
<div id="quickCreateForEmail"></div>
<div id="quickCreateContent"></div>


<div id="importDialog"></div>
<div id="importDialogContent" ></div>


<div id="relateDialog"  ></div>
<div id="relateDialogContent"  ></div>


<div id="assignmentDialog"  ></div>
<div id="assignmentDialogContent"  ></div>


<div id="emailDetailDialog"  ></div>
<div id="emailDetailDialogContent"  ></div>


<!-- for detailView views -->
<div id="viewDialog"></div>
<div id="viewDialogContent"></div>

<!-- addressBook select -->
{include file="modules/Emails/templates/addressSearchContent.tpl"}

<!-- accounts outbound server dialogue -->
<div id="outboundDialog" class="yui-hidden">
    {include file="modules/Emails/templates/outboundDialog.tpl"}
</div>

<!-- accounts edit dialogue -->
<div id="editAccountDialogue" class="yui-hidden">
    {include file="modules/Emails/templates/editAccountDialogue.tpl"}
</div>

<div id="testOutboundDialog" class="yui-hidden">
    {include file="modules/Emails/templates/outboundDialogTest.tpl"}
</div>

<div id="assignToDiv" class="yui-hidden">
    {include file="modules/Emails/templates/assignTo.tpl"}
</div>


<script type="text/javascript" language="Javascript">
	enableQS(true);
</script>

</div>
