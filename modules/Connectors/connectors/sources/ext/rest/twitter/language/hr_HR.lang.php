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
										Nabavite API ključ i tajni kod od Twittera tako da registrirate svoju instancu Sugar kao novu aplikaciju.<br/><br>Koraci za registraciju instance:<br/><br/>
										<ol>
											<li>Idite na web-mjesto Twitter za razvojne inženjere: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Prijavite se s pomoću Twitter računa pod kojim biste željeli registrirati aplikaciju.</li>
											<li>U obrascu za registraciju unesite naziv za aplikaciju. To je naziv koji će korisnici vidjeti pri provjeri autentičnosti svojih Twitter računa iz aplikacije Sugar.</li>
											<li>Unesite opis.</li>
											<li>Unesite URL adresu web-mjesta aplikacije.</li>
											<li>Unesite URL adresu poziva za povrat (može biti bilo što s obzirom na to da Sugar to zaobilazi pri provjeri autentičnosti. Primjer: unesite svoju URL adresu web-mjesta Sugar).</li>
											<li>Prihvatite uvjete upotrebe Twitter API.</li>
											<li>Kliknite na „Create your Twitter application” (Stvorite svoju Twitter aplikaciju).</li>
											<li>Na stranici aplikacije pronađite API ključ i tajni kod u kartici „API Keys” (API ključevi). Unesite ključ i tajni kod u nastavku.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Korisničko ime za Twitter',
    'LBL_ID' => 'Korisničko ime za Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API ključ',
    'oauth_consumer_secret' => 'API tajni kod',
);

?>
