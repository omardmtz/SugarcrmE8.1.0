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
Hanki API-avain ja salasana Googlelta rekisteröimällä Sugar-instanssisi uudeksi sovellukseksi.
<br/><br>Instanssin rekisteröinti vaiheittain:
<br/><br/>
<ol>
<li>Siirry Google Developers -sivulle:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Kirjaudu sisään Google-tilille, jolle haluat rekisteröidä sovelluksen.</li>
<li>Luo uusi projekti</li>
<li>Kirjoita projektin nimi ja napsauta "Create" (luo).</li>
<li>Kun projekti on luotu, ota käyttöön Google Drive ja Google Contacts API</li>
<li>Kohdassa "APIs & Auth > Credentials" napsauta "Create new Client ID" (Luo uusi asiakkaan ID)</li>
<li>Valitse "Web Application" (Verkkosovellus) ja napsauta "Configure consent screen" (Muokkaa hyväksymissivua)</li>
<li>Kirjoita tuotteen nimi ja napsauta "Save" (tallenna)</li>
<li>Kirjoita "Authorized redirect URIs" (Sallitut uudelleenohjausosoitteet) -kohtaan tämä URL-osoite: {$SITE_URL}/index.php?module=module=EAPM&action=GoogleOauth2Redirect</li>
<li>Napsauta "Create Client ID" (Luo asiakkaan ID)</li>
<li>Kopioi asiakkaan ID ja asiakkaan salasana alla oleviin kenttiin</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Asiakkaan ID',
    'oauth2_client_secret' => 'Asiakkaan salasana',
);
