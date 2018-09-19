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
Iegūstiet patērētāja atslēgu un paroli no Google, reģistrējot savu Sugar instanci kā jaunu programmu.
<br/><br>Instances reģistrēšanas soļi:
<br/><br/>
<ol>
<li>Dodieties uz Google Developers vietni:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Piesakieties savā Google kontā, no kura vēlaties reģistrēt programmu.</li>
<li>Izveidojiet jaunu projektu</li>
<li>Ievadiet projekta nosaukumu un noklikšķiniet uz "Create" (izveidot).</li>
<li>Kad projekts ir izveidots, iespējojiet Google Drive un Google Contacts saskarni</li>
<li>Sadaļā APIs & Auth > Credentials izveidojiet jaunu klienta ID </li>
<li>Atlasiet "Web Application" un noklikšķiniet uz "Configure conscent screen" (Konfigurēt piekrišanas ekrānu)</li>
<li>Ievadiet produkta nosaukumu un noklikšķiniet uz "Save" (Saglabāt)</li>
<li>Sadaļā "Authorized redirect URIs" (Apstiprinātās novirzīšanas URL) ievadiet šādu URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Noklikšķiniet uz "create client id" (Izveidot klienta ID)</li>
<li>Iekopējiet klienta ID un klienta paroli zemāk redzamajos logos</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Klienta ID',
    'oauth2_client_secret' => 'Klienta parole',
);
