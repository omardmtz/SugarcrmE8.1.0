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
Holen Sie sich den Google API-Key und Secret-Code, indem Sie Ihre Sugar-Instanz als neue Anwendung registrieren.
<br/><br>So registrieren Sie Ihre Instanz:
<br/><br/>
<ol>
<li>Gehen Sie zur Google Developers-Webseite:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Melden Sie sich mit dem Google-Konto an, unter dem Sie diese Anwendung registrieren möchten.</li>
<li>Legen Sie ein neues Projekt an</li>
<li>Geben Sie den Projektnamen ein und klicken Sie auf "Erstellen".</li>
<li>Aktivieren Sie daraufhin Google Drive und die Google Contacts API</li>
<li>Erstellen Sie in dem Abschnitt "APIs & Auth > Anmeldedaten" eine neue Client ID </li>
<li>Wählen Sie Web-Anwendung und klicken Sie auf Einstellung des Einwilligungsdialogfensters</li>
<li>Geben Sie einen Produktnamen ein und klicken Sie auf "Speichern"</li>
<li>Geben Sie in dem Abschnitt "Autorisierte weiterleitende URIs" folgende URL ein: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klicken Sie auf "Client ID erstellen"</li>
<li>Kopieren Sie die Client ID und den Secret-Code in die untenstehenden Feldera</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Client ID',
    'oauth2_client_secret' => 'Secret-Code',
);
