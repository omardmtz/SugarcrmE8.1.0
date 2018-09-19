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
Снабдете се с API ключ и криптиращ стринг от Google като регистрирате вашата инсталация на Sugar като ново приложение.
<br/><br>Стъпки за регистриране на инсталацията:
<br/><br/>
<ol>
<li>Отидете до сайта Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Идентифицирайте се като използвате Google профила, с който бихте искали да регистрирате приложението.</li>
<li>Създайте нов проект</li>
<li>Въведете Име на проекта и натиснете "създай".</li>
<li>След като проектът е създаден, активирайте API Google Drive и Google Contacts </li>
<li>В полето под APIs & Auth > Credentials създайте нова идентификация на клиент </li>
<li>Изберете Web Application и натиснете Configure conscent screen</li>
<li>Въведете име на продукт и натистене Запази</li>
<li>В полето под Authorized redirect URIs въведете следната url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Натиснете "създай идентификация на клиент"</li>
<li>Копирайте идентификацията на клиента и криптиращия стринг на клиента в полетата по-долу</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Идентификация на клиента',
    'oauth2_client_secret' => 'Криптиращ стринг на клиента',
);
