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
Ottieni una chiave utente da LogMeIn registrando una nuova applicazione GoToMeeting.<br>
&nbsp;<br>
Azioni per la registrazione della propria istanza:<br>
&nbsp;<br>
<ol>
    <li>Effettuare il login al proprio account LogMeIn Developer Center: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Fare clic su My Apps (Le mie app)</li>
    <li>Fare clic su Add a new App (Aggiungi una nuova app)</li>
    <li>Completare tutti i campi del modulo Add App (Aggiungi app):</li>
        <ul>
            <li>App Name (Nome app)</li>
            <li>Description (Descrizione)</li>
            <li>Product API (API prodotto): selezionare GoToMeeting</li>
            <li>Application URL (URL applicazione): immettere l\'URL istanza</li>
        </ul>
    <li>Fare clic sul pulsante Create App (Crea app)</li>
    <li>Dall\'elenco delle app, fare clic sul nome della propria app</li>
    <li>Fare clic sulla scheda Keys (Chiavi)</li>
    <li>Copiare il valore della chiave utente e inserirlo sotto</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Chiave utente',
);
