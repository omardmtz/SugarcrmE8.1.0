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
    'LBL_MODULE_NAME' => 'Jobkø',
    'LBL_MODULE_NAME_SINGULAR' => 'Jobkø',
    'LBL_MODULE_TITLE' => 'Jobkø: Hjem',
    'LBL_MODULE_ID' => 'Jobkø',
    'LBL_TARGET_ACTION' => 'Handling',
    'LBL_FALLIBLE' => 'Fejlbarlig',
    'LBL_RERUN' => 'Kør igen',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Se Jobkø',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguration',
    'LBL_CONFIG_PAGE' => 'Jobkø indstillinger',
    'LBL_JOB_CANCEL_BUTTON' => 'Annullér',
    'LBL_JOB_PAUSE_BUTTON' => 'Pause',
    'LBL_JOB_RESUME_BUTTON' => 'Genoptag',
    'LBL_JOB_RERUN_BUTTON' => 'Requeue',
    'LBL_LIST_NAME' => 'Navn',
    'LBL_LIST_ASSIGNED_USER' => 'Anmodet om',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Opløsning',
    'LBL_NAME' => 'Jobnavn',
    'LBL_EXECUTE_TIME' => 'Udfør tid',
    'LBL_SCHEDULER_ID' => 'Planlægger',
    'LBL_STATUS' => 'Job status',
    'LBL_RESOLUTION' => 'Løsning',
    'LBL_MESSAGE' => 'Meddelelser',
    'LBL_DATA' => 'Job data',
    'LBL_REQUEUE' => 'Forsøg igen ved fejl',
    'LBL_RETRY_COUNT' => 'Maksimum antal forsøg',
    'LBL_FAIL_COUNT' => 'Fejl',
    'LBL_INTERVAL' => 'Minimum interval mellem forsøg',
    'LBL_CLIENT' => 'Ejer',
    'LBL_PERCENT' => 'Procent færdig',
    'LBL_JOB_GROUP' => 'Jobgruppe',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Løsning sat i kø',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Delvis løsning',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Løsning afsluttet',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Løsningsfejl',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Løsning annulleret',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Løsning kører',
    // Errors
    'ERR_CALL' => "Kan ikke kalde funktionen: %s",
    'ERR_CURL' => "Ingen CURL - kan ikke køre URL jobs",
    'ERR_FAILED' => "Uventet fejl. Venligst tjek PHP logs og sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s i %s på linie %d",
    'ERR_NOUSER' => "Intet bruger id angivet for jobbet",
    'ERR_NOSUCHUSER' => "Bruger id %s ikke fundet",
    'ERR_JOBTYPE' => "Ukendt job type: %s",
    'ERR_TIMEOUT' => "Fejl pga. timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Job %1$s (%2$s) fejlede under CRON kørsel',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Kan ikke indlæse bønne med id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Kan ikke finde føreren af rute %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Forlængelse af denne kø er ikke installeret',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Nogle af felterne er tomme',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Jobkø indstillinger',
    'LBL_CONFIG_MAIN_SECTION' => 'Primær konfiguration',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman konfiguration',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP konfiguration',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs konfiguration',
    'LBL_CONFIG_SERVERS_TITLE' => 'Jobkø konfiguration Hjælp',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Primær konfiguration Sektion.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> - brug kun én proces for arbejdstagere.</li>
    <li><i>Parallel</i> - brug få processer for arbejdstagere.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Standard kø</i> - Dette vil kun bruge Sugars Database uden nogen besked kø.</li>
    <li><i>Amazon SQS</i> - Amazon Simple kø Service er en distribueret kø
beskedtjenesten introduceret af Amazon.com.
Den understøtter programmatisk afsendelse af beskeder via web service-applikationer som en måde til at
    kommunikere over internettet.</li>
    <li><i>RabbitMQ</i> - er opensource besked broker software (også kaldet besked-orienteret middleware)
    der implementerer Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> - er et opensource applikation framework, der er designet til at distribuere passende computer
     opgaver til flere computere, så store opgaver kan udføres hurtigere.</li>
    <li><i>Omgående</i> - Ligesom standardkøen, men udfører opgaven umiddelbart efter tilføjelsen.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS konfiguration Hjælp',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS konfigurationsafsnit.</b></p>
<ul>
    <li>Access Key ID: <i>Indtast din adgangsnøgle id-nummer til Amazon SQS</i></li>
    <li>Secret Access Key: <i>Indtast din hemmelige adgangsnøgle til Amazon SQS</i></li>
    <li>Region: <i>Indtast regionen for Amazon SQS server</i></li>
    <li>Queue Name: <i>Indtast kønavn for Amazon SQS server</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP konfiguration Hjælp',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP konfigurationsafsnit.</b></p>
<ul>
    <li>Server URL: <i>Indtast din besked kø servers URL.</i></li>
    <li>Login: <i>Indtast dit login til RabbitMQ</i></li>
    <li>Password: <i>Indtast din adgangskode til RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman konfiguration Hjælp',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman konfigurationsafsnit.</b></p>
<ul>
    <li>Server URL: <i>Indtast din besked kø serverens URL.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Server URL',
    'LBL_LOGIN' => 'Login',
    'LBL_ACCESS_KEY' => 'Adgangskode ID',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Hemmelig adgangskode',
    'LBL_QUEUE_NAME' => 'Adapter navn',
);
