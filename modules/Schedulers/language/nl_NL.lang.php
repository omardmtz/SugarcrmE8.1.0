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
'LBL_OOTB_WORKFLOW'		=> 'Uitvoeren Workflow Taken',
'LBL_OOTB_REPORTS'		=> 'Geplande taken voor het generen van rapporten uitvoeren',
'LBL_OOTB_IE'			=> 'Inkomende mailboxen controleren',
'LBL_OOTB_BOUNCE'		=> 'Teruggestuurde campagne-e-mails van het proces &#39;s nachts uitvoeren',
'LBL_OOTB_CAMPAIGN'		=> 'Massa e-mailcampagnes &#39;s nachts uitvoeren',
'LBL_OOTB_PRUNE'		=> 'Database weghalen op elke 1ste van de maand',
'LBL_OOTB_TRACKER'		=> 'Trackertabellen weghalen',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Oude recordlijsten weghalen',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Tijdelijke bestanden verwijderen',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Bestanden diagnostisch middel verwijderen',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Tijdelijke PDF bestanden verwijderen',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Tracker_sessions tabel bijwerken',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Herinneringsberichten per e-mail uitvoeren',
'LBL_OOTB_CLEANUP_QUEUE' => 'Gooi de wachtrij voor taken leeg',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Toekomstige tijdperioden aanmaken',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent artikelen bijwerken.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Goedgekeurde artikelen & verlopen KB-artikelen publiceren.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Geplande Taak Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Gedenormaliseerde teambeveiligingsgegevens opnieuw opbouwen',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Taakplanners:',
'LBL_LIST_NAME' => 'Taakplanner:',
'LBL_LIST_RANGE' => 'Bereik:',
'LBL_LIST_REMOVE' => 'Verwijderen:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Lijst plannen:',
'LBL_LIST_EXECUTE_TIME' => 'Zal worden uitgevoerd op:',
// human readable:
'LBL_SUN'		=> 'Zondag',
'LBL_MON'		=> 'Maandag',
'LBL_TUE'		=> 'Dinsdag',
'LBL_WED'		=> 'Woensdag',
'LBL_THU'		=> 'Donderdag',
'LBL_FRI'		=> 'Vrijdag',
'LBL_SAT'		=> 'Zaterdag',
'LBL_ALL'		=> 'Elke dag',
'LBL_EVERY_DAY'	=> 'Every day',
'LBL_AT_THE'	=> 'At the',
'LBL_EVERY'		=> 'Every',
'LBL_FROM'		=> 'From',
'LBL_ON_THE'	=> 'On the',
'LBL_RANGE'		=> 'to',
'LBL_AT' 		=> 'at',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'and',
'LBL_MINUTES'	=> 'minutes',
'LBL_HOUR'		=> 'hours',
'LBL_HOUR_SING'	=> 'hour',
'LBL_MONTH'		=> 'month',
'LBL_OFTEN'		=> ' Zo vaak mogelijk.',
'LBL_MIN_MARK'	=> 'minute mark',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'uur',
'LBL_DAY_OF_MONTH' => 'datum',
'LBL_MONTHS' => 'ma',
'LBL_DAY_OF_WEEK' => 'dag',
'LBL_CRONTAB_EXAMPLES' => 'Het bovenstaande gebruikt een standaard crontab notering.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'De cron specificaties lopen op basis van de tijdzone op de server (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Specificeer de uitvoertijd van de planning.',
// Labels
'LBL_ALWAYS' => 'Altijd',
'LBL_CATCH_UP' => 'Uitvoeren indien gemist',
'LBL_CATCH_UP_WARNING' => 'Selectie ongedaan maken als deze taak lang duurt om uit te voeren.',
'LBL_DATE_TIME_END' => 'Datum & tijd einde',
'LBL_DATE_TIME_START' => 'Datum & tijd start',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Taak',
'LBL_JOB_URL' => 'URL taak',
'LBL_LAST_RUN' => 'Laatste succesvolle uitvoer',
'LBL_MODULE_NAME' => 'Sugar Taakplanner',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar Taakplanner',
'LBL_MODULE_TITLE' => 'Taakplanners',
'LBL_NAME' => 'Naam taak',
'LBL_NEVER' => 'Never',
'LBL_NEW_FORM_TITLE' => 'Nieuwe planning',
'LBL_PERENNIAL' => 'eeuwig',
'LBL_SEARCH_FORM_TITLE' => 'Taakplanner Zoeken',
'LBL_SCHEDULER' => 'Taakplanner:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Actief vanaf',
'LBL_TIME_TO' => 'Actief tot',
'LBL_WARN_CURL_TITLE' => 'cURL waarschuwing:',
'LBL_WARN_CURL' => 'Waarschuwing:',
'LBL_WARN_NO_CURL' => 'Dit systeem bevat geen ingeschakelde cURL bibliotheken/samengesteld in de PHP module (--met-curl=/path/to/curl_library). Neem contact op met uw beheerder om dit probleem op te losse. Zonder de cURL functie kan de planning de taken niet doorlussen.',
'LBL_BASIC_OPTIONS' => 'Basis configuratie',
'LBL_ADV_OPTIONS'		=> 'Geavanceerde opties',
'LBL_TOGGLE_ADV' => 'Geavanceerde opties weergeven',
'LBL_TOGGLE_BASIC' => 'Basis opties weergeven',
// Links
'LNK_LIST_SCHEDULER' => 'Taakplanners',
'LNK_NEW_SCHEDULER' => 'Nieuwe Taakplanner',
'LNK_LIST_SCHEDULED' => 'Geplande taken',
// Messages
'SOCK_GREETING' => "This is the interface for SugarCRM Schedulers Service. <br />[ Available daemon commands: start|restart|shutdown|status ]<br />To quit, type &#39;quit&#39;.  To shutdown the service &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'U moet een recordnummer specificeren om de planning te verwijderen.',
'ERR_CRON_SYNTAX' => 'Ongeldige Cron syntax',
'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
'NTC_STATUS' => 'Stel de status in op inactief om deze planning uit de vervolgkeuzelijsten van de planning te verwijderen',
'NTC_LIST_ORDER' => 'Stel de volgorde waarop deze planning zal verschijnen in de vervolgkeuzelijsten van de planning in',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Configuratie van Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Configuratie van Crontab',
'LBL_CRON_LINUX_DESC' => 'Note: In order to run Sugar Schedulers, add the following line to the crontab file:',
'LBL_CRON_WINDOWS_DESC' => 'Note: In order to run the Sugar schedulers, create a batch file to run using Windows Scheduled Tasks. The batch file should include the following commands:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Taaklog',
'LBL_EXECUTE_TIME'			=> 'Execute Time',

//jobstrings
'LBL_REFRESHJOBS' => 'Taken vernieuwen',
'LBL_POLLMONITOREDINBOXES' => 'Inkomende mailaccounts controleren',
'LBL_PERFORMFULLFTSINDEX' => 'Volledig tekst zoeken indexsysteem',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Tijdelijke PDF bestanden verwijderen',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Goedgekeurde artikelen & vervallen KB artikelen publiceren',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch rijplanning',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Bestanden van het diagnostische middel verwijderen',
'LBL_SUGARJOBREMOVETMPFILES' => 'Tijdelijke bestanden verwijderen',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Gedenormaliseerde teambeveiligingsgegevens opnieuw opbouwen',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Massa e-mailcampagnes &#39;s nachts uitvoeren',
'LBL_ASYNCMASSUPDATE' => 'Asynchrone massa-updates uitvoeren',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Teruggestuurde campagne-e-mails van het proces &#39;s nachts uitvoeren',
'LBL_PRUNEDATABASE' => 'Database weghalen op elke 1ste van de maand',
'LBL_TRIMTRACKER' => 'Trackertabellen weghalen',
'LBL_PROCESSWORKFLOW' => 'Uitvoeren Workflow Taken',
'LBL_PROCESSQUEUE' => 'Geplande taken voor het generen van rapporten uitvoeren',
'LBL_UPDATETRACKERSESSIONS' => 'Sessietabellen tracker bijwerken',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Toekomstige tijdperioden aanmaken',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'E-mailherinneringen verzenden uitvoeren',
'LBL_CLEANJOBQUEUE' => 'Gooi de wachtrij voor taak leeg',
'LBL_CLEANOLDRECORDLISTS' => 'Oude recordlijsten opschonen',
'LBL_PMSEENGINECRON' => 'Planner Advanced Workflow',
);

