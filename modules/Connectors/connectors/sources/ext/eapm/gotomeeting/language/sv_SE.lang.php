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
Få en konsumentnyckel från LogMeIn genom att registrera en ny GoToMeeting-applikation.<br>
&nbsp;<br>
Steg för att registrera ditt exemplar:<br>
&nbsp;<br>
<ol>
    <li>Logga in på ditt LogMeIn Developer Center-konto: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Klicka på Mina appar</li>
    <li>Klicka på lägg till en ny app</li>
    <li>Fyll i alla fält på Lägg till app-formuläret:</li>
        <ul>
            <li>Appnamn</li>
            <li>Beskrivning</li>
            <li>Produkt-API: Välj GoToMeeting</li>
            <li>Applikations-URL: Mata in ditt exemplars URL</li>
        </ul>
    <li>Klicka på Skapa app-knappen</li>
    <li>Från listan över appar, klicka på namnet för din app</li>
    <li>Klicka på Nycklar-fliken</li>
    <li>Kopiera kundnyckelns värde och mata in det nedan</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Kundnyckel',
);
