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
'LBL_OOTB_WORKFLOW'		=> 'Kjør workflow-oppgaver',
'LBL_OOTB_REPORTS'		=> 'Kjør rapportgenerering på planlagte oppgaver',
'LBL_OOTB_IE'			=> 'Sjekk innkommende e-post',
'LBL_OOTB_BOUNCE'		=> 'Kjør nattlige prosesser på returnert kampanje-e-post',
'LBL_OOTB_CAMPAIGN'		=> 'Kjør nattlige masse-e-post kampanjer',
'LBL_OOTB_PRUNE'		=> 'Redusér databasen den første i hver måned',
'LBL_OOTB_TRACKER'		=> 'Redusér brukerhistorikktabeller den første i hver måned',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Beskjær gamle registreringslister',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Fjern midlertidige filer',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Fjern filer for diagnostikkverktøy',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Fjern midlertidige PDF-filer',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Oppdater tracker_sessions tabell',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Kjør påminnelsesvarsler via e-post',
'LBL_OOTB_CLEANUP_QUEUE' => 'Rens jobbkø',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Opprett fremtidige tidsperioder',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Oppdater KBContent-artikler.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publiser godkjente artikler og KB-artikler som utløper.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Gjenoppbygg demormaliserte sikkerhetsdata for team',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervall:',
'LBL_LIST_LIST_ORDER' => 'Planleggere:',
'LBL_LIST_NAME' => 'Planlegger:',
'LBL_LIST_RANGE' => 'Rekkevidde:',
'LBL_LIST_REMOVE' => 'Fjern:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Planlegg liste:',
'LBL_LIST_EXECUTE_TIME' => 'Kommer til å kjøres:',
// human readable:
'LBL_SUN'		=> 'Søndag',
'LBL_MON'		=> 'Mandag',
'LBL_TUE'		=> 'Tirsdag',
'LBL_WED'		=> 'Onsdag',
'LBL_THU'		=> 'Torsdag',
'LBL_FRI'		=> 'Fredag',
'LBL_SAT'		=> 'Lørdag',
'LBL_ALL'		=> 'Hver dag',
'LBL_EVERY_DAY'	=> 'Hver dag',
'LBL_AT_THE'	=> 'Ved den/det',
'LBL_EVERY'		=> 'Hver',
'LBL_FROM'		=> 'Fra',
'LBL_ON_THE'	=> 'På den/det',
'LBL_RANGE'		=> 'til',
'LBL_AT' 		=> 'ved',
'LBL_IN'		=> 'i',
'LBL_AND'		=> 'og',
'LBL_MINUTES'	=> 'minutter',
'LBL_HOUR'		=> 'timer',
'LBL_HOUR_SING'	=> 'time',
'LBL_MONTH'		=> 'måned',
'LBL_OFTEN'		=> 'Så ofte som mulig.',
'LBL_MIN_MARK'	=> 'minuttmarkør',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'timer',
'LBL_DAY_OF_MONTH' => 'dato',
'LBL_MONTHS' => 'måned',
'LBL_DAY_OF_WEEK' => 'dag',
'LBL_CRONTAB_EXAMPLES' => 'De ovenstående bruker standardisert crontab-tegnsystem.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'De kronologiske spesifikasjonene er basert på serverens tidssone (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Vennligst oppgi tid for kjøring av planleggeren tilsvarende.',
// Labels
'LBL_ALWAYS' => 'Alltid',
'LBL_CATCH_UP' => 'Gjennomfør hvis savnet',
'LBL_CATCH_UP_WARNING' => 'Ta vekk markering hvis oppgaven tar land tid å gjennomføre.',
'LBL_DATE_TIME_END' => 'Dato & tid start',
'LBL_DATE_TIME_START' => 'Dato & tid slutt',
'LBL_INTERVAL' => 'Intervall',
'LBL_JOB' => 'Jobb',
'LBL_JOB_URL' => 'Jobb-URL',
'LBL_LAST_RUN' => 'Siste suksessrike kjøring',
'LBL_MODULE_NAME' => 'Sugar-planlegger',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar-planlegger',
'LBL_MODULE_TITLE' => 'Planleggere',
'LBL_NAME' => 'Jobbnavn',
'LBL_NEVER' => 'Aldri',
'LBL_NEW_FORM_TITLE' => 'Nytt skjema',
'LBL_PERENNIAL' => 'evig',
'LBL_SEARCH_FORM_TITLE' => 'Søk planlegger',
'LBL_SCHEDULER' => 'Planlegger:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktiv fra',
'LBL_TIME_TO' => 'Aktiv til',
'LBL_WARN_CURL_TITLE' => 'cURL-advarsel:',
'LBL_WARN_CURL' => 'Advarsel:',
'LBL_WARN_NO_CURL' => 'Dette systemet har ikke integrert cURL-bibliotek i PHP-modulen (--with-curl=/path/to/curl_library).  Vennligst ta kontakt med din administrator for å løse dette problemet. Uten cURL-funksjonen kan ikke Planleggeren gjenge oppgavene sine.',
'LBL_BASIC_OPTIONS' => 'Grunnleggende oppsett',
'LBL_ADV_OPTIONS'		=> 'Avanserte innstillinger',
'LBL_TOGGLE_ADV' => 'Avanserte innstillinger',
'LBL_TOGGLE_BASIC' => 'Grunnleggende innstillinger',
// Links
'LNK_LIST_SCHEDULER' => 'Planleggere',
'LNK_NEW_SCHEDULER' => 'Opprett planlegger',
'LNK_LIST_SCHEDULED' => 'Planlagte jobber',
// Messages
'SOCK_GREETING' => "Dette er grensesnittet for SugarCRM Planleggingsservice. [Tilgjenglige daemon-kommandoer: start|restart|shutdown|status] For å avslutte, tast &#39;quit&#39;. For å lukke tjenesten tast &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Du må oppgi et registreringsnummer for å slette skjemaet.',
'ERR_CRON_SYNTAX' => 'Ugyldig Cron syntaks',
'NTC_DELETE_CONFIRMATION' => 'Er du sikker på at du vil slette denne oppføringen?',
'NTC_STATUS' => 'Sett status til Passiv for å fjerne dette skjemaet fra Planlegger-rullelisten',
'NTC_LIST_ORDER' => 'Velg rekkefølgen for hvordan dette skjemaet vil vises i Planlegger-rullelisten',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Til oppsett for Windows-planlegger',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Til Crontab-oppsett',
'LBL_CRON_LINUX_DESC' => 'Legg til denne setningen til din crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Opprett en samlefil med de følgende kommandoene:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Arbeidslogg',
'LBL_EXECUTE_TIME'			=> 'Avslutningstid',

//jobstrings
'LBL_REFRESHJOBS' => 'Oppdater jobber',
'LBL_POLLMONITOREDINBOXES' => 'Sjekk inngående e-postkontoer',
'LBL_PERFORMFULLFTSINDEX' => 'Fulltekst Søkeindeks-system',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Fjern midlertidige PDF-filer',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publiser godkjente artikler og Utløpt KB-artikler.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch Queue Scheduler',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Fjern filer av diagnostikkverktøy',
'LBL_SUGARJOBREMOVETMPFILES' => 'Fjern midlertidige filer',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Gjenoppbygg demormaliserte sikkerhetsdata for team',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Kjør nattlige masseutsendelser av e-post-kampanje',
'LBL_ASYNCMASSUPDATE' => 'Utfør asynkrone masseoppdateringer',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Kjør nattlige prosesser på returnert kampanje-e-post',
'LBL_PRUNEDATABASE' => 'Rens opp i databasen den første i hver måned',
'LBL_TRIMTRACKER' => 'Rens opp i Tracker tabellene.',
'LBL_PROCESSWORKFLOW' => 'Kjør workflow-oppgaver',
'LBL_PROCESSQUEUE' => 'Kjør rapportgenerering for planlagte oppgaver',
'LBL_UPDATETRACKERSESSIONS' => 'Oppdater Tracker Session tabeller',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Opprett fremtidige tidsperioder',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Kjør sending av e-postpåminnelser',
'LBL_CLEANJOBQUEUE' => 'Rens jobbkø',
'LBL_CLEANOLDRECORDLISTS' => 'Opprydding av gamle registeringslister',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

