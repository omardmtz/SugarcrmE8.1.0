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
    'LBL_MODULE_NAME' => 'Email архивация',
    'LBL_SNIP_SUMMARY' => "Архивация Email - это автоматический сервис, который позволяет пользователям импортировать email-сообщения в систему Sugar из любого почтового клиента или сервиса на email-адрес, предоставленный в Sugar. Каждая система Sugar имеет свой уникальный email-адрес. Для импорта email-сообщений пользователь осуществляет отправку на предоставленный email-адрес, используя поля Кому, Копия, Скрытая копия. Сервис архивации email-сообщений импортирует письма в систему Sugar. Сервис импортирует не только сообщения, а также любые вложения, изображения и события календаря, и создает записи внутри приложения, которые относятся к существующим записям в случае совпадения email-адреса.<br /><br />Пример: Если я пользователь, то при просмотре контрагента, я смогу увидеть все письма, которые относятся к данному контрагенту, в зависимости от email-адреса в записи контрагента. Я также смогу увидеть письма, которые относятся к контактам данного контрагента.<br /><br />Примите условия ниже, и нажмите \"Активировать\", чтобы начать использовать сервис. Вы сможете отключить сервис в любой момент. Когда сервис будет активирован, то будет отображен email-адрес, который должен быть использован для сервиса.",
	'LBL_REGISTER_SNIP_FAIL' => 'Неудачная попытка соединения с сервисом архивации Email: %s!',
	'LBL_CONFIGURE_SNIP' => 'Архивация Email',
    'LBL_DISABLE_SNIP' => 'Отключить',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Уникальный ключ приложения',
    'LBL_SNIP_USER' => 'Логин для архивации Email',
    'LBL_SNIP_PWD' => 'Пароль для архивации Email',
    'LBL_SNIP_SUGAR_URL' => 'URL этой системы Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL сервиса Email-архивации',
    'LBL_SNIP_USER_DESC' => 'Пользователь архивации Email',
    'LBL_SNIP_KEY_DESC' => 'Ключ OAuth для архивации Email. Используется для доступа к данной системе с целью импорта email-сообщений.',
    'LBL_SNIP_STATUS_OK' => 'Доступен',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Данная система Sugar успешно подключена к серверу архивации Email.',
    'LBL_SNIP_STATUS_ERROR' => 'Ошибка',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'У этой системы имеется активная лицензия сервера архивации Email, но сервер вернул следующую ошибку.',
    'LBL_SNIP_STATUS_FAIL' => 'Невозможно зарегистрироваться на сервере архивации Email',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Сервис архивации Email на данный момент недоступен. Возможно, сервис не функционирует или соединение к данной системе Sugar неуспешно.',
    'LBL_SNIP_GENERIC_ERROR' => 'Сервис архивации Email на данный момент недоступен. Возможно, сервис не функционирует или соединение к данной системе Sugar неуспешно.',

	'LBL_SNIP_STATUS_RESET' => 'Еще не запущено',
	'LBL_SNIP_STATUS_PROBLEM' => 'Проблема: %s',
    'LBL_SNIP_NEVER' => "Никогда",
    'LBL_SNIP_STATUS_SUMMARY' => "Статус сервиса архивации Email:",
    'LBL_SNIP_ACCOUNT' => "Контрагент",
    'LBL_SNIP_STATUS' => "Статус",
    'LBL_SNIP_LAST_SUCCESS' => "Последний успешный запуск:",
    "LBL_SNIP_DESCRIPTION" => "Сервис Email-архивации - автоматический Сервис email-архивации",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Он предоставляет возможность видеть email-сообщение, которые были отправлены вашим контактам в SugarCRM или получены от них, при этом нет необходимости вручную импортировать и связывать сообщения.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Для использования архивации Email, Вам необходимо приобрести лицензию для Вашей системы SugarCRM.",
    "LBL_SNIP_PURCHASE" => "Нажмите здесь для покупки",
    'LBL_SNIP_EMAIL' => 'Адрем архивации Email',
    'LBL_SNIP_AGREE' => "Я принимаю вышеуказанные условия и соглашаюсь с <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;>соглашением конфиденциальности</a>.",
    'LBL_SNIP_PRIVACY' => 'соглашение о конфиденциальности',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Обратный пинг неуспешен',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Сервер архивации Email не может установить соединение с Вашей Sugar. Попробуйте еще раз или <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">свяжитесь со службой поддержки </a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Включить архивацию Email',
    'LBL_SNIP_BUTTON_DISABLE' => 'Выключить архивацию Email',
    'LBL_SNIP_BUTTON_RETRY' => 'Еще одна попытка соединения',
    'LBL_SNIP_ERROR_DISABLING' => 'Во время попытки соединения с сервером архивации Email произошла ошибка, и сервис не был отключен.',
    'LBL_SNIP_ERROR_ENABLING' => 'Во время попытки соединения с сервером архивации Email произошла ошибка, и сервис не был отключен.',
    'LBL_CONTACT_SUPPORT' => 'Попробуйте еще раз или обратитесь в поддержку SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Пожалуйста, свяжитесь со службой поддержки SugarCRM.',
    'ERROR_BAD_RESULT' => 'Некорректная ошибка сервиса',
	'ERROR_NO_CURL' => 'Необходимы расширения cURL, но они не были активированы',
	'ERROR_REQUEST_FAILED' => 'Нет соединения с сервером',

    'LBL_CANCEL_BUTTON_TITLE' => 'Отмена',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Это статус сервиса архивации Email в вашей системе. Этот статус показывает, было ли успешным соединение между сервером архивации Email и вашей системой Sugar.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Это email-адрес Email архивации, на который должна быть осуществлена отправка для импорта постовых сообщений в Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Это URL сервера архивации Email. Все запросы, такие как активация или отключение сервиса архивации Email, будут проходить через этот URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Это URL веб-сервисов вашей системы Sugar. Сервер архивации Email будет осуществлять подключение к вашему серверу через этот URL.',
);
