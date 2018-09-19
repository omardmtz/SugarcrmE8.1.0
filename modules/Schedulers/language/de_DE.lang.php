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
'LBL_OOTB_WORKFLOW'		=> 'Workflow-Aufgaben verarbeiten',
'LBL_OOTB_REPORTS'		=> 'Berichte-Aufgaben verarbeiten',
'LBL_OOTB_IE'			=> 'Eingehende E-Mail-Konten prüfen',
'LBL_OOTB_BOUNCE'		=> 'Unzustellbare Kampagnen-E-Mails verarbeiten (Nacht)',
'LBL_OOTB_CAMPAIGN'		=> 'Kampagnen-Massenmails versenden (Nacht)',
'LBL_OOTB_PRUNE'		=> 'Datenbank am 1. des Monats säubern',
'LBL_OOTB_TRACKER'		=> 'Tracker-Tabellen säubern',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Alte Datensatzlisten kürzen',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Temporäre Dateien entfernen',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Diagnose-Tool-Dateien entfernen',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Temporäre PDF-Dateien entfernen',
'LBL_UPDATE_TRACKER_SESSIONS' => 'tracker_sessions-Tabelle aktualisieren',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'E-Mail-Erinnerungsbenachrichtigungen ausführen',
'LBL_OOTB_CLEANUP_QUEUE' => 'Aufgaben-Warteschlange bereinigen',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Zukünftige Zeiträume anlegen',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent-Artikel aktualisieren.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Genehmigte Artikel veröffentlichen & Artikel aus der Wissensdatenbank auslaufen lassen.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Geplante Aufgaben für Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Denormalisierte Team-Sicherheitsdaten neu erstellen',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervall:',
'LBL_LIST_LIST_ORDER' => 'Geplante Aufgaben:',
'LBL_LIST_NAME' => 'Geplante Aufgabe:',
'LBL_LIST_RANGE' => 'Bereich:',
'LBL_LIST_REMOVE' => 'Entfernen:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Aufgaben-Liste:',
'LBL_LIST_EXECUTE_TIME' => 'Wird gestartet am:',
// human readable:
'LBL_SUN'		=> 'Sonntag',
'LBL_MON'		=> 'Montag',
'LBL_TUE'		=> 'Dienstag',
'LBL_WED'		=> 'Mittwoch',
'LBL_THU'		=> 'Donnerstag',
'LBL_FRI'		=> 'Freitag',
'LBL_SAT'		=> 'Samstag',
'LBL_ALL'		=> 'Jeden Tag',
'LBL_EVERY_DAY'	=> 'Jeden Tag',
'LBL_AT_THE'	=> 'Am',
'LBL_EVERY'		=> 'alle',
'LBL_FROM'		=> 'Von',
'LBL_ON_THE'	=> 'Um',
'LBL_RANGE'		=> 'an',
'LBL_AT' 		=> 'um',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'und',
'LBL_MINUTES'	=> 'Minuten',
'LBL_HOUR'		=> 'Stunden',
'LBL_HOUR_SING'	=> 'Stunde',
'LBL_MONTH'		=> 'Monat',
'LBL_OFTEN'		=> 'So oft wie möglich.',
'LBL_MIN_MARK'	=> 'Minuten nach',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'h',
'LBL_DAY_OF_MONTH' => 'Datum',
'LBL_MONTHS' => 'Monat',
'LBL_DAY_OF_WEEK' => 'Tag',
'LBL_CRONTAB_EXAMPLES' => 'Oben wird die Standard-crontab-Notierung verwendet.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Die cron-Spezifikationen laufen über die Server-Zeitzone (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Bitte die Zeitplaner-Ausführungszeit entsprechend definieren.',
// Labels
'LBL_ALWAYS' => 'Immer',
'LBL_CATCH_UP' => 'Ausführen, wenn versäumt',
'LBL_CATCH_UP_WARNING' => 'Deaktivieren, wenn die Ausführung dieses Auftrags länger dauert.',
'LBL_DATE_TIME_END' => 'Enddatum & -zeit',
'LBL_DATE_TIME_START' => 'Startdatum & -zeit',
'LBL_INTERVAL' => 'Intervall',
'LBL_JOB' => 'Auftrag',
'LBL_JOB_URL' => 'Auftrags-URL',
'LBL_LAST_RUN' => 'Zuletzt erfolgreich ausgeführt',
'LBL_MODULE_NAME' => 'Sugar-Zeitplaner',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar-Zeitplaner',
'LBL_MODULE_TITLE' => 'Zeitplaner',
'LBL_NAME' => 'Auftragsname',
'LBL_NEVER' => 'Nie',
'LBL_NEW_FORM_TITLE' => 'Neuer Plan',
'LBL_PERENNIAL' => 'andauernd',
'LBL_SEARCH_FORM_TITLE' => 'Augabenplaner-Suche',
'LBL_SCHEDULER' => 'Geplante Aufgabe:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Aktiv von',
'LBL_TIME_TO' => 'Aktiv bis',
'LBL_WARN_CURL_TITLE' => 'cURL-Warnung:',
'LBL_WARN_CURL' => 'Warnung:',
'LBL_WARN_NO_CURL' => 'In diesem System sind die cURL Bibliotheken im PHP Modul nicht aktiviert (--with-curl=/pfad/zu/curl_library). Bitte kontaktieren Sie den Administrator zur Lösung dieses Problems. Ohne diese Funktionalität kann der Zeitplaner die Augaben nicht einreihen.',
'LBL_BASIC_OPTIONS' => 'Grundlegende Einrichtung',
'LBL_ADV_OPTIONS'		=> 'Erw. Optionen',
'LBL_TOGGLE_ADV' => 'Erw. Optionen anzeigen',
'LBL_TOGGLE_BASIC' => 'Grundlegende Optionen anzeigen',
// Links
'LNK_LIST_SCHEDULER' => 'Zeitplaner',
'LNK_NEW_SCHEDULER' => 'Neue Aufgabe',
'LNK_LIST_SCHEDULED' => 'Geplante Aufgaben',
// Messages
'SOCK_GREETING' => "Dies ist die Oberfläche für den Sugar-Zeitplaner. 
[ Verfügbare daemon-Befehle: start|restart|shutdown|status ]
Zum Abbrechen schreiben Sie 'quit'. Um den Dienst zu stoppen, 'shutdown'.
",
'ERR_DELETE_RECORD' => 'Zum Löschen des Plans muss eine Datensatznummer angegeben werden.',
'ERR_CRON_SYNTAX' => 'Ungültige Cron-Syntax',
'NTC_DELETE_CONFIRMATION' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?',
'NTC_STATUS' => 'Zum Entfernen dieses Plans aus der Zeitplaner-Dropdown-Liste setzen Sie den Status auf "inaktiv"',
'NTC_LIST_ORDER' => 'Legen Sie die Reihenfolge fest, in der dieser Plan in der Zeitplaner-Dropdown-Liste erscheinen soll',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Konfiguration des Windows-Aufgabenplaners',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Konfiguration eines crontab-Eintrags',
'LBL_CRON_LINUX_DESC' => 'Fügen Sie diese Zeile Ihrem Crontab hinzu:',
'LBL_CRON_WINDOWS_DESC' => 'Erstellen Sie eine Batch-Datei mt den folgenden Befehlen:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Aktive Aufgaben',
'LBL_EXECUTE_TIME'			=> 'Ausführungszeit',

//jobstrings
'LBL_REFRESHJOBS' => 'Aufgaben aktualisieren',
'LBL_POLLMONITOREDINBOXES' => 'Eingehende E-Mail-Konten prüfen',
'LBL_PERFORMFULLFTSINDEX' => 'Volltextsuche Index-System',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Temporäre PDF-Dateien entfernen',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Genehmigte Artikel veröffentlichen & Artikel aus der Wissensdatenbank auslaufen lassen.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch Warteschlangen-Planer',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Diagnose-Tool-Dateien entfernen',
'LBL_SUGARJOBREMOVETMPFILES' => 'Temporäre Dateien entfernen',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Denormalisierte Team-Sicherheitsdaten neu erstellen',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Run Nightly Mass Email Campaigns',
'LBL_ASYNCMASSUPDATE' => 'Asynchrone Massen-Updates durchführen',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Unzustellbare Kampagnen-E-Mails verarbeiten (Nacht)',
'LBL_PRUNEDATABASE' => 'Prune Database on 1st of Month',
'LBL_TRIMTRACKER' => 'Tracker-Tabellen säubern',
'LBL_PROCESSWORKFLOW' => 'Workflow-Aufgaben verarbeiten',
'LBL_PROCESSQUEUE' => 'Berichte-Aufgaben verarbeiten',
'LBL_UPDATETRACKERSESSIONS' => 'Tracker-Sitzungstabellen aktualisieren',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Zukünftige Zeiträume anlegen',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Senden der E-Mail-Erinnerungen ausführen',
'LBL_CLEANJOBQUEUE' => 'Auftrags-Warteschlange bereinigen',
'LBL_CLEANOLDRECORDLISTS' => 'Alte Datensatzlisten bereinigen',
'LBL_PMSEENGINECRON' => 'Advanced Workflow-Zeitplaner',
);

