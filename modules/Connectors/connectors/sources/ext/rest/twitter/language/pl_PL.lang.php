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
										Uzyskaj klucz API i poufny klucz z serwisu Twitter, rejestrując instancję Sugar jako nową aplikację.<br/><br>Kroki do rejestracji instancji:<br/><br/>
										<ol>
											<li>Przejdź do strony programistów w serwisie Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Zaloguj się na konto Twitter, za pomocą którego chcesz zarejestrować aplikację.</li>
											<li>W formularzu rejestracji wprowadź nazwę aplikacji. Nazwa ta będzie widoczna dla użytkowników podczas autoryzacji kont Twitter z poziomu systemu Sugar.</li>
											<li>Wprowadź opis.</li>
											<li>Wprowadź adres URL do strony internetowej aplikacji.</li>
											<li>Wprowadź adres URL wywołania zwrotnego (może być dowolny, ponieważ system Sugar omija podczas uwierzytelniania. Przykład: Wprowadź adres URL strony Sugar).</li>
											<li>Zaakceptuj warunki korzystania z interfejsu API serwisu Twitter.</li>
											<li>Kliknij opcję Utwórz aplikację Twitter.</li>
											<li>Na stronie aplikacji znajdź Klucz API i Poufny klucz API w karcie Klucze API. Wprowadź je poniżej.</li>
										</ol>
									</td>
								</tr>
							</table>
 
Context | Request Context
$connector_strings[\'LBL_LICENSING_INFO\']
File: en_us.lang.php',
    'LBL_NAME' => 'Nazwa użytkownika Twitter',
    'LBL_ID' => 'Nazwa użytkownika Twitter',
	'company_url' => 'Adres URL',
    'oauth_consumer_key' => 'Klucz API',
    'oauth_consumer_secret' => 'Poufny klucz API',
);

?>
