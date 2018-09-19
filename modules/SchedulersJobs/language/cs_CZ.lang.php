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
    'LBL_MODULE_NAME' => 'Fronta úloh',
    'LBL_MODULE_NAME_SINGULAR' => 'Fronta úloh',
    'LBL_MODULE_TITLE' => 'Fronta úloh: Domů',
    'LBL_MODULE_ID' => 'Fronta úloh',
    'LBL_TARGET_ACTION' => 'Akce',
    'LBL_FALLIBLE' => 'Nepřesné',
    'LBL_RERUN' => 'Znovu spustit',
    'LBL_INTERFACE' => 'Rozhraní',
    'LINK_SCHEDULERSJOBS_LIST' => 'Zobrazit frontu úloh',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfigurace',
    'LBL_CONFIG_PAGE' => 'Nastavení fronty úloh',
    'LBL_JOB_CANCEL_BUTTON' => 'Zrušit',
    'LBL_JOB_PAUSE_BUTTON' => 'Pozastavit',
    'LBL_JOB_RESUME_BUTTON' => 'Pokračovat',
    'LBL_JOB_RERUN_BUTTON' => 'Znovu zařadit do fronty',
    'LBL_LIST_NAME' => 'Název',
    'LBL_LIST_ASSIGNED_USER' => 'Žadatel',
    'LBL_LIST_STATUS' => 'Stav',
    'LBL_LIST_RESOLUTION' => 'Řešení',
    'LBL_NAME' => 'Název naplánované úlohy',
    'LBL_EXECUTE_TIME' => 'Čas spuštění',
    'LBL_SCHEDULER_ID' => 'Naplánované úlohy',
    'LBL_STATUS' => 'Stav:',
    'LBL_RESOLUTION' => 'Výsledek:',
    'LBL_MESSAGE' => 'Zpráva',
    'LBL_DATA' => 'Data naplánované úlohy',
    'LBL_REQUEUE' => 'Opakovat při chybě',
    'LBL_RETRY_COUNT' => 'Max. počet vstupů',
    'LBL_FAIL_COUNT' => 'Chyby',
    'LBL_INTERVAL' => 'Minimální inteval',
    'LBL_CLIENT' => 'Vlastní klient',
    'LBL_PERCENT' => 'Splněno procent',
    'LBL_JOB_GROUP' => 'Skupina úlohy',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Řešení uloženo do fronty',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Částečné řešení',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Řešení dokončeno',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Řešení se nezdařilo',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Řešení zrušeno',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Řešení probíhá',
    // Errors
    'ERR_CALL' => "Nelze zavolat funkci: %s",
    'ERR_CURL' => "Nelze spustit naplánovanou úlohu pomocí CURL",
    'ERR_FAILED' => "Neočekávaná chyba, zkontrolujte PHP logy a sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s v %s na řádku %d",
    'ERR_NOUSER' => "Žádné uživatelské ID není přiřazeno k této naplánované úloze",
    'ERR_NOSUCHUSER' => "Nebylo nalezeno ID uživatele %s",
    'ERR_JOBTYPE' => "Neznámý typ naplánované úlohy",
    'ERR_TIMEOUT' => "Vynucená chyba z důvodů překročení časového limitu",
    'ERR_JOB_FAILED_VERBOSE' => 'Naplánovaná úloha %1$s (%2$s) se nepodařila spustit',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nejle načíst objekt bean s ID: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nelze najít obslužnou rutinu pro trasu %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Rozšíření pro tuto frontu není naisntalováno',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Některá pole jsou prázdná',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Nastavení fronty úloh',
    'LBL_CONFIG_MAIN_SECTION' => 'Hlavní konfigurace',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Konfigurace Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Konfigurace AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Konfigurace Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Nápověda ke konfiguraci fronty úloh',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Sekce Hlavní konfigurace.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standardní</i> – použijte jen jeden proces pro pracovníky.</li>
    <li><i>Paralelní</i> – použijte několik procesů pro pracovníky.</li>
    </ul>
    </li>
    <li>Adaptér:
    <ul>
    <li><i>Výchozí fronta</i> – Bude používat jen databázi společnosti Sugar, bez front zpráv.</li>
    <li><i>Amazon SQS</i> – Služba Amazon Simple Queue Service je distribuovaná služba zasílání zpráv fronty webu Amazon.com.
    Podporuje programové odesílání zpráv prostřednictvím aplikací webové služby jakožto způsob komunikace
    přes internet.</li>
    <li><i>RabbitMQ</i> – je software pro zasílání zpráv s otevřeným kódem (někdy nazývaný middleware orientovaný na zprávy),
    který využívá protokol AMQP (Advanced Message Queuing Protocol).</li>
    <li><i>Gearman</i> – aplikační rozhraní s otevřeným kódem navržené k distribuci vhodných počítačových
    úloh do více počítačů, aby bylo možné úlohy vykonat rychleji.</li>
    <li><i>Immediate</i> – Jako výchozí fronta, ale spouští úlohy okamžitě po přidání.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Nápověda ke konfiguraci Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Sekce konfigurace Amazon SQS.</b></p>
<ul>
    <li>ID přístupového klíče: <i>Zadejte identifikační číslo vašeho přístupového klíče pro Amazon SQS</i></li>
    <li>Tajný přístupový klíč: <i>Zadejte váš tajný přístupový klíč pro Amazon SQS</i></li>
    <li>Region: <i>Zadejte region serveru Amazon SQS</i></li>
    <li>Název fronty: <i>Zadejte název fronty serveru Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Nápověda ke konfiguraci AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Sekce konfigurace AMQP.</b></p>
<ul>
    <li>Adresa URL serveru: <i>Zadejte adresu URL vašeho serveru fronty zpráv.</i></li>
    <li>Přihlášení: <i>Zadejte svoje přihlašovací údaje pro RabbitMQ</i></li>
    <li>Heslo: <i>Zadejte svoje heslo pro RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Nápověda ke konfiguraci Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Sekce konfigurace Gearman.</b></p>
<ul>
    <li>Adresa URL serveru: <i>Zadejte adresu URL vašeho serveru fronty zpráv.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptér',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'Adresa URL serveru',
    'LBL_LOGIN' => 'Přihlášení',
    'LBL_ACCESS_KEY' => 'ID přístupového klíče',
    'LBL_REGION' => 'Region',
    'LBL_ACCESS_KEY_SECRET' => 'Tajný přístupový klíč',
    'LBL_QUEUE_NAME' => 'Název adaptéru',
);
