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
Pribavite API ključ i Tajnu od Google-a tako što ćete registrovati Vašu Sugar instancu u novoj aplikaciji.
<br/><br>Koraci za registraciju Vaše instance:
<br/><br/>
<ol>
<li>Idite na lokaciju Google programera:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Prijavite se pomoću Google naloga pod kojim biste želeli da registrujete aplikaciju.</li>
<li>Kreirajte nov projekat</li>
<li>Upišite Naziv projekta i kliknite na kreiraj.</li>
<li>Kada je projekat kreiran aktivirajte Google drajv i API Google kontakta</li>
<li>Pod odeljkom API-ji i Autorizacija > Ovlašćenja kreirajte nov id klijenta</li>
<li>Odaberite Web aplikaciju i kliknite na ekran za odobrenje Konfiguracije</li>
<li>Upišite ime proizvoda i kliknite Snimi</li>
<li>Pod odeljkom Ovlašćeno preusmeravanje URI-ja unesite sledeći url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Kliknite na kreiraj id korisnika</li>
<li>Kopirajte id korisnika i tajnu korisnika u polja ispod</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID klijenta',
    'oauth2_client_secret' => 'Tajna klijenta',
);
