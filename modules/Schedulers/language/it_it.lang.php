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
'LBL_OOTB_WORKFLOW'		=> 'Esegui le operazioni del workflow',
'LBL_OOTB_REPORTS'		=> 'Esegui i report dei compiti schedulati',
'LBL_OOTB_IE'			=> 'Controlla la posta in arrivo',
'LBL_OOTB_BOUNCE'		=> 'Invia di notte le email rimbalzate delle campagne',
'LBL_OOTB_CAMPAIGN'		=> 'Invia di notte le campagne email',
'LBL_OOTB_PRUNE'		=> 'Riduci le dimensioni del database il primo giorno del mese',
'LBL_OOTB_TRACKER'		=> 'Riduci le tabelle del tracker',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Pulizia Vecchie Liste di Record',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Rimuovi file temporanei',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Rimuovi files di strumenti di diagnosi',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Rimuovi files PDF temporanei',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Aggiorna tabella tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Esegui notifiche di promemoria tramite posta elettronica',
'LBL_OOTB_CLEANUP_QUEUE' => 'Svuota Coda Jobs',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Creare Archi Temporali Futuri',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Aggiorna articoli KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Pubblicare gli articoli approvati e gli Articoli Expire KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Ricostruisci dati di sicurezza squadra denormalizzati',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervallo:',
'LBL_LIST_LIST_ORDER' => 'Schedulatori:',
'LBL_LIST_NAME' => 'Schedulatore:',
'LBL_LIST_RANGE' => 'Intervallo:',
'LBL_LIST_REMOVE' => 'Rimuovi:',
'LBL_LIST_STATUS' => 'Stato:',
'LBL_LIST_TITLE' => 'Elenco Schedulazioni:',
'LBL_LIST_EXECUTE_TIME' => 'Sarà eseguito di:',
// human readable:
'LBL_SUN'		=> 'Domenica',
'LBL_MON'		=> 'Lunedì',
'LBL_TUE'		=> 'Martedì',
'LBL_WED'		=> 'Mercoledì',
'LBL_THU'		=> 'Giovedì',
'LBL_FRI'		=> 'Venerdì',
'LBL_SAT'		=> 'Sabato',
'LBL_ALL'		=> 'Ogni Giorno',
'LBL_EVERY_DAY'	=> 'Ogni giorno',
'LBL_AT_THE'	=> 'Al',
'LBL_EVERY'		=> 'Ogni',
'LBL_FROM'		=> 'Da',
'LBL_ON_THE'	=> 'Al',
'LBL_RANGE'		=> 'a',
'LBL_AT' 		=> 'di',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'e',
'LBL_MINUTES'	=> 'minuti',
'LBL_HOUR'		=> 'ore',
'LBL_HOUR_SING'	=> 'ora',
'LBL_MONTH'		=> 'mese',
'LBL_OFTEN'		=> 'Il più spesso possibile.',
'LBL_MIN_MARK'	=> 'minuto scelto',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'ore',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'mese',
'LBL_DAY_OF_WEEK' => 'giorno',
'LBL_CRONTAB_EXAMPLES' => 'Questo utilizza soprattutto la notazione standard crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Le specifiche cron girano in base al fuso orario del server (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Si prega di conseguenza di specificare il run time dello schedulatore.',
// Labels
'LBL_ALWAYS' => 'Sempre',
'LBL_CATCH_UP' => 'Esegui Se Mancato',
'LBL_CATCH_UP_WARNING' => 'Togli la selezione se questa operazione può richiedere molto tempo per l´esecuzione.',
'LBL_DATE_TIME_END' => 'Data e Ora Fine',
'LBL_DATE_TIME_START' => 'Data e Ora Inizio',
'LBL_INTERVAL' => 'Intervallo',
'LBL_JOB' => 'Operazione',
'LBL_JOB_URL' => 'URL Job',
'LBL_LAST_RUN' => 'Ultima Esecuzione senza errori',
'LBL_MODULE_NAME' => 'Schedulatore Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Schedulatore Sugar',
'LBL_MODULE_TITLE' => 'Schedulatori',
'LBL_NAME' => 'Nome Operazione',
'LBL_NEVER' => 'Mai',
'LBL_NEW_FORM_TITLE' => 'Nuova Schedulazione',
'LBL_PERENNIAL' => 'perpetua',
'LBL_SEARCH_FORM_TITLE' => 'Cerca Schedulazione',
'LBL_SCHEDULER' => 'Schedulatore:',
'LBL_STATUS' => 'Stato',
'LBL_TIME_FROM' => 'Attivo Da',
'LBL_TIME_TO' => 'Attivo A',
'LBL_WARN_CURL_TITLE' => 'Avviso cURL:',
'LBL_WARN_CURL' => 'Avviso:',
'LBL_WARN_NO_CURL' => 'Il PHP di questo sistema non ha le librerie cURL (--with-curl=/path/to/curl_library). Contatta l´amministratore del server per risolvere questo problema. Senza queste librerie non è possibile eseguire le operazioni schedulate.',
'LBL_BASIC_OPTIONS' => 'Configurazione Base',
'LBL_ADV_OPTIONS'		=> 'Opzioni Avanzate',
'LBL_TOGGLE_ADV' => 'Mostra Opzioni Avanzate',
'LBL_TOGGLE_BASIC' => 'Mostra Opzioni Base',
// Links
'LNK_LIST_SCHEDULER' => 'Schedulatori',
'LNK_NEW_SCHEDULER' => 'Nuova Schedulazione',
'LNK_LIST_SCHEDULED' => 'Operazioni Schedulate',
// Messages
'SOCK_GREETING' => "Questa è l´interfaccia per il Servizio di Schedulazione di SugarCRM. <br />[ Comandi del servizio disponibili: start|restart|shutdown|status ]<br /> Per uscire scrivi ´Esci´. Per arrestare il servizio scrivi ´Chiudi´.",
'ERR_DELETE_RECORD' => 'Per eliminare la schedulazione deve essere specificato il numero del record.',
'ERR_CRON_SYNTAX' => 'Syntax Cron non valida',
'NTC_DELETE_CONFIRMATION' => 'Sei sicuro di voler eliminare questo record?',
'NTC_STATUS' => 'Configura lo stato in Inattivo per rimuovere questa schedulazione dalla lista dei schedulatori.',
'NTC_LIST_ORDER' => 'Imposta l´ordine con cui appariranno queste schedulazioni nell´elenco dei schedulatori.',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Per impostare il schedulatore di Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Per impostare il Crontab',
'LBL_CRON_LINUX_DESC' => 'Nota: Per eseguire gli schedulatori di Sugar, aggiungere la seguente riga al file crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Nota: Per eseguire gli schedulatori di Sugar, creare un file batch usando le operazioni schedulate di Windows. Il file batch dovrebbe includere i seguenti comandi:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Operazioni Attive',
'LBL_EXECUTE_TIME'			=> 'Run Time',

//jobstrings
'LBL_REFRESHJOBS' => 'Aggiorna Jobs',
'LBL_POLLMONITOREDINBOXES' => 'Controlla gli indirizzi email della posta in arrivo',
'LBL_PERFORMFULLFTSINDEX' => 'Sistema Indice per la Ricerca full-text',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Rimuovi file PDF temporanei',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Pubblicare gli articoli approvati e far scadere gli articoli KB.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Schedulatore di coda Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Rimuovi i file dello strumenti di diagnosi',
'LBL_SUGARJOBREMOVETMPFILES' => 'Rimuovi file temporanei',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Ricostruisci dati di sicurezza squadra denormalizzati',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Invia di notte le campagne email massive',
'LBL_ASYNCMASSUPDATE' => 'Eseguire Aggiornamenti di Massa Asincroni',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Invia durante la notte le email rimbalzate della campagna',
'LBL_PRUNEDATABASE' => 'Riduci il Database il primo giorno del mese',
'LBL_TRIMTRACKER' => 'Riduci le tabelle del tracker',
'LBL_PROCESSWORKFLOW' => 'Esegui le operazioni del workflow',
'LBL_PROCESSQUEUE' => 'Esegui i report dei compiti schedulati',
'LBL_UPDATETRACKERSESSIONS' => 'Aggiorna le tabelle di sessione del Tracker',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Creare Archi Temporali Futuri',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Esegui invio notifiche di promemoria tramite posta elettronica',
'LBL_CLEANJOBQUEUE' => 'Pulisci Coda Job',
'LBL_CLEANOLDRECORDLISTS' => 'Pulire Vecchi Elenchi',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

