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
Merr kodin API dhe atë sekret nga Google duke e regjistruar rastin tënd Sugar si aplikim të ri.
<br/><br>Hapat për të regjistruar rastin tënd:
<br/><br/>
<ol>
<li>Shko te faqja Zhvilluesit e Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Identifikohu me llogarinë në Google me të cilën dëshiron të regjistrosh aplikimin.</li>
<li>Krijo projekt të ri</li>
<li>Jep emrin e projektit dhe kliko Krijo.</li>
<li>Pasi të krijohet projekti, aktivizo Google Drive dhe Google Contacts API</li>
<li>Tek API dhe Autentikimi > Seksioni i kredencialeve, krijo ID të re klienti</li>
<li>Zgjidh Aplikimi në ueb dhe kliko Konfiguro te dritarja e pranimit</li>
<li>Jep emrin e produktit dhe kliko Ruaj</li>
<li>Te seksioni i URL-ve të autorizuara të ridrejtuara, jep URL-në e mëposhtme: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Kliko krijo ID klienti</li>
<li>Kopjo ID-në e klientit dhe kodin e tij sekret në kutitë e mëposhtme</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID-ja e klientit',
    'oauth2_client_secret' => 'Kodi sekret i klientit',
);
