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
    'LBL_MODULE_NAME' => 'Wachtrij voor taak',
    'LBL_MODULE_NAME_SINGULAR' => 'Wachtrij voor taak',
    'LBL_MODULE_TITLE' => 'Wachtrij voor taak: Home',
    'LBL_MODULE_ID' => 'Wachtrij voor taak',
    'LBL_TARGET_ACTION' => 'Actie',
    'LBL_FALLIBLE' => 'Feilbaar',
    'LBL_RERUN' => 'Opnieuw uitvoeren',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Bekijk Wachtrij voor taak',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuratie',
    'LBL_CONFIG_PAGE' => 'Wachtrij voor taak Instellingen',
    'LBL_JOB_CANCEL_BUTTON' => 'Annuleren',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauzeren',
    'LBL_JOB_RESUME_BUTTON' => 'Hervatten',
    'LBL_JOB_RERUN_BUTTON' => 'Opnieuw in de rij zetten',
    'LBL_LIST_NAME' => 'Naam',
    'LBL_LIST_ASSIGNED_USER' => 'Verzocht door',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Oplossing',
    'LBL_NAME' => 'Naam taak',
    'LBL_EXECUTE_TIME' => 'Uitvoeringstijd',
    'LBL_SCHEDULER_ID' => 'Taakplanner',
    'LBL_STATUS' => 'Status taak',
    'LBL_RESOLUTION' => 'Resultaat',
    'LBL_MESSAGE' => 'Berichten',
    'LBL_DATA' => 'Gegevens taak',
    'LBL_REQUEUE' => 'Opnieuw proberen bij mislukken',
    'LBL_RETRY_COUNT' => 'Maximaal aantal nieuwe pogingen',
    'LBL_FAIL_COUNT' => 'Fouten',
    'LBL_INTERVAL' => 'Minimale interval tussen de pogingen',
    'LBL_CLIENT' => 'Eigenaar client',
    'LBL_PERCENT' => 'Percentage compleet',
    'LBL_JOB_GROUP' => 'Job groep',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Oplossing in de wachtrij',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Gedeeltelijke oplossing',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Oplossing voltooid',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Oplossing mislukt',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Oplossing geannuleerd',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Oplossing wordt uitgevoerd',
    // Errors
    'ERR_CALL' => "Kan functie niet oproepen: %s",
    'ERR_CURL' => "Geen CURL - kan geen URL taken uitvoeren",
    'ERR_FAILED' => "Onverwachte fout, controleer PHP logs en sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s over %s op regel %d",
    'ERR_NOUSER' => "Geen user ID gespecificeerd voor de JOB",
    'ERR_NOSUCHUSER' => "Gebruiker-ID %s niet gevonden",
    'ERR_JOBTYPE' => "Onbekend type taak: %s",
    'ERR_TIMEOUT' => "Gedwongen fout na time-out",
    'ERR_JOB_FAILED_VERBOSE' => 'Taak %1$s (%2$s) mislukt tijdens CRON uitvoer',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Kan bean niet laden met id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Kan handler niet vinden voor route %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Extensie voor deze rij is niet geïnstalleerd',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Sommige velden zijn leeg',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Wachtrij voor taak Instellingen',
    'LBL_CONFIG_MAIN_SECTION' => 'Hoofdconfiguratie',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman configuratie',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP configuratie',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs configuratie',
    'LBL_CONFIG_SERVERS_TITLE' => 'Hulp bij configuratie takenrij',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Hoofdstuk hoofdconfiguratie.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standaard</i> - slechts één proces gebruiken voor personeel.</li>
    <li><i>Parallel</i> - enkele processen gebruiken voor personeel.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Standaard rij</i> - Hiervoor wordt de database van Sugar gebruikt zonder berichtenrij.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service is een gedistribueerde rijberichtenservice die door Amazon.com is geïntroduceerd.
    Het ondersteunt het programmagericht verzenden van berichten via webservicetoepassingen als manier om via het internet te communiceren.</li>
    <li><i>RabbitMQ</i> - is open source berichtbrokersoftware (soms ook wel berichtgeoriënteerde middleware genoemd)
    die het Advanced Message Queuing Protocol (AMQP) implementeert.</li>
    <li><i>Gearman</i> - is een open source toepassing framework dat is ontworpen om geschikte computertaken te verspreiden naar meerdere computers zodat grote taken sneller gedaan kunnen worden.</li>
    <li><i>Onmiddellijk</i> - Hetzelfde als de standaard rij, maar voert taken onmiddellijk uit nadat ze zijn toegevoegd.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Hulp bij Amazon SQS configuratie',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Hoofdstuk over Amazon SQS configuratie.</b></p>
<ul>
    <li>Toegangssleutel-ID: <i>Voer uw toegangssleutel-id in voor Amazon SQS</i></li>
    <li>Geheime toegangssleutel: <i>Voer uw geheime toegangssleutel in voor Amazon SQS</i></li>
    <li>Regio: <i>Voer de regio van Amazon SQS server in</i></li>
    <li>Rijnaam: <i>Voer de rijnaam in van de Amazon SQS server</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Hulp bij AMQP configuratie',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Hoofdstuk over AMQP configuratie</b></p>
<ul>
    <li>Server-URL: <i>Voer uw bericht in van de URL van de rijserver.</i></li>
    <li>Inlog: <i>Voer uw inlog in voor RabbitMQ</i></li>
    <li>Wachtwoord: <i>Voer uw wachtwoord in voor RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Hulp bij Gearman configuratie',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Hoofdstuk Gearman configuratie.</b></p>
<ul>
    <li>Server URL: <i>Voer uw bericht in voor de URL van de rijserver.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Loper',
    'LBL_SERVER_URL' => 'Server URL',
    'LBL_LOGIN' => 'Inlog',
    'LBL_ACCESS_KEY' => 'Toegangssleutel-ID',
    'LBL_REGION' => 'Regio',
    'LBL_ACCESS_KEY_SECRET' => 'Geheime toegangssleutel',
    'LBL_QUEUE_NAME' => 'Adapter Naam',
);
