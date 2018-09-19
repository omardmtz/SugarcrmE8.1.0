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
Få en API-nøkkel og -hemmelighet fra Google ved å registrere Sugar-forekomsten som et nytt program.
<br/><br>Trinn for å registrere forekomsten:
<br/><br/>
<ol>
<li>Gå til Google Developers-siden:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Logg på med Google-kontoen du vil registrere i programmet..</li>
<li>Opprett et nytt prosjekt</li>
<li>Angi et prosjektnavn og klikk på opprett.</li>
<li>Etter prosjektet er opprettet, aktiverer du Google Disk og Google Kontakter-API-en</li>
<li> Gå til APIs & Auth > Credentials for å opprette en ny klient-ID </li>
<li>Velg Web Application og klikk på Configure consent screen</li>
<li>Angi et produktnavn og klikk på Save</li>
<li>Under Autorized redirect URIs-seksjonen angir du følgende URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klikk på Create client id</li>
<li>Kopier klient-ID-en og klient-hemmeligheten inn i feltene nedenfor</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Klient-ID',
    'oauth2_client_secret' => 'Klient-hemmelighet',
);
