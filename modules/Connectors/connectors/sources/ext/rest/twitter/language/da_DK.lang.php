<?php

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

/*********************************************************************************
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/


$connector_strings = array (
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1">
								<tr>
									<td valign="top" width="35%" class="dataLabel">
										Få en API-nøgle og Secret fra Twitter ved at registrere din Sugar-instans som en ny applikation.<br/><br>Steps to register your instance:<br/><br/>
										<ol>
											<li>Gå til Twitter udviklerside: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Log ind med den Twitter-konto, som du vil registrere applikationen under.</li>
											<li>Skriv applikationens navn indenfor registreringsformularen. Det er dette navn, som brugere vil se, når de godkender deres Twitter-konti inde fra Sugar.</li>
											<li>Indtast en beskrivelse.</li>
											<li>Indtast en applikations-website-URL.</li>
											<li>Indtast en callback-URL (kan være hvad som helst, idet Sugar omgår denne i forbindelse med godkendelse. Eksempel: Indtast din Sugar site-URL).</li>
											<li>Accepter betingelserne i Twitter API Terms of Service.</li>
											<li>Klik "Opret din Twitter-applikation".</li>
											<li>Find the API-nøglen og API Secret på applikationssiden under fanen "API Keys". Indtast nøgle og Secret herunder.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter brugernavn',
    'LBL_ID' => 'Twitter brugernavn',
	'company_url' => 'Link',
    'oauth_consumer_key' => 'API-nøgle',
    'oauth_consumer_secret' => 'API-hemmelighed',
);

?>
