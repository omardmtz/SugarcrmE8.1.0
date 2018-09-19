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
'LBL_OOTB_WORKFLOW'		=> 'Procesné Úlohy pracovného toku',
'LBL_OOTB_REPORTS'		=> 'Spustite vytváranie správ Naplánovaných úloh',
'LBL_OOTB_IE'			=> 'Skontrolujte Prichádzajúce poštové schránky',
'LBL_OOTB_BOUNCE'		=> 'Spustiť Nightly proces Bounced kampane e-mailov',
'LBL_OOTB_CAMPAIGN'		=> 'Spustiť nočne hromadné emailové kampane',
'LBL_OOTB_PRUNE'		=> 'Prerezávať databázy na 1. mesiaca',
'LBL_OOTB_TRACKER'		=> 'Prerezávať Tracker tabuľky',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Zachovávajte zoznamy starého záznamu',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Remove temporary files',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Remove diagnostic tool files',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Remove temporary PDF files',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Aktualizácia tracker_sessions tabuľka',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Spustiť pripomienky prostredníctvom e-mailu',
'LBL_OOTB_CLEANUP_QUEUE' => 'Poradie čistej práce',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Vytvoriť budúce časové obdobie',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Update KBContent articles.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publish approved articles & Expire KB Articles.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Naplánovaná úloha nástroja Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Prerobiť denormalizované tímové bezpečnostné údaje',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Plánovače',
'LBL_LIST_NAME' => 'Plánovač',
'LBL_LIST_RANGE' => 'rozsah:',
'LBL_LIST_REMOVE' => 'odstrániť',
'LBL_LIST_STATUS' => 'Stav',
'LBL_LIST_TITLE' => 'Zoznam plánovača',
'LBL_LIST_EXECUTE_TIME' => 'Bude prebiehať v:',
// human readable:
'LBL_SUN'		=> 'Nedela',
'LBL_MON'		=> 'Pondelok',
'LBL_TUE'		=> 'Utorok',
'LBL_WED'		=> 'Streda',
'LBL_THU'		=> 'Štvrtok',
'LBL_FRI'		=> 'Piatok',
'LBL_SAT'		=> 'Sobota',
'LBL_ALL'		=> 'Každý deň',
'LBL_EVERY_DAY'	=> 'Každý deň',
'LBL_AT_THE'	=> 'na',
'LBL_EVERY'		=> 'každý',
'LBL_FROM'		=> 'Od:',
'LBL_ON_THE'	=> 'na',
'LBL_RANGE'		=> 'na',
'LBL_AT' 		=> 'na',
'LBL_IN'		=> 'v',
'LBL_AND'		=> 'a',
'LBL_MINUTES'	=> 'Minúty',
'LBL_HOUR'		=> 'hodiny',
'LBL_HOUR_SING'	=> 'hodina',
'LBL_MONTH'		=> 'Mesiac',
'LBL_OFTEN'		=> 'Tak často, ako je to možné.',
'LBL_MIN_MARK'	=> 'minúte',


// crontabs
'LBL_MINS' => 'minúta',
'LBL_HOURS' => 'Hodiny',
'LBL_DAY_OF_MONTH' => 'dátum',
'LBL_MONTHS' => 'Mesiace',
'LBL_DAY_OF_WEEK' => 'deň',
'LBL_CRONTAB_EXAMPLES' => 'Vyššie používa štandardný zápis crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron špecifikácia bude bežať na základe servera v časovom pásme (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Prosím, zadajte prevedenie plánovača čas spôsobom.',
// Labels
'LBL_ALWAYS' => 'Vždy',
'LBL_CATCH_UP' => 'Prevedenie Ak chýba',
'LBL_CATCH_UP_WARNING' => 'Zrušte začiarknutie, ak táto práca môže trvať dlhšie ako okamih spustiť.',
'LBL_DATE_TIME_END' => 'Dátum & čas koniec',
'LBL_DATE_TIME_START' => 'Dátum & čas začiatok',
'LBL_INTERVAL' => 'interval',
'LBL_JOB' => 'práca',
'LBL_JOB_URL' => 'URL práca',
'LBL_LAST_RUN' => 'Posledné Úspešné Riešenie',
'LBL_MODULE_NAME' => 'Sugar plány',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar plánovač',
'LBL_MODULE_TITLE' => 'plány',
'LBL_NAME' => 'Názov práce',
'LBL_NEVER' => 'nikdy',
'LBL_NEW_FORM_TITLE' => 'nový plán',
'LBL_PERENNIAL' => 'trvalý',
'LBL_SEARCH_FORM_TITLE' => 'Hľadaj plán',
'LBL_SCHEDULER' => 'Plán',
'LBL_STATUS' => 'Stav',
'LBL_TIME_FROM' => 'aktívne Z',
'LBL_TIME_TO' => 'Ak chcete aktívny',
'LBL_WARN_CURL_TITLE' => 'cURL Upozornenie:',
'LBL_WARN_CURL' => 'Upozornenie:',
'LBL_WARN_NO_CURL' => 'tento systém nemá Curl knižnice povolený / skompilovaný do modulu PHP (- with-curl = / cesta / k / curl_library). Prosím, obráťte sa na správcu, aby tento problém vyriešiť. Bez CURL funkcie, môže Plánovač nie je navliecť svojej práci.',
'LBL_BASIC_OPTIONS' => 'zakladné nastavenie',
'LBL_ADV_OPTIONS'		=> 'Rozšírené možnosti',
'LBL_TOGGLE_ADV' => 'zobraziť rozšírene možnosti',
'LBL_TOGGLE_BASIC' => 'zobrazit základné možnosti',
// Links
'LNK_LIST_SCHEDULER' => 'Plány',
'LNK_NEW_SCHEDULER' => 'vytvoriť plány',
'LNK_LIST_SCHEDULED' => 'vypracovať plán',
// Messages
'SOCK_GREETING' => "Jedná sa o rozhranie pre plánovača SugarCRM služby. [Dostupné démon príkazy: štart | restart | shutdown | status] Ak chcete ukončiť, zadajte \"ukončite\". K vypnutiu služieb \"shutdown\".",
'ERR_DELETE_RECORD' => 'K odstráneniu verzie musíte zadať číslo záznamu.',
'ERR_CRON_SYNTAX' => 'Neplatný syntaxe Cron',
'NTC_DELETE_CONFIRMATION' => 'Ste si istý, že chcete vymazať tento záznam?',
'NTC_STATUS' => 'K odstráneniu tejto verzie zo zoznamu výberových polí verzie nastavte stav na neaktívny.',
'NTC_LIST_ORDER' => 'Nastavte poradie, v ktorom sa táto verzia objaví vo výberových poliach.',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Ak chcete Nastavenie systému Windows Plánovač',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Ak chcete Nastavenie crontab',
'LBL_CRON_LINUX_DESC' => 'Poznámka: Aby bolo možné spustiť plánovača, pridajte nasledujúci riadok do súboru crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Poznámka: Aby bolo možné spustiť plánovača, vytvorte dávkový súbor spustiť pomocou systému Windows Naplánované úlohy. Dávkový súbor by mal obsahovať nasledujúce príkazy:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'procovný log',
'LBL_EXECUTE_TIME'			=> 'Čas spustenia',

//jobstrings
'LBL_REFRESHJOBS' => 'obnovenie prác',
'LBL_POLLMONITOREDINBOXES' => 'Skontrolujte Prichádzajúce mailové kontá',
'LBL_PERFORMFULLFTSINDEX' => 'vyhľadať plný text index systém',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Odstrániť dočasné súbory PDF',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Zverejniť schválené články a ukončiť platnosť článkov v báze znalostí.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Plánovač frontu Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Odstrániť súbory diagnostického nástroja',
'LBL_SUGARJOBREMOVETMPFILES' => 'Odstrániť dočasné súbory',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Prerobiť denormalizované tímové bezpečnostné údaje',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Spustiť nočne hromadné emailové kampane',
'LBL_ASYNCMASSUPDATE' => 'Vykonajte asynchrónnu masívne aktualizáciu',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Spustiť Nightly proces Bounced kampane e-mailov',
'LBL_PRUNEDATABASE' => 'Prerezávať databázy na 1. mesiaca',
'LBL_TRIMTRACKER' => 'Prerezávať Tracker tabuľky',
'LBL_PROCESSWORKFLOW' => 'Procesné Úlohy pracovného toku',
'LBL_PROCESSQUEUE' => 'Spustite vytváranie správ Naplánovaných úloh',
'LBL_UPDATETRACKERSESSIONS' => 'Aktualizácia Tracker relácie tabuľky',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Vytvoriť budúce časové obdobie',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Spustiť odoslanie pripomienok prostredníctvom e-mailu',
'LBL_CLEANJOBQUEUE' => 'vyčíslenie pracovnej kvoty',
'LBL_CLEANOLDRECORDLISTS' => 'Vyčistenie zoznamov starého záznamu',
'LBL_PMSEENGINECRON' => 'Plánovač Advanced Workflow',
);

