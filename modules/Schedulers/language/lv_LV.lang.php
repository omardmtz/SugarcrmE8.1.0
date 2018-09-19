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
'LBL_OOTB_WORKFLOW'		=> 'Izpildīt darbplūsmas uzdevumus',
'LBL_OOTB_REPORTS'		=> 'Izpildīt atskaišu ģenerēšanas ieplānotos uzdevumus',
'LBL_OOTB_IE'			=> 'Pārbaudīt ienākošā e-pasta kastītes',
'LBL_OOTB_BOUNCE'		=> 'Procesa noraidītos kompaņas e-pastus apstrādāt pa nakti',
'LBL_OOTB_CAMPAIGN'		=> 'Masu e-pasta kampaņas izpildīt pa nakti',
'LBL_OOTB_PRUNE'		=> 'Attīrīt datubāzi 1. mēneša dienā',
'LBL_OOTB_TRACKER'		=> 'Attīrīt sekotāja tabulas',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Izmest vecos ierakstus',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Noņemt pagaidu failus',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Noņemt diagnostikas rīka failus',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Noņemt pagaidu PDF failus',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Atjaunināt tracker_sessions Tabulu',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Ieslēgt e-pasta atgādinājumu paziņojumus',
'LBL_OOTB_CLEANUP_QUEUE' => 'Attīrīt uzdevumu rindu',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Izveidot nākotnes laika periodus',
'LBL_OOTB_HEARTBEAT' => 'Sugar pulss',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Atjaunināt Zināšanu bāzes satura rakstus.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicēt apstiprinātos rakstus un Expire KB Articles.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Atjaunot denormalizētos komandas drošības datus',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervāls:',
'LBL_LIST_LIST_ORDER' => 'Plānotāji:',
'LBL_LIST_NAME' => 'Plānotājs:',
'LBL_LIST_RANGE' => 'Diapazons:',
'LBL_LIST_REMOVE' => 'Noņemt:',
'LBL_LIST_STATUS' => 'Statuss:',
'LBL_LIST_TITLE' => 'Grafiku saraksts:',
'LBL_LIST_EXECUTE_TIME' => 'Izpildīsies:',
// human readable:
'LBL_SUN'		=> 'Svētdiena',
'LBL_MON'		=> 'Pirmdiena',
'LBL_TUE'		=> 'Otrdiena',
'LBL_WED'		=> 'Trešdiena',
'LBL_THU'		=> 'Ceturtdiena',
'LBL_FRI'		=> 'Piektdiena',
'LBL_SAT'		=> 'Sestdiena',
'LBL_ALL'		=> 'Katru dienu',
'LBL_EVERY_DAY'	=> 'Katru dienu',
'LBL_AT_THE'	=> 'Pie',
'LBL_EVERY'		=> 'Katru',
'LBL_FROM'		=> 'No',
'LBL_ON_THE'	=> 'Precīzi',
'LBL_RANGE'		=> 'līdz',
'LBL_AT' 		=> 'pie',
'LBL_IN'		=> 'iekš',
'LBL_AND'		=> 'un',
'LBL_MINUTES'	=> 'minūtes',
'LBL_HOUR'		=> 'stundas',
'LBL_HOUR_SING'	=> 'stundas',
'LBL_MONTH'		=> 'mēnesis',
'LBL_OFTEN'		=> 'Cik bieži vien iespējams.',
'LBL_MIN_MARK'	=> 'noteikt minūti',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'st',
'LBL_DAY_OF_MONTH' => 'datums',
'LBL_MONTHS' => 'mēn',
'LBL_DAY_OF_WEEK' => 'diena',
'LBL_CRONTAB_EXAMPLES' => 'Augstāk redzamais balstīts uz standarta crontab notāciju.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron specifikācijas izpildās, balstoties uz servera laika zonu  (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Lūdzu norādiet atbilstošu plānotāja izpildes laiku.',
// Labels
'LBL_ALWAYS' => 'Vienmēr',
'LBL_CATCH_UP' => 'Izpildīt, ja neveiksmīgs',
'LBL_CATCH_UP_WARNING' => 'Neatzīmēt, ja procesa izpildei vajaga daudz laika.',
'LBL_DATE_TIME_END' => 'Beigu datums un laiks',
'LBL_DATE_TIME_START' => 'Sākuma datums un laiks',
'LBL_INTERVAL' => 'Intervāls',
'LBL_JOB' => 'Uzdevums',
'LBL_JOB_URL' => 'Uzdevuma URL',
'LBL_LAST_RUN' => 'Pēdēja veiksmīgā izpilde',
'LBL_MODULE_NAME' => 'Sugar Plānotājs',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar Plānotājs',
'LBL_MODULE_TITLE' => 'Plānotāji',
'LBL_NAME' => 'Uzdevuma nosaukums',
'LBL_NEVER' => 'nekad',
'LBL_NEW_FORM_TITLE' => 'Jauns Grafiks',
'LBL_PERENNIAL' => 'nepārtraukts',
'LBL_SEARCH_FORM_TITLE' => 'Plānotāja meklēšana',
'LBL_SCHEDULER' => 'Plānotājs:',
'LBL_STATUS' => 'Statuss',
'LBL_TIME_FROM' => 'Aktīvs no',
'LBL_TIME_TO' => 'Aktīvs līdz',
'LBL_WARN_CURL_TITLE' => 'cURL brīdinājums:',
'LBL_WARN_CURL' => 'Brīdinājums:',
'LBL_WARN_NO_CURL' => 'Šīs sistēmas PHP modulī nav aktivizētas/nokompilētas cURL bibliotēkas (--with-curl=/path/to/curl_library).  Sazinieties ar administratoru, lai atrisinātu šo problēmu.  Bez cURL funkcionalitātes, Plānotājs nevar izpildīt savus uzdevumus.',
'LBL_BASIC_OPTIONS' => 'Pamata uzstādījumi',
'LBL_ADV_OPTIONS'		=> 'Paplašinātas iespējas',
'LBL_TOGGLE_ADV' => 'Parādīt paplašinātas iespējas',
'LBL_TOGGLE_BASIC' => 'Parādīt standarta iespējas',
// Links
'LNK_LIST_SCHEDULER' => 'Plānotāji',
'LNK_NEW_SCHEDULER' => 'Jauns plānotājs',
'LNK_LIST_SCHEDULED' => 'Ieplānotie uzdevumi',
// Messages
'SOCK_GREETING' => "Šis ir SugarCRM Plānotāju servisa interfeiss. <br />[ Pieejamas komandas: start|restart|shutdown|status ]<br />Lai izietu, ierakstiet &#39;quit&#39;.  Lai izslēgtu servisu, ierakstiet &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Jānorada ieraksta numurs, lai dzēstu grafiku.',
'ERR_CRON_SYNTAX' => 'Kļūdaina Cron sintakse',
'NTC_DELETE_CONFIRMATION' => 'Vai jūs tiešām vēlaties dzēst šo ierakstu?',
'NTC_STATUS' => 'Uzstādiet statusu "Neaktīvs", lai izņemtu šo grafiku no plānotāju nolaižamā saraksta',
'NTC_LIST_ORDER' => 'Noradiet kartību, pēc kuras grafiki būs sakārtoti plānotāju nolaižamajos sarakstos',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Lai uzstādītu Windows Plānotāju',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Lai uzstādītu Crontab',
'LBL_CRON_LINUX_DESC' => 'Piezīme:  Sugar plānotāju darbināšanai, pievienojiet sekojošu rindu crontab failam:',
'LBL_CRON_WINDOWS_DESC' => 'Piezīme: Sugar plānotāju darbināšanai, izveidojiet batch failu darbināšanai, izmantojot Windows uzdevumu plānotāju. Batch failam jāsatur sekojošas komandas:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Uzdevumu žurnāls',
'LBL_EXECUTE_TIME'			=> 'Izpildes laiks',

//jobstrings
'LBL_REFRESHJOBS' => 'Atjaunot uzdevumus',
'LBL_POLLMONITOREDINBOXES' => 'Pārbaudīt ienākošā e-pasta kontus',
'LBL_PERFORMFULLFTSINDEX' => 'Pilna teksta meklēšanas indeksēšanas sistēma',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Noņemt pagaidu PDF failus',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publicēt apstiprinātos rakstus un Expire KB Articles.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elastīgās meklēšanas rindas plānotājs',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Noņemt diagnostikas rīka failus',
'LBL_SUGARJOBREMOVETMPFILES' => 'Noņemt pagaidu failus',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Atjaunot denormalizētos komandas drošības datus',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Masveida e-pasta kampaņas izpildīt pa nakti',
'LBL_ASYNCMASSUPDATE' => 'Notiek asinhronas masveida izmaiņas (Mass Update)',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Procesa noraidītos kompaņas e-pastus apstrādāt pa nakti',
'LBL_PRUNEDATABASE' => 'Attīrīt datubāzi 1. mēneša dienā',
'LBL_TRIMTRACKER' => 'Attīrīt sekotāja tabulas',
'LBL_PROCESSWORKFLOW' => 'Izpildīt darbplūsmas uzdevumus',
'LBL_PROCESSQUEUE' => 'Izpildīt atskaišu ģenerēšanas ieplānotos uzdevumus',
'LBL_UPDATETRACKERSESSIONS' => 'Atjaunināt Sekotāja sesiju tabulas',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Izveidot nākotnes laika periodus',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar pulss',
'LBL_SENDEMAILREMINDERS'=> 'Ieslēgt e-pasta atgādinājumu sūtīšanu',
'LBL_CLEANJOBQUEUE' => 'Attīrīšanas uzdevumu rinda',
'LBL_CLEANOLDRECORDLISTS' => 'Iztīrīt vecos ierakstus',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

