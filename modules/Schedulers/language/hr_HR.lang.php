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
'LBL_OOTB_WORKFLOW'		=> 'Obrada zadataka iz tijeka rada',
'LBL_OOTB_REPORTS'		=> 'Pokreni generiranje izvješća o zakazanim zadacima',
'LBL_OOTB_IE'			=> 'Provjeri dolazne poštanske sandučiće',
'LBL_OOTB_BOUNCE'		=> 'Pokreni noćnu obradu vraćenih poruka e-pošte iz kampanje',
'LBL_OOTB_CAMPAIGN'		=> 'Pokreni noćno slanje masovne e-pošte kampanje',
'LBL_OOTB_PRUNE'		=> 'Pročisti bazu podataka prvog dana u mjesecu',
'LBL_OOTB_TRACKER'		=> 'Pročisti tablice pratitelja',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Pročisti popise starih zapisa',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Ukloni privremene datoteke',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Ukloni datoteke dijagnostičkog alata',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Ukloni privremene PDF datoteke',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Ažuriraj tablicu tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Pokreni obavijesti podsjetnika putem e-pošte',
'LBL_OOTB_CLEANUP_QUEUE' => 'Očisti red čekanja poslova',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Stvori buduća vremenska razdoblja',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Ažurirajte članke sadržaja baze znanja.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Objavite odobrene članke i istekle članke baze znanja.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Ponovna izgradnja denormaliziranih sigurnosnih podataka o timovima',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Planeri:',
'LBL_LIST_NAME' => 'Planer:',
'LBL_LIST_RANGE' => 'Raspon:',
'LBL_LIST_REMOVE' => 'Ukloni:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Popis obaveza:',
'LBL_LIST_EXECUTE_TIME' => 'Pokrenut će se:',
// human readable:
'LBL_SUN'		=> 'Nedjelja',
'LBL_MON'		=> 'Ponedjeljak',
'LBL_TUE'		=> 'Utorak',
'LBL_WED'		=> 'Srijeda',
'LBL_THU'		=> 'Četvrtak',
'LBL_FRI'		=> 'Petak',
'LBL_SAT'		=> 'Subota',
'LBL_ALL'		=> 'Svaki dan',
'LBL_EVERY_DAY'	=> 'Svaki dan ',
'LBL_AT_THE'	=> 'U ',
'LBL_EVERY'		=> 'Svakih ',
'LBL_FROM'		=> 'Od ',
'LBL_ON_THE'	=> 'Na ',
'LBL_RANGE'		=> ' do ',
'LBL_AT' 		=> ' u ',
'LBL_IN'		=> ' u ',
'LBL_AND'		=> ' i ',
'LBL_MINUTES'	=> ' min ',
'LBL_HOUR'		=> ' h',
'LBL_HOUR_SING'	=> ' sat',
'LBL_MONTH'		=> ' mjesec',
'LBL_OFTEN'		=> ' Što češće.',
'LBL_MIN_MARK'	=> ' oznaka za minutu',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'h',
'LBL_DAY_OF_MONTH' => 'datum',
'LBL_MONTHS' => 'mj.',
'LBL_DAY_OF_WEEK' => 'dan',
'LBL_CRONTAB_EXAMPLES' => 'Navedeno upotrebljava standardnu oznaku crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Specifikacije cron rade na temelju vremenske zone poslužitelja (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Odredite vrijeme izvršavanja planera u skladu s tim.',
// Labels
'LBL_ALWAYS' => 'Uvijek',
'LBL_CATCH_UP' => 'Izvrši ako je propušteno',
'LBL_CATCH_UP_WARNING' => 'Odznači ako ovaj posao treba više od trenutka za pokretanje.',
'LBL_DATE_TIME_END' => 'Datum i vrijeme završetka',
'LBL_DATE_TIME_START' => 'Datum i vrijeme početka',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Posao',
'LBL_JOB_URL' => 'URL adresa posla',
'LBL_LAST_RUN' => 'Posljednje uspješno pokretanje',
'LBL_MODULE_NAME' => 'Planer Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Planer Sugar',
'LBL_MODULE_TITLE' => 'Planeri',
'LBL_NAME' => 'Naziv posla',
'LBL_NEVER' => 'Nikada',
'LBL_NEW_FORM_TITLE' => 'Novi raspored',
'LBL_PERENNIAL' => 'neprestano',
'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje planera',
'LBL_SCHEDULER' => 'Planer:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktivno od',
'LBL_TIME_TO' => 'Aktivno do',
'LBL_WARN_CURL_TITLE' => 'Upozorenje cURL:',
'LBL_WARN_CURL' => 'Upozorenje:',
'LBL_WARN_NO_CURL' => 'Ovaj sustav nema biblioteke cURL omogućene/kompilirane u modul PHP (--with-curl=/path/to/curl_library). Obratite se svojem administratoru da biste riješili ovaj problem. Bez funkcionalnosti cURL-a planer ne može razvrstati svoje poslove.',
'LBL_BASIC_OPTIONS' => 'Osnovno postavljanje',
'LBL_ADV_OPTIONS'		=> 'Napredne mogućnosti',
'LBL_TOGGLE_ADV' => 'Prikaz naprednih mogućnosti',
'LBL_TOGGLE_BASIC' => 'Prikaz osnovnih mogućnosti',
// Links
'LNK_LIST_SCHEDULER' => 'Planeri',
'LNK_NEW_SCHEDULER' => 'Stvori planer',
'LNK_LIST_SCHEDULED' => 'Zakazani poslovi',
// Messages
'SOCK_GREETING' => "\nOvo je sučelje za servis rasporeda SugarCRM. \n[Dostupne naredbe daemon: start|restart|shutdown|status ]\nZa izlaz utipkajte „quit”. Za isključivanje usluge utipkajte „shutdown”.\n",
'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da biste izbrisali raspored.',
'ERR_CRON_SYNTAX' => 'Neispravna Cron sintaksa',
'NTC_DELETE_CONFIRMATION' => 'Jeste li sigurni da želite izbrisati ovaj zapis?',
'NTC_STATUS' => 'Postavite status na Neaktivno da biste uklonili ovaj raspored s padajućih popisa planera',
'NTC_LIST_ORDER' => 'Postavite redoslijed kojim će se ovaj raspored prikazivati na padajućim popisima planera',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Postavljanje planera Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Postavljanje Crontaba',
'LBL_CRON_LINUX_DESC' => 'Napomena: da biste pokrenuli planere Sugar, dodajte sljedeći red u datoteku crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'Napomena: da biste pokrenuli planere Sugar, stvorite naredbenu datoteku za pokretanje s pomoću Windows zakazanih zadataka. Naredbena datoteka treba uključivati sljedeće naredbe: ',
'LBL_NO_PHP_CLI' => 'Ako vaše glavno računalo nema dostupnu binarnu datoteku PHP, možete upotrijebiti wget ili curl za pokretanje poslova.<br>za wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>za curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Zapisnik poslova',
'LBL_EXECUTE_TIME'			=> 'Vrijeme izvršavanja',

//jobstrings
'LBL_REFRESHJOBS' => 'Osvježi poslove',
'LBL_POLLMONITOREDINBOXES' => 'Provjeri račune dolazne pošte',
'LBL_PERFORMFULLFTSINDEX' => 'Sustav indeksa pretraživanja cijelog teksta',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Ukloni privremene PDF datoteke',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Objavite odobrene članke i istekle članke baze znanja.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Planer reda čekanja za Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Ukloni datoteke dijagnostičkog alata',
'LBL_SUGARJOBREMOVETMPFILES' => 'Ukloni privremene datoteke',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Ponovna izgradnja denormaliziranih sigurnosnih podataka o timovima',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Pokreni noćno slanje masovne e-pošte kampanje',
'LBL_ASYNCMASSUPDATE' => 'Izvrši asinkrona masovna ažuriranja',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Pokreni noćnu obradu vraćenih poruka e-pošte iz kampanje',
'LBL_PRUNEDATABASE' => 'Pročisti bazu podataka prvog dana u mjesecu',
'LBL_TRIMTRACKER' => 'Pročisti tablice pratitelja',
'LBL_PROCESSWORKFLOW' => 'Obrada zadataka iz tijeka rada',
'LBL_PROCESSQUEUE' => 'Pokreni generiranje izvješća o zakazanim zadacima',
'LBL_UPDATETRACKERSESSIONS' => 'Ažuriraj tablicu sesija pratitelja',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Stvori buduća vremenska razdoblja',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Pokreni slanje podsjetnika putem e-pošte',
'LBL_CLEANJOBQUEUE' => 'Čišćenje reda čekanja poslova',
'LBL_CLEANOLDRECORDLISTS' => 'Čišćenje popisa starih zapisa',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

