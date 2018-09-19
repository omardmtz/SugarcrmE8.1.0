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
										Hanki API-avain ja Secret Twitteristä rekisteröimällä Sugar-instanssisi uudeksi sovellukseksi:<br/><br/>Instanssin rekisteröinti:<br/><br/>
										<ol>
											<li>Siirry Twitter-kehittäjien sivustolle: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Kirjaudu sisään Twitter-tilille, johon haluat rekisteröidä sovelluksen.</li>
											<li>Kirjoita rekisteröintilomakkeelle sovelluksen nimi. Tämä on nimi, jonka käyttäjät näkevät varmentaessaan Twitter-tilinsä Sugarista.</li>
											<li>Kirjoita kuvaus.</li>
											<li>Kirjoista sovelluksen verkkosivuston URL-osoite.</li>
											<li>Kirjoita URL-takaisinkutsuosoite (tämä voi olla mikä tahansa, sillä Sugar ohittaa tämän varmennuksen yhteydessä. Esimerkki: Kirjoita Sugar-sivustosi URL-osoite).</li>
											<li>Hyväksy Twitter API:n palveluehdot.</li>
											<li>Napsauta "Luo oma Twitter-sovellus".</li>
											<li>Etsi sovelluksen sivulta API-avain ja API Secret "API-avaimet"-välilehdellä. Kirjoita avain ja Secret alle.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter-käyttäjänimi',
    'LBL_ID' => 'Twitter-käyttäjänimi',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API-avain',
    'oauth_consumer_secret' => 'API-salasana',
);

?>
