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
Få en API-nyckel och tillhörande hemlighet från Google genom att registrera din Sugar-instans som en ny applikation.
<br/><br>Så här registrerar du din instans:
<br/><br/>
<ol>
<li>Gå till sidan för Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Logga in med Google-kontot du vill registrera applikationen på.</li>
<li>Skapa ett nytt projekt</li>
<li>Skriv in ett projektnamn och tryck på skapa.</li>
<li>När projektet väl är skapat, aktivera API:erna för Google Drive och Google Contacts</li>
<li>Skapa ett nytt klient-ID i menyn API & Autentisering > Inloggningsuppgifter </li>
<li>Välj Webbapplikation och klicka på Anpassa medgivande-skärm</li>
<li>Skriv in ett produktnamn och klicka på Spara</li>
<li>Skriv in följande adress under "Auktoriserade omdirigerings-URI:er": {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klicka på skapa klient-ID</li>
<li>Kopiera klient-ID:t och klienthemligheten till lådorna nedanför</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Klient-ID',
    'oauth2_client_secret' => 'Klienthemlighet',
);
