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
'LBL_OOTB_WORKFLOW'		=> 'Przetwarzaj zadania workflow',
'LBL_OOTB_REPORTS'		=> 'Wykonaj zaplanowane zadania generowania raportu',
'LBL_OOTB_IE'			=> 'Sprawdź skrzynki poczty przychodzącej',
'LBL_OOTB_BOUNCE'		=> 'Uruchom nocny proces odsyłania wiadomości e-mail',
'LBL_OOTB_CAMPAIGN'		=> 'Uruchom nocną kampanie masowych wiadomości e-mail',
'LBL_OOTB_PRUNE'		=> 'Oczyść bazę danych 1-go dnia miesiąca',
'LBL_OOTB_TRACKER'		=> 'Oczyść tabele śledzenia',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Oczyść stare listy rekordów',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Usuń pliki tymczasowe',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Usuń pliki diagnostyczne',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Usuń tymczasowe pliki PDF',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Aktualizuj tabelę tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Uruchom przypomnienia e-mail',
'LBL_OOTB_CLEANUP_QUEUE' => 'Wyczyść kolejkę zadań',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Utwórz przyszłe przedziały czasowe',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Aktualizuj artykuły KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publikuj zatwierdzone artykuły i zakończ ważność artykułów bazy wiedzy.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Zaplanowane zadanie Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Odbuduj zdenormalizowane dane bezpieczeństwa zespołu',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interwał:',
'LBL_LIST_LIST_ORDER' => 'Harmonogramy:',
'LBL_LIST_NAME' => 'Harmonogram:',
'LBL_LIST_RANGE' => 'Zakres:',
'LBL_LIST_REMOVE' => 'Usuń:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Lista harmonogramów:',
'LBL_LIST_EXECUTE_TIME' => 'Rozpocznie się o:',
// human readable:
'LBL_SUN'		=> 'Niedziela',
'LBL_MON'		=> 'Poniedziałek',
'LBL_TUE'		=> 'Wtorek',
'LBL_WED'		=> 'Środa',
'LBL_THU'		=> 'Czwartek',
'LBL_FRI'		=> 'Piątek',
'LBL_SAT'		=> 'Sobota',
'LBL_ALL'		=> 'Każdego dnia',
'LBL_EVERY_DAY'	=> 'Każdego dnia',
'LBL_AT_THE'	=> 'O',
'LBL_EVERY'		=> 'Co',
'LBL_FROM'		=> 'Od',
'LBL_ON_THE'	=> 'Co',
'LBL_RANGE'		=> 'do',
'LBL_AT' 		=> 'o',
'LBL_IN'		=> 'w',
'LBL_AND'		=> 'i',
'LBL_MINUTES'	=> 'minut',
'LBL_HOUR'		=> 'godzin',
'LBL_HOUR_SING'	=> 'godzinę',
'LBL_MONTH'		=> 'miesiąc',
'LBL_OFTEN'		=> 'Tak często, jak tylko możliwe.',
'LBL_MIN_MARK'	=> 'minut',


// crontabs
'LBL_MINS' => 'min.',
'LBL_HOURS' => 'godz.',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'miesiąc',
'LBL_DAY_OF_WEEK' => 'dzień',
'LBL_CRONTAB_EXAMPLES' => 'Powyżej użyto notacji crontaba.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Specyfikacja cron opiera się na strefie czasowej serwera (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Ustaw czas wywołania harmonogramu zadań zgodnie z tymi ustawieniami.',
// Labels
'LBL_ALWAYS' => 'Zawsze',
'LBL_CATCH_UP' => 'Wykonuj, jeśli pominięto',
'LBL_CATCH_UP_WARNING' => 'Odznacz, jeżeli to zadanie ma potrwać dłużej.',
'LBL_DATE_TIME_END' => 'Data i czas zakończenia',
'LBL_DATE_TIME_START' => 'Data i czas rozpoczęcia',
'LBL_INTERVAL' => 'Interwał',
'LBL_JOB' => 'Zadanie',
'LBL_JOB_URL' => 'URL zadania',
'LBL_LAST_RUN' => 'Ostatnie pomyślne wykonanie',
'LBL_MODULE_NAME' => 'Harmonogram Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Harmonogram Sugar',
'LBL_MODULE_TITLE' => 'Harmonogramy',
'LBL_NAME' => 'Nazwa zadania',
'LBL_NEVER' => 'Nigdy',
'LBL_NEW_FORM_TITLE' => 'Nowy harmonogram',
'LBL_PERENNIAL' => 'cyklicznie',
'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie harmonogramu',
'LBL_SCHEDULER' => 'Harmonogram:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktywny od',
'LBL_TIME_TO' => 'Aktywny do',
'LBL_WARN_CURL_TITLE' => 'Ostrzeżenie cURL:',
'LBL_WARN_CURL' => 'Ostrzeżenie:',
'LBL_WARN_NO_CURL' => 'Ten system nie zawiera bibliotek cURL włączonych lub wkompilowanych w moduł PHP (--with-curl=/path/to/curl_library).  Skontaktuj się z Administratorem, aby rozwiązać ten problem.  Bez cURL nie można wykonać harmonogramu.',
'LBL_BASIC_OPTIONS' => 'Podstawowe ustawienia',
'LBL_ADV_OPTIONS'		=> 'Opcje zaawansowane',
'LBL_TOGGLE_ADV' => 'Pokaż opcje zaawansowane',
'LBL_TOGGLE_BASIC' => 'Pokaż podstawowe ustawienia',
// Links
'LNK_LIST_SCHEDULER' => 'Harmonogramy',
'LNK_NEW_SCHEDULER' => 'Utwórz harmonogram',
'LNK_LIST_SCHEDULED' => 'Zaplanowane zadania',
// Messages
'SOCK_GREETING' => "\n To jest interfejs usług harmonogramów SugarCRM. \n[ Dostępne komendy demona: start|restart|shutdown|status ]\nAby wyjść, wpisz „quit”.  Aby wyłączyć usługę, wpisz „shutdown”.\n",
'ERR_DELETE_RECORD' => 'Musisz podać numer rekordu, aby usunąć harmonogram.',
'ERR_CRON_SYNTAX' => 'Niewłaściwa składnia Crona',
'NTC_DELETE_CONFIRMATION' => 'Czy na pewno chcesz usunąć ten rekord?',
'NTC_STATUS' => 'Ustaw status na Nieaktywny, aby usunąć ten harmonogram z listy rozwijalnej harmonogramów',
'NTC_LIST_ORDER' => 'Kolejność wykonywania tego Harmonogramu pojawi się na liście rozwijalnej',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Aby ustawić Harmonogram Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Aby ustawić Crontab',
'LBL_CRON_LINUX_DESC' => 'Uwaga: aby uruchomić harmonogramy Sugar, dodaj następujący wiersz do pliku crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'Uwaga: aby uruchomić harmonogramy Sugar, utwórz plik wsadowy do uruchomienia przy użyciu zadań zaplanowanych Windows. Plik wsadowy powinien zawierać następujące polecenia: ',
'LBL_NO_PHP_CLI' => 'Jeśli host nie udostępnia binarnego PHP, można użyć wget lub curl do uruchamiania zadań.<br>W przypadku wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>W przypadku curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Log zadań',
'LBL_EXECUTE_TIME'			=> 'Czas wykonania',

//jobstrings
'LBL_REFRESHJOBS' => 'Odśwież zadanie',
'LBL_POLLMONITOREDINBOXES' => 'Sprawdź konta poczty przychodzącej',
'LBL_PERFORMFULLFTSINDEX' => 'System indeksowania wyszukiwania pełnotekstowego',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Usuń tymczasowe pliki PDF',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publikuj zatwierdzone artykuły i zakończ ważność artykułów bazy wiedzy.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Narzędzie harmonogramowania kolejki wyszukiwania Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Usuń pliki diagnostyczne',
'LBL_SUGARJOBREMOVETMPFILES' => 'Usuń pliki tymczasowe',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Odbuduj zdenormalizowane dane bezpieczeństwa zespołu',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Uruchom nocną kampanie masowych wiadomości e-mail',
'LBL_ASYNCMASSUPDATE' => 'Przeprowadź asynchroniczne masowe aktualizacje',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Uruchom nocny proces odsyłania wiadomości e-mail',
'LBL_PRUNEDATABASE' => 'Oczyść bazę danych 1-go dnia miesiąca',
'LBL_TRIMTRACKER' => 'Oczyść tabele śledzenia',
'LBL_PROCESSWORKFLOW' => 'Przetwarzaj zadania workflow',
'LBL_PROCESSQUEUE' => 'Wykonaj zaplanowane zadania generowania raportu',
'LBL_UPDATETRACKERSESSIONS' => 'Zaktualizuj tabele śledzenia sesji',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Utwórz przyszłe przedziały czasowe',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Uruchom wysyłanie przypomnienia pocztą e-mail',
'LBL_CLEANJOBQUEUE' => 'Wyczyść kolejkę zadania',
'LBL_CLEANOLDRECORDLISTS' => 'Wyczyść stare listy rekordów',
'LBL_PMSEENGINECRON' => 'Program planujący Advanced Workflow',
);

