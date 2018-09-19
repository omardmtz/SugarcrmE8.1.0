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
										Skaff en API-nøkkel og hemmelighet fra Twitter ved å registrere din Sugar-instans som en ny applikasjon.<br/><br>Trinn for å registrere instansen din:<br/><br/>
										<ol>
											<li>Gå til Twitter Developers-nettstedet: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Logg på med Twitter-kontoen du vil registrere applikasjonen under.</li>
											<li>I registreringsskjemaet skirver du inn et navn for applikasjonen. Dette er navnet brukere vil se når de autentiserer Twitter-kontoene sine i Sugar.</li>
											<li>Angi en beskrivelse.</li>
											<li>Angi en URL-adresse for applikasjonen.</li>
											<li>Angi en Callback URL (kan være hva som helst siden Sugar omgår denne under autentiseringen. Eksempel: Angi URL-adressen til ditt Sugar-nettsted).</li>
											<li>Godta Twitters API-tjenestevilkår.</li>
											<li>Klikk på "Opprett din Twitter-applikasjon".</li>
											<li>På applikasjonssiden finner du API-nøkkelen og API-hemmeligheten under fanen "API Keys". Angi nøkkelen og hemmeligheten nedenfor.</li>
										</ol>
									</td>
								</tr>
							</table>
 
Kontekst | Be om kontekst
$connector_strings[\'LBL_LICENSING_INFO\']',
    'LBL_NAME' => 'Twitter-brukernavn',
    'LBL_ID' => 'Twitter-brukernavn',
	'company_url' => 'Link',
    'oauth_consumer_key' => 'API-nøkkel',
    'oauth_consumer_secret' => 'API-hemmelighet',
);

?>
