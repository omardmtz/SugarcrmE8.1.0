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
SUGAR.email2.templates['displayOneEmail'] = 
'<div class="emailDetailTable" style="height:100%">' +
'<div id="viewMenuDiv{idx}"></div>' + 
'<div width="100%" class="displayEmailValue">' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'reply\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_reply.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_REPLY}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'replyAll\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_replyall.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_REPLY_ALL}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.c0_replyForwardEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\', \'forward\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_forward.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_FORWARD}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.detailView.emailDeleteSingle(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_delete.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_DELETE}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.detailView.viewPrintable(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=Print_Email.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_PRINT}</button>' +
'					<button id="btnEmailView{idx}" type="button" class="button" onclick="SUGAR.email2.detailView.showViewMenu(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_view.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_VIEW} <img src="themes/default/images/more.gif" align="absmiddle" border="0"></button>' +
'					<button id="archiveEmail{idx}" type="button" class="button" onclick="SUGAR.email2.detailView.importEmail(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="themes/default/images/icon_email_archive.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_IMPORT_EMAIL}</button>' +
'					<button id="quickCreateSpan{meta.panelId}" type="button" class="button" onclick="SUGAR.email2.detailView.showQuickCreate(\'{meta.ieId}\', \'{meta.uid}\', \'{meta.mbox}\');"><img src="themes/default/images/icon_email_create.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_QUICK_CREATE} <img src="themes/default/images/more.gif" align="absmiddle" border="0"></button>' +
'					<button type="button" id="showDeialViewForEmail{meta.panelId}" class="button" onclick="SUGAR.email2.contextMenus.showEmailDetailViewInFullPage(\'{meta.uid}\');"><img src="themes/default/images/icon_email_relate.gif&v='+SUGAR.VERSION_MARK+'" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_VIEW_RELATIONSHIPS}</button>' +
'</div>' +
'			<table cellpadding="0" cellspacing="0" border="0" width="100%" >' +
'				<tr>' +
'					<td NOWRAP valign="top" width="1%" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_FROM}:' +
'					</td>' +
'					<td width="99%" class="displayEmailValue">' +
'						{email.from_addr}' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_SUBJECT}:' +
'					</td>' +
'					<td NOWRAP valign="top" class="displayEmailValue">' +
'						<b>{email.name}</b>' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_DATE_SENT_BY_SENDER}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.date_start} {email.time_start}' +
'					</td>' +
'				</tr>' +
'				<tr>' +
'					<td NOWRAP valign="top" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_TO}:' +
'					</td>' +
'					<td class="displayEmailValue">' +
'						{email.toaddrs}' +
'					</td>' +
'				</tr>' +
'				<tr>{meta.cc}</tr>' +
'				<tr>{email.attachments}</tr>' +
'			</table>' +
'			<div id="displayEmailFrameDiv{idx}" name="displayEmailFrameDiv{idx}"><iframe id="displayEmailFrame{idx}" src="modules/Emails/templates/_blank.html" width="100%" height="100%" frameborder="0"></iframe></div>' +
'</div>'
;
