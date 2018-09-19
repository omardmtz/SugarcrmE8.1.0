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
'LBL_OOTB_WORKFLOW'		=> 'Töötle töövooülesandeid',
'LBL_OOTB_REPORTS'		=> 'Käivita aruande loomise plaanitud ülesanded',
'LBL_OOTB_IE'			=> 'Kontrolli sissetulevate e-kirjade postkaste',
'LBL_OOTB_BOUNCE'		=> 'Käivita igaõhtused protsessi tagastatud kampaaniameilid',
'LBL_OOTB_CAMPAIGN'		=> 'Käivita igaõhtused hulgimeili kampaaniad',
'LBL_OOTB_PRUNE'		=> 'Kärbi andmebaasi kuu 1. kuupäeval',
'LBL_OOTB_TRACKER'		=> 'Kärbi otsija tabeleid',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Kärbi vanade kirjete loendeid',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Eemalda ajutised failid',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Eemalda diagnostikatööriista failid',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Eemalda ajutised PDF-failid',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Värskenda tabelit tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Käivita meili meeldetuletuse teavitused',
'LBL_OOTB_CLEANUP_QUEUE' => 'Puhasta tööde järjekord',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Loo tulevased ajaperioodid',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Värskenda KBContent artikleid.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Avaldage heakskiidetud artiklid ja aeguvad teadmusbaasiartiklid.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Taasta meeskonna denormaliseeritud turbeandmed',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervall:',
'LBL_LIST_LIST_ORDER' => 'Planeerijad:',
'LBL_LIST_NAME' => 'Planeerija:',
'LBL_LIST_RANGE' => 'Vahemik:',
'LBL_LIST_REMOVE' => 'Eemalda:',
'LBL_LIST_STATUS' => 'Olek:',
'LBL_LIST_TITLE' => 'Ajakava loend:',
'LBL_LIST_EXECUTE_TIME' => 'Käivitub:',
// human readable:
'LBL_SUN'		=> 'Pühapäev',
'LBL_MON'		=> 'Esmaspäev',
'LBL_TUE'		=> 'Teisipäev',
'LBL_WED'		=> 'Kolmapäev',
'LBL_THU'		=> 'Neljapäev',
'LBL_FRI'		=> 'Reede',
'LBL_SAT'		=> 'Laupäev',
'LBL_ALL'		=> 'Iga päev',
'LBL_EVERY_DAY'	=> 'Iga päev',
'LBL_AT_THE'	=> 'Ajal ',
'LBL_EVERY'		=> 'Iga',
'LBL_FROM'		=> 'Alates ',
'LBL_ON_THE'	=> 'Ajal ',
'LBL_RANGE'		=> ' kuni ',
'LBL_AT' 		=> ' kell ',
'LBL_IN'		=> ' aastal ',
'LBL_AND'		=> ' ja ',
'LBL_MINUTES'	=> 'minutit',
'LBL_HOUR'		=> 'tundi',
'LBL_HOUR_SING'	=> 'tund',
'LBL_MONTH'		=> 'kuu',
'LBL_OFTEN'		=> 'Nii tihti kui võimalik.',
'LBL_MIN_MARK'	=> ' minuti märk',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'h',
'LBL_DAY_OF_MONTH' => 'kuupäev',
'LBL_MONTHS' => 'kuu',
'LBL_DAY_OF_WEEK' => 'päev',
'LBL_CRONTAB_EXAMPLES' => 'Ülaltoodu kasutab standardset crontab esitust.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron spetsifikatsioone käitatakse serveri ajavööndi põhjal (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Määrake vastavalt planeerija täitmisaeg.',
// Labels
'LBL_ALWAYS' => 'Alati',
'LBL_CATCH_UP' => 'Täida märkamata jätmisel',
'LBL_CATCH_UP_WARNING' => 'Tühjendage ruut, kui selle töö käivitamiseks võib kuluda rohkem kui hetk.',
'LBL_DATE_TIME_END' => 'Lõppemise kuupäev ja kellaaeg',
'LBL_DATE_TIME_START' => 'Alustamise kuupäev ja kellaaeg',
'LBL_INTERVAL' => 'Intervall',
'LBL_JOB' => 'Töö',
'LBL_JOB_URL' => 'Töö URL',
'LBL_LAST_RUN' => 'Viimane edukas käivitus',
'LBL_MODULE_NAME' => 'Sugari planeerija',
'LBL_MODULE_NAME_SINGULAR' => 'Sugari planeerija',
'LBL_MODULE_TITLE' => 'Planeerijad',
'LBL_NAME' => 'Töö nimi',
'LBL_NEVER' => 'Mitte kunagi',
'LBL_NEW_FORM_TITLE' => 'Uus ajakava',
'LBL_PERENNIAL' => 'tähtajatu',
'LBL_SEARCH_FORM_TITLE' => 'Planeerija otsing',
'LBL_SCHEDULER' => 'Planeerija:',
'LBL_STATUS' => 'Olek',
'LBL_TIME_FROM' => 'Aktiivne alates',
'LBL_TIME_TO' => 'Aktiivne kuni',
'LBL_WARN_CURL_TITLE' => 'cURL-i hoiatus:',
'LBL_WARN_CURL' => 'Hoiatus:',
'LBL_WARN_NO_CURL' => 'Sellel süsteemil pole PHP moodulis lubatud/loodud cURL-i teeke (--with-curl=/path/to/curl_library). Probleemi lahendamiseks võtke ühendust oma administraatoriga. cURL funktsioonita ei saa planeerija oma töid teha.',
'LBL_BASIC_OPTIONS' => 'Põhiseadistus',
'LBL_ADV_OPTIONS'		=> 'Täpsemad suvandid',
'LBL_TOGGLE_ADV' => 'Kuva täpsemad suvandid',
'LBL_TOGGLE_BASIC' => 'Kuva põhisuvandid',
// Links
'LNK_LIST_SCHEDULER' => 'Planeerijad',
'LNK_NEW_SCHEDULER' => 'Loo planeerija',
'LNK_LIST_SCHEDULED' => 'Plaanitud tööd',
// Messages
'SOCK_GREETING' => "\nSee on SugarCRM-i planeerijate teenuse liides. \n[ Saadaval deemoni käsud: käivita|taaskäivita|sulge|olek ]\nLoobbumiseks sisestage quit. Teenuse sulgemiseks sisestage shutdown.\n",
'ERR_DELETE_RECORD' => 'Ajakava kustutamiseks täpsustage kirje numbrit.',
'ERR_CRON_SYNTAX' => 'Sobimatu Croni süntaks',
'NTC_DELETE_CONFIRMATION' => 'Kas olete kindel, et soovite selle kirje kustutada?',
'NTC_STATUS' => 'Selle planeerija eemaldamiseks Planeerija ripploenditest määrake selle olekuks Inaktiivne',
'NTC_LIST_ORDER' => 'Määrake järjekord, mitmendana see ajakava ripploendites Planeerija kuvatakse',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Windowsi planeerija seadistamine',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Crontabi seadistamine',
'LBL_CRON_LINUX_DESC' => 'Märkus: Sugari planeerijate käitamiseks lisage crontabi faili järgmine rida: ',
'LBL_CRON_WINDOWS_DESC' => 'Märkus: Sugari planeerijate käitamiseks looge käitatav pakkfail, kasutades Windowsi ajastatud ülesandeid. Pakkfail peaks sisaldama järgmisi käske: ',
'LBL_NO_PHP_CLI' => 'Kui teie hosti PHP kahendandmed pole saadavalb, saate oma tööde käivitamiseks kasutada suvandeid wget või curl.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Tööde logi',
'LBL_EXECUTE_TIME'			=> 'Täitmisaeg',

//jobstrings
'LBL_REFRESHJOBS' => 'Värskenda tööd',
'LBL_POLLMONITOREDINBOXES' => 'Kontrolli sissetulevate e-kirjade kontosid',
'LBL_PERFORMFULLFTSINDEX' => 'Täistekstiotsingu indeksi süsteem',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Eemalda ajutised PDF-failid',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Avaldage heakskiidetud artiklid ja aeguvad teadmusbaasiartiklid.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearchi ootejärjekorra plaanija',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Eemalda diagnostikatööriista failid',
'LBL_SUGARJOBREMOVETMPFILES' => 'Eemalda ajutised failid',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Taasta meeskonna denormaliseeritud turbeandmed',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Käivita igaõhtused hulgimeili kampaaniad',
'LBL_ASYNCMASSUPDATE' => 'Tee asünkroonne hulgivärskendus',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Käivita igaõhtused protsessi tagastatud kampaaniameilid',
'LBL_PRUNEDATABASE' => 'Kärbi andmebaasi kuu 1. kuupäeval',
'LBL_TRIMTRACKER' => 'Kärbi otsija tabeleid',
'LBL_PROCESSWORKFLOW' => 'Töötle töövooülesandeid',
'LBL_PROCESSQUEUE' => 'Käivita aruande loomise plaanitud ülesanded',
'LBL_UPDATETRACKERSESSIONS' => 'Värskenda otsija seansi tabelit',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Loo tulevased ajaperioodid',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Käivita meili meeldetuletuste saatmine',
'LBL_CLEANJOBQUEUE' => 'Puhasta tööde järjekord',
'LBL_CLEANOLDRECORDLISTS' => 'Puhasta vanade kirjete loendid',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

