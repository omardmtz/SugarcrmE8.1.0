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
    'LBL_MODULE_NAME' => 'Feladat várólista',
    'LBL_MODULE_NAME_SINGULAR' => 'Feladat várólista',
    'LBL_MODULE_TITLE' => 'Feladat várólista: Kezdőoldal',
    'LBL_MODULE_ID' => 'Feladat várólista',
    'LBL_TARGET_ACTION' => 'Művelet',
    'LBL_FALLIBLE' => 'Esés',
    'LBL_RERUN' => 'Újrafuttat',
    'LBL_INTERFACE' => 'Felület',
    'LINK_SCHEDULERSJOBS_LIST' => 'Feladat-várólista megtekintése',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguráció',
    'LBL_CONFIG_PAGE' => 'Feladat-várólista-beállítások',
    'LBL_JOB_CANCEL_BUTTON' => 'Mégsem',
    'LBL_JOB_PAUSE_BUTTON' => 'Szünet',
    'LBL_JOB_RESUME_BUTTON' => 'Folytatás',
    'LBL_JOB_RERUN_BUTTON' => 'Újra várólistába állít',
    'LBL_LIST_NAME' => 'Név',
    'LBL_LIST_ASSIGNED_USER' => 'Kérte:',
    'LBL_LIST_STATUS' => 'Állapot',
    'LBL_LIST_RESOLUTION' => 'Felbontás',
    'LBL_NAME' => 'Munka neve',
    'LBL_EXECUTE_TIME' => 'Végrehajtás ideje',
    'LBL_SCHEDULER_ID' => 'Ütemező',
    'LBL_STATUS' => 'Munka állapota',
    'LBL_RESOLUTION' => 'Eredmény',
    'LBL_MESSAGE' => 'Üzenetek',
    'LBL_DATA' => 'Munka adatai',
    'LBL_REQUEUE' => 'Ismételt próbálkozás hiba esetén',
    'LBL_RETRY_COUNT' => 'Maximum ismételt próbálkozások száma',
    'LBL_FAIL_COUNT' => 'Hibák',
    'LBL_INTERVAL' => 'Az ismételt próbálkozások között eltelt minimális időmennyiség',
    'LBL_CLIENT' => 'Tartozó kliens',
    'LBL_PERCENT' => 'Százalék',
    'LBL_JOB_GROUP' => 'Munkacsoport',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Felbontás várólistára állítva',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Részleges felbontás',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Felbontás befejezve',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Felbontási hiba',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Felbontás törölve',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'A felbontás fut',
    // Errors
    'ERR_CALL' => "Nincs ilyen funkció: %s",
    'ERR_CURL' => "Nincs cURL - az URL nem futtatható",
    'ERR_FAILED' => "Váratlan hiba, ellenőrizze a PHP és a sugarcrm.log fájlokat",
    'ERR_PHP' => "%s [%d]: %s itt: %s a következő soron: %d",
    'ERR_NOUSER' => "Nincs munkafeladatra kiválasztott felhasználó",
    'ERR_NOSUCHUSER' => "Nincs ilyen felhasználói azonosító",
    'ERR_JOBTYPE' => "Ismeretlen munkafeladat: %s",
    'ERR_TIMEOUT' => "Hiba időkorlát túllépése miatt",
    'ERR_JOB_FAILED_VERBOSE' => '%1$s (%2$s) nem fut a cron-on',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nem lehet betölteni a következő azonosítót: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nem található kezelő a következő útvonalra: %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Ehhez a sorhoz nincs telepítve kiterjesztés',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Néhány mező üres',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Feladat-várólista-beállítások',
    'LBL_CONFIG_MAIN_SECTION' => 'Fő konfiguráció',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman konfiguráció',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP konfiguráció',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs konfiguráció',
    'LBL_CONFIG_SERVERS_TITLE' => 'Feladat-várólista konfigurációs súgó',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Fő konfigurációs rész.</b></p>
<ul>
    <li>Futó:
    <ul>
    <li><i>Normál</i> - Csak egy folyamatot használ dolgozókhoz.</li>
    <li><i>Párhuzamos</i> - Több folyamatot használ dolgozókhoz.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Alapértelmezett várólista</i> - Csak a Sugar adatbázisát használja, üzenet nélkül a várólistán.</li>
    <li><i>Amazon SQS</i> - Az Amazon Simple Queue Service egy megosztott sorküldő szolgáltatás, amelyet az Amazon.com vezetett be. Támogatja az üzenetek programatikus küldését webszolgáltató alkalmazásokon keresztül, mint az interneten keresztüli kommunikációs módot.</li>
    <li><i>RabbitMQ</i> - nyílt forráskódú üzenetfelbontó szoftver (más néven üzenetorientált közbenső szoftver), amely az Advanced Message Queuing Protocol (AMQP) használatával működik.</li>
    <li><i>Gearman</i> - nyílt forráskódú alkalmazás-keretterv, amelynek célja úgy felosztani a megfelelő számítógépes feladatokat több számítógép között, hogy a nagy feladatok gyorsabban elvégezhetőek legyenek</li>
    <li><i>Immediate</i> - Mint az alapértelmezett várólista, de hozzáadás után azonnal végrehajtja a feladatokat.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS konfigurációs súgó',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS konfigurációs rész.</b></p>
<ul>
    <li>Hozzáférési kulcs azonosító: <i>Adja meg hozzáférési kulcs azonosítószámát az Amazon SQS-hez</i></li>
    <li>Titkos hozzáférési kulcs: <i>Adja meg titkos hozzáférési kulcsát az Amazon SQS-hez</i></li>
    <li>Régió: <i>Adja meg az Amazon SQS szerver régióját</i></li>
    <li>Várólista neve: <i>Adja meg az Amazon SQS szerver várólista-nevét</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP konfigurációs súgó',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP Configuration Section.</b></p>
<ul>
    <li>Server URL: <i>Enter your message queue server's URL.</i></li>
    <li>Login: <i>Enter your login for RabbitMQ</i></li>
    <li>Password: <i>Enter your password for RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman konfigurációs súgó',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman konfigurációs rész</b></p>
<ul>
    <li>Szerver URL: <i>Adja meg az üzenet várólista szerver URL-címét.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Futó',
    'LBL_SERVER_URL' => 'Szerver URL-címe',
    'LBL_LOGIN' => 'Felhasználónév',
    'LBL_ACCESS_KEY' => 'Hozzáférési kulcs azonosító',
    'LBL_REGION' => 'Régió',
    'LBL_ACCESS_KEY_SECRET' => 'Titkos hozzáférési kulcs',
    'LBL_QUEUE_NAME' => 'Adapter neve',
);
