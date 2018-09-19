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
SUGAR.email2.templates['viewPrintable'] = '<html>' +
'<body onload="javascript:window.print();">' + 
'<style>' + 
'body {' + 
'	margin: 0px;' + 
'	font-family: helvetica, impact, sans-serif;' +
'	font-size : 12pt;' +
'} ' +
'table {' +
'	padding:10px;' +
'}' +
'</style>' +
'<div>' +
'<table cellpadding="0" cellspacing="0" border="0" width="100%">' +
'	<tr>' +
'		<td>' +
'			<table cellpadding="0" cellspacing="0" border="0" width="100%">' +
'				<tr>' +
'					<td NOWRAP valign="top" width="1%" class="displayEmailLabel">' +
'						{app_strings.LBL_EMAIL_FROM}:' +
'					</td>' +
'					<td width="99%" class="displayEmailValue">' +
'						{email.from_name} &lt;{email.from_addr}&gt;' +
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
'				{email.cc}' +
'				{email.attachments}' +
'			</table>' +
'		</td>' +
'	</tr>' +
'	<tr>' +
'		<td>' +
'			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">' +
'				<tr>' +
'					<td style="border-top: 1px solid #333;">' +
'						<div style="padding:5px;">' +
							'{email.description}' +
'						</div>' +
'					</td>' +
'				</tr>' +
'			</table>' +
'		</td>' +
'	</tr>' +
'</table>' +
'</div>' +
'</body></html>';
