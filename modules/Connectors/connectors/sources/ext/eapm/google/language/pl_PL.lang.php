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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Uzyskaj klucz API i tajny klucz od firmy Google, rejestrując swoją instancję Sugar jako nową aplikację.
<br/><br>Kroki do zarejestrowania instancji:
<br/><br/>
<ol>
<li>Przejdź do strony Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Zaloguj się do konta Google, dla którego chcesz zarejestrować aplikację.</li>
<li>Utwórz nowy projekt</li>
<li>Wprowadź nazwę projektu i kliknij opcję tworzenia.</li>
<li>Po utworzeniu projektu włącz interfejs API Google Drive i Google Contacts</li>
<li>W obszarze APis & Auth (API i autoryzacja), w sekcji Credentials (Dane logowania) utwórz nowy identyfikator klienta </li>
<li>Wybierz opcję Web Application (Aplikacja sieciowa) i kliknij opcję Configure consent screen (Konfiguruj ekran zgody)</li>
<li>Wpisz nazwę produktu i kliknij opcję Save (Zapisz) </li>
<li>W części Authorized redirect URI (Adres URL autoryzowanego przekierowania) wprowadź następujący adres URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Kliknij opcję tworzenia identyfikatora klienta</li>
<li>Skopiuj identyfikator klienta i tajny klucz klienta i wklej w polach poniżej</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID klienta',
    'oauth2_client_secret' => 'Tajny kod klienta',
);
