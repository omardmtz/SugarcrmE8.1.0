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
    'LBL_MODULE_NAME' => 'Jobbkø',
    'LBL_MODULE_NAME_SINGULAR' => 'Jobbkø',
    'LBL_MODULE_TITLE' => 'Jobbkø: hjemme',
    'LBL_MODULE_ID' => 'Jobbkø',
    'LBL_TARGET_ACTION' => 'Handling',
    'LBL_FALLIBLE' => 'Feilbar',
    'LBL_RERUN' => 'Kjør på nytt',
    'LBL_INTERFACE' => 'Grensesnitt',
    'LINK_SCHEDULERSJOBS_LIST' => 'Vis jobbkø',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfigurasjon',
    'LBL_CONFIG_PAGE' => 'Innstillinger for jobbkø',
    'LBL_JOB_CANCEL_BUTTON' => 'Avbryt',
    'LBL_JOB_PAUSE_BUTTON' => 'Pause',
    'LBL_JOB_RESUME_BUTTON' => 'Gjenoppta',
    'LBL_JOB_RERUN_BUTTON' => 'Sett tilbake i køen',
    'LBL_LIST_NAME' => 'Navn',
    'LBL_LIST_ASSIGNED_USER' => 'Anmodet av',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Løsning',
    'LBL_NAME' => 'Jobbnavn',
    'LBL_EXECUTE_TIME' => 'Utførelsetid',
    'LBL_SCHEDULER_ID' => 'Planlegger',
    'LBL_STATUS' => 'Status',
    'LBL_RESOLUTION' => 'Resultat',
    'LBL_MESSAGE' => 'Meldinger',
    'LBL_DATA' => 'Data',
    'LBL_REQUEUE' => 'Forsøk på nytt ved feil',
    'LBL_RETRY_COUNT' => 'Maks antall nye forsøk',
    'LBL_FAIL_COUNT' => 'Feil',
    'LBL_INTERVAL' => 'Minimumsintervall mellom forsøk',
    'LBL_CLIENT' => 'Tilhørende klient',
    'LBL_PERCENT' => 'Prosent ferdig',
    'LBL_JOB_GROUP' => 'Jobb gruppe',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Løsning satt i kø',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Løsningsportal',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Løsning fullført',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Løsning mislyktes',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Løsning kansellert',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Løsning kjører',
    // Errors
    'ERR_CALL' => "Kan ikke kjøre funksjon: %s",
    'ERR_CURL' => "Ingen CURL - kan ikke kjøre URL-jobber",
    'ERR_FAILED' => "Det har oppstått en feil, vennligst sjekk PHP-logger og sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s i %s på linje %d",
    'ERR_NOUSER' => "Ingen Bruker-ID definert for jobben",
    'ERR_NOSUCHUSER' => "Bruker-ID %s ikke funnet",
    'ERR_JOBTYPE' => "Ukjent jobbtype: %s",
    'ERR_TIMEOUT' => "Tvunget feil på tidsavbrudd",
    'ERR_JOB_FAILED_VERBOSE' => 'Jobb %1$s (%2$s) feilet i CRON-kjøring',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Kan ikke laste bean med ID: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Kunne ikke finne handler for ruten %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Utvidelse for køen er ikke installert',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Noen felt er tomme',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Innstillinger for jobbkø',
    'LBL_CONFIG_MAIN_SECTION' => 'Hovedkonfigurasjon',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman-konfigurasjon',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP-konfigurasjon',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs-konfigurasjon',
    'LBL_CONFIG_SERVERS_TITLE' => 'Konfigurasjonshjelp for jobbkøen',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Seksjon for hovedkonfigurasjon.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> – bruk bare én prosess for arbeidere.</li>
    <li><i>Parallell</i> – bruk noen få prosesser for arbeidere.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Standardkø</i> – denne bruker bare Sugars database uten noen meldingskø.</li>
    <li><i>Amazon SQS</i> – Amazon Simple Queue Service er en distribuert kømeldingstjeneste fra Amazon.com.
    Den støtter programmeringssending av meldinger via nettjenesteprogrammer som en måte å kommunisere på Internett på.</li>
    <li><i>RabbitMQ</i> – er et meglerprogram med åpen kildekode (noen ganger kalt meldingsorientert mellomvare) som implementerer Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> – er et programrammeverk med åpen kildekode som er utformet for å distribuere passende dataoppgaver til flere datamaskiner, slik at store oppgaver kan utføres raskere.</li>
    <li><i>Umiddelbar</i> – som standardkøen, men den kjører oppgavene umiddelbart etter de legges til.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS-konfigurasjonshjelp',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Seksjon for Amazon SQS-konfigurasjon.</b></p> <ul><li>Tilgangsnøkkel-ID: <i>Angi tilgangsnøkkel-ID-nummeret for Amazon SQS</i></li> <li>Hemmelig tilgangsnøkkel: <i>Angi den hemmelige tilgangsnøkkelen for Amazon SQS</i></li> <li>Region: <i>Angi regionen for Amazon SQS-serveren</i></li> <li>Kønavn: <i>Angi kønavnet for Amazon SQS-serveren</i></li></ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP-konfigurasjonshjelp',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Seksjon for AMQP-konfigurasjon.</b></p> <ul><li>Server-URL: <i>Angi meldingkøens server-URL.</i></li>     <li>Pålogging: <i>Angi påloggingsinformasjonen for RabbitMQ</i></li>     <li>Passord: <i>Angi passordet for RabbitMQ</i></li></ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman-konfigurasjonshjelp',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Seksjon for Gearman-konfigurasjon.</b></p>
<ul>
    <li>Server-URL: <i>Angi meldingkøens server-URL.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Server-URL',
    'LBL_LOGIN' => 'Pålogging',
    'LBL_ACCESS_KEY' => 'Tilgangsnøkkel-ID',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Hemmelig tilgangsnøkkel',
    'LBL_QUEUE_NAME' => 'Adapternavn',
);
