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
										Verkrijg een API-sleutel en geheim van Twitter door uw versie van Sugar te registreren als nieuwe toepassing.<br/><br>Stappen voor het registreren van uw versie van Sugar:<br/><br/>
										<ol>
											<li>Ga naar de Twitter Developers site: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Meld u aan met het Twitter account waaronder u de toepassing wilt registreren.</li>
											<li>Voer op het registratieformulier een naam in voor de toepassing. Dit is de naam die gebruikers zien als zij hun Twitter accounts authenticeren vanuit Sugar.</li>
											<li>Voer een beschrijving in.</li>
											<li>Voer een website URL in van de toepassing.</li>
											<li>Voer een terugbel-URL in (kan alles zijn, gezien Sugar deze omzeilt bij authenticatie. Voorbeeld: de URL van uw Sugar website).</li>
											<li>Accepteer de Twitter API Servicevoowaarden.</li>
											<li>Klik op "Maak uw Twitter toepassing aan".</li>
											<li>Zoek op de pagina van de toepassing de API-sleutel en het API geheim onder het tabblad "API sleutels". Voer de sleutel en het geheim hieronder in.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter-gebruikersnaam',
    'LBL_ID' => 'Twitter-gebruikersnaam',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API sleutel',
    'oauth_consumer_secret' => 'API geheim',
);

?>
