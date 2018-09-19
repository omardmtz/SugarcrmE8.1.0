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
U kunt van Google een API-sleutel en geheim krijgen door uw Sugar-exemplaar als een nieuwe applicatie te registreren.
<br/><br>Stappen om uw exemplaar te registreren:
<br/><br/>
<ol>
<li>Ga naar de Google Developers-website:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Meld u aan met de Google-account waarmee u de applicatie wilt registreren.</li>
<li>Maak een nieuw project</li>
<li>Voer een projectnaam in en klik op aanmaken.</li>
<li>Zodra het project werd gemaakt, activeer Google Drive en Google Contacts API</li>
<li>In het gedeelte API\'s & Auth > Verificatiegegevens, maak een nieuwe klant-id </li>
<li>Selecteer Web Application en klik op het scherm Configure conscent</li>
<li>Voer een productnaam in en klik op opslaan</li>
<li>In het gedeelte Authorized redirect URIs voert u de URL in: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klik op klant-id maken</li>
<li>Kopieer de klant-id en het geheim in de onderstaande vakjes</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Klant-id',
    'oauth2_client_secret' => 'Geheim client',
);
