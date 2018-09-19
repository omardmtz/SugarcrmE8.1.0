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
Szerezzen be egy fogyasztói kulcsot a LogMeIn rendszerből egy új GoToMeeting alkalmazás regisztrálásával.<br>
&nbsp;<br>
A példány regisztrálásának a lépései:<br>
&nbsp;<br>
<ol>
    <li>Jelentkezzen be a LogMeIn fejlesztőközpont fiókkal: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Kattintson a My Apps (Saját alkalmazások) lehetőségre</li>
    <li>Kattintson az Add a new App (Új alkalmazás hozzáadása) lehetőségre</li>
    <li>Töltse ki az összes mezőt az Add App (Alkalmazás hozzáadása) űrlapban:</li>
        <ul>
            <li>App Name (Alkalmazás neve)</li>
            <li>Description (Leírás)</li>
            <li>Product API (Termék API): Válassza a GoToMeeting lehetőséget</li>
            <li>Application URL (Alkalmazás URL-cím): Adja meg a példány URL-címét</li>
        </ul>
    <li>Kattintson a Create App Button (Alkalmazás létrehozása) gombra</li>
    <li>Az alkalmazások listájában kattintson az alkalmazás nevére</li>
    <li>Kattintson a Keys (Kulcsok) fülre</li>
    <li>Másolja le a Consumer Key (Fogyasztói kulcs) értéket, és adja meg alább</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Fogyasztói kulcs',
);
