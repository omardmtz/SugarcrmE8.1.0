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
'LBL_OOTB_WORKFLOW'		=> 'Pokreni zadatke radnog toka',
'LBL_OOTB_REPORTS'		=> 'Pokreni generisanje izveštaja o planiranim zadacima',
'LBL_OOTB_IE'			=> 'Proveri dolazno poštansko sanduče',
'LBL_OOTB_BOUNCE'		=> 'Pokreni noćno procesiranje vraćenih email poruka iz kampanja',
'LBL_OOTB_CAMPAIGN'		=> 'Pokreni noćne masovne Email kampanje',
'LBL_OOTB_PRUNE'		=> 'Smanji bazu prvog dana u mesecu',
'LBL_OOTB_TRACKER'		=> 'Smanji tabele sistema za praćenje',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Skrati listu starih zapisa',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Ukloniti privremene fajlove',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Ukloniti fajlove alata za dijagnozu',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Ukloniti privremene PDF fajlove',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Ažuriraj tabelu tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Pokreni obaveštenja podsetnika za e-poštu',
'LBL_OOTB_CLEANUP_QUEUE' => 'Red poslova čišćenja',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Kreiraj Buduće Vremenske Periode',
'LBL_OOTB_HEARTBEAT' => 'Sugar-ovi otkucaji srca',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Ažuriraj KBsadržaj članaka.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Objavi odobrene artikle i istekle KB artikle.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Planirani zadaci u Advanced Workflow-u',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Ponovna izgradnja denormalizovanih bezbednosnih podataka o timovima',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Planeri:',
'LBL_LIST_NAME' => 'Planer:',
'LBL_LIST_RANGE' => 'Opseg:',
'LBL_LIST_REMOVE' => 'Ukloni:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Lista planova:',
'LBL_LIST_EXECUTE_TIME' => 'Počeće u:',
// human readable:
'LBL_SUN'		=> 'Nedelja',
'LBL_MON'		=> 'Ponedeljak',
'LBL_TUE'		=> 'Utorak',
'LBL_WED'		=> 'Sreda',
'LBL_THU'		=> 'Četvrtak',
'LBL_FRI'		=> 'Petak',
'LBL_SAT'		=> 'Subota',
'LBL_ALL'		=> 'Svaki dan',
'LBL_EVERY_DAY'	=> 'Svaki dan',
'LBL_AT_THE'	=> 'U',
'LBL_EVERY'		=> 'Svaki',
'LBL_FROM'		=> 'Od',
'LBL_ON_THE'	=> 'Na',
'LBL_RANGE'		=> 'do',
'LBL_AT' 		=> 'na',
'LBL_IN'		=> 'u',
'LBL_AND'		=> 'i',
'LBL_MINUTES'	=> 'minuta',
'LBL_HOUR'		=> 'sati',
'LBL_HOUR_SING'	=> 'sat',
'LBL_MONTH'		=> 'mesec',
'LBL_OFTEN'		=> 'Koliko god je moguće često.',
'LBL_MIN_MARK'	=> 'oznaka minute',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'sati',
'LBL_DAY_OF_MONTH' => 'datum',
'LBL_MONTHS' => 'mesec',
'LBL_DAY_OF_WEEK' => 'dan',
'LBL_CRONTAB_EXAMPLES' => 'Iznad koristi standardnu crontab notaciju.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Specifikacije cron-a rade u zavisnosti od vremenske zone servera (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Molim navedite vreme izvršenja shodno tome.',
// Labels
'LBL_ALWAYS' => 'Uvek',
'LBL_CATCH_UP' => 'Izvrši ako je propušteno',
'LBL_CATCH_UP_WARNING' => 'Isključite ako je za izvršavanje ovoga potrebno više od nekoliko trenutaka.',
'LBL_DATE_TIME_END' => 'Datum i vreme završetka',
'LBL_DATE_TIME_START' => 'Datum i vreme početka',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Zadatak',
'LBL_JOB_URL' => 'URL posla',
'LBL_LAST_RUN' => 'Poslednje uspešno izvršavanje',
'LBL_MODULE_NAME' => 'Suger planer',
'LBL_MODULE_NAME_SINGULAR' => 'Suger planer',
'LBL_MODULE_TITLE' => 'Planeri',
'LBL_NAME' => 'Naziv zadatka',
'LBL_NEVER' => 'Nikada',
'LBL_NEW_FORM_TITLE' => 'Novo planiranje',
'LBL_PERENNIAL' => 'neprekidan',
'LBL_SEARCH_FORM_TITLE' => 'Pretraga planera',
'LBL_SCHEDULER' => 'Planer:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktivan od',
'LBL_TIME_TO' => 'Aktivan do',
'LBL_WARN_CURL_TITLE' => 'cURL upozorenje:',
'LBL_WARN_CURL' => 'Upozorenje:',
'LBL_WARN_NO_CURL' => 'Ovaj sistem nema cURL biblioteke omogućene/kompajlirane u PHP modulu  (--with-curl=/path/to/curl_library). Molim, obratite se vašem administratoru za rešenje ovog problema. Bez cURL funkcionalnosti, Planer ne može nizati svoje poslove.',
'LBL_BASIC_OPTIONS' => 'Osnovna podešavanja',
'LBL_ADV_OPTIONS'		=> 'Napredne opcije',
'LBL_TOGGLE_ADV' => 'Prikaži Napredne Opcije',
'LBL_TOGGLE_BASIC' => 'Prikaži osnovne opcije',
// Links
'LNK_LIST_SCHEDULER' => 'Planeri',
'LNK_NEW_SCHEDULER' => 'Kreiraj Planera',
'LNK_LIST_SCHEDULED' => 'Planirani zadaci',
// Messages
'SOCK_GREETING' => "Ovo je interfejs za SugarCRM Servis Planera. [ Dostupne daemon komande: start|restart|shutdown|status ] Da odustanete, unesite &#39;quit&#39;. Da ugasite servis &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da bi obrisali zadatak.',
'ERR_CRON_SYNTAX' => 'Neispravna Cron sintaksa',
'NTC_DELETE_CONFIRMATION' => 'Da li ste sigurni da želite da obrišete ovaj zapis?',
'NTC_STATUS' => 'Podesi status na Neaktivno da bi se ovaj zadatak uklonio iz padajuće liste Planera',
'NTC_LIST_ORDER' => 'Postavi redosled po kome će se ovaj zadatak prikazati u padajućoj listi Planera',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Da bi podesli Windows Planer',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Da bi podesili Crontab',
'LBL_CRON_LINUX_DESC' => 'Napomena: Za aktiviranje Sugar Planera, dodajte sledeću liniju u vaš crontab fajl:',
'LBL_CRON_WINDOWS_DESC' => 'Napomena: Za aktiviranje Sugar planera, kreirajte komandni fajl koji se aktivira koristeći Windows Scheduled Tasks. Komandni fajl bi trebao da uključi sledeće komande:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Log zadatka',
'LBL_EXECUTE_TIME'			=> 'Vreme izvršavanja',

//jobstrings
'LBL_REFRESHJOBS' => 'Osveži zadatke',
'LBL_POLLMONITOREDINBOXES' => 'Proveri dolazne Email naloge',
'LBL_PERFORMFULLFTSINDEX' => 'Sistem indeksiranja za punu tekstualnu pretragu',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Ukloniti privremene PDF fajlove',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Objavi odobrene artikle i istekle KB artikle.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Planer redosleda Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Ukloniti fajlove alata za dijagnozu',
'LBL_SUGARJOBREMOVETMPFILES' => 'Ukloniti privremene fajlove',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Ponovna izgradnja denormalizovanih bezbednosnih podataka o timovima',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Pokreni noćne masovne Email kampanje',
'LBL_ASYNCMASSUPDATE' => 'Pokreni asinhrono masovno ažuriranje',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Pokreni noćno procesiranje odbijenih email-ova kampanje',
'LBL_PRUNEDATABASE' => 'Smanji bazu prvog dana u mesecu',
'LBL_TRIMTRACKER' => 'Smanji tabele sistema za praćenje',
'LBL_PROCESSWORKFLOW' => 'Pokreni zadatke radnog toka',
'LBL_PROCESSQUEUE' => 'Pokreni generisanje izveštaja o planiranim zadacima',
'LBL_UPDATETRACKERSESSIONS' => 'Ažuriraj tabele sesija sistema za praćenje',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Kreiraj Buduće Vremenske Periode',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Pokreni slanje podsetnika za e-poštu',
'LBL_CLEANJOBQUEUE' => 'Red poslova čišćenja',
'LBL_CLEANOLDRECORDLISTS' => 'Očisti listu starih zapisa',
'LBL_PMSEENGINECRON' => 'Planer Advanced Scheduler',
);

