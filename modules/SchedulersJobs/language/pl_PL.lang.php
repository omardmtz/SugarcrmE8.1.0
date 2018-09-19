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
    'LBL_MODULE_NAME' => 'Kolejka zadań',
    'LBL_MODULE_NAME_SINGULAR' => 'Kolejka zadań',
    'LBL_MODULE_TITLE' => 'Kolejka zadań: strona główna',
    'LBL_MODULE_ID' => 'Kolejka zadań',
    'LBL_TARGET_ACTION' => 'Akcja',
    'LBL_FALLIBLE' => 'Omylny',
    'LBL_RERUN' => 'Uruchom ponownie',
    'LBL_INTERFACE' => 'Interfejs',
    'LINK_SCHEDULERSJOBS_LIST' => 'Wyświetl kolejkę zadań',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguracja',
    'LBL_CONFIG_PAGE' => 'Ustawienia kolejki zadań',
    'LBL_JOB_CANCEL_BUTTON' => 'Anuluj',
    'LBL_JOB_PAUSE_BUTTON' => 'Wstrzymaj',
    'LBL_JOB_RESUME_BUTTON' => 'Wznów',
    'LBL_JOB_RERUN_BUTTON' => 'Zakolejkuj ponownie',
    'LBL_LIST_NAME' => 'Nazwa',
    'LBL_LIST_ASSIGNED_USER' => 'Zlecone przez',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Rozwiązanie',
    'LBL_NAME' => 'Nazwa zadania',
    'LBL_EXECUTE_TIME' => 'Czas wykonania',
    'LBL_SCHEDULER_ID' => 'Harmonogram',
    'LBL_STATUS' => 'Status zadania',
    'LBL_RESOLUTION' => 'Wynik',
    'LBL_MESSAGE' => 'Wiadomości',
    'LBL_DATA' => 'Dane zadania',
    'LBL_REQUEUE' => 'Spróbuj ponownie w przypadku niepowodzenia',
    'LBL_RETRY_COUNT' => 'Maksymalna liczba prób',
    'LBL_FAIL_COUNT' => 'Niepowodzenia',
    'LBL_INTERVAL' => 'Minimalny interwał pomiędzy próbami',
    'LBL_CLIENT' => 'Klient będący właścicielem',
    'LBL_PERCENT' => 'Procent ukończenia',
    'LBL_JOB_GROUP' => 'Grupa zadania',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Zakolejkowane rozwiązanie',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Częściowe rozwiązanie',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Zakończone rozwiązanie',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Nieudane rozwiązanie',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Anulowane rozwiązanie',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Uruchomione rozwiązanie',
    // Errors
    'ERR_CALL' => "Nie można wywołać funkcji: %s",
    'ERR_CURL' => "Brak CURL — nie można uruchomić zadań URL",
    'ERR_FAILED' => "Niepowodzenie, sprawdź logi PHP oraz sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s w %s w linii %d",
    'ERR_NOUSER' => "Nie określono ID użytkownika dla zadania",
    'ERR_NOSUCHUSER' => "Nie odnaleziono ID użytkownika %s",
    'ERR_JOBTYPE' => "Nieznany typ zadania: %s",
    'ERR_TIMEOUT' => "Niepowodzenie z powodu przekroczenia limitu czasowego",
    'ERR_JOB_FAILED_VERBOSE' => 'Zadanie %1$s (%2$s) zakończyło się niepowodzeniem w przebiegu CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nie można załadować pliku bean o identyfikatorze: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nie można znaleźć mechanizmu obsługującego trasę %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Rozszerzenie dla tej kolejki nie jest zainstalowane',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Niektóre pola są puste',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Ustawienia kolejki zadań',
    'LBL_CONFIG_MAIN_SECTION' => 'Główna konfiguracja',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Konfiguracja aplikacji Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Konfiguracja AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Konfiguracja Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Pomoc konfiguracji kolejki zadań',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Główna sekcja konfiguracji.</b></p>
<ul>
<li>Element wykonujący:
<ul>
<li><i>Standardowy</i> — jeden proces dla pracowników.</li>
<li><i>Równoległy</i> — kilka procesów dla pracowników.</li>
</ul>
</li>
<li>Adapter:
<ul>
<li><i>Domyślna kolejka</i> — spowoduje użycie wyłącznie bazy danych systemu Sugar bez kolejki komunikatów.</li>
<li><i>Amazon SQS</i> — Amazon Simple Queue Service to dystrybuowany system kolejkowania komunikatów firmy Amazon.com.
Obsługuje on programowane wysyłanie komunikatów przez aplikacje typu web service jako sposób komunikacji przez Internet.</li>
<li><i>RabbitMQ</i> — to oprogramowanie open source typu broker komunikatów (nazywane czasem oprogramowaniem pośredniczącym zorientowanym na komunikaty), które wdraża protokół Advanced Message Queuing Protocol (AMQP).</li>
<li><i>Gearman</i> — to platforma aplikacyjna open source zaprojektowana do dystrybuowania odpowiednich zadań komputerowych do wielu komputerów, tak aby duże zadania były wykonywane szybciej.</li>
<li><i>Natychmiastowy</i> — tak jak domyślna kolejka, ale wykonuje zadania natychmiast po dodaniu.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Pomoc dla konfiguracji systemu Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Sekcja konfiguracji systemu Amazon SQS.</b></p>
<ul>
<li>ID klucza dostępu: <i>wprowadź numer identyfikacyjny klucza dostępu do usługi Amazon SQS</i></li>
<li>Tajny klucz dostępu: <i>wprowadź swój tajny klucz dostępu do usługi Amazon SQS</i></li>
<li>Region: <i>wprowadź region serwera Amazon SQS</i></li>
<li>Nazwa kolejki: <i>wprowadź nazwę kolejki serwera Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Pomoc dla konfiguracji AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Sekcja konfiguracji AMQP.</b></p>
<ul>
<li>Adres URL serwera: <i>wprowadź adres URL serwera kolejki komunikatów.</i></li>
<li>Login: <i>wprowadź login do systemu RabbitMQ</i></li>
<li>Hasło: <i>wprowadź swoje hasło do systemu RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Pomoc dla konfiguracji systemu Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Sekcja konfiguracji systemu Gearman.</b></p>
<ul>
<li>Adres URL serwera: <i>wprowadź adres URL serwera kolejki komunikatów.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Obiekt wykonujący',
    'LBL_SERVER_URL' => 'Adres URL serwera',
    'LBL_LOGIN' => 'Login',
    'LBL_ACCESS_KEY' => 'Identyfikator klucza dostępu',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Tajny klucz dostępu',
    'LBL_QUEUE_NAME' => 'Nazwa adaptera',
);
