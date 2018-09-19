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
Gaukite API raktą ir slaptažodį iš „Google“ užregistravę savo „Sugar“ egzempliorių kaip naują programą.
<br/><br>Kaip užregistruoti egzempliorių:
<br/><br/>
<ol>
<li>Įeikite į svetainę „Google Developers“:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Prisijunkite naudodami „Google“ paskyrą, pagal kurią norite registruoti programą.</li>
<li>Sukurkite naują projektą.</li>
<li>Įveskite projekto pavadinimą ir spustelėkite „create“.</li>
<li>Kai projektas sukurtas, įjunkite „Google Drive“ ir „Google Contacts“ API.</li>
<li>Dalyje „APIs & Auth“ > skyriuje „Credentials“ sukuriamas naujas kliento ID. </li>
<li>Pasirinkite „Web Application“ ir spustelėkite rodinį „Configure conscent“.</li>
<li>Įveskite produkto pavadinimą ir spustelėkite „Save“.</li>
<li>Dalyje „Authorized redirect“ URL skyriuje įveskite šį URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Spustelėkite „Create client id“.</li>
<li>Nukopijuokite kliento ID ir kliento slaptažodį toliau esančius laukus.</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Kliento ID',
    'oauth2_client_secret' => 'Client secret',
);
