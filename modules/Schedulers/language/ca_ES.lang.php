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
'LBL_OOTB_WORKFLOW'		=> 'Processar Tasques de Workflow',
'LBL_OOTB_REPORTS'		=> 'Executar Tasques Programades de Generació d´Informes',
'LBL_OOTB_IE'			=> 'Comprovar Safates d´Entrada',
'LBL_OOTB_BOUNCE'		=> 'Executar Procés Nocturn de Correus de Campanya Rebotats',
'LBL_OOTB_CAMPAIGN'		=> 'Executar Procés Nocturn de Campanyes de Correu Massiu',
'LBL_OOTB_PRUNE'		=> 'Truncar Base de dades al Inici del Mes',
'LBL_OOTB_TRACKER'		=> 'Truncar Històrial d´Usuari al Inici del Mes',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Netejar antigues llistes de registres',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Esborra arxius temporals',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Esborra arxius d&#39;eines de diagnòstic',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Esborra arxius PDF temporals',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Actualitzar taula tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Executa les notificacions dels recordatoris per correu electrònic',
'LBL_OOTB_CLEANUP_QUEUE' => 'Netejar cua de treball',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Crear períodes futurs de temps',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Actualització d&#39;articles KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicar articles aprovats i fer que caduquin articles de la base de coneixement.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Tasca planifica de l&#39;Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstuïu les dades de seguretat desregularitzades de l&#39;equip',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intèrval:',
'LBL_LIST_LIST_ORDER' => 'Planificadors:',
'LBL_LIST_NAME' => 'Planificador:',
'LBL_LIST_RANGE' => 'Rang:',
'LBL_LIST_REMOVE' => 'Treure:',
'LBL_LIST_STATUS' => 'Estat:',
'LBL_LIST_TITLE' => 'Llista de planificació:',
'LBL_LIST_EXECUTE_TIME' => 'Será executat en:',
// human readable:
'LBL_SUN'		=> 'Diumenge',
'LBL_MON'		=> 'Dilluns',
'LBL_TUE'		=> 'Dimarts',
'LBL_WED'		=> 'Dimecres',
'LBL_THU'		=> 'Dijous',
'LBL_FRI'		=> 'Divendres',
'LBL_SAT'		=> 'Dissabte',
'LBL_ALL'		=> 'Tots els dies',
'LBL_EVERY_DAY'	=> 'Tots els dies',
'LBL_AT_THE'	=> 'El',
'LBL_EVERY'		=> 'Cada',
'LBL_FROM'		=> 'Desde',
'LBL_ON_THE'	=> 'En el',
'LBL_RANGE'		=> 'a',
'LBL_AT' 		=> 'en',
'LBL_IN'		=> 'en',
'LBL_AND'		=> 'i',
'LBL_MINUTES'	=> 'minuts',
'LBL_HOUR'		=> 'hores',
'LBL_HOUR_SING'	=> 'hora',
'LBL_MONTH'		=> 'mes',
'LBL_OFTEN'		=> 'Tan sovint com sigui possible.',
'LBL_MIN_MARK'	=> 'marca per minut',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'hrs',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'mes',
'LBL_DAY_OF_WEEK' => 'dia',
'LBL_CRONTAB_EXAMPLES' => 'Lo a dalt mostrat utilitza notació estàndard de crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Les especificacions que cron s&#39;executi sobre la base de la zona horària del servidor (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Si us plau, especifiqui el temps d&#39;execució del planificador en conseqüència',
// Labels
'LBL_ALWAYS' => 'Sempre',
'LBL_CATCH_UP' => 'Executar Si Falla',
'LBL_CATCH_UP_WARNING' => 'Desmarqui si l´execució d´aquesta tasca pot durar més d´un moment.',
'LBL_DATE_TIME_END' => 'Data i hora de fi',
'LBL_DATE_TIME_START' => 'Data i hora d&#39;inici',
'LBL_INTERVAL' => 'Intèrval',
'LBL_JOB' => 'Tasca',
'LBL_JOB_URL' => 'URL tasca',
'LBL_LAST_RUN' => 'Última Execució Exitosa',
'LBL_MODULE_NAME' => 'Planificador de Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Planificador de Sugar',
'LBL_MODULE_TITLE' => 'Planificadors',
'LBL_NAME' => 'Nom de la feina',
'LBL_NEVER' => 'Mai',
'LBL_NEW_FORM_TITLE' => 'Nova planificació',
'LBL_PERENNIAL' => 'continu',
'LBL_SEARCH_FORM_TITLE' => 'Cerca de planificadors',
'LBL_SCHEDULER' => 'Planificador:',
'LBL_STATUS' => 'Estat',
'LBL_TIME_FROM' => 'Actiu Desde',
'LBL_TIME_TO' => 'Actiu Fins a',
'LBL_WARN_CURL_TITLE' => 'Avís cURL:',
'LBL_WARN_CURL' => 'Avís:',
'LBL_WARN_NO_CURL' => 'Aquest sistema no té les llibreries cURL habilitades/compilades en el mòdul de PHP (--with-curl=/ruta/a/libreria_curl).  ﻿Si us plau, contacti amb el seu administrador per resoldre el problema. Sense la funcionalitat que proveeix cURL, el Planificador no pot utilitzar fils amb les seves tasques.',
'LBL_BASIC_OPTIONS' => 'Configuració Bàsica',
'LBL_ADV_OPTIONS'		=> 'Opcions Avançades',
'LBL_TOGGLE_ADV' => 'Opcions Avançades',
'LBL_TOGGLE_BASIC' => 'Opcions Bàsiques',
// Links
'LNK_LIST_SCHEDULER' => 'Planificadors',
'LNK_NEW_SCHEDULER' => 'Nou Planificador',
'LNK_LIST_SCHEDULED' => 'Tasques Planificades',
// Messages
'SOCK_GREETING' => "Aquest és l&#39;interfície d&#39;usuari per al Servei de Planificació de SugarCRM.  [ Comandos de dimoni disponibles: start|restart|shutdown|status ] Per sortir, escrigui &#39;quit&#39;.  Per parar el servei &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Per suprimir la programació, heu d&#39;especificar un número de registre.',
'ERR_CRON_SYNTAX' => 'Sintaxis de Cron invàlida',
'NTC_DELETE_CONFIRMATION' => 'Esteu segur que voleu suprimir aquest registre?',
'NTC_STATUS' => 'Estableixi l´estat a Inactiu per treure aquesta planificació de les llistes desplegables de selecció de Planificador',
'NTC_LIST_ORDER' => 'Estableixi l´ordre en el qual aquesta planificació apareixerà en les llistes desplegables de selecció de Planificador',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Per configurar el Planificador de Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Per configurar Crontab',
'LBL_CRON_LINUX_DESC' => 'Afegeixi aquesta línia en el seu crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Crear un arxiu de procés per lots amb els següents comandos:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Registre de Tasques',
'LBL_EXECUTE_TIME'			=> 'Temps d´Execució',

//jobstrings
'LBL_REFRESHJOBS' => 'Actualitzar Treballs',
'LBL_POLLMONITOREDINBOXES' => 'Comprovar Comptes de Correu Entrant',
'LBL_PERFORMFULLFTSINDEX' => 'Cerca de text complet Sistema d&#39;Índex',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Esborra els arxius PDF temporals',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publiqueu articles aprovats i feu que caduquin articles de la base de coneixement.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Planificador de tasques de cerca d&#39;Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Esborra els arxius de les eines de diagnòstic',
'LBL_SUGARJOBREMOVETMPFILES' => 'Esborra els arxius temporals',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstuïu les dades de seguretat desregularitzades de l&#39;equip',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Executar campanyes de Correu Massiu Nocturnes',
'LBL_ASYNCMASSUPDATE' => 'Fer actualitzacions massives asíncrones',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Executar Procés Nocturn de Correus Rebotats en Campanyes',
'LBL_PRUNEDATABASE' => 'Truncar Base de Dades el 1º de cada Mes',
'LBL_TRIMTRACKER' => 'Truncar Taules de Monitorització',
'LBL_PROCESSWORKFLOW' => 'Processar Tasques de Workflow',
'LBL_PROCESSQUEUE' => 'Executar Tasques Planificades de Generació d&#39;Informes',
'LBL_UPDATETRACKERSESSIONS' => 'Actualitzar Taules de Sessió de Monitorització',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Crear períodes futurs de temps',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Executa l&#39;enviament de recordatoris per correu electrònic',
'LBL_CLEANJOBQUEUE' => 'Alliberar espai en cua de treball',
'LBL_CLEANOLDRECORDLISTS' => 'Netejar antigues llistes de registres',
'LBL_PMSEENGINECRON' => 'Planificador de l&#39;Advanced Workflow',
);

