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
SUGAR.email2.templates['compose'] = '<div id="composeLayout{idx}" class="ylayout-inactive-content"></div>' +
'<div id="composeOverFrame{idx}" style="height:100%;width:100%;position:absolute;top:0px">' +
'	<form id="emailCompose{idx}" name="ComposeEditView{idx}" action="index.php" method="POST">' +
'		<input type="hidden" id="email_id{idx}" name="email_id" value="">' +
'		<input type="hidden" id="uid{idx}" name="uid" value="">' +
'		<input type="hidden" id="ieId{idx}" name="ieId" value="">' +
'		<input type="hidden" id="mbox{idx}" name="mbox" value="">' +
'		<input type="hidden" id="type{idx}" name="type" value="">' +
'		<input type="hidden" id="composeLayoutId" name="composeLayoutId" value="shouldNotSeeMe">' +
'		<input type="hidden" id="composeType" name="composeType">' +
'		<input type="hidden" id="fromAccount" name="fromAccount">' +
'		<input type="hidden" id="sendSubject" name="sendSubject">' +
'		<input type="hidden" id="sendDescription" name="sendDescription">' +
'		<input type="hidden" id="sendTo" name="sendTo">' +
'		<input type="hidden" id="sendBcc" name="sendBcc">' +
'		<input type="hidden" id="sendCc" name="sendCc">' +
'		<input type="hidden" id="setEditor" name="setEditor">' +
'		<input type="hidden" id="selectedTeam" name="selectedTeam">' +
'		<input type="hidden" id="teamIds" name="teamIds">' +
'		<input type="hidden" id="primaryteam" name="primaryteam">' +
'		<input type="hidden" id="saveToSugar" name="saveToSugar">' +
'		<input type="hidden" id="parent_id" name="parent_id">' +
'		<input type="hidden" id="parent_type" name="parent_type">' +
'		<input type="hidden" id="attachments" name="attachments">' +
'		<input type="hidden" id="documents" name="documents">' +
'		<input type="hidden" id="outbound_email{idx}" name="outbound_email">' +
'		<input type="hidden" id="templateAttachments" name="templateAttachments">' +
'		<input type="hidden" id="templateAttachmentsRemove{idx}" name="templateAttachmentsRemove">' +
'		<table id="composeHeaderTable{idx}" cellpadding="0" cellspacing="0" border="0" width="100%" class="list">' +
'			<tr>' +
'				<th><table cellpadding="0" cellspacing="0" border="0"><tbody><tr ><td style="padding: 0px !important;margin:0px; !important" >' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.sendEmail({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_send.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_SEND}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.saveDraft({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_save.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_SAVE_DRAFT}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.showAttachmentPanel({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_attach.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_ATTACHMENT}</button>' +
'					<button type="button" class="button" onclick="SUGAR.email2.composeLayout.showOptionsPanel({idx}, false);"><img src="index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=icon_email_options.gif" align="absmiddle" border="0"> {app_strings.LBL_EMAIL_OPTIONS}</button>' +
'</td><td style="padding: 0px !important;margin:0px; !important">&nbsp;&nbsp;{mod_strings.LBL_EMAIL_RELATE}:&nbsp;&nbsp;<select class="select" id="data_parent_type{idx}" onchange="document.getElementById(\'data_parent_name{idx}\').value=\'\';document.getElementById(\'data_parent_id{idx}\').value=\'\'; SUGAR.email2.composeLayout.enableQuickSearchRelate(\'{idx}\');" name="data_parent_type{idx}">{linkbeans_options}</select>' + 
'&nbsp;</td><td style="padding: 0px !important;margin:0px; !important"><input id="data_parent_id{idx}" name="data_parent_id{idx}" type="hidden" value="">' +
'<input class="sqsEnabled" id="data_parent_name{idx}" name="data_parent_name{idx}" type="text" value="">&nbsp;<button type="button" class="button" onclick="SUGAR.email2.composeLayout.callopenpopupForEmail2({idx});"><img src="index.php?entryPoint=getImage&themeName=default&imageName=id-ff-select.png" align="absmiddle" border="0"></button>' +
'			</td></tr></tbody></table></th>'     +
'			</tr>' +
'			<tr>' +
'				<td>' +
'					<div style="margin:5px;">' +
'					<table cellpadding="4" cellspacing="0" border="0" width="100%">' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP >' +
'								<label for="addressFrom{idx}">{app_strings.LBL_EMAIL_FROM}:</label>' +
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div>' +
'									&nbsp;&nbsp;<select style="width: 500px;" class="ac_input" id="addressFrom{idx}" name="addressFrom{idx}"></select>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP>' +
'								<button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressTO{idx}\')">' + 
'                                   {app_strings.LBL_EMAIL_TO}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressTO{idx}" title="{app_strings.LBL_EMAIL_TO}" name="addressTO{idx}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressTO{idx}" href="#" style="display: none;">+<span id="DetailaddressTO{idx}">&nbsp;</span></a> </span>' +
'									<div class="ac_container" id="addressToAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr id="add_addr_options_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>&nbsp;</td><td class="emailUIField" valign="top" NOWRAP>&nbsp;&nbsp;<span id="cc_span{idx}"><a href="#" onclick="SE.composeLayout.showHiddenAddress(\'cc\',\'{idx}\');">{mod_strings.LBL_ADD_CC}</a></span><span id="bcc_cc_sep{idx}">&nbsp;{mod_strings.LBL_ADD_CC_BCC_SEP}&nbsp;</span><span id="bcc_span{idx}"><a href="#" onclick="SE.composeLayout.showHiddenAddress(\'bcc\',\'{idx}\');">{mod_strings.LBL_ADD_BCC}</a></span><span id="bcc_move_to_bcc_sep{idx}">&nbsp;{mod_strings.LBL_ADD_CC_BCC_SEP}&nbsp;</span><span id="move_to_bcc_span{idx}"><a href="#" onclick="SE.composeLayout.moveToBCC(\'move_to_bcc\',\'{idx}\');">{mod_strings.LBL_MOVE_TO_BCC}</a></span></td>'+
'						</tr>'+
'						<tr class="yui-hidden" id="cc_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>' +
'                               <button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressCC{idx}\')">' + 
'								{app_strings.LBL_EMAIL_CC}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressCC{idx}" name="addressCC{idx}"   title="{app_strings.LBL_EMAIL_CC}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressCC{idx}" href="#"  style="display: none;">+<span id="DetailaddressCC{idx}">&nbsp;</span></a> </span>' + 
'									<div class="ac_container" id="addressCcAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr class="yui-hidden" id="bcc_tr{idx}">' +
'							<td class="emailUILabel" NOWRAP>' +
'                               <button class="button" type="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue(\'addressBCC{idx}\')">' + 
'                               {app_strings.LBL_EMAIL_BCC}:' +
'                               </button>' + 
'							</td>' +
'							<td class="emailUIField" NOWRAP>' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="addressBCC{idx}" name="addressBCC{idx}" title="{app_strings.LBL_EMAIL_BCC}" onkeyup="SE.composeLayout.showAddressDetails(this);">' +
'									<span class="rolloverEmail"> <a id="MoreaddressBCC{idx}" href="#" style="display: none;">+<span id="DetailaddressBCC{idx}">&nbsp;</span></a> </span>' +
'									<div class="ac_container" id="addressBccAC{idx}"></div>' +
'								</div>' +
'							</td>' +
'						</tr>' +
'						<tr>' +
'							<td class="emailUILabel" NOWRAP width="1%">' +
'								<label for="emailSubject{idx}">{app_strings.LBL_EMAIL_SUBJECT}:</label>' +
'							</td>' +
'							<td class="emailUIField" NOWRAP width="99%">' +
'								<div class="ac_autocomplete">' +
'									&nbsp;&nbsp;<input class="ac_input" type="text" size="96" id="emailSubject{idx}" name="subject{idx}" value="" maxlength="'+SUGAR.email2.composeLayout.subjectMaxlen+'">' +
'								</div>' +
'							</td>' +
'						</tr>' +
'					</table>' +
'					</div>' +
'				</td>'	 +
'			</tr>' +
'		</table>' +
'		<textarea id="htmleditor{idx}" name="htmleditor{idx}" style="width:100%; height: 100px;"></textarea>' +
'		<div id="divAttachments{idx}" class="ylayout-inactive-content">' +
'			<div style="padding:5px;">' +
'				<table cellpadding="2" cellspacing="0" border="0">' +
'					<tr>' +
'						<th>' +
'							<b>{app_strings.LBL_EMAIL_ATTACHMENTS}</b>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<input type="button" name="add_file_button" onclick="SUGAR.email2.composeLayout.addFileField();" value="{mod_strings.LBL_ADD_FILE}" class="button" />' +
'							<div id="addedFiles{idx}" name="addedFiles{idx}"></div>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							&nbsp;' +
'							<br />' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<th>' +
'							<b>{app_strings.LBL_EMAIL_ATTACHMENTS2}</b>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<input type="button" name="add_document_button" onclick="SUGAR.email2.composeLayout.addDocumentField({idx});" value="{mod_strings.LBL_ADD_DOCUMENT}" class="button" />' +
'							<div id="addedDocuments{idx}"></div>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							&nbsp;' +
'							<br />' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<th>' +
'							<div id="templateAttachmentsTitle{idx}" style="display:none"><b>{app_strings.LBL_EMAIL_ATTACHMENTS3}</b></div>' +
'							<br />' +
'							&nbsp;' +
'						</th>' +
'					</tr>' +
'					<tr>' +
'						<td>' +
'							<div id="addedTemplateAttachments{idx}"></div>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'			</div>' +
'		</div>' +
'	</form>' +
'		<div id="divOptions{idx}" class="ylayout-inactive-content"' +
'             <div style="padding:5px;">' +
'			<form name="composeOptionsForm{idx}" id="composeOptionsForm{idx}">' + 
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_TEMPLATES}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<select name="email_template{idx}" id="email_template{idx}"  onchange="SUGAR.email2.composeLayout.applyEmailTemplate(\'{idx}\', this.options[this.selectedIndex].value);"></select>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_SIGNATURES}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<select name="signatures{idx}" id="signatures{idx}" onchange="SUGAR.email2.composeLayout.setSignature(\'{idx}\');"></select>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<b>{app_strings.LBL_EMAIL_TEAMS}:</b>' +
'						</td>' +
'					</tr>' +
'					<tr>' +
'						<td id="teamOptions{idx}" NOWRAP style="padding:2px;">' +
'							&nbsp;' +
'						</td>' +
'					</tr>' +
'				</table>' +
'				<br />' +
'				<table border="0" width="100%">' +
'					<tr>' +
'						<td NOWRAP style="padding:2px;">' +
'							<input type="checkbox" id="setEditor{idx}" name="setEditor{idx}" value="1" onclick="SUGAR.email2.composeLayout.renderTinyMCEToolBar(\'{idx}\', this.checked);"/>&nbsp;' +
'							<b>{mod_strings.LBL_SEND_IN_PLAIN_TEXT}</b>' +
'						</td>' +
'					</tr>' +
'				</table>' +
'         </form>' +
'			</div> ' +
'		</div>' +
'</div>';
