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
/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Архівування Email',
    'LBL_SNIP_SUMMARY' => "Архівація Email - це автоматичний сервіс, який дозволяє користувачам імпортувати email-повідомлення в систему Sugar з будь-якого поштового клієнта або сервісу на email-адресу, надану в Sugar. Кожна система Sugar має свою унікальну email-адресу. Для імпорту email-повідомлень користувач здійснює відправку на надану email-адресу, використовуючи поля Кому, Копія, Прихована копія. Сервіс архівації email-повідомлень імпортує листи в систему Sugar. Сервіс імпортує не лише повідомлення, а також будь-які вкладення, зображення та події календаря, і створює записи всередині програми, які відносяться до існуючих записів у разі збігу email-адреси. <br/> <br/> Приклад: Якщо я користувач, то при перегляді контрагента, я зможу побачити всі листи, які відносяться до даного контрагенту, залежно від email-адреси в записі контрагента. Я також зможу побачити листи, які відносяться до контактів даного контрагента. <br/> <br/> Прийміть умови нижче, і натисніть \"Активувати\", щоб почати використовувати сервіс. Ви зможете відключити сервіс в будь-який момент. Коли сервіс буде активований, то буде відображений email-адреса, яка повинна використовуватись для сервісу.",
	'LBL_REGISTER_SNIP_FAIL' => 'Невдала спроба з&#39;єднання з сервісом архівації Email:% s!',
	'LBL_CONFIGURE_SNIP' => 'Архівування Email',
    'LBL_DISABLE_SNIP' => 'Деактивувати',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Унікальний ключ',
    'LBL_SNIP_USER' => 'Логін для архівації Email',
    'LBL_SNIP_PWD' => 'Пароль  для архівації Email',
    'LBL_SNIP_SUGAR_URL' => 'URL-адреса цього екземпляру Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL сервісу Email-архівації',
    'LBL_SNIP_USER_DESC' => 'Користувач архівації Email',
    'LBL_SNIP_KEY_DESC' => 'Ключ OAuth для архівації Email. Використовується для доступу до даної системи з метою імпорту email-повідомлень.',
    'LBL_SNIP_STATUS_OK' => 'Активовано',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Дана система Sugar успішно підключена до сервера архівації Email.',
    'LBL_SNIP_STATUS_ERROR' => 'Помилка',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'У цієї системи є активна ліцензія сервера архівації Email, але сервер повернув таку помилку.',
    'LBL_SNIP_STATUS_FAIL' => 'Неможливо зареєструватися на сервері архівації Email',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Сервіс архівації Email на даний момент недоступний. Можливо, сервіс не функціонує або з&#39;єднання до даної системи Sugar пройшло неуспішно.',
    'LBL_SNIP_GENERIC_ERROR' => 'Сервіс архівації Email на даний момент недоступний. Можливо, сервіс не функціонує або з&#39;єднання до даної системи Sugar пройшло неуспішно.',

	'LBL_SNIP_STATUS_RESET' => 'Ще не запущено',
	'LBL_SNIP_STATUS_PROBLEM' => 'Проблема: %s',
    'LBL_SNIP_NEVER' => "Ніколи",
    'LBL_SNIP_STATUS_SUMMARY' => "Статус сервісу архівації Email:",
    'LBL_SNIP_ACCOUNT' => "Контрагент",
    'LBL_SNIP_STATUS' => "Статус",
    'LBL_SNIP_LAST_SUCCESS' => "Останній успішний запуск",
    "LBL_SNIP_DESCRIPTION" => "Сервіс Email-архівації - автоматичний Сервіс email-архівації",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Він надає можливість бачити email-повідомлення, які були відправлені вашим контактам в SugarCRM або отримані від них, при цьому немає необхідності вручну імпортувати і зв&#39;язувати повідомлення.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Для використання архівації Email, Вам необхідно придбати ліцензію для Вашої системи SugarCRM.",
    "LBL_SNIP_PURCHASE" => "Натисніть тут для покупки",
    'LBL_SNIP_EMAIL' => 'Адреса архівації Email',
    'LBL_SNIP_AGREE' => "Я приймаю вищевказані умови і погоджуюся з <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;> угодою конфіденційності </a>.",
    'LBL_SNIP_PRIVACY' => 'Угода про конфіденційність',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Зворотний пінг не виконано',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Сервер архівації Email не може встановити з&#39;єднання з Вашої Sugar. Спробуйте ще раз або <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank"> зв&#39;яжіться зі службою підтримки </a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Включити архівацію Email',
    'LBL_SNIP_BUTTON_DISABLE' => 'Відключити архівацію Email',
    'LBL_SNIP_BUTTON_RETRY' => 'Спробуйте підключитися ще раз',
    'LBL_SNIP_ERROR_DISABLING' => 'Під час спроби з&#39;єднання з сервером архівації Email сталася помилка, і сервіс не був відключений.',
    'LBL_SNIP_ERROR_ENABLING' => 'Під час спроби з&#39;єднання з сервером архівації Email сталася помилка, і сервіс не був включений.',
    'LBL_CONTACT_SUPPORT' => 'Спробуйте ще раз або зверніться в службу підтримки SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Будь ласка, зв&#39;яжіться зі службою підтримки SugarCRM.',
    'ERROR_BAD_RESULT' => 'Некоректна помилка сервісу',
	'ERROR_NO_CURL' => 'Необхідні розширення cURL, але вони не були активовані',
	'ERROR_REQUEST_FAILED' => 'Не вдалося зв&#39;язатися із сервером',

    'LBL_CANCEL_BUTTON_TITLE' => 'Скасувати',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Це статус сервісу архівації Email у вашій системі. Цей статус показує, чи було успішним з&#39;єднання між сервером архівації Email і вашою системою Sugar.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Це email-адреса Email архівації, на який повинна бути здійснена відправка для імпорту постових повідомлень в Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Це URL сервера архівації Email. Всі запити, такі як активація або відключення сервісу архівації Email, будуть проходити через цей URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Це URL веб-сервісів вашої системи Sugar. Сервер архівації Email буде здійснювати підключення до вашого сервера через цей URL.',
);
