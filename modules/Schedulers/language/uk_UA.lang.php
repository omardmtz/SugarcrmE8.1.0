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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Оброблення завдань бізнес-процесу',
'LBL_OOTB_REPORTS'		=> 'Виконувати заплановані завдання створення звітів',
'LBL_OOTB_IE'			=> 'Перевіряти вхідні листи',
'LBL_OOTB_BOUNCE'		=> 'Запустити вночі перевірку поштових скриньок для листів, що повертаються',
'LBL_OOTB_CAMPAIGN'		=> 'Запустити вночі масову розсилку листів',
'LBL_OOTB_PRUNE'		=> 'Стискати базу даних першого числа кожного місяця',
'LBL_OOTB_TRACKER'		=> 'Очищати історію останніх переглядів',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Видалити списки зі старими записами',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Видалити тимчасові файли',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Видалити файли засобів діагностики',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Видалити тимчасові PDF файли',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Оновити таблицю tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Запуск сповіщень електронною поштою',
'LBL_OOTB_CLEANUP_QUEUE' => 'Очистити список завдань',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Створити майбутні часові проміжки',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Update KBContent articles.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Опублікуйте затверджені статті та статті бази знань, термін дії яких скоро вичерпається.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Заплановане завдання Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Відновити денормалізовані дані безпеки команди',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Інтервал:',
'LBL_LIST_LIST_ORDER' => 'Планувальники завдань:',
'LBL_LIST_NAME' => 'Планувальник завдань:',
'LBL_LIST_RANGE' => 'Порядок:',
'LBL_LIST_REMOVE' => 'Видалити:',
'LBL_LIST_STATUS' => 'Статус:',
'LBL_LIST_TITLE' => 'Розклад:',
'LBL_LIST_EXECUTE_TIME' => 'Буде запущено в:',
// human readable:
'LBL_SUN'		=> 'Неділя',
'LBL_MON'		=> 'Понеділок',
'LBL_TUE'		=> 'Вівторок',
'LBL_WED'		=> 'Середа',
'LBL_THU'		=> 'Четвер',
'LBL_FRI'		=> 'П&#39;ятниця',
'LBL_SAT'		=> 'Субота',
'LBL_ALL'		=> 'Кожного дня',
'LBL_EVERY_DAY'	=> 'Буде запущено в:',
'LBL_AT_THE'	=> 'О',
'LBL_EVERY'		=> 'Кожен',
'LBL_FROM'		=> 'З',
'LBL_ON_THE'	=> 'в',
'LBL_RANGE'		=> 'до',
'LBL_AT' 		=> 'в',
'LBL_IN'		=> 'в',
'LBL_AND'		=> 'і',
'LBL_MINUTES'	=> 'Хвилини/Хвилин',
'LBL_HOUR'		=> 'години',
'LBL_HOUR_SING'	=> 'година',
'LBL_MONTH'		=> 'Місяць',
'LBL_OFTEN'		=> 'Якомога частіше.',
'LBL_MIN_MARK'	=> 'хвилина',


// crontabs
'LBL_MINS' => 'хвилина/хвилин',
'LBL_HOURS' => 'години/годин',
'LBL_DAY_OF_MONTH' => 'дата',
'LBL_MONTHS' => 'місяців/місяці',
'LBL_DAY_OF_WEEK' => 'день',
'LBL_CRONTAB_EXAMPLES' => 'Значення представлені в стандартній crontab-нотації',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Команди планувальника cron запускаються у відповідності з налаштуваннями часового поясу сервера (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Враховуйте це при використанні планувальника.',
// Labels
'LBL_ALWAYS' => 'Завжди',
'LBL_CATCH_UP' => 'Виконати, якщо пропущено',
'LBL_CATCH_UP_WARNING' => 'Зняти мітку, якщо запус може зайняти більше хвилини',
'LBL_DATE_TIME_END' => 'Дата і час закінчення',
'LBL_DATE_TIME_START' => 'Дата і час початку',
'LBL_INTERVAL' => 'Інтервал',
'LBL_JOB' => 'Завдання',
'LBL_JOB_URL' => 'URL завдання',
'LBL_LAST_RUN' => 'Останній вдалий запуск',
'LBL_MODULE_NAME' => 'Планувальник Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Планувальник Sugar',
'LBL_MODULE_TITLE' => 'Планувальники',
'LBL_NAME' => 'Назва завдання',
'LBL_NEVER' => 'Ніколи',
'LBL_NEW_FORM_TITLE' => 'Новий розклад',
'LBL_PERENNIAL' => 'Безстроково',
'LBL_SEARCH_FORM_TITLE' => 'Пошук завдання',
'LBL_SCHEDULER' => 'Планувальник завдань:',
'LBL_STATUS' => 'Статус',
'LBL_TIME_FROM' => 'Активний з',
'LBL_TIME_TO' => 'Активний до',
'LBL_WARN_CURL_TITLE' => 'cURL попередження:',
'LBL_WARN_CURL' => 'Попередження:',
'LBL_WARN_NO_CURL' => 'Ця система не має cURL-бібліотеку, ввімкнену/ відкомпільовану в PHP-модулі (--with-curl = / path / to / curl_library). Будь ласка, зв&#39;яжіться з Вашим адміністратором, щоб вирішити це питання. Без cURL-функціональності планувальник завдань не може виконати необхідні дії.',
'LBL_BASIC_OPTIONS' => 'Основні налаштування',
'LBL_ADV_OPTIONS'		=> 'Додаткові параметри',
'LBL_TOGGLE_ADV' => 'Показати додаткові налаштування',
'LBL_TOGGLE_BASIC' => 'Показати основні налаштування',
// Links
'LNK_LIST_SCHEDULER' => 'Планувальники',
'LNK_NEW_SCHEDULER' => 'Створити планувальника',
'LNK_LIST_SCHEDULED' => 'Заплановані завдання',
// Messages
'SOCK_GREETING' => "Це інтерфейс сервісу планувальника завдань. [ Доступні команди: start | restart | shutdown | status] Для виходу наберіть &#39;quit&#39;. Для виключення сервісу: &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Ви повинні вказати номер запису перед видаленням розкладу.',
'ERR_CRON_SYNTAX' => 'Невірний Cron-синтаксис',
'NTC_DELETE_CONFIRMATION' => 'Ви дійсно бажаєте видалити цей запис?',
'NTC_STATUS' => 'Встановіть статус "Не активна" для видалення цього розкладу зі списку завдань Планувальника',
'NTC_LIST_ORDER' => 'Установка послідовності, в якій завдання з&#39;являться у списку',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Налаштування планувальника завдань Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Налаштування сrontab',
'LBL_CRON_LINUX_DESC' => 'Примітка: Для запуску планувальника завдань Sugar додайте цей рядок у файл crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Примітка: Для запуску планувальника завдань Sugar створіть пакетний файл і виконуйте його за допомогою планувальника завдань Windows. Пакетний файл повинен містити наступні команди:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Журнал завдань',
'LBL_EXECUTE_TIME'			=> 'Час виконання',

//jobstrings
'LBL_REFRESHJOBS' => 'Оновити завдання',
'LBL_POLLMONITOREDINBOXES' => 'Перевірити облікові записи вхідної пошти',
'LBL_PERFORMFULLFTSINDEX' => 'Повнотекстова Індекс-система пошуку',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Видалити тимчасові PDF-файли',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Публікація затверджених статей і завершення терміну дії статей бази знань.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Планувальник черги Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Видалити файли засобів діагностики',
'LBL_SUGARJOBREMOVETMPFILES' => 'Видалити тимчасові файли',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Відновити денормалізовані дані безпеки команди',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Запустити вночі масову розсилку листів',
'LBL_ASYNCMASSUPDATE' => 'Провести асинхронні масові оновлення',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Запустити вночі перевірку поштових скриньок для листів, що повертаються',
'LBL_PRUNEDATABASE' => 'Стискати базу даних першого числа кожного місяця',
'LBL_TRIMTRACKER' => 'Очищати історію останніх переглядів',
'LBL_PROCESSWORKFLOW' => 'Оброблення завдань бізнес-процесу',
'LBL_PROCESSQUEUE' => 'Виконувати заплановані завдання створення звітів',
'LBL_UPDATETRACKERSESSIONS' => 'Оновлювати таблиці сесій трекера',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Створити майбутні часові проміжки',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Запуск надсилання сповіщень електронною поштою',
'LBL_CLEANJOBQUEUE' => 'Очищення списку завдань',
'LBL_CLEANOLDRECORDLISTS' => 'Очистити списки зі старими записами',
'LBL_PMSEENGINECRON' => 'Планувальник Advanced Workflow',
);

