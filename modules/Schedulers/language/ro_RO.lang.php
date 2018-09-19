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
'LBL_OOTB_WORKFLOW'		=> 'Prelucrati activitatile Workflow',
'LBL_OOTB_REPORTS'		=> 'Activati Raportul de Sarcini programat',
'LBL_OOTB_IE'			=> 'Verificati casutele postale Inbound',
'LBL_OOTB_BOUNCE'		=> 'Activati procesul Nightly pt email-urile returnate companiei',
'LBL_OOTB_CAMPAIGN'		=> 'Derulati campania Nightly Mass email',
'LBL_OOTB_PRUNE'		=> 'Baza de date Prune la data de 1 a lunii',
'LBL_OOTB_TRACKER'		=> 'tabele Prune Tracker',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Reducere liste înregistrări vechi',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Eliminare fişiere temporare',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Eliminare fişiere instrumente de diagnosticare',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Eliminare fişiere PDF temporare',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Update tabel sesiuni tracker',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Rulare notificări memento e-mail',
'LBL_OOTB_CLEANUP_QUEUE' => 'Curăţaţi coada de operaţii',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Creați perioadele viitoare de timp',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Actualizaţi articolele KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicare articole aprobate şi Expirare articole KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstruire date de securitate echipă denormalizate',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'interval',
'LBL_LIST_LIST_ORDER' => 'Programatori',
'LBL_LIST_NAME' => 'Programator:',
'LBL_LIST_RANGE' => 'Specrtu:',
'LBL_LIST_REMOVE' => 'Inlatura:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Lista programari',
'LBL_LIST_EXECUTE_TIME' => 'Va rula la:',
// human readable:
'LBL_SUN'		=> 'Duminica',
'LBL_MON'		=> 'Luni',
'LBL_TUE'		=> 'Marti',
'LBL_WED'		=> 'Miercuri',
'LBL_THU'		=> 'Joi',
'LBL_FRI'		=> 'Vineri',
'LBL_SAT'		=> 'Sambata',
'LBL_ALL'		=> 'In fiecare zi',
'LBL_EVERY_DAY'	=> 'In fiecare zi',
'LBL_AT_THE'	=> 'La',
'LBL_EVERY'		=> 'In fiecare',
'LBL_FROM'		=> 'de la',
'LBL_ON_THE'	=> 'in',
'LBL_RANGE'		=> 'catre',
'LBL_AT' 		=> 'la',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'si',
'LBL_MINUTES'	=> 'minute',
'LBL_HOUR'		=> 'ore',
'LBL_HOUR_SING'	=> 'ora',
'LBL_MONTH'		=> 'Luna',
'LBL_OFTEN'		=> 'Cat se poate de des.',
'LBL_MIN_MARK'	=> 'Marca minute',


// crontabs
'LBL_MINS' => 'min.',
'LBL_HOURS' => 'ore',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'luni',
'LBL_DAY_OF_WEEK' => 'zi',
'LBL_CRONTAB_EXAMPLES' => 'Cele de mai sus folosesc notatia crontab standard.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Caietul de sarcini cron rula pe server-ul de  fus orar',
'LBL_CRONTAB_SERVER_TIME_POST' => 'Vă rugăm să specificaţi timpul de executie planificator în consecinţă',
// Labels
'LBL_ALWAYS' => 'Intotdeauna',
'LBL_CATCH_UP' => 'Activati in cazul in care sunt pierdute',
'LBL_CATCH_UP_WARNING' => 'Debifaţi daca aceasta functie poate dura mai mult decât o clipă pentru a rula.',
'LBL_DATE_TIME_END' => 'Data si timpul expirarii',
'LBL_DATE_TIME_START' => 'Data si timpul de start',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Slujba',
'LBL_JOB_URL' => 'URL operaţie',
'LBL_LAST_RUN' => 'Ultima rulare cu succes',
'LBL_MODULE_NAME' => 'Programator Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Programator Sugar',
'LBL_MODULE_TITLE' => 'Programatori',
'LBL_NAME' => 'Nume slujba',
'LBL_NEVER' => 'Niciodata',
'LBL_NEW_FORM_TITLE' => 'Programare noua',
'LBL_PERENNIAL' => 'perpetuu',
'LBL_SEARCH_FORM_TITLE' => 'Cautare programare',
'LBL_SCHEDULER' => 'Programator:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Activ de la',
'LBL_TIME_TO' => 'Activ pana la',
'LBL_WARN_CURL_TITLE' => 'Avertisment cURL"',
'LBL_WARN_CURL' => 'Avertisment',
'LBL_WARN_NO_CURL' => 'Acest sistem nu are biblioteci cURL activat / compilată în modulul PHP (- with-curl = / calea / catre / curl_library). Vă rugăm să contactaţi administratorul dvs. pentru a rezolva această problemă. Fără funcţionalitatea cURL, Scheduler nu poate arata functiile.',
'LBL_BASIC_OPTIONS' => 'Setari de baza',
'LBL_ADV_OPTIONS'		=> 'Optiune avansate',
'LBL_TOGGLE_ADV' => 'Arata optiunile avansate',
'LBL_TOGGLE_BASIC' => 'Arata optiunile de baza',
// Links
'LNK_LIST_SCHEDULER' => 'Programatori',
'LNK_NEW_SCHEDULER' => 'Creati',
'LNK_LIST_SCHEDULED' => 'Functii programate',
// Messages
'SOCK_GREETING' => "Aceasta este interfaţa pentru SugarCRM Programatoare Service. [Disponibil comenzi daemon: start | restart | shutdown | starea] Pentru a ieşi, de tip \"renunta\". Pentru a shutdown \"shutdown\" de serviciu.",
'ERR_DELETE_RECORD' => 'Trebuie sa specifici un numar de inregistrare pentru a sterge programarea',
'ERR_CRON_SYNTAX' => 'Sintaxa Cron invalida',
'NTC_DELETE_CONFIRMATION' => 'Esti sigur ca vrei sa stergi aceasta inregistrare?',
'NTC_STATUS' => 'Setare Starea Inactiv pentru a elimina acest producător din lista abandonata',
'NTC_LIST_ORDER' => 'Setaţi ordinea în care acest tip vor fi afişate în listele de tip marfuri abandonate',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Pt a seta Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Pt a seta Crontab',
'LBL_CRON_LINUX_DESC' => 'Notă: Pentru a rula Sugar Programatoare, adăugaţi următoarea linie în fişierul crontab',
'LBL_CRON_WINDOWS_DESC' => 'Notă: Pentru a rula Programatoare Sugar, a crea un fişier batch pentru a rula folosind Windows Scheduled Tasks. Fişier batch ar trebui să includă următoarele comenzi:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Jurnal',
'LBL_EXECUTE_TIME'			=> 'Executa Timpul',

//jobstrings
'LBL_REFRESHJOBS' => 'Locuri de munca actualizate',
'LBL_POLLMONITOREDINBOXES' => 'Verificaţi Conturi Inbound Mail',
'LBL_PERFORMFULLFTSINDEX' => 'Full-text de căutare sistem de index',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Eliminare fişiere PDF temporare',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publicare articole aprobate şi Expirare articole KB.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Programator de coadă Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Eliminare fişiere instrument de diagnosticare',
'LBL_SUGARJOBREMOVETMPFILES' => 'Eliminare fişiere temporare',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstruire date de securitate echipă denormalizate',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Derulati campania Nightly Mass',
'LBL_ASYNCMASSUPDATE' => 'Realizare actualizări în masă asincrone',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Activati procesul Nightly pt email-urile returnate companiei',
'LBL_PRUNEDATABASE' => 'Baza de date Prune la data de 1 a lunii',
'LBL_TRIMTRACKER' => 'Tabele Prune Tracker',
'LBL_PROCESSWORKFLOW' => 'Prelucrati activitati Workflow',
'LBL_PROCESSQUEUE' => 'Activati raportul de sarcini programat',
'LBL_UPDATETRACKERSESSIONS' => 'Update tabele tracker session',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Creați perioadele viitoare de timp',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Rulare trimitere mementouri e-mail',
'LBL_CLEANJOBQUEUE' => 'Curăţare coadă de operaţii',
'LBL_CLEANOLDRECORDLISTS' => 'Curăţare liste înregistrări vechi',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

