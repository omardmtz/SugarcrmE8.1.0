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
    'LBL_MODULE_NAME' => 'Uzdevumu rinda',
    'LBL_MODULE_NAME_SINGULAR' => 'Uzdevumu rinda',
    'LBL_MODULE_TITLE' => 'Uzdevumu rinda: Sākums',
    'LBL_MODULE_ID' => 'Uzdevumu rinda',
    'LBL_TARGET_ACTION' => 'Darbība',
    'LBL_FALLIBLE' => 'No kļūdām nepasargāts',
    'LBL_RERUN' => 'Atkārtota izpilde',
    'LBL_INTERFACE' => 'Saskarne',
    'LINK_SCHEDULERSJOBS_LIST' => 'Skatīt uzdevumu rindu',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfigurācija',
    'LBL_CONFIG_PAGE' => 'Uzdevumu rindas iestatījumi',
    'LBL_JOB_CANCEL_BUTTON' => 'Atcelt',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauze',
    'LBL_JOB_RESUME_BUTTON' => 'Atsākt',
    'LBL_JOB_RERUN_BUTTON' => 'Atgriezt rindā',
    'LBL_LIST_NAME' => 'Nosaukums',
    'LBL_LIST_ASSIGNED_USER' => 'Pieprasīja',
    'LBL_LIST_STATUS' => 'Statuss',
    'LBL_LIST_RESOLUTION' => 'Risinājums',
    'LBL_NAME' => 'Uzdevuma nosaukums',
    'LBL_EXECUTE_TIME' => 'Izpildes laiks',
    'LBL_SCHEDULER_ID' => 'Plānotājs',
    'LBL_STATUS' => 'Uzdevuma statuss',
    'LBL_RESOLUTION' => 'Rezultāts',
    'LBL_MESSAGE' => 'Ziņojumi',
    'LBL_DATA' => 'Uzdevuma dati',
    'LBL_REQUEUE' => 'Atkārtot ja neizdodas',
    'LBL_RETRY_COUNT' => 'Maksimālais atkārtojumu skaits',
    'LBL_FAIL_COUNT' => 'Neizdošanās',
    'LBL_INTERVAL' => 'Minimālais intervāls starp mēģinājumiem',
    'LBL_CLIENT' => 'Klients',
    'LBL_PERCENT' => 'Pabeigtības procents',
    'LBL_JOB_GROUP' => 'Uzdevuma grupa',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Risinājums ievietots rindā',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Daļējs risinājums',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Pabeigts risinājums',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Neizdevies risinājums',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Risinājums atcelts',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Risinājums tiek izpildīts',
    // Errors
    'ERR_CALL' => "Nevar izsaukt funkciju: %s",
    'ERR_CURL' => "Nav pieejams komandrindas rīks (CURL) URL uzdevumu izpildei",
    'ERR_FAILED' => "Negaidīta kļūda, pārbaudiet PHP un SugarCRM žurnālus",
    'ERR_PHP' => "%s [%d]: %s ir %s rindā %d",
    'ERR_NOUSER' => "Uzdevumam nav norādīts lietotāja ID",
    'ERR_NOSUCHUSER' => "Lietotāja ID %s netika atrasts",
    'ERR_JOBTYPE' => "Nezināms uzdevuma tips: %s",
    'ERR_TIMEOUT' => "Laika limita izsīkuma kļūda",
    'ERR_JOB_FAILED_VERBOSE' => 'Uzdevums %1$s (%2$s) neizpildījās CRON sesijā',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nevar ielādēt bean ar id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nevar atrast apstrādes funkciju ceļam %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Paplašinājums šai rindai nav instalēts',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Daži lauki ir tukši',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Uzdevumu rindas iestatījumi',
    'LBL_CONFIG_MAIN_SECTION' => 'Galvenā konfigurācija',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman konfigurācija',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP konfigurācija',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs konfigurācija',
    'LBL_CONFIG_SERVERS_TITLE' => 'Uzdevumu rindas konfigurācijas palīdzība',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Galvenā konfigurācijas sadaļa.</b></p>
<ul>
    <li>Palaidējs:
    <ul>
    <li><i>Standarta</i> - izmanto tikai vienu procesu darbiniekiem.</li>
    <li><i>Paralēlais</i> - izmanto vairākus procesus darbiniekiem.</li>
    </ul>
    </li>
    <li>Adapteris:
    <ul>
    <li><i>Rinda pēc noklusējuma</i> - Izmantos tikai Sugar Datubāzi bez ziņojumu rindas.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service ir izplatītās rindas  ziņojumu pakalpojums, ko piedāvā Amazon.com.
    Tas atbalsta programmējamu ziņojumu nosūtīšanu, izmantojot tīmekļa pakalpojuma lietotnes, kā kominikācijas veidu internetā.</li>
    <li><i>RabbitMQ</i> - ir atvērtā koda paziņojuma brokera programmatūra (ko dažkārt dēvē par uz ziņojumiem orientētu starpprogrammatūru),
    kas ietver Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> - ir atvērtā koda  lietotnes ietvars, kas paredzēts, lai izplatītu atbilstošus datoruzdevumus vairākiem datoriem, lai lielākie uzdevumi varētu tikt paveikti ātrāk.</li>
    <li><i>Tūlītējs</i> - Līdzīgs rindai pēc noklusējuma, bet izpilda uzdevumu uzreiz pēc pievienošanas.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS konfigurācijas palīdzība',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS konfigurācijas sadaļa.</b></p>
<ul>
    <li>Piekļuves atslēgas Key ID: <i>Ievadiet savu Amazon SQS piekļuves atslēgas id numuru</i></li>
    <li>Slepetā piekļuves atslēga: <i>Ievadiet savu Amazon SQS slepeno piekļuves atslēgu</i></li>
    <li>Reģions: <i>Ievadiet Amazon SQS servera reģionu</i></li>
    <li>Rindas nosaukums: <i>Ievadiet Amazon SQS servera rindas nosaukumu</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP konfigurācijas palīdzība',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP konfigurācijas sadaļa.</b></p>
<ul>
    <li>Servera URL: <i>Ievadiet sava ziņojumu rindas servera URL.</i></li>
    <li>Pieteikšanās: <i>Ievadiet savu RabbitMQ pieteikšanos</i></li>
    <li>Parole: <i>Ievadiet savu RabbitMQ paroli</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman konfigurācijas palīdzība',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman konfigurācijas sadaļa.</b></p>
<ul>
    <li>Servera URL: <i>Ievadiet sava ziņojumu rindas servera URL.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapteris',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Palaidējs',
    'LBL_SERVER_URL' => 'Servera URL',
    'LBL_LOGIN' => 'Pieteikšanās',
    'LBL_ACCESS_KEY' => 'Piekļuves atslēgas ID',
    'LBL_REGION' => 'Reģions',
    'LBL_ACCESS_KEY_SECRET' => 'Slepenā piekļuves atslēga',
    'LBL_QUEUE_NAME' => 'Adaptera nosaukums',
);
