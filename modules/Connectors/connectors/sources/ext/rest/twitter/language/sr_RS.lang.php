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
										Nabavite API ključ i tajnu sa Twitter-a tako što ćete registrovati Sugar instancu kao novu aplikaciju.<br/><br>Koraci za registrovanje instance:<br/><br/>
										<ol>
											<li>Idite na Twitter sajt za programere: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Prijavite se koristeći Twitter nalog pod kojim želite da registrujete aplikaciju.</li>
											<li>Unesite naziv aplikacije u okviru formulara za registraciju. To je naziv koji će korisnici videti kada budu potvrđivali Twitter naloge u okviru Sugar-a.</li>
											<li>Unesite opis.</li>
											<li>Unesite URL veb-sajta aplikacije.</li>
											<li>Unesite Callback URL (može da bude bilo šta, pošto Sugar ovo zaobilazi prilikom autentifikacije.Primer: unesite URL Sugar sajta).</li>
											<li>Prihvatite Twitter API uslove korišćenja usluge.</li>
											<li>Kliknite na „Kreirajte Twitter aplikaciju“.</li>
											<li>Pronađite API ključ i API tajnu na stranici aplikacije u okviru kartice „API ključevi“. Unesite ključ i tajnu u nastavku.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter korisničko ime',
    'LBL_ID' => 'Twitter korisničko ime',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Potrošački ključ',
    'oauth_consumer_secret' => 'Potrošačka tajna',
);

?>
