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
    'LBL_MODULE_NAME' => 'Red čekanja posla',
    'LBL_MODULE_NAME_SINGULAR' => 'Red čekanja posla',
    'LBL_MODULE_TITLE' => 'Red čekanja posla: početno',
    'LBL_MODULE_ID' => 'Red čekanja posla',
    'LBL_TARGET_ACTION' => 'Radnja',
    'LBL_FALLIBLE' => 'Pogrešivo',
    'LBL_RERUN' => 'Ponavlj.',
    'LBL_INTERFACE' => 'Sučelje',
    'LINK_SCHEDULERSJOBS_LIST' => 'Prikaži red čekanja posla',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguracija',
    'LBL_CONFIG_PAGE' => 'Postavke reda čekanja posla',
    'LBL_JOB_CANCEL_BUTTON' => 'Odustani',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauziraj',
    'LBL_JOB_RESUME_BUTTON' => 'Nastavi',
    'LBL_JOB_RERUN_BUTTON' => 'Pon. red ček.',
    'LBL_LIST_NAME' => 'Naziv',
    'LBL_LIST_ASSIGNED_USER' => 'Zatražio/la',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Rješenje',
    'LBL_NAME' => 'Naziv posla',
    'LBL_EXECUTE_TIME' => 'Vrijeme izvršavanja',
    'LBL_SCHEDULER_ID' => 'Planer',
    'LBL_STATUS' => 'Status posla',
    'LBL_RESOLUTION' => 'Rezultat',
    'LBL_MESSAGE' => 'Poruke',
    'LBL_DATA' => 'Podaci o poslu',
    'LBL_REQUEUE' => 'Pokušaj ponovno pri neuspjehu',
    'LBL_RETRY_COUNT' => 'Maksimalni ponovni pokušaji',
    'LBL_FAIL_COUNT' => 'Neuspjesi',
    'LBL_INTERVAL' => 'Minimalni interval između pokušaja',
    'LBL_CLIENT' => 'Korisnik vlasnik',
    'LBL_PERCENT' => 'Postotak dovršenosti',
    'LBL_JOB_GROUP' => 'Grupa posla',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Rješenje na čekanju',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Djelomično rješenje',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Potpuno rješenje',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Neuspješno rješenje',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Otkazano rješenje',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Pokrenuto rješenje',
    // Errors
    'ERR_CALL' => "Nije moguće pozvati funkciju: %s",
    'ERR_CURL' => "Nema softvera CURL - nije moguće pokrenuti poslove URL-a",
    'ERR_FAILED' => "Neočekivana pogreška, provjerite zapisnike za PHP i sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s u %s u retku %d",
    'ERR_NOUSER' => "Nema korisničkog ID-a određenog za ovaj posao",
    'ERR_NOSUCHUSER' => "Korisnički ID %s nije pronađen",
    'ERR_JOBTYPE' => "Nepoznata vrsta posla: %s",
    'ERR_TIMEOUT' => "Prisilni prekid zbog prekoračenja vremena",
    'ERR_JOB_FAILED_VERBOSE' => 'Posao %1$s (%2$s) nije uspio pri izvođenju softvera CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nije moguće učitati podatkovno zrno s ID-om: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nije moguće pronaći rukovatelja za rutu %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Nije instalirano proširenje za ovaj red čekanja',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Neka su polja prazna',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Postavke reda čekanja posla',
    'LBL_CONFIG_MAIN_SECTION' => 'Glavna konfiguracija',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Konfiguracija sustava Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Konfiguracija protokola AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Konfiguracija usluge Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Pomoć za konfiguraciju reda čekanja posla',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Odjeljak za glavnu konfiguraciju.</b></p>
<ul>
<li>Pokretač:
<ul>
<li><i>Standardni</i> - upotrebljavajte samo jedan proces za radnike.</li>
<li><i>Paralelni</i> - upotrebljavajte nekoliko procesa za radnike.</li>
</ul>
</li>
<li>Prilagodnik:
<ul>
<li><i>Zadani red čekanja</i> - putem ove opcije upotrebljavat će se samo baza podataka Sugara bez reda čekanja za poruke.</li>
<li><i>Amazon SQS</i> - Amazon Simple Queue Service distribuirana je usluga za slanje poruka s redom čekanja koju je razvio Amazon.com.
Podržava programirano slanje poruka putem aplikacija web-servisa kao način komunikacije putem interneta.</li>
<li><i>RabbitMQ</i> - softver za posredništvo u slanju poruka s otvorenim izvornim kodom (ponekad se naziva i posrednički softver usmjeren na poruke)
koji provodi protokol Advanced Message Queuing Protocol (AMQP).</li>
<li><i>Gearman</i> -okvir za aplikacije s otvorenim izvornim kodom koji je namijenjen distribuciji odgovarajućih računalnih
zadataka većem broju računala, tako da se veći zadaci mogu brže izvršiti.</li>
<li><i>Neposredno</i> - slično zadanom redu čekanja, ali izvršava zadatak odmah nakon dodavanja.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Pomoć za konfiguraciju usluge Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Odjeljak za konfiguraciju usluge Amazon SQS.</b></p>
<ul>
    <li>ID ključa za pristup: <i>unesite ID broj pristupnog ključa za Amazon SQS</i></li>
    <li>Ključ za tajni pristup: <i>unesite svoj ključ za tajni pristup za Amazon SQS</i></li>
    <li>Regija: <i>unesite regiju poslužitelja Amazon SQS</i></li>
    <li>Naziv reda čekanja: <i>unesite naziv reda čekanja za poslužitelj Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Pomoć za konfiguraciju protokola AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Odjeljak za konfiguraciju protokola AMQP.</b></p>
<ul>
    <li>URL poslužitelja: <i>unesite URL poslužitelja za poruke s redom čekanja.</i></li>
    <li>Prijava: <i>unesite prijavu za RabbitMQ</i></li>
    <li>Lozinka: <i>unesite lozinku za RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Pomoć za konfiguraciju sustava Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Odjeljak za konfiguraciju sustava Gearman.</b></p>
<ul>
    <li>URL poslužitelja: <i>unesite URL poslužitelja za poruke s redom čekanja.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Prilagodnik',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Pokretač',
    'LBL_SERVER_URL' => 'URL poslužitelja',
    'LBL_LOGIN' => 'Prijava',
    'LBL_ACCESS_KEY' => 'ID ključa za pristup',
    'LBL_REGION' => 'Regija',
    'LBL_ACCESS_KEY_SECRET' => 'Ključ za tajni pristup',
    'LBL_QUEUE_NAME' => 'Naziv prilagodnika',
);
