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
    'LBL_MODULE_NAME' => 'Redosled poslova',
    'LBL_MODULE_NAME_SINGULAR' => 'Redosled poslova',
    'LBL_MODULE_TITLE' => 'Redosled posla: Početna strana',
    'LBL_MODULE_ID' => 'Redosled poslova',
    'LBL_TARGET_ACTION' => 'Akcija',
    'LBL_FALLIBLE' => 'Sa greškom',
    'LBL_RERUN' => 'Ponovno pokretanje',
    'LBL_INTERFACE' => 'Interfejs',
    'LINK_SCHEDULERSJOBS_LIST' => 'Prikaz redosleda poslova',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguracija',
    'LBL_CONFIG_PAGE' => 'Parametri redosleda poslova',
    'LBL_JOB_CANCEL_BUTTON' => 'Otkaži',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauza',
    'LBL_JOB_RESUME_BUTTON' => 'Nastavi',
    'LBL_JOB_RERUN_BUTTON' => 'Ponovni razmeštaj redosleda',
    'LBL_LIST_NAME' => 'Naziv',
    'LBL_LIST_ASSIGNED_USER' => 'Zahtev od strane',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Rezolucija',
    'LBL_NAME' => 'Naziv posla',
    'LBL_EXECUTE_TIME' => 'Vreme izvršavanja',
    'LBL_SCHEDULER_ID' => 'Planer',
    'LBL_STATUS' => 'Status posla',
    'LBL_RESOLUTION' => 'Rezultat',
    'LBL_MESSAGE' => 'Poruke',
    'LBL_DATA' => 'Podaci posla',
    'LBL_REQUEUE' => 'Ponovni pokušaj nakon greške',
    'LBL_RETRY_COUNT' => 'Maksimalni broj pokušaja',
    'LBL_FAIL_COUNT' => 'Greške',
    'LBL_INTERVAL' => 'Najmanji interval između linija',
    'LBL_CLIENT' => 'Poseduje klijent',
    'LBL_PERCENT' => 'Procentualno završeno',
    'LBL_JOB_GROUP' => 'Grupa posla',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Redosled rezolucije',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Delimična rezolucija',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Potpuna rezolucija',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Neuspela rezolucija',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Otkazana rezolucija',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Rezolucija u toku',
    // Errors
    'ERR_CALL' => "Ne može se pozvati funkcija: %s",
    'ERR_CURL' => "Nema CURL-a - ne mogu se pokrenuti URL poslovi",
    'ERR_FAILED' => "Neočekivana greška, molimo proverite PHP logove i sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s u %s na liniji %d",
    'ERR_NOUSER' => "Nema ID-a korisnika dodeljenog za posao",
    'ERR_NOSUCHUSER' => "ID korisnika %s nije pronađen",
    'ERR_JOBTYPE' => "Nepoznat tip posla: %s",
    'ERR_TIMEOUT' => "Greška usled isteka vremena",
    'ERR_JOB_FAILED_VERBOSE' => 'Posao %1$s (%2$s) neuspeo u izvršenju CRON-a',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Ne može se učitati zrno sa id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Ne može se pronaći procedura za rutu %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Ekstenzija za ovaj red nije instalirana',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Neka od polja su prazna',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Parametri redosleda poslova',
    'LBL_CONFIG_MAIN_SECTION' => 'Glavna konfiguracija',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman konfiguracija',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP konfiguracija',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs konfiguracija',
    'LBL_CONFIG_SERVERS_TITLE' => 'Pomoć za konfiguraciju redosleda posla',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Glavni odeljak za konfiguraciju.</b></p>
<ul>
    <li>Pokretač:
    <ul>
    <li><i>Standardno</i> - koristi samo jedan proces za radnike.</li>
    <li><i>Paralelno</i> - koristi nekoliko procesa za radnike.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Podrazumevan red</i> - Ovo koristi samo bazu podataka Sugar bez bolo kakvog reda poruka.</li>
    <li><i>Amazon SQS</i> - Usluga jednostavnog redosleda Amazona je distributivna 
    usluga redosleda poruka koju je uveo Amazon.com.
   Ona podržava programirano slanje poruka putem aplikacija za Web uslugu kao način komunikacije putem Interneta. </li>
    <li><i>RabbitMQ</i> - je softver za posredovanje poruka otvorenog izvora (koji se ponekad zove posrednički softver koji je orijentisan na poruke) koji primenjuje Protokol za napredno svrstavanje poruka u redosled (AMQP).</li>
    <li><i>Gearman</i> - je okvir aplikacije otvorenog izvora koji je dizajniran da distribuira odgovarajuće računarske zadatke na više računara, kako bi veći zadaci mogli brže da se urade.</li>
    <li><i>Immediate</i> - Kao podrazumevan redosled ali izvršava zadatke odmah nakon dodavanja.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Pomoć za konfiguraciju Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Odeljak za konfiguraciju Amazon SQS.</b></p>
<ul>
    <li>ID pristupnog koda: <i>Unesite vaš id pristupnog koda za Amazon SQS</i></li>
    <li>Tajni pristupni kod: <i>Unesite vaš tajni pristupni kod za Amazon SQS</i></li>
    <li>Region: <i>Unesite region Amazon SQS servera</i></li>
    <li>Ime redosleda: <i>Unesite ime redosleda Amazon SQS servera</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Pomoć za AMQP konfiguraciju',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Odeljak za AMQP konfiguraciju.</b></p>
<ul>
    <li>URL servera: <i>Unesite vaš URL servera za redosled poruke.</i></li>
    <li>Prijava: <i>Unesite vašu prijavu za RabbitMQ</i></li>
    <li>Lozinka: <i>Unesite vašu lozinku za RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Pomoć za Gearman konfiguraciju',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Odeljak za Gearman konfiguraciju.</b></p>
<ul>
    <li>URL servera: <i>Unesite vaš URL servera za redosled poruke.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Pokretač',
    'LBL_SERVER_URL' => 'URL servera',
    'LBL_LOGIN' => 'Prijava',
    'LBL_ACCESS_KEY' => 'ID pristupnog koda',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Kod tajnog pristupa',
    'LBL_QUEUE_NAME' => 'Ime adaptera',
);
