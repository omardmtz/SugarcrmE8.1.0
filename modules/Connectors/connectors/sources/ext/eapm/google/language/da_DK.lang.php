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
Modtag en API-nøgle og en hemmelighed fra Google ved registrering af din Sugar-instance som ny applikation.
<br/><br>Trinnene for registrering af din instance:
<br/><br/>
<ol>
<li>Gå til siden Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Log ind på den Google konti, hvori du ønsker at registrere din applikation.</li>
<li>Opret et nyt projekt</li>
<li>Indtast et projektnavn og klik på create.</li>
<li>Når projektet er oprettet, aktivér Google Drive og Google Contact API</li>
<li>Under APIs & Auth > Opret en ny klient-ID i afsnittet Credentials </li>
<li>Vælg Web applikationen og klik på skærmen Configure conscent</li>
<li>Indtast et produktnavn og klik på Save</li>
<li>Under sektionen Authorized redirect URIs indtast følgende url: {$SITE_URL}/index.php?
module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klik på opret klient-ID</li>
<li>Kopiér klient-ID og klient hemmeligheden til kasserne nedenfor</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Klient ID',
    'oauth2_client_secret' => 'Klient hemmelighed',
);
