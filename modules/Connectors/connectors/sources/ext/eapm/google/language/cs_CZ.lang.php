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
Získejte klíč rozhraní API a tajný klíč rozhraní API od společnosti Google pomocí registrace vaší instance Sugar jako nové aplikace.
<br/><br>Kroky k registraci instance:
<br/><br/>
<ol>
<li>Přejděte na stránku Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Přihlaste se pomocí účtu Google, pod kterým chcete zaregistrovat aplikaci.</li>
<li>Vytvořte nový projekt.</li>
<li>Zadejte název projektu a klikněte na tlačítko vytvořeni.</li>
<li>Po vytvoření projektu povolte služby Google Drive a Google Contacts API</li>
<li>V části APIs & Auth > Credentials vytvořte nové ID klienta.</li>
<li>Vyberte položku Web Application a klikněte na obrazovku Configure conscent.</li>
<li>Zadejte název produktu a klikněte na položku Save</li>
<li>V části Authorized redirect URIs zadejte následující adresu URL: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Klikněte na vytvoření ID klienta</li>
<li>Do níže uvedených polí zkopírujte ID klienta a tajný klíč klienta.</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID klienta',
    'oauth2_client_secret' => 'Tajný klíč klienta',
);
