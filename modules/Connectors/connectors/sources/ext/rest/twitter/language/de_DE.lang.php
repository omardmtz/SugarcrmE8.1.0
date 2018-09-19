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
										API-Schlüssel und Secret-Code von Twitter durch Registrierung Ihrer Sugar-Instanz als neue Anwendung abrufen.<br/><br>Schritte für die Registrierung Ihrer Instanz:<br/><br/>
										<ol>
											<li>Gehen Sie zur Twitter Developers-Website: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Melden Sie sich mit dem Twitter-Konto an, unter dem die Anwendung registriert werden soll.</li>
											<li>Geben Sie im Registrierungsformular einen Namen für die Anwendung ein. Dieser Name wird für die Benutzer bei der Authentifizierung ihrer Twitter-Konten innerhalb von Sugar angezeigt.</li>
											<li>Geben Sie eine Beschreibung ein.</li>
											<li>Geben Sie eine Website-URL der Anwendung ein.</li>
											<li>Geben Sie eine Rückruf-URL ein (alles möglich, da Sugar dies bei der Authentifizierung überspringt. Beispiel: Geben Sie Ihre Sugar-Website-URL ein).</li>
											<li>Nehmen Sie die Twitter API-AGB an.</li>
											<li>Klicken Sie auf "Twitter-Anwendung erstellen".</li>
											<li>Auf der Anwendungsseite finden Sie den API-Schlüssel und den API-Geheimschlüssel unter der Registerkarte "API-Schlüssel". Geben Sie den Schlüssel und den Secret-Code unten ein.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter-Benutzername',
    'LBL_ID' => 'Twitter-Benutzername',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API-Schlüssel',
    'oauth_consumer_secret' => 'API-Secret',
);

?>
