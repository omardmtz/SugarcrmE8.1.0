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
    'LBL_MODULE_NAME' => 'Työjono',
    'LBL_MODULE_NAME_SINGULAR' => 'Työjono',
    'LBL_MODULE_TITLE' => 'Työjono: Alkuun',
    'LBL_MODULE_ID' => 'Työjono',
    'LBL_TARGET_ACTION' => 'Toiminto',
    'LBL_FALLIBLE' => 'Erehtyväinen',
    'LBL_RERUN' => 'Suorita uudelleen',
    'LBL_INTERFACE' => 'Käyttöliittymä',
    'LINK_SCHEDULERSJOBS_LIST' => 'Näytä työjono',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Määrittely',
    'LBL_CONFIG_PAGE' => 'Työjonon asetukset',
    'LBL_JOB_CANCEL_BUTTON' => 'Peruuta',
    'LBL_JOB_PAUSE_BUTTON' => 'Tauko',
    'LBL_JOB_RESUME_BUTTON' => 'Jatka',
    'LBL_JOB_RERUN_BUTTON' => 'Siirrä jonoon',
    'LBL_LIST_NAME' => 'Nimi',
    'LBL_LIST_ASSIGNED_USER' => 'Pyytänyt',
    'LBL_LIST_STATUS' => 'Tila',
    'LBL_LIST_RESOLUTION' => 'Ratkaisuvaihe',
    'LBL_NAME' => 'Nimi',
    'LBL_EXECUTE_TIME' => 'Suoritusaika',
    'LBL_SCHEDULER_ID' => 'Ajastin',
    'LBL_STATUS' => 'Tila:',
    'LBL_RESOLUTION' => 'Tulos',
    'LBL_MESSAGE' => 'Viestit',
    'LBL_DATA' => 'Tehtävän data',
    'LBL_REQUEUE' => 'Yritä uudelleen virhetilanteessa',
    'LBL_RETRY_COUNT' => 'Uudelleenyritysten enimmäismäärä',
    'LBL_FAIL_COUNT' => 'Epäonnistumisia',
    'LBL_INTERVAL' => 'Minimiaika yritysten välissä',
    'LBL_CLIENT' => 'Omistava käyttäjä',
    'LBL_PERCENT' => 'Prosenttia valmiina',
    'LBL_JOB_GROUP' => 'Ryhmä',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Ratkaisu jonossa',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Osittain ratkaistu',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Täysin ratkaistu',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Ratkaisu epäonnistui',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Ratkaisu peruutettu',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Ratkaisua käsitellään',
    // Errors
    'ERR_CALL' => "Ei voida kutsua funktiota: %s",
    'ERR_CURL' => "Ei CURL:a - ei voida ajaa URL-tehtäviä",
    'ERR_FAILED' => "Odottamaton virhe, tarkista PHP:n lokitiedostot ja sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s tiedostossa %s rivillä %d",
    'ERR_NOUSER' => "Käyttäjätunnusta ei ole määritelty tehtävälle",
    'ERR_NOSUCHUSER' => "Käyttäjätunnusta %s ei löytynyt",
    'ERR_JOBTYPE' => "Tuntematon tehtävätyyppi: %s",
    'ERR_TIMEOUT' => "Pakotettu epäonnistuminen aikakatkaisun vuoksi",
    'ERR_JOB_FAILED_VERBOSE' => 'Tehtävä %1$s (%2$s) epäonnistui CRON-ajossa',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Ei voida ladata beania id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Ei löydetä käsittelijää reitille %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Tämän jonon laajennusta ei ole asennettu',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Jotkin kentät ovat tyhjiä',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Työjonon asetukset',
    'LBL_CONFIG_MAIN_SECTION' => 'Ensisijainen määrittely',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman-määrittely',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP-määrittely',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs -määrittely',
    'LBL_CONFIG_SERVERS_TITLE' => 'Työjonon määrittelyn ohje',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Ensisijainen määrittely.</b></p>
<ul>
<li>Ajaja:
<ul>
<li><i>Standardi</i> - käytä vain yhtä prosessia työhön.</li>
<li><i>Rinnakkain</i> - käytä useita prosesseja työhön.</li>
</ul>
</li>
<li>Adapteri:
<ul>
<li><i>Oletusjono<i> - Tämä käyttää vain Sugarin tietokantaa ilman viestijonoa.</li>
<li><i>Amazon SQS</i> - Amazon Simple Queue Service on hajautettu jonoviestintäpalvelu,
jonka tarjoaa Amazon.com.
    Se tukee viestien ohjelmallista lähetystä verkkopalvelusovellusten kautta keinona     
viestiä internetin kautta.</li>
<li><i>RabbitMQ</i> - on avoimen lähdekoodin viestinvälittäjäsovellus (nk. \"message-oriented middleware\"),    
joka käyttää Advanced Message Queuing -protokollaa (AMQP).</li>
<li><i>Gearman</i> - on avoimen lähdekoodin runkojärjestelmä, joka jakaa soveltuvia tietokoneen tehtäviä
useille tietokoneille, jolloin työläät tehtävät voidaan suorittaa nopeammin.</li>
<li><i>Välitön</i> - Kuten oletusjono, mutta tehtävät suoritetaan välittömästi niiden jonoon lisäämisen jälkeen.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS -määrittelyn ohje',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS -määrittely.</b></p>
<ul>
    <li>Käyttöavaimen ID: <i>Syötä Amazon SQS -palvelun käyttöavaimesi ID-numero</i></li>
    <li>Salainen käyttöavain: <i>Syötä Amazon SQS -palvelun salainen käyttöavaimesi</i></li>
    <li>Alue: <i>Syötä Amazon SQS -palvelimen alue<i></li>
    <li>Jonon nimi: <i>Syötä Amazon SQS -palvelimen jonon nimi</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP -määrittelyn ohje',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP-määrittely</b></p>
<ul>
    <li>Palvelimen URL: <i>Syötä viestijonopalvelimesi URL-osoite.</i></li>
    <li>Käyttäjätunnus: <i>Syötä RabbitMQ-palvelun käyttäjätunnus</i></li>
    <li>Salasana: <i>Syötä RabbitMQ-palvelun salasana</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman-määrittelyn ohje',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman-määrittely.</b></p>
<ul>
    <li>Palvelimen URL: <i>Syötä viestijonopalvelimesi URL-osoite.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapteri',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Ajaja',
    'LBL_SERVER_URL' => 'Palvelimen URL',
    'LBL_LOGIN' => 'Käyttäjätunnus',
    'LBL_ACCESS_KEY' => 'Käyttöavaimen ID',
    'LBL_REGION' => 'Alue',
    'LBL_ACCESS_KEY_SECRET' => 'Salainen käyttöavain',
    'LBL_QUEUE_NAME' => 'Adapterin nimi',
);
