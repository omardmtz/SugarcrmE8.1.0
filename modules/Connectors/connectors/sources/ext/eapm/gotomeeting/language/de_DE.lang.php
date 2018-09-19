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
    'LBL_LICENSING_INFO' =>
'<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Rufen Sie einen Kunden-Schlüssel von LogMeIn ab, indem Sie eine neue GoToMeeting-Anwendung registrieren.<br>
&nbsp;<br>
Vorgehensweise zur Registrierung Ihrer Instanz:<br>
&nbsp;<br>
<ol>
<li>Melden Sie sich mit Ihrem LogMeIn Developer Center-Konto an:  <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Klicken Sie auf "Meine Apps"</li>
    <li>Klicken Sie auf "Neue App hinzufügen"</li>
    <li>Füllen Sie alle Felder des Formulars "App hinzufügen" aus:</li>
         <ul>
             <li>App-Name</li>
             <li>Beschreibung</li>
             <li>Produkt-API: wählen Sie GoToMeeting</li>
             <li>App-URL: Geben Sie die URL Ihrer Instanz ein</li>
         </ul>
     <li>Klicken Sie auf "App erstellen"</li>
     <li>Klicken Sie in der Appliste auf den Namen Ihrer App</li>
     <li>Klicken Sie auf den Tab "Schlüssel"</li>
     <li>Kopieren Sie den Kunden-Schlüsselwert und geben Sie ihn unten ein</li>
 </ol> </td></tr></table>',
    'oauth_consumer_key' => 'Kunden-Schlüssel',
);
