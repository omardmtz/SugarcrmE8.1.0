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
Отримайте ключ API та секретний ключ Google, зареєструвавши додаток для своєї копії Sugar.
<br/><br>Ось як це зробити:
<br/><br/>
<ol>
<li>Перейдіть на сайт розробника Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Увійдіть в обліковий запис Google, у якому потрібно зареєструвати додаток.</li>
<li>Створіть новий проект.</li>
<li><a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>Введіть назву проекту та натисніть Create (Створити).</li>
<li>Коли проект буде створено, увімкніть Google Диск і API Контактів Google.</li>
<li>У розділі APIs & Auth (API та авторизація) > Credentials (Облікові дані) створіть новий ідентифікатор клієнта.</li>
<li>Виберіть пункт Web Application (Веб-додаток) та натисніть Configure conscent screen (Налаштувати екран погодження).</li>
<li>Введіть назву продукту та натисніть Save (Зберегти).</li>
<li>У розділі Authorized redirect URIs (Авторизовані URI перенаправлення) введіть посилання {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Натисніть Create client id (Створити ідентифікатор клієнта)</li>
<li>Скопіюйте ідентифікатор і секретний ключ клієнта та вставте їх у поля нижче.</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Ідентифікатор клієнта',
    'oauth2_client_secret' => 'Секретний ключ клієнта',
);
