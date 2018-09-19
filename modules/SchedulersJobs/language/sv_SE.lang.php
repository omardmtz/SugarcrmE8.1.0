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
    'LBL_MODULE_NAME' => 'Jobbkö',
    'LBL_MODULE_NAME_SINGULAR' => 'Jobbkö',
    'LBL_MODULE_TITLE' => 'Jobbkö: Hem',
    'LBL_MODULE_ID' => 'Jobbkö',
    'LBL_TARGET_ACTION' => 'Åtgärd',
    'LBL_FALLIBLE' => 'Ofullkomlig',
    'LBL_RERUN' => 'Repris',
    'LBL_INTERFACE' => 'Gränssnitt',
    'LINK_SCHEDULERSJOBS_LIST' => 'Visa jobbkö',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguration',
    'LBL_CONFIG_PAGE' => 'Jobbköinställningar',
    'LBL_JOB_CANCEL_BUTTON' => 'Avbryt',
    'LBL_JOB_PAUSE_BUTTON' => 'Paus',
    'LBL_JOB_RESUME_BUTTON' => 'Återuppta',
    'LBL_JOB_RERUN_BUTTON' => 'Återköa',
    'LBL_LIST_NAME' => 'Namn',
    'LBL_LIST_ASSIGNED_USER' => 'Begärd av',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Upplösning',
    'LBL_NAME' => 'Jobbnamn',
    'LBL_EXECUTE_TIME' => 'Exekveringstid',
    'LBL_SCHEDULER_ID' => 'Schemaläggare',
    'LBL_STATUS' => 'Jobbstatus',
    'LBL_RESOLUTION' => 'Resultat',
    'LBL_MESSAGE' => 'Meddelanden',
    'LBL_DATA' => 'Jobb Data',
    'LBL_REQUEUE' => 'Försök igen om misslyckat',
    'LBL_RETRY_COUNT' => 'Maximal återförsök',
    'LBL_FAIL_COUNT' => 'Misslyckanden',
    'LBL_INTERVAL' => 'Minsta intervall mellan försök',
    'LBL_CLIENT' => 'Äger klient',
    'LBL_PERCENT' => 'Procent klart',
    'LBL_JOB_GROUP' => 'Jobb grupp',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Lösning köad',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Delvis löst',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Lösning klar',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Lösning misslyckades',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Lösning avbruten',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Lösning körs',
    // Errors
    'ERR_CALL' => "Kan inte anropa funktion: %s",
    'ERR_CURL' => "Ingen CURL - kan inte köra URL jobb",
    'ERR_FAILED' => "Oväntat fel, vänligen kolla PHP loggar och sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s i %s på rad %d",
    'ERR_NOUSER' => "Ingen specificerad AnvändarID för jobbet",
    'ERR_NOSUCHUSER' => "AnvändarID %s hittades inte",
    'ERR_JOBTYPE' => "Okänd jobbtyp: %s",
    'ERR_TIMEOUT' => "Forcerat misslyckande av timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Jobb %1$s (%2$s) misslyckades i CRON körningen',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Kan inte ladda bean med id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Kan inte hitta handler för rutt %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Tillägget för denna kö är inte installerat',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Vissa fält är tomma',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Jobbköinställningar',
    'LBL_CONFIG_MAIN_SECTION' => 'Huvudkonfiguration',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman-konfiguration',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP-konfiguration',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs-konfiguration',
    'LBL_CONFIG_SERVERS_TITLE' => 'Hjälp för konfiguration av jobbkö',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Huvudsaklig konfiguration</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> - använd bara en process för arbetare.</li>
    <li><i>Parallell</i> - använd ett antal processer för arbetare.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Standardkö</i> - Detta kommer bara att använda Sugars databas utan meddelandekö.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service är ett distribuerat kösystem för meddelanden gjort av Amazon.com.
    Det har stöd för sändning av meddelanden direkt från kod via webbtjänstapplikationer som ett sätt att kommunicera över internet.</li>
    <li><i>RabbitMQ</i> - är en open source meddelandeupphandlare (ibland kallat message-oriented middleware, ung. meddelandeorienterad mellanmjukvara) som implementerar Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> - är ett open source ramverk designat för att distribuera tjänster mellan flera datorer för att optimera stora uppgifter.</li>
    <li><i>Omedelbar</i> - Samma som standardkön fast uppgiften körs direkt efter den lagts till.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS Konfigurationshjälp',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS-Konfiguration.</b></p>
<ul>
    <li>Accessnyckel-ID: <i>Ange ditt Accessnyckel-ID för Amazon SQS</i></li>
    <li>Hemlig accessnyckel: <i>Ange din hemliga accessnyckel för Amazon SQS</i></li>
    <li>Region: <i>Ange region för Amazon SQS server</i></li>
    <li>Könamn: <i>Ange könamn för Amazon SQS server</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP-konfigurationshjälp',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP-Konfiguration.</b></p>
<ul>
    <li>Server-URL: <i>Ange URL till din meddelandeköserver.</i></li>
    <li>Login: <i>Ange login till RabbitMQ</i></li>
    <li>Lösenord: <i>Ange lösenord till RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman-konfigurationshjälp',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman-konfiguration.</b></p>
<ul>
    <li>Server URL: <i>Ange URL till din meddelandeköserver.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Löpare',
    'LBL_SERVER_URL' => 'Server-URL',
    'LBL_LOGIN' => 'Logga in',
    'LBL_ACCESS_KEY' => 'Accessnyckel-ID',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Hemlig accessnyckel',
    'LBL_QUEUE_NAME' => 'Adapternamn',
);
