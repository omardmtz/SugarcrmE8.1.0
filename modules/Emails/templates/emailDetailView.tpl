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
<!-- BEGIN: main -->
{$emailTitle}

<P/>

<script type="text/javascript" src="{sugar_getjspath file="modules/Emails/javascript/Email.js"}"></script>
<script type="text/javascript" language="Javascript">
{$JS_VARS}
</script>
<form action="index.php" method="POST" name="DetailView" id="emailDetailView">
{sugar_csrf_form_token}
    <input type="hidden" name="inbound_email_id" value="{$ID}">
    <input type="hidden" name="type" value="out">
    <input type="hidden" name="email_name" value="{$EMAIL_NAME}">
    <input type="hidden" name="to_email_addrs" value="{$FROM}">
    <input type="hidden" name="module" value="Emails">
    <input type="hidden" name="record" value="{$ID}">
    <input type="hidden" name="isDuplicate" value=false>
    <input type="hidden" name="action">
    <input type="hidden" name="contact_id" value="{$CONTACT_ID}">
    <input type="hidden" name="user_id" value="{$USER_ID}">
    <input type="hidden" name="return_module">
    <input type="hidden" name="return_action">
    <input type="hidden" name="return_id">
    <input type="hidden" name="assigned_user_id">
    <input type="hidden" name="parent_id" value="{$PARENT_ID}">
    <input type="hidden" name="parent_type" value="{$PARENT_TYPE}">
    <input type="hidden" name="parent_name" value="{$PARENT_NAME}">
</form>

<table width="100%" border="0" cellspacing="{$GRIDLINE}" cellpadding="0" class="detail view">
	<tr>
		<td width="15%" valign="top" scope="row"><slot>{$APP.LBL_ASSIGNED_TO}</slot></td>
		<td width="35%" valign="top"><slot>{$ASSIGNED_TO}</slot></td>
		<td width="15%" scope="row"><slot>{$MOD.LBL_DATE_SENT}</slot></td>
		<td width="35%" colspan="3"><slot>{$DATE_START} {$TIME_START}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$APP.LBL_TEAMS}:</slot></td>
		<td><slot>{$TEAM}</slot></td>
		<td scope="row"><slot>{$PARENT_TYPE}</slot></td>
		<td><slot>{$PARENT_NAME}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$MOD.LBL_FROM}</slot></td>
		<td colspan=3><slot>{$FROM}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$MOD.LBL_TO}</slot></td>
		<td colspan='3'><slot>{$TO}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$MOD.LBL_CC}</slot></td>
		<td colspan='3'><slot>{$CC}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$MOD.LBL_BCC}</slot></td>
		<td colspan='3'><slot>{$BCC}</slot></td>
	</tr>
	<tr>
		<td scope="row"><slot>{$MOD.LBL_SUBJECT}</slot></td>
		<td colspan='3'><slot>{$NAME}</slot></td>
	</tr>
	<tr>
		<td valign="top" valign="top" scope="row"><slot>{$MOD.LBL_BODY}</slot></td>
		<td colspan="3"  style="background-color: #ffffff; color: #000000" ><slot>
			<div id="html_div" style="background-color: #ffffff;padding: 5px">{$DESCRIPTION_HTML}</div>
			<input id='toggle_textarea_elem' onclick="toggle_textarea();" type="checkbox" name="toggle_html"/> <label for='toggle_textarea_elem'>{$MOD.LBL_SHOW_ALT_TEXT}</label><br>
			<div id="text_div" style="display: none;background-color: #ffffff;padding: 5px">{$DESCRIPTION}</div>
			<script type="text/javascript" language="Javascript">
				var plainOnly = {$SHOW_PLAINTEXT};
				{literal}
				if(plainOnly == true) {
					document.getElementById("toggle_textarea_elem").checked = true;
					toggle_textarea();
				}
				{/literal}
			</script>
		</td>
	</tr>
	<tr>
		<td valign="top" scope="row"><slot>{$MOD.LBL_ATTACHMENTS}</td>
		<td colspan="3"><slot>{$ATTACHMENTS}</slot></td>
	</tr>
</table>
{literal}
<script>
$(document).ready(function(){
	SUGAR.themes.actionMenu();
    // this view shouldn't be supported outside of a BWC frame.
    window.parent.SUGAR.App.controller.layout.getComponent('bwc').rewriteLinks();
});
</script>
{/literal}

{$SUBPANEL}
<!-- END: main -->
