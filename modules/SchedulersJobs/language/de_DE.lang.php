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

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Auftrags-Warteschleife',
    'LBL_MODULE_NAME_SINGULAR' => 'Auftrags-Warteschleife',
    'LBL_MODULE_TITLE' => 'Auftrags-Warteschleife: Startseite',
    'LBL_MODULE_ID' => 'Auftrags-Warteschleife',
    'LBL_TARGET_ACTION' => 'Aktion',
    'LBL_FALLIBLE' => 'Fehlbar',
    'LBL_RERUN' => 'Erneut ausführen',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Auftrags-Warteschleife anzeigen',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguration',
    'LBL_CONFIG_PAGE' => 'Auftrags-Warteschleife-Einstellungen',
    'LBL_JOB_CANCEL_BUTTON' => 'Abbrechen',
    'LBL_JOB_PAUSE_BUTTON' => 'Anhalten',
    'LBL_JOB_RESUME_BUTTON' => 'Fortsetzen',
    'LBL_JOB_RERUN_BUTTON' => 'In die Auftragswarteschleife zurücksetzen',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_ASSIGNED_USER' => 'Beantragt durch',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Lösung',
    'LBL_NAME' => 'Auftragsname',
    'LBL_EXECUTE_TIME' => 'Ausführungszeit',
    'LBL_SCHEDULER_ID' => 'Zeitplaner',
    'LBL_STATUS' => 'Auftrags-Status',
    'LBL_RESOLUTION' => 'Resultat',
    'LBL_MESSAGE' => 'Nachrichten',
    'LBL_DATA' => 'Auftragsdaten',
    'LBL_REQUEUE' => 'Bei Fehler erneut versuchen',
    'LBL_RETRY_COUNT' => 'Maximale Neuverscuhe',
    'LBL_FAIL_COUNT' => 'Fehlgeschlagene Vorgänge',
    'LBL_INTERVAL' => 'Mindestintervalle zwischen Versuchen',
    'LBL_CLIENT' => 'Zuständiger Client',
    'LBL_PERCENT' => 'Prozent fertig',
    'LBL_JOB_GROUP' => 'Auftragsgruppe',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Lösung in der Warteschleife',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Teillösung',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Komplette Lösung',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Lösung fehlgeschlagen',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Lösung annuliert',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Lösung wird ausgeführt',
    // Errors
    'ERR_CALL' => "Kann Funktion %s nicht aufrufen",
    'ERR_CURL' => "Kein CURL - kann URL-Aufträge nicht ausführen",
    'ERR_FAILED' => "Unerwarteter Fehler. Bitte PHP-Protokoll bzw. sugarcrm.log überprüfen",
    'ERR_PHP' => "%s [%d]: %s in %s auf Zeile %d",
    'ERR_NOUSER' => "Keine Benutzer-ID für diesen Auftrag angegeben",
    'ERR_NOSUCHUSER' => "Benutzer-ID %s nicht gefunden",
    'ERR_JOBTYPE' => "Unbekannter Auftragtyp: %s",
    'ERR_TIMEOUT' => "Zwangsversagen bei Timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Auftrag %1$s (%2$s) in CRON-Lauf fehlgeschlagen',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Bean mit ID %s kann nicht geladen werden',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Für Route %s kann kein Handler gefunden werden',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Erweiterung für diese Warteschleife ist nicht installiert',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Einige Felder sind leer',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Auftrags-Warteschleife-Einstellungen',
    'LBL_CONFIG_MAIN_SECTION' => 'Hauptkonfiguration',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman-Konfiguration',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP-Konfiguration',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs-Konfiguration',
    'LBL_CONFIG_SERVERS_TITLE' => 'Hilfe für Konfiguration der Auftragswarteschleife',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Hauptkonfigurationsbereich.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> - nur einen Prozess für Arbeiter verwenden.</li>
    <li><i>Parallel</i> - mehrere Prozesse für Arbeiter verwenden.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Standardwarteschleife</i> - Greift nur auf die Datenbank von Sugar zu, ohne Nachrichtenwarteschlange.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service ist ein Verteilerwarteschleife-Nachrichtendienst von Amazon.com.
  Es unterstützt das programmatische Versenden von Nachrichten über Web-Service-Anwendungen als eine Möglichkeit der Internetkommunikation.</li>
    <li><i>RabbitMQ</i> - 
ist eine Open Source-Message Broker-Software (manchmal auch Message Oriented Middleware bezeichnet), die das
     Advanced Message Queuing-Protokoll (AMQP) implentiert.</li>
    <li><i>Gearman</i> - ist ein Open-Source- Anwendungsframework, entwickelt, um geeignete Computeraufgaben auf mehrere Computer zu verteilen, sodass große Aufgaben schneller ausgeführt werden können.</li>
    <li><i>Immediate</i> - Gleich wie die Standard-Warteschleife, nur dass die Aufgaben sofort nach der Hinzufügung ausgeführt werden.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS-Konfigurationshilfe',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS-Konfigurationsbereich.</b></p> <ul><li>Zugangsschlüssel-ID: <i>Geben Sie Ihre Zugangsschlüssel-ID für Amazon SQS ein</i></li> <li>Geheimer Zugangsschlüssel: <i>Geben Sie Ihren geheimen Zugangsschlüssel für Amazon SQS ein</i></li> <li>Region: <i>Geben Sie die Region des Amazon SQS-Servers ein</i></li> <li>Warteschleifenname: <i>Geben Sie den  Warteschleifennamne von Amazon SQS-Server ein</i></li></ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP-Konfigurationshilfe',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP-Konfigurationsbereich.</b></p> <ul><li>Server-URL: <i>Geben Sie die URL Ihres Nachrichtenwarteschleifen-Servers ein.</i></li>     <li>Anmeldung: <i>Geben Sie Ihre Anmeldeinformationen für RabbitMQ ein</i></li>     <li>Passwort: <i>Geben Sie Ihr Passwort für RabbitMQ ein</i></li></ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman-Konfigurationshilfe',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman-Konfigurationsbereich.</b></p> <ul><li>Server-URL: <i>Geben Sie die URL Ihres Nachrichtenwarteschleifen-Servers ein.</i></li></ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Server-URL',
    'LBL_LOGIN' => 'Login',
    'LBL_ACCESS_KEY' => 'Zugangsschlüssel ID',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Geheimer Zugangsschlüssel',
    'LBL_QUEUE_NAME' => 'Adaptername',
);
