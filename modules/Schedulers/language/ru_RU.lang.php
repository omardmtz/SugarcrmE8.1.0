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
'LBL_OOTB_WORKFLOW'		=> 'Обработать задачи Рабочего процесса',
'LBL_OOTB_REPORTS'		=> 'Выполнять запланированные задачи создания отчетов',
'LBL_OOTB_IE'			=> 'Проверять входящие письма',
'LBL_OOTB_BOUNCE'		=> 'Запускать ночью проверку почтовых ящиков для возвращаемых писем',
'LBL_OOTB_CAMPAIGN'		=> 'Запускать ночью массовую рассылку писем',
'LBL_OOTB_PRUNE'		=> 'Сжимать базу данных первого числа каждого месяца',
'LBL_OOTB_TRACKER'		=> 'Очищать историю последних просмотров первого числа каждого месяца',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Удалить списки со старыми записями',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Удалить временные файлы',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Удалить файлы средств диагностики',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Удалить временные PDF файлы',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Обновить таблицу tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Запуск оповещений по электронной почте',
'LBL_OOTB_CLEANUP_QUEUE' => 'Очистить список задач',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Создать будущие временные промежутки',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Update KBContent articles.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Публикация одобренных статей и статей базы знаний с истекающим сроком действия.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Запланированное задание Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Восстановить денормализованные данные безопасности команды',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Интервал:',
'LBL_LIST_LIST_ORDER' => 'Планировщик заданий:',
'LBL_LIST_NAME' => 'Планировщик заданий:',
'LBL_LIST_RANGE' => 'Порядок:',
'LBL_LIST_REMOVE' => 'Удалить:',
'LBL_LIST_STATUS' => 'Статус:',
'LBL_LIST_TITLE' => 'Список заданий',
'LBL_LIST_EXECUTE_TIME' => 'Будет запущено в:',
// human readable:
'LBL_SUN'		=> 'Воскресенье',
'LBL_MON'		=> 'Понедельник',
'LBL_TUE'		=> 'Вторник',
'LBL_WED'		=> 'Среда',
'LBL_THU'		=> 'Четверг',
'LBL_FRI'		=> 'Пятница',
'LBL_SAT'		=> 'Суббота',
'LBL_ALL'		=> 'Каждый день',
'LBL_EVERY_DAY'	=> 'Каждый день',
'LBL_AT_THE'	=> 'В',
'LBL_EVERY'		=> '&#39;Каждые',
'LBL_FROM'		=> 'с',
'LBL_ON_THE'	=> 'Раз в',
'LBL_RANGE'		=> 'до',
'LBL_AT' 		=> 'в',
'LBL_IN'		=> 'в',
'LBL_AND'		=> 'и',
'LBL_MINUTES'	=> 'минут',
'LBL_HOUR'		=> 'часов',
'LBL_HOUR_SING'	=> 'час',
'LBL_MONTH'		=> 'месяц',
'LBL_OFTEN'		=> 'Постоянно',
'LBL_MIN_MARK'	=> 'минуту',


// crontabs
'LBL_MINS' => 'мин',
'LBL_HOURS' => 'час',
'LBL_DAY_OF_MONTH' => 'дата',
'LBL_MONTHS' => 'мес',
'LBL_DAY_OF_WEEK' => 'день',
'LBL_CRONTAB_EXAMPLES' => 'Значения представлены в стандартной crontab-нотации',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Команды планировщика cron запускаются в соответствии с настройками часового пояса сервера (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Учитывайте это при использовании планировщика.',
// Labels
'LBL_ALWAYS' => 'Всегда',
'LBL_CATCH_UP' => 'Выполнить, если пропущена',
'LBL_CATCH_UP_WARNING' => 'Снять метку, если работу можно выполнить не только единожды.',
'LBL_DATE_TIME_END' => 'Дата и время окончания',
'LBL_DATE_TIME_START' => 'Дата и время начала',
'LBL_INTERVAL' => 'Интервал',
'LBL_JOB' => 'Действие:',
'LBL_JOB_URL' => 'URL задачи',
'LBL_LAST_RUN' => 'Последняя удачно запущенная',
'LBL_MODULE_NAME' => 'Задания Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Планировщик Sugar',
'LBL_MODULE_TITLE' => 'Планировщик заданий',
'LBL_NAME' => 'Задание',
'LBL_NEVER' => 'Никогда',
'LBL_NEW_FORM_TITLE' => 'Новое задание',
'LBL_PERENNIAL' => 'Бессрочно',
'LBL_SEARCH_FORM_TITLE' => 'Поиск задания',
'LBL_SCHEDULER' => 'задание:',
'LBL_STATUS' => 'Статус',
'LBL_TIME_FROM' => 'Выполнять действие с',
'LBL_TIME_TO' => 'Выполнять действие до',
'LBL_WARN_CURL_TITLE' => 'cURL предупреждение:',
'LBL_WARN_CURL' => 'Предупреждение:',
'LBL_WARN_NO_CURL' => 'Эта система не имеет cURL-библиотеку, доступную/откомпилированную в PHP-модуле (--with-curl=/path/to/curl_library).  Пожалуйста, свяжитесь с Вашим администратором, чтобы решить этот вопрос. Без cURL-функциональности планировщик заданий не может выполнить необходимые действия.',
'LBL_BASIC_OPTIONS' => 'Основные параметры',
'LBL_ADV_OPTIONS'		=> 'Расширенные опции',
'LBL_TOGGLE_ADV' => 'Показать дополнительные настройки',
'LBL_TOGGLE_BASIC' => 'Показать основные параметры',
// Links
'LNK_LIST_SCHEDULER' => 'Список заданий',
'LNK_NEW_SCHEDULER' => 'Новое задание',
'LNK_LIST_SCHEDULED' => 'Запланированные задания',
// Messages
'SOCK_GREETING' => "Это интерфейс сервиса планировщика заданий. [ Доступные команды: start|restart|shutdown|status ] Для выхода наберите &#39;quit&#39;. Для выключения сервиса: &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Вы должны указать номер записи перед удалением расписания.',
'ERR_CRON_SYNTAX' => 'Неверный Cron-синтаксис',
'NTC_DELETE_CONFIRMATION' => 'Вы действительно хотите удалить эту запись?',
'NTC_STATUS' => 'Установите статус "Не активна" для удаления этой задачи из списка заданий',
'NTC_LIST_ORDER' => 'Установка последовательности, в которой задания появятся в списке',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Настройка планировщика заданий Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Настройка сrontab',
'LBL_CRON_LINUX_DESC' => 'Примечание: Для запуска планировщика заданий Sugar добавьте эту строку в файл crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Примечание: Для запуска планировщика заданий Sugar создайте пакетный файл и выполняйте его при помощи планировщика заданий Windows. Пакетный файл должен содержать следующие команды:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Журнал заданий',
'LBL_EXECUTE_TIME'			=> 'Время выполнения',

//jobstrings
'LBL_REFRESHJOBS' => 'Обновить задания',
'LBL_POLLMONITOREDINBOXES' => 'Проверять почтовые ящики для входящей почты',
'LBL_PERFORMFULLFTSINDEX' => 'Полнотекстовая Индекс-система поиска',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Удалить временные PDF-файлы',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Публикация одобренных статей и завершение срока действия статей базы знаний.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Планировщик очереди Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Удалить файлы средств диагностики',
'LBL_SUGARJOBREMOVETMPFILES' => 'Удалить временные файлы',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Восстановить денормализованные данные безопасности команды',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Запускать ночью массовую рассылку писем',
'LBL_ASYNCMASSUPDATE' => 'Произвести асинхронные массовые обновления',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Запускать ночью проверку почтовых ящиков для возвращаемых писем',
'LBL_PRUNEDATABASE' => 'Сжимать базу данных первого числа каждого месяца',
'LBL_TRIMTRACKER' => 'Очищать таблицы трекера',
'LBL_PROCESSWORKFLOW' => 'Обработать задачи Рабочего процесса',
'LBL_PROCESSQUEUE' => 'Выполнять запланированные задачи создания отчетов',
'LBL_UPDATETRACKERSESSIONS' => 'Обновлять таблицы сессий трекера',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Создать будущие временные промежутки',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Запуск отправки оповещений по электронной почте',
'LBL_CLEANJOBQUEUE' => 'Очистка списка задач',
'LBL_CLEANOLDRECORDLISTS' => 'Очистить списки со старыми записями',
'LBL_PMSEENGINECRON' => 'Планировщик Advanced Workflow',
);

