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
'LBL_OOTB_WORKFLOW'		=> 'Vykdyti darbo sekų užduotis',
'LBL_OOTB_REPORTS'		=> 'Vykdyti suplanuotas ataskaitų užduotis',
'LBL_OOTB_IE'			=> 'Tikrinti įeinančias pašto dėžutes',
'LBL_OOTB_BOUNCE'		=> 'Vykdyti naktines nepasiekusių adresatų el. pašto kampanijas',
'LBL_OOTB_CAMPAIGN'		=> 'Vykdyti naktinius kampanijų laiškų siuntimus',
'LBL_OOTB_PRUNE'		=> 'Sumažinti duomenų bazę kiekvieno mėnesio 1 dieną',
'LBL_OOTB_TRACKER'		=> 'Sumažinti audito lenteles',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Prune Old Record Lists',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Remove temporary files',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Remove diagnostic tool files',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Remove temporary PDF files',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Atnaujinti tracker_sessions lentelę',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Paleisti el. pašto priminimų pranešimus',
'LBL_OOTB_CLEANUP_QUEUE' => 'Išvalyti suplanuotą darbų eilę',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Sukurti laiko periodus ateičiai',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Update KBContent articles.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publish approved articles & Expire KB Articles.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Atkurkite sugadintus komandos saugos duomenis',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervalas:',
'LBL_LIST_LIST_ORDER' => 'Planuotojai:',
'LBL_LIST_NAME' => 'Planuotojas:',
'LBL_LIST_RANGE' => 'Periodas:',
'LBL_LIST_REMOVE' => 'Išimti:',
'LBL_LIST_STATUS' => 'Statusas:',
'LBL_LIST_TITLE' => 'Planuotojų sąrašas:',
'LBL_LIST_EXECUTE_TIME' => 'Bus vykdomas:',
// human readable:
'LBL_SUN'		=> 'Sekmadienis',
'LBL_MON'		=> 'Pirmadienis',
'LBL_TUE'		=> 'Antradienis',
'LBL_WED'		=> 'Trečiadienis',
'LBL_THU'		=> 'Ketvirtadienis',
'LBL_FRI'		=> 'Penktadienis',
'LBL_SAT'		=> 'Šeštadienis',
'LBL_ALL'		=> 'Kiekvieną dieną',
'LBL_EVERY_DAY'	=> 'Kiekvieną dieną',
'LBL_AT_THE'	=> 'Laiku',
'LBL_EVERY'		=> 'Kiekvieną',
'LBL_FROM'		=> 'Nuo',
'LBL_ON_THE'	=> 'Kas',
'LBL_RANGE'		=> 'iki',
'LBL_AT' 		=> 'prie',
'LBL_IN'		=> 'į',
'LBL_AND'		=> 'ir',
'LBL_MINUTES'	=> 'minutės',
'LBL_HOUR'		=> 'valandos',
'LBL_HOUR_SING'	=> 'valanda',
'LBL_MONTH'		=> 'mėnuo',
'LBL_OFTEN'		=> 'Kaip įmanoma dažnai.',
'LBL_MIN_MARK'	=> 'minutės žymuo',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'val',
'LBL_DAY_OF_MONTH' => 'm.diena',
'LBL_MONTHS' => 'mėnuo',
'LBL_DAY_OF_WEEK' => 's.diena',
'LBL_CRONTAB_EXAMPLES' => 'Viršuje naudojama standartinė crontab žymėjimo sistema.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron nustatymai yra pagal serverio laiko zoną (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Prašome nustatyti planuotoją atsižvelgiant į tai.',
// Labels
'LBL_ALWAYS' => 'Visada',
'LBL_CATCH_UP' => 'Vykdyti jei praleistas',
'LBL_CATCH_UP_WARNING' => 'Nuimti pažymėjimą, jeigu darbas gali užtrukti ilgiau.',
'LBL_DATE_TIME_END' => 'Užbaigimo data ir laikas',
'LBL_DATE_TIME_START' => 'Pradžios data ir laikas',
'LBL_INTERVAL' => 'Intervalas',
'LBL_JOB' => 'Darbas',
'LBL_JOB_URL' => 'Darbo adresas',
'LBL_LAST_RUN' => 'Paskutinis sėkmingas įvykdymas',
'LBL_MODULE_NAME' => 'Sugar planuotojas',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar planuotojas',
'LBL_MODULE_TITLE' => 'Planuotojai',
'LBL_NAME' => 'Darbo pavadinimas',
'LBL_NEVER' => 'Niekada',
'LBL_NEW_FORM_TITLE' => 'Naujas planuotojas',
'LBL_PERENNIAL' => 'Amžinas',
'LBL_SEARCH_FORM_TITLE' => 'Planuotojo paieška',
'LBL_SCHEDULER' => 'Planuotojas:',
'LBL_STATUS' => 'Statusas',
'LBL_TIME_FROM' => 'Aktyvus nuo',
'LBL_TIME_TO' => 'Aktyvus iki',
'LBL_WARN_CURL_TITLE' => 'cURL perspėjimas:',
'LBL_WARN_CURL' => 'Perspėjimas:',
'LBL_WARN_NO_CURL' => 'Ši sistema netri cURL bibliotekų įjungtų/sukompiliuotų PHP modulyje(--su-curl=/kelias/į/curl_biblioteką).  Prašome susisiekti su administratoriumi ir išspręsti šią problemą.  Be cURL funkcionalumo, planuotojas negali daryti savo darbų.',
'LBL_BASIC_OPTIONS' => 'Baziniai nustatymai',
'LBL_ADV_OPTIONS'		=> 'Sudėtingesni nustatymai',
'LBL_TOGGLE_ADV' => 'Sudėtingesni nustatymai',
'LBL_TOGGLE_BASIC' => 'Baziniai nustatymai',
// Links
'LNK_LIST_SCHEDULER' => 'Planuotojai',
'LNK_NEW_SCHEDULER' => 'Sukurti planuotoją',
'LNK_LIST_SCHEDULED' => 'Suplanuoti darbai',
// Messages
'SOCK_GREETING' => "Tai yra interfeisas skirtas SugarCRM suplanavimo servisams. <br />[ Galimas daemon komandos: start|restart|shutdown|status ]<br /> Baigti, rašykite &#39;quit&#39;. Išjungti servisą rašykite &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Jūs turite nurodyti įrašo numerį, kad galėtumėme ištrinti suplanavimą.',
'ERR_CRON_SYNTAX' => 'Neteisinga Cron sintaksė',
'NTC_DELETE_CONFIRMATION' => 'Ar tikrai norite ištrinti šį įrašą ?',
'NTC_STATUS' => 'Nustatykite statusą į neaktyvų, kad ištrintumėte šį darbą iš iššokančio sąrašo',
'NTC_LIST_ORDER' => 'Nustatykite tvarką, kuria norite matyti iššokančiame sąraše',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Nustatyti Windows planuotoją',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Nustatyti crontab',
'LBL_CRON_LINUX_DESC' => 'Pridėti šią eilutę prie crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Sukurti batch failą su šiomis komandomis:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Darbų registratorius',
'LBL_EXECUTE_TIME'			=> 'Vykdymo laikas',

//jobstrings
'LBL_REFRESHJOBS' => 'Atnaujinti darbus',
'LBL_POLLMONITOREDINBOXES' => 'Patikrinti įeinančias pašto dėžutes',
'LBL_PERFORMFULLFTSINDEX' => 'Pilnos teksto paieškos indeksavimo sistema',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Pašalinkite laikinus PDF failus',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Paskelbkite patvirtintus straipsnius ir „Expire KB“ straipsnius.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => '„Elasticsearch“ eilės planavimo priemonė',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Pašalinkite diagnostikos įrankio failus',
'LBL_SUGARJOBREMOVETMPFILES' => 'Pašalinkite laikinus failus',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Atkurkite sugadintus komandos saugos duomenis',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Vykdyti masinių laiškų siuntimą',
'LBL_ASYNCMASSUPDATE' => 'Perform Asynchronous Mass Updates',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Vykdyti grįžtančių laiškų apdorojimą',
'LBL_PRUNEDATABASE' => 'Išvalyti duomenų bazę pirmą mėnesio dieną',
'LBL_TRIMTRACKER' => 'Išvalyti audito lenteles',
'LBL_PROCESSWORKFLOW' => 'Vykdyti darbo sekos užduotis',
'LBL_PROCESSQUEUE' => 'Paleisti ataskaitų generavimą nustatytoms užduotims',
'LBL_UPDATETRACKERSESSIONS' => 'Atnaujinti audito lentelės',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Sukurti laiko periodus ateičiai',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Siųsti priminimus el. paštu',
'LBL_CLEANJOBQUEUE' => 'Išvalyti suplanuotą darbų eilę',
'LBL_CLEANOLDRECORDLISTS' => 'Cleanup Old Record Lists',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

