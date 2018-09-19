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
Få en forbrugernøgle fra LogMeIn ved at registrere et ny GoToMeetin-program.<br>
&nbsp;<br>
Trin i forbindelse med at registrere din instans:<br>
&nbsp;<br>
<ol>
    <li>Log ind på din LogMeIn udviklercenterkonto: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Klik på mine apps</li>
    <li>Klik på tilføj ny app</li>
    <li>Udfyld alle felter på tilføj app-formularen:</li>
        <ul>
            <li>Appnavn</li>
            <li>Beskrivelse</li>
            <li>Produkt-API: Vælg GoToMeeting</li>
            <li>Program-URL: Indtast din instans-URL</li>
        </ul>
    <li>Klik på opret app-knappen</li>
    <li>Klik på navnet på din app på listen over apps</li>
    <li>Klik på nøgle-fanen</li>
    <li>Kopier forbrugernøgleværdien og indtast den herunder</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Forbrugernøgle',
);
