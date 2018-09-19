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
									<td valign="top" width="35%" class="dataLabel">Skaffa en API-nyckel och hemlighet från Twitter genom att skapa en applikation för din Sugarinstans.<br/><br>Använd följande steg för att skapa en applikation för din instans:<br/><br/>
										<ol>
											<li>Gå till Twitters Utvecklarsida: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Logga in genom att använda Twitterkontot under vilket du registrerat din applikation.</li>
											<li>Under registreringsformuläret, skriv in namnet för applikationen. Det är det här namnet användarna kommer att se när de autentiserar deras Twitterkonton inifrån Sugar.</li>
											<li>Ge en beskrivning.</li>
											<li>Skriv in en hemside-URL för applikationen (kan vara vad som helst).</li>
											<li>Välj "Browser" som Application Type.</li>
											<li>Efter att ha valt "Browser" som Application Type, skriv in en Callback URL (Kan vara vad som helst, eftersom Sugar kringgår det här vid autentisering. T.ex. Slå in din Sugar-sidas URL).</li>
											<li>Skriv in säkerhetsorden</li>
											<li>Klicka på "Register application".</li>
											<li>Acceptera Twitters Användarvillkor för API.</li>
											<li>Under applikationssidan, hitta API-nyckel och API-hemlighet. Skriv in nyckel och hemlighet nedanför.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Användarnamn på Twitter',
    'LBL_ID' => 'Användarnamn på Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API-nyckel',
    'oauth_consumer_secret' => 'API-hemlighet',
);

?>
