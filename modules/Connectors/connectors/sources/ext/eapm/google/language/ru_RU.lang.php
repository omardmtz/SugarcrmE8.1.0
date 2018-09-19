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
Получите ключ API и Секретный ключ от Google, зарегистрировав свою систему SugarCRM в качестве нового приложения.
<br/><br>Шаги для регистрации вашей системы:
<br/><br/>
<ol>
<li>Перейдите на веб-сайт Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'> https://console.developers.google.com/project</a>.</li>

 <li>Войдите, используя аккаунт Google, в котором вы хотите зарегистрировать приложение.</li>
<li>Создайте новый проект</li>
<li>Введите имя проекта и нажмите кнопку «Create» (Создать).</li>
<li>После создания проекта включите Google Диск и API контактов Google</li>
<li>В разделе APIs & Auth (API и полномочия) > Credentials (Учетные данные) создайте новый идентификатор клиента</li>
<li>Выберите пункт Web Application (Веб-приложение) и нажмите кнопку Configure conscent screen (Настройки соглашения)</li>
<li>Введите название продукта и нажмите кнопку Save (Сохранить)</li>
<li>В разделе Authorized redirect URIs (Разрешенные для перенаправления универсальные идентификаторы ресурсов) введите следующий URL-адрес: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Нажмите кнопку Create client id (Создать идентификатор клиента)</li>
<li>Скопируйте идентификатор и секретный ключ клиента в поля ниже</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Идентификатор клиента',
    'oauth2_client_secret' => 'Секретный ключ клиента',
);
