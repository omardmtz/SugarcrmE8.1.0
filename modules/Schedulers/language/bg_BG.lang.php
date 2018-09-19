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
'LBL_OOTB_WORKFLOW'		=> 'Изпълнение на задачи в автоматизираните процеси',
'LBL_OOTB_REPORTS'		=> 'Изпълняване на задачите за автоматично генериране на справки',
'LBL_OOTB_IE'			=> 'Проверка на пощенски кутии за входяща поща',
'LBL_OOTB_BOUNCE'		=> 'Обработка на върнати електронни писма от кампании',
'LBL_OOTB_CAMPAIGN'		=> 'Изпращане на електронни писма от кампании',
'LBL_OOTB_PRUNE'		=> 'Изчистване на базата на 1-о число всеки месец',
'LBL_OOTB_TRACKER'		=> 'Изчистване на таблицата с потребителска активност на 1-во число всеки месец',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Изтриване на стари списъци със записи',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Премахване на временните файлове',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Премахване на файловете за диагностика',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Премахване на временните PDF файлове',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Актуализирай таблицата tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Активирай известията за напомняне за получаване на имейли',
'LBL_OOTB_CLEANUP_QUEUE' => 'Изчистване на опашките от заявки',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Създаване на бъдещи времеви периоди',
'LBL_OOTB_HEARTBEAT' => 'Статистика на Sugar',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Актуализирай статиите от KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Публикувай одобрени материали & изтичане на срока на KB материали.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Насрочена задача в Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Регенерирай денормализираните защитени данни за екипа',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Интервал на изпълнение:',
'LBL_LIST_LIST_ORDER' => 'Планирани задачи:',
'LBL_LIST_NAME' => 'Задача:',
'LBL_LIST_RANGE' => 'Времеви диапазон:',
'LBL_LIST_REMOVE' => 'Премахни:',
'LBL_LIST_STATUS' => 'Статус:',
'LBL_LIST_TITLE' => 'Списък с автоматизирани задачи:',
'LBL_LIST_EXECUTE_TIME' => 'Ще бъде изпълнена в:',
// human readable:
'LBL_SUN'		=> 'Неделя',
'LBL_MON'		=> 'Понеделник',
'LBL_TUE'		=> 'Вторник',
'LBL_WED'		=> 'Сряда',
'LBL_THU'		=> 'Четвъртък',
'LBL_FRI'		=> 'Петък',
'LBL_SAT'		=> 'Събота',
'LBL_ALL'		=> 'Ежедневно',
'LBL_EVERY_DAY'	=> 'Ежедневно',
'LBL_AT_THE'	=> 'At the',
'LBL_EVERY'		=> 'Интервал на повторение ',
'LBL_FROM'		=> 'от',
'LBL_ON_THE'	=> 'На всеки',
'LBL_RANGE'		=> 'до',
'LBL_AT' 		=> 'в',
'LBL_IN'		=> 'в',
'LBL_AND'		=> 'и',
'LBL_MINUTES'	=> ' минути ',
'LBL_HOUR'		=> ' часове',
'LBL_HOUR_SING'	=> ' час',
'LBL_MONTH'		=> 'месец',
'LBL_OFTEN'		=> 'Възможной най-често.',
'LBL_MIN_MARK'	=> 'minute mark',


// crontabs
'LBL_MINS' => 'мин.',
'LBL_HOURS' => 'часове',
'LBL_DAY_OF_MONTH' => 'дата',
'LBL_MONTHS' => 'месец',
'LBL_DAY_OF_WEEK' => 'ден',
'LBL_CRONTAB_EXAMPLES' => 'Използва се стандартната за crontab нотация.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Програмата cron се изпълнява в съответствие с часовата зона на сървъра (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Моля посочете времето за изпълнение на автоматизираната задача, като се съобразите с текущата часова зона.',
// Labels
'LBL_ALWAYS' => 'Винаги',
'LBL_CATCH_UP' => 'Изпълни, ако е пропуснато',
'LBL_CATCH_UP_WARNING' => 'Uncheck if this Job may take more than a moment to run.',
'LBL_DATE_TIME_END' => 'Крайна дата и час',
'LBL_DATE_TIME_START' => 'Начална дата и час',
'LBL_INTERVAL' => 'Интервал',
'LBL_JOB' => 'Задача',
'LBL_JOB_URL' => 'URL на задачата',
'LBL_LAST_RUN' => 'Последно успешно изпълнение',
'LBL_MODULE_NAME' => 'Автоматизирани задачи',
'LBL_MODULE_NAME_SINGULAR' => 'Автоматизирани задачи',
'LBL_MODULE_TITLE' => 'Автоматизирани задачи',
'LBL_NAME' => 'Име на задачата',
'LBL_NEVER' => 'Никога',
'LBL_NEW_FORM_TITLE' => 'Нова дейност',
'LBL_PERENNIAL' => 'непрекъснато',
'LBL_SEARCH_FORM_TITLE' => 'Търсене на автоматизирани задачи',
'LBL_SCHEDULER' => 'График на дейностите:',
'LBL_STATUS' => 'Статус',
'LBL_TIME_FROM' => 'Активна от',
'LBL_TIME_TO' => 'Активна до',
'LBL_WARN_CURL_TITLE' => 'cURL предупреждение:',
'LBL_WARN_CURL' => 'Внимание:',
'LBL_WARN_NO_CURL' => 'cURL бибилиотеките не са компилирaни и разрешени в PHP дистрибуцията (--with-curl=/path/to/curl_library).  Моля, свържете се със системния администратор за разрешаване на проблема.  Без функционалнопстта предоставяна от библиотеките cURL, автоматизираните задачи няма да могат да се изпълняват.',
'LBL_BASIC_OPTIONS' => 'Базови настройки',
'LBL_ADV_OPTIONS'		=> 'Допълнителни настройки',
'LBL_TOGGLE_ADV' => 'Показване на допълнителни настройки',
'LBL_TOGGLE_BASIC' => 'Базови настройки',
// Links
'LNK_LIST_SCHEDULER' => 'Автоматизирани задачи',
'LNK_NEW_SCHEDULER' => 'Създаване на автоматизирана задача',
'LNK_LIST_SCHEDULED' => 'Запланирани дейности',
// Messages
'SOCK_GREETING' => "\nТова е интерфейса за Услугата за създаване на график на SugarCRM. \n[ Налични команди: старт|рестарт|затваряне|статус ]\nЗа да излезете, напишете 'излез'. За да затворите услугата напишете 'shutdown'.\n",
'ERR_DELETE_RECORD' => 'Трябва да определите номер на записа, за да изтриете тази запланирана дейност.',
'ERR_CRON_SYNTAX' => 'Невалиден синтаксис',
'NTC_DELETE_CONFIRMATION' => 'Сигурни ли сте, че желаете да изтриете този запис?',
'NTC_STATUS' => 'Установете статус НЕАКТИВЕН, за да премахнете тази задача от списъка с автоматизиранните задачи',
'NTC_LIST_ORDER' => 'Установете реда, в който да се подреждат автоматизираните задачи в списъка',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'За да конфигурирате Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'За да конфигурирате Crontab',
'LBL_CRON_LINUX_DESC' => 'Добавете следния ред към настройките за автоматично изпълнение на задачи:',
'LBL_CRON_WINDOWS_DESC' => 'Създаване на batch файл със следните команди:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Хронология на изпълнението',
'LBL_EXECUTE_TIME'			=> 'Изпълнена на',

//jobstrings
'LBL_REFRESHJOBS' => 'Опресни задачите',
'LBL_POLLMONITOREDINBOXES' => 'Проверка на пощенски кутии за входяща поща',
'LBL_PERFORMFULLFTSINDEX' => 'Индексиране на съдържанието за пълнотекстово търсене',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Премахване на временните PDF файлове',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Публикувайте одобрени статии и отбележете статии в базата знания като изтекли.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Планиране на опашка за Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Премахване на файловете на инструмента за диагностика',
'LBL_SUGARJOBREMOVETMPFILES' => 'Премахване на временните файлове',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Регенерирай денормализираните защитени данни за екипа',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Изпращане на електронни писма от кампании',
'LBL_ASYNCMASSUPDATE' => 'Изпъняване на асинхронни масови актуализации',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Обработка на върнати електронни писма от кампании',
'LBL_PRUNEDATABASE' => 'Изчистване на базата на 1-о число всеки месец',
'LBL_TRIMTRACKER' => 'Изчистване на потребителската история на 1-о число всеки месец',
'LBL_PROCESSWORKFLOW' => 'Изпълнение на задачи в автоматизираните процеси',
'LBL_PROCESSQUEUE' => 'Изпълняване на задачите за автоматично генериране на справки',
'LBL_UPDATETRACKERSESSIONS' => 'Актуализация на таблиците за проследяване на активностите в системата',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Създаване на бъдещи времеви периоди',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar статистика',
'LBL_SENDEMAILREMINDERS'=> 'Изпълни Изпращане на напомняния по имейла',
'LBL_CLEANJOBQUEUE' => 'Изчистване на опашките от заявки',
'LBL_CLEANOLDRECORDLISTS' => 'Изчистване на списъците със стари записи',
'LBL_PMSEENGINECRON' => 'Автоматизирани задачи на Advanced Workflow',
);

