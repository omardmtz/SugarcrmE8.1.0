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
'LBL_OOTB_WORKFLOW'		=> 'Detyrat e procesit të rrjedhës së punës',
'LBL_OOTB_REPORTS'		=> 'Detyrat e planifikuara të gjenerimit të drejtimit të raporteve',
'LBL_OOTB_IE'			=> 'Kontrollo kutitë e maileve të drejtuara përbrenda',
'LBL_OOTB_BOUNCE'		=> 'Drejtimi i procesit të natës refuzon Emailat e Kampanjës',
'LBL_OOTB_CAMPAIGN'		=> 'Drejtimi i natës së Emailave masive të Kampanjës',
'LBL_OOTB_PRUNE'		=> 'Baza e të dhënave të trapave në muajin e 1',
'LBL_OOTB_TRACKER'		=> 'Tabela të trapave të gjurmuesve',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Shkurto listat e vjetra të regjistrimit',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Hiq fajllat e përkohshëm',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Hiq fajllat e mjeteve diagnostifikuese',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Hiq fajllat PDF të përkohshëm',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Rinovo tabelën e sesionit të gjurmimit',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Aktivizo njoftimet e rikujtesës me email',
'LBL_OOTB_CLEANUP_QUEUE' => 'Pastrimi i punëve të reshtit',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'krijo periudha kohore te ardhshme',
'LBL_OOTB_HEARTBEAT' => 'Rrahja e zemrës së Sugar',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Përditëso artikujt KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publiko artikujt e miratuar dhe artikujt e përfunduar të bazës së njohurive.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Punë e planifikuar e "Advanced Workflow"',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Rindërto të dhënat e denormalizuara të sigurisë së ekipit',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervali',
'LBL_LIST_LIST_ORDER' => 'Planifikimet',
'LBL_LIST_NAME' => 'Planifikimi:',
'LBL_LIST_RANGE' => 'Gama:',
'LBL_LIST_REMOVE' => 'Largo',
'LBL_LIST_STATUS' => 'Statusi',
'LBL_LIST_TITLE' => 'Lista e planifikimit',
'LBL_LIST_EXECUTE_TIME' => 'do të drejtohet në',
// human readable:
'LBL_SUN'		=> 'E diel',
'LBL_MON'		=> 'E hënë',
'LBL_TUE'		=> 'E martë',
'LBL_WED'		=> 'E mërkurë',
'LBL_THU'		=> 'E enjte',
'LBL_FRI'		=> 'E premte',
'LBL_SAT'		=> 'E shtunë',
'LBL_ALL'		=> 'Çdo ditë',
'LBL_EVERY_DAY'	=> 'Çdo ditë',
'LBL_AT_THE'	=> 'në',
'LBL_EVERY'		=> 'Çdo',
'LBL_FROM'		=> 'nga',
'LBL_ON_THE'	=> 'në',
'LBL_RANGE'		=> 'gjer',
'LBL_AT' 		=> 'tek',
'LBL_IN'		=> 'në',
'LBL_AND'		=> 'Dhe',
'LBL_MINUTES'	=> 'Minutat',
'LBL_HOUR'		=> 'orët',
'LBL_HOUR_SING'	=> 'orë',
'LBL_MONTH'		=> 'Muaji',
'LBL_OFTEN'		=> 'sa më shpesh që mundet',
'LBL_MIN_MARK'	=> 'shënues i minutave',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'Orët',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'Muajtë',
'LBL_DAY_OF_WEEK' => 'dita',
'LBL_CRONTAB_EXAMPLES' => 'E lartëshënuara përdor simbole planifikuese të etiketimit.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Drejtimet e specifikimeve të planifikuara të bazuara në orën e zonës së serverit (',
'LBL_CRONTAB_SERVER_TIME_POST' => 'Ju lutemi specifikoni kohën e përmbushjeve të planifikimit në përputhje me rrethanat',
// Labels
'LBL_ALWAYS' => 'Çdoherë',
'LBL_CATCH_UP' => 'ekzekuto nëse mungon',
'LBL_CATCH_UP_WARNING' => 'Mos kontrollo nësë puna do të kap më tepër se një minutë për ta drejtuar',
'LBL_DATE_TIME_END' => 'Data dhe koha e përfundimit',
'LBL_DATE_TIME_START' => 'Data dhe ora e nisjes',
'LBL_INTERVAL' => 'Intervali',
'LBL_JOB' => 'puna',
'LBL_JOB_URL' => 'Puna URL',
'LBL_LAST_RUN' => 'Drejtimi i fundit i suksesshëm',
'LBL_MODULE_NAME' => 'Sugar planifikimet',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar planifikues',
'LBL_MODULE_TITLE' => 'Planifikimet',
'LBL_NAME' => 'Emri i punës',
'LBL_NEVER' => 'Asnjëherë',
'LBL_NEW_FORM_TITLE' => 'Planifikim i ri',
'LBL_PERENNIAL' => 'I vazhdueshëm',
'LBL_SEARCH_FORM_TITLE' => 'Kërkim i planifikimeve:',
'LBL_SCHEDULER' => 'Planifikim:',
'LBL_STATUS' => 'Statusi',
'LBL_TIME_FROM' => 'Aktive nga',
'LBL_TIME_TO' => 'Aktive deri',
'LBL_WARN_CURL_TITLE' => 'cURL paralajmërim:',
'LBL_WARN_CURL' => 'Paralajmërim',
'LBL_WARN_NO_CURL' => 'Ky sistem nuk ka bibliotekat cURL të mundësuara/përpiluara në modulin e PHP.<br />(--withcurl=/path/to/curl_library). Ju lutemi të kontaktoni administratorin tuaj për të zgjidhur këtë çështje. Pa funksionalitetin e cURL, orari nuk mund të fute punët e saja.',
'LBL_BASIC_OPTIONS' => 'Themelimi bazë',
'LBL_ADV_OPTIONS'		=> 'Opcionet e avancuara',
'LBL_TOGGLE_ADV' => 'Trego opcionet e avancuara',
'LBL_TOGGLE_BASIC' => 'Trego opcionet bazike',
// Links
'LNK_LIST_SCHEDULER' => 'Planifikuesit',
'LNK_NEW_SCHEDULER' => 'Krijo planifikues',
'LNK_LIST_SCHEDULED' => 'Punët e planifikuara',
// Messages
'SOCK_GREETING' => "Kjo është ndërfaqja e SugarCRM për shërbimin e orarit. [ Komandat e demonit në dispozicion:fillimi|rinisja|mbyllja|statusi] Për të mbaruar, lloji $#39;mbaro$#39;\".<br />Për të mbyllë shërbimin $#39;mbyllje$#39;.",
'ERR_DELETE_RECORD' => 'Duhet përcaktuar numrin e regjistrimit për të fshirë planifikimin',
'ERR_CRON_SYNTAX' => 'Sintaksë e planifikuar jo valide',
'NTC_DELETE_CONFIRMATION' => 'A jeni të sigurtë që dëshironi të fshini këtë regjistrim?',
'NTC_STATUS' => 'Vendos statusin në pasiv për heqje të këtij planifikimi prej listës së poshtme të planifikimeve',
'NTC_LIST_ORDER' => 'Përcakto porosinë që ky planifikim do të shfaq në listën e poshtme të planifikimeve',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Për të themeluar planifikues të Windowsit',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Për të themeluar etiketim planifikues',
'LBL_CRON_LINUX_DESC' => 'Vërejtje: Në mënyrë për të drejtuar orarin Sugar, shtoni linjën në vijim për dosjen e skedës planifikuese:',
'LBL_CRON_WINDOWS_DESC' => 'Vërejtje: Në mënyrë për të drejtuar orarin Sugar, krijoni serinë e dosjes për të drejtuar përdorimin e detyrave të planifikuara të Windows. Seria e dosjes duhet të përfshijë komandat vijuese:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Regjisrtim i punës',
'LBL_EXECUTE_TIME'			=> 'Koha e ekzekutimit',

//jobstrings
'LBL_REFRESHJOBS' => 'Rifrsko punët',
'LBL_POLLMONITOREDINBOXES' => 'Kontrollo llogaritë e maileve të drejtuara për brenda',
'LBL_PERFORMFULLFTSINDEX' => 'Kërkimi i tekstit të plotë në sistemin e indeksit',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Hiq skedarët e përkohshëm PDF',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publiko artikujt e miratuar dhe artikujt e përfunduar të bazës së njohurive.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Planifikuesi i radhës së kërkimit elastik',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Hiq skedarët e mjetit diagnostikues',
'LBL_SUGARJOBREMOVETMPFILES' => 'Hiq skedarët e përkohshëm',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Rindërto të dhënat e denormalizuara të sigurisë së ekipit',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Drejtimi i natës së Emailave masive të Kampanjës',
'LBL_ASYNCMASSUPDATE' => 'Kryeni përditësim masive të josinkronik',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Drejtimi i procesit të natës refuzon Emailat e Kampanjës',
'LBL_PRUNEDATABASE' => 'Baza e të dhënave të trapave në muajin e 1',
'LBL_TRIMTRACKER' => 'Tabelat e trapave të gjurmuesve',
'LBL_PROCESSWORKFLOW' => 'Detyrat e procesit të rrjedhës së punës',
'LBL_PROCESSQUEUE' => 'Drejto detyrat e raporteve të planifikuara të gjenerimit',
'LBL_UPDATETRACKERSESSIONS' => 'Rinovo tabelat e sesionit të gjurmuesve',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'krijo periudha kohore te ardhshme',
'LBL_SUGARJOBHEARTBEAT' => 'Rrahja e zemrës së Sugar',
'LBL_SENDEMAILREMINDERS'=> 'Aktivizo dërgimin e rikujtesave me email',
'LBL_CLEANJOBQUEUE' => 'Pastrim i punës së reshtit',
'LBL_CLEANOLDRECORDLISTS' => 'Pastro listat e vjetra të regjistrimit',
'LBL_PMSEENGINECRON' => 'Planifikuesi i "Advanced Workflow"',
);

