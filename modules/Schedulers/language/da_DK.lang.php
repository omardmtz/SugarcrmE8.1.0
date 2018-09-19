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
'LBL_OOTB_WORKFLOW'		=> 'Behandling af arbejdsgangopgaver',
'LBL_OOTB_REPORTS'		=> 'Kør planlagte opgaver til rapportgenerering',
'LBL_OOTB_IE'			=> 'Tjek indgående postkasser',
'LBL_OOTB_BOUNCE'		=> 'Kør hver nat proces med afviste kampagne-e-mails',
'LBL_OOTB_CAMPAIGN'		=> 'Kør hver nat kampagner med masse-e-mails',
'LBL_OOTB_PRUNE'		=> 'Beskær databasen den 1. i måneden',
'LBL_OOTB_TRACKER'		=> 'Beskær sporingstabeller',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Kort af gamle rekord lister',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Fjern midlertidige filer',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Fjern diagnosticeringsværktøjsfiler',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Fjern midlertidige filer',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Opdater sporingssessionstabel',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Kør e-mail-påmindelsesbeskeder',
'LBL_OOTB_CLEANUP_QUEUE' => 'Rens job kø',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Opret fremtidige tidsperioder',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Opdatér KBContent artikler.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Udgiv godkendte artikler & udløb KB-artikler.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow planlagt Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Genopbyg unormale team-sikkerhedsdata',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Planlæggere:',
'LBL_LIST_NAME' => 'Planlægger:',
'LBL_LIST_RANGE' => 'Interval:',
'LBL_LIST_REMOVE' => 'Fjern:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Liste over tidsplaner:',
'LBL_LIST_EXECUTE_TIME' => 'Vil køre på:',
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
'LBL_AT_THE'	=> 'På',
'LBL_EVERY'		=> 'Hver',
'LBL_FROM'		=> 'Fra',
'LBL_ON_THE'	=> 'På den',
'LBL_RANGE'		=> 'til',
'LBL_AT' 		=> 'på',
'LBL_IN'		=> 'i',
'LBL_AND'		=> 'og',
'LBL_MINUTES'	=> 'minutter',
'LBL_HOUR'		=> 'timer',
'LBL_HOUR_SING'	=> 'time',
'LBL_MONTH'		=> 'måned',
'LBL_OFTEN'		=> 'Så tit som muligt.',
'LBL_MIN_MARK'	=> 'minutmærke',


// crontabs
'LBL_MINS' => 'min.',
'LBL_HOURS' => 't.',
'LBL_DAY_OF_MONTH' => 'dato',
'LBL_MONTHS' => 'må',
'LBL_DAY_OF_WEEK' => 'dag',
'LBL_CRONTAB_EXAMPLES' => 'Ovennævnte bruger standard crontab-notation.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron specifikationer køre ud på serveren tidszone (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Specificer venligst Planlægger eksekverings tid tilsvarende.',
// Labels
'LBL_ALWAYS' => 'Altid',
'LBL_CATCH_UP' => 'Udfør hvis ubesvaret',
'LBL_CATCH_UP_WARNING' => 'Fjern markeringen, hvis dette job må tage mere end et øjeblik at køre.',
'LBL_DATE_TIME_END' => 'Dato & tid slut',
'LBL_DATE_TIME_START' => 'Dato & tid start',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Job',
'LBL_JOB_URL' => 'Job URL',
'LBL_LAST_RUN' => 'Seneste succesfulde kørsel',
'LBL_MODULE_NAME' => 'Sugar-planlægger',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar-planlægger',
'LBL_MODULE_TITLE' => 'Planlæggere',
'LBL_NAME' => 'Jobnavn',
'LBL_NEVER' => 'Aldrig',
'LBL_NEW_FORM_TITLE' => 'Ny tidsplan',
'LBL_PERENNIAL' => 'tidsubegrænset',
'LBL_SEARCH_FORM_TITLE' => 'Søg efter planlægger',
'LBL_SCHEDULER' => 'Planlægger:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktiv fra',
'LBL_TIME_TO' => 'Aktiv til',
'LBL_WARN_CURL_TITLE' => 'cURL-advarsel:',
'LBL_WARN_CURL' => 'Advarsel:',
'LBL_WARN_NO_CURL' => 'Dette system har ikke cURL-biblioteker aktiveret/kompileret i PHP-modulet (- with-curl=/sti/til/curl_library). Kontakt administratoren for at løse dette problem. Uden cURL-funktionaliteten, kan planlæggeren ikke tråde sine job.',
'LBL_BASIC_OPTIONS' => 'Basiskonfiguration',
'LBL_ADV_OPTIONS'		=> 'Avancerede indstillinger',
'LBL_TOGGLE_ADV' => 'Avancerede indstillinger',
'LBL_TOGGLE_BASIC' => 'Grundlæggende indstillinger',
// Links
'LNK_LIST_SCHEDULER' => 'Planlæggere',
'LNK_NEW_SCHEDULER' => 'Opret planlægger',
'LNK_LIST_SCHEDULED' => 'Planlagte job',
// Messages
'SOCK_GREETING' => "\nDette er grænsefladen til SugarCRM Schedulers-tjenesten. \n[ Tilgængelig daemon kommandoer: start|genstart|shutdown|status ]\nTo quit, type 'forlad'. For at lukke service 'shutdown'.\n",
'ERR_DELETE_RECORD' => 'Du skal angive et postnummer for at slette tidsplanen.',
'ERR_CRON_SYNTAX' => 'Ugyldig Cron-syntaks',
'NTC_DELETE_CONFIRMATION' => 'Er du sikker på, at du vil slette denne post?',
'NTC_STATUS' => 'Angiv status til Inaktiv for at fjerne denne tidsplan fra Planlægger-rullelisterne',
'NTC_LIST_ORDER' => 'Angiv den rækkefølge, hvori denne tidsplan vil blive vist i Planlægger-rullelisterne',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Til Setup Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Til Setup crontab',
'LBL_CRON_LINUX_DESC' => 'Føj denne linje til crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Opret en batchfil med følgende kommandoer:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Joblog',
'LBL_EXECUTE_TIME'			=> 'Udfør tid',

//jobstrings
'LBL_REFRESHJOBS' => 'Opdater job',
'LBL_POLLMONITOREDINBOXES' => 'Tjek indgående e-mail-konti',
'LBL_PERFORMFULLFTSINDEX' => 'Fuldtekst søgeindeks system',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Fjern midlertidige PDF-filer',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Udgiv godkendte artikler & udløb KB-artikler.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch kø-planlægger',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Fjern diagnosticeringsværktøjsfiler',
'LBL_SUGARJOBREMOVETMPFILES' => 'Fjern midlertidige filer',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Genopbyg unormale team-sikkerhedsdata',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Kør hver nat kampagner med masse-e-mails',
'LBL_ASYNCMASSUPDATE' => 'Udfør Asynchronous masse opdateringer',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Kør hver nat proces med afviste kampagne-e-mails',
'LBL_PRUNEDATABASE' => 'Beskær databasen den 1. i måneden',
'LBL_TRIMTRACKER' => 'Beskær sporingstabeller',
'LBL_PROCESSWORKFLOW' => 'Behandling af arbejdsgangopgaver',
'LBL_PROCESSQUEUE' => 'Kør planlagte opgaver til rapportgenerering',
'LBL_UPDATETRACKERSESSIONS' => 'Opdater sporingssessionstabeller',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Opret fremtidige tidsperioder',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Kør e-mail-påmindelser sender',
'LBL_CLEANJOBQUEUE' => 'Rens job kø',
'LBL_CLEANOLDRECORDLISTS' => 'Oprydning i gamle rekord lister',
'LBL_PMSEENGINECRON' => 'Avanceret Workflow planlægger',
);

