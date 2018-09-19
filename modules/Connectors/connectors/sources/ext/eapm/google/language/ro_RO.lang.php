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
Obţineţi o Cheie API şi Secret de la Google înregistrându-vă instanţa Sugar ca aplicaţie nouă.
<br/><br>Paşi pentru a vă înregistra instanţa:
<br/><br/>
<ol>
<li>Accesaţi Site-ul pentru dezvoltatori Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Faceţi sign in folosind contul Google pentru care aţi dori să înregistraţi aplicaţia.</li>
<li>Creaţi un proiect nou</li>
<li>Introduceţi un Nume de proiect şi faceţi clic pe Creare.</li>
<li>După crearea proiectului, activaţi Google Drive şi Google Contacts API</li>
<li>În secţiunea API-uri şi autentificare > Credenţiale, creaţi un id nou de client </li>
<li>Selectaţi Aplicaţie Web şi faceţi clic pe ecranul Configurare consimţământ</li>
<li>Introduceţi un nume de produs şi faceţi clic pe Salvare</li>
<li>În secţiunea URl-uri de redirecţionare autorizate, introduceţi următorul url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Faceţi clic pe creare id client</li>
<li>Copiaţi id-ul clientului şi Secret client în casetele de mai jos</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID client',
    'oauth2_client_secret' => 'Secret client',
);
