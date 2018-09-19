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
Regisztrálja Sugar példányát új alkalmazásként, és szerezzen a Google-tól API kulcsot és titkos kulcsot.
<br/><br>A példány regisztrálásának lépései:
<br/><br/>
<ol>
<li>Látogasson el a Google Developers oldalra:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Jelentkezzen be azzal a Google fiókkal, amely alá regisztrálni szeretné az alkalmazást.</li>
<li>Hozzon létre új projektet.</li>
<li>Adjon meg egy projektnevet, és kattintson a létrehozásra.</li>
<li>Miután létrehozta a projektet, engedélyezze a Google Drive és Google Contacts API-t</li>
<li>Az APIs és eng.> Hitelesítő adatok részben hozzon létre egy új ügyfélazonosítót </li>
<li>Válassa a Webes alkalmazást, és kattintson a Jóváhagyás konfigurálása képernyőre </li>
<li>Adja meg a terméknevet, és kattintson a Mentés gombra</li>
<li>Az Engedélyezett átirányítási URI részbe írja be a következő URL címet: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Kattintson az ügyfélazonosító létrehozására</li>
<li>Másolja be az ügyfélazonosítót és ügyféltitkot az alábbi négyzetekbe</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Ügyfélazonosító',
    'oauth2_client_secret' => 'Ügyféltitok',
);
