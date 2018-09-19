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
Hankige API võti ja Secret Google\'ist, registereerides oma Sugari eksemplari uue rakendusena.
<br/><br>Eksemplari registreerimise etapid on järgmised:
<br/><br/>
<ol>
<li>Minge järgmisele Google\'i Developers saidile:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Logige sisse Google\'i kontoga, mille alt soovite rakenduse registreerida.</li>
<li>Looge uus projekt</li>
<li>Sosestage projekti nime ja klõpsake loo.</li>
<li>Kui projekt on loodud, lubage Google Drive\'i ja Google\'i kontaktide API</li>
<li>Looge uus kliendi ID jaotises API-d & Aut. > Mandaadid </li>
<li>Valige Veebirakendus ja klõpsake suvandit Konfigureeri nõusoleku ekraani</li>
<li>Sisestage toote nimi ja klõpsake nuppu Salvesta</li>
<li>Sisestage jaotises Volitatud ümbersuunamise URI-d järgmine URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klõpsake Loo kliendi ID</li>
<li>Kopeerige kliendi ID ja kliendi salavõti allolevatesse kastidesse</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Kliendi ID',
    'oauth2_client_secret' => 'Kliendi salavõti',
);
