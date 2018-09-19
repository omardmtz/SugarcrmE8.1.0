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
Skaff en forbrukernøkkel fra LogMeIn ved å registrere en ny GoToMeeting-applikasjon.<br>
&nbsp;<br>
Trinn for å registrere instansen din:<br>
&nbsp;<br>
<ol>
    <li>Logg på LogMeIn Developer Center-kontoen din: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Klikk på Mine apper</li>
    <li>Klikk på Legg til ny app</li>
    <li>Fyll ut alle felter i skjemaet Legg til app:</li>
        <ul>
            <li>Appnavn</li>
            <li>Beskrivelse</li>
            <li>Produkt-API: VelgGoToMeeting</li>
            <li>Applikasjons-URL: Angi instans-URLen</li>
        </ul>
    <li>Klikk på knappen Opprett app</li>
    <li>Fra listen med apper klikker du på navnet til appen din</li>
    <li>Klkk på fanen Nøkler</li>
    <li>Kopier verdien til forbrukernøkkelen og angi den nedenfor</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Forbrukernøkkel',
);
