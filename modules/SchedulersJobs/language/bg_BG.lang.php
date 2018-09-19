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

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Опашка от заявки',
    'LBL_MODULE_NAME_SINGULAR' => 'Опашка от заявки',
    'LBL_MODULE_TITLE' => 'Опашка от заявки: Начало',
    'LBL_MODULE_ID' => 'Опашка от заявки',
    'LBL_TARGET_ACTION' => 'Действие',
    'LBL_FALLIBLE' => 'С вероятност за грешка',
    'LBL_RERUN' => 'Повторно стартиране',
    'LBL_INTERFACE' => 'Интерфейс',
    'LINK_SCHEDULERSJOBS_LIST' => 'Разгледай опашката от заявки',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Конфигурация',
    'LBL_CONFIG_PAGE' => 'Настройки на опашките от заявки',
    'LBL_JOB_CANCEL_BUTTON' => 'Отмени',
    'LBL_JOB_PAUSE_BUTTON' => 'Пауза',
    'LBL_JOB_RESUME_BUTTON' => 'Поднови',
    'LBL_JOB_RERUN_BUTTON' => 'Върни на опашката',
    'LBL_LIST_NAME' => 'Име',
    'LBL_LIST_ASSIGNED_USER' => 'Поискано от',
    'LBL_LIST_STATUS' => 'Статус',
    'LBL_LIST_RESOLUTION' => 'Решение',
    'LBL_NAME' => 'Име на задачата',
    'LBL_EXECUTE_TIME' => 'Изпълнена на',
    'LBL_SCHEDULER_ID' => 'Автоматизирани задачи',
    'LBL_STATUS' => 'Статус на задачата',
    'LBL_RESOLUTION' => 'Резултат',
    'LBL_MESSAGE' => 'Съобщения',
    'LBL_DATA' => 'Данни за задачата',
    'LBL_REQUEUE' => 'Повторно изпълнение при възникване на грешка',
    'LBL_RETRY_COUNT' => 'Брой повторни изпълнения',
    'LBL_FAIL_COUNT' => 'Грешки',
    'LBL_INTERVAL' => 'Интервал от време преди повторно изпълнение',
    'LBL_CLIENT' => 'Притежаващ клиент',
    'LBL_PERCENT' => 'Процент на изпълнение',
    'LBL_JOB_GROUP' => 'Група на задачата',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Чакащо решение',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Решението е частично',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Решението е готово',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Неуспешно решение',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Анулирано решение',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Решението е в процес на обработка',
    // Errors
    'ERR_CALL' => "Не може да бъде извикана функцията: %s",
    'ERR_CURL' => "Не е достъшноPHP разширението CURL - не могат да се изпълняват URL задачи",
    'ERR_FAILED' => "Грешка при изпълнението. Моля проверете логовете на PHP и файла sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s в %s на линия %d",
    'ERR_NOUSER' => "Не е посочен идентификатор на потребителя за задачата",
    'ERR_NOSUCHUSER' => "Не е намерен идентификаторът на потребителя %s",
    'ERR_JOBTYPE' => "Неизвестен тип на задача: %s",
    'ERR_TIMEOUT' => "Принудителен неуспех при изчакване",
    'ERR_JOB_FAILED_VERBOSE' => 'Задачата %1$s (%2$s) не се изпълни коректно при стартиране на скрипта за автоматично изпълнение.',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Дефиницията на модул (Bean) с идентификатор: %s не се зарежда',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Не може да намери маршрут %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Разширението за тази опашка не е инсталирано',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Някои от полетата са празни',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Настройки на опашките от заявки',
    'LBL_CONFIG_MAIN_SECTION' => 'Основна конфигурация',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman конфигурация',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP конфигурация',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs конфигурация',
    'LBL_CONFIG_SERVERS_TITLE' => 'Помощ при конфигурирането на опашката от заявки',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Основен раздел за конфигурация.</b></p> 
<ul>
   <li>Runner: 
   <ul>
   <li><i>Стандартен</i> - използвайте само един процес за работници.</li>
   <li><i>Паралелен</i> - използвайте няколко процеса за работници.</li>     
   </ul>
   </li> 
   <li>Адаптер: 
   <ul>
   <li><i>Опашка по подразбиране</i> - Тази опция ще използва само базата данни на Sugar, без опашката за съобщения.</li>
   <li><i>Amazon SQS</i> - услугата на Amazon опашка с кратки въпроси представлява услуга по изпращане на разпределени съобщения от опашка, въведена от Amazon.com.     
   Тя поддържа програмно изпращане на съобщения чрез приложения за уеб услуги, като начин за общуване в интернет.</li>     
   <li><i>RabbitMQ</i> - е софтуер с отворен код за брокер на съобщения (понякога се нарича мидълуер, ориентиран към съобщенията), който осъществява Разширения протокол за подреждане на съобщения на опашка (AMQP).</li>     
   <li><i>Gearman</i> - е рамка за приложения с отворен код, предназначена да разпределя съответните компютърни задачи на няколко компютъра, така че обемните задачи да могат да се изпълняват по-бързо.</li>
   <li><i>Незабавно</i> - като опашката по подразбиране, но изпълнява задачата веднага след добавянето.</li>
   </ul>     
   </li> 
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Помощ за конфигуриране на Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Раздел за конфигуриране на Amazon SQS.</b></p> 
<ul>
    <li>Идентификатор на ключа за достъп: <i>Въведете номера на идентификатора на вашия ключ за достъп за Amazon SQS</i></li> 
    <li>Таен ключ за достъп: <i>Въведете вашия таен ключ за достъп за Amazon SQS</i></li> 
    <li>Регион: <i>Въведете региона на сървъра на Amazon SQS</i></li> 
    <li>Име на опашката: <i>Въведете името на опашката на сървъра на Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Помощ за конфигуриране на AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Раздел за конфигурация на AMQP.</b></p> 
<ul>
    <li>URL адрес на сървъра: <i>Въведете URL на сървъра на опашката на вашето съобщение.</i></li>     
    <li>Вход: <i>Въведете данните си за вход за RabbitMQ</i></li>     
    <li>Парола: <i>Въведете паролата си за RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Помощ за конфигуриране на Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Раздел за конфигуриране на Gearman.</b></p> 
<ul>
    <li>URL адрес на сървъра: <i>Въведете URL адреса на сървъра на опашката на вашето съобщение.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Адаптер',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Адрес на сървъра',
    'LBL_LOGIN' => 'Login',
    'LBL_ACCESS_KEY' => 'Идентификатор на ключа за достъп',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Таен ключ за достъп',
    'LBL_QUEUE_NAME' => 'Име на адаптера',
);
