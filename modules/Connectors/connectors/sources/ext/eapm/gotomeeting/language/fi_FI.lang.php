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
Hanki kuluttajan avain LogMeln\'iltä rekisteröimällä uusi GoToMeeting-sovellus.<br>
&nbsp;<br>
Instanssin rekisteröinti:<br>
&nbsp;<br>
<ol>
    <li>Kirjaudu LogMeIn Developer Center -tilillesi: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Napsauta My Apps</li>
    <li>Napsauta Add a new App</li>
    <li>Täytä kaikki kentät Add App -lomakkeessa:</li>
        <ul>
            <li>Sovelluksen nimi</li>
            <li>Kuvaus</li>
            <li>Tuotteen API: Valitse GoToMeeting</li>
            <li>Sovelluksen URL: Kirjoita instanssisi URL-osoite</li>
        </ul>
    <li>Napsauta Create App -painiketta</li>
    <li>Napsauta sovellusten luettelossa sovelluksesi nimeä</li>
    <li>Napsauta Keys-välilehteä</li>
    <li>Kopioi kuluttajan avain ja lisää se alle</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Kuluttajan avain',
);
