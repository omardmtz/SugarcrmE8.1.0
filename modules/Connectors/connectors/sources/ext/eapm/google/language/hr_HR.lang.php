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
Pribavite API ključ i tajni kod od Googlea tako da registrirate svoju instancu Sugar kao novu aplikaciju.
<br/><br>Koraci za registraciju instance:
<br/><br/>
<ol>
<li>Idite na web-mjesto Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Prijavite se s pomoću Google računa pod kojim biste željeli registrirati aplikaciju.</li>
<li>Stvorite novi projekt</li>
<li>Unesite naziv projekta i kliknite na Stvori.</li>
<li>Nakon stvaranja projekta omogućite API za Google Disk i Google Kontakte</li>
<li>U dijelu API-ovi i provjera autentičnosti > Vjerodajnice stvorite ID novog klijenta </li>
<li>Odaberite „Web-aplikacija” i kliknite na Konfiguriraj zaslon za pristanak</li>
<li>Unesite naziv proizvoda i kliknite na Spremi</li>
<li>U dijelu Autorizirane URL adrese za preusmjeravanje unesite sljedeću URL adresu: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Kliknite na Stvori ID klijenta</li>
<li>Kopirajte ID klijenta i tajni kod klijenta u okvire u nastavku</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID klijenta',
    'oauth2_client_secret' => 'Tajni kod klijenta',
);
